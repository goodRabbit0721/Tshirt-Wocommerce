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

class Upload
{
	public $file_name = '';
	public $file_type = array(
		'0'=>'zip'
	);
	public $path = ROOT;
	public $file_size = 10485760; // 10MB
	public $permission = 777;
	
	function file($file)
	{
		$res = array(
			'error' => 0,
			'msg'=>''
		);
		if(isset($file['name']) && $file['name'] != '')
		{	
			$type = $this->file_type;
			
			if($this->file_name != '')
				$path = $this->getPath(basename($file['name']));
			else
				$path = $this->path .DS. basename($file['name']);
			
			if(!file_exists($this->path))
				mkdir($this->path, 0777, true);
			
			if($this->checkFileType(basename($file['name'])))
			{
				if($this->checkFileSize($file['size']))
				{
					if(!is_writable($this->path))
						chmod($this->path, $permission);
						
					if(move_uploaded_file($file["tmp_name"], $path))
					{
						$file_exten = explode('.', basename($file['name']));
						$res = array(
							'error' => 0,
							'path' => $this->path,
							'full_path' => $path,
							'file_name' => basename($file['name']),
							'file_type' => $file_exten[count($file_exten) - 1],
							'msg'=>'Upload success'
						);
					}else
					{
						$res = array(
							'error' => 1,
							'msg'=>'Sorry, there was an error uploading your file.'
						);
						
						if(!is_writable($this->path))
							$res['msg'] = 'Sorry, Please go to ftp and chmod(755) folder: '.$this->path;
					}
				}else
				{
					$res = array(
						'error' => 1,
						'msg'=>'Sorry, your file larger than 20Mb'
					);
				}
			}else
			{
				$res = array(
					'error' => 1,
					'msg'=>'Sorry, only '.implode(", ", $this->file_type).' files are allowed.'
				);
			}
		}
		return $res;
	}
	
	function getPath($data)
	{		
		$file_name = $this->file_name;
		return $this->path .DS. $file_name;
	}
	
	function checkFileType($data)
	{
		$types = $this->file_type;
		$check = false;
		$filetype = explode('.', $data);
		$file_exten = $filetype[count($filetype) - 1];
		foreach($types as $type)
		{
			if($file_exten == $type)
				$check = true;
		}
		return $check;
	}
	
	function checkFileSize($data)
	{
		if($this->file_size > $data)
			return true;
		else
			return false;
	}
}

?>