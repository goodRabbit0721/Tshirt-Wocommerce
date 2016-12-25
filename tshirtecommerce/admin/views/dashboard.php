<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT')) exit('No direct script access allowed');

?>
<div class="row">
	<div class="col-md-8">
		<div class="control-panel-box">
			<div class="row">
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
					<a class="control-panel-icon" href="<?php echo site_url('index.php/clipart'); ?>">
						<i class="fa fa-picture-o fa-4x"></i><span><?php lang('dashboard_arts')?></span>
					</a>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
					<a class="control-panel-icon" href="<?php echo site_url('index.php/product'); ?>">
						<i class="clip-t-shirt fa-4x"></i><span><?php lang('dashboard_products')?></span>
					</a>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
					<a class="control-panel-icon" href="<?php echo site_url('index.php/product/edit'); ?>">
						<i class="fa fa-plus-square fa-4x"></i><span><?php lang('dashboard_add_product')?></span>
					</a>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
					<a class="control-panel-icon" href="<?php echo site_url('index.php/settings'); ?>">
						<i class="fa fa-gear fa-4x"></i><span><?php lang('dashboard_config')?></span>
					</a>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
					<a class="control-panel-icon" href="<?php echo site_url('index.php/settings/colors'); ?>">
						<i class="fa fa-adjust fa-4x"></i><span><?php lang('dashboard_colors')?></span>
					</a>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
					<a class="control-panel-icon" href="<?php echo site_url('index.php/settings/fonts'); ?>">
						<i class="fa fa-font fa-4x"></i><span><?php lang('dashboard_fonts')?></span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="shop-info">
			<h3>T Shirt eCommerce</h3>
			<span>Online solution for printing</span><br/>
			<span>Website: </span> <a target="_blank" href="http://tshirtecommerce.com">http://tshirtecommerce.com</a><br/>
			<span>Email: </span> <a href="mailto:info@tshirtecommerce.com">info@tshirtecommerce.com</a>
		</div>
	</div>
	<?php include('feed_news.php') ?>
</div>