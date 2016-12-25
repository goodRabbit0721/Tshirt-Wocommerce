<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-21
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

?>
<hr>
<div class="form-group">
	<label class="col-sm-3 control-label">
		<?php echo lang('product_show_attribute'); ?>
	</label>
	<div class='col-sm-4'>
		<?php if(isset($data->show_attribute) && $data->show_attribute == 1) { ?>
			<input type='checkbox' name='product[show_attribute]' checked="checked" value="1">
		<?php } else { ?>
			<input type='checkbox' name='product[show_attribute]' value="1">
		<?php } ?>
		
	</div>
</div>