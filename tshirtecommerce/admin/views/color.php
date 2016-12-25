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
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel"><?php echo lang('product_select_color'); ?></h4>
		</div>
		
		<div class="modal-body">
		
			<div class="row">				
				<div class="col-md-4"><input type="text" class="form-control" placeholder="<?php echo lang('color_title_place')?>" id="add-color-title" /></div>
				<div class="col-md-2"><input type="text" class="form-control color {pickerPosition:'botton'}" placeholder="<?php echo lang('color_hex_place')?>" id="add-color-color" /></div>
				<div class="col-md-2"><ul class="add-more-colors"></ul></div>
				<div class="col-md-2"><a href="javascript:void(0)" onclick="dgUI.product.color.add()" title="<?php echo lang('color_add_color')?>" class="btn btn-green"><i class="fa fa-plus"></i></a></div>
				<div class="col-md-2"><a href="javascript:void(0)" onclick="dgUI.product.addHex()" class="btn btn-primary"><?php echo lang('add'); ?></a></div>				
			</div>
			<br />
			<div class="row">
				<div class="col-md-4">					
					<input type="text" class="form-control" placeholder="<?php echo lang('color_find_color_place')?>" onkeyup="dgUI.product.color.find('key', this)">
				</div>				
			</div>
			<br />
			<div class="clear-line"></div>
			
			<?php if($data['colors']) { ?>
			<ul class="colors">
			
			<?php foreach($data['colors'] as $color) { ?>
				
				<li>
				<?php if($data['function'] == null) $data['function'] = "dgUI.product.addColor"; ?>
					<?php
						if(isset($data['id']) && $data['id'] != null)
							$js = $data['function'] . "('".$color->title."', '".$color->hex."', '".$data['id']."')";
						else
							$js = $data['function'] . "('".$color->title."', '".$color->hex."')";
					?>
					<a class="box-color" href="javascript:void(0);" onclick="<?php echo $js; ?>">
						<span class="color-bg" style="background-color:#<?php echo $color->hex; ?>"></span>
						<?php echo $color->title; ?>
					</a>
				</li>
				
			<?php } ?>
			
			</ul>
			<?php } ?>
		
		</div>
		
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('cancel'); ?></button>
		</div>
	</div>
</div>