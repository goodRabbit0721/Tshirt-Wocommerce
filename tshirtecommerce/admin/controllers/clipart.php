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

class Clipart extends Controllers
{
	
	public function index($index = 1, $cateid = 0)
	{		
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$data = array();
		if ($index < 1) $index = 1;
		
		$data['page']		= 1;
		$data['cateid']		= $cateid;
		$data['title'] 		= 'Manager';
		$data['sub_title'] 	= 'Images of Design';
		
		$dgClass 			= new dg();

		$arts 				= array();
		
		$file = dirname(ROOT) .DS. 'data' .DS. 'arts.json';
		$setting = dirname(ROOT).DS.'data'.DS.'settings.json';
		$data['currency_symbol'] = '$';
		if (file_exists($file))
		{
			$str 		= $dg->readFile($file);
			$setting 		= $dg->readFile($setting);
			$settings 		= json_decode($setting);
			$rows 		= json_decode($str);
			
			// find category
			if ($cateid > 0)
			{
				$array = array();
				$count = 0;
				for($i=0; $i<$rows->count; $i++)
				{
					if ($rows->arts[$i]->cate_id == $cateid)
					{
						$count++;
						$array[] = $rows->arts[$i];
					}
				}
				$rows->count = $count;
				$rows->arts = $array;
			}
			
			if ($rows->count > 30)
			{
				$min = ($index-1) * 30;
				$max = $index * 30;
				for($i=$min; $i<$max; $i++)
				{
					if (empty($rows->arts[$i])) break;
					$arts[] = $rows->arts[$i];
				}
			}
			else
			{
				$arts 	= $rows->arts;
			}
			
			if ($rows->count % 30 == 0)
			{
				$data['page']	= $rows->count / 30;
			}
			else
			{
				$data['page']	= (int) ($rows->count / 30) + 1;
			}
			$data['currency_symbol'] = $settings->currency_symbol;
		}
		$data['arts'] 	= $arts;		
		
		$data['index']		= $index;
		
		$this->view('clipart', $data);
	}
	
	// find ajax
	public function ajax($index = 1, $cateid = 0)
	{		
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$data = array();
		if ($index < 1) $index = 1;
		
		$data['cateid']		= $cateid;
		$data['page']		= 1;
		$data['title'] 		= 'Manager';
		$data['sub_title'] 	= 'Images of Design';
		
		$dgClass 			= new dg();

		$arts 				= array();
		
		$file = dirname(ROOT) .DS. 'data' .DS. 'arts.json';
		$setting = dirname(ROOT).DS.'data'.DS.'settings.json';
		$data['currency_symbol'] = '$';
		if (file_exists($file))
		{
			$str 		= $dg->readFile($file);
			$setting 		= $dg->readFile($setting);
			$settings 		= json_decode($setting);
			$rows 		= json_decode($str);
			if ($cateid > 0)
			{
				$array = array();
				$count = 0;
				for($i=0; $i<$rows->count; $i++)
				{
					if (empty($rows->arts[$i])) continue;
					if ($rows->arts[$i]->cate_id == $cateid)
					{
						$count++;
						$array[] = $rows->arts[$i];
					}
				}
				$rows->count = $count;
				$rows->arts = $array;
			}
			
			if ($rows->count > 30)
			{
				$min = ($index-1) * 30;
				$max = $index * 30;
				for($i=$min; $i<$max; $i++)
				{
					if (empty($rows->arts[$i])) break;
					$arts[] = $rows->arts[$i];
				}
			}
			else
			{
				$arts 	= $rows->arts;
			}
			
			if ($rows->count % 30 == 0)
			{
				$data['page']	= $rows->count / 30;
			}
			else
			{
				$data['page']	= (int) ($rows->count / 30) + 1;
			}
			$data['currency_symbol'] = $settings->currency_symbol;
		}
		$data['arts'] 	= $arts;		
		
		$data['index']		= $index;
		
		$this->modal('ajax_clipart', $data);
	}
	
	// add, edit art
	function edit($id = 0)
	{
		$data 	= array();
		
		if ($id == 0)
		{
			$data['sub_title'] = lang('art_add', true);
			$art 	= new stdClass();
		}
		else
		{
			$data['sub_title'] = lang('art_edit', true);
			$file 	= dirname(ROOT) .DS. 'data' .DS. 'arts.json';
			$art 	= new stdClass();
			if (file_exists($file))
			{
				$str 	= file_get_contents($file);
				$rows 	= json_decode($str);
				if (isset($rows->arts) && count($rows->arts) > 0)
				{
					foreach($rows->arts as $row)
					{
						if ($row->clipart_id == $id)
						{
							$art = $row;
							break;
						}
					}
				}			
			}
		}
		$data['art'] = $art;
				
		$data['title'] 	= 'Clipart';
		$data['id'] 	= $id;
		
		$data['categories'] 	= $this->categoriestree(true);			
		
		$this->view('clipart_edit', $data);
	}
	
