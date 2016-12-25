<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-01; 2015-11-03
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

$data 						= $GLOBALS['data'];
//if (isset($data['print_type']))
//{
	$result 				= $params['result'];
	$setting 				= $params['setting'];
	$post					= $params['post'];
	$product				= $params['product'];
if (property_exists($product,'print_type'))
{
	//$printing_type 			= $data['print_type'];							// printing type
	$printing_type 			= $product->print_type;							// printing type
	$product_quantity 		= $post['quantity'];							// total quantity of post
	$product_size_front		= 'A6';											// default size
	$product_size_back		= 'A6';											// default size
	$product_size_left		= 'A6';											// default size
	$product_size_right		= 'A6';											// default size
																			// (if 'DTG' and 'sublimation' or 'sceen' and 'embroidery' with No option)
	$price_discount			= 0;											// price discount default
	$arr_sizes 				= json_decode($post['print']['sizes']);
	$arr_colors				= json_decode($post['print']['colors']);
	$check_discount 		= false;										// if exit discount

	$product_allow_print_discount = 1;										// value = 1: not allow
	if(isset($product->print_discount)) $product_allow_print_discount = 0;	// if exits value = 0: allow
	
	/** (5):get price discount for each region : front, back, left, right **/
	// get DTG fixed price
	$price_dtg_discount_front 	= 0;
	if(isset($setting->price_fix_dtg_discount_front)) $price_dtg_discount_front = $setting->price_fix_dtg_discount_front;	
	$price_dtg_discount_back 	= 0;
	if(isset($setting->price_fix_dtg_discount_back )) $price_dtg_discount_back  = $setting->price_fix_dtg_discount_back;	
	$price_dtg_discount_left 	= 0;
	if(isset($setting->price_fix_dtg_discount_left )) $price_dtg_discount_left  = $setting->price_fix_dtg_discount_left;	
	$price_dtg_discount_right 	= 0;
	if(isset($setting->price_fix_dtg_discount_right)) $price_dtg_discount_right = $setting->price_fix_dtg_discount_right;
	
	// get Screen fixed price
	$price_screen_discount_front 	= 0;
	if(isset($setting->price_fix_screen_discount_front)) $price_screen_discount_front = $setting->price_fix_screen_discount_front;	
	$price_screen_discount_back 	= 0;
	if(isset($setting->price_fix_screen_discount_back )) $price_screen_discount_back  = $setting->price_fix_screen_discount_back;	
	$price_screen_discount_left 	= 0;
	if(isset($setting->price_fix_screen_discount_left )) $price_screen_discount_left  = $setting->price_fix_screen_discount_left;	
	$price_screen_discount_right 	= 0;
	if(isset($setting->price_fix_screen_discount_right)) $price_screen_discount_right = $setting->price_fix_screen_discount_right;
	
	// get Submilation fixed price
	$price_sublimation_discount_front 	= 0;
	if(isset($setting->price_fix_sublimation_discount_front)) $price_sublimation_discount_front = $setting->price_fix_sublimation_discount_front;	
	$price_sublimation_discount_back 	= 0;
	if(isset($setting->price_fix_sublimation_discount_back )) $price_sublimation_discount_back  = $setting->price_fix_sublimation_discount_back;	
	$price_sublimation_discount_left 	= 0;
	if(isset($setting->price_fix_sublimation_discount_left )) $price_sublimation_discount_left  = $setting->price_fix_sublimation_discount_left;	
	$price_sublimation_discount_right 	= 0;
	if(isset($setting->price_fix_sublimation_discount_right)) $price_sublimation_discount_right = $setting->price_fix_sublimation_discount_right;
	
	// get Embroidery fixed price
	$price_embroidery_discount_front 	= 0;
	if(isset($setting->price_fix_embroidery_discount_front)) $price_embroidery_discount_front = $setting->price_fix_embroidery_discount_front;	
	$price_embroidery_discount_back 	= 0;
	if(isset($setting->price_fix_embroidery_discount_back )) $price_embroidery_discount_back  = $setting->price_fix_embroidery_discount_back;	
	$price_embroidery_discount_left 	= 0;
	if(isset($setting->price_fix_embroidery_discount_left )) $price_embroidery_discount_left  = $setting->price_fix_embroidery_discount_left;	
	$price_embroidery_discount_right 	= 0;
	if(isset($setting->price_fix_embroidery_discount_right)) $price_embroidery_discount_right = $setting->price_fix_embroidery_discount_right;
	/** end (5) **/
	
	if($product_allow_print_discount == 0)
	{
		// get colors
		$arr_colors_temp_front 	= array(); // array temp front
		$arr_colors_temp_back 	= array(); // array temp back
		$arr_colors_temp_left 	= array(); // array temp left
		$arr_colors_temp_right 	= array(); // array temp right
		
		// init color for each
		$quantity_colors_front 	= 0;
		$quantity_colors_back 	= 0;
		$quantity_colors_left 	= 0;
		$quantity_colors_right	= 0;
		
		// get colors of front
		if(isset($arr_colors->front))
		{
			foreach($arr_colors->front as $key=>$value)
			{
				$arr_colors_temp_front[]  = $value;
			}
			$quantity_colors_front = count(array_unique($arr_colors_temp_front)); // Removes duplicate values from an array
		}
		
		// get colors of back
		if(isset($arr_colors->back))
		{
			foreach((array)$arr_colors->back as $key=>$value)
			{
				$arr_colors_temp_back[]  = $value;
			}
			$quantity_colors_back = count(array_unique($arr_colors_temp_back)); // Removes duplicate values from an array
		}
		
		// get colors of left
		if(isset($arr_colors->left))
		{
			foreach((array)$arr_colors->left as $key=>$value)
			{
				$arr_colors_temp_left[]  = $value;
			}
			$quantity_colors_left = count(array_unique($arr_colors_temp_left)); // Removes duplicate values from an array
		}
		// get colors of right
		if(isset($arr_colors->right))
		{
			foreach((array)$arr_colors->right as $key=>$value)
			{
				$arr_colors_temp_right[]  = $value;
			}
			$quantity_colors_right = count(array_unique($arr_colors_temp_right)); // Removes duplicate values from an array
		}
		
		// get sizes
		if(isset($arr_sizes->front->size)) 	$product_size_front = 'A' . $arr_sizes->front->size;
		else $quantity_colors_front = 0; 	//Fix 2015-12-18
		
		if(isset($arr_sizes->back->size )) 	$product_size_back  = 'A' . $arr_sizes->back->size;
		else $quantity_colors_back = 0; 	//Fix 2015-12-18
		
		if(isset($arr_sizes->left->size )) 	$product_size_left  = 'A' . $arr_sizes->left->size;
		else $quantity_colors_left = 0; 	//Fix 2015-12-18
		
		if(isset($arr_sizes->right->size)) 	$product_size_right = 'A' . $arr_sizes->right->size;
		else $quantity_colors_right = 0; 	//Fix 2015-12-18
		
		switch ($printing_type)
		{
			case 'DTG':
			case 'sublimation':				
				if(isset($setting->pricediscount->$printing_type->quantity))
				{
					for($i = 0; $i < count($setting->pricediscount->$printing_type->quantity); $i++)
					{
						if($product_quantity >= $setting->pricediscount->$printing_type->quantity[$i])
						{
							if(isset($arr_sizes->front->size))
							{
								$arr_price_front = $setting->pricediscount->$printing_type->$product_size_front;
							}
							else
							{
								$arr_price_front = 0;
							}
							if(isset($arr_sizes->back->size))
							{
								$arr_price_back	= $setting->pricediscount->$printing_type->$product_size_back;
							}
							else
							{
								$arr_price_back	= 0;
							}
							if(isset($arr_sizes->left->size))
							{
								$arr_price_left	= $setting->pricediscount->$printing_type->$product_size_left;
							}
							else
							{
								$arr_price_left	= 0;
							}
							if(isset($arr_sizes->right->size))
							{
								$arr_price_right = $setting->pricediscount->$printing_type->$product_size_right;
							}
							else
							{
								$arr_price_right = 0;
							}							
							
							// base on setting discount front, back, left, right
							if($printing_type == 'DTG')
							{
								if(isset($arr_sizes->front->size) && isset($setting->allow_dtg_discount_front)) $arr_price_front[$i] = $price_dtg_discount_front;								
								if(isset($arr_sizes->back->size)  && isset($setting->allow_dtg_discount_back))  $arr_price_back[$i]  = $price_dtg_discount_back;
								if(isset($arr_sizes->left->size)  && isset($setting->allow_dtg_discount_left))  $arr_price_left[$i]  = $price_dtg_discount_left;
								if(isset($arr_sizes->right->size) && isset($setting->allow_dtg_discount_right)) $arr_price_right[$i] = $price_dtg_discount_right;
							}
							else
							{
								if(isset($arr_sizes->front->size) && isset($setting->allow_sublimation_discount_front)) $arr_price_front[$i] = $price_sublimation_discount_front;
								if(isset($arr_sizes->back->size)  && isset($setting->allow_sublimation_discount_back))  $arr_price_back[$i]  = $price_sublimation_discount_back;
								if(isset($arr_sizes->left->size)  && isset($setting->allow_sublimation_discount_left))  $arr_price_left[$i]  = $price_sublimation_discount_left;
								if(isset($arr_sizes->right->size) && isset($setting->allow_sublimation_discount_right)) $arr_price_right[$i] = $price_sublimation_discount_right;
							}							
							
							$price_discount = $arr_price_front[$i] 
											+ $arr_price_back[$i] 
											+ $arr_price_left[$i] 
											+ $arr_price_right[$i];							
							$check_discount	= true;
						}
					}
				}
				break;			
			case 'screen':
			case 'embroidery':				
				if(count(get_object_vars($setting->pricediscount->$printing_type)) <= 1) // 'Calculated price with size of area design' is 'No'
				{
					$product_size_front = 'A6';
					$product_size_back 	= 'A6';
					$product_size_left 	= 'A6';
					$product_size_right = 'A6';
				}
				
				// get colors of front, back, left, right
				$setting_colors_front = count(get_object_vars($setting->pricediscount->$printing_type->$product_size_front)) - 1;
				$setting_colors_back  = count(get_object_vars($setting->pricediscount->$printing_type->$product_size_back )) - 1;
				$setting_colors_left  = count(get_object_vars($setting->pricediscount->$printing_type->$product_size_left )) - 1;
				$setting_colors_right = count(get_object_vars($setting->pricediscount->$printing_type->$product_size_right)) - 1;
				
				// Else:: 'Calculated price with size of area design' is 'Yes'
				
				// front
				$price_discount_front = 0;
				for($i = 0; $i < count($setting->pricediscount->$printing_type->$product_size_front->quantity); $i ++)
				{
					if(isset($setting->pricediscount->$printing_type->$product_size_front->quantity))
					{
						if($product_quantity >= $setting->pricediscount->$printing_type->$product_size_front->quantity[$i])
						{
							if($quantity_colors_front > $setting_colors_front)
							{
								$arr_price_front 		= $setting->pricediscount->$printing_type->$product_size_front->$setting_colors_front;
								$price_discount_front 	= $arr_price_front[$i] * $quantity_colors_front;
							}
							else
							{
								$arr_price_front 		= $setting->pricediscount->$printing_type->$product_size_front->$quantity_colors_front;
								$price_discount_front 	= $arr_price_front[$i] * $quantity_colors_front;
							}
							$check_discount = true;
						}
					}
				}
				
				// back
				$price_discount_back = 0;
				for($i = 0; $i < count($setting->pricediscount->$printing_type->$product_size_back->quantity); $i ++)
				{
					if(isset($setting->pricediscount->$printing_type->$product_size_back->quantity))
					{
						if($product_quantity >= $setting->pricediscount->$printing_type->$product_size_back->quantity[$i])
						{
							if($quantity_colors_back > $setting_colors_back)
							{
								$arr_price_back			= $setting->pricediscount->$printing_type->$product_size_back->$setting_colors_back;
								$price_discount_back 	= $arr_price_back[$i] * $quantity_colors_back;
							}
							else
							{
								$arr_price_back 		= $setting->pricediscount->$printing_type->$product_size_back->$quantity_colors_back;
								$price_discount_back 	= $arr_price_back[$i] * $quantity_colors_back;
							}
							$check_discount = true;
						}
					}
				}
				
				// left
				$price_discount_left = 0;
				for($i = 0; $i < count($setting->pricediscount->$printing_type->$product_size_left->quantity); $i ++)
				{
					if(isset($setting->pricediscount->$printing_type->$product_size_left->quantity))
					{
						if($product_quantity >= $setting->pricediscount->$printing_type->$product_size_left->quantity[$i])
						{
							if($quantity_colors_left > $setting_colors_left)
							{
								$arr_price_left			= $setting->pricediscount->$printing_type->$product_size_left->$setting_colors_left;
								$price_discount_left 	= $arr_price_left[$i] * $quantity_colors_left;
							}
							else
							{
								$arr_price_left 		= $setting->pricediscount->$printing_type->$product_size_left->$quantity_colors_left;
								$price_discount_left 	= $arr_price_left[$i] * $quantity_colors_left;
							}
							$check_discount = true;
						}
					}
				}
				
				// right
				$price_discount_right = 0;
				for($i = 0; $i < count($setting->pricediscount->$printing_type->$product_size_right->quantity); $i ++)
				{
					if(isset($setting->pricediscount->$printing_type->$product_size_right->quantity))
					{
						if($product_quantity >= $setting->pricediscount->$printing_type->$product_size_right->quantity[$i])
						{
							if($quantity_colors_right > $setting_colors_right)
							{
								$arr_price_right		= $setting->pricediscount->$printing_type->$product_size_right->$setting_colors_right;
								$price_discount_right 	= $arr_price_right[$i] * $quantity_colors_right;
							}
							else
							{
								$arr_price_right 		= $setting->pricediscount->$printing_type->$product_size_right->$quantity_colors_right;
								$price_discount_right 	= $arr_price_right[$i] * $quantity_colors_right;
							}
							$check_discount = true;
						}
					}
				}
				
				// base on setting discount front, back, left, right
				if($printing_type == 'screen')
				{
					if(isset($arr_sizes->front->size) && isset($setting->allow_screen_discount_front)) $price_discount_front = $price_screen_discount_front;
					if(isset($arr_sizes->back->size)  && isset($setting->allow_screen_discount_back )) $price_discount_back  = $price_screen_discount_back;
					if(isset($arr_sizes->left->size)  && isset($setting->allow_screen_discount_left )) $price_discount_left  = $price_screen_discount_left;
					if(isset($arr_sizes->right->size) && isset($setting->allow_screen_discount_right)) $price_discount_right = $price_screen_discount_right;
				}
				else
				{
					if(isset($arr_sizes->front->size) && isset($setting->allow_embroidery_discount_front)) $price_discount_front = $price_embroidery_discount_front;
					if(isset($arr_sizes->back->size)  && isset($setting->allow_embroidery_discount_back )) $price_discount_back  = $price_embroidery_discount_back;
					if(isset($arr_sizes->left->size)  && isset($setting->allow_embroidery_discount_left )) $price_discount_left  = $price_embroidery_discount_left;
					if(isset($arr_sizes->right->size) && isset($setting->allow_embroidery_discount_right)) $price_discount_right = $price_embroidery_discount_right;
				}
				
				$price_discount = $price_discount_front 	// for front
								+ $price_discount_back 		// for back
								+ $price_discount_left 		// for left
								+ $price_discount_right;	// for right
				break;
			default:
				break;
		}
	}
	
	if($check_discount)
	{
		$result->price->print_discount = $result->price->prints - $price_discount;	// fix: only show price discount for sale
	} 
	else 
	{
		$result->price->print_discount = 0;											// fix: only show price discount for sale
	}
	
	$GLOBALS['result'] = $result;
}
?>
