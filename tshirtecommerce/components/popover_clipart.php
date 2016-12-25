<?php
$addons = $GLOBALS['addons'];
?>
<div id="options-add_item_clipart" class="dg-options">
	<div class="dg-options-toolbar">
		<div aria-label="First group" role="group" class="btn-group btn-group-lg">						
			<button class="btn btn-default btn-action-edit" type="button" data-type="edit">
				<i class="glyphicon glyphicon-tint"></i> <small class="clearfix"><?php echo lang('designer_edit'); ?></small>
			</button>
			<button class="btn btn-default btn-action-colors" type="button" data-type="colors">
				<i class="glyphicon glyphicon-tint"></i> <small class="clearfix"> <?php echo lang('designer_colors'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="size">
				<i class="fa fa-text-height"></i> <small class="clearfix"> <?php echo lang('designer_clipart_edit_size'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="rotate">
				<i class="fa fa-rotate-right"></i> <small class="clearfix"><?php echo lang('designer_clipart_edit_rotate'); ?></small>
			</button>			
		</div>
	</div>
	
	<div class="dg-options-content">
		<div class="row toolbar-action-edit">					
			<div id="item-print-colors">
			</div>
		</div>
		<div class="row toolbar-action-size">
			<div class="col-xs-3 col-lg-3 align-center">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_width'); ?></small>
					<input type="text" size="2" id="clipart-width" readonly disabled>
				</div>
			</div>
			<div class="col-xs-3 col-lg-3 align-center">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_height'); ?></small>
					<input type="text" size="2" id="clipart-height" readonly disabled>
				</div>
			</div>
			<div class="col-xs-6 col-lg-6 align-left">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_unlock_proportion'); ?></small><br />
					<input type="checkbox" class="ui-lock" id="clipart-lock" />
				</div>
			</div>
		</div>
		
		<div class="row toolbar-action-rotate">					
			<div class="form-group col-lg-12">
				<div class="row">
					<div class="col-xs-6 col-lg-6">
						<small><?php echo lang('designer_clipart_edit_rotate'); ?></small>
					</div>
					<div class="col-xs-6 col-lg-6 align-right">
						<span class="rotate-values"><input type="text" value="0" class="input-small rotate-value" id="clipart-rotate-value" />&deg;</span>
						<span class="rotate-refresh glyphicons refresh"></span>
					</div>
				</div>						
			</div>
		</div>
		
		<div class="row toolbar-action-colors">
			<div id="clipart-colors">
				<div class="form-group col-lg-12 text-left position-static">
					<small><?php echo lang('designer_clipart_edit_choose_your_color'); ?></small>
					<div id="list-clipart-colors" class="list-colors"></div>
				</div>
			</div>
		</div>		
	</div>
</div>