<?php 
$product = $GLOBALS['product'];
?>
<div class="modal fade" id="modal-product-size" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="text-right clearfix">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="row">
					<div class="col-md-12 product-detail-size">
						<?php echo $product->size; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>