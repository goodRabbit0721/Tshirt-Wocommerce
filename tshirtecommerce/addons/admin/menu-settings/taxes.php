<!-- start: product -->
<?php 
	//$addons = $GLOBALS['addons'];
	$url = $_SERVER['REQUEST_URI'];
?>
<li <?php if(strpos($url, 'taxes')) echo 'class="active open taxes_menu"' ?>>
	<a href="<?php echo site_url('index.php/taxes'); ?>">
		<span class="title"> Taxes </span>
	</a>
</li>
<!-- end: product -->
<?php if(strpos($url, 'taxes')){ ?>
	<script type="text/javascript">
		jQuery('.taxes_menu').parent('.sub-menu').show();
		jQuery('.taxes_menu').parent('.sub-menu').parent('li').addClass('open');
	</script>
<?php } ?>