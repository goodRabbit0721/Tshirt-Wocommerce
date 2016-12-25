<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * ajax
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
error_reporting(0);
session_start();
date_default_timezone_set('America/Los_Angeles');
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

if ( isset($_GET['type']) )
{
	$type = $_GET['type'];
}
else
{
	$type = '';
}

require_once ROOT .DS. 'includes' .DS. 'functions.php';
$dg = new dg();
$lang = $dg->lang();

switch($type){
	case 'upload':
		
		require_once ROOT .DS. 'includes' .DS. 'upload.php';
		$data = array();
		$data['status'] = 0;
		if (!empty($_FILES['myfile']))
		{			
			$root		= $dg->folder();
			
			$uploader   =   new Uploader();
			$uploader->setDir(ROOT .DS. $root);

			$uploader->setExtensions(array('jpg','jpeg','png','gif'));
			$uploader->setMaxSize(10);
			$uploader->sameName(false);
			
			if($uploader->uploadFile('myfile'))
			{
				$data['status'] = 1;
				$image  		=   $uploader->getUploadName();
				$data['src'] 	= $root .'/'. $image;
				$data['src']	= str_replace(DS, '/', $data['src']);
				
				$data['item'] 	= array(
					'title'=> $image,
					'url'=> $data['src'],
					'file_name'=> $image,
					'thumb'=> $data['src'],
					'file_type'=> 'image'
				);
			}
			else
			{
				$data['status'] = 0;
				$data['msg'] 	= $uploader->getMessage(); //get upload error message 
			}			

		}
		echo json_encode($data); exit;
		break;
		
	case 'uploadIE':
		$data 		= $_POST['myfile'];
		$temp 		= explode(';base64,', $data);
		$buffer		= base64_decode($temp[1]);
		
		$root		= $dg->folder();
		$file 		= strtotime("now") . '.png';
		$path_file	= ROOT .DS. $root . $file;
		
		$data = array();
		$data['status'] = 0;
		if ( ! $dg->WriteFile($path_file, $buffer))
		{
			$data['status'] = 0;
			$data['msg']	= 'Can not upload file. Please try again.';
		}
		else
		{
			$src = str_replace('\\', '/', $root . $file);			
			$data['status'] = 1;			
			$data['src'] =$src;
			
			$data['src']	= str_replace(DS, '/', $data['src']);
			
			$data['item'] = array(
				'title'=> $file,
				'url'=> $data['src'],
				'file_name'=> $file,
				'thumb'=> $data['src'],
				'file_type'=> 'image'
			);
		}
		echo json_encode($data); exit;
		break;
		
	case 'qrcode':
		$text = $_GET['text'];		
		$file = $dg->qrcode($text);
		echo $file;
		break;
		
	case 'user':
		$email 		= $_POST['email'];
		$password 	= $_POST['password'];
		$id = md5($email.$password);
		setcookie('design', $id, time() + (86400 * 1000), "/");
		echo $id; exit;
		break;
	
	// save design
	case 'saveDesign':
		$dg->saveDesign();
		break;
	
	// remove design
	case 'removeDesign':
		if (empty($_SESSION['is_logged']) && $_SESSION['is_logged'] === false)
		{
			echo 'Please login!';
			exit;
		}
		
		$is_logged 	= $_SESSION['is_logged'];
		$user 		= md5($is_logged['id']);
		$id = $_GET['id'];
		$ids = explode(':', $id);
		if (count($ids) == 2)
		{
			if ( $user == $ids[0] )
			{
				if ($is_logged['is_admin'] === true)
				{
					$cache = $dg->cache('admin');
				}
				else
				{
					$cache = $dg->cache();
				}
				$designs = $cache->get($user);
				unset($designs[$ids[1]]);
				$cache->set($user, $designs);
			}
		}
		break;

	// load design
	case 'userDesign':
		
		if (empty($_SESSION['is_logged']) && $_SESSION['is_logged'] === false)
		{
			echo 'Please login';
			exit;
		}
		$is_logged = $_SESSION['is_logged'];
		$user_id = md5($is_logged['id']);
		
		// get old data
		if (isset($_COOKIE['design']))
		{
			$user = $_COOKIE['design'];
			include_once(ROOT .DS. 'admin' .DS. 'config' .DS. 'config.php');
			$code 	= md5($config['email'].$config['password']);
			if ($user == $code)
			{				
				$cache = $dg->cache('admin');
			}
			else
			{
				$cache = $dg->cache();
			}
			$designs = $cache->get($user);
			if ($designs != null && count($designs) > 0)
			{				
				// move data
				if ($is_logged['is_admin'] === true)
				{
					$cache = $dg->cache('admin');
				}
				else
				{
					$cache = $dg->cache();
				}
				$cache->set($user_id, $designs);
				setcookie('design', $user, time() - (86400 * 1000), "/");
			}
		}
		$user = $user_id;
		
		// get all design
		if ($is_logged['is_admin'] === true)
		{			
			$cache = $dg->cache('admin');
		}
		else
		{
			$cache = $dg->cache();
		}
		$designs = $cache->get($user_id);
					
		$baseURL = $_POST['url'];
		if (strpos($baseURL, '?') > 0)
		{
			$url = '&design=';
		}
		else
		{
			$url = '?design=';
		}		
		
		if ($designs == null || count($designs) == 0)
		{
			echo lang('design_msg_save_found', true);
			return;
		}
		else
		{	
			$designs = array_reverse($designs, true);
			// get page
			if ($_POST['datas'])
			{
				$datas = $_POST['datas'];
				if (isset($datas['page']))
					$page = $datas['page'];
			}
			if (empty($page))
				$page = 0;
			
			$html = '';
			$i = 0;
			$number = 8;
			foreach($designs as $key => $design)
			{
				$i++;
				if ($i <= ($number * $page)) continue;
				if ($i > ($number * ($page+1))) break;
				
				$link = $url.$user.':'.$key.':'.$design['product_id'].':'.$design['product_options'].':'.$design['parent_id'].'&parent_id='.$design['parent_id'];				
				$html .= '<div class="col-xs-6 col-sm-4 col-md-3 design-box">'
						. 	'<a href="'.$link.'" title="'.lang('design_load', true).'">'
						.		'<img src="'.$design['image'].'" class="img-responsive img-thumbnail" alt="">';
				if ($design['title'] != '')
				{
					$html 	.= '<span title="'.$design['description'].'" class="text-muted"><small>'.$design['title'].'</small></span>';
				}
				
				$html 	.=	'</a>'
						.	'<span class="design-action design-action-remove" onclick="design.ajax.removeDesign(this)" data-id="'.$user.':'.$key.'" title="Remove this design"><i class="red glyphicons remove_2"></i></span>'
						. '</div>';
			}
			echo $html;
		}
		break;
	
	// load design idea
	case 'loadDesign':
		if (isset($_GET['user_id']))
			$user_id 	= $_GET['user_id'];
		else
			$user_id = '';
		
		if (isset($_GET['design_id']))		
			$design_id 	= $_GET['design_id'];
		else
			$design_id = '';
		
		$result	= array();
		$result['error'] 		= 0;
		if ($user_id == '' || $design_id == '')
		{
			$result['error'] 	= 1;
			$result['msg'] 		= lang('design_msg_save_found', true);
		}
		else
		{							
			if ($result['error'] == 0)
			{				
				$cache = $dg->cache();
				$designs = $cache->get($user_id);
				if ($designs == null || empty ($designs[$design_id]))
				{
					$cache = $dg->cache('admin');
					$designs = $cache->get($user_id);
				}
							
				
				if ($designs == null || empty ($designs[$design_id]))
				{
					$result['error'] 	= 1;
					$result['msg'] 		= lang('design_msg_save_found');
				}
				else
				{
					$designs[$design_id]['user_id'] = $user_id;
					$designs[$design_id]['design_id'] = $design_id;
					$designs[$design_id]['id'] = $design_id;					
					$result['design'] 	= $designs[$design_id];
					$result['msg'] 		= '';
				}
			}
			else
			{
				$result['error'] 	= 1;
				$result['msg'] 		= lang('design_msg_save_found');
			}
		}
		
		echo json_encode($result);
		exit;
		break;
		
	// load design of add to cart
	case 'cartDesign':
		if (isset($_GET['cart_id']))
			$cart_id 	= $_GET['cart_id'];
		else
			$cart_id = '';
				
		$result	= array();
		$result['error'] 		= 0;
		if ($cart_id == '')
		{
			$result['error'] 	= 1;
			$result['msg'] 		= lang('design_msg_save_found', true);
		}
		else
		{							
			if ($result['error'] == 0)
			{				
				$cache 	= $dg->cache('cart');
				$design = $cache->get($cart_id);
				
				if ($design == null)
				{
					$result['error'] 	= 1;
					$result['msg'] 		= lang('design_msg_save_found');
				}
				else
				{					
					//echo '<pre>'; print_r($design); exit;
					$result['design'] 	= $design;
					$result['msg'] 		= '';
				}
			}
			else
			{
				$result['error'] 	= 1;
				$result['msg'] 		= lang('design_msg_save_found');
			}
		}
		
		echo json_encode($result);
		exit;
		break;
		
	case 'cateArts':
		$dg->categoriestree(false);
		break;
	case 'arts':
		$file = ROOT .DS. 'data' .DS. 'arts.json';
		$arts = array('count'=>0, 'arts'=>array());
		if (file_exists($file))
		{
			$str 		= file_get_contents($file);
			$rows 		= json_decode($str);
			if ($rows->count > 0)
			{
				$arts['count']  = $rows->count;
				$arts['arts']	= array_reverse($rows->arts);
			}
		}
		echo json_encode($arts);
		break;
		
	case 'prices':
		$data = file_get_contents('php://input');
		$data = json_decode($data, true);
		$productId = $data.product_id;
		echo $product_id;
		$prices = $dg->prices($data);
		if ($productId == "70") {
				$prices->sale = "123";
		}
		echo json_encode($prices);
		exit;
		break;
		
	case 'svg':
		$data = $_POST;		
		$svg = $dg->getSVG($data);
		echo json_encode($svg);
		exit;
		break;
	
	case 'addCart':
		header('Content-Type: text/html; charset=UTF-8');
		$data = file_get_contents('php://input');
		$data = json_decode($data, true);
		$content = $dg->addCart($data);
		echo json_encode($content);
		exit;
		break;
	case 'fonts':
		$file = ROOT .DS. 'data' .DS. 'fonts.json';
		$fonts = array('status'=>1, 'fonts'=>array());
		if (file_exists($file))
		{
			$str 		= @file_get_contents($file);
			$rows 		= json_decode($str, true);		
			
			if (empty($rows['fonts']))
				$rows['fonts'] = array();
			
			if (empty($rows['fonts']['categories']))
			{
				$file = ROOT .DS. 'admin' .DS. 'data' .DS. 'font_categories.json';
				if (file_exists($file))
				{
					$str 							= @file_get_contents($file);
					$categories 					= json_decode($str, true);
					$rows['fonts']['categories']	= array();
					$rows['fonts']['cateFonts']		= array();
										
					for($i=0; $i< count($categories); $i++)
					{
						$category = array(
							'cate_id' => $i,
							'title' => $categories[$i],
							'id' => $i,
							'type' => 'google',
						);
						$rows['fonts']['categories'][] 				= $category;
						
						$rows['fonts']['cateFonts'][$i] 			= array();
						$rows['fonts']['cateFonts'][$i]['fonts'] 	= array();
						for($j=0; $j<count($rows['fonts']['fonts']); $j++)
						{
							
							$font = $rows['fonts']['fonts'][$j];						
							
							if ($i == $font['cate_id'])
							{
								$rows['fonts']['cateFonts'][$i]['fonts'][] = $font;
							}
						}
					}
					
					$fonts = $rows;
				}
			}
			else
			{
				$fonts = $rows;
			}
		}
		echo json_encode($fonts);
		break;		
	case 'categories':
		$data = array(
			'error' => 0,
			'categories' => ''
		);
		
		$file = ROOT .DS. 'data' .DS. 'categories.json';
		if (file_exists($file))
		{
			$content 	= file_get_contents($file);
			if ($content != false)
			{
				$array 		= json_decode($content);
				$category 	= array();
				
				if (empty($_POST['parent_id']))
					$parent_id = 0;
				else
					$parent_id = $_POST['parent_id'];
				
				for($i=0; $i<count($array); $i++)
				{
					if ($parent_id == $array[$i]->parent_id)
					{
						$category[] = $array[$i];
					}
				}
				$data = array(
					'error' => 0,
					'categories' => $category
				);
			}
		}
		
		echo json_encode($data); exit;
		break;

	case 'addon':
		if ( isset($_GET['task']) )
		{
			$task 	= $_GET['task'];
			
			$file 	= ROOT .DS. 'addons' .DS. 'ajax' .DS. $task.'.php';
			if ( file_exists($file) )
			{
				include_once($file);
			}
		}
		break;
	case 'iframeupload':
		echo json_encode($_FILES); exit;
		break;
		
	default:
		break;
}