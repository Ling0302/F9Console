    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side ">                	
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php echo lang('app.mining') ?>
                <small><?php echo lang('app.settings') ?></small>
            </h1>
            <!--<ul class="mini-save-toolbox">
				<li>
					<button type="submit" class="btn btn-sm btn-primary save-minera-settings" name="save" value="1"><i class="fa fa-floppy-o"></i> <?php echo lang('app.save') ?></button>
				</li>
				<li>
					<button type="submit" class="btn btn-sm btn-danger save-minera-settings-restart" name="save_restart" value="1"><i class="fa fa-repeat"></i> <?php echo lang('app.save_and_restart') ?></button>
				</li>
	    	</ul>-->
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> <?php echo lang('app.dashboard') ?></a></li>
            </ol>
        </section>

		<!-- Save toolbox -->
    	<div class="save-toolbox">
	    	<ul>
		    	<li><a href="#" class="toggle-save-toolbox"><i class="fa fa-close"></i></a></li>
				<li>
					<button type="submit" class="btn btn-lg btn-primary save-minera-settings" name="save" value="1"><i class="fa fa-floppy-o"></i> <?php echo lang('app.save') ?></button>
				</li>
				<li>
					<button type="submit" class="btn btn-lg btn-danger save-minera-settings-restart" name="save_restart" value="1"><i class="fa fa-repeat"></i> <?php echo lang('app.save_and_restart') ?></button>
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
												
						<input type="hidden" name="save_miner_pools" value="1" />
						                            	                          
						<!-- Pools box -->
                        <div class="box box-primary" id="pools-box">
							<div class="box-header">
								<!-- tools box -->
                                <div class="pull-right box-tools">
                                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /. tools -->
                                <i class="fa fa-cloud"></i>
                                
                                <h3 class="box-title"><?php echo lang('app.pools_settings') ?></h3>
                            </div>

							<div class="box-body">
								<p><?php echo lang('app.setting_tips') ?></p>

								<div class="form-group">
                                    <div class="row">
										<div class="col-xs-4">
											<strong><?php echo lang('app.pool_url') ?></strong>
										</div>
										<div class="col-xs-2">
											<strong><?php echo lang('app.pool_username') ?></strong>
										</div>
										<div class="col-xs-2">
											<strong>网络设置</strong>
										</div>
										<div class="col-xs-2">
											<strong><?php echo lang('app.pool_password') ?></strong>
										</div>
                                    </div>
								</div>
								<!-- Main Pool -->
								<div class="poolSortable ui-sortable">
									<?php $savedPools = json_decode($minerdPools); ?>
									<?php for ($i=0;$i<=2;$i++) : ?>
										<!-- row pool for Minera -->
										<div class="form-group pool-group">
										    <div class="row sort-attach pool-row">
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-cloud-<?php echo ($i == 0) ? "upload" : "download"; ?>"></i></span>
										    			<input type="text" class="form-control pool_url" placeholder="<?php echo ($i == 0) ? lang('app.setting_main').lang('app.setting_url'): lang('app.setting_failover').lang('app.setting_url'); ?>" name="pool_url[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->url)) ? $savedPools[$i]->url : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
										    			<input type="text" class="form-control pool_username" placeholder="<?php echo lang('app.username') ?>" name="pool_username[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->user)) ? $savedPools[$i]->user : ''; ?>"  />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										    			<input type="text" class="form-control pool_password" placeholder="<?php echo lang('app.password') ?>" name="pool_password[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->pass)) ? $savedPools[$i]->pass : ''; ?>"  />
										    		</div>
										    	</div>
										    </div>
										</div>
									
									<?php endfor; ?>
					
								</div><!-- sortable -->
                            </div>

							<div class="box-footer">
								<button type="submit" class="btn btn-primary save-miner-pools"><?php echo lang('app.save_pools') ?></button>
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
                            
                            <h3 class="box-title"><?php echo lang('app.user') ?></h3>
                        </div>
						
						<form action="<?php echo site_url("app/settings") ?>" method="post" role="form" id="minerapassword">
							<input type="hidden" name="save_password" value="1" />
                            <div class="box-body">
								<p><?php echo lang('app.change_password') ?></p>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" class="form-control" name="password" placeholder="<?php echo lang('app.lock_screen_password') ?>">
								</div>
								<div class="input-group mt10">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" class="form-control" name="password2" placeholder="<?php echo lang('app.repeat_lock_screen_password') ?>">
								</div>
                            </div>
							<div class="box-footer">
								<button type="submit" class="btn btn-primary save-minera-password"><?php echo lang('app.save_password') ?></button>
							</div>
						
						</form>
                    </div>

					<!-- 网络设置 -->
					<div class="box box-primary" id="network-box">
						<div class="box-header">
							<!-- tools box -->
                            <div class="pull-right box-tools">
                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /. tools -->
                            <i class="fa fa-cloud"></i>
                            <h3 class="box-title">网络设置</h3>
                        </div>
						
						<form action="<?php echo site_url("app/settings") ?>" method="post" role="form" id="minerapassword">
							<input type="hidden" name="save_network" value="1" />
                            <div class="box-body">
								<p>设置静态/动态IP</p>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<select class="form-control" name="network-type">
										<option value ="static">Static</option>
										<option value ="dhcp">DHCP</option>
									</select>
								</div>
								<div class="input-group mt10">
									<span class="input-group-addon"><i class="fa fa-cog"></i></span>
									<input type="text" class="form-control" name="ip_address" placeholder="IP Address">
								</div>
								<div class="input-group mt10">
									<span class="input-group-addon"><i class="fa fa-cog"></i></span>
									<input type="text" class="form-control" name="net_mask" placeholder="Network Mask">
								</div>
								<div class="input-group mt10">
									<span class="input-group-addon"><i class="fa fa-cog"></i></span>
									<input type="text" class="form-control" name="gateway" placeholder="Gateway">
								</div>
								<div class="input-group mt10">
									<span class="input-group-addon"><i class="fa fa-cog"></i></span>
									<input type="text" class="form-control" name="dns" placeholder="DNS">
								</div>
                            </div>
							<div class="box-footer">
								<button type="submit" class="btn btn-primary save-minera-password">Save Network Settings</button>
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
                            
                            <h3 class="box-title"><?php echo lang('app.resets') ?></h3>
                        </div>
						
                        <div class="box-body">
	                        <div class="row">
	                        <div class="col-md-10">
								<div class="form-group">
	                            	<button type="submit" class="btn btn-danger reset-factory-action"><i class="fa fa-recycle"></i> <?php echo lang('app.setting_reset_factory') ?></button>
									<h6><?php echo lang('app.setting_reset_factory_tips') ?></h6>
								</div>
	                        </div>
	                        </div>
                        </div>
						<div class="box-footer">
							<h6><strong><?php echo lang('app.setting_reset_footer_tips') ?></strong></h6>
						</div>
                    </div>
                
                </section><!-- /.left col -->
                
			</div><!-- /.row -->

        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
