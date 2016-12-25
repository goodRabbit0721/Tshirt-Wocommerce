<?php 
$settings = $GLOBALS['settings'];
$addons = $GLOBALS['addons'];
?>
<div class="modal fade" id="dg-myclipart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				
				<ul role="tablist" id="upload-tabs">
					<li class="active"><a href="#upload-conputer" role="tab" data-toggle="tab"><?php echo lang('designer_upload_upload_photo'); ?></a></li>					
					<li><a href="#uploaded-art" role="tab" data-toggle="tab"><?php echo lang('designer_upload_photo_uploaded'); ?></a></li>
					<?php $addons->view('upload-label'); ?>
				</ul>
			</div>
			<div class="modal-body">
				<div class="tab-content">
					<div class="tab-pane active" id="upload-conputer">
						<div class="row">
							<div class="col-xs-6 col-md-6">
								<div class="form-group">
									<form id="files-upload-form">
										<label><?php echo lang('designer_upload_choose_a_file_upload'); ?></label>
										<input type="file" name="myfile" id="files-upload" autocomplete="off"/>	
									</form>
								</div>
								
								<div class="checkbox" style="display:none;">
									<label>
									  <input type="checkbox" autocomplete="off" id="remove-bg"> <span class="help-block"><?php echo lang('designer_upload_remove_white_background'); ?></span>
									</label>
								</div>
							</div>
							
							<div class="col-xs-6 col-md-6">
								<div class="form-group">
									<label><strong><?php echo lang('designer_upload_accepted_file_types'); ?></strong> <small>(<?php echo lang('designer_upload_max_file_size'); ?>: <?php echo $settings->site_upload_max; ?>MB)</small></label>
									<p><?php echo lang('designer_upload_accept_the_following'); ?>: <strong>png, jpg, gif</strong></p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="checkbox">
									<label>
									  <input type="checkbox" autocomplete="off" id="upload-copyright">
									  <span class="help-block"><?php echo lang('designer_upload_please_read'); ?> 
									  <a href="<?php echo $settings->site_upload_terms; ?>" target="_blank"><?php echo lang('designer_upload_copyright_terms'); ?></a>. <?php echo lang('designer_upload_if_you_do_not_have_the_complete'); ?></span>
									</label>
								</div>
								<div class="form-group">
									<button type="button" class="btn btn-primary" id="action-upload"><?php echo lang('designer_upload_upload_btn'); ?></button>
								</div>
							</div>
						</div>
					</div>
										
					<div class="tab-pane" id="uploaded-art">
						<div class="row" id="dag-files-images">
						</div>
						
						<div id="drop-area"></div>
						<div class="row col-md-12">
							<span class="help-block"><?php echo lang('designer_upload_click_image_to_add_design'); ?></span>
						</div>
					</div>
					
					<?php $addons->view('upload-content'); ?>
				</div>
			</div>
		</div>
	</div>
</div>