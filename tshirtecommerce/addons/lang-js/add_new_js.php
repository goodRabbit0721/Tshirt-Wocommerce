<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-26
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
	$addons 	= $GLOBALS['addons'];
	$dg 		= $GLOBALS['dg'];
	$product 	= $GLOBALS['product'];
	
	$printing_method = '';
	if (isset($product->print_type) && $product->print_type != '')
	{
		$printing = $dg->getPrintingType($product->print_type);
		
		if ( isset($printing->price_type) )
		{
			$printing_method = $printing->price_type;
		}		
	}
?>
<script type="text/javascript">
var addon_lang_js_design_blank = '<?php echo $addons->__('addon_lang_js_design_blank'); ?>';
var printing_method = '<?php echo $printing_method; ?>';
</script>