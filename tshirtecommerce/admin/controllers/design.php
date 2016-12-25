<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-26
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 if ( ! defined('ROOT')) exit('No direct script access allowed');
 
 class Design extends Controllers{
	public function index(){}
	
	public function user_design($segment = 1)
	{
		$str_file_name = '';
		if(isset($_POST['search_text']))
		{
			$str_file_name = str_replace(' ','', $_POST['search_text']);
		}
		
		$perpage = 10;
		if(isset($_POST['per_page']))
		{
			$perpage = $_POST['per_page'];
		}
		
		$data['user_design'] 	= array();
		$cache 					= $this->cache('design');		 
		$user_design_folder 	= dirname(ROOT).DS.'cache'.DS.'design';
		$cache_arr 				= array(); 
		
		$files = $this->getFiles($user_design_folder, '.txt');
		
		if($str_file_name == '')
		{
			if($files && count($files) > 0)
			{
				foreach($files as $file) 
				{
					$file_name = str_replace('.txt', '', $file);
					$cache_arr[$file_name] = array_reverse($cache->get($file_name)); // for sort
				}			
			}
		}
		else
		{
			if($files && count($files) > 0)
			{
				foreach($files as $file) 
				{
					$file_name = str_replace('.txt', '', $file);
					if($file_name == $str_file_name)
					{
						$cache_arr[$file_name] = array_reverse($cache->get($file_name)); // for sort
					}
				}			
			}
		}
		
		$data['total_page'] = 1;		
		$i 					= 1;
		$total_arr 			= array();		
	
		if($perpage == 'all')
		{
			foreach($cache_arr as $keys => $values)
			{
				foreach($values as $key => $value)
				{
					$total_arr[$key] = $value;
					$data['user_design'][$keys][$key] = $value;				
					$i++;
				}				
			}
			
			$data['total_page'] = 1;
			$data['page_curr'] 	= 1;
			$data['per_page']	= 'all';
		}
		else
		{
			$start 				= ($segment - 1) * $perpage;
			$end 				= $segment * $perpage;
		
			foreach($cache_arr as $keys => $values)
			{
				foreach($values as $key => $value)
				{
					$total_arr[$key] = $value;
					if($start < $i && $i <= $end) $data['user_design'][$keys][$key] = $value;				
					$i++;
				}				
			}

			$phan_nguyen 	= (int)(count($total_arr) / $perpage);
			$phan_du		= count($total_arr) % $perpage;
			$total_page 	= $phan_nguyen;
			
			if($phan_du > 0) $total_page++;
			
			$data['total_page'] = $total_page;
			$data['page_curr']  = $segment;
			$data['per_page']	= $perpage;
		}	
		
		$data['search_file_name'] 	= $str_file_name;
		$data['title'] 				= lang('addon_user_design_menu_li_user', true);
		$data['sub_title']  		= lang('breadcrumb_manager', true);

		$this->view ('user_design_manage', $data);
	 }
	 
	public function admin_design($segment = 1)
	{
		$str_file_name = '';
		if(isset($_POST['search_text']))
		{
			$str_file_name = str_replace(' ','', $_POST['search_text']);
		}
		
		$perpage = 10;
		if(isset($_POST['per_page']))
		{
			$perpage = $_POST['per_page'];
		}
		
		$data['admin_design'] 	= array();
		$cache 					= $this->cache('admin');		 
		$user_design_folder 	= dirname(ROOT).DS.'cache'.DS.'admin';
		$cache_arr 				= array(); 
		
		$files = $this->getFiles($user_design_folder, '.txt');
		
		if($str_file_name == '')
		{
			if($files && count($files) > 0)
			{
				foreach($files as $file) 
				{
					$file_name = str_replace('.txt', '', $file);
					$cache_arr[$file_name] = array_reverse($cache->get($file_name)); // for sort
				}			
			}
		}
		else
		{
			if($files && count($files) > 0)
			{
				foreach($files as $file) 
				{
					$file_name = str_replace('.txt', '', $file);
					if($file_name == $str_file_name)
					{
						$cache_arr[$file_name] = array_reverse($cache->get($file_name)); // for sort
					}
				}			
			}
		}
		
		$data['total_page'] = 1;		
		$i 					= 1;
		$total_arr 			= array();		
	
		if($perpage == 'all')
		{
			foreach($cache_arr as $keys => $values)
			{
				foreach($values as $key => $value)
				{
					$total_arr[$key] = $value;
					$data['admin_design'][$keys][$key] = $value;				
					$i++;
				}				
			}
			
			$data['total_page'] = 1;
			$data['page_curr'] 	= 1;
			$data['per_page']	= 'all';
		}
		else
		{
			$start 				= ($segment - 1) * $perpage;
			$end 				= $segment * $perpage;
		
			foreach($cache_arr as $keys => $values)
			{
				foreach($values as $key => $value)
				{
					$total_arr[$key] = $value;
					if($start < $i && $i <= $end) $data['admin_design'][$keys][$key] = $value;				
					$i++;
				}				
			}

			$phan_nguyen 	= (int)(count($total_arr) / $perpage);
			$phan_du		= count($total_arr) % $perpage;
			$total_page 	= $phan_nguyen;
			
			if($phan_du > 0) $total_page++;
			
			$data['total_page'] = $total_page;
			$data['page_curr']  = $segment;
			$data['per_page']	= $perpage;
		}	
		
		$data['search_file_name'] 	= $str_file_name;
		$data['title'] 				= lang('addon_user_design_menu_li_user', true);
		$data['sub_title']  		= lang('breadcrumb_manager', true);

		$this->view ('admin_design_manage', $data);
	}
	
	public function delete($page_curr = 1)
	{
		$str_file_name = '';
		if(isset($_POST['search_text']))
		{
			$str_file_name = str_replace(' ','', $_POST['search_text']);
		}
		
		$perpage = 10;
		if(isset($_POST['per_page']))
		{
			$perpage = $_POST['per_page'];
		}
		
		if (isset($_POST['ids']))
		{
			$id_arr 			= $_POST['ids'];
			$cache 				= $this->cache('design');			 
			$user_design_folder = dirname(ROOT).DS.'cache'.DS.'design';
			$files 				= $this->getFiles($user_design_folder, '.txt');
			if($files)				
			{
				if($searchtext == '')
				{
					foreach($files as $file) 
					{
					   $file_name = str_replace('.txt','',$file);
					   $cache_arr = $cache->get($file_name);
					   $arr       = $cache_arr;
					   foreach($cache_arr as $key => $value)
					   {
						   foreach($id_arr as $k => $v)
						   {
							   if($key == $v) unset($arr[$key]);
						   }
					   }		   
					   $cache->set($file_name, $arr);
					}
				}
				else
				{
					foreach($files as $file) 
					{
					   $file_name = str_replace('.txt','',$file);
					   if($searchtext == $file_name)
					   {
							$cache_arr = $cache->get($file_name);
							$arr       = $cache_arr;
							foreach($cache_arr as $key => $value)
							{
							   foreach($id_arr as $k => $v)
							   {
								   if($key == $v) unset($arr[$key]);
							   }
							}		   
							$cache->set($file_name, $arr);
					   }					   
					}
				}				
			}
		}
		
		$data['total_page'] = $total_page;
		$data['page_curr']  = $page_curr;
		$data['per_page']	= $perpage;
			
		$data['search_file_name'] 	= $str_file_name;
		$data['title'] 				= lang('addon_user_design_menu_li_admin', true);		 
		$data['sub_title'] 			= lang('breadcrumb_manager', true);	
				
		$str_url = site_url('index.php/design/user_design/'.$page_curr);
		header("Location: $str_url");
		exit;
		//$this->view ('user_design_manage', $data);
	}
	
	public function admin_delete($page_curr = 1)
	{
		$str_file_name = '';
		if(isset($_POST['search_text']))
		{
			$str_file_name = str_replace(' ','', $_POST['search_text']);
		}
		
		$perpage = 10;
		if(isset($_POST['per_page']))
		{
			$perpage = $_POST['per_page'];
		}
		
		if (isset($_POST['ids']))
		{
			$id_arr 			= $_POST['ids'];
			$cache 				= $this->cache('admin');			 
			$user_design_folder = dirname(ROOT).DS.'cache'.DS.'admin';
			$files 				= $this->getFiles($user_design_folder, '.txt');
			if($files)				
			{
				if($searchtext == '')
				{
					foreach($files as $file) 
					{
					   $file_name = str_replace('.txt','',$file);
					   $cache_arr = $cache->get($file_name);
					   $arr       = $cache_arr;
					   foreach($cache_arr as $key => $value)
					   {
						   foreach($id_arr as $k => $v)
						   {
							   if($key == $v) unset($arr[$key]);
						   }
					   }		   
					   $cache->set($file_name, $arr);
					}
				}
				else
				{
					foreach($files as $file) 
					{
					   $file_name = str_replace('.txt','',$file);
					   if($searchtext == $file_name)
					   {
							$cache_arr = $cache->get($file_name);
							$arr       = $cache_arr;
							foreach($cache_arr as $key => $value)
							{
							   foreach($id_arr as $k => $v)
							   {
								   if($key == $v) unset($arr[$key]);
							   }
							}		   
							$cache->set($file_name, $arr);
					   }					   
					}
				}
				
			}
		}
		
		$data['total_page'] = $total_page;
		$data['page_curr']  = $page_curr;
		$data['per_page']	= $perpage;
			
		$data['search_file_name'] 	= $str_file_name;
		$data['title'] 				= lang('addon_user_design_menu_li_admin', true);		 
		$data['sub_title'] 			= lang('breadcrumb_manager', true);	
				
		$str_url = site_url('index.php/design/admin_design/'.$page_curr);
		header("Location: $str_url");
		exit;
	}
	
	private function cache($folder = 'design')
	{
		require_once (dirname(ROOT) .DS. 'includes' .DS. 'libraries' .DS. 'phpfastcache.php');
		phpFastCache::setup("storage", "files");
		phpFastCache::setup("path", dirname(ROOT) .DS. 'cache');
		phpFastCache::setup("securityKey", $folder);
		$cache = phpFastCache();
		
		return $cache;
	}
	
	private function getFiles($path, $exten = '.txt')
	{
		if (file_exists($path))
		{
			$files 	= scandir($path);
			$list 	= array();
			
			if (count($files) == 0) return false;
			
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
 }
?>