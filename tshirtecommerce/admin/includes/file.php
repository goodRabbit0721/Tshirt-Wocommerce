<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
class File {
	
	function exists($path)
	{
		return is_dir($this->clear($path));
	}
	
	function create($path, $mode = 0777)
	{
		if( file_exists($path) == false ){
			mkdir( $path, $mode, true);
			return true;
		}
		return false;
	}
	
	function removeFolder($path)
	{
		if( file_exists($path) == true ){
			chmod($path, 0755);
			
			$path = rtrim($path, '/') . '/';
			
			$items = glob($path . '*');
			foreach($items as $item) {
				chmod($path, 0755);
				is_dir($item) ? $this->removeFolder($item) : unlink($item);
			}
			
			return rmdir($path);
		}
		return 0;
	}
	
	function rename($path, $newfolder)
	{
		if( file_exists($path) == true ){
			return rename ( $path, $newfolder );
		}
		return 0;
	}
	
	function delete_file($path)
	{
		if( file_exists($path) == true )
		{
			chmod($path, 0644);
			return unlink($path);
		}
		
		return false;
	}
	
	function clear($path, $ds = DIRECTORY_SEPARATOR)
	{
		
		if (!is_string($path) && !empty($path))
		{
			throw new UnexpectedValueException('JPath::clean: $path is not a string.');
		}

		$path = trim($path);

		if (empty($path))
		{
			$path = JPATH_ROOT;
		}
		// Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
		// If dealing with a UNC path don't forget to prepend the path with a backslash.
		elseif (($ds == '\\') && ($path[0] == '\\' ) && ( $path[1] == '\\' ))
		{
			$path = "\\" . preg_replace('#[/\\\\]+#', $ds, $path);
		}
		else
		{
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	
	}
	
	function folders($path, $full = false)
	{
		$path 	= $this->clear($path);
		
		$arr = array();

		// Read the source directory
		if (!($handle = @opendir($path)))
		{
			return $arr;
		}

		while (($file = readdir($handle)) !== false)
		{
			if ($file != '.' && $file != '..')
			{
				// Compute the fullpath
				$fullpath = $path . DIRECTORY_SEPARATOR . $file;

				// Compute the isDir flag
				$isDir = is_dir($fullpath);
				if($isDir)
				{
					if($full)
						$arr[] = $fullpath;
					else 
						$arr[] = $file;
				}				
			}
		}
		closedir($handle);
		return $arr;
	}
	
	function files($path, $extens = array('png','jpg','gif','pdf','psd','ai','svg','jpge','jpeg'))
	{		
		$files 	= array();
		
		$path 	= $this->clear($path);
		
		if(!$this->exists($path))
		{
			return $files;
		}
		
		if ($handle = @opendir($path)) {
		
			 while ($file = readdir($handle)) {
				if ($file != "." && $file != "..") {
					if(is_file($path .DS. $file))
					{
						$info 				= $this->get_file_info($path .DS. $file, array('name', 'size', 'date', 'exten'));						
						if (count($info))
						{
							if (in_array($info['exten'], $extens))
							{
								$data				= array();
								$data['name'] 		= $info['name'];
								$data['size'] 		= $info['size'];
								$data['exten'] 		= $info['exten'];
								$data['filename'] 	= $info['filename'];
								$data['date'] 		= date("d-m-Y", $info['date']);
								$data['title'] 		= substr( str_replace( '.'.$data['exten'], '', $info['name'] ), 0, 6 );
								
								$files[] 			= $data;
							}
						}
					}
				}
			}
			
			closedir($handle);
		}		
		return $files;
	}
	
	function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date', 'exten'))
	{

		if ( ! file_exists($file))
		{
			return FALSE;
		}

		if (is_string($returned_values))
		{
			$returned_values = explode(',', $returned_values);
		}

		foreach ($returned_values as $key)
		{
			switch ($key)
			{
				case 'name':
					$fileinfo['name'] = substr(strrchr($file, DIRECTORY_SEPARATOR), 1);
					break;
				case 'server_path':
					$fileinfo['server_path'] = $file;
					break;
				case 'size':
					$fileinfo['size'] = filesize($file);
					break;
				case 'date':
					$fileinfo['date'] = filemtime($file);
					break;
				case 'exten':
					$pathinfo 			= pathinfo($file);
					$fileinfo['exten']	= $pathinfo['extension'];
					$fileinfo['filename']	= $pathinfo['filename'];
					break;
				case 'readable':
					$fileinfo['readable'] = is_readable($file);
					break;
				case 'writable':
					// There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
					$fileinfo['writable'] = is_writable($file);
					break;
				case 'executable':
					$fileinfo['executable'] = is_executable($file);
					break;
				case 'fileperms':
					$fileinfo['fileperms'] = fileperms($file);
					break;
			}
		}

		return $fileinfo;
	}
}
