<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-04
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE
 *
 */
error_reporting(0);
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}
function openURL($url)
{
	if(get_http_response_code($url) != "200")
	{
		return false;
	}
	
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
	
if (isset($_GET['name']) && isset($_GET['type']))
{
	$font_name = $_GET['name'];
	$font_type = $_GET['type'];
	if ($font_type == 'google')
	{
		$file = 'google-'.$font_name;	
	}
	else
	{
		$file = 'add-'.$font_name;
	}
	$file = str_replace(' ', '_', $file);
	$file = str_replace('+', '_', $file);
	
	include_once ROOT .DS. 'includes' .DS. 'functions.php';
	$dg = new dg();
	$cache = $dg->cache('fonts');
	
	$content = $cache->get($file);
	
	if ($content == false)
	{
		if ($font_type == 'google')
		{
			$font_name = str_replace(' ', '+', $font_name);
			$url = 'http://fonts.googleapis.com/css?family='.$font_name;
			$data = openURL($url);
			
			if ($data === false)
			{
				echo 0; exit();
			}
			preg_match_all("/url\((.*)\.ttf\)/s", $data, $links);
			
			if (isset($links[1]) && isset($links[1][0]))
			{
				$content = openURL($links[1][0].'.ttf');
				
				if ($content == false)
				{
					echo 0; exit();
				}
			
				$content = base64_encode($content);
				$cache->set($file, $content);
			}
			else
			{
				echo 0; exit();
			}
		}
		else
		{
			$file_font = ROOT .DS. $font_type;
			$data = file_get_contents($file_font);
			$content = base64_encode($data);
			$cache->set($file, $content);
		}
	}
	
	echo $content;
	exit;
}
else
{
	echo '0'; exit();
}








