<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

// get price fixed on each view
function getPricePrinting($printing, $data, $quantity)
{
	$price = 0;
	if ( isset($printing->values) && count($printing->values) && isset($data['elements']) )
	{		
		$elements = $data['elements'];
		
		$obj = array(
			'text' => 0,
			'clipart' => 1,
			'upload' => 2,
			'names' => 3,
			'numbers' => 4,
		);
		
		if (count($elements))
		{
			$values = json_decode ( json_encode($printing->values), true);
						
			foreach($elements as $view => $element)
			{
				if ( is_array($element) && count($element) == 0) continue;
				if ( is_string($element) && $element == '') continue;
				
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
					
					//get price
					for($i=0; $i<count($element); $i++)
					{
						if (isset($element[$i]['type']))
						{
							$type = $element[$i]['type'];
							if ( isset($obj[$type]) )
							{
								$index = $obj[$type];
								if ( isset($prices[$index]) )
								{
									$price 	= $price + $prices[$index];
								}
							}
						}
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