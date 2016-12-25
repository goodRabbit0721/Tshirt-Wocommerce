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
<div class="form-group">
	<label class="col-sm-3 control-label">
		<?php echo $addons->__('addon_price_discount_allow_discount'); ?>
	</label>
	<div class='col-sm-4'>
		<input type='checkbox' name='product[print_discount]' <?php if(isset($data->print_discount)) echo "value='1' checked";else echo "value='0'"; ?>>
	</div>
</div>
