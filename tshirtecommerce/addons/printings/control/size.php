<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

// get price of size
function getPricePrinting($printing, $data, $quantity)
{
	$price = 0;
	if ( isset($printing->values) && count($printing->values) && isset($data['sizes']) )
	{
		$page = array(
			'6'=>0,
			'5'=>1,
			'4'=>2,
			'3'=>3,
			'2'=>4,
			'1'=>5,
			'0'=>6,
		);
		$sizes = json_decode($data['sizes']);
		if (count($sizes))
		{
			$values = json_decode ( json_encode($printing->values), true);
			
			foreach($sizes as $view => $size)
			{
				if ( is_array($size) && count($size) == 0) continue;
				if ( is_string($size) && $size == '') continue;
				
				if ( isset($values[$view]) && isset($values[$view]['prices']) && isset($values[$view]['quatity']) )
				{
					// get price with quantity
					$index = 0;
					$count_quantity = count($values[$view]['quatity']);
					for($i=0; $i<$count_quantity; $i++)
					{
						if ($quantity <= $values[$view]['quatity'][$i])
						{
							$index = $i;
							break;
						}	
					}
					
					if ($index == 0 && $values[$view]['quatity'][$count_quantity-1] < $quantity)
					{
						$index = $count_quantity-1;
					}
					
					// get list price with quantity
					if ( isset($values[$view]['prices'][$index]) )
					{
						$prices = $values[$view]['prices'][$index];
					}
					else
					{
						$prices = $values[$view]['prices'][0];
					}
					
					//get color
					if ( isset($size->size) )
					{
						$price 	= $price + $prices[ $page[$size->size] ];
					}
					else if ($count_color > count($prices) )
					{
						$price = $price + $prices[0];
					}					
				}
			}
		}
		
		if ( isset($printing->price_extra) && $price > 0 )
		{
			$price = $price + ($printing->price_extra/$quantity);
		}
	}

	return $price;
}