<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

class dgCart{
	
	/*
	 * get price of base product with discount quantity, sale
	 *
	 * $product product info
	 * $lisPrice list price with quantity
	 *
	*/
	function getPrice($product, $lisPrice, $quantity){
	
		$prices	= new stdClass();
		
		$prices->base = $product->price;
		$prices->sale = $product->price;
				
		// overwrite price
		if ($product->sale_price > 0)
		{
			$prices->sale = $product->sale_price;
			return $prices;
		}
		
		// check price with quantity
		if ($lisPrice == false || empty($lisPrice->min_quantity)) return $prices;
				
				
		$mins 	= $lisPrice->min_quantity;
		$maxs 	= $lisPrice->max_quantity;
		$price 	= $lisPrice->price;
		$i 		= count($price) - 1;
		
		if (count($mins) == 0 || count($maxs) == 0 || count($price) == 0) return $prices;
		
		if ($quantity <= $mins[0])
		{
			$prices->sale = $price[0];
			return $prices;
		}

		if ($quantity > $maxs[$i])
		{
			$prices->sale = $price[$i];
			return $prices;
		}
		
		for($j=0; $j<($i + 1); $j++)
		{
			if ( $quantity >= $mins[$j] && $quantity <= $maxs[$j]  )
			{
				$prices->sale = $price[$j];
				return $prices;
			}			
		}
		return $prices;
	}
	
	function settingPrint($setting, $type, $paper, $value = 0){
		if (empty($setting->prints))
			return $value;
		
		if (empty($setting->prints->$type))
			return $value;
		
		if (empty($setting->prints->$type->$paper))
			return $value;
			
		return $setting->prints->$type->$paper;
	}

	// get price of art
	public function getPriceArt($cliparts)
	{
		$ids = array();
		foreach($cliparts as $view => $arts)
		{
			for($i=0; $i<count($arts); $i++)
			{
				if (!in_array($arts[$i], $ids))
				{
					$ids[] = $arts[$i];
				}
			}
		}
		
		if (count($ids) == 0) return array();
		
		$file = ROOT .DS. 'data' .DS. 'arts.json';
		if (!file_exists($file)) return array();
		
		$str 	= file_get_contents($file);
		$rows 	= json_decode($str);
		
		if (empty($rows->count) || $rows->count == 0 || empty($rows->arts)) return array();
		
		$prices = array();
		foreach($rows->arts as $art)
		{
			if (in_array($art->clipart_id, $ids))
			{
				$prices[$art->clipart_id] = $art->price;
			}
		}		
		return $prices;
	}
	
	// get printingType
	function printingType($printing_code, $data, $quantity)
	{
		$price = 0;	
		
		// get list printing method
		$file 			= ROOT .DS. 'data' .DS. 'printings.json';
		if ( file_exists($file) )
		{
			$content 	= file_get_contents($file);			
			if ($content != false && $content != '')
			{
				$printings = json_decode($content);				
				if ( count($printings) )
				{
					foreach ($printings as $printing)
					{
						// check printing type of product
						if ( $printing->printing_code == $printing_code )
						{
							$code_type	= ROOT .DS. 'addons' .DS. 'printings' .DS. $printing->price_type.'.json';
							
							if ( file_exists ($code_type) && isset($printing->printing_code))
							{
								$control = ROOT .DS. 'addons' .DS. 'printings' .DS. 'control' .DS. $printing->price_type.'.php';								
								if ( file_exists($control) )
								{
									include_once($control);
									if (function_exists('getPricePrinting'))
									{
										$price	= getPricePrinting($printing, $data, $quantity);
									}
								}
								
							}
							break;
						}
					}
				}
			}
		}
		return $price;
	}
	
