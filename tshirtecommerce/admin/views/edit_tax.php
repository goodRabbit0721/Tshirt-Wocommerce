<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT')) exit('No direct script access allowed');
$tax = $tax_view;
include (ROOT .DS. 'includes' .DS. 'addons.php');
$addons = new addons();	
?>

<div class="modal-dialog">
	<div class="modal-content">
			
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?php if($tax->id != '') echo $addons->lang['tax_edit_tax_title']; else echo $addons->lang['tax_add_tax_title']; ?></h4>
		</div>

		<form id="fr-edit-tax" class="form-horizontal" method="POST" action="<?php echo site_url('index.php/taxes/edit/'.$tax->id); ?>">
			<div class="modal-body">		
				<div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<?php echo $addons->lang['tax_name_title']; ?>
							</label>
							<div class="col-sm-6">	
								<input type="text" name="data[title]" data-minlength="2" data-maxlength="255" data-msg="<?php echo $addons->lang['tax_name_validate_msg']; ?>" class="form-control validate required" placeholder="<?php echo $addons->lang['tax_name_title']; ?>" value="<?php echo $tax->title; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<?php echo $addons->lang['tax_rate_title']; ?>
							</label>
							<div class="col-sm-6">	
								<input type="text" name="data[value]" data-minlength="1" data-maxlength="10" data-type="number" data-msg="<?php echo $addons->lang['tax_value_validate_msg']; ?>" class="form-control validate required" placeholder="<?php echo $addons->lang['tax_value_title']; ?>" value="<?php echo $tax->value; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo $addons->lang['tax_type_title']; ?> </label>
							<div class="col-sm-6">
								<input id="percent_coupon" class="pull-left" type="radio" name="data[type]" value="p" style="margin-right: 5px;" <?php if($tax->type == 'p') echo 'checked=""';?>/>
								<label for="percent_coupon" class="pull-left" style="margin-right: 20px;"><?php echo $addons->lang['tax_percent_title']; ?> <small>%</small></label>
								
								<input id="total_coupon" class="pull-left" type="radio" name="data[type]" value="t" style="margin-right: 5px;" <?php if($tax->type == 't') echo 'checked=""';?>/>
								<label for="total_coupon" class="pull-left"><?php echo $addons->lang['tax_amount_title']; ?> <small>+</small></label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="publish"><?php echo $addons->lang['tax_publish_title']; ?></label>
							<div class="col-sm-6">	
								<select name="data[published]" class="form-control">
									<option value="1" <?php if($tax->published === 1) echo 'selected=""'; ?>><?php echo $addons->lang['tax_yes_title']; ?></option>
									<option value="0" <?php if($tax->published === 0) echo 'selected=""'; ?>><?php echo $addons->lang['tax_no_title']; ?></option>
								</select>
							</div>
						</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn modal-close"><?php echo $addons->lang['tax_close_title']; ?></button>
				<button class="btn btn-primary" type="submit"><?php echo $addons->lang['tax_save_title']; ?></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	jQuery('#fr-edit-tax').validate();
</script>