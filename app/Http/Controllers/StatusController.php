<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Includes\jsonRPCClient;
use App\Includes\AppHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller {

/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

	public function __construct() {
	}



	protected function showStatus()
	{

		$web_status = "danger";
		$mysql_status = "danger";
		$marscoind_status = "danger";

		$blockexplorer = "danger";
		$pebas_status = "danger";
		$ipfs_status = "danger";



		//apache webserver check
		try {
    		$a = AppHelper::file_get_contents_curl('http://127.0.0.1');
    		if($a)
    			$web_status = "success";
    		else $web_status = "danger";
		}
		catch (Exception $e) {
		    $web_status = "danger";
		}


		// Test database connection
		try {
		    DB::connection()->getPdo();
		    $mysql_status = "success";
		} catch (\Exception $e) {
		    $mysql_status = "danger";
		}


		try {
			$data = json_decode(file_get_contents("/home/mars/constitution/marswallet.json"), true);
			$RPC_Host = $data['rpc_host'];          // host for marscoin rpc
			$RPC_Port = $data['rpc_port'];              // port for marscoin rpc
			$RPC_User = $data['rpc_user'];      // username for marscoin rpc
			$RPC_Pass = $data['rpc_pass'];     // password for marscoin rpc

			$nu92u5p9u2np8uj5wr = "http://" . $RPC_User . ":" . $RPC_Pass . "@" . $RPC_Host . ":" . $RPC_Port . "/";
			$Marscoind = new jsonRPCClient($nu92u5p9u2np8uj5wr);
			Log::debug($Marscoind);
			$json = $Marscoind->getNetworkInfo	();
			Log::debug($json);
			$network = $json;
			if($a)
    			$marscoind_status = "success";
    		else $marscoind_status = "danger";

    		if($network && count($network) > 0)
    			$marscoind_status = "success";
    		else
    			$marscoind_status = "danger";

		} catch (\Exception $e) {
		    $marscoind_status = "danger";
		    $network = array();
		}
		

		//blockexplorer check
		try {
    		$json = AppHelper::file_get_contents_curl('http://explore.marscoin.org/api/status?q=getInfo');
    		$a = json_decode($json, true);
    		if($a)
    			$blockexplorer = "success";
    		else $blockexplorer = "danger";
		}
		catch (Exception $e) {
		    $blockexplorer = "danger";
		}


		//pebas api check
		try {
    		$json = AppHelper::file_get_contents_curl('https://pebas.marscoin.org/api/mars/utxo?sender_address=MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz&receiver_address=MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz&amount=0.1');
    		$a = json_decode($json, true);
			Log::debug($a);
    		if($a)
    			$pebas_status = "success";
    		else $pebas_status = "danger";
		}
		catch (Exception $e) {
		    $pebas_status = "danger";
		}


		
		//ipfs node check
		try {
    		$a = AppHelper::file_get_contents_curl('http://127.0.0.1:5001/api/v0/swarm/peers');
    		if($a)
    			$ipfs_status = "success";
    		else $ipfs_status = "danger";
		}
		catch (Exception $e) {
		    $ipfs_status = "danger";
		}


		// Check the blockchain tracker status
		// In your check method
		$lastProcessed = $this->getLastProcessedTimestamp();
		$now = Carbon::now(); // Use same timezone as database

		Log::debug("Last Processed: " . $lastProcessed);
		Log::debug("Now: " . $now);
		Log::debug("Diff in minutes: " . $lastProcessed->diffInMinutes($now));

		if ($lastProcessed->diffInMinutes($now) <= 15) {
			Log::debug("Success - diff is under 15 mins");
			$blockchain_tracker_status = "success";
		} else {
			Log::debug("Danger - diff is over 15 mins");
			$blockchain_tracker_status = "danger";
		}


		// Check the ballot shuffle server status
		$ballot_server_status = $ballot_server_status = $this->checkBallotServer();

		$view = View::make('status');

		$view->web_status = $web_status;
		$view->mysql_status = $mysql_status;
		$view->marscoind_status = $marscoind_status;
		$view->network = $network;
		$view->blockexplorer = $blockexplorer;
		$view->pebas_status = $pebas_status;
		$view->ipfs_status = $ipfs_status;
		$view->blockchain_tracker_status = $blockchain_tracker_status;
		$view->ballot_server_status = $ballot_server_status;

		return $view;
	}


	public function getSystemStatus() {
        $response = [
            'ipfs_status' => 'offline',
            'blockchain_tracker_status' => 'offline',
            'ballot_server_status' => 'offline'
        ];

        // IPFS node check
        try {
            $a = AppHelper::file_get_contents_curl('http://127.0.0.1:5001/api/v0/swarm/peers');
            $response['ipfs_status'] = $a ? 'online' : 'offline';
        } catch (\Exception $e) {
            $response['ipfs_status'] = 'offline';
        }

        // Blockchain tracker status
        try {
            $lastProcessed = $this->getLastProcessedTimestamp();
            $now = Carbon::now();
            $response['blockchain_tracker_status'] = $lastProcessed->diffInMinutes($now) <= 15 ? 'online' : 'offline';
        } catch (\Exception $e) {
            $response['blockchain_tracker_status'] = 'offline';
        }

        // Ballot server status
        $response['ballot_server_status'] = $this->checkBallotServer();

        return response()->json($response);
    }

    private function checkBallotServer($host = 'martianrepublic.org', $port = 3678) {
        $socket = @fsockopen('ssl://' . $host, $port, $errno, $errstr, 5);
        if ($socket) {
            fclose($socket);
            return "success";
        }
        return "danger";
    }

    private function getLastProcessedTimestamp() {
        $lastLog = DB::table('feed_log')->latest('processed_at')->first();
        return Carbon::createFromFormat('Y-m-d H:i:s', $lastLog->processed_at, 'America/New_York')
                    ->setTimezone('UTC');
    }



}


?>