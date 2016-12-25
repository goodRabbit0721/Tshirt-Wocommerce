<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT')) exit('No direct script access allowed');

class dg{
	
	public function __construct()
	{
		$this->path_data = dirname(ROOT) .DS. 'data';
		$this->components = ROOT .DS. 'components';
	}
	
	public function url(){
		$pageURL = 'http';
		
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		
		$pageURL .= "://";
				
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			else
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}		
		$url = explode('/tshirtecommerce', $pageURL);	
		
		return $url[0].'/';
	}
	
	// load language
	public function lang($file = 'lang.ini')
	{
		// check language default
		$file_lang = dirname(ROOT) .DS. 'data' .DS. 'languages.json';
		if (file_exists($file_lang))
		{			
			$languages = json_decode(file_get_contents($file_lang));
			if (count($languages))
			{
				foreach($languages as $language)
				{
					if (isset($language->default) && $language->default == 1)
					{
						if (file_exists(ROOT .DS. 'data' .DS. $language->file))
						{
							$file = $language->file;
						}
					}
				}
			}
		}
		
		$file = ROOT .DS. 'data' .DS. $file;
		
		if (file_exists($file))
		{
			$data = parse_ini_file($file);
			if ($data === false || $data == null)
			{
				$content 	= file_get_contents($file);
				$data 		= parse_ini_string($content);
			}
			return $data;
		}
		else
		{
			return false;
		}
	}
	
	function openURL($url)
	{
		$data = false;
		if( function_exists('curl_exec') )
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			curl_close($ch);
		}
		
		if( $data == false && function_exists('file_get_contents') )
		{
			$data = file_get_contents($url);
		}
		
		return $data;
	}
	
	public function sendPostData($url, $args)
	{
		$postvars = '';
		foreach($args as $key=>$value) {
			$postvars .= $key . "=" . $value . "&";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);		
		$response = curl_exec($ch);
		
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
		curl_close($ch);
		
		if ($code == '404')
			return false;
		
		return $response;
	}
	
	// load view layout
	public function view($name)
	{
		$file = $this->components .DS. $name. '.php';
		
		if (file_exists($file))
		{
			include_once ($file);
		}		
	}
	
	// get products
	public function getProducts()
	{
		$file = $this->path_data .DS. 'products.json';
		
		if (file_exists($file))
		{
			$data 		= file_get_contents($file);
			$products 	= json_decode($data);
			if (isset($products->products))
				return $products->products;
			else
				return array();
		}
		else
		{
			return array();
		}
	}
	
	// get categories
	public function getCategories()
	{
		$file = $this->path_data .DS. 'categories.json';
		if (file_exists($file))
		{
			$data 		= file_get_contents($file);
			$categories 	= json_decode($data);			
			return $categories;
		}
		else
		{
			return array();
		}
	}
	
	// get categories
	public function getProductCategories()
	{
		$file = $this->path_data .DS. 'product_categories.json';
		if (file_exists($file))
		{
			$data 		= file_get_contents($file);
			$categories 	= json_decode($data);			
			return $categories;
		}
		else
		{
			return array();
		}
	}
	
	// get Cate to tree.
	public function categoriesToTree($categories, $cate_id = 0)
	{
		$map = array();	

		foreach ($categories as $category) {
			$category->subcategories = array();
			$map[$category->id] = $category;
		}
		foreach ($categories as $category) {		
			$map[$category->parent_id]->subcategories[] = $category;
		}
		if (isset($map[$cate_id]))
			return $map[$cate_id]->subcategories;
		else
			return array();
	}
	
	public function dispayTree($categories, $level = 0, $options = array('type'=>'checkbox', 'name'=>'category'), $cate_checked = array() )
	{
		if (!is_array($categories) OR empty($categories)) return '';
		
		$html = '';
		
		if(count($categories))
		{
			foreach($categories as $category)
			{	
				$checked = '';
				if($options['type'] == 'checkbox')
				{
					if( in_array( $category->id, $cate_checked) ) $checked = 'checked="checked"';			
					
					$html .= str_repeat('&emsp;&emsp;', $level) .'<input type="checkbox" '.$checked.' name="'.$options['name'].'" value="'.$category->id.'"/> '. $category->title . '<br />';
				}
				else if($options['type'] == 'select')
				{
					if( in_array($category->id, $cate_checked) ) $checked = 'selected="selected"';				
					
					$html .= '<option '.$checked.' value="'.$category->id.'">'. str_repeat('&emsp;', $level) .str_repeat('- ', $level). $category->title . '</option>';
				}
				
				if(count($category->subcategories) > 0)
				{
					$html .= $this->dispayTree($category->subcategories, $level + 1, $options, $cate_checked);
				}
			}
		}
		return $html;
	}
	
	// get products
	public function getSetting()
	{
		$file = $this->path_data .DS. 'settings.json';		
		if (file_exists($file))
		{
			$data 		= file_get_contents($file);			
			$settings 	= json_decode($data);			
			return $settings;
		}
		else
		{
			return array();
		}
	}
	
	/**
	 * Write File
	 *
	 * Writes data to the file specified in the path.
	 * Creates a new file if non-existent.
	 *
	 * @access	public
	 * @param	string	path to file
	 * @param	string	file data
	 * @return	bool
	 */
	public function WriteFile($path, $data)
	{
		if ( ! $fp = fopen($path, 'w'))
		{
			return FALSE;
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;
	}
	
	// read file
	public function readFile($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}

		if (function_exists('file_get_contents'))
		{
			return @file_get_contents($file);
		}

		if ( ! $fp = @fopen($file, FOPEN_READ))
		{
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}
	
	public function folder($type = 'uploaded')
	{
		$date 	= new DateTime();
		$year	= $date->format('Y');
		$root 	= $type .DS. $year;
		if (!file_exists(ROOT .DS. $root))
			mkdir(ROOT .DS. $root, 0755);
		
		$month 	= $date->format('m');
		$root 	= $root .DS. $month .DS;
		if (!file_exists(ROOT .DS. $root))
			mkdir(ROOT .DS. $root, 0755);
		
		return $root;
	}
	
	// get all file in foder
	public function getFiles($path, $exten = '.txt')
	{
		if (file_exists($path))
		{
			$files = scandir($path);
			if (count($files) == 0)
				return false;
			
			$list = array();
			for($i=0; $i<count($files); $i++)
			{
				if (strpos($files[$i], $exten) > 0)
				{
					$list[] = $files[$i];
				}
			}
			if (count($list) == 0) return false;
			
			return $list;
		}
		else
		{
			return false;
		}
	}
	
	// qrcode
	public function qrcode($text)
	{	
		include_once ROOT .DS. 'includes' .DS. 'libraries' .DS. 'qrcode.php';
		$qr = new qrcode();
		$qr->setText($text);
		
		$image = $qr->getImage(500);
		
		$root = $this->folder();
		
		$file = 'qrcode-'.strtotime("now") . '.png';
		
		$this->WriteFile(ROOT .DS. $root . $file, $image);
		
		return str_replace('\\', '/', $root .DS. $file);
	}
	
	// setup cache
	public function cache($folder = 'design')
	{
		require_once ROOT .DS. 'includes' .DS. 'libraries' .DS. 'phpfastcache.php';
		phpFastCache::setup("storage", "files");
		phpFastCache::setup("path", ROOT .DS. 'cache');
		phpFastCache::setup("securityKey", $folder);
		$cache = phpFastCache();
		
		return $cache;
	}
	
	public function saveDesign()
	{
		$results	= array();
		
		if ( !isset($_COOKIE['design']) )
		{
			$results['error'] = 1;
			$results['login'] = 1;
			$results['msg']	= lang('design_save_login');
			echo json_encode($results);
			exit;
		}
		
		// check user login
		$user = $_COOKIE['design'];
		
		$data = json_decode(file_get_contents('php://input'), true);
		
		$uploaded 	= $this->folder();
		$path		= ROOT .DS. $uploaded;
		
		$temp 		= explode(';base64,', $data['image']);
		$buffer		= base64_decode($temp[1]);
		
		$design 					= array();
		
		
		$design['vectors']			= $data['vectors'];		
		$design['teams']			= $data['teams'];	
		$design['fonts']			= $data['fonts'];
				
		$designer_id				= $data['designer_id'];
		
		// check design and author
		if ($data['design_file'] != '' && $designer_id == $user && $data['design_key'] != '')
		{
			// override file and update
			$file 			= $data['design_file'];
			
			$path_file		= ROOT .DS. str_replace('/', DS, $file);			
			$key			= $data['design_key'];
			$design['design_id'] 		= $key;
		}
		else
		{
			
			$key 		= strtotime("now"). rand();
			$file 		=  'design-' . $key . '.png';
			
			$path_file	= $path .DS. $file;
			$file		= str_replace('\\', '/', $uploaded) .'/'. $file;
			$file		= str_replace('//', '/', $file);		
			
			$design['design_id'] 		= $key;
		}
		
		
		if ( ! $this->WriteFile($path_file, $buffer))
		{
			$results['error'] = 1;
			$results['msg']	= lang('design_msg_save');
		}
		else
		{
			$cache = $this->cache();
			$myDesign = $cache->get($user);
			if ( $myDesign == null )
			{			
				$myDesign = array();
			}
			
			$design['image']			= $file;
			$design['parent_id']		= $data['parent_id'];
			$design['product_id']		= $data['product_id'];
			$design['product_options']  = $data['product_color'];
			
			$design['title']  			= '';
			$design['description']  	= '';				
			
			// save design to cache
			$myDesign[$key]	= $design;
			$cache->set($user, $myDesign);
			
			$results['error'] = 0;
			
			$content = array(
				'user_id'=> $user,
				'design_id'=> $key,
				'design_key'=> $key,
				'designer_id'=> $user,
				'design_file'=> $file,					
			);					
			$results['content'] = $content;	

		}
		
		echo json_encode($results);
		exit;
	}
	
	// get price of design
	public function prices($data)
	{
		// get data post
		$product_id		= $data['product_id'];
		$colors			= $data['colors'];
		$print			= $data['print'];		
		$quantity		= $data['quantity'];		
		
		// get attribute
		if ( isset( $data['attribute'] ) )
		{
			$attribute		= $data['attribute'];
		}
		else
		{
			$attribute		= false;
		}
				
		if ($quantity < 1 ) $quantity = 1;
		
		// load product
		$products 		= $this->getProducts();		
		$product 		= false;
		
		for($i=0; $i < count($products); $i++)
		{
			if ($product_id == $products[$i]->id)
			{
				$product = $products[$i];
				break;
			}
		}
		
		if ($product === false)
		{
			echo json_encode( array('error' => 'Product could not be found') );
			exit;
		}
		else
		{
			// load cart
			include_once (ROOT .DS. 'includes' .DS. 'cart.php');
			$cart 		= new dgCart();	
			$post 		= array(
				'colors' 		=> $colors,
				'print' 		=> $print,
				'attribute' 	=> $attribute,
				'quantity' 		=> $quantity,
				'product_id' 	=> $product_id					
			);
			
			// load setting			
			$setting 		= $this->getSetting();			
			$result 		= $cart->totalPrice($product, $post, $setting);
			
			// get cliparts
			$clipartsPrice = array();			
			
			$result->cliparts = $clipartsPrice;
			$result->quantity = $quantity;				
				
			$total	= new stdClass();
			$total->old = $result->price->base + $result->price->colors + $result->price->prints;
			$total->sale = $result->price->sale + $result->price->colors + $result->price->prints;
				
			if (count($result->cliparts))
			{
				foreach($result->cliparts as $view=>$art)
				{
					foreach($art as $id=>$amount)
					{
						$total->old 	= $total->old + $amount;
						$total->sale 	= $total->sale + $amount;
					}
				}
			}
			
			$total->old 	= ($total->old * $quantity) + $result->price->attribute;
			$total->sale 	= ($total->sale * $quantity) + $result->price->attribute;
			
			$total->old 	= number_format($total->old, 2, '.', ',');
			$total->sale 	= number_format($total->sale, 2, '.', ',');
			
			return $total;
		}	
	}
	
	public function getSVG($post)
	{
		
		$art_id		= $post['clipart_id'];			
		$type		= $post['file_type'];			
		$medium		= $post['medium'];			
		$url		= $post['url'];
		$file_name	= $post['file_name'];
		$colors		= $post['colors'];
						
		$file 	= $url . 'print/' . $file_name;			
		
		include_once (ROOT .DS. 'includes' .DS. 'libraries' .DS. 'svg.php');
					
		$data = array();
		$size = array();
		
		$size['height'] = 100;
		$size['width'] = 100;
		
		$xml = new svg($file, true);
			
		// get width, heigh of svg file
		$width = $xml->getWidth();
		$height = $xml->getHeight();
		
		// calculated width, height
		if($width > $height){
			$newHeight = $size['height'];
			$newWidth = ($size['height'] / $height) * $width;
		}else{
			$newWidth = $size['width'];
			$newHeight = ($size['width'] / $width) * $height;
		}
		
		// set width, height
		$xml->setWidth ($newWidth.'px');
		$xml->setHeight ($newHeight.'px');

		$data['content'] 		= $xml->asXML();
		$data['info']['type'] 	= 'svg';				
		$data['info']['colors'] = json_decode($colors);

		$data['size']['width'] 	= $newWidth . 'px';
		$data['size']['height'] = $newHeight . 'px';
		
		return $data;
	}
	
	// add to cart
	public function addCart($data)
	{
		// get data post
		$product_id		= $data['product_id'];
		$colors			= $data['colors'];
		$print			= $data['print'];		
		$quantity		= $data['quantity'];		
		
		// get attribute
		if ( isset( $data['attribute'] ) )
		{
			$attribute		= $data['attribute'];
		}
		else
		{
			$attribute		= false;
		}
				
		if ($quantity < 1 ) $quantity = 1;
		
		$time = strtotime("now");			
		
		if (isset($data['cliparts']))
		{
			$cliparts = $data['cliparts'];
		}
		else
		{
			$cliparts = false;
		}
		
		$content = array();
		$content['error'] = 1;
		
		// load product
		$products 		= $this->getProducts();		
		$product 		= false;
		
		for($i=0; $i < count($products); $i++)
		{
			if ($product_id == $products[$i]->id)
			{
				$product = $products[$i];
				break;
			}
		}
		
		if ($product === false)
		{
			$content['msg'] = 'Product could not be found';
		}
		else
		{			
			$content['error'] = 0;
			// load cart
			include_once (ROOT .DS. 'includes' .DS. 'cart.php');
			$cart 		= new dgCart();	
			$post 		= array(
				'colors' 		=> $colors,
				'print' 		=> $print,
				'attribute' 	=> $attribute,
				'quantity' 		=> $quantity,
				'product_id' 	=> $product_id					
			);
			
			// load setting			
			$setting 		= $this->getSetting();
			
			$result 		= $cart->totalPrice($product, $post, $setting);
						
			$result->product	= new stdClass();
			$result->product->name 	= $product->title;
			$result->product->sku 	= $product->sku;
			
			// get cliparts
			$clipartsPrice = array();			
			$result->cliparts = $clipartsPrice;
				
			$total	= new stdClass();
			//$total->old = $result->price->base + $result->price->colors + $result->price->prints;
			$total->old = $result->price->colors + $result->price->prints;
			//$total->sale = $result->price->sale + $result->price->colors + $result->price->prints;
			$total->sale = $result->price->colors + $result->price->prints;
			
			if (count($result->cliparts))
			{
				foreach($result->cliparts as $view=>$art)
				{
					foreach($art as $id=>$amount)
					{
						$total->old 	= $total->old + $amount;
						$total->sale 	= $total->sale + $amount;
					}
				}
			}
			
			$result->total 	= $total;
			
			// get symbol
			if (!isset($setting->currency_symbol))
				$setting->currency_symbol = '$';
			$result->symbol = $setting->currency_symbol;
			
			// save file image design
			$path = $this->folder();
			$design = array();
			$design['images'] = array();
			if (isset($data['design']['images']['front']))
				$design['images']['front'] 	= $this->createFile($data['design']['images']['front'], $path, 'cart-front-'.$time);
					
			if (isset($data['design']['images']['back']))	
				$design['images']['back'] 	= $this->createFile($data['design']['images']['back'], $path, 'cart-back-'.$time);
				
			if (isset($data['design']['images']['left']))
				$design['images']['left'] 	= $this->createFile($data['design']['images']['left'], $path, 'cart-left-'.$time);
				
			if (isset($data['design']['images']['right']))
				$design['images']['right']	= $this->createFile($data['design']['images']['right'], $path, 'cart-right-'.$time);
						
				
			if (empty($result->options)) $result->options = array();
			
			if (isset($data['teams'])) $teams = $data['teams'];
			else $teams = '';
			
			// add cart
			$item 	= array(
				'id'      		=> $result->product->sku,
				'product_id'    => $data['product_id'],
				'qty'     		=> $data['quantity'],
				'teams'     	=> $teams,
				'price'   		=> $result->total->sale,
				'prices'   		=> json_encode($result->price),
				'cliparts'   	=> json_encode($result->cliparts),
				'symbol'   		=> $result->symbol,
				'customPrice'   => $result->price->attribute,
				'name'    		=> $result->product->name,
				'time'    		=> $time,
				'options' 		=> json_decode(json_encode($result->options), true)
			);
			
			$rowid			= md5($result->product->sku . $time);
			$cache			= $this->cache('cart');			
			

			$designs		= array(
				'color' => $data['colors'][key($data['colors'])],
				'images' => $design['images'],
				'vector' => $data['design']['vectors'],
				'fonts' => $data['fonts'],
				'item' => $item
			);
			$cache->set($rowid, $designs);
						
			$content['product'] = array(
				'rowid'=> $rowid,
				'price'=> $result->total->sale,
				'quantity'=> $data['quantity'],
				'color_hex' => $data['colors'][key($data['colors'])],
				'color_title' => $product->design->color_title[key($data['colors'])],
				'images'=> json_encode($design['images']),
				'teams'=> $teams,
				'options' => json_encode($result->options)
			);
		}
		
		return $content;
	}
	
	public function createFile($data, $path, $file)
	{
		$temp 		= explode(';base64,', $data);
		$buffer		= base64_decode($temp[1]);
		
		$path_file 	= ROOT .DS. $path .DS. $file .'.png';
		$path_file	= str_replace('/', DS, $path_file);
		
		if ( $this->WriteFile($path_file, $buffer) === false)
			return '';
		else
			return str_replace('\\', '/', $path .DS. $file .'.png');
	}
	
	public function getColors()
	{
		$file = $this->path_data .DS. 'colors.json';		
		if (file_exists($file))
		{
			$data 		= file_get_contents($file);			
			$str 	= json_decode($data);			
			if (isset($str->colors))
			{
				return $str->colors;
			}
			else
			{
				return array();
			}
		}
		else
		{
			return array();
		}
	}
	
	public function redirect($url)
	{
		$site_url 	= $this->url();
		$site_url	= $site_url . '/tshirtecommerce/admin/';
		$url 		= $site_url . $url;
		$url 		= str_replace('//tshirtecommerce', '/tshirtecommerce', $url);

		$url = str_replace('tshirtecommerce/admin/index.php/', 'tshirtecommerce/admin/index.php?/', $url);
	
		header("Location: ".$url);
		die();
	}
}

