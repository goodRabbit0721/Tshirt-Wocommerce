<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: November 26 2015; December 01 2015
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
	$product = $params['product'];
	if (isset($params['data']['print_type']))
	{		
		$product->print_type = $params['data']['print_type'];		
	}	
	
	$GLOBALS['product'] = $product;
 ?>

