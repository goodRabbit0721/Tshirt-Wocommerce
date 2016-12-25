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

class Addon extends Controllers
{
	public $api_url = 'https://tshirtecommerce.com/index.php';
	
	public function index()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$path = 'https://tshirtecommerce.com/addons/addons.json';
		
		$content = $dg->openURL($path);
		
		$addons 		= json_decode($content);		
		$data['addons'] = $addons;
		$data['title'] = lang('breadcrumb_addons', true);
		$data['breadcrumb'] = lang('breadcrumb_addons', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		
		$this->view('addon', $data);
	}
	
	public function install()
	{
		include_once(ROOT.DS.'includes'.DS.'upload.php');
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$upload = array(
			'error' => 0,
			'msg'  => ''
		);
		$data = array();
		
		// check key
		if (isset($_POST['key']) && isset($_FILES['file']))
		{
			
			if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' &&  $_POST['key'] != '')
			{
				$check_key = 0;
				
				$key 	= $_POST['key'];
				$index 	= explode('-api-tshirtecommerce-', $key);
				
				if (count($index) > 1 && $index[0] != '')
				{
					// check key
					$args = array(
						'woo_sl_action'	=> 'install',
						'licence_key'	=> $key,				
						'product_unique_id'	=> $index[0],				
						'domain'	=> $_SERVER['HTTP_HOST']
					);
					
					$result = $dg->sendPostData($this->api_url, $args);
					
					if ($result != false && $result != '')
					{
						$addon = json_decode($result);
						
						if (isset($addon->status) && $addon->status == 'success')
						{
							$check_key 		= 1;
							
							// upload file
							$up 			= new Upload();
							$up->path 		= dirname(dirname(ROOT));
							$up->file_size 	= 20971520; // 20Mb
							$up->file_type 	= array(0=>'zip'); // zip file type.
							$upload 		= $up->file($_FILES['file']);
							
							// unzip file
							if($upload['error'] == 0 && $upload['msg'] != '')
							{
								if(file_exists($upload['full_path']))
								{
									$zip = new ZipArchive;
									if(!is_writable($upload['full_path']))
										chmod($upload['full_path'], 755);
									
									if ($zip->open($upload['full_path']) === TRUE) 
									{
										$zip->extractTo($upload['path']);
										$zip->close();
										unlink($upload['full_path']);
										
										$file = dirname(ROOT) .DS. 'addons' .DS. 'install' .DS. $index[0] .'.json';
										if(file_exists($file))
										{
											$content = file_get_contents($file);
											if ($content != false)
											{
												$arr = json_decode($content);
												$arr->key = $key;
												$dg->WriteFile($file, json_encode($arr));
												$dg->redirect('index.php/addon/installed');
											}
										}
										else
										{
											$check_key 		= -3;
										}
									}
									else
									{
										$check_key 		= -2;
									}
								}
							}
							else
							{
								$check_key 		= -1;
							}
						}
					}	
				}

				if ($check_key == 0)
				{
					$data['error'] = 'Your key not found! Please check your key and try again.';
				}
				else if ($check_key == -1)
				{
					$data['error'] = 'Your system not allow upload file. Please set permission 755 to ROOT flder.';
				}
				else if ($check_key == -2)
				{
					$data['error'] = 'Your system not allow upload file. Please set permission 755 to ROOT flder.';
				}
				else if ($check_key == -3)
				{
					$data['error'] = 'Your system not allow write file. Please set permission 755 to Folder_your_site/tshirtecommerce/addons/install.';
				}
			}
			else
			{
				$data['error'] = 'Please add your key and upload file install!';
			}
		}		
		
		$data['upload'] = $upload;
		
		$data['title'] = lang('breadcrumb_install', true);
		$data['breadcrumb'] = lang('breadcrumb_install', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		
		$this->view('install', $data);
	}
	
	public function installed()
	{	
		$path = dirname(ROOT).DS.'addons'.DS.'install';
		
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$addons = array();
		$keys = array();
		if(file_exists($path))
		{
			if ($handle = opendir($path)) {
				while (false !== ($entry = readdir($handle))) {
					if(!in_array($entry, array(".","..")) && file_exists($path.DS.$entry))
					{
						$product_id 	= str_replace('.json', '', $entry);
						$file 			= $dg->readFile($path.DS.$entry);
						
						if ($file != false)
						{
							$addon = json_decode($file);
							$addon->new_version = $addon->version;
							$addon->product_id = $product_id;
							
							if (isset($addon->key) && $addon->key != '')
							{
								$args = array(
									'woo_sl_action'	=> 'plugin_update',
									'licence_key'	=> $addon->key,				
									'product_unique_id'	=> $product_id,				
									'domain'	=> $_SERVER['HTTP_HOST']
								);
								
								$result = $dg->sendPostData($this->api_url, $args);
								
								if ($result != false && $result != '')
								{
									$content = json_decode($result);
									
									if (isset($content[0]) && isset($content[0]->status) && isset($content[0]->message) && $content[0]->status == 'success')
									{
										if (isset($content[0]->message->new_version))
										{
											$addon->new_version = $content[0]->message->new_version;
										}										
									}
								}
								
								$addons[] = $addon;
							}
						}
					}
				}
			}
		}
				
		$data['addons'] = $addons;
		
		$data['title'] = lang('breadcrumb_installed', true);
		$data['breadcrumb'] = lang('breadcrumb_installed', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		
		$this->view('installed', $data);
	}
	
	public function update($product_id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		$path_info = dirname(ROOT) .DS. 'addons' .DS. 'install' .DS. $product_id.'.json';
		
		if($product_id != '' && file_exists($path_info))
		{
			$content = file_get_contents($path_info);
			if ($content != false)
			{
				$addon = json_decode($content);
				
				$args = array(
					'woo_sl_action'	=> 'plugin_update',
					'licence_key'	=> $addon->key,				
					'product_unique_id'	=> $product_id,				
					'domain'	=> $_SERVER['HTTP_HOST']
				);
				
				$result = $dg->sendPostData($this->api_url, $args);
				
				if ($result != false && $result != '')
				{
					$content = json_decode($result);				
					if (isset($content[0]) && isset($content[0]->status) && isset($content[0]->message) && $content[0]->status == 'success')
					{
						if (isset($content[0]->message->package))
						{
							$addon->version = $content[0]->message->new_version;
							$addon->date = $content[0]->message->date;							
							
							// download and upzip file
							$file 		= $dg->openURL($content[0]->message->package);
							$zip 		= new ZipArchive;
							
							$path 		= dirname(dirname(ROOT));
							$path_file 	= $path .DS. 'addon.zip';
							if($dg->WriteFile($path_file, $file) && $zip->open($path_file) == true)
							{
								$zip->extractTo($path);
								$zip->close();
								unlink($path_file);
								
								$dg->WriteFile($path_info, json_encode($addon));								
							}
						}									
					}
				}
			}
		}		
		$dg->redirect('index.php/addon/installed');
	}

	public function remove($product_id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		$path_file = dirname(ROOT) .DS. 'addons' .DS. 'remove' .DS. $product_id.'.json';
		
		if($product_id != '' && file_exists($path_file))
		{
			// remove addons.
			$list_file = file_get_contents($path_file);
			$list_files = json_decode($list_file);
			if(is_array($list_files))
			{
				foreach($list_files as $key=>$val)
				{
					$val = str_replace('/', DS, $val);
					$path = dirname(ROOT).DS.$val;
					if(file_exists($path))
					{
						unlink($path);
					}
				}
			}
		}
		
		$dg->redirect('index.php/addon/installed');
	}
}

?>