// get language
function lang($key, $string = false)
{
	$lang = $GLOBALS['lang'];	
	
	if ( isset($lang[$key]) )
	{
		$txt = $lang[$key];
	}
	else
	{
		$txt = '';
	}
	
	if($string === false)
		echo $txt;
	else
		return $txt;
			
}

function setValue($data, $key, $default = '')
{
	
	if (!isset($data)) return $default;
	
	if (is_array($data))
	{
		if (isset($data[$key]))
			return $data[$key];
		else
			return $default;
	}
	elseif (is_object($data))
	{
		if (isset($data->$key))
			return $data->$key;
		else
			return $default;
	}
	
	return $default;
}

// get images
function getImgage($str){
	
	$data = str_replace("'", '"', $str);
	$data = json_decode($data);
	
	if( count($data) > 0 )
	{
		foreach($data as $vector)
		{
			if( isset($vector->img) && $vector->img != '' )
			{
				$img = $vector->img;
				return base_url($img);
			}
		}
	}
	
	return '';
	
}

function base_url($url)
{
	return $url;
}

function site_url($url = '', $is_admin = true)
{
	$site_url = $GLOBALS['site_url'];	
	$url = $site_url.$url;
	
	$url = str_replace('//tshirtecommerce', '/tshirtecommerce', $url);
	$url = str_replace('tshirtecommerce/admin/index.php/', 'tshirtecommerce/admin/index.php?/', $url);
	
	if ($is_admin === false)
	{
		$url = str_replace('admin/', '', $url);
	}
	
	return $url;
}

