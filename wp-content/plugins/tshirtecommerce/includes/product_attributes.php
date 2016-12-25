<?php
// list attribute of product
add_action('tshirtecommerce_product_attribute', 'designer_product_attribute', 10, 2);
function designer_product_attribute($values)
{	
	// blank product
	if (count($values) == 1)
	{
		$product_id = $values[0];
		$rowid		= 'blank';
	}
	// design template
	else
	{
		$rowid		= $values[0].':'.$values[1];
		if (isset($values[2]))
			$product_id = $values[2];
		else
			$product_id = 0;		
	}
	
	// get product
	$data = array();
	if (isset($product_id) && $product_id > 0)
	{
		$json = ABSPATH .'tshirtecommerce/data/products.json';
		if (file_exists($json))
		{
			$string = file_get_contents($json);
			if ($string != false)
			{
				$products = json_decode($string);
				if ( isset($products->products) && count($products->products) > 0)
				{
					foreach($products->products as $product)
					{
						if ($product->id == $product_id)
						{
							$data = $product;
							break;
						}
					}
				}
			}
		}
	}
	
	
	if (count($data))
	{		
		if (empty($data->show_attribute) || (isset($data->show_attribute) && $data->show_attribute == 0)) return false;
		
		define('ROOT', ABSPATH.'tshirtecommerce');
		define('DS', DIRECTORY_SEPARATOR);
		include_once ABSPATH. 'tshirtecommerce/includes/functions.php';
		$dg = new dg();
		$lang = $dg->lang('lang.ini', false);
		
		// check old design or new design
		if ($rowid != 'blank' && count($values) > 2)
		{
			$cache = $dg->cache();
			$designs = $cache->get($values[0]);
			if ($designs == null || empty($designs[$values[1]]))
			{
				$cache = $dg->cache('admin');
				$designs = $cache->get($values[0]);
			}
			
			if ($designs == null || empty($designs[$values[1]]))
			{
				echo 'Design not found';
				return false;
			}
			else
			{
				$design = $designs[$values[1]];
								
				if (empty($design['print']))
				{
					return false;
				}
				else
				{
					$print = array(
						'colors' => $design['print']['colors'],
						'sizes' => $design['print']['sizes'],
					);
					$cliparts = array();
					if (isset($design['front']))
						$cliparts['front'] = json_encode($design['front']);
					else
						$cliparts['front'] = '';
					
					if (isset($design['back']))
						$cliparts['back'] = json_encode($design['back']);
					else
						$cliparts['back'] = '';
					
					if (isset($design['left']))
						$cliparts['left'] = json_encode($design['left']);
					else
						$cliparts['left'] = '';
					
					if (isset($design['right']))
						$cliparts['right'] = json_encode($design['right']);
					else
						$cliparts['right'] = '';
					
					if (count($design['images']))
					{
						$images_temp = $design['images'];
						$images 	 = array();
						foreach($images_temp as $view => $img)
						{
							if ($img != '')
							{
								$images[$view] = $img;
							}
						}
						$images = json_encode($images);
					}
					else
					{
						$images = '{}';
					}
				}
			}
		}
		else
		{
			$cliparts = array(
				'front' => '',
				'back' => '',
				'left' => '',
				'right' => '',
			);
			
			$print = array(
				'colors' => '{}',
				'sizes' => '{}',
			);
			$images = '{}';
		}
		
		
		include_once(dirname(dirname(__FILE__)).'/helper/functions.php');
		
		//echo '<pre>'; print_r($data);
		$html = array();
		if (isset($data->design) && isset($data->design->color_hex))
		{
			echo '<div class="row designer-attributes"><div class="list-colors">';
			
			// colors
			echo '<input type="hidden" value="" name="color_hex" class="designer_color_hex">';
			echo '<input type="hidden" value="" name="colors" class="designer_color_index">';
			echo '<input type="hidden" value="'.$data->id.'" name="product_id">';
			echo '<input type="hidden" value="" name="color_title" class="designer_color_title">';
			echo '<input type="hidden" value="'.$rowid.'" name="rowid" class="designer_rowid">';
			echo "<input type='hidden' value='".$images."' name='images' class='designer_images'>";
			
			echo "<input type='hidden' value='1' name='is_page_detail'>";
			
			//cliparts
			echo "<input type='hidden' value='".$cliparts['front']."' name='cliparts[front]' class='cliparts_front'>";		
			echo "<input type='hidden' value='".$cliparts['back']."' name='cliparts[back]' class='cliparts_back'>";		
			echo "<input type='hidden' value='".$cliparts['left']."' name='cliparts[left]' class='cliparts_left'>";		
			echo "<input type='hidden' value='".$cliparts['right']."' name='cliparts[right]' class='cliparts_right'>";		
			
			// print
			echo "<input type='hidden' value='".$print['colors']."' name='print[colors]' class='print_colors_front'>";			
			echo "<input type='hidden' value='".$print['sizes']."' name='print[sizes]' class='print_sizes_front'>";			
			
			foreach($data->design->color_hex as $index => $value)
			{
				$colors 	= explode(';', $data->design->color_hex[$index]);
				$n 			= count($colors);
				$width 		= (int) (24/$n);
				
				if (isset($values[3]) && $values[3] == $data->design->color_hex[$index])
					$active = 'active';
				else
					$active = '';
				
				echo '<a href="javascript:void(0);" onclick="e_productColor(this)" data-color="'.$data->design->color_hex[$index].'" data-index="'.$index.'" class="bg-colors '.$active.'" title="'.$data->design->color_title[$index].'">';
				
				for($i=0; $i<$n; $i++)
				{
					echo '<span style="width:'.$width.'px;background-color:#'.$colors[$i].';"></span>';
				}
				
				echo '</a>';
			}
			echo '</div>';
			
			// product attributes
			$html['attributes'] = getAttributes($data->attributes);
			
			echo '<div class="product-attributes">';
			
			echo $html['attributes'];
			
			// product size info
			if ($data->size != '')
			{
				echo '<div class="form-group product-fields"><a href="javascript:void(0);" onclick="jQuery(\'.product-size-info\').toggle()" title="">'.$lang['design_size_chart'].'</a><div class="product-size-info" style="display:none;">'.$data->size.'</div></div>';
			}
			
			
			echo '</div>';
			
			// min, max order
			if ($data->min_order < 1)
			{
				$data->min_order = 1;
			}
			if ($data->max_order < $data->min_order)
				$data->max_order = 10000;
			
			echo '<script type="text/javascript"> var min_order = '.$data->min_order.', max_order = '.$data->max_order.', txt_min_order = "'.$lang['min_quantity'].'";</script>';			
			
			echo '</div>';
		}
	}
}

