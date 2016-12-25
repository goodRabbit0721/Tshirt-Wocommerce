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
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php lang('add_cate'); ?></h4>
</div>

<div class="modal-body">
	<div class="form-group">
		<label><?php lang('product_title_category'); ?></label>
		<input type="text" class="form-control" name="title" id="cate_art_title" value="<?php echo $data['category']['title']; ?>" placeholder="Enter <?php lang('title'); ?>">
	</div>
	<div class="form-group">
		<label><?php lang('parent_category'); ?></label>
		<select id="art_change_cate_id" class="form-control" name="parent_id">
			<option value="0"><?php lang('art_category'); ?></option>
			<?php 
			foreach($data['categories'] as $category) {
				if ($data['category']['parent_id'] == $category->id)
					$selected = 'selected="selected"';
				else
					$selected = '';
			?>
			<option value="<?php echo $category->id; ?>" <?php echo $selected; ?>><?php echo $category->title; ?></option>
			<?php } ?>
		</select>
	</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('close'); ?></button>
	<button type="button" class="btn btn-primary" onclick="dgUI.art.saveCategory(<?php echo $data['id']; ?>)"><?php lang('save'); ?></button>
</div>