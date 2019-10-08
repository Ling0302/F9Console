    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side ">                	
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Mining
                <small>Settings</small>
            </h1>
            <ul class="mini-save-toolbox">
				<li>
					<button type="submit" class="btn btn-sm btn-primary save-minera-settings" name="save" value="1"><i class="fa fa-floppy-o"></i> Save</button>
				</li>
				<li>
					<button type="submit" class="btn btn-sm btn-danger save-minera-settings-restart" name="save_restart" value="1"><i class="fa fa-repeat"></i> Save & Restart Miner</button>
				</li>
	    	</ul>
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            </ol>
        </section>

		<!-- Save toolbox -->
    	<div class="save-toolbox">
	    	<ul>
		    	<li><a href="#" class="toggle-save-toolbox"><i class="fa fa-close"></i></a></li>
				<li>
					<button type="submit" class="btn btn-lg btn-primary save-minera-settings" name="save" value="1"><i class="fa fa-floppy-o"></i> Save</button>
				</li>
				<li>
					<button type="submit" class="btn btn-lg btn-danger save-minera-settings-restart" name="save_restart" value="1"><i class="fa fa-repeat"></i> Save & Restart Miner</button>
				</li>
	    	</ul>
		</div>

        <!-- Main content -->
        <section class="content">

			<div class="row">

                <?php if ($message) : ?>
                    <section class="col-md-12">
                    	<div class="alert alert-<?php echo $message_type ?> alert-dismissable">
							<i class="fa fa-check"></i>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<?php echo $message ?>.
						</div>
                    </section>
                <?php endif; ?>                        
                <?php if ($this->session->flashdata('message')) : ?>
                    <section class="col-md-12">
                    	<div class="alert alert-<?php echo $this->session->flashdata('message_type') ?> alert-dismissable">
							<i class="fa fa-check"></i>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<?php echo $this->session->flashdata('message') ?>.
						</div>
                    </section>
                <?php endif; ?>                        
                
                <!-- Top section -->
                <section class="col-md-12">
						
					<form action="#" method="post" role="form" id="minersettings" enctype="multipart/form-data">
												
						<input type="hidden" name="save_settings" value="1" />
						                            	                          
						<!-- Pools box -->
                        <div class="box box-primary" id="pools-box">
							<div class="box-header">
								<!-- tools box -->
                                <div class="pull-right box-tools">
                                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /. tools -->
                                <i class="fa fa-cloud"></i>
                                
                                <h3 class="box-title">Pools Settings</h3>
                            </div>

							<div class="box-body">
								<p>Pools are taken in the order you put them, the first one is the main pool, all the others ones are failovers.</p>

								<div class="form-group">
                                    <div class="row">
										<div class="col-xs-4">
											<strong>Pool URL</strong>
										</div>
										<div class="col-xs-2">
											<strong>Pool Username</strong>
										</div>
										<div class="col-xs-2">
											<strong>Pool Password</strong>
										</div>
                                    </div>
								</div>
								<!-- Main Pool -->
								<div class="poolSortable ui-sortable">
									<?php $savedPools = json_decode($minerdPools); $donationPool = false; ?>
									<?php $s = (count($savedPools) == 0) ? 2 : count($savedPools); ?>
									<?php $donationHelp = '<h6><strong>Minera pool</strong></h6>
														<p><small>You can always remove the Minera donation pool clicking the button below, but if you hadn\'t issue with it and you like Minera, you should think to keep it as failover pool because your support is really needed to continue developing Minera. So please, before clicking the button below, consider keeping the donation pool as at least your latest failover. Thanks for your support. (If you have enabled time donation, this pool is automatically added.)</small></p>
														<p><button class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i> Remove donation pool </button></p>'; ?>
									<?php for ($i=0;$i<=$s;$i++) : ?>
										<?php if ( isset($savedPools[$i]->url) && 
													($savedPools[$i]->url == $this->config->item('minera_pool_url') || $savedPools[$i]->url == $this->config->item('minera_pool_url_sha256')) && 
													isset($savedPools[$i]->username) && 
													$savedPools[$i]->username == $this->util_model->getMineraPoolUser() && 
													isset($savedPools[$i]->password) && 
													$savedPools[$i]->password == $this->config->item('minera_pool_password') ) : $donationPool = true; ?>
										<!-- row pool for Minera -->
										<div class="form-group">
										    <div class="row sort-attach">
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-gift"></i></span>
										    			<input type="text" class="form-control" name="pool_url[]" data-ismain="0" value="<?php echo $savedPools[$i]->url ?>" readonly />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
										    			<input type="text" class="form-control" name="pool_username[]" data-ismain="0" value="<?php echo $this->util_model->getMineraPoolUser() ?>" readonly />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										    			<input type="text" class="form-control" name="pool_password[]" data-ismain="0" value="x" readonly />
										    		</div>
										    	</div>
										    	<div class="col-xs-1">
										    		<button style="margin-top:5px;" class="btn btn-primary btn-xs help-pool-row" name="help-row" value="1"><i class="fa fa-question"></i></button>
										    	</div>
										    </div>
										    <div class="row minera-pool-help" style="display:none;">
										    	<div class="col-xs-11" style="margin-top:10px">
											    	<div class="callout callout-info">
														<?php echo $donationHelp ?>
													</div>
										    	</div>
										    	<div class="col-xs-1">&nbsp;</div>
										    </div>
										</div>
										<?php else : ?>
										<div class="form-group pool-group">
										    <div class="row sort-attach pool-row">
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-cloud-<?php echo ($i == 0) ? "upload" : "download"; ?>"></i></span>
										    			<input type="text" class="form-control pool_url" placeholder="<?php echo ($i == 0) ? "Main" : "Failover"; ?> url" name="pool_url[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->url)) ? $savedPools[$i]->url : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
										    			<input type="text" class="form-control pool_username" placeholder="username" name="pool_username[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->username)) ? $savedPools[$i]->username : ''; ?>"  />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										    			<input type="text" class="form-control pool_password" placeholder="password" name="pool_password[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->password)) ? $savedPools[$i]->password : ''; ?>"  />
										    		</div>
										    	</div>
										    	<div class="col-xs-1">
										    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i></button>
										    	</div>
										    </div>
										</div>
										<?php endif; ?>
									<?php endfor; ?>
									<!-- fake donation row pool for Minera -->
									<div class="form-group pool-donation-group" style="display:none;">
									    <div class="row sort-attach">
										    <?php if ($algo === "Scrypt") : ?>
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-gift"></i></span>
										    			<input type="text" class="form-control form-donation" name="pool_url[]" data-ismain="0" value="<?php echo $this->config->item('minera_pool_url') ?>" readonly />
										    		</div>
										    	</div>
										    <?php else: ?>
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-gift"></i></span>
										    			<input type="text" class="form-control form-donation" name="pool_url[]" data-ismain="0" value="<?php echo $this->config->item('minera_pool_url_sha256') ?>" readonly />
										    		</div>
										    	</div>
										    <?php endif; ?>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
									    			<input type="text" class="form-control form-donation" name="pool_username[]" data-ismain="0" value="<?php echo $this->util_model->getMineraPoolUser() ?>" readonly />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									    			<input type="text" class="form-control form-donation" name="pool_password[]" data-ismain="0" value="x" readonly />
									    		</div>
									    	</div>
									    	<div class="col-xs-3">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
													<input type="text" class="form-control pool_proxy" placeholder="socks5|http://proxy:port" name="pool_proxy[]" readonly />
									    		</div>
									    	</div>
									    	<div class="col-xs-1">
									    		<button style="margin-top:5px;" class="btn btn-primary btn-xs help-pool-row" name="help-row" value="1"><i class="fa fa-question"></i></button>
									    	</div>
									    </div>
									    <div class="row minera-pool-help" style="display:none;">
									    	<div class="col-xs-11" style="margin-top:10px">
										    	<div class="callout callout-info">
													<?php echo $donationHelp ?>
												</div>
									    	</div>
									    	<div class="col-xs-1">&nbsp;</div>
									    </div>
									</div>
									<!-- fake row to be cloned -->
									<div class="form-group pool-group pool-group-master" style="display:none;">
									    <div class="row sort-attach pool-row">
									    	<div class="col-xs-4">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-cloud-download"></i></span>
									    			<input type="text" class="form-control pool_url" placeholder="Failover url" name="pool_url[]" data-ismain="0" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
									    			<input type="text" class="form-control pool_username" placeholder="username" name="pool_username[]" data-ismain="0" value=""  />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									    			<input type="text" class="form-control pool_password" placeholder="password" name="pool_password[]" data-ismain="0" value=""  />
									    		</div>
									    	</div>
									    	<div class="col-xs-3">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
													<input type="text" class="form-control pool_proxy" placeholder="socks5|http://proxy:port" name="pool_proxy[]" data-ismain="0" value=""  />
									    		</div>
									    	</div>
									    	<div class="col-xs-1">
									    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i></button>
									    	</div>
									    </div>
									</div>
									
								</div><!-- sortable -->
								<div>
									<button class="btn btn-default btn-sm add-pool-row" name="add-row" value="1"><i class="fa fa-plus"></i> Add row</button>
								</div>
                            </div>
                        </div>

	                    <!-- Network Miners box -->
						<div class="box box-primary" id="network-miners-box">
						    <div class="box-header">
						    	<!-- tools box -->
	                            <div class="pull-right box-tools">
	                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
	                            </div><!-- /. tools -->
	                            <i class="fa fa-server"></i>
	                            
	                            <h3 class="box-title">Network Miners Settings</h3>
	                        </div>
						    
	                        <div class="box-body">
								<p>You can scan your network or add your network device manually. If you have miners like Antminer or RockMiner or any miner with a networked connection, you can control them in Minera.</p>
								<h6>Network names are picked up randomly from a small constellation database, you can change it. To scan your network insert it with subnet in the input below, like this: 192.168.1.0/24</h6>
								<div class="row">
									<div class="col-lg-4 col-md-6 col-sm-12">
								    	<div class="form-group">
								    		<div class="input-group">
								    			<input name="networkToScan" id="network-to-scan" value="" class="form-control" placeholder="192.168.1.0/24" />
								    			<span class="input-group-btn"><button type="button" class="btn bg-olive scan-network">Scan network</button></span>
								    		</div>
								    	</div>
								    </div>
								</div>

								<div class="alert alert-warning alert-no-net-devices" style="display:none">There aren't new network devices, try to add them manually.</div>

								<div class="form-group">
                                    <div class="row">
										<div class="col-xs-1">
											<strong>Status</strong>
										</div>
										<div class="col-xs-3">
											<strong>Name</strong>
										</div>
										<div class="col-xs-2">
											<strong>IP</strong>
										</div>
										<div class="col-xs-2">
											<strong>Port</strong>
										</div>
										<div class="col-xs-1">
											<strong>Algorithm</strong>
										</div>
										<div class="col-xs-2">
											<strong>Type</strong>
										</div>
                                    </div>
								</div>
								<!-- Main Pool -->
								<div class="netSortable ui-sortable">
									<?php if (count($networkMiners) > 0) : ?>
										<?php foreach($networkMiners as $networkMiner) : $isOnlineNet = $this->util_model->checkNetworkDevice($networkMiner->ip, $networkMiner->port); ?>
										<div class="form-group net-group">
										    <div class="row sort-attach net-row">
										    	<div class="col-xs-1 text-center">
										    		<span class="label <?php if ($isOnlineNet) : ?>label-success<?php else : ?>label-danger<?php endif; ?> net_miner_status"><?php if ($isOnlineNet) : ?>Online<?php else: ?>Offline<?php endif; ?></span>
										    	</div>
										    	<div class="col-xs-3">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-server"></i></span>
										    			<input type="text" class="form-control net_miner_name" placeholder="Miner Name" name="net_miner_name[]" value="<?php echo (isset($networkMiner->name)) ? $networkMiner->name : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-hdd-o"></i></span>
										    			<input type="text" class="form-control net_miner_ip" placeholder="Miner Ip Address (192.168.1.10)" name="net_miner_ip[]" value="<?php echo (isset($networkMiner->ip)) ? $networkMiner->ip : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
										    			<input type="text" class="form-control net_miner_port" placeholder="Miner Port (4028)" name="net_miner_port[]" value="<?php echo (isset($networkMiner->port)) ? $networkMiner->port : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-1">
										    		<div class="input-group">
										    			<select class="form-control net_miner_algo" name="net_miner_algo[]">
											    			<option <?php if (isset($networkMiner->algo) && $networkMiner->algo === "SHA-256") echo "selected" ?>>SHA-256</option>
											    			<option <?php if (isset($networkMiner->algo) && $networkMiner->algo === "Scrypt") echo "selected" ?>>Scrypt</option>
											    			<option <?php if (isset($networkMiner->algo) && $networkMiner->algo === "Dash") echo "selected" ?>>Dash</option>
										    			</select>
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<select class="form-control net_miner_type" name="net_miner_type[]">
											    			<option <?php if (isset($networkMiner->type) && $networkMiner->type === "newAnt") echo "selected" ?> value="newAnt">Antminer (Any new supported)</option>
											    			<option <?php if (isset($networkMiner->type) && $networkMiner->type === "other") echo "selected" ?> value="other">Other</option>
										    			</select>
										    		</div>
										    	</div>
										    	<div class="col-xs-1">
										    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-net-row" name="del-net-row" value="1"><i class="fa fa-times"></i></button>
										    	</div>
										    </div>
										</div>
										<?php endforeach; ?>
									<?php endif; ?>
									<!-- fake row to be cloned -->
									<div class="form-group net-group net-group-master" style="display:none;">
									    <div class="row sort-attach net-row">
									    	<div class="col-xs-1 text-center">
									    		<span style="width: 40px;" class="label label-primary net_miner_status">New</span>
									    	</div>
									    	<div class="col-xs-3">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-server"></i></span>
									    			<input type="text" class="form-control net_miner_name" placeholder="Miner Name" name="net_miner_name[]" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-hdd-o"></i></span>
									    			<input type="text" class="form-control net_miner_ip" placeholder="Miner Ip Address (192.168.1.10)" name="net_miner_ip[]" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
									    			<input type="text" class="form-control net_miner_port" placeholder="Miner Port (4028)" name="net_miner_port[]" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-1">
									    		<div class="input-group">
									    			<select class="form-control net_miner_algo" name="net_miner_algo[]">
										    			<option>SHA-256</option>
										    			<option>Scrypt</option>
										    			<option>Dash</option>
									    			</select>
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<select class="form-control net_miner_type" name="net_miner_type[]">
											    			<option value="newAnt">Antminer (Any new supported)</option>
											    			<option value="other">Other</option>
										    			</select>
										    		</div>
										    	</div>
									    	<div class="col-xs-1">
									    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-net-row" name="del-net-row" value="1"><i class="fa fa-times"></i></button>
									    	</div>
									    </div>
									</div>
									
								</div><!-- sortable -->
								<div>
									<button class="btn btn-default btn-sm add-net-row" name="add-net-row" value="1"><i class="fa fa-plus"></i> Add Network Miner</button>
								</div>								
	                        </div>
						    <div class="box-footer">
						    	<p class="small">Pools for network devices can be handle from the dashboard. If you select "Antminer" it should work with devices like S9, S9i, V9, Z9, L3+, L3++, D3. If this doesn't work please <a href="https://github.com/getminera/minera/issues/236#issuecomment-427736398" target="_blank">follow instruction here</a> and tell me the output of that command.</p>
						    </div>
	                    </div>
	                                            
                        <!-- System box -->
						<div class="box box-primary" id="system-box">
							<div class="box-header">
								<!-- tools box -->
                                <div class="pull-right box-tools">
                                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /. tools -->
                                <i class="fa fa-rocket"></i>
                                
                                <h3 class="box-title">System Settings</h3>
                            </div>
							
                            <div class="box-body">
								<p>Setup the system options</p>

									<!-- hostname -->
                                    <div class="form-group">
                                        <label>System hostname</label>
										<p>Current hostname is: <span class="badge bg-blue"><?php echo $mineraHostname ?></span></p>
                                        <p>You can change the Raspbian hostname where your Minera is running</p>
                                        <div class="input-group">
	                                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
	                                        <input type="text" name="system_hostname" class="form-control" placeholder="Use numbers/letters, symbols allowed are dash and underscore" />
                                        </div>
									</div>
									
									<!-- system password -->
                                    <div class="form-group">
                                        <label>System password</label>
                                        <p>Minera works with the system user <span class="badge bg-blue">minera</span>, here you can change the system user password</p>
                                        <div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
											<input type="password" class="form-control" id="system_password" name="system_password" placeholder="Password for Minera system user">
										</div>
										<div class="input-group mt10">
											<span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
											<input type="password" class="form-control" name="system_password2" placeholder="Repeat the password to validate it">
										</div>
										<h6>This is not the web password! This is the system user password you should use to login into the system by SSH. For the <a href="#user-box">web password look below</a>.
									</div>
									
									<!-- timezone -->
                                    <div class="form-group">
                                        <label>System timezone</label>
                                        <p>Current system time is: <span class="badge bg-blue"><?php echo date("c", time()); ?></span></p>
                                        <p>You should change the timezone to reflect yours</p>
										<select name="minera_timezone" class="form-control">
											<?php foreach ($timezones as $timezone) : ?>
												<option<?php echo ($mineraTimezone == $timezone) ? " selected" : ""; ?>><?php echo $timezone ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									
									<!-- rc.local extra commands -->
                                    <div class="form-group">
                                        <label>Startup extra commands (rc.local)</label>
                                        <p>If you need to launch any other extra command on boot, you can place them here. Each line will be appended to the file /etc/rc.local</p>
                                        <textarea name="system_extracommands" class="form-control system_extracommands" rows="5" placeholder="There isn't any error control here"><?php echo $systemExtracommands ?></textarea>
										<h6>(WARNING: you could harm your controller putting wrong strings here.)</h6>
									</div>
									
									<!-- scheduled event -->
									<div class="form-group">
                                        <label>Scheduled event</label>
                                        <p>Here you can schedule to reboot the system or restart the miner every X hours</p>
                                        <p><?php if ($scheduledEventTime > 0) : ?><span class="badge bg-green"><?php echo strtoupper($scheduledEventAction) ?> every <?php echo $scheduledEventTime ?> hour(s)</span>  Next event at about: <small class="label label-light"><?php echo date("c", (($scheduledEventTime*3600) + $scheduledEventStartTime))?></small><?php else : ?><span class="badge bg-muted">Disabled</span><?php endif; ?></p>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											<input type="text" class="form-control scheduled-event-time" placeholder="Hour(s)" name="scheduled_event_time" value="<?php echo $scheduledEventTime ?>" style="width:90px">&nbsp;
											<label>
												<input type="radio" class="event-reboot-radio" name="scheduled_event_action" value="reboot" <?php if ($scheduledEventAction == "reboot") : ?>checked=""<?php endif; ?> />
												Reboot System
											</label>&nbsp;
											<label>
												<input type="radio" class="event-restart-radio" name="scheduled_event_action" value="restart" <?php if ($scheduledEventAction == "restart") : ?>checked=""<?php endif; ?> />
												Restart Miner
											</label>
										</div>
										<h6>If you leave the hours empty it will be disabled.</h6>
									</div>
									
									<!-- anonymus stats -->
                                    <div class="form-group">
                                        <label>Send anonymous stats</label>
                                        <p>Join the Minera community! Send your completely anonymous stats to help growing the total Minera hashrate.</p>
										<div class="checkbox">
											<label>
												<input type="checkbox" class="anonymous-checkbox" name="anonymous_stats" value="1" <?php if ($anonymousStats) : ?>checked=""<?php endif; ?> />
												Enable Anonymous Stats
											</label>                                                
										</div>
										<h6>(Stats included are: total hashrate, devices count and miner used. No IP, host or any other data will be sent. Stats are collected and sent every hour. With the stats you will be able to see some cool numbers on the <a href="http://getminera.com">Minera website</a>)</h6>
									</div>
												
                            </div>
                        </div>
                        
						<!-- Import/Export box -->
						<div class="box box-primary" id="importexport-box">
						    <div class="box-header">
						    	<!-- tools box -->
	                            <div class="pull-right box-tools">
	                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
	                            </div><!-- /. tools -->
	                            <i class="fa fa-code-fork"></i>
	                            
	                            <h3 class="box-title">Import/Export/Share Settings</h3>
	                        </div>
						    
	                        <div class="box-body">
						    	<p>You can export a JSON file with all the settings from your current Minera system. This file can be imported to reproduce the same settings in a new Minera system with a click (this will export everything excluding: user password, charts and stats). You can also save a single miner config to be used in future<em>*</em> or shared with the Minera community<em>**</em>.</p>
						    	
								<div class="import-export-box margin-bottom">
									<span class="btn btn-success fileinput-button" data-toggle="tooltip" data-title="File must be a JSON export file from a Minera system">
										<i class="glyphicon glyphicon-plus"></i>
										Import file...
										<input class="import-file" type="file" name="import_system_config">
									</span>
									<span class="btn btn-warning export-action" data-toggle="tooltip" data-title="This generates a JSON file to be imported into Minera">
										<i class="glyphicon glyphicon-download-alt"></i>
										Export Settings
									</span> 
									<span class="btn btn-default save-config-action" data-toggle="tooltip" data-title="This saves only the miner config to be used or shared later">
										<i class="glyphicon glyphicon-floppy-disk"></i>
										Save Miner Config
									</span>
								</div>
						    	
								<!-- The global progress bar -->
								<div id="progress" class="progress">
									<div class="progress-bar progress-bar-success"></div>
								</div>
								<!-- The container for the uploaded files -->
								<div id="files" class="files"></div>
						    	
									<div class="saved-configs" <?php if (!$savedConfigs) : ?>style="display:none;"<?php endif; ?>>
									    <div class="table-responsive">
									    	<table id="saved-configs-table" class="table table-striped datatable">
									    		<thead>
									    			<tr>
									    				<th>Date</th>
									    				<th>Software</th>
									    				<th style="width:35%">Settings</th>
									    				<th>Pools</th>
									    				<th style="width:8%">Actions</th>
									    			</tr>
									    		</thead>
									    		<tbody>
												<?php if ($savedConfigs) : ?>
									    		<?php foreach ($savedConfigs as $savedConfig) : $savedConfig = json_decode(base64_decode($savedConfig));?>
									    			<tr class="config-<?php echo $savedConfig->timestamp ?>">
									    			<td>
									    				<small class="label label-info"><?php echo date("m/d/y h:i a", $savedConfig->timestamp) ?></small>
									    			</td>
									    			<td>
									    				<small class="label bg-blue"><?php echo $savedConfig->software ?></small>
									    			</td>
									    			<td>
									    				<small class="font-bold"><?php echo $savedConfig->settings ?></small>
									    			</td>
									    			<td>
										    			<small>
										    			<?php foreach ($savedConfig->pools as $savedPool) : ?>
										    				<?php echo $savedPool->url ?> <i class="fa fa-angle-double-right"></i> <?php echo $savedPool->username ?><br />
										    			<?php endforeach; ?>
										    			</small>
									    			</td>
									    			<td class="text-center">
									    				<a href="#" class="share-config-open" data-config-id="<?php echo $savedConfig->timestamp ?>" data-toggle="tooltip" data-title="Share saved config"><i class="fa fa-share-square-o"></i></a>
									    				<a href="#" class="load-config-action" style="margin-left:10px;" data-config-id="<?php echo $savedConfig->timestamp ?>" data-toggle="tooltip" data-title="Load saved config"><i class="fa fa-upload"></i></a>
									    				<a href="#" class="delete-config-action" style="margin-left:10px;" data-config-id="<?php echo $savedConfig->timestamp ?>" data-toggle="tooltip" data-title="Delete saved config"><i class="fa fa-times"></i></a>
									    			</td>
									    			</tr>
									    		<?php endforeach; ?>
												<?php endif; ?>
									    		</tbody>
									    		<tfoot>
									    		</tfoot>
									    	</table>
									    </div>
									</div>
								
	                        </div>
							<div class="box-footer">
								<h6><em>*</em> Loading a saved miner config sets the manual settings mode with the saved command line, sets the miner software and completely overwrites the pools settings.</h6>
								<h6><em>**</em> Sharing the miner config to the Minera community won't share your pools settings</h6>
							</div>
	                    </div>

					</form>

					<!-- User box -->
					<div class="box box-primary" id="user-box">
						<div class="box-header">
							<!-- tools box -->
                            <div class="pull-right box-tools">
                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /. tools -->
                            <i class="fa fa-user"></i>
                            
                            <h3 class="box-title">User</h3>
                        </div>
						
						<form action="<?php echo site_url("app/settings") ?>" method="post" role="form" id="minerapassword">
							<input type="hidden" name="save_password" value="1" />
                            <div class="box-body">
								<p>Change the Minera lock screen password</p>
                               	<label for="password1">Password</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" class="form-control" name="password" placeholder="Lock screen password">
								</div>
								<div class="input-group mt10">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" class="form-control" name="password2" placeholder="Repeat the lock screen password">
								</div>
                            </div>
							<div class="box-footer">
								<button type="submit" class="btn btn-primary save-minera-password">Save password</button>
							</div>
						
						</form>
                    </div>
                    
					<!-- Reset box -->
					<div class="box box-primary" id="resets-box">
						<div class="box-header">
							<!-- tools box -->
                            <div class="pull-right box-tools">
                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /. tools -->
                            <i class="fa fa-warning"></i>
                            
                            <h3 class="box-title">Resets</h3>
                        </div>
						
                        <div class="box-body">
	                        <div class="row">
	                        <div class="col-md-10">
								<p>If you are in trouble or you wanna start over, you can resets some of the stored data or reset everything to factory default.</p>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-warning reset-action" data-reset-action="charts"><i class="fa fa-eraser"></i> Reset Charts data</button>
	                            	<h6>This resets all the stored stats needed by the charts, so charts will start from zero.</h6>
								</div>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-primary reset-action" data-reset-action="options"><i class="fa fa-eraser"></i> Reset Guided/manual settings</button>
									<h6>If you have problem choosing between guided/manual options above you can reset them here.</h6>
								</div>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-warning reset-action" data-reset-action="logs"><i class="fa fa-eraser"></i> Clear the Minera logs</button>
									<h6>This will delete everything inside application/logs. This includes all Minera application logs and also all the Miner logs.</h6>
								</div>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-danger reset-factory-action"><i class="fa fa-recycle"></i> Reset to factory default</button>
									<h6>This will reset your Minera to the factory default settings (it doesn't change anything at system level, only the web interface with all the relative data will be reset, this includes: lock password, stats, charts, miner settings, saved miner configs, pools, etc...)</h6>
								</div>
	                        </div>
	                        </div>
                        </div>
						<div class="box-footer">
							<h6><strong>Clicking the reset buttons resets data immediately, there isn't any confirmation to do. Reset actions aren't recoverable, data will be lost.</strong></h6>
						</div>
                    </div>
                
                </section><!-- /.left col -->
                
			</div><!-- /.row -->

        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
