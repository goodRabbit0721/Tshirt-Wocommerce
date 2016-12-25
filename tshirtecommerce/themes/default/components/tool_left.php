<?php
$settings = $GLOBALS['settings'];
$addons = $GLOBALS['addons'];
?>
<div class="col-left">
	<div class="text-center product-btn-info">		
		<a href="#none" <?php echo cssShow($settings, 'show_product_info'); ?> data-target="#modal-product-info" data-toggle="modal" class="btn btn-default pull-left btn-sm"><i class="fa fa-info"></i> <span><?php echo lang('design_product_info'); ?></span></a>
		<a href="#none" <?php echo cssShow($settings, 'show_product_size'); ?> data-target="#modal-product-size" data-toggle="modal" class="btn btn-default pull-right btn-sm"><i class="fa fa-male"></i> <span><?php echo lang('design_size_chart'); ?></span></a>
	</div>
	
	<div id="dg-left" class="width-100">
		<div class="dg-box width-100">
			<ul class="menu-left">
				<li <?php echo cssShow($settings, 'show_product'); ?>>
					<a href="#dg-products" class="view_change_products" title="" data-toggle="modal" data-target="#dg-products">
						<i class="glyphicons t-shirt"></i> <span><?php echo lang('designer_menu_choose_product'); ?></span>
					</a>
				</li>			
				<li <?php echo cssShow($settings, 'show_add_text'); ?>>
					<a href="javascript:void(0);" class="add_item_text" title="">
						<i class="glyphicons text_bigger"></i> <span><?php echo lang('designer_menu_add_text'); ?></span>
					</a>
				</li>
				
				<li <?php echo cssShow($settings, 'show_add_art'); ?>>
					<a href="#dg-cliparts" class="add_item_clipart" title="" data-toggle="modal" data-target="#dg-cliparts">
						<i class="glyphicons picture"></i> <span><?php echo lang('designer_menu_add_art'); ?></span>
					</a>
				</li>							
				<li <?php echo cssShow($settings, 'show_add_upload'); ?>>
					<a href="#dg-myclipart" title="" data-toggle="modal" data-target="#dg-myclipart">
						<i class="glyphicons cloud-upload"></i> <span><?php echo lang('designer_menu_upload_image'); ?></span>
					</a>
				</li>
				
				<li <?php echo cssShow($settings, 'show_add_team'); ?>>
					<a href="javascript:void(0);" class="add_item_team" title="">
						<i class="glyphicons soccer_ball"></i> <span><?php echo lang('designer_menu_name_number'); ?></span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);" class="add_item_mydesign" <?php echo cssShow($settings, 'show_my_design'); ?>>
						<i class="glyphicons user"></i> <span><?php echo lang('designer_menu_my_design'); ?></span>
					</a>
				</li>				
				<li <?php echo cssShow($settings, 'show_add_qrcode'); ?>>
					<a href="javascript:void(0);" class="add_item_qrcode" title="">
						<i class="glyphicons qrcode"></i> <span><?php echo lang('designer_menu_add_qrcode'); ?></span>
					</a>
				</li>
				<?php $addons->view('menu-left'); ?>
			</ul>
		</div>
		
		<div class="dg-box width-100 div-layers" <?php echo cssShow($settings, 'show_layers'); ?>>
			<div class="layers-toolbar">
				<button type="button" class="btn btn-default btn-sm">
					<i class="icon-layers"></i>
					<span><?php echo lang('designer_menu_login_layers'); ?></span>
				</button>
			</div>
				
			<div class="accordion control-layers">
				<h3><?php echo lang('designer_menu_login_layers'); ?></h3>
				<div id="dg-layers">
					<ul id="layers"></ul>
				</div>
			</div>
		</div>
	</div>
</div>