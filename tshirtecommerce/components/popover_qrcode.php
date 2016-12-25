<div id="options-add_item_qrcode" class="dg-options">
	<div class="dg-options-toolbar">
		<div role="group" class="btn-group btn-group-lg">
			<button class="btn btn-default" type="button" data-type="create-qr-code">
				<i class="glyphicons qrcode"></i> <small class="clearfix"><?php echo lang('designer_qrcode_create'); ?></small>
			</button>
			
			<button class="btn btn-default" type="button" data-type="image-qr-code">
				<i class="glyphicons picture"></i> <small class="clearfix"><?php echo lang('designer_qrcode_image'); ?></small>
			</button>
		</div>
	</div>
	
	<div class="dg-options-content">
		
		<div class="row">
			<div class="col-md-12">
				<span class="help-block">
					<?php echo lang('designer_qrcode_mgs'); ?>
				</span>
			</div>
			
			<div class="col-md-12">
				<div class="clear-line"></div><br>
			</div>
		</div>
		<div class="row toolbar-action-create-qr-code">
			<div class="col-md-12">
				<textarea class="form-control" id="enter-qrcode"></textarea>
			</div>
			
			<br	/>
			<div class="col-md-12 text-right">
				<button class="btn btn-primary input-sm" type="button" onclick="design.qrcode.create(this)">
				<?php echo lang('designer_qrcode_create'); ?>
				</button>
			</div>
		</div>
		
		<div class="row toolbar-action-image-qr-code">
			<div class="col-md-12" id="qrcode-img"></div>
		</div>
	</div>
</div>