<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?php lang('color_edit'); ?></h4>
		</div>

		<!--add-->
		<form id="fr-color" class="form-horizontal" name="form_edit" id="form-edit" method="POST" action="<?php echo site_url('index.php/settings/editcolor/'.$id);?>">
		<div class="modal-body">
			<div id="row">
				<div class="form-group">
					<label for="inputlang" class="col-sm-4 control-label"><?php lang('color_name'); ?> *</label>
					<div class="col-md-5">
						<input type="text" name="data[title]" id="color_title" data-minlength="2" data-maxlength="255" data-msg="<?php lang('colors_validate_length_name'); ?>" class="form-control validate" value="<?php if(!empty($color['title'])) echo $color['title']; ?>" placeholder="<?php lang('color_name'); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputlang" class="col-sm-4 control-label"><?php lang('hex'); ?> *</label>
					<div class="col-md-5">
						<input type="text" name="data[hex]" id="color_hex" data-minlength="3" data-maxlength="6" data-msg="<?php lang('colors_validate_length_hex'); ?>" class="color form-control validate" value="<?php if(!empty($color['hex'])) echo $color['hex']; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn modal-close"><?php lang('close');?></button>
			<button type="button" class="btn btn-primary" onclick="editColor()"><?php lang('save');?></button>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
    jscolor.init();
</script>