// get url of image in clipart
function imageArt($art)
{	
	$url  = $art->url;
	
	$images = new stdClass();
	$images->thumb  	= $url . $art->thumb;
	$images->medium 	= $url . $art->medium;
	
	return $images;
}

function dispayCateTree($categories, $level = 0, $cate_checked = array() , $remove_id = '')
{
	if (!is_array($categories) OR empty($categories)) return '';
	
	$html = '';
	
	if(count($categories))
	{
		foreach($categories as $category)
		{	
			if($category->id != $remove_id)
			{
				$checked = '';
				if( in_array($category->id, $cate_checked) ) $checked = 'selected="selected"';				
				
				$html .= '<option '.$checked.' value="'.$category->id.'">'. str_repeat('&emsp;', $level) .str_repeat(' ', $level). $category->title . '</option>';
				
				if(isset($category->children) && count($category->children) > 0)
				{
					$html .= dispayCateTree($category->children, $level + 1, $cate_checked, $remove_id);
				}
			}
		}
	}
	return $html;
}

function imageURL($src)
{
	if ($src == '') return '';
	
	if (strpos($src, 'http') !== false)
		return $src;
	
	$site_url 	= $GLOBALS['site_url'];
	$url 		= str_replace('//tshirtecommerce', '/tshirtecommerce', $site_url);
	$temp 		= explode('tshirtecommerce/', $url);
	
	return $temp[0].'/tshirtecommerce/'.$src;
}

function displayRadio($name, $data, $key, $default = 1)
{
	if (isset($data[$key]))
		$value = $data[$key];
	else
		$value = $default;
	
	echo '<label class="radio-inline">';
	if ($value == 1)	
		echo '<input type="radio" name="setting['.$name.']" value="1" checked="checked"> Yes';
	else
		echo '<input type="radio" name="setting['.$name.']" value="1"> Yes';
	echo '</label>';
	
	echo '<label class="radio-inline">';
	if ($value == 0)	
		echo '<input type="radio" name="setting['.$name.']" value="0" checked="checked"> No';
	else
		echo '<input type="radio" name="setting['.$name.']" value="0"> No';
	echo '</label>';
}