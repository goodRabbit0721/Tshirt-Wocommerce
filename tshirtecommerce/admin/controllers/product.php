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

class Product extends Controllers
{
	
	public function index()
	{	
		$data = array();
		
		$data['title'] 		= 'Products';
		$data['sub_title'] 	= 'Manage';
		
		$dgClass 			= new dg();	
		$products 			= $dgClass->getProducts();	
		
		//sort array().
		$sort = array();
		foreach($products as $key=>$val)
		{
			$count = 0;
			$vl = array();
			foreach($products as $k=>$v)
			{
				if($count <= $k && !isset($sort[$k]))
				{
					$count = $k;
					$vl = $v;
				}
			}
			$sort[$count] = $vl;
		}
		
		// get admin info.
		include_once(ROOT .DS. 'config' .DS. 'config.php');
		$code 			= md5($config['email'] .'design-template'. $config['password']);		
		
		$url_design 	= str_replace('admin/', 'admin-template.php', site_url());
		
		$data['url_design']	= $url_design;
		$data['products']	= $sort;
		
		$this->view('products', $data);
	}
	
	public function page($segment = 0)
	{
		// get Products.
		$dgClass 			= new dg();	
		$products 			= $dgClass->getProducts();		
		
		if(isset($products))
		{
		
			$search = array();
			foreach($products as $key=>$val)
			{
				if(!empty($_POST['search_product']))
				{
					if(strpos(strtolower($val->title), strtolower($_POST["search_product"])) !== false)
						$search[$key] = $val;
				}else
				{
					$search[$key] = $val;
				}
			}
			
			//sort array().
			$sort = array();
			foreach($search as $key=>$val)
			{
				$count = 0;
				$vl = array();
				foreach($search as $k=>$v)
				{
					if($count <= $k && !isset($sort[$k]))
					{
						$count = $k;
						$vl = $v;
					}
				}
				$sort[$count] = $vl;
			}
			
			$page = array();
			if(isset($_POST['per_page']))
				$perpage = $_POST['per_page'];
			else
				$perpage = 10; 
				
			if($perpage == 'all')
				$perpage = count($sort);
			$j = 1;
			foreach($sort as $key=>$val)
			{
				if($j > $segment && $j <= ($perpage+$segment))
					$page[$key] = $sort[$key];
				$j++;
			}
			
			if($perpage < count($sort))
				$data['page'] = $perpage;
			else
				$data['page'] = 0;
			$data['products'] = $page;
			$data['total'] = count($sort);
			$data['segment'] = $segment;
			
			include_once(ROOT .DS. 'config' .DS. 'config.php');
			$code 			= md5($config['email'] .'design-template'. $config['password']);		
			
			$url_design 	= str_replace('admin/', 'admin-template.php', site_url());
			$url_design 	= $url_design . '?token='.$code;
			
			$data['url_design']	= $url_design;
						
			include_once(ROOT.DS.'views/product.php');
		}
		else
		{
			return;
		}
	}
	
	public function edit($id = 0, $error = 2)
	{
		$data = array();
		
		$data['title'] 		= 'Product';
		$data['sub_title'] 	= 'Add';
		$data['cate_checked']	= array();
		
		$is_new = true;
		if ($id > 0)
		{
			$dgClass 			= new dg();	
			$products 			= $dgClass->getProducts();		
			if (count($products) > 0)
			{
				foreach($products as $row)
				{
					if ($row->id == $id)
					{
						$is_new = false;
						$product = $row;
						break;
					}
				}
			}
			$product_cate = $dgClass->getProductCategories();	//var_dump($product_cate);exit;
			$data_cate = array();			
			foreach((array)$product_cate as $val)
			{
				if($val->product_id == $id)
					$data_cate[] = $val->cate_id;
			}
			$data['cate_checked'] = $data_cate;
		}
		
		if ($is_new === true)
		{
			$product 		= new stdClass();			
		}
		$data['error']		= $error;
		$data['product']	= $product;
		
		$this->view('edit_product', $data);
	}
	
	public function colors($f = null, $id = null)
	{
		$data = array();
		$dgClass 			= new dg();	
		$colors 			= $dgClass->getColors();
		$data['colors']		= $colors;
		$data['function'] 	= $f;			
		$data['id'] 		= $id;
		
		$this->modal('color', $data);
	}
	
	public function design()
	{
		$data = array();
		$data['position'] 			= $_POST['position'];
		$data['color'] 				= $_POST['color'];
		$data['title'] 				= $_POST['title'];
		$data['number'] 			= $_POST['number'];
		
		$this->modal('design', $data);
	}
	
