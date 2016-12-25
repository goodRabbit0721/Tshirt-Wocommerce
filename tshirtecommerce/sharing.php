<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-09-03
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE
 *
 */
error_reporting(0);
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

include_once ROOT .DS. 'includes' .DS. 'functions.php';

$dg = new dg();
$lang = $dg->lang();

$settings	= $dg->getSetting();

$base_url 	= $dg->url();
$params 	= explode('sharing.php/', $_SERVER['REQUEST_URI']);

if ( count($params) > 1)
{
	$design_id = $params[1];
	
	$url = $base_url . 'tshirtecommerce/sharing.php/'.$design_id;
}
else
{
	$design_id = '';
	$url = $base_url;
}

if ($design_id != '')
{
	$cache = $dg->cache('design');
	$params = explode(':', $design_id);
	$user_id = $cache->get($params[0]);
	
	if ($user_id != false && count($user_id[$params[1]]) > 0)
	{
		$data = $user_id[$params[1]];		
	}
	
	if (empty($data))
	{
		$cache = $dg->cache('admin');
		$params = explode(':', $design_id);
		$user_id = $cache->get($params[0]);
		
		if ($user_id != false && count($user_id[$params[1]]) > 0)
		{
			$data = $user_id[$params[1]];		
		}
	}
}

if (isset($data))
{
	$image = $base_url . 'tshirtecommerce/'.$data['image'];
	
	$file = dirname(__FILE__) .DS. 'version.json';
	if (file_exists($file))
	{
		$content = file_get_contents($file);
		if ($content != '')
		{
			$options = json_decode($content);
			if (isset($options->platforms))
			{
				if ($options->platforms == 'wordpress')
				{
					$base_url = $base_url . '?design_id='.$design_id;
				}
				else if ($options->platforms == 'opencart')
				{
					$base_url = $base_url . 'index.php?route=tshirtecommerce/designer&product_id='.$design_id.'&parent_id='.$params[4];
				}
			}
		}
	}
}
else
{
	$image = '';
}
?>
<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
    <meta content="<?php echo setValue($settings, 'meta_description', 'T-Shirt eCommerce'); ?>" name="description" />
	<meta content="<?php echo setValue($settings, 'meta_keywords', 'T-Shirt eCommerce'); ?>" name="keywords" />
    <title><?php echo setValue($settings, 'site_name', 'T-Shirt eCommerce'); ?></title>
	
	<meta property="og:url" content="<?php echo $url; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo setValue($settings, 'site_name', 'T-Shirt eCommerce'); ?>" />
    <meta property="og:description" content="<?php echo setValue($settings, 'meta_description', 'T-Shirt eCommerce'); ?>" />
    <meta property="og:image" content="<?php echo $image; ?>" />
	<script>
		window.location.href = "<?php echo $base_url; ?>";
	</script>
</head>
<body>	
</body>
</html>