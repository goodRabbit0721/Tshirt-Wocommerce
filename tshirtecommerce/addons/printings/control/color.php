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
	if ( isset($printing->values) && count($printing->values) && isset($data['colors']) )
	{
		
		
		$colors = json_decode($data['colors']);
		if (count($colors))
		{
			$values = json_decode ( json_encode($printing->values), true);
			foreach($colors as $view => $color)
			{
				if ( is_array($color) && count($color) == 0) continue;
				if ( is_string($color) && $color == '') continue;
				
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
					
					// get price
					if ( isset($values[$view]['prices'][$index]) )
					{
						$prices = $values[$view]['prices'][$index];
					}
					else
					{
						$prices = $values[$view]['prices'][0];
					}
					
					//get color
					$count_color = count($color);
					if ( isset($prices[$count_color-1]) )
					{
						$price = $price + $count_color * $prices[$count_color-1];
					}
					else if ($count_color > count($prices) )
					{
						$count_price = count($prices);
						$price = $price + $count_color * $prices[$count_price - 1];
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