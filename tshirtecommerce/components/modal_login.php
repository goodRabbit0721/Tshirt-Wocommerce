<div class="modal fade" id="f-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div id="f-login-content" class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title" id="myModalLabel"><?php echo lang('designer_user_login_now_or_sign_up'); ?></h4>
	  </div>
	  <div class="modal-body">
		
		<div class="row">
			<!-- login form -->
			<div class="col-md-8">		
				<form id="fr-login" role="form" style="margin-bottom: 5px;">						  						 
				  <div class="form-group">
					<label><?php echo lang('designer_user_username'); ?>:</label>
					<input type="text" id="login-username" class="form-control">
				  </div>
				  <div class="form-group">
					<label><?php echo lang('designer_user_password'); ?>:</label>
					<input type="password" id="login-password" class="form-control">
				  </div>
				</form>
				<p class="text-muted"><?php echo lang('designer_user_description'); ?></p>
				<p id="login-status" class="alert alert-danger" style="display: none;"></p>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-8">
				<button type="button" onclick="design.user.ini(this, 'login')" autocomplete="off" class="btn btn-default btn-primary" data-loading-text="Loading"><?php echo lang('designer_user_login'); ?></button>
				 <?php echo lang('or'); ?> 
				<a href="#f-register" onclick="jQuery('#f-login').modal('hide');" data-toggle="modal" data-target="#f-register"><strong><?php echo lang('designer_user_register'); ?></strong></a>
			</div>
		</div>
	  </div>			 
	</div>
  </div>	  
</div>
<!-- End Login -->

<!-- Begin create account -->
<div class="modal fade" id="f-register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div id="f-register-content" class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><?php echo lang('designer_user_sign_up'); ?></h4>
	  </div>
	  <div class="modal-body">
		
		<div class="row">
			<!-- login form -->
			<div class="col-md-8">					
				<form id="fr-register" role="form" style="margin-bottom: 5px;">						  						 
				  <div class="form-group">
					<label><?php echo lang('designer_user_username'); ?>:</label>
					<input type="text" id="register-username" class="form-control">
				  </div>
				   <div class="form-group">
					<label><?php echo lang('designer_user_email'); ?>:</label>
					<input type="text" id="register-email" class="form-control">
				  </div>
				  <div class="form-group">
					<label><?php echo lang('designer_user_password'); ?>:</label>
					<input type="password" id="register-password" class="form-control">
				  </div>
				</form>
				<p class="text-muted"><?php echo lang('designer_user_description'); ?></p>
				<p id="register-status" class="alert alert-danger" style="display: none;"></p>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-8">
				<button type="button" onclick="design.user.ini(this, 'register')" autocomplete="off" class="btn btn-default btn-primary" data-loading-text="Loading"><?php echo lang('designer_user_register'); ?></button>
				 <?php echo lang('or'); ?> 
				<a href="#f-login" onclick="jQuery('#f-register').modal('hide');" data-toggle="modal" data-target="#f-login"><strong><?php echo lang('designer_user_login'); ?></strong></a>
			</div>
		</div>
	  </div>			 
	</div>
  </div>	  
</div>
<!-- End create account -->