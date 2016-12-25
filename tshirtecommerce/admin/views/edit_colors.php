<?php
	define('ROOT', dirname(__FILE__));
	define('DS', DIRECTORY_SEPARATOR);

	include_once ROOT .DS. 'includes' .DS. 'functions.php';

	// call language
	$dg = new dg();
	$lang = $dg->lang();
	$site_url = $dg->siteUrl();
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?php lang('color_edit'); ?></h4>
		</div>

		<!--add-->
		<form class="form-horizontal" name="form_edit" id="form-edit" method="POST" action="">
		<div class="modal-body">
			<div id="row">
				<div class="form-group">
					<label for="inputlang" class="col-sm-4 control-label"><?php lang('color_name'); ?> *</label>
					<div class="col-md-5">
						<input type="text" name="data[title]" id="color_title" data-minlength="2" data-maxlength="255" data-msg="<?php lang('colors_validate_length_name'); ?>" class="form-control validate">
					</div>
				</div>
				<div class="form-group">
					<label for="inputlang" class="col-sm-4 control-label"><?php lang('hex'); ?> *</label>
					<div class="col-md-5">
						<input type="text" name="data[hex]" id="color_hex" data-minlength="3" data-maxlength="6" data-msg="<?php lang('colors_validate_length_hex'); ?>" class="color form-control validate" value="">
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn modal-close"><?php lang('close');?></button>
			<button type="submit" class="btn btn-primary"><?php lang('save');?></button>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
    jscolor.init();
</script>