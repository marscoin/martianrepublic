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
			$json = $Marscoind->getinfo();
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
    		$json = AppHelper::file_get_contents_curl('https://pebas.marscoin.org/api/mars/utxo?sender_address=MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz&receiver_address=MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz&amount=1');
    		$a = json_decode($json, true);
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
        $lastProcessed = $this->getLastProcessedTimestamp();
		Log::debug("Blocktracker: " . $lastProcessed);
		Log::debug("Time now: " .  Carbon::now()->setTimezone('America/New_York'));
        if ($lastProcessed->diffInMinutes(Carbon::now()->setTimezone('America/New_York')) <= 15) {
			Log::debug("Blocktracker: success");
            $blockchain_tracker_status = "success";
        } else {
			Log::debug("Blocktracker: danger");
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

	function checkBallotServer($host = 'martianrepublic.org', $port = 3678) {
		$socket = @fsockopen('ssl://' . $host, $port, $errno, $errstr, 5);
		
		if ($socket) {
			fclose($socket);
			return "success";
		}
		
		return "danger";
	}


    private function getLastProcessedTimestamp() {
        $lastLog = DB::table('feed_log')->latest('processed_at')->first();
		Log::debug("lP: " . $lastLog->processed_at);
        return Carbon::parse($lastLog->processed_at);
    }



}


?>