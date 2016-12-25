<?php 
$product = $GLOBALS['product'];
?>
<div class="modal fade" id="dg-products" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="row">							
					<div class="col-sm-11" id="list-categories">
						<?php if ( isset($product->categories) && count ($product->categories) ) { ?>
						<div class="col-xs-4 col-md-3">
							<select data-level="1" id="parent-categories-1" class="form-control input-sm" onchange="design.products.changeCategory(this)">
								<option value="0"> - <?php echo lang('designer_product_select_category'); ?> - </option>
								<?php 
								foreach ($product->categories as $category) { 
								if ($category->parent_id > 0) continue;
								?>
								<option value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
								<?php } ?>
								
							</select>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<!-- list product category -->
					<div class="product-list col-sm-12">
					</div>
					
					<!-- product detail -->
					<div class="products-detail col-sm-12">
						<button type="button" class="btn btn-danger btn-sm" id="close-product-detail"><?php echo lang('designer_close_btn'); ?></button>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('designer_close_btn'); ?></button>
				<button type="button" class="btn btn-primary" id="loading-change-product" data-loading-text="<?php echo lang('designer_loading_btn'); ?>..." onclick="design.products.changeDesign(this)"><?php echo lang('designer_product_change_product'); ?></button>
			</div>
		</div>
	</div>
</div>