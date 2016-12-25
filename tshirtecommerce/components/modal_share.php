<?php 
$settings = $GLOBALS['settings'];
$addons = $GLOBALS['addons'];
?>
<div class="modal fade" id="dg-share" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>						
				<h4><?php echo lang('designer_share_save_completed'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo lang('designer_share_your_design_link'); ?>:</label>
					<input type="text" class="form-control" id="link-design-saved" value="" readonly>
				</div>
				
				<div class="form-group row">
					<label class="col-xs-1 col-sm-1 col-md-1" style="line-height: 24px;"><?php echo lang('designer_share'); ?>: </label>					
					<div class="col-xs-1 col-sm-1 col-md-1">
						<a href="javascript:void(0)" onclick="design.share.facebook()" class="icon-25 share-facebook" title="Facebook"></a> 
					</div>
					<div class="col-xs-1 col-sm-1 col-md-1">
						<a href="javascript:void(0)" onclick="design.share.twitter()" class="icon-25 share-twitter" title="Twitter"></a>
					</div>
					<div class="col-xs-1 col-sm-1 col-md-1">
						<a href="javascript:void(0)" onclick="design.share.pinterest()" class="icon-25 share-pinterest" title="Pinterest"></a> 
					</div>
					<?php $addons->view('share'); ?>
				</div>
			</div>
		</div>
	</div>
</div>