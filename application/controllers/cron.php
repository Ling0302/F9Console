<?php if (!defined('BASEPATH')) die();

class Cron extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/*
	// Cron controller to be used to run scheduled tasks
	*/
	public function cron()
	{
		if ($this->redis->get("cron_lock"))
		{
			log_message('error', "CRON locked waiting previous process to terminate...");			
			return true;
		}
		
		if ( ($this->util_model->getSysUptime() + $this->redis->get('minerd_delaytime') ) <= 60 )
		{
			log_message('error', "System just started, warming up...");			
			return true;			
		}
	
		$time_start = microtime(true); 
			
		log_message('info', "--- START CRON TASKS ---");
			
		$this->redis->set("cron_lock", true);
			
		// Check and restart the minerd if it's dead
		if ($this->redis->get('minerd_autorecover'))
		{
			$this->util_model->checkMinerIsUp();	
		}
		
		$now = time();
		$currentHour = date("H", $now);
		$currentMinute = date("i", $now);
		
		// Refresh Cryptsydata if needed
		//$this->util_model->refreshcryptsyData();
		//$this->util_model->updateAltcoinsRates();
						
		// Store the live stats
		$stats = $this->util_model->storeStats();
		
		// Publish stats to Redis
		$this->util_model->getStats();

		/*
		// Store the avg stats
		*/
		// Store 5min avg
		if ( ($currentMinute%5) == 0)
		{
			$this->util_model->storeAvgStats(300);
		}
		// Store 1hour avg
		if ( $currentMinute == "00")
		{
			$this->util_model->storeAvgStats(3600);
		}
		// Store 1day avg
		if ( $currentHour == "04" && $currentMinute == "00")
		{
			$this->util_model->storeAvgStats(86400);
		}
		
		// Store coins profitability
		//if ($profit = $this->util_model->getProfitability()) {
		//	$this->redis->set("coins_profitability", $profit);
		//}
		
		// Activate/Deactivate time donation pool if enable
		if ($this->util_model->isOnline() && isset($stats->pool_donation_id))
		{		
			$donationTime = $this->redis->get("minera_donation_time");
			if ($donationTime > 0)
			{
				$currentHr = (isset($stats->pool->hashrate)) ? $stats->pool->hashrate : 0;
				$poolDonationId = $stats->pool_donation_id;
				$donationTimeStarted = ($this->redis->get("donation_time_started")) ? $this->redis->get("donation_time_started") : false;

				$donationTimeDoneToday = ($this->redis->get("donation_time_done_today")) ? $this->redis->get("donation_time_done_today") : false;

				$donationStartHour = "04";
				$donationStartMinute = "10";
				$donationStopHour = date("H", ($donationTimeStarted + $donationTime*60));
				$donationStopMinute = date("i", ($donationTimeStarted + $donationTime*60));
				
				// Delete the donation-done flag after 24h
				if ($now >= ($donationTimeDoneToday+86400))
				{
					$this->redis->del("donation_time_started");
					$this->redis->del("donation_time_done_today");	
					$donationTimeStarted = false;
					$donationTimeDoneToday = false;
				}
				
				// Stop time donation
				if ($donationTimeStarted > 0 && (int)$currentHour >= (int)$donationStopHour && (int)$currentMinute >= (int)$donationStopMinute)
				{
					$this->redis->del("donation_time_started");
					$donationTimeStarted = false;
					$this->util_model->selectPool(0);
					log_message("error", "[Donation-time] Terminated... Switching back to main pool ID [0]");
				}

				if ($donationTimeStarted > 0)
				{
					// Time donation in progress
					$remain = round(((($donationTime*60) - ($now - $donationTimeStarted))/60));
					$this->redis->set("donation_time_remain", $remain);
					log_message("error", "[Donation time] In progress..." . $remain . " minutes remaing..." );
				}

				// Start time donation
				if ($donationTimeDoneToday === false && ((int)$currentHour >= (int)$donationStartHour && (int)$currentMinute >= (int)$donationStartMinute))
				{
					// Starting time donation
					$this->util_model->selectPool($poolDonationId);
					$this->redis->set("donation_time_started", $now);
					
					// This prevent any re-activation for the current day
					$this->redis->set("donation_time_done_today", $now);
					
					$this->redis->command("LPUSH saved_donations ".$now.":".$donationTime.":".$currentHr);
					
					log_message("error", "[Donation time] Started... (for ".$donationTime." minutes) - Switching to donation pool ID [".$poolDonationId."]");
				}
			}
		}

		// Scheduled event
		$scheduledEventStartTime = $this->redis->get("scheduled_event_start_time");
		$scheduledEventTime = $this->redis->get("scheduled_event_time");
		$scheduledEventAction = $this->redis->get("scheduled_event_action");
		if ($scheduledEventTime > 0)
		{
			log_message("error", "TIME: ".time()." - SCHEDULED START TIME: ".$scheduledEventStartTime);
			
			$timeToRunEvent = (($scheduledEventTime*3600) + $scheduledEventStartTime);
			if (time() >= $timeToRunEvent)
			{				
				
				log_message("error", "Running scheduled event ($timeToRunEvent) -> ".strtoupper($scheduledEventAction));
				
				$this->redis->set("scheduled_event_start_time", time());

				$this->redis->command("BGSAVE");
								
				log_message("error", "TIME: ".time()." - AFTER SCHEDULED START TIME: ".$this->redis->get("scheduled_event_start_time"));
				
				if ($scheduledEventAction == "restart")
				{
					$this->util_model->minerRestart();
				}
				else
				{
					sleep(10);
					$this->util_model->reboot();
				}
			}
		}
		
		// Send anonymous stats
		$anonynousStatsEnabled = $this->redis->get("anonymous_stats");
		$mineraSystemId = $this->util_model->generateMineraId();

		if ($mineraSystemId)
		{
			if ($this->util_model->isOnline()) {
				$totalDevices = 0; $totalHashrate = 0; 
				if (isset($stats->totals->hashrate))
					$totalHashrate = $stats->totals->hashrate;
					
				if (isset($stats->devices))
				{
					$devs = (array)$stats->devices;
					$totalDevices = count($devs);
				}
	
				$minerdRunning = $this->redis->get("minerd_running_software");

				$anonStats = array("id" => $mineraSystemId, "algo" => "SHA-256", "hashrate" => $totalHashrate, "devices" => $totalDevices, "miner" => $minerdRunning, "version" => $this->util_model->currentVersion(true), "timestamp" => time());
			}
			
			if ( $currentMinute == "00")
			{
				if ($this->util_model->isOnline()) $this->util_model->sendAnonymousStats($mineraSystemId, $anonStats);
			}
		}
				
		// Use the live stats to check if autorestart is needed
		// (devices possible dead)
		$autorestartenable = $this->redis->get("minerd_autorestart");
		$autorestartdevices = $this->redis->get("minerd_autorestart_devices");
		$autorestarttime = $this->redis->get("minerd_autorestart_time");

		if ($autorestartenable && $autorestartdevices)
		{
			log_message('error', "Checking miner for possible dead devices...");
		
			// Use only if miner is online
			if ($this->util_model->isOnline())
			{
				// Check if there is stats error
				if (isset($stats->error))
					return false;
				
				// Get the max last_share time per device
				$lastshares = false;
				
				if (isset($stats->devices))
				{
					foreach ($stats->devices as $deviceName => $device)
					{
						$lastshares[$deviceName] = $device->last_share;
					}
				}
				
				// Check if there is any device with last_share time > 10minutes (possible dead device)
				if (is_array($lastshares))
				{
					$i = 0;
					foreach ($lastshares as $deviceName => $lastshare)
					{
						if ( (time() - $lastshare) > $autorestarttime )
						{
							log_message('error', "WARNING: Found device: ".$deviceName." possible dead");
							$i++;
						}
					}
					
					// Check if dead devices are equal or more than the ones set
					if ($i >= $autorestartdevices)
					{
						// Restart miner
						log_message('error', "ATTENTION: Restarting miner due to possible dead devices found - Threshold: ".$autorestartdevices." Found: ".$i);
						$this->util_model->minerRestart();
					}
				}
			}
		}

		$this->redis->del("cron_lock");

		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start);
		
		log_message('error', "--- END CRON TASKS (".round($execution_time, 2)." secs) ---");
	}
	

}
