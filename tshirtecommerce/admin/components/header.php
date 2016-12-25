<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<head>
		<title><?php echo $title; ?></title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<!-- end: META -->
		<!-- start: MAIN CSS -->
		<link href="<?php echo site_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="<?php echo site_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/fonts/style.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/css/main.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/css/main-responsive.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/css/theme_light.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/plugins/jquery-ui/jquery-ui.min.css'); ?>">
		
		<?php echo $addons->css(); ?>
		<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo site_url('assets/plugins/font-awesome/css/font-awesome-ie7.min.css'); ?>">
		<![endif]-->
		<!-- end: MAIN CSS -->		
		<script src="<?php echo site_url('assets/js/jquery.min.js'); ?>"></script>
		<script src="<?php echo site_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
		<script src="<?php echo site_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>	
		<script>
			var admin_url_site = '<?php echo site_url(''); ?>';
			jQuery(document).ready(function(){
				var height = jQuery('body').height();
				window.parent.setHeightF(height);
			});
		</script>
		<?php echo $addons->js(); ?>
	</head>
<body>