	public function save()
	{
		$data = $_POST['product'];
		
		$attributes = array();
		if (isset($data['fields']) && count($data['fields']) > 0)
		{
			$attributes['name'] 		= array();
			$attributes['prices'] 		= array();
			$attributes['titles'] 		= array();
			$attributes['type'] 		= array();
			$i=0;
			foreach($data['fields'] as $filed)
			{				
				$attributes['name'][$i] 	= $filed['name'];
				$attributes['prices'][$i] 	= $filed['prices'];
				$attributes['titles'][$i] 	= $filed['titles'];
				$attributes['type'][$i] 	= $filed['type'];
				$i++;
			}
		}
		unset($data['fields']);
		$data['attributes'] = $attributes;
		
		$dgClass 				= new dg();
		$content 				= array('products'=> array());		
		// get id
		if ($data['id'] == 0)
		{
			$id 				= 1;
			$products 			= $dgClass->getProducts();		
			if (count($products) > 0)
			{
				foreach($products as $product)
				{
					if ($product->id > $id)
						$id = $product->id;
				}
				$id = $id + 1;
			}
			$data['id'] = $id;
			
			$products[] = $data;
			$content['products'] = $products;
		}
		else
		{			
			$products 			= $dgClass->getProducts();
			
			$is_new 			= true;
			if (count($products) > 0)
			{
				foreach($products as $product)
				{
					if ($product->id == $data['id'])
					{
						$content['products'][]  = $data;
						$is_new 	= false;
					}
					else
					{
						$content['products'][] = $product;
					}
				}
			}
			
			if ($is_new === true)
			{
				$products[] = $data;
				$content['products']	= $products;
			}
		}
		
		$content = str_replace('\\\\', '', json_encode($content));
		
		// write file
		$path = dirname(ROOT) .DS. 'data' .DS. 'products.json';
		$check = $dgClass->WriteFile($path, $content);
		
		// update categories.		
		if(isset($_POST['category']))
		{
			$categories = $_POST['category'];
			$data_cate = array();
			$category = $dgClass->getProductCategories();
			if(count($categories))
			{
				$cid = 0;
				if(count($category))
				{
					foreach($category as $val)
					{
						if($val->product_id != $data['id'])
						{
							$data_cate[] = $val;
							if($val->id > $cid)
								$cid = $val->id;
						}
					}
				}
				
				foreach($categories as $val)
				{
					$cid++;
					$catedt = new stdClass();
					$catedt->id = $cid;
					$catedt->product_id = $data['id'];
					$catedt->cate_id = $val;
					$data_cate[] = $catedt;
				}
			}
			else
			{
				if(count($category))
				{
					foreach($category as $val)
					{
						if($val->product_id != $data['id'])
						{
							$data_cate[] = $val;
						}
					}
				}
			}
		}
		else
		{
			$category = $dgClass->getProductCategories();
			if(count($category))
			{
				foreach($category as $val)
				{
					if($val->product_id != $data['id'])
					{
						$data_cate[] = $val;
					}
				}
			}
		}
		// write file
		$path = dirname(ROOT) .DS. 'data' .DS. 'product_categories.json';
		$check = $dgClass->WriteFile($path, json_encode($data_cate));
		
		if ($check === false)
			$dgClass->redirect('index.php/product/edit/'.$data['id'].'/0');
		else
			$dgClass->redirect('index.php/product/edit/'.$data['id'].'/1');
	}
	
	public function unpublish($id = 0)
	{
		$dgClass 				= new dg();
		
		if ($id > 0)
		{
			$products 			= $dgClass->getProducts();				
			if (count($products) > 0)
			{
				$content 		= array('products'=> array());
				foreach($products as $product)
				{
					if ($product->id == $id)
					{
						$product->published = 0;
					}
					$content['products'][] = $product;
				}
			}
			$path = dirname(ROOT) .DS. 'data' .DS. 'products.json';
			$check = $dgClass->WriteFile($path, json_encode($content));
		}
		
		
		$dgClass->redirect('index.php/product');
	}
	
	public function publish($id = 0)
	{
		$dgClass 				= new dg();
		
		if ($id > 0)
		{
			$products 			= $dgClass->getProducts();				
			if (count($products) > 0)
			{
				$content 		= array('products'=> array());
				foreach($products as $product)
				{
					if ($product->id == $id)
					{
						$product->published = 1;
					}
					$content['products'][] = $product;
				}
			}
			$path = dirname(ROOT) .DS. 'data' .DS. 'products.json';
			$check = $dgClass->WriteFile($path, json_encode($content));
		}
		
		
		$dgClass->redirect('index.php/product');
	}
	
	public function Copy($id = '')
	{
		// get id product copy
		if(isset($_POST['ids']) && $_POST['ids'] != '')
		{
			$ids = $_POST['ids'];
		}
		else
		{
			if ($id != '' && (int) $id > 0)
				$ids = array($id);
			else
				$ids = array();
		}
		
		//get data product.
		$dgClass 				= new dg();
		$products 				= $dgClass->getProducts();
		$categories 			= $dgClass->getProductCategories();
		
		$content 				= array();
		$content['products'] 	= $products;
		
		// copy products.
		if(count($ids) > 0)
		{
			$product_id = 1;
			$category_id = 1;
			if (count($products) > 0)
			{	
				foreach($ids as $id)
				{
					// copy products.
					$data	= array();
					foreach($products as $product)
					{
						if ($product->id > $product_id)
							$product_id = $product->id;
							
						if ($product->id == $id)
						{
							$data	= (array) $product;
						}
					}
					if(count($data))
					{
						$product_id = $product_id + 1;
						$data['id'] = $product_id;
						$data['sku'] = 'copy'.$product_id;
						$data['title'] = '(copy) '.$data['title'];
						$content['products'][] = (object) $data;
					}
					
					// copy categories.
					if(count($categories))
					{
						foreach($categories as $category)
						{
							if ($category->id > $category_id)
								$category_id = $category->id;
						}	
						foreach($categories as $category)
						{
							$category_data	= array();
							$cate_id = '';
							if ($category->product_id == $id)
							{
								$cate_id = $category->cate_id;
								$category_data	= (array) $category;
							}
							if(count($category_data))
							{
								$category_id = $category_id + 1;
								$category_data['id'] = $category_id;
								$category_data['product_id'] = $product_id;
								$category_data['cate_id'] = $cate_id;
								$categories[] = (object) $category_data;
							}
						}
					}
				}
				
				$content = json_encode($content);
				$path = dirname(ROOT) .DS. 'data' .DS. 'products.json';
				$check = $dgClass->WriteFile($path, $content);
				$path = dirname(ROOT) .DS. 'data' .DS. 'product_categories.json';
				$check = $dgClass->WriteFile($path, json_encode($categories));
				
				$dgClass->redirect('index.php/product');
			}
		}
			
		$dgClass->redirect('index.php/product');
	}
	
