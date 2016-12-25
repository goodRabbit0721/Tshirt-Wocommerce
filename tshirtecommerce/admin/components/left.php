<div class="navbar-content">
	<!-- start: SIDEBAR -->
	<div class="main-navigation navbar-collapse collapse">
		<!-- start: MAIN MENU TOGGLER BUTTON -->
		<div class="navigation-toggler">
			<i class="clip-chevron-left"></i>
			<i class="clip-chevron-right"></i>
		</div>
		<!-- end: MAIN MENU TOGGLER BUTTON -->
		
		<!-- start: MAIN NAVIGATION MENU -->
		<ul class="main-navigation-menu">
		
			<!-- start: dashboard -->
			<li <?php if($segments[0] == 'dashboard') echo 'class="active open"' ?>>
				<a href="<?php echo site_url(); ?>"><i class="clip-home-3"></i>
					<span class="title"> <?php lang('menu_left_dashboard'); ?> </span><span class="selected"></span>
				</a>
			</li>
			<!-- end: dashboard -->
			
			<!-- start: product -->
			<li <?php if($segments[0] == 'product') echo 'class="active open"' ?>>
				<a href="javascript:void(0)"><i class="clip-t-shirt"></i>
					<span class="title"> <?php lang('menu_left_products'); ?> </span><i class="icon-arrow"></i>
					<span class="selected"></span>
				</a>
				<ul class="sub-menu">
					<li <?php if(($segments[0] == 'product' && empty($segments[1])) || ($segments[0] == 'product' && isset($segments[1]) && $segments[1] == 'index')) echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/product'); ?>">
							<span class="title"> <?php lang('menu_left_products'); ?> </span>
						</a>
					</li>
					<li <?php if($segments[0] == 'product' && isset($segments[1]) && $segments[1] == 'edit') echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/product/edit'); ?>">
							<span class="title"> <?php lang('menu_left_add_product'); ?> </span>
						</a>
					</li>					
				</ul>
			</li>
			<!-- end: product -->
			
			<!-- start: clipart -->
			<li <?php if($segments[0] == 'clipart') echo 'class="active open"' ?>>
				<a href="<?php echo site_url('index.php/clipart'); ?>">
					<i class="clip-pictures"></i>
					<span class="title"> 
						<?php lang('menu_left_clipart'); ?> 
					</span>									
				</a>				
			</li>
			<!-- end: clipart -->
			
			<!-- start: addons -->
			<li <?php if($segments[0] == 'addon') echo 'class="active open"' ?>>
				<a href="javascript:void(0)"><i class="clip-puzzle-4"></i>
					<span class="title"> <?php lang('menu_left_addons'); ?> </span><i class="icon-arrow"></i>
					<span class="selected"></span>
				</a>
				<ul class="sub-menu">
					<li <?php if($segments[0] == 'addon' && isset($segments[1]) && $segments[1] == 'installed') echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/addon/installed'); ?>">
							<span class="title"> <?php lang('menu_left_installed'); ?> </span>
						</a>
					</li>
					<li <?php if($segments[0] == 'addon' && isset($segments[1]) && $segments[1] == 'install') echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/addon/install'); ?>">
							<span class="title"> <?php lang('menu_left_install'); ?> </span>
						</a>
					</li>
					<li <?php if(($segments[0] == 'addon' && empty($segments[1])) || ($segments[0] == 'addon' && isset($segments[1]) && $segments[1] == 'index')) echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/addon'); ?>">
							<span class="title"> <?php lang('menu_left_addons'); ?> </span>
						</a>
					</li>					
				</ul>
			</li>
			<!-- end: addons -->
			
			<!-- start: bank -->
			<li <?php if($segments[0] == 'settings' || ($segments[0] == 'settings' && isset($segments[1]) && ($segments[1] == 'fonts' || $segments[1] == 'editfont' || $segments[1] == 'colors' || $segments[1] == 'editcolor'))) echo 'class="active open"' ?>>
				<a href="javascript:void(0)"><i class="	clip-settings"></i>
					<span class="title"><?php echo lang('menu_left_settings');?></span><i class="icon-arrow"></i>
					<span class="selected"></span>
				</a>
				<ul class="sub-menu">
					<li <?php if($segments[0] == 'settings' && (empty($segments[1]) || (isset($segments[1]) && $segments[1] == 'index'))) echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/settings'); ?>">
							<span class="title"><?php echo lang('menu_left_settings_configuration');?></span>
						</a>
					</li>
					
					<li <?php if($segments[0] == 'settings' && isset($segments[1]) && ($segments[1] == 'colors' || $segments[1] == 'editcolor')) echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/settings/colors'); ?>">
							<span class="title"><?php echo lang('menu_left_settings_colors');?></span>
						</a>
					</li>
					
					<li <?php if($segments[0] == 'settings' && isset($segments[1]) && ($segments[1] == 'fonts' || $segments[1] == 'addgooglefont' || $segments[1] == 'editfont')) echo 'class="active open"' ?>> <!-- Fixed active menu font file -->
						<a href="<?php echo site_url('index.php/settings/fonts'); ?>">
							<span class="title"><?php echo lang('menu_left_settings_fonts');?></span>
						</a>
					</li>
					
					<li <?php if($segments[0] == 'settings' && isset($segments[1]) && ($segments[1] == 'languages' || $segments[1] == 'editlanguage')) echo 'class="active open"' ?>>
						<a href="<?php echo site_url('index.php/settings/languages'); ?>">
							<span class="title"><?php echo lang('menu_left_settings_language');?></span>
						</a>
					</li>
					<?php $addons->view('menu-settings', $addons, $segments); ?>
				</ul>
			</li>		
			<!-- end: bank -->		
			
			<?php $addons->view('menu', $addons, $segments); ?>
			
			<!-- start: media -->
			<li <?php if($segments[0] == 'media') echo 'class="active open"' ?>>
				<a href="<?php echo site_url('index.php/media'); ?>">
					<i class="clip-image"></i>
					<span class="title"><?php echo lang('menu_left_media');?></span>
					<span class="selected"></span>
				</a>
			</li>
			<!-- end: media -->
		</ul>
		<!-- end: MAIN NAVIGATION MENU -->
	</div>
	<!-- end: SIDEBAR -->
</div>
<!-- start: PAGE -->