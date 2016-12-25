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

class Media extends Controllers
{

	public function index()
	{
		$this->root 	= dirname(ROOT) .DS. 'uploaded';
		
		$dgClass 		= new dg();
		$this->url 		= $dgClass->url();
		$this->url		.= 'tshirtecommerce/';		
		
		$data = array();
		$data['title'] 		= 'Media';
		$data['sub_title'] 	= 'Manage';
		
		$data['root'] 	= 'uploaded';
		$data['imgURL'] = $this->url;
		
		include_once(ROOT .DS. 'includes' .DS. 'file.php');
		$file = new File();
		$data['folders'] 	= $file->folders($this->root);
		$data['files'] 		= $file->files($this->root);
				
		$this->view('media', $data);
	}
	
	function modals($function = null, $selected = 1)
	{				
		$this->root 	= dirname(ROOT) .DS. 'uploaded';
		
		$dgClass 		= new dg();
		$this->url 		= $dgClass->url();
		$this->url		.= 'tshirtecommerce/';		
		
		$data = array();
		$data['title'] 		= 'Media';
		$data['sub_title'] 	= 'Manage';
		
		$data['root'] 		= 'uploaded';
		$data['imgURL'] 	= $this->url;
		
		include_once(ROOT .DS. 'includes' .DS. 'file.php');
		$file = new File();
		$data['folders'] 	= $file->folders($this->root);
		$data['files'] 		= $file->files($this->root);
		
		$data['selected'] 	= $selected;
		$data['function'] 	= $function;
		
		$this->modal('modals', $data);
	}
	
	public function folder($action = 'load')
	{
		$path 		= $_POST['path'];
		if (isset($_POST['folder']))
			$tree 		= $_POST['folder'];
		else
			$tree		= 0;
		
		$this->root 	= dirname(ROOT) .DS;
		
		$arr = array();
		if($action == 'load')
		{
			include_once(ROOT .DS. 'includes' .DS. 'file.php');			
			$file = new File();
				
			$folders 		= $file->folders($this->root . $path);
			$arr['folder'] 	= $folders;
			
			if($tree != 1)
			{
				$files 			= $file->files($this->root . $path); 
				$arr['files'] 	= $files;
			}
		}
		
		echo json_encode($arr);
	}
	
	// add folder
	public function add()
	{
		$path 		= $_POST['path'];
		$folder 	= $_POST['folder'];
		
		include_once(ROOT .DS. 'includes' .DS. 'file.php');			
		$file = new File();
		
		$this->root 	= dirname(ROOT);
		$path 			= $this->root .DS. $path .DS. $folder;
		$path 			= str_replace('/', DS, $path);
		
		$check 		= $file->create($path, 0755);
		
		if($check == false)
		{
			echo lang('media_exists');
		}
		else
		{
			echo 1;
		}
		exit();
	}
	
	// rename folder
	public function rename()
	{
		$path 	= $_POST['path'];
		$tree 	= $_POST['folder'];
		
		$check = strripos($path, '/');
		$ds = '/';
		
		if($check === false)
		{
			$check = strripos($path, '\\');
			$ds = '\\';
		}
		
		if($check === false)
		{
			echo lang('media_folder_found');
			exit;
		}
		
		$folders = explode($ds, $path);
		if($folders > 1)
		{
			$src 	= '';
			$n = count($folders) - 1;
			for($i=0; $i<$n; $i++)
			{
				if($i == 0) $src = $folders[$i];
				else $src .= $ds . $folders[$i];
			}
			$src .= $ds . $tree;
		}		
		
		include_once(ROOT .DS. 'includes' .DS. 'file.php');			
		$file = new File();
		$this->root 	= dirname(ROOT);
		
		$dis 	= $this->root .DS. $path;
		$dis	= str_replace('/', DS, $dis);
		
		$src 	= $this->root .DS. $src;
		$src	= str_replace('/', DS, $src);
		
		echo $file->rename($dis, $src);
		exit();
	}
	
	// remove folder
	public function remove()
	{
		$path 	= $_POST['path'];
		
		include_once(ROOT .DS. 'includes' .DS. 'file.php');			
		$file = new File();
		
		$this->root 	= dirname(ROOT);
		$src 	= $this->root .DS. $path;
		$src	= str_replace('/', DS, $src);
		echo $file->removeFolder($src);
		exit();
	}
	
	// upload files
	public function upload()
	{		
		require_once dirname(ROOT) .DS. 'includes' .DS. 'upload.php';
		$data = array();
		$data['status'] = 0;
		if (!empty($_FILES['myfile']))
		{
			$folder		= $_GET['folder'];
			$root		= dirname(ROOT) .DS. $folder;
			$root		= str_replace('/', DS, $root);
			$root		= str_replace(DS.DS, DS, $root) .DS;			
			
			$uploader   =   new Uploader();
			$uploader->setDir($root);

			$uploader->setExtensions(array('jpg','jpeg','png','gif','svg','pdf','doc','txt','docx'));
			$uploader->setMaxSize(10);
			$uploader->sameName(false);
			
			if($uploader->uploadFile('myfile'))
			{
				$data['status'] = 1;
				$image  		=   $uploader->getUploadName();
				$src 			= 	$folder .'/'. $image;
				
				$data['file'] 	= array(
					'title'=> $image,
					'url'=> $src,
					'file_name'=> $image,
					'thumb'=> $src,
					'file_type'=> 'image'
				);			
			}
			else
			{
				$data['status'] = 0;
				$data['msg'] 	= $uploader->getMessage();				
			}			
			echo json_encode($data);
			exit;
		}	
	}
	
	// rename file
	function FileFename()
	{
		$file 		= $_POST['path'];
		$title 		= $_POST['folder'];
		
		$path 		= dirname(ROOT) .DS. $file;
		$path		= str_replace('/', DS, $path);
		if(file_exists($path) == true)
		{
			$info = pathinfo($path);
			$new = $info['dirname'] .DS. $title .'.'. $info['extension'];
			$check = rename($path, $new);
			
			if($check == false)
			{
				echo lang('media_file_rename');
				exit();
			}
			echo '1';
			exit();
		}
		else
		{
			echo lang('media_file_found');
			exit();
		}
	}
	
	// remove file
	function FileRemove()
	{
		$path 	= $_POST['path'];
		
		include_once(ROOT .DS. 'includes' .DS. 'file.php');			
		$file = new File();
		
		$path 		= dirname(ROOT) .DS. $path;
		$path		= str_replace('/', DS, $path);
		
		$check = $file->delete_file( $path );
		if($check == true)
		{
			echo '1';
		}
		else
		{
			echo lang('media_remove_file_msg');
		}
		exit();
	}
}

?>