// update attribute when click add to cart
/*
$data_items = array(
		'design_price' => $price,
		'design_id' => $rowid,
		'color_hex' => $color_hex,
		'color_title' => $color_title,
		'teams' => $teams,
		'options' => $options,
		'images' => $images,
);
*/
add_filter('tshirtecommerce_product_set_attribute', 'product_update_attribute', 10, 2);
function product_update_attribute($data_items, $product_id)
{
	if ( isset($_POST['attribute']) && isset($_POST['product_id']) && isset($_POST['colors']) && isset($_POST['is_page_detail']) )
	{
		$data 				= array();
		$data['attribute']	= $_POST['attribute'];
		$data['product_id']	= $_POST['product_id'];
		$data['quantity']	= $_POST['quantity'];
		$data['colors']		= $_POST['colors'];
		$data['cliparts']	= $_POST['cliparts'];
		$data['print']		= $_POST['print'];
		
		// check update price, use with old data
		$update_price = true;
		
		define('ROOT', ABSPATH.'tshirtecommerce');
		define('DS', DIRECTORY_SEPARATOR);
		include_once ABSPATH. 'tshirtecommerce/includes/functions.php';
		$dg = new dg();
		
		// add to cart with design template
		$rowid				= $_POST['rowid'];
		if ($rowid == $data_items['design_id'] && $data_items['design_id'] != 'blank')
		{
			$params = explode(':', $data_items['design_id']);
			if (count($params) > 1)
			{
				$cache = $dg->cache();
				$designs = $cache->get($params[0]);
				if ($designs == null || empty($designs[$params[1]]))
				{
					$cache = $dg->cache('admin');
					$designs = $cache->get($params[0]);
				}
				
				if (isset($designs[$params[1]]))
				{
					$design = $designs[$params[1]];
					
					// get printing size, colors of design template
					if ($data['print']['colors'] == '{}' && $data['print']['sizes'] == '{}')
					{
						if (isset($design['print']))
						{
							$data['print'] 		= $design['print'];
							$data['cliparts'] 	= $design['cliparts'];
						}
						else
						{
							$update_price = false;
						}
					}
					
					$cache = $dg->cache('cart');

					$item			= array(
						'id'			=> '',
						'product_id'	=> $_POST['product_id'],
						'qty'			=> $data['quantity'],
						'teams'			=> $data_items['teams'],
						'price'			=> $data_items['price'],
						'cliparts'		=> $data['cliparts'],
						'options'		=> $data_items['options'],
						'prices'		=> '{}',
					);
					
					$images	= $_POST['images'];
					if ($images != '')
					{
						$temp = str_replace('\"', '"', $images);
						$images = json_decode($temp);
					}
					
					$content		= array(
						'color' 	=> $_POST['color_hex'],
						'images' 	=> $images,
						'vector'	=> $design['vectors'],
						'fonts' 	=> $design['fonts'],
						'item' 		=> $item
					);
					
					$rowid = md5($rowid);
					$cache->set($rowid, $content);
					
					$data_items['design_id'] = $rowid;
				}
			}
		}
		
		if ($update_price == true)
		{
			if (isset($data['print']['colors']) && $data['print']['colors'] != '{}')
				$data['print']['colors']	= str_replace('\"', '"', $data['print']['colors']);
			if (isset($data['print']['sizes']) && $data['print']['sizes'] != '{}')
				$data['print']['sizes']	= str_replace('\"', '"', $data['print']['sizes']);
			
			$prices = $dg->prices($data, false);
			
			if (isset($prices->sale))
			{
				$price = str_replace(',', '', $prices->sale);
				$data_items['design_price'] = ($price/$data['quantity']);
			}
		}
	}
		
	return $data_items;
}

