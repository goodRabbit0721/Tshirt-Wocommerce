<!-- start: printing -->
<li <?php if($data[0] == 'printing') echo 'class="active"' ?>>
	<a href="<?php echo site_url('index.php/printing'); ?>">
		<i class="fa fa-print"></i>
		<span class="title"> <?php echo $addons->lang['addon_printing_type']; ?> </span>
	</a>
</li>
<!-- end: printing -->