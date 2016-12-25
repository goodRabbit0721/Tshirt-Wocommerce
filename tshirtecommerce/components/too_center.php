<?php 
$products = $GLOBALS['products'];
$addons = $GLOBALS['addons'];
$settings = $GLOBALS['settings'];
?>
<div class="col-xs-12 col-md-12 col-center align-center">
	<!-- Begin sidebar -->
	<div id="dg-sidebar">
		<div class="dg-tools">
			<button type="button" class="btn btn-default btn-sm" onclick="design.save()">
				<i class="fa fa-save"></i>
				<small><?php echo lang('designer_save_btn'); ?></small>
			</button>
			<a href="javascript:void(0)" data-type="preview" class="dg-tool btn btn-default btn-sm">
				<i class="fa fa-eye"></i>
				<small><?php echo lang('designer_top_preview'); ?></small>
			</a>
			<a href="javascript:void(0)" data-type="zoom" title="<?php echo lang('designer_top_zoom'); ?>" class="dg-tool btn btn-default btn-sm">
				<i class="fa fa-search"></i>
				<small><?php echo lang('designer_top_zoom'); ?></small>
			</a>
			<button type="button" class="btn btn-default btn-sm" onclick="design.save()" <?php echo cssShow($settings, 'show_share'); ?>>
				<i class="fa fa-share-alt"></i>
				<small><?php echo lang('designer_share'); ?></small>
			</button>
			<?php $addons->view('helper'); ?>
		</div>
	</div>
	<!-- END sidebar -->
	
	<!-- design area -->
	<div id="design-area" class="div-design-area">
		<div id="app-wrap" class="div-design-area">
		<?php if ($products === false) { ?>
			<div id="view-front" class="labView active">
				<div class="product-design">
					<strong><?php echo lang('designer_product_data_found'); ?></strong>
				</div>
			</div>
		<?php } else { ?>
			
			<!-- begin front design -->						
			<div id="view-front" class="labView active">
				<div class="product-design"></div>
				<div class="design-area"><div class="content-inner"></div></div>
			</div>						
			<!-- end front design -->
			
			<!-- begin back design -->
			<div id="view-back" class="labView">
				<div class="product-design"></div>
				<div class="design-area"><div class="content-inner"></div></div>
			</div>
			<!-- end back design -->
			
			<!-- begin left design -->
			<div id="view-left" class="labView">
				<div class="product-design"></div>
				<div class="design-area"><div class="content-inner"></div></div>
			</div>
			<!-- end left design -->
			
			<!-- begin right design -->
			<div id="view-right" class="labView">
				<div class="product-design"></div>
				<div class="design-area"><div class="content-inner"></div></div>
			</div>
			<!-- end right design -->
			
		<?php } ?>
			
			<!-- BEGIN help functions -->
			<div id="dg-help-functions" <?php echo cssShow($settings, 'show_toolbar'); ?>>
				<div class="btn-group-vertical" role="group" aria-label="Group functions">
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_clipart_edit_flip'); ?>" onclick="design.tools.flip('x')">
						<i class="glyphicons transfer glyphicons-12"></i>
					</span>					
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_align_horizontal'); ?>" onclick="design.tools.move('vertical')">
						<i class="glyphicon glyphicon-object-align-vertical"></i>
					</span>
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_align_vertical'); ?>" onclick="design.tools.move('horizontal')">
						<i class="glyphicon glyphicon-object-align-horizontal"></i>
					</span>	
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_align_left'); ?>" onclick="design.tools.move('left')">
						<i class="fa fa-chevron-left"></i>
					</span>	
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_align_right'); ?>" onclick="design.tools.move('right')">
						<i class="fa fa-chevron-right"></i>
					</span>	
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_align_up'); ?>" onclick="design.tools.move('up')">
						<i class="fa fa-chevron-up"></i>
					</span>	
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_align_down'); ?>" onclick="design.tools.move('down')">
						<i class="fa fa-chevron-down"></i>
					</span>
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_team_remove'); ?>" onclick="design.tools.remove()">
						<i class="fa fa-trash-o"></i>
					</span>
					<span class="btn btn-default" data-placement="left" data-toggle="tooltip" data-original-title="<?php echo lang('designer_top_reset'); ?>" onclick="design.tools.reset(this)">
						<i class="fa fa-refresh"></i>
					</span>
					<?php $addons->view('tools'); ?>
				</div>
			</div>
			<!-- END help functions -->
		</div>
	</div>
	
	<div class="" id="product-thumbs"></div>
</div>