	public function delete($id = 0)
	{
		//get data product.
		$dgClass 				= new dg();
		$products 				= $dgClass->getProducts();
		$categories 			= $dgClass->getProductCategories();
		
		//get id products
		if(isset($_POST['ids']) && $_POST['ids'] != '')
		{
			$ids = $_POST['ids'];
		}
		else
		{
			if ($id != '' && (int) $id > 0)
				$ids = array($id);
			else
				$ids = array();
		}
		
		if (count($ids) > 0)
		{	
			//remove products.
			if (count($products) > 0)
			{
				$content['products'] = array();
				foreach($products as $product)
				{
					if (!in_array($product->id, $ids))
					{
						$content['products'][] = $product;
					}
				}
			}
			
			$content = json_encode($content);
			
			$path = dirname(ROOT) .DS. 'data' .DS. 'products.json';
			$dgClass->WriteFile($path, $content);
			
			//remove categories.
			if (count($categories) > 0)
			{
				$category_data = array();
				foreach($categories as $category)
				{
					if (!in_array($category->product_id, $ids))
					{
						$category_data[] = $category;
					}
				}
				$category_data = json_encode($category_data);
			
				$path = dirname(ROOT) .DS. 'data' .DS. 'product_categories.json';
				$dgClass->WriteFile($path, $category_data);
			}
		}
		
		$dgClass->redirect('index.php/product');
	}
	
	public function category()
	{
		if (isset($_POST['title']))
		{
			$title 		= $_POST['title'];
		}		
		
		if (isset($_POST['cateid']))
		{
			$parent_id 	= $_POST['cateid'];
		}
		
		if (isset($_POST['ids']))
		{
			$ids 	= $_POST['ids'];
		}
		else
		{
			$ids 	= array();
		}
		
		$dgClass = new dg();
		
		if(!empty($title))
		{
			$categories = $dgClass->getCategories();
			$cate_data = array();
			
			$cate_id = 0;
			if($parent_id == '')
				$parent_id = 0;
				
			foreach($categories as $cate)
			{
				$cate_data[] = array(
					'id' => $cate->id,
					'parent_id' => $cate->parent_id,
					'title' => $cate->title
				);
				
				if($cate->id > $cate_id)
					$cate_id = $cate->id;
			}
			
			$cate_data[] = array(
				'id' => $cate_id + 1,
				'parent_id' => $parent_id,
				'title' => $title
			);
			
			$path = dirname(ROOT) .DS. 'data' .DS. 'categories.json';
			$check = $dgClass->WriteFile($path, json_encode($cate_data));
		}
		elseif(count($ids))
		{
			$categories = $dgClass->getCategories();
			$cate_data = array();
			
			foreach($categories as $val)
			{
				if(!in_array($val->id, $ids))
					$cate_data[] = $val;
			}
			$path = dirname(ROOT) .DS. 'data' .DS. 'categories.json';
			$check = $dgClass->WriteFile($path, json_encode($cate_data));
		}
		
		$categories = $dgClass->getCategories();
		$categories = $dgClass->categoriesToTree($categories);
		$data['content'] 	= $dgClass->dispayTree( $categories, 0, array('type'=>'checkbox', 'name'=>'category[]') );				
		$data['list'] 		= '<option value="0">'. lang('product_parent_category', true) . '</option>' . $dgClass->dispayTree( $categories, 0, array('type'=>'select', 'name'=>'') );				
		echo json_encode($data);
		return;
	}
	
