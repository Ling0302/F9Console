<?php
/*
 * Utit_model
 */
class Util_model extends CI_Model {

	private $_minerdSoftware;

	public function __construct()
	{
		parent::__construct();
	}

	public function isLoggedIn() 
	{
		return true;
	}
	
	public function switchMinerSoftware($software = false, $network = false)
	{
		return true;
	}

	// Get the mac address
	public function getMacAddr()
	{
		return "aa:3d:de:23:ed:fe";
	}

	public function getIfconfig()
	{
		$a = new stdClass();
		$a->ip = "192.168.0.10";
		$a->gw = "192.168.0.1";
		$a->mask = "255.255.255.255";
		$a->dns = "114.114.114.114";
		$a->dhcp = 'dhcp';

		return $a;
	}
	
	// Get the stored stats from Redis
	public function getHistoryStats($type = "hourly")
	{	
		switch ($type)
		{
			case "hourly":
				$period = 300;
				$range = 12;
				$avg = false;
			break;
			case "daily":
				$period = 3600;
				$range = 24;
				$avg = 300;
			break;
			case "weekly":
				$period = 3600*24;
				$range = 7;
				$avg = 3600;
			break;
			case "monthly":
				$period = 3600*24;
				$range = 30;
				$avg = 3600;
			break;
			case "yearly":
				$period = 3600*24*14;
				$range = 27;
				$avg = 86400;
			break;
		}
		
		$items = array();

		for ($i=0;$i<=($range*$period);$i+=$period)
		{
			$statTime = (time()-$i);
			$item = json_decode($this->avgStats($period, $statTime, $avg));
			if ($item)
				$items[] = $item;
		}

		$o = json_encode($items);
		
		return $o;
	}
	
	public function avgStats($seconds = 900, $startTime = false, $avg = false)
	{
		$records = $this->getStoredStats($seconds, $startTime, $avg);
		
		$i = 0; $timestamp = 0; $poolHashrate = 0; $hashrate = 0; $frequency = 0; $accepted = 0; $errors = 0; $rejected = 0; $shares = 0;
		
		if (count($records) > 0)
		{
			foreach ($records as $record)
			{
				$i++;
				$obj = json_decode($record);
				$timestamp += (isset($obj->timestamp)) ? $obj->timestamp : 0;
				$poolHashrate += (isset($obj->pool_hashrate)) ? $obj->pool_hashrate : 0;
				$hashrate += (isset($obj->hashrate)) ? $obj->hashrate : 0;
				$frequency += (isset($obj->avg_freq)) ? $obj->avg_freq : 0;
				$accepted += (isset($obj->accepted)) ? $obj->accepted : 0;
				$errors += (isset($obj->errors)) ? $obj->errors : 0;
				$rejected += (isset($obj->rejected)) ? $obj->rejected : 0;
				$shares += (isset($obj->shares)) ? $obj->shares : 0;
			}
			
			$timestamp = round(($timestamp/$i), 0);
			$poolHashrate = round(($poolHashrate/$i), 0);
			$hashrate = round(($hashrate/$i), 0);
			$frequency = round(($frequency/$i), 0);
			$accepted = round(($accepted/$i), 0);
			$errors = round(($errors/$i), 0);
			$rejected =round(($rejected/$i), 0);
			$shares =round(($shares/$i), 0);
		}

		$o = false;
		if ($timestamp)
		{
			$o = array(
				"timestamp" => $timestamp,
				"seconds" => $seconds,
				"pool_hashrate" => $poolHashrate,
				"hashrate" => $hashrate,
				"frequency" => $frequency,
				"accepted" => $accepted,
				"errors" => $errors,
				"rejected" => $rejected,
				"shares" => $shares
			);
		}
		
		return json_encode($o);
		
	}
	
	function getMineraPoolUser()
	{
		return $this->config->item('minera_pool_username');
	}

	function setPools($pools)
	{
		return true;
	}

	function getPools()
	{
		$confContent = file_get_contents($this->config->item("minerd_conf_file"));
		$minerd_pools = json_decode($confContent, JSON_FORCE_OBJECT);
		$this->setPools($minerd_pools['pools']);
		
		return json_encode($minerd_pools['pools']);
	}
	
	function parsePools($pools)
	{
		$poolsArray = array();
		
		if (is_array($pools)) {
			foreach ($pools as $pool)
			{
				$poolsArray[] = array("url" => $pool['url'], "user" => $pool['username'], "pass" => $pool['password']);	
			}
		}
		
		return  $poolsArray;
	}
		
