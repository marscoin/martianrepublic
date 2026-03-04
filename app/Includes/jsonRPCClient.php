<?php
namespace App\Includes;
use Exception;  
/*
  				COPYRIGHT

Copyright 2007 Sergio Vaccaro <sergio@inservibile.org>

This file is part of JSON-RPC PHP.

JSON-RPC PHP is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

JSON-RPC PHP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JSON-RPC PHP; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The object of this class are generic jsonRPC 1.0 clients
 * http://json-rpc.org/wiki/specification
 *
 * @author sergio <jsonrpcphp@inservibile.org>
 */
class jsonRPCClient {
    private $debug;
    private $url;
    private $id;
    private $notification = false;
    private $proxy = ''; // Add proxy property

    public function __construct($url, $debug = false) {
        $this->url = $url;
        $this->debug = empty($debug) ? false : true;
        $this->id = 1;
    }


	/**
	 * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
	 *
	 * @param boolean $notification
	 */
	public function setRPCNotification($notification) {
		empty($notification) ?
							$this->notification = false
							:
							$this->notification = true;
	}

	/**
	 * Performs a jsonRCP request and gets the results as an array
	 *
	 * @param string $method
	 * @param array $params
	 * @return array
	 */
	public function __call($method,$params) {

		// check
		if (!is_scalar($method)) {
			throw new Exception('Method name has no scalar value');
		}

		// check
		if (is_array($params)) {
			// no keys
			$params = array_values($params);
		} else {
			throw new Exception('Params must be given as array');
		}

		// sets notification or request task
		if ($this->notification) {
			$currentId = NULL;
		} else {
			$currentId = $this->id;
		}

		// prepares the request (method name must be lowercase for Marscoin v28+)
		$request = array(
						'jsonrpc' => '1.0',
						'method' => strtolower($method),
						'params' => $params,
						'id' => $currentId
						);
		$request = json_encode($request);
		$this->debug && $this->debug.='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";

		// performs the HTTP POST using curl (compatible with Marscoin v28+)
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Connection: close'
		));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

		// Extract credentials from URL for Basic Auth
		$urlParts = parse_url($this->url);
		if (isset($urlParts['user']) && isset($urlParts['pass'])) {
			curl_setopt($ch, CURLOPT_USERPWD, $urlParts['user'] . ':' . $urlParts['pass']);
		}

		$responseRaw = curl_exec($ch);
		$curlError = curl_error($ch);
		curl_close($ch);

		if ($responseRaw === false || empty($responseRaw)) {
			throw new Exception('Unable to connect to '.$this->url.($curlError ? ': '.$curlError : ''));
		}

		$this->debug && $this->debug.='***** Server response *****'."\n".$responseRaw.'***** End of server response *****'."\n";
		$response = json_decode($responseRaw, true);

		if (!is_array($response)) {
			throw new Exception('Invalid JSON response from server');
		}

		// debug output
		if ($this->debug) {
			echo nl2br($this->debug);
		}

		// final checks and return
		if (!$this->notification) {
			// check
			if (isset($response['id']) && $response['id'] != $currentId) {
				throw new Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
			}
			if (isset($response['error']) && !is_null($response['error'])) {
				$errorMsg = is_array($response['error']) ? json_encode($response['error']) : $response['error'];
				throw new Exception('Request error: '.$errorMsg);
			}

			return $response['result'];

		} else {
			return true;
		}
	}
}
?>