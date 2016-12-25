<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-02-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
error_reporting(0);
define('DS', DIRECTORY_SEPARATOR);
$file = dirname(dirname(__FILE__)) .DS. 'version.json';
if (file_exists($file))
{
	$content = file_get_contents($file);
	if ($content != false)
	{
		$version = json_decode($content);
		
		//get main url
		if (isset($_POST['url']))
		{
			$main_url = $_POST['url'];
			$main_url = explode('tshirtecommerce', $main_url);
			$root = $main_url[0];
		}
		else
		{
			$root = '';
		}
		
		
		// wordpress
		if ($version->platforms == 'wordpress')
		{
			$url = 'http://updates.tshirtecommerce.com/wp/updates.json';
			if ($root == '')
				$link_update = '#';
			else
				$link_update = $root.'wp-admin/admin.php?page=admin.php?page=online_designer_update';
		}
		else if($version->platforms == 'opencart')
		{
			
		}
		
		$content = openURL($url);
		if ($content != false)
		{
			$result = array();
			
			$data = json_decode($content, true);
			if ( isset($data[0]) )
			{
				$new = str_replace('.', '', $data[0]['version']);
				$old = str_replace('.', '', $version->version);
				if ($new > $old)
				{
					$result['content'] = '<a href="#">Version '.$data[0]['version'].'</a> is available! <a href="#" onclick="openUpdate(\''.$link_update.'\')" title="Click here to update">Please update now.</a>';
				}
				
				$result['url'] = $link_update;
				$result['version'] = $data[0]['version'];
			}
			echo json_encode($result);
			exit;
		}
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
?>