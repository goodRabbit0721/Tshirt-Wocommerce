<?php
//error_reporting(0);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$src 	= $_GET['src'];

if(strpos($src, 'http') === false)
	$src	= 'http://'. $src;

$src 	= str_replace(' ', '%20', $src);

$type = pathinfo($src, PATHINFO_EXTENSION);

$img =  openURL($src);
header("Content-type: image/$type");
echo $img;
exit;

function openURL($url)
{	
	$url = str_replace('//uploaded', '//uploaded', $url);
	
	if( ini_get('allow_url_fopen') )
	{
		$host = base_url();
		$temp1 = explode('tshirtecommerce/', $host);
		$temp2 = explode('tshirtecommerce/', $url);
		
		if ($temp1[0] == $temp2[0])
		{
			$temp = explode('uploaded', $temp2[1]);
			$url = ROOT .DS. 'uploaded' .DS. str_replace('/', DS, $temp[1]);
		}
		$data = file_get_contents($url);
	}
	else
	{		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
	}
	return $data;
}

function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
	if (isset($_SERVER['HTTP_HOST'])) {
		$http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
		$hostname = $_SERVER['HTTP_HOST'];
		$dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

		$core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
		$core = $core[0];

		$tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
		$end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
		$base_url = sprintf( $tmplt, $http, $hostname, $end );
	}
	else $base_url = 'http://localhost/';

	if ($parse) {
		$base_url = parse_url($base_url);
		if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
	}

	return $base_url;
}

?>	