	# function: import data from CSV file
	public function import()
	{
		$dg = new dg();
		
		# get data from product.json file to array 1
		$file 		= dirname(ROOT) .DS. 'data' .DS. 'products.json';		
		$product 	= $dg->readFile($file);
		$products 	= json_decode($product, true);
		if(isset($products['products']))
		{
			$data['products'] = $products['products'];
		}
		else
		{
			$data['products'] = array();
		}
		
		# get data from product_categories.json
		$file_cat 	= dirname(ROOT) .DS. 'data' .DS. 'product_categories.json';
		$cat		= $dg->readFile($file_cat);
		$cats		= json_decode($cat, true);
		
		# get max id in product_categories.json file
		$max_id = 0;
		foreach($cats as $v)
		{
			if($v['id'] > $max_id) $max_id = $v['id'];
		}
		$max_id++;
		
		# get data from CSV file to array 2
		$file_uploaded_name = '';
		$csv = array();
		if (isset($_FILES["fileToImport"]))
		{
			# upload directory
			$upload_path = dirname(ROOT) .DS. 'uploaded' .DS. 'csv' . DS;
			
			# upload file
			require_once dirname(ROOT) .DS. 'includes' .DS. 'upload.php';
			$uploader   =   new Uploader();
			$uploader->imageSeq = 'import-products';
			$uploader->setDir($upload_path);

			$uploader->setExtensions(array('csv'));
			$uploader->setMaxSize(20);
			$uploader->sameName(false);
			if($uploader->uploadFile('fileToImport'))
			{
				$file_uploaded_name = $uploader->getUploadName();
				
				# get data from csv uploaded
				# Set path to CSV file
				$csvFile = $upload_path . $file_uploaded_name;

				$csv = $this->readCSV($csvFile);
			}
		}
		
		# unset bool(false/true) from csv array
		foreach($csv as $key => $value)
		{
			if(!is_array($value)) unset($csv[$key]);
		}
		
		# convert csv array to json array
		$expand_arr = $this->expand($csv);
		$import = $expand_arr['product'];
		$import_cat = $expand_arr['categories'];
		
		# merger array 1 and array 2 and remove duplicate id product,
		# if ids are same but content is not same: update by content in csv
		foreach($data['products'] as $key => $value)
		{
			foreach($import as $k => $v)
			{
				if($value['id'] == $v['id']) unset($data['products'][$key]);
			}
		}
		$result = array_merge($data['products'], $import);;
		
		# sort array asc by id
		function sortIdProduct($a, $b)
		{
			$a = $a['id'];
			$b = $b['id'];
			return $a - $b;
		}
		usort($result, 'sortIdProduct');
		
		# remove duplicate category
		foreach($import_cat as $key=>$value)
		{
			foreach($cats as $v)
			{
				if($v['product_id'] == $value['product_id'] && $v['cate_id'] == $value['cate_id'])
					unset($import_cat[$key]);
			}
		}
		
		# set id for each element on import_arr array()
		foreach($import_cat as &$value)
		{
			$value['id'] = $max_id;
			$max_id++;
		}
		
		# merger categories array
		$result_cat = array_merge($cats, $import_cat);
		
		# write file product.json
		$res = array();
		$res['products'] = $result;
		$write = json_encode($res);
		$bres = $dg->WriteFile($file, $write);
		
		# write file product_categories.json
		$cat_json = json_encode($result_cat);
		$bres2 = $dg->WriteFile($file_cat, $cat_json);
		
		if($bres && $bres2) $ajax = 'Import success.';
		
		#print_r($import_cat);
		
		echo $ajax;
		return; # return page
	}
	
