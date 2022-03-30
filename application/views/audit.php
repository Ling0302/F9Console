			<!-- Right side column. Contains the navbar and content of the page -->
			<aside class="right-side ">				   
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						<?php echo lang('app.audit') ?>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> <?php echo lang('app.dashboard') ?></a></li>
					</ol>
				</section>
				<hr>

				<!-- Main content -->
				<section class="content">
					<?php  if(count($logs)>0): ?>
                      <table class="table table-hover table-responsive">
                          <tr>
                             <th class="active">#</th>
                             <th class="active"><?php echo lang('app.time') ?></th>
                             <th class="active"><?php echo lang('app.action') ?></th>
                              <th class="active"><?php echo lang('app.remark') ?></th>
                          </tr>
                          <?php foreach($logs as $key=>$log) : ?>
                           <tr>
                             <td><?php echo $key+1; ?></td>
                             <td><?php echo $log[0]; ?></td>
                             <td><?php echo $log[1]; ?></td>
                             <td><?php echo $log[2]; ?></td>
                             </tr>
                          <?php endforeach; ?>
            
                      </table>
					<?php else: ?>
						<p>暂无操作日志！</p>
					<?php endif; ?>
			

				</section><!-- /.content -->
			</aside><!-- /.right-side -->
		</div><!-- ./wrapper -->