	// get price of printing
	function getPricePrint($print_type, $setting, $print, $quantity)
	{
		$price = 0;	
		
		// check other printing
		if ($print_type != 'DTG' && $print_type != 'screen' && $print_type != 'sublimation' && $print_type != 'embroidery')
		{
			$price = $this->printingType($print_type, $print, $quantity);
			return $price;
		}
		
		if ($print['sizes'] == '{}') return 0;
		
		// get price with size		
		$sizes = json_decode($print['sizes']);
		$colors = json_decode($print['colors']);
		foreach($sizes as $view=>$value)
		{
			$price_print = $this->settingPrint($setting, $print_type, $value->size, 0);
		
			if ($print_type == 'DTG' || $print_type == 'sublimation')
				$price = $price + $price_print;
			else
				$price = $price + ($price_print * count($colors->$view));			
		}
		
		return $price;
	}
	/*
	 * $fields is all attribute get from post
	 * $attributes get attribute from database
	*/
	function getPriceAttributes($attributes, $fields, $quantity)
	{
		$total 			= 0;
		$data 			= new stdClass();
		$data->prices 	= 0;
		$data->fields 	= array();
		
		if (is_string($attributes->prices))
			$prices 	= json_decode($attributes->prices);
		else
			$prices 	= $attributes->prices;
		
		if (is_string($attributes->type))
			$types 	= json_decode($attributes->type);
		else
			$types 	= $attributes->type;
		
		if (is_string($attributes->name))
			$names 	= json_decode($attributes->name);
		else
			$names 	= $attributes->name;
		
		if (is_string($attributes->titles))
			$titles 	= json_decode($attributes->titles);
		else
			$titles 	= $attributes->titles;	
	
		if (count($prices) == 0)
		{
			return $data;
		}
		else
		{
			foreach($types as $i=>$type)
			{
				if ( isset($fields[$i]) )
				{
					$data->fields[$i] = array();
					$data->fields[$i]['name'] = $names[$i];
					$data->fields[$i]['type'] = $types[$i];
					$data->fields[$i]['value'] = array();
					
					if ($type == 'selectbox' || $type == 'radio')
					{
						$total = $total + ($prices[$i][$fields[$i]] * $quantity);
						
						$data->fields[$i]['value'] = $titles[$i][$fields[$i]];
					}
					elseif ($type == 'textlist') // product size
					{						
						foreach($fields[$i] as $j=>$value)
						{
							$total = $total + ($prices[$i][$j] * $value);
							if ($value == '' || $value == 0) continue;
							$data->fields[$i]['value'][$titles[$i][$j]] = $value;
						}
					}
					elseif ($type == 'checkbox')
					{						
						foreach($fields[$i] as $j=>$value)
						{
							if ($value == '') continue;
							if (isset($prices[$i][$value]))
								$total = $total + ($prices[$i][$value] * $quantity);
							else
								$total = $total;
							
							$data->fields[$i]['value'][$j] = $titles[$i][$j];							
						}
					}
				}
			}
		}
		
		$data->prices = $total;
		
		return $data;
	}
	
	function totalPrice($product, $post, $setting)
	{
		$data 		= new stdClass();
		
		// get base price of product
		if (isset($product->prices) && count($product->prices) > 0)
		{
			$prices 	= $product->prices;		
			$data->price = $this->getPrice($product, $prices, $post['quantity']);
		}
		else
		{
			$prices	= new stdClass();
			$data->price = $this->getPrice($product, $prices, $post['quantity']);
		}
		
		// get price of product color
		$design = $product->design;		
		if ($design == false)
		{
			$data->price->colors = 0;
		}
		else
		{
			$data->price->colors 	= 0;
			$color_hex				= $design->color_hex;
			if (isset($design->price))
			{
				$color_price			= $design->price;
				if( $color_hex[key($post['colors'])] == $post['colors'][key($post['colors'])])
					$data->price->colors = $color_price[key($post['colors'])];
				else
					$data->price->colors = 0;
			}
			else
			{
				$data->price->colors = 0;
			}
		}
		
		// get price of print
		if (count($setting) > 0)
		{
			if ($product->print_type == '')
				$print_type = 'screen';
			else
				$print_type = $product->print_type;
			
			$data->price->prints = $this->getPricePrint($print_type, $setting, $post['print'], $post['quantity']);
		}
		else
		{
			$data->price->prints = 0;
		}
		
		// get price attribute
		if ($post['attribute'] == false)
		{
			$data->price->attribute = 0;
		}
		else
		{
			$attributes	= $product->attributes;
			if( count($attributes) == 0)
			{
				$data->price->attribute = 0;
				$data->options = false;
			}
			else
			{									
				$customField 			= $this->getPriceAttributes($attributes, $post['attribute'], $post['quantity']);			
				$data->price->attribute = $customField->prices;
				$data->options 			= $customField->fields;							
			}
		}
		return $data;
	}
}
?>