// remove edit quantity in page cart
add_filter( 'woocommerce_cart_item_quantity', 'woocommerce_cart_disable_item_quantity', 10, 2);
function woocommerce_cart_disable_item_quantity( $product_quantity, $cart_item_key) {
	
	
	$data = WC()->session->get( $cart_item_key.'_designer');	
	if ($data != null && count($data) > 0 && isset($data['design_id']) && $data['design_id'] != '' && $data['design_price'] != '')
	{
		$cart_item 	= WC()->cart->cart_contents[ $cart_item_key ];
		$product_quantity = '<strong>'.$cart_item['quantity'].'</strong>';
	}
	return $product_quantity;
}

// add product color
add_filter('tshirt_set_url_designer', 'product_tshirt_set_url_designer', 10, 3);
function product_tshirt_set_url_designer($url)
{
	if (isset($_GET['color']))
	{
		$url = $url . '&color='.$_GET['color'];
	}
	
	return $url;
}

function tshirt_e_add_to_cart()
{
	global $product;
	global $wc_cpdf;
	$link = $wc_cpdf->get_value($product->id, '_product_id');
	if ($link != '')
	{
		echo '<style>.product.post-'.$product->id.' .add_to_cart_button{display:none;}</style>';
	}
}
add_action('woocommerce_after_shop_loop_item','tshirt_e_add_to_cart');


function tshirt_e_is_purchasable( $purchasable, $product ){
	$product_id = get_the_ID();
	
	print_r($product);
	
	if (is_product())
	{		
	}
	else
	{
		global $wc_cpdf;
		$link = $wc_cpdf->get_value($product->id, '_product_id');
		if ($link != '')
			$purchasable = false;
	}	
    return $purchasable;	
}
//add_filter( 'woocommerce_is_purchasable', 'tshirt_e_is_purchasable', 10, 2 );
?>