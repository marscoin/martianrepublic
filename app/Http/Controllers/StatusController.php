<?php

namespace App\Http\Controllers;

use App\Includes\AppHelper;
use App\Includes\jsonRPCClient;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class StatusController extends Controller
{
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    public function __construct() {}

    protected function showStatus()
    {
        // Cache status results for 60 seconds to avoid slow RPC calls on every page load
        $cached = Cache::remember('status_page_data', 60, function () {
            return $this->collectStatusData();
        });

        $view = View::make('status');
        $view->web_status = $cached['web_status'];
        $view->mysql_status = $cached['mysql_status'];
        $view->marscoind_status = $cached['marscoind_status'];
        $view->network = $cached['network'];
        $view->blockexplorer = $cached['blockexplorer'];
        $view->pebas_status = $cached['pebas_status'];
        $view->ipfs_status = $cached['ipfs_status'];
        $view->blockchain_tracker_status = $cached['blockchain_tracker_status'];
        $view->ballot_server_status = $cached['ballot_server_status'];

        return $view;
    }

    protected function collectStatusData()
    {
        $web_status = 'danger';
        $mysql_status = 'danger';
        $marscoind_status = 'danger';

        $blockexplorer = 'danger';
        $pebas_status = 'danger';
        $ipfs_status = 'danger';

        // apache webserver check
        try {
            $a = AppHelper::file_get_contents_curl('http://127.0.0.1');
            if ($a) {
                $web_status = 'success';
            } else {
                $web_status = 'danger';
            }
        } catch (Exception $e) {
            $web_status = 'danger';
        }

        // Test database connection
        try {
            DB::connection()->getPdo();
            $mysql_status = 'success';
        } catch (Exception $e) {
            $mysql_status = 'danger';
        }

        try {
            $data = json_decode(file_get_contents('/home/mars/constitution/marswallet.json'), true);
            $RPC_Host = $data['rpc_host'];
            $RPC_Port = $data['rpc_port'];
            $RPC_User = $data['rpc_user'];
            $RPC_Pass = $data['rpc_pass'];

            $nu92u5p9u2np8uj5wr = 'http://'.$RPC_User.':'.$RPC_Pass.'@'.$RPC_Host.':'.$RPC_Port.'/';
            $Marscoind = new jsonRPCClient($nu92u5p9u2np8uj5wr);
            $network = $Marscoind->getNetworkInfo();

            // Also fetch blockchain info for blocks/difficulty (separate RPC call in v28+)
            try {
                $blockchain = $Marscoind->getBlockchainInfo();
                $network['blocks'] = $blockchain['blocks'] ?? 0;
                $network['difficulty'] = $blockchain['difficulty'] ?? 0;
            } catch (Exception $e) {
                $network['blocks'] = 0;
                $network['difficulty'] = 0;
            }

            // Check specific fields in the response
            $marscoind_status = (isset($network['version']) && isset($network['protocolversion'])) ? 'success' : 'danger';

        } catch (Exception $e) {
            Log::debug('Exception caught: '.$e->getMessage());
            $marscoind_status = 'danger';
            $network = [];
        }

        // blockexplorer check
        try {
            $json = AppHelper::file_get_contents_curl(config('blockchain.explorer.primary_url').'/api/status?q=getInfo');
            $a = json_decode($json, true);
            if ($a) {
                $blockexplorer = 'success';
            } else {
                $blockexplorer = 'danger';
            }
        } catch (Exception $e) {
            $blockexplorer = 'danger';
        }

        // pebas api check
        try {
            $json = AppHelper::file_get_contents_curl(config('blockchain.pebas.public_url').'/api/mars/utxo?sender_address=MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz&receiver_address=MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz&amount=0.1');
            $a = json_decode($json, true);
            // Log::debug($a);
            if ($a) {
                $pebas_status = 'success';
            } else {
                $pebas_status = 'danger';
            }
        } catch (Exception $e) {
            $pebas_status = 'danger';
        }

        // ipfs node check
        try {
            $a = AppHelper::file_get_contents_curl(config('blockchain.ipfs.api_url').'/api/v0/swarm/peers');
            if ($a) {
                $ipfs_status = 'success';
            } else {
                $ipfs_status = 'danger';
            }
        } catch (Exception $e) {
            $ipfs_status = 'danger';
        }

        // Check the blockchain tracker status
        // In your check method
        $lastProcessed = $this->getLastProcessedTimestamp();
        $now = Carbon::now(); // Use same timezone as database

        Log::debug('Last Processed: '.$lastProcessed);
        Log::debug('Now: '.$now);
        Log::debug('Diff in minutes: '.$lastProcessed->diffInMinutes($now));

        if ($lastProcessed->diffInMinutes($now) <= 15) {
            Log::debug('Success - diff is under 15 mins');
            $blockchain_tracker_status = 'success';
        } else {
            Log::debug('Danger - diff is over 15 mins');
            $blockchain_tracker_status = 'danger';
        }

        // Check the ballot shuffle server status
        $ballot_server_status = $this->checkBallotServer();

        return [
            'web_status' => $web_status,
            'mysql_status' => $mysql_status,
            'marscoind_status' => $marscoind_status,
            'network' => $network,
            'blockexplorer' => $blockexplorer,
            'pebas_status' => $pebas_status,
            'ipfs_status' => $ipfs_status,
            'blockchain_tracker_status' => $blockchain_tracker_status,
            'ballot_server_status' => $ballot_server_status,
        ];
    }

    public function getSystemStatus()
    {
        $response = [
            'ipfs_status' => 'offline',
            'blockchain_tracker_status' => 'offline',
            'ballot_server_status' => 'offline',
        ];

        // IPFS node check
        try {
            $a = AppHelper::file_get_contents_curl(config('blockchain.ipfs.api_url').'/api/v0/swarm/peers');
            $response['ipfs_status'] = $a ? 'online' : 'offline';
        } catch (Exception $e) {
            $response['ipfs_status'] = 'offline';
        }

        // Blockchain tracker status
        try {
            $lastProcessed = $this->getLastProcessedTimestamp();
            $now = Carbon::now();
            $response['blockchain_tracker_status'] = $lastProcessed->diffInMinutes($now) <= 15 ? 'online' : 'offline';
        } catch (Exception $e) {
            $response['blockchain_tracker_status'] = 'offline';
        }

        // Ballot server status
        $response['ballot_server_status'] = $this->checkBallotServer();

        return response()->json($response);
    }

    private function checkBallotServer($host = null, $port = null)
    {
        $host = $host ?? config('blockchain.ballot.host', '127.0.0.1');
        $port = $port ?? config('blockchain.ballot.port', 3678);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);
        $socket = @stream_socket_client('ssl://'.$host.':'.$port, $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);
        if ($socket) {
            fclose($socket);

            return 'success';
        }

        return 'danger';
    }

    private function getLastProcessedTimestamp()
    {
        $lastLog = DB::table('feed_log')->latest('processed_at')->first();

        return Carbon::createFromFormat('Y-m-d H:i:s', $lastLog->processed_at, 'America/New_York')
            ->setTimezone('UTC');
    }
}
