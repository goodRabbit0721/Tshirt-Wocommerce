<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
?>
<div class="modal-dialog modal-lg" id="product-designer">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel"><?php echo lang('product_configure'); ?></h4>
		</div>
		
		<div class="modal-body">
			<div class="row">
				<div class="col-md-4">
					<?php echo lang('product_view_name'); ?></strong>
				</div>
				<div class="col-md-4">
					<strong><?php echo $data['position']; ?></strong>
				</div>
				<div class="col-md-4">
					<ul class="colors">
						<li>							
							<a href="javascript:void(0);" class="box-color margin-top">
								<span style="background-color:#<?php echo $data['color']; ?>" class="color-bg"></span> <?php echo $data['title']; ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			
			<br />
			<div class="clear-line"></div>
			<div class="row">
				<div class="col-md-8">
					<div class="product-design-view">
						<div id="product-images"></div>
						<div id="area-design"></div>
					</div>
					<div class="design-tools row col-md-12">
						<a href="javascript:void(0)" title="" onclick="dgUI.product.move('up')"><i class="clip-arrow-up-3"></i></a>
						<a href="javascript:void(0)" title="" onclick="dgUI.product.move('down')"><i class="clip-arrow-down-3"></i></a>
						<a href="javascript:void(0)" title="" onclick="dgUI.product.move('left')"><i class="clip-arrow-left-3"></i></a>
						<a href="javascript:void(0)" title="" onclick="dgUI.product.move('right')"><i class="clip-arrow-right-3"></i></a>
						<a href="javascript:void(0)" title="" onclick="dgUI.product.move('center')"><i class="clip-fullscreen-exit-alt"></i></a>
						<a href="javascript:void(0)" title="" onclick="jQuery.fancybox( {href : '<?php echo site_url('index.php/media/modals/design/2') ?>', type: 'iframe'} );"><i class="clip-upload"></i> <?php echo lang('product_change_design');?></a>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">
							<span class=""><i class="glyphicon glyphicon-move"></i> Click images, area design to move, resize object.</span>
						</div>
					</div>
				</div>
				
				<div class="col-md-4">
					<!-- area size -->
					<div class="panel panel-simple">
						<div class="panel-heading">
							<span class="attribute-title"><?php echo lang('product_set_dimensins');?></span>
							<div class="panel-tools">
								<a class="btn btn-xs btn-link panel-collapse collapses" href="javascript:void(0);"></a>
							</div>
						</div>
						
						<div class="panel-body area-size">
							<div class="pull-left row col-md-9">
								<label><?php echo lang('product_width');?></label>
								<div class="input-group">									
									<input type="text" class="form-control area-width" onkeyup="dgUI.product.area(this);" value="" />
									<span class="input-group-addon">cm</span>
								</div>
							
								<label><?php echo lang('product_height');?></label>
								<div class="input-group">									
									<input type="text" class="form-control area-height" onkeyup="dgUI.product.area(this);" value="">
									<span class="input-group-addon">cm</span>
								</div>
							</div>
							<div class="design-area-lock">
								<span>
									<input type="checkbox" class="area-locked-width" onclick="dgUI.product.lock(this)" /> <?php echo lang('product_locked')?>
								</span>
								<span>
									<input type="checkbox" class="area-locked-height" onclick="dgUI.product.lock(this)" /> <?php echo lang('product_locked')?>
								</span>
							</div>
						</div>
					</div>
					
					<!-- shape -->
					<div class="panel panel-simple">
						<div class="panel-heading">
							<span class="attribute-title"><?php echo lang('product_select_printable')?></span>
							<div class="panel-tools">
								<a class="btn btn-xs btn-link panel-collapse collapses" href="javascript:void(0);"></a>
							</div>
						</div>
						
						<div class="panel-body">
							<div class="pull-left">
								<div class="shape-tool">
									<a href="javascript:void(0)" title="<?php echo lang('product_square');?>" onclick="dgUI.product.shape('square', this)"><span class="shape-square"></span></a>
									<a href="javascript:void(0)" title="<?php echo lang('product_circle');?>" onclick="dgUI.product.shape('circle', this)"><span class="shape-circle"></span></a>
									<a href="javascript:void(0)" title="<?php echo lang('product_circlesquare');?>" onclick="dgUI.product.shape('circlesquare', this)"><span class="shape-circlesquare"></span></a>
								</div>
							</div>
							<div class="pull-left" id="shape-slider"></div>
							<input type="hidden" value="0" id="shape-slider-value" />
						</div>
					</div>
					
					<!-- options -->
					<div class="panel panel-simple" style="display:none;">
						<div class="panel-heading">
							<span class="attribute-title"><?php echo lang('product_other_options');?></span>
							<div class="panel-tools">
								<a class="btn btn-xs btn-link panel-collapse collapses" href="javascript:void(0);"></a>
							</div>
						</div>
						
						<div class="panel-body">
							<input type="checkbox" class="options-setbgcolor"  /> <?php echo lang('product_other_setbg_color');?>
						</div>
					</div>
					
					<!-- layers -->
					<div class="panel panel-simple">
						<div class="panel-heading">
							<span class="attribute-title"><?php echo lang('product_other_layers');?></span>
							<div class="panel-tools">
								<a class="btn btn-xs btn-link panel-collapse collapses" href="javascript:void(0);"></a>
							</div>
						</div>
						
						<div class="panel-body">
							<ul id="layers"></ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" id="design-view-number" value="<?php echo $data['number']; ?>" />
		<div class="modal-footer">			
			<button type="button" class="btn btn-primary white" onclick="dgUI.product.save('<?php echo $data['position']; ?>', '<?php echo $data['color']; ?>')"><?php echo lang('save'); ?></button>
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('cancel'); ?></button>						
		</div>
	</div>
</div>