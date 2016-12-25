<?php 
$product = $GLOBALS['product'];
$settings = $GLOBALS['settings'];
$addons = $GLOBALS['addons'];
if (isset($settings->show_detail_price) && $settings->show_detail_price == 0)
{
	echo '<style>div.product-price-info{display:none;}</style>';
}
?>
<div class="col-right">
	<span class="arrow-mobile active" data="right">
		<div class="product-color-active list-colors">
			<span class="bg-colors product-color-active-value"></span> 
			<span><?php echo lang('product_color'); ?></span>
		</div>
		<button type="button" class="btn btn-xs btn-primary">
			<i class="fa fa-shopping-cart"></i> <span><?php echo lang('designer_right_buy_now'); ?></span>
		</button>
		<i class="fa fa-times"></i>
	</span>
	<div id="dg-right">
		<!-- product -->
		<div class="align-center" id="right-options">
			<div class="dg-box">
				<div class="accordion">
					<h3><?php echo lang('designer_right_product_options'); ?></h3>
					<div class="product-options contentHolder" id="product-details">
					<?php if ($product != false) { ?>
						<div class="content-y">									
							<?php if (isset($product->design) && $product->design != false) { ?>
							<div class="product-info">
								<div class="form-group product-fields">
									<label for="fields"><?php echo lang('designer_right_choose_product_color'); ?></label>
									<div class="list-colors" id="product-list-colors">
										
										<?php for ($i=0; $i<count($product->design->color_hex); $i++) { ?>
										<span class="bg-colors dg-tooltip <?php if ($i==0) echo 'active'; ?>" onclick="design.products.changeColor(this, <?php echo $i; ?>)" data-color="<?php echo $product->design->color_hex[$i]; ?>" data-placement="top" data-original-title="<?php echo $product->design->color_title[$i]; ?>">
											
											<?php 
												$colors_hex = explode(';', $product->design->color_hex[$i]);
												$span_with = (23/count($colors_hex));
											?>
											<?php for($jc=0; $jc<count($colors_hex); $jc++) { ?>
												<a href="javascript:void(0);" style="width:<?php echo $span_with; ?>px; background-color:#<?php echo $colors_hex[$jc]; ?>"></a>
											<?php } ?>
										</span>
										<?php } ?>
										
									</div>
								</div>
								<?php $addons->view('product'); ?>
							</div>
							<?php } ?>
							<form method="POST" id="tool_cart" name="tool_cart" action="">							
							<div class="product-info" id="product-attributes">
								<?php if (isset($product->attribute)) { ?>
									<?php echo $product->attribute; ?>
								<?php } ?>
								<?php $addons->view('attribute'); ?>
							</div>
							</form>	
						</div>
					<?php } ?>
					</div>
					
					<h3 <?php echo cssShow($settings, 'show_color_used'); ?>><?php echo lang('designer_right_color_used'); ?></h3>
					<div class="color-used" <?php echo cssShow($settings, 'show_color_used'); ?>></div>
					
					<h3 <?php echo cssShow($settings, 'show_screen_size'); ?>><?php echo lang('designer_right_screen_size'); ?></h3>
					<div class="screen-size" <?php echo cssShow($settings, 'show_screen_size'); ?>></div>					
				</div>
				<div class="product-prices">
					<div id="product-price" <?php echo cssShow($settings, 'show_total_price'); ?>>
						<span class="product-price-title"><?php echo lang('designer_right_total'); ?></span>
						<div class="product-price-list">
							<span id="product-price-old">
								<?php echo $settings->currency_symbol; ?><span class="price-old-number"></span>
							</span>
							<span id="product-price-sale">
								<?php echo $settings->currency_symbol; ?><span class="price-sale-number"></span>
							</span>
						</div>
						<span class="price-restart" title="<?php echo lang('designer_get_price'); ?>" onclick="design.ajax.getPrice()"><i class="glyphicons restart"></i></span>
					</div>
					<?php $addons->view('cart'); ?>
					<button <?php echo cssShow($settings, 'show_add_to_cart', 1); ?> type="button" class="btn btn-warning btn-addcart" onclick="design.ajax.addJs(this)"><i class="glyphicons shopping_cart"></i><?php echo lang('designer_right_buy_now'); ?></button>								
				</div>
			</div>
		</div>
	</div>
</div>