	// save clipart
	public function save()
	{
		$dgClass 				= new dg();
		if (!empty($_POST))
		{
			$art = $_POST['art'];
			if (isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] != '')
			{
				// create folder
				$root		= dirname(ROOT) .DS. 'uploaded' .DS. 'cliparts' .DS. $art['cate_id'];
				if (!file_exists($root))
				{
					 mkdir($root, 0755, TRUE);
				}
				
				$upload_path = $root .DS. 'print' .DS;
				if(!is_dir($upload_path))
				{
					mkdir($upload_path, 0755, TRUE);
				}
				
				// upload file
				require_once dirname(ROOT) .DS. 'includes' .DS. 'upload.php';
				$uploader   =   new Uploader();
				$uploader->setDir($upload_path);

				$uploader->setExtensions(array('jpg','jpeg','png','gif','svg'));
				$uploader->setMaxSize(10);
				$uploader->sameName(false);
				
				if($uploader->uploadFile('file'))
				{					
					$image  		=  $uploader->getUploadName();
					$extension 		= $uploader->getExtension($image);
					$extension		= strtolower($extension);
					
					$url 				= site_url('uploaded/cliparts/');
					$url				= str_replace('/admin/', '/', $url);
					$art['file_type'] 	= $extension;					
					$art['file_name'] 	= $image;					
					$art['path'] 		= $url;					
					$art['url'] 		= $url. $art['cate_id'] .'/';
					
					// create folder thumb
					$thumbs	= $root .DS. 'thumbs';				
					if(!is_dir($thumbs)) mkdir($thumbs, 0755, TRUE);
					
					$medium	= $root .DS. 'medium';
					if(!is_dir($medium)) mkdir($medium, 0755, TRUE);
					
					if ($extension == 'svg')
					{
						$art['change_color'] = 1;
						$art['thumb'] 	= 'print/' . $image;
						$art['medium'] 	= 'print/' . $image;
					}
					else
					{
						require_once ROOT .DS. 'includes' .DS. 'thumb.php';
						$thumb		= new thumb($upload_path . $image);
						$thumb->resize(null, 100, 100, $thumbs .DS. md5($image) .'.'.$extension);
						$thumb->resize(null, 300, 300, $medium .DS. md5($image.'medium').'.'.$extension);
						
						$art['thumb'] 	= 'thumbs/' . md5($image) .'.'. $extension;
						$art['medium'] 	= '/medium/' . md5($image.'medium') .'.'. $extension;
						$art['change_color'] = 0;
					}					
				}
				else
				{
					echo $uploader->getMessage();
				}
			}			
			
			$art['price'] = (float) $art['price'];
			
			$file 	= dirname(ROOT) .DS. 'data' .DS. 'arts.json';
			// check file
			if (!file_exists($file))
			{
				$dgClass->redirect('index.php/clipart');
				return;
			}
			
			$data 	= file_get_contents($file);
			$arts 	= json_decode($data);
			$is_new = true;
			$conent	= array();
			if (isset($_POST['id']) && $_POST['id'] > 0)
			{
				$id 	= $_POST['id'];
				// update clipart
				if (isset($arts->arts) && count($arts->arts) > 0)
				{
					foreach($arts->arts as $row)
					{
						if ($row->clipart_id == $id)
						{
							$art['clipart_id']	= $id;
							$conent[] 			= $art;
							$is_new			= false;
						}
						else
						{
							$conent[] = $row;
						}
					}
				}
			}
			
			if ($is_new === true)
			{
				$index = 0;
				foreach($arts->arts as $row)
				{
					if ($row->clipart_id > $index)
					{
						$index = $row->clipart_id;
					}
					$conent[] = $row;
				}
				$art['clipart_id']	= $index + 1;
				$conent[] = $art;
				$arts->count = $arts->count + 1;
			}
			$arts->arts	= $conent;
			$dgClass->WriteFile($file, json_encode($arts));
			$dgClass->redirect('index.php/clipart');
		}
		else
		{
			$dgClass->redirect('index.php/clipart');
		}
	}
	
	// delete arts
	public function delete()
	{
		$dgClass 				= new dg();
		if (isset($_POST['ids']))
		{
			$ids = $_POST['ids'];
			
			$file 	= dirname(ROOT) .DS. 'data' .DS. 'arts.json';
			$arts 	= array();
			if (file_exists($file))
			{
				$str 	= file_get_contents($file);
				$rows 	= json_decode($str);
				$n		= 0;
				if (isset($rows->arts) && count($rows->arts) > 0)
				{
					foreach($rows->arts as $row)
					{
						if (!in_array($row->clipart_id, $ids))
						{
							$arts[] = $row;
							$n++;
						}
					}
				}
				$content = new stdClass();
				$content->count = $n;
				$content->arts = $arts;
				$dgClass->WriteFile($file, json_encode($content));
			}
		}
		$dgClass->redirect('index.php/clipart');
	}
	
	// get list category
	public function categoriestree($return = true)
	{
		$path = dirname(ROOT) .DS. 'data' .DS. 'categories_art.json';
		$categories = array();
		if (file_exists($path))
		{
			$str	= file_get_contents($path);
			$categories = json_decode($str);
			if (count($categories) > 0)
			{
				$new = array();
				foreach ($categories as $a){
					if ($a->id == 0) continue;
					$new[$a->parent_id][] = $a;
				}
				if (isset($new[0]))
					$tree = $this->createTree($new, $new[0]);
				else
					$tree = $this->createTree($new, $new);
				
				$categories = $tree;
			}
		}
		$all 				= array();
		$all[0]				= new stdClass();
		$all[0]->id 		= 0;
		$all[0]->title 		= 'All Art';
		$all[0]->children 	= array();
		$all[0]->parent_id 	= 0;
			
			
		$categories = array_merge($all, $categories);
		
		if ($return === true)
		{
			return $categories;
			
		}
		else
		{
			echo json_encode($categories);
			exit();
		}
	}
	
	public function createTree(&$list, $parent){
		$tree = array();
		foreach ($parent as $k=>$l){
			if(isset($list[$l->id])){
				$l->children = $this->createTree($list, $list[$l->id]);
				if ( count($l->children) > 0) $l->isFolder = true;	
			}
			$tree[] = $l;
		} 
		return $tree;
	}
	
	public function editCategory($id = 0)
	{
		$data = array();
		
		$data['id'] 	= $id;
		
		$file = dirname(ROOT) .DS. 'data' .DS. 'categories_art.json';
		$categories		= array();
		
		$data['category'] = array(
			'id' => 0,
			'title' => '',
			'parent_id' => 0
		);
		if (file_exists($file))
		{
			$str 		= file_get_contents($file);
			$array 		= json_decode($str);
			if (count($array))
			{
				foreach($array as $category)
				{
					if ($category->parent_id == 0)
					{
						$categories[] = $category;
					}
				}
				if ($id > 0)
				{
					foreach($array as $category)
					{
						if ($category->id == $id)
						{
							$data['category'] =  array(
								'id' => $category->id,
								'title' => $category->title,
								'parent_id' => $category->parent_id
							);
							break;
						}
					}
				}
			}
		}
		$data['categories']	= $categories;
		
		$this->modal('category', $data);
	}
	
	// save category
	public function saveCategory()
	{
		$dgClass 				= new dg();
		if (!empty($_POST))
		{
			$id 		= $_POST['id'];
			$title 		= $_POST['title'];
			$parent_id 	= $_POST['parent_id'];
			
			// get categories
			$file = dirname(ROOT) .DS. 'data' .DS. 'categories_art.json';
			$categories		= array();
			$categories[0]	= new stdClass();
			$categories[0]->id = 0;
			$categories[0]->title = 'All art';
			$categories[0]->parent_id = '0';
			
			if (file_exists($file))
			{
				$str 		= file_get_contents($file);
				$categories = json_decode($str);
			}
			
			if ($parent_id == 0)
				$level = 1;
			else
				$level = 2;
			
			if ($id == 0)
			{
				// add new category
				$index = 0;
				foreach($categories as $category)
				{
					if ($category->id > $index)
						$index = $category->id;
				}
				$index = $index + 1;
				
				$category = array(
					'id' => $index,
					'title' => $title,
					'parent_id' => $parent_id
				);
				$categories[] = $category;
			}
			else
			{
				// edit category
				$index = $id;
				$array = array();
				foreach($categories as $category)
				{
					if ($category->id == $index)
					{
						$array[] = array(
							'id' => $index,
							'title' => $title,
							'parent_id' => $parent_id
						);
					}
					else
					{
						$array[] = $category;
					}
				}
				$categories = $array;
			}
						
			$check = $dgClass->WriteFile($file, json_encode($categories));
			echo 1;
			exit();
		}
		else
		{
			$dgClass->redirect('index.php/clipart');
		}
	}
	
	// delete category
	public function deleteCategory($id)
	{
		$file 		= dirname(ROOT) .DS. 'data' .DS. 'categories_art.json';
		$str 		= file_get_contents($file);
		$categories = json_decode($str);
		$array = array();
		foreach($categories as $category)
		{
			if ($category->id != $id)
			{
				$array[] = $category;
			}
		}
		$categories = $array;
		
		$dgClass 				= new dg();
		$check = $dgClass->WriteFile($file, json_encode($categories));
		echo $check;
		exit();
	}
}

?>