	# function:
	private function expand($arr = array())
	{
		$result = array();
		$result_cat = array();
		
		# remove header csv
		unset($arr[0]);
		
		# expand products
		# get value csv and set to array for write products.json
		foreach($arr as $row)
		{
			if (count($row) < 40) continue;
			
			$id 				= $row[0];
			$title 				= $row[1];
			$short_description 	= $row[2];
			$description 		= $row[3];
			$size 				= $row[4];
			$published 			= $row[5];
			$sku 				= $row[6];
			$print_type 		= $row[7];
			$print_discount 	= $row[8];
			$allow_change_printing_type = $row[9];
			$allow_screen_printing 		= $row[10];
			$allow_dtg_printing 		= $row[11];
			$allow_sublimation_printing = $row[12];
			$allow_embroidery_printing 	= $row[13];
			#$product_layout_design = array();
				$_show_product_info 	= $row[14];
				$_show_product_size 	= $row[15];
				$_show_change_product 	= $row[16];
				$_show_add_text 		= $row[17];
				$_show_add_art 			= $row[18];
				$_show_upload 			= $row[19];
				$_show_add_team 		= $row[20];
				$_show_add_qrcode 		= $row[21];
				$_show_color_used 		= $row[22];
				$_show_screen_size 		= $row[23];
			$product_layout_design 	= array(
				'show_product_info' 	=> $_show_product_info,
				'show_product_size' 	=> $_show_product_size,
				'show_change_product' 	=> $_show_change_product,
				'show_add_text' 		=> $_show_add_text,
				'show_add_art' 			=> $_show_add_art,
				'show_upload' 			=> $_show_upload,
				'show_add_team' 		=> $_show_add_team,
				'show_add_qrcode' 		=> $_show_add_qrcode,
				'show_color_used' 		=> $_show_color_used,
				'show_screen_size' 		=> $_show_screen_size,
			);
			$price 		= $row[24];
			$sale_price = $row[25];
			$min_order 	= $row[26];
			$max_oder 	= $row[27];
			$image 		= $row[28];
			# design array()
			$design_color_hex_arr 	= $row[29];
			$design_color_hex 		= explode(';', $design_color_hex_arr); 	# breaks a string into an array
			foreach($design_color_hex as $key=>$hex)
			{
				if(empty($hex)) unset($design_color_hex[$key]); 			# remove empty element
			}			
			# $design_color_hex 		= $row[29];
			
			$design_color_title_arr 	= $row[30];
			$design_color_title 		= explode(';', $design_color_title_arr); 	# breaks a string into an array
			foreach($design_color_title as $key=>$hex)
			{
				if(empty($hex)) unset($design_color_title[$key]); 					# remove empty element
			}
			# $design_color_title = $row[30];
			
			$design_price_arr 	= $row[31];
			$design_price 		= explode(';', $design_price_arr); 	# breaks a string into an array
						
			foreach($design_price as $key=>$hex)
			{
				if($hex == '') unset($design_price[$key]); 		# remove empty element
			}
			# $design_price 	= $row[31];
			
			$design_front_arr 	= substr($row[32], 1, -1);
			$design_front 		= explode(';', $design_front_arr); 	# breaks a string into an array
			foreach($design_front as $key=>$hex)
			{
				#$hex = substr($hex, 1, -1); 						# remove '{' and '}' on first and last of string
				if(empty($hex)) unset($design_front[$key]); 		# remove empty element
			}
			# $design_front 	= $row[32];
			$design_back_arr 	= substr($row[33], 1, -1);
			$design_back 		= explode(';', $design_back_arr); 	# breaks a string into an array
			foreach($design_back as $key=>&$hex)
			{
				#$hex = substr($hex, 1, -1); 						# remove '{' and '}' on first and last of string
				if(empty($hex)) unset($design_back[$key]); 			# remove empty element
			}
			# $design_back 		= $row[33];
			$design_left_arr 	= substr($row[34], 1, -1);
			$design_left 		= explode(';', $design_left_arr); 	# breaks a string into an array
			foreach($design_left as $key=>$hex)
			{
				#$hex = substr($hex, 1, -1); 						# remove '{' and '}' on first and last of string
				if(empty($hex)) unset($design_left[$key]); 			# remove empty element
			}
			# $design_left 		= $row[34];
			$design_right_arr 	= substr($row[35], 1, -1);
			$design_right 		= explode(';', $design_right_arr); 	# breaks a string into an array
			foreach($design_right as $key=>$hex)
			{
				#$hex = substr($hex, 1, -1); 						# remove '{' and '}' on first and last of string
				if(empty($hex)) unset($design_right[$key]); 		# remove empty element
			}
			# $design_right 		= $row[35];
				$design_params_front= $row[36];
				$design_params_back = $row[37];
				$design_params_left = $row[38];
				$design_params_right= $row[39];
			$design_params = array(
				'front' => $design_params_front,
				'back' 	=> $design_params_back,
				'left' 	=> $design_params_left,
				'right' => $design_params_right,
			);
				$design_area_front = $row[40];
				$design_area_back  = $row[41];
				$design_area_left  = $row[42];
				$design_area_right = $row[43];
			$design_area = array(
				'front' => $design_area_front,
				'back' 	=> $design_area_back,
				'left' 	=> $design_area_left,
				'right' => $design_area_right,
			);
			$design = array(
				'color_hex' 	=> str_replace('#', '', $design_color_hex),
				'color_title' 	=> $design_color_title,
				'price' 		=> $design_price,
				'front' 		=> $design_front,
				'back' 			=> $design_back,
				'left' 			=> $design_left,
				'right' 		=> $design_right,
				'params' 		=> $design_params,
				'area' 			=> $design_area
			);
				# $attributes_name 	= $row[44];
				$attributes_name_arr = $row[44];
				$attributes_name = explode(';', $attributes_name_arr);
				foreach($attributes_name as $key => $name)
				{
					if(empty($name)) unset($attributes_name[$key]);
				}
				# $attributes_prices 	= $row[45];
				$attributes_prices_arr 	= $row[45];
				$attributes_prices_els 	= explode('-', $attributes_prices_arr);
				$attributes_prices 		= array();
				foreach($attributes_prices_els as $key => $els)
				{
					$attributes_prices_el = array();
					if($els != '')
					{
						$attributes_prices_el_arr = explode(';', $els);
						foreach($attributes_prices_el_arr as $k => $v)
						{
							if($v != '')
								$attributes_prices_el[] = $v;
						}
					}
					if (count($attributes_prices_el) > 0)
						$attributes_prices[] = $attributes_prices_el;
				}
				# $attributes_titles 	= $row[46];
				$attributes_titles_arr 	= $row[46];
				$attributes_titles_els 	= explode('-', $attributes_titles_arr);
				$attributes_titles 		= array();
				foreach($attributes_titles_els as $key => $els)
				{
					$attributes_titles_el = array();
					if(empty($els)) unset($attributes_titles_els[$key]);
					else
					{
						$attributes_titles_el_arr = explode(';', $els);
						foreach($attributes_titles_el_arr as $k => $v)
						{
							if($v != '') $attributes_titles_el[] = $v;
						}
					}
					if(!$this->is_array_empty($attributes_titles_el)) $attributes_titles[] = $attributes_titles_el;
				}
				# $attributes_type = $row[47];
				$attributes_type_arr = $row[47];
				$attributes_type = explode(';', $attributes_type_arr);
				foreach($attributes_type as $key => $type)
				{
					if(empty($type)) unset($attributes_type[$key]);
				}
			$attributes = array(
				'name' 		=> $attributes_name,
				'prices' 	=> $attributes_prices,
				'titles' 	=> $attributes_titles,
				'type' 		=> $attributes_type
			);
						
			# 'tax', 'prices.price', 'prices.min_quantity', 'prices.max_quantity', 'categories'
			$tax = ''; # TODO Taxes- # $row[48]
				$prices_price_arr 	= explode(';', $row[51]);
				$prices_price 		= array();
				foreach($prices_price_arr as $v)
				{
					if(!empty($v)) $prices_price[] = $v;
				}
				$prices_min_quantity_arr = explode(';', $row[49]);
				$prices_min_quantity 	 = array();
				foreach($prices_min_quantity_arr as $v)
				{
					if(!empty($v)) $prices_min_quantity[] = $v;
				}
				$prices_max_quantity_arr = explode(';', $row[50]);
				$prices_max_quantity 	 = array();
				foreach($prices_max_quantity_arr as $v)
				{
					if(!empty($v)) $prices_max_quantity[] = $v;
				}
			$prices = array(
				'price' 		=> $prices_price, 
				'min_quantity' 	=> $prices_min_quantity,
				'max_quantity' 	=> $prices_max_quantity
			);
			
			# add row for product array
			$product = array(
				'title' 						=> $title,
				'short_description' 			=> $short_description,
				'description' 					=> $description,
				'size' 							=> $size,
				'published' 					=> $published,
				'sku' 							=> $sku,
				'print_type' 					=> $print_type,
				'print_discount' 				=> $print_discount,
				'allow_change_printing_type' 	=> $allow_change_printing_type,
				'allow_screen_printing' 		=> $allow_screen_printing,
				'allow_dtg_printing' 			=> $allow_dtg_printing,
				'allow_sublimation_printing' 	=> $allow_sublimation_printing,
				'allow_embroidery_printing' 	=> $allow_embroidery_printing,
				'product_layout_design' 		=> $product_layout_design,
				'price' 						=> $price,
				'sale_price' 					=> $sale_price,
				'min_order' 					=> $min_order,
				'max_oder' 						=> $max_oder,
				'image' 						=> $image,
				'design' 						=> $design,
				'id' 							=> $id,
				'attributes' 					=> $attributes,
				'tax'							=> $tax,
				'prices'						=> $prices
			);			
			
			$result[] = $product;
			
			# expand categories
			$categories_arr = explode(';', $row[52]);
			if(!$this->is_array_empty($categories_arr))
			{
				foreach($categories_arr as $v)
				{
					if(!empty($v)) 
					{
						# split category id from $v '(id)name'
						$tmp_id = $this->getValueBetween('[' ,']', $v);
						
						$cat_row = array(
							'id' => 0, 'product_id' => $id, 'cate_id' => $tmp_id
						);
						$result_cat[] = $cat_row;
					}
				}
			}
		}
		#print_r($result_cat);
		return array('product' => $result, 'categories' => $result_cat);
	}
	
