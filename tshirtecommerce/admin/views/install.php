<?php
	if(!empty($data['error']))
		echo '<div class="alert alert-danger">'.$data['error'].'</div>';
	if(!empty($data['msg']))
		echo '<div class="alert alert-success">'.$data['msg'].'</div>';
	if($data['upload']['error'] == 1 && $data['upload']['msg'] != '')
		echo '<div class="alert alert-danger">'.$data['upload']['msg'].'</div>';
?>
<form id="fr-addon" action="<?php echo site_url('index.php/addon/install'); ?>" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-5">
			<div class="form-group">
				<label><?php echo lang('addons_purchased_key'); ?> <span class="symbol required"></span></label>
				<input type="text" name="key" class="form-control purchased_key" placeholder="<?php echo lang('addons_purchased_key'); ?>"/>
			</div>
			<div class="form-group">
				<div class="fileupload fileupload-new pull-left" data-provides="fileupload">
					<span class="btn btn-file btn-light-grey">
					<i class="clip-puzzle-4"></i>
					<span class="fileupload-new"><?php echo lang('addons_upload_addon'); ?></span>
					<input id="file_upload" type="file" name="file">
					</span>
				</div>
				<button id="btn-addon" style="margin-left:10px;" type="submit" disabled="disabled" class="btn btn-default" onclick="return installAddon();"><?php echo lang('addons_install_now'); ?></button>
			</div>
		</div>
		
		<div class="col-md-6 pull-right">			
			<h4>
				<?php lang('addons_title'); ?>
				<a href="https://www.youtube.com/watch?v=7bQ26MpjBFw" target="_blank" class="btn btn-default btn-sm pull-right"><?php lang('video_tutorial'); ?> <i class="fa fa-youtube-play icon-red"></i></a>
			</h4>
			<hr />
			<div class="text-muted"><?php lang('addons_install_description'); ?></div>
			<br />
			<p><?php lang('addons_install_note'); ?></p>
		</div>
	</div>
</form>
<script type="text/javascript">
	jQuery('input:file').change(function(){
		jQuery('#btn-addon').attr('disabled', 'disabled');
		var file = this.files[0];
		
		if(file.size > 20971520 || file.size <=0 )
		{
			alert('Sorry, your file larger than 20Mb');
			return false;
		}
		
		var filename = file.name;
		if(filename.indexOf('.zip') == -1 && filename.indexOf('.ZIP') == -1)
		{
			alert('Please upload a file .zip!');			
			return false;
		}
		
		jQuery('#btn-addon').removeAttr('disabled');
	});
	
	function installAddon()
	{
		var purchased_key = jQuery('.purchased_key').val();
		
		if(purchased_key != '')
			return true;
		else
			alert('Purchased key is not null.');
		return false;
	}
</script>