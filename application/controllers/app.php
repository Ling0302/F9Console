<?php if (!defined('BASEPATH')) die();

class App extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		self::__initLanguage();

		date_default_timezone_set('Asia/Shanghai');

	}

	private function __initLanguage(){

		$lang_support = config_item('support_language');
		$lang_url    = $this->uri->segment(1);

		$lang_default = config_item('language');
		$lang_current = empty($this->session->userdata("language")) ?  $lang_default : $this->session->userdata("language");

		$this->config->set_item('language', $lang_current);
		$this->lang->load('app', $lang_current);
		$this->load->helper('language');
		return;
	}

	/*
	 * switch lanuage
	 */
    public function switchLanguage()
    {
		$lang_default = config_item('language');

        $lang_switch  = empty($this->input->get('lang')) ? $lang_default : $this->input->get('lang');
		$lang_current = empty($this->session->userdata("language")) ?  $lang_default : $this->session->userdata("language");
		
		$this->session->set_userdata("language", $lang_switch);
		$this->lang->load('app', $lang_switch);

        redirect('app/index'); // redirect to index
    }
	
	/*
	// Index/lock screen controller
	*/
	public function index()
	{
		$miner_pools = $this->config->item('minerd_pools');
		$pools = $this->util_model->getPools();
		
		$data['now'] = time();
		$data['minera_system_id'] = $this->config->item('system_id');
		$data['minera_version'] = 'dummyVersion';
		$data['browserMining'] = '';
		$data['browserMiningThreads'] = '';
		$data['env'] = $this->config->item('ENV');
		$data['sectionPage'] = 'lockscreen';
		$data['htmlTag'] = "lockscreen";
		$data['firmwareVersion'] = $this->util_model->getFirmwareVersion();
		$data['isOnline'] = $this->util_model->isOnline();

		$this->load->view('include/header', $data);
		$this->load->view('lockscreen');
		$this->load->view('include/footer', $data);
	}
	
	/*
	// Login controller
	*/
	public function login()
	{	
		redirect('app/dashboard');
	}

	/*
	// Logout controller
	*/
	public function logout()
	{	
		$this->session->set_userdata("loggedin", null);
		redirect('app/index'); // redirect to index
	}
	
	/*
	// Dashboard controller
	*/
	public function dashboard()
	{
		
		//var_export($this->redis->command("HGETALL box_status"));
		// $boxStatuses = json_decode($this->redis->get("box_status"), true);

		$data['boxStatuses'] = array();
		if (isset($boxStatuses)) {
			$data['boxStatuses'] = $boxStatuses;
		}

		$data['now'] = time();
		$data['sectionPage'] = 'dashboard';
		$data['minerdPools'] = json_decode($this->util_model->getPools());
		$data['isOnline'] = $this->util_model->isOnline();
		$data['htmlTag'] = "dashboard";
		$data['appScript'] = true;
		$data['settingsScript'] = false;
		$data['mineraUpdate'] = false;
		$data['dashboardDevicetree'] =  true;
		$data['dashboardSkin'] = "black";
		$data['minerdRunning'] = '';
		$data['minerdRunningUser'] = '';
		$data['browserMining'] = '';
		$data['browserMiningThreads'] = '';
		$data['minerdSoftware'] = '';
		$data['netMiners'] = [];
		$data['localAlgo'] = "SHA-256";
		$data['env'] = $this->config->item('ENV');
		$data['mineraSystemId'] = $this->config->item('system_id');
		
		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('frontpage', $data);
		$this->load->view('include/footer', $data);
	}

    public function audit()
	{
		$this->util_model->isLoggedIn();
		
		$data['now'] = time();
		$data['sectionPage'] = 'audit';
		$data['isOnline'] = $this->util_model->isOnline();
		$data['htmlTag'] = "audit";
		$data['chartsScript'] = true;
		$data['appScript'] = false;
		$data['settingsScript'] = false;
		$data['mineraUpdate'] = false;
		$data['minerdLog'] = '';
		$data['dashboardSkin'] = "black";
		$data['dashboardDevicetree'] = false;
		$data['browserMining'] = '';
		$data['browserMiningThreads'] = '';
		$data['env'] = $this->config->item('ENV');
		$data['mineraSystemId'] = $this->config->item('system_id');
		$data['logs'] = $this->util_model->getAuditLog();
		
		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('audit', $data);
		$this->load->view('include/footer');
	}

	/*
	// Settings controller
	*/
	public function settings()
	{
		
		$data['now'] = time();
		$data['sectionPage'] = 'settings';

		$data['message'] = false;
		$data['message_type'] = false;

		if ($this->input->post('save_password'))
		{
			$password = trim($this->input->post('password'));
			$password2 = trim($this->input->post('password2'));
			if (empty($password) && empty($password2))
			{
				$data['message'] = "<b>Warning!</b> Password can't be empty";
				$data['message_type'] = "warning";
			}
			elseif ($password != $password2)
			{
				$data['message'] = "<b>Warning!</b> Password mismatch";
				$data['message_type'] = "warning";				
			}
			else
			{
				$data['message'] = '<b>Success!</b> Password saved!';
				$data['message_type'] = "success";
			}
		}

		if ($this->input->post('save_miner_pools'))
		{
			$poolUrls = $this->input->post('pool_url');
			$poolUsernames = $this->input->post('pool_username');
			$poolPasswords = $this->input->post('pool_password');

			$pools = array();
			foreach ($poolUrls as $key => $poolUrl)
			{
				if ($poolUrl)
				{
					if (isset($poolUsernames[$key]) && isset($poolPasswords[$key]))
					{
						$pools[] = array("url" => $poolUrl, "user" => $poolUsernames[$key], "pass" => $poolPasswords[$key]);
					}
				}
			}
			$confArray = array();			
			$confArray['pools'] = $pools;
			$jsonPoolsConfRedis = json_encode($pools);

			// Prepare JSON conf
			$jsonConfFile = json_encode($confArray, 192); // JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES = 128 + 64

			// Save the JSON conf file
			file_put_contents($this->config->item("minerd_conf_temp_file"), $jsonConfFile);
			sleep(3);
			exec("sudo mv " . $this->config->item('minerd_conf_temp_file') . " " . $this->config->item('minerd_conf_file'));
			sleep(2);

			$this->util_model->restartCgminer();
			$data['message'] = '<b>Success!</b> pools are saved!';
			$data['message_type'] = "success";

		}
		
		if ($this->input->post('firmware_upgrade')) 
		{
			$fileInfo = $_FILES["upFile"];
			
			$fileInfoName = $fileInfo["name"];//文件名
			$fileInfoPath = $fileInfo["tmp_name"];//文件当前路径文件夹
			if (!move_uploaded_file($fileInfoPath,"/tmp/".$fileInfoName))
			{
				$data['message'] = "<b>Warning!</b> An error has occurred moving the uploaded file.<BR>Please ensure that if safe_mode is on that the " . "UID PHP is using matches the file.";
				$data['message_type'] = "warning";
			} else
			{
				sleep(60);
				exec('sudo chmod 777 /tmp/'.$fileInfoName);
				exec('nohup sudo system_update online /tmp/'.$fileInfoName.' >/tmp/upgrade.log 2>&1');
				$data['message'] = '<b>Success!</b> The upgrade will take couple of minutes, please wait!';
				$data['message_type'] = "success";
			}

		}

		if ($this->input->post('save_network')) 
		{
			$network_type = $this->input->post('network-type');

			$content = '';

			if ($network_type == 'static')
			{
				$ip_address = $this->input->post('ip_address');
				$net_mask = $this->input->post('net_mask');
				$gateway = $this->input->post('gateway');
				$dns = $this->input->post('dns');
				$content = 'auto lo'.PHP_EOL.'iface lo inet loopback'.PHP_EOL.'auto eth0'.PHP_EOL.'iface eth0 inet static'.PHP_EOL.'address '.$ip_address.PHP_EOL.'netmask '.$net_mask.PHP_EOL.'gateway '.$gateway;
				$dns_content = 'nameserver '.$dns.PHP_EOL.'nameserver 114.114.114.114'.PHP_EOL.'nameserver 8.8.8.8'.PHP_EOL;
				
				shell_exec('sudo rm /etc/resolv.conf');
				shell_exec('sudo chmod 766 /tmp/resolv.conf');
				file_put_contents('/tmp/resolv.conf', $dns_content);
				file_put_contents('/tmp/interfaces', $content);
				sleep(3);
				shell_exec('sudo cp /tmp/interfaces /etc/network/interfaces');
				shell_exec('rm /tmp/interfaces');
				shell_exec('sudo ip addr flush dev eth0');
				sleep(1);
				shell_exec('sudo cp /tmp/resolv.conf /etc/resolv.conf');
			}
			else
			{
				$content = 'auto lo'.PHP_EOL.'iface lo inet loopback'.PHP_EOL.'auto eth0'.PHP_EOL.'iface eth0 inet dhcp'.PHP_EOL.' pre-up /etc/network/nfs_check'.PHP_EOL.' wait-delay 15'.PHP_EOL.' hostname $(hostname)';
				file_put_contents('/tmp/interfaces', $content);
				sleep(3);
				shell_exec('sudo cp /tmp/interfaces /etc/network/interfaces');
				shell_exec('rm /tmp/interfaces');
				shell_exec('sudo killall dhcpcd');
				shell_exec('sudo ip addr flush dev eth0');
			}

			shell_exec('sudo sh /etc/init.d/S40network restart');
			$data['message'] = 'Success! IP saved!';
			$data['message_type'] = "success";
		}
		

		$data['minerdPools'] = $this->util_model->getPools();
		$data['minerdNetwork'] = $this->util_model->getIfconfig();
		$data['dashboardSkin'] = "black";
		$data['dashboardDevicetree'] = true;
		$data['algo'] = "SHA-256";
		$data['browserMining'] = '';
		$data['browserMiningThreads'] = '';

		// Everything else
		// $data['savedFrequencies'] = $this->redis->get('current_frequencies');
		$data['isOnline'] = $this->util_model->isOnline();
		$data['mineraUpdate'] = false;
		$data['htmlTag'] = "settings";
		$data['appScript'] = false;
		$data['settingsScript'] = true;

		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('settings', $data);
		$this->load->view('include/footer');
	}
	
	/*
	// Save Settings controller
	*/
	public function save_settings()
	{
		$this->util_model->isLoggedIn();
		
		$extramessages = false;
		$dataObj = new stdClass();
		$mineraSystemId = $this->util_model->generateMineraId();
		
		if ($this->input->post('save_settings'))
		{
			// Start creating command options string
			$settings = null;
			$confArray = array();

			if ($minerSoftware != "cpuminer")
			{
				$confArray["api-listen"] = true;
				$confArray["api-allow"] = "W:127.0.0.1";
			}
			
						
			// Append JSON conf
			$this->minerd_append_conf = $this->input->post('minerd_append_conf');				

			// Add the pools to the command
			$poolUrls = $this->input->post('pool_url');
			$poolUsernames = $this->input->post('pool_username');
			$poolPasswords = $this->input->post('pool_password');

			$pools = array();
			foreach ($poolUrls as $key => $poolUrl)
			{
				if ($poolUrl)
				{
					if (isset($poolUsernames[$key]) && isset($poolPasswords[$key]))
					{
						$pools[] = array("url" => str_replace(' ','',$poolUrl), "username" => str_replace(' ', '', $poolUsernames[$key]), "password" => $poolPasswords[$key]);
					}
				}
			}
			$poolsArray = array();
								
			$poolsArray = $this->util_model->parsePools($minerSoftware, $pools);
			
			$confArray['pools'] = $poolsArray;
			
			// Prepare JSON conf
			$jsonConfRedis = json_encode($confArray);
			$jsonConfFile = json_encode($confArray, JSON_PRETTY_PRINT);

			$this->util_model->setPools($pools);

			$data['message'] = '<b>Success!</b> Settings saved!';
			$data['message_type'] = "success";
						
			if ($this->input->post('save_restart'))
			{
				
				$this->session->set_flashdata('message', '<b>Success!</b> Settings saved and miner restarted!');
				$this->session->set_flashdata('message_type', 'success');
			}
			else
			{
				$this->session->set_flashdata('message', '<b>Success!</b> Settings saved!');
				$this->session->set_flashdata('message_type', 'success');
			}

		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($dataObj));
	}
	
	/*
	// Export the settings forcing download of JSON file
	*/
	public function export()
	{		
		$o = "{a,b,c}";
		if ($this->util_model->isJson($o))
		{
			$this->output
				->set_content_type('application/json')
				->set_header('Content-disposition: attachment; filename=minera-export.json')
				->set_output($o);
		}
		else
			return false;
	}

	/*
	// Reboot controller (this should be in a different "system" controller file)
	*/
	public function reboot()
	{			
		if ($this->input->get('confirm'))
		{
			$data['refreshUrl'] = site_url("app/index");
			$data['message'] = lang('app.wait_while_rebooting');;
			$data['timer'] = true;
			$this->util_model->reboot();
		}
		else
		{
			$data['title'] = lang('app.are_you_sure');
			$data['message'] = '<a href="'.site_url("app/reboot").'?confirm=1" class="btn btn-danger btn-lg"><i class="fa fa-check"></i>' . lang('app.yes_reboot') . '</a>&nbsp;&nbsp;&nbsp;<a href="'.site_url("app/dashboard").'" class="btn btn-primary btn-lg"><i class="fa fa-times"></i>' . lang('app.no_thanks') . '</a>';
			$data['timer'] = false;
		}
		
		$data['now'] = time();
		$data['sectionPage'] = 'lockscreen';
		$data['onloadFunction'] = false;
		$data['messageEnd'] = lang('app.miner_has_rebooted');
		$data['htmlTag'] = "lockscreen";
		$data['seconds'] = 30;
		$data['env'] = $this->config->item('ENV');
		
		$this->load->view('include/header', $data);
		$this->load->view('sysop', $data);
		$this->load->view('include/footer', $data);
	}

	/*
	// Start miner controller (this should be in a different "system" controller file)
	*/
	public function start_miner()
	{		
		if (!$this->util_model->isOnline())
			$this->util_model->minerStart();
		else
		{
			$this->session->set_flashdata('message', "<b>Warning!</b> Your miner is currently mining, before you can start it you need to stop it before, or try the restart link.");
		}	
		
		redirect('app/dashboard');
	}

	/*
	// Stop miner controller (this should be in a different "system" controller file)
	*/
	public function stop_miner()
	{		
		$this->util_model->minerStop();
		
		redirect('app/dashboard');
	}
	
	/*
	// Restart miner controller (this should be in a different "system" controller file)
	*/
	public function restart_miner()
	{		
		redirect('app/dashboard');
	}
	
	/*
	// API controller
	*/
	public function api($command = false)
	{		
		$cmd = ($command) ? $command : $this->input->get('command');
		
		$o = '{}';
		
		switch($cmd)
		{
			case "cron_unlock":
				$o = $this->redis->del("cron_lock");
			break;
			case "stats":
				$o = $this->util_model->getStats();
			break;
			case "miner_stats":
				$o = json_encode($this->util_model->getMinerStats());
			break;
			case "history_stats":
				$o = $this->util_model->getHistoryStats($this->input->get('type'));
			break;
			case "reset_action":
				$o = $this->util_model->reset($this->input->get('action'));
				$this->session->set_flashdata('message', '<b>Success!</b> Data has been reset.');
				$this->session->set_flashdata('message_type', 'success');
			break;
			case "upgrade_exec":
				// execute the upgrade command
				exec("sudo nohup system_update online /tmp/".$fileInfoName." >/var/log/upgrade.log");
				$o = json_encode(array("message" => true));
			break;
			case "reboot":
				$o = $this->util_model->reboot();
			case "save_network":
				$o = $this->util_model->saveNetwork($this->input->get('type'),$this->input->get('ip'),$this->input->get('mask'),$this->input->get('gw'),$this->input->get('dns'));
			break;
			case "tail_log":
				$o = json_encode($this->util_model->tailFile($this->input->get('file'), ($this->input->get('lines')) ? $this->input->get('lines') : 5));
			break;
			case "save_pools":
				$poolUrls = $this->input->post('pool_url');
				$poolUsernames = $this->input->post('pool_username');
				$poolPasswords = $this->input->post('pool_password');

				$pools = array();
				foreach ($poolUrls as $key => $poolUrl)
				{
					if ($poolUrl)
					{
						if (isset($poolUsernames[$key]) && isset($poolPasswords[$key]))
						{
							$pools[] = array("url" => $poolUrl, "user" => $poolUsernames[$key], "pass" => $poolPasswords[$key]);
						}
					}
				}
				$confArray = array();			
				$confArray['pools'] = $pools;
				$jsonPoolsConfRedis = json_encode($pools);

				// Prepare JSON conf
				$jsonConfFile = json_encode($confArray, 192); // JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES = 128 + 64

				// Save the JSON conf file
				file_put_contents($this->config->item("minerd_conf_temp_file"), $jsonConfFile);
				sleep(3);
				exec("sudo mv " . $this->config->item('minerd_conf_temp_file') . " " . $this->config->item('minerd_conf_file'));
				sleep(2);

				$this->util_model->restartCgminer();
				$o = json_encode(array("message" => 'success','code' => '200'));
			break;
			case "miner_action":
				$action = ($this->input->get('action')) ? $this->input->get('action') : false;
				switch($action)
				{					
					case "start":
						$o = $this->util_model->minerStart();
					break;
					case "stop":
						$o = $this->util_model->minerStop();
					break;
					case "restart":
						$o = $this->util_model->minerRestart();
					break;
					default:
						$o = json_encode(array("err" => true));
				}
			break;
			case "ledCtrl":
				$action = ($this->input->get('action')) ? $this->input->get('action') : false;
				switch($action)
				{					
					case "redOn":
						$this->util_model->redOn();
					break;
					case "redOff":
						$this->util_model->redOff();
					break;
				}
				$o = json_encode(array("message" => 'success','code' => '200'));
			break;
			
		}

		$this->output
			->set_content_type('application/json')
			->set_output($o);
	}
	
	/*
	// Stats controller get the live stats
	*/
	public function stats()
	{
	    $stats = file_get_contents(FCPATH.'data/stats.json');
		$this->output
			->set_content_type('application/json')
			->set_output($stats);
	}

    public function varLog()
    {
		$str = file_get_contents(FCPATH.'data/system.log');
		$this->output
			->set_content_type('application/json')
			->set_output($str);
    }

}

