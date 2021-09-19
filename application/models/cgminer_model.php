<?php
/*
 * CGminer model
 * @author michael
 */
class Cgminer_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	function getsock($addr, $port)
	{
		$socket = null;
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket === false || $socket === null)
		{
			$error = socket_strerror(socket_last_error());
			$msg = "socket create(TCP) failed";
			log_message("error", "$msg '$error'");
			return null;
		}

		$res = socket_connect($socket, $addr, $port);
		if ($res === false)
		{
			$error = socket_strerror(socket_last_error());
			$msg = "socket connect($addr,$port) failed";
			log_message("error", "$msg '$error'");
			socket_close($socket);
			return null;
		}

		return $socket;
	}
	
	function readsockline($socket)
	{
		$line = '';
		while (true)
		{
			$byte = @socket_read($socket, 1);
			if ($byte === false || $byte === '')
				break;
			if ($byte === "\0")
				break;
			$line .= $byte;
		}
		return $line;
	}
	
	
	function callMinerd($cmd = false, $network = false)
	{
		if (!$cmd)
			$cmd = 'summary+devs+pools+estats';
		$c['command'] = $cmd;

		// Setup socket
		$client = @stream_socket_client('tcp://127.0.0.1:4028', $errno, $errorMessage);

		// Socket failed
		if ($client === false) {
			return array('type' => 'error', 'text' => 'Miner: '.$errno.' '.$errorMessage);
		}
		// Socket success
		else{
			fwrite($client, json_encode($c));
			$response = stream_get_contents($client);
			fclose($client);
			
			// Cleanup json
			$response = preg_replace('/[^[:alnum:][:punct:]]/','',$response);

			// Add api response
			return json_decode($response);	
		}	
		
	}
	
	public function selectPool($poolId, $network) {
		log_message("error", "Trying to switch pool ".(int)$poolId." to the main one. (".$network.")");
		$o = $this->callMinerd('{"command":"switchpool", "parameter":'.(int)$poolId.'}', $network);
		log_message("error", var_export($o, true));
		return $o;
	}
	
	public function addPool($url, $user, $pass, $network) {
		log_message("error", "Trying to add pool parameter:".$url.",".$user.",".$pass." (".$network.")");
		$o = $this->callMinerd('{"command":"addpool", "parameter":"'.$url.','.$user.','.$pass.'"}', $network);
		log_message("error", var_export($o, true));
		return $o;
	}
	
	public function removePool($poolId, $network) {
		log_message("error", "Trying to remove pool ".(int)$poolId." (".$network.")");
		$o = $this->callMinerd('{"command":"removepool", "parameter":'.(int)$poolId.'}', $network);
		log_message("error", var_export($o, true));
		return $o;
	}
	
}
