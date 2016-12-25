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
<script src="<?php echo site_url('assets/js/dg-function.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script>
<div class="row">
	<div class="col-md-6 pull-right text-right">
		<button type="button" class="btn btn-primary" onclick="dgUI.art.validation()"><?php lang('save');?></button>
		<a href="<?php echo site_url('index.php/clipart'); ?>" class="btn btn-danger"><?php lang('close');?></a>
	</div>
</div>
<hr />
<div class="panel panel-default">
	<div class="panel-heading">
		<i class="clip-list"></i>				
		<?php echo $data['sub_title']; ?>
	</div>
	<div class="panel-body">
		<form enctype="multipart/form-data" id="add-clipart" class="form-horizontal" method="post" action="<?php echo site_url('index.php/clipart/save'); ?>">
			<div class="col-sm-10">
				<div class="form-group">
					<div class="alert alert-warning fade in">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<strong><?php lang('art_choose_file_upload'); ?></strong><br>
						<small><?php lang('art_file_support_type'); ?></small><br>
						<small><?php lang('art_file_support_size'); ?> 10MB</small>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3"><?php lang('art_upload'); ?></label>
				<div class="col-sm-7">
					<input type="file" id="dg-file" value="" name="file">				
					<div id="image-list">
						<?php if(setValue($data['art'], 'thumb', '') != '') { ?>
						<img src="<?php echo setValue($data['art'], 'url', '').setValue($data['art'], 'thumb', ''); ?>" width="100" alt="">
						<?php } ?>
					</div>
					<div id="response"></div>
					<div id="file-data">
						<input type="hidden" id="fle_url" name="art[fle_url]" value="<?php echo setValue($data['art'], 'url', ''); ?>">
						<input type="hidden" name="art[file_name]" value="<?php echo setValue($data['art'], 'file_name', ''); ?>">
						<input type="hidden" name="art[file_type]" value="<?php echo setValue($data['art'], 'file_type', ''); ?>">
						<input type="hidden" name="art[colors]" value="<?php echo setValue($data['art'], 'colors', ''); ?>">					
						<input type="hidden" name="art[path]" value="<?php echo setValue($data['art'], 'path', ''); ?>">					
						<input type="hidden" name="art[url]" value="<?php echo setValue($data['art'], 'url', ''); ?>">					
						<input type="hidden" name="art[change_color]" value="<?php echo setValue($data['art'], 'change_color', ''); ?>">					
						<input type="hidden" name="art[thumb]" value="<?php echo setValue($data['art'], 'thumb', ''); ?>">					
						<input type="hidden" name="art[medium]" value="<?php echo setValue($data['art'], 'medium', ''); ?>">					
						<input type="hidden" name="art[description]" value="<?php echo setValue($data['art'], 'description', ''); ?>">										
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3"><?php lang('title'); ?></label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="art[title]" value="<?php echo setValue($data['art'], 'title', ''); ?>" id="artlang_title">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3"><?php lang('add_price'); ?></label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="art[price]" value="<?php echo setValue($data['art'], 'price', ''); ?>">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3"><?php lang('art_category_choose'); ?></label>
				<div class="col-sm-7">
					<select name="art[cate_id]" class="form-control">
						<option value="0"><?php lang('art_category'); ?></option>
						<?php echo dispayCateTree($data['categories'], 0, array(setValue($data['art'], 'cate_id', 0))); ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3"><?php lang('description'); ?></label>
				<div class="col-sm-7">
					<textarea type="text" class="form-control" name="art[description]" rows="3"><?php echo setValue($data['art'], 'description', ''); ?></textarea>
				</div>
			</div>
			
			<input type="hidden" class="form-control" name="id" value="<?php echo $data['id']; ?>">
		</form>
	</div>
</div>