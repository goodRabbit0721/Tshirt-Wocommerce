<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-01
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
	$addons 	= $GLOBALS['addons'];
?>
<div class="modal fade" tabindex="-1" role="dialog" id="save-design-info">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<?php echo $addons->__('addon_save_design_title'); ?>
				</h4>
			</div>
			<div class="modal-body">				
				<p class="text-muted"><?php echo $addons->__('addon_save_design_description'); ?></p>
				
				<div class="form-group">
					<label><?php echo $addons->__('addon_save_design_f_name'); ?></label>
					<input type="text" class="form-control" id="design-save-title" placeholder="">
				</div>
				
				<div class="form-group">
					<label><?php echo $addons->__('addon_save_design_f_description'); ?></label>
					<textarea class="form-control" id="design-save-description" rows="3"></textarea>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="design.save(1)"><?php echo lang('designer_save_btn'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('designer_close_btn'); ?></button>
			</div>
		</div>
	</div>
</div>