	# function: get string between characters
	private function getValueBetween($start_char = '', $end_char = '', $pool)
	{
		$temp 	= strpos($pool, $start_char) + strlen($start_char);
		$result = substr($pool, $temp, strlen($pool));
		$dd 	= strpos($result, $end_char);
		if($dd == 0) { $dd = strlen($result); }
		
		return substr($result, 0, $dd);
	}
	
	# function: check array is empty or not
	private function is_array_empty($a)
	{
		foreach($a as $elm)
			if(!empty($elm)) return false;
		return true;
	}
	
	# function: read data from CSV file
	private function readCSV($csvFile)
	{
		$file_handle = fopen($csvFile, 'r');
		while (!feof($file_handle)) 
		{
			$line_of_text[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle);
		return $line_of_text;
	}
	
	# function: export data to CSV file
	public function export()
	{
		$dg = new dg();
		
		# set CSV name
		$file_name = 'product-export.csv';
		
		# get data from product.json file to array 1
		$file 		= dirname(ROOT) .DS. 'data' .DS. 'products.json';
		$product 	= $dg->readFile($file);
		$products 	= json_decode($product, true);
		if(isset($products['products'])) $data['products'] = $products['products'];
		else $data['products'] = array();
		
		# get data from product_categories.json
		$file_cat 	= dirname(ROOT) .DS. 'data' .DS. 'product_categories.json';
		$cat		= $dg->readFile($file_cat);
		$cats		= json_decode($cat, true);
		
		# get data from categories.json
		$file_cat_info 	= dirname(ROOT) .DS. 'data' .DS. 'categories.json';
		$cat_info		= $dg->readFile($file_cat_info);
		$cats_info		= json_decode($cat_info, true);
		
		$csv = $this->flatten($data['products'], $cats, $cats_info);
		
		# output csv file
		$this->outputCSV($csv, $file_name);
	}
	
	# function: flatten a multi-dimensional array
	# params: 	$arr		-> data from products.json
	#			$cat		-> data from product_categories.json
	#			$cat_info	-> data from categories.json
	private function flatten($arr = array(), $cat = array(), $cat_info = array())
	{
		$result = array();
		
		# init and create header row for CSV		
		$header = array(
			#'id', 'title', 'short_description', 'description', 'size', 'published', 'sku',
			'Product ID', 'Product Name', 'Short Description', 'Product Description', 'Product Size Info', 'Published', 'SKU',
			#'print_type', 'print_discount', 
			'Print type', 'Allow print discount', 
			#'allow_change_printing_type', 'allow_screen_printing', 'allow_dtg_printing', 
			'Allow change printing type', 'Screen Pringting', 'DTG Printing', 
			#'allow_sublimation_printing', 'allow_embroidery_printing',
			'Sublimation Printing', 'Embroidery',
			#'product_layout_design.show_product_info', 'product_layout_design.show_product_size',
			'Change product layout', 'Show Product Info',
			#'product_layout_design.show_change_product', 'product_layout_design.show_add_text',
			'Show Change Product', 'Allow Add Text',
			#'product_layout_design.show_add_art', 'product_layout_design.show_upload',
			'Allow Add Art', 'Allow Upload',
			#'product_layout_design.show_add_team', 'product_layout_design.show_add_qrcode',
			'Allow Add Team', 'Allow Add QRcode',
			#'product_layout_design.show_color_used', 'product_layout_design.show_screen_size',
			'Show Color used', 'Show Screen size',
			#'price', 'sale_price', 'min_order', 'max_oder', 'image',
			'Regular Price', 'Sale Price ', 'Order Minimum Purchase Quantity', 'Order Maximum Purchase Quantity', 'Product image',
			#'design.color_hex', 'design.color_title', 'design.price', 'design.front',
			'Product Design Color(Hex)', 'Product Design Color title', 'Product Design Price', 'Product Design Front View',
			#'design.back', 'design.left', 'design.right',
			'Product Design Back View', 'Product Design Left View', 'Product Design Right View',
			#'design.params.front', 'design.params.back', 'design.params.left', 'design.params.right',
			'Product Design Params Front', 'Product Design Params Back', 'Product Design Params Left', 'Product Design Params Right',
			#'design.area.front', 'design.area.back', 'design.area.left', 'design.area.right',
			'Product Design Area Front', 'Product Design Area Back', 'Product Design Area Left', 'Product Design Area Right',
			#'attributes.name', 'attributes.prices', 'attributes.titles', 'attributes.type',
			'Product Data Attribute Name', 'Product Data Attribute Prices', 'Product Data Attribute Titles', 'Product Data Attribute Type',
			#'tax', 'prices.min_quantity', 'prices.max_quantity', 'prices.price', 'categories'
			'Product Taxes', 'Product Min Sale Quantity', 'Product Max Sale Quantity', 'Product Sale Price', 'Product Categories [(Id)Name]'
		); # -- end header
		
		$result[] = $header;
		
		# add rows for CSV from $arr array
		foreach($arr as $key => $product)
		{
			$title 				= $product['title'];
			$short_description 	= $product['short_description'];
			$description 		= $product['description'];
			$size 				= $product['size'];
			$published 			= $product['published'];
			$sku 				= $product['sku'];
			$print_type 		= $product['print_type'];
			if(array_key_exists('print_discount', $product))
				$print_discount = $product['print_discount'];
			else 
				$print_discount = 0;
			if(array_key_exists('allow_change_printing_type', $product))
				$allow_change_printing_type = $product['allow_change_printing_type'];
			else
				$allow_change_printing_type = 0;
			if(array_key_exists('allow_screen_printing', $product))
				$allow_screen_printing = $product['allow_screen_printing'];
			else
				$allow_screen_printing = 0;
			if(array_key_exists('allow_dtg_printing', $product))
				$allow_dtg_printing = $product['allow_dtg_printing'];
			else
				$allow_dtg_printing = 0;
			if(array_key_exists('allow_sublimation_printing', $product))
				$allow_sublimation_printing = $product['allow_sublimation_printing'];
			else
				$allow_sublimation_printing = 0;
			if(array_key_exists('allow_embroidery_printing', $product))
				$allow_embroidery_printing 	= $product['allow_embroidery_printing'];
			else
				$allow_embroidery_printing = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_product_info', $product['product_layout_design']))
				$show_product_info = $product['product_layout_design']['show_product_info'];
			else
				$show_product_info = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_product_size', $product['product_layout_design']))
				$show_product_size = $product['product_layout_design']['show_product_size'];
			else
				$show_product_size = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_change_product', $product['product_layout_design']))
				$show_change_product = $product['product_layout_design']['show_change_product'];
			else
				$show_change_product = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_add_text', $product['product_layout_design']))
				$show_add_text = $product['product_layout_design']['show_add_text'];
			else
				$show_add_text = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_add_art', $product['product_layout_design']))
				$show_add_art = $product['product_layout_design']['show_add_art'];
			else
				$show_add_art = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_upload', $product['product_layout_design']))
				$show_upload = $product['product_layout_design']['show_upload'];
			else
				$show_upload = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_add_team', $product['product_layout_design']))
				$show_add_team = $product['product_layout_design']['show_add_team'];
			else
				$show_add_team = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_add_qrcode', $product['product_layout_design']))
				$show_add_qrcode = $product['product_layout_design']['show_add_qrcode'];
			else
				$show_add_qrcode = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_color_used', $product['product_layout_design']))
				$show_color_used = $product['product_layout_design']['show_color_used'];
			else
				$show_color_used = 0;
			if(array_key_exists('product_layout_design', $product) && array_key_exists('show_screen_size', $product['product_layout_design']))
				$show_screen_size = $product['product_layout_design']['show_screen_size'];
			else
				$show_screen_size = 0;
			$price		= $product['price'];
			$sale_price	= $product['sale_price'];
			$min_order	= $product['min_order'];
			$max_oder	= $product['max_oder'];
			$image		= $product['image'];
			$color_hex 	= '';
			foreach($product['design']['color_hex'] as $design_color)
			{
				$color_hex .= '#' . $design_color . ';';
			}
			$color_title = '';
			foreach($product['design']['color_title'] as $design_title)
			{
				$color_title .= $design_title . ';';
			}
			if(array_key_exists('design', $product) && array_key_exists('price', $product['design']))
			{
				$design_price = '';
				foreach($product['design']['price'] as $design_price_)
				{
						$design_price .= $design_price_ . ';';
				}
			}
			else
				$design_price = '';
			if(array_key_exists('design', $product) && array_key_exists('front', $product['design']))
			{
				$design_front = '{';
				foreach($product['design']['front'] as $v)
				{
					if (!empty($v)) $design_front .= $v . ';';
				}
				$design_front .= '}';
			}
			else
				$design_front = '';
			$design_left = '{';
			foreach($product['design']['left'] as $v)
			{
				if (!empty($v)) $design_left .= $v . ';';
			}
			$design_left .= '}';
			$design_right = '{';
			foreach($product['design']['right'] as $v)
			{
				if (!empty($v)) $design_right .= $v . ';';
			}
			$design_right .= '}';
			$design_back = '{';
			foreach($product['design']['back'] as $v)
			{
				if (!empty($v)) $design_back .= $v . ';';
			}
			$design_back .= '}';
			$design_param_front = $product['design']['params']['front'];
			#foreach((array)$product['design']['params']['front'] as $front)
			#{
			#	$design_param_front .= $front . ';';
			#}
			$design_param_left = $product['design']['params']['left'];
			#foreach((array)$product['design']['params']['left'] as $left)
			#{
			#	$design_param_left .= $left . ';';
			#}
			$design_param_right	= $product['design']['params']['right'];
			#foreach((array)$product['design']['params']['right'] as $right)
			#{
			#	$design_param_right .= $right . ';';
			#}
			$design_param_back = $product['design']['params']['back'];
			#foreach((array)$product['design']['params']['back'] as $back)
			#{
			#	$design_param_back .= $back . ';';
			#}
			$design_area_front = $product['design']['area']['front'];
			$design_area_left = $product['design']['area']['left'];
			$design_area_right	= $product['design']['area']['right'];
			$design_area_back = $product['design']['area']['back'];
			$id	= $product['id'];
			if(array_key_exists('attributes', $product) && array_key_exists('name', $product['attributes']))
			{
				$attributes_name = '';
				foreach($product['attributes']['name'] as $attribute_name)
				{
					$attributes_name .= $attribute_name . ';';
				}
			}
			else
				$attributes_name = '';
			if(array_key_exists('attributes', $product) && array_key_exists('prices', $product['attributes']))
			{
				$attributes_prices = '';
				foreach($product['attributes']['prices'] as $attribute_price)
				{
					foreach($attribute_price as $v)
						$attributes_prices .= $v . ';';
					$attributes_prices .= '-';
				}
			}
			else
				$attributes_prices = '';
			if(array_key_exists('attributes', $product) && array_key_exists('titles', $product['attributes']))
			{
				$attributes_titles = '';
				foreach($product['attributes']['titles'] as $attribute_title)
				{
					foreach($attribute_title as $v)
						$attributes_titles.= $v . ';';
					$attributes_titles .= '-';
				}
			}
			else
				$attributes_titles = '';
			if(array_key_exists('attributes', $product) && array_key_exists('type', $product['attributes']))
			{
				$attributes_type = '';
				foreach($product['attributes']['type'] as $attribute_type)
				{
					$attributes_type .= $attribute_type . ';';
				}
			}
			else
				$attributes_type = '';
			# TODO # 'tax', 'prices.price', 'prices.min_quantity', 'prices.max_quantity', 'categories'
			$tax = '';	# TODO [-It have not Taxes-]
			if(array_key_exists('prices', $product))
			{
				if(array_key_exists('price', $product['prices']))
				{
					$prices_price_arr = $product['prices']['price'];
					$prices_price = '';
					foreach($prices_price_arr as $v)
					{
						$prices_price .= $v . ';';
					}
				}
				else { $prices_price = ''; }
				if(array_key_exists('min_quantity', $product['prices']))
				{
					$prices_min_quantity_arr = $product['prices']['min_quantity'];
					$prices_min_quantity = '';
					foreach($prices_min_quantity_arr as $v)
					{
						$prices_min_quantity .= $v . ';';
					}
				}
				else { $prices_min_quantity = '';	}
				if(array_key_exists('max_quantity', $product['prices']))
				{
					$prices_max_quantity_arr = $product['prices']['max_quantity'];
					$prices_max_quantity = '';
					foreach($prices_max_quantity_arr as $v)
					{
						$prices_max_quantity .= $v . ';';
					}
				}
				else { $prices_max_quantity = ''; }
			}
			else { $prices_price = ''; $prices_min_quantity = ''; $prices_max_quantity = ''; }
			
			$categories = '';
			foreach($cat as $v)
			{
				$cate_id = $v['cate_id'];
				if($v['product_id'] == $id)
				{
					# get category name from categories.json
					$cate_name = '';
					foreach($cat_info as $info)
					{
						if($cate_id == $info['id']) { $cate_name = $info['title']; break; }
					}
					$categories .= '[' . $cate_id . ']' . $cate_name . ';';
				}
			}
			
			# add a row to csv
			$row = array(
				$id, $title, $short_description, $description, $size, $published, $sku,
				$print_type, $print_discount,
				$allow_change_printing_type, $allow_screen_printing, $allow_dtg_printing,
				$allow_sublimation_printing, $allow_embroidery_printing,
				$show_product_info, $show_product_size,
				$show_change_product, $show_add_text,
				$show_add_art, $show_upload,
				$show_add_team, $show_add_qrcode,
				$show_color_used, $show_screen_size,
				$price, $sale_price, $min_order, $max_oder, $image,
				$color_hex, $color_title, 
				$design_price, $design_front, $design_back, $design_left, $design_right, 
				$design_param_front, $design_param_back, $design_param_left, $design_param_right,
				$design_area_front, $design_area_back, $design_area_left, $design_area_right,
				$attributes_name, $attributes_prices, $attributes_titles, $attributes_type,
				$tax, $prices_min_quantity, $prices_max_quantity, $prices_price, $categories
			);
			
			$result[] = $row;
		}
		
		return $result;
	}
	
	# function: output to CSV file
	private function outputCSV($data, $file_name = 'filename.csv') 
	{
		# output headers so that the file is downloaded rather than displayed
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name");
        # Disable caching - HTTP 1.1
        header("Cache-Control: no-cache, no-store, must-revalidate");
        # Disable caching - HTTP 1.0
        header("Pragma: no-cache");
        # Disable caching - Proxies
        header("Expires: 0");
    
        # Start the ouput
        $output = fopen("php://output", "w");
        
         # Then loop through the rows
        foreach ($data as $row) 
		{
            # Add the rows to the body
            fputcsv($output, $row); // here you can change delimiter/enclosure
        }
		
        # Close the stream off
        fclose($output);
		exit;
	}
}

?>