	// Check if pool is alive
	public function checkPool($url)
	{	
		$parsedUrl = @parse_url($url);

		if (isset($parsedUrl['host']) && isset($parsedUrl['port']))
		{
			$conn = @fsockopen($parsedUrl['host'], $parsedUrl['port'], $errno, $errstr, 1);
			if (is_resource($conn))
			{
				fclose($conn);
				return true;
			}
		}		
		
		return false;
	}

	public function isOnline($network = false)
	{
		return true;
	}
	
	// Check RPi temp
	public function checkTemp($device)
	{
		$d = 0;
		$c = 0;
		
		foreach ($device as $key=>$val) {
			$d += $val->temperature;
			if ($val->temperature > 0){
				$c ++;
			}	
		}
		if ($c == 0) {
			return 0;
		}

		
		return $d/intval($c);
	}

	public function saveNetwork($type,$ip,$mask,$gw,$dns)
	{
		$data['message'] = 'Success! IP saved!';
		$data['message_type'] = "success";
		return json_encode($data);
	}

	public function reboot()
	{
		return true;
	}
	
	public function tailFile($filename, $lines) {
		$file = file(FCPATH.APPPATH."logs/".$filename);
		
		if (count($file) > 0) {
			for ($i = count($file)-$lines; $i < count($file); $i++) {
				if ($i >= 0 && $file[$i]) {
					$readlines[] = $file[$i] . "\n";
				}
			}
		} else {
			$readlines = array('No logs found');
		}
		
		return $readlines;
	}
			
	// Stop miner
	public function minerStop()
	{			
		return true;
	}

	// restart cgminer
	public function restartCgminer()
	{
		return true;
	}
	
	// Start miner
	public function minerStart()
	{
		return true;
	}
	
	// Restart minerd
	public function minerRestart()
	{	
		$this->minerStop();
		$this->minerStart();
		
		return true;
	}
	
	public function reset($action)
	{
		switch($action)
		{					
		    case "charts":
				$o = json_encode(array("success" => true));
		    break;
			case "options":
				$o = json_encode(array("success" => true));
		    break;
		    case "logs":
				array_map('unlink', glob("application/logs/*"));
				$o = json_encode(array("success" => true));
		    break;
		    default:
		    	$o = json_encode(array("err" => true));
		}
		
		return $o;
	}
	
	public function generateMineraId()
	{
		return $this->config->item('system_id');
	}

	public function checkNetworkDevice($ip, $port=4028) 
	{		
		$connection = @fsockopen($ip, 4028, $errno, $errstr, 5);
	    if (is_resource($connection))
	    {	
	        fclose($connection);
	        
	        return true;
	    }
	    
	    return false;
	}
	
	public function convertHashrate($hash)
	{
		if ($hash > 900000000)
			return round($hash/1000000000, 2) . 'Gh/s';
		elseif ($hash > 900000)
			return round($hash/1000000, 2) . 'Mh/s';
		elseif ($hash > 900)
			return round($hash/1000, 2) . 'Kh/s';
		else
			return $hash;
	}

	public function getSysUptime()
	{
		return "109902";
	}

	public function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public function getFirmwareVersion()
	{
		return '20220401:2330';
	}

	public function getAuditLog() {
		$data = "";
		$handle = fopen(FCPATH.'data/cfg_modify_log', "r");
	
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$data .= $line;
			}
			fclose($handle);
		}
		$rawLogs = array_filter(explode("----------------------------------------------",trim($data)));
		$logs = [];
		foreach($rawLogs as $val)
		{
			$tmp1 = $tmp2 = [];
			if(stripos($val,'poweron') !== false)
			{
				// 开机操作
				$tmp1 = [date('Y-m-d H:i:s', strtotime(str_replace('poweron','',$val))), '开机', ''];
				$logs[] = $tmp1;
			} else 
			{
				// 切换矿池、矿工操作
				$time = date('Y-m-d H:i:s', strtotime(explode("\n",trim($val))[0]));
	
				preg_match('/\{(.*)\}/s', $val, $matches);
				$rel_data = json_decode($matches[0], true);
	
				$pool_remark = "矿池地址变更为".$rel_data['pools'][0]['url'].",".$rel_data['pools'][1]['url'].",".$rel_data['pools'][2]['url'];
				$worker_remark = "矿工账号变更为".$rel_data['pools'][0]['user'].",".$rel_data['pools'][1]['user'].",".$rel_data['pools'][2]['user'];
	
				$tmp1 = [$time, '变更矿池', $pool_remark];
				$tmp2 = [$time, '变更矿工', $worker_remark];
	
				$logs[] = $tmp1;
				$logs[] = $tmp2;
	
			}
		}
		return $logs;
	}
}

?>