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
		<?php echo $addons->__('tax_add_tax_title'); ?>
	</label>
	<div class="col-sm-4">
		<?php 
		$taxes[''] = $addons->__('tax_product_choose_tax_title');
		$file = dirname(ROOT) .DS. 'data' .DS. 'taxes.json';
		if(file_exists($file))
		{
			$taxdata = @file_get_contents($file);
			$taxdata = json_decode($taxdata);
			if(count($taxdata))
			{
				foreach($taxdata as $val)
				{
					if($val->published == 1)
						$taxes[$val->id] = $val->title;
				}
			}
		}
		$value = '';
		if(isset($data->tax))
			$value = $data->tax;
		?>
		<select name="product[tax]" size="1" class="form-control input-sm">
		
		<?php 
		foreach($taxes as $key => $val) {
			if ($value == $key) $selected = 'selected="selected"';
			else $selected = '';
		?>
			<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
		<?php } ?>
		
		</select>
	</div>
</div>