<?php
$addons = $GLOBALS['addons'];
?>
<div id="options-add_item_text" class="dg-options">
	<div class="dg-options-toolbar">
		<div aria-label="First group" role="group" class="btn-group btn-group-lg">
			<button class="btn btn-default" type="button" data-type="text">
				<i class="fa fa-pencil"></i> <small class="clearfix"><?php echo lang('designer_text'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="fonts">
				<i class="fa fa-font"></i> <small class="clearfix"><?php echo lang('designer_fonts'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="style">
				<i class="fa fa-align-justify"></i> <small class="clearfix"><?php echo lang('designer_style'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="outline">
				<i class="fa fa-crop"></i> <small class="clearfix"><?php echo lang('designer_clipart_edit_out_line'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="size">
				<i class="fa fa-text-height"></i> <small class="clearfix"> <?php echo lang('designer_clipart_edit_size'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="rotate">
				<i class="fa fa-rotate-right"></i> <small class="clearfix"><?php echo lang('designer_clipart_edit_rotate'); ?></small>
			</button>
			<?php $addons->view('text-mobile'); ?>
		</div>
	</div>
	
	<div class="dg-options-content">
		<!-- edit text -->
		<div class="row toolbar-action-text">
			<div class="col-xs-12">
				<textarea class="form-control text-update" data-event="keyup" data-label="text" id="enter-text"></textarea>
			</div>
		</div>
		
		<div class="row toolbar-action-fonts">
			<div class="col-xs-8">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_choose_a_font'); ?></small>
					<div class="dropdown" data-target="#dg-fonts" data-toggle="modal">
						<a id="txt-fontfamily" class="pull-left" href="javascript:void(0)">
						<?php echo lang('designer_clipart_edit_arial'); ?>
						</a>
						<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s pull-right"></span>
					</div>
				</div>
			</div>
			<div class="col-xs-4 position-static">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_text_color'); ?></small>
					<div class="list-colors">
						<a class="dropdown-color" id="txt-color" href="javascript:void(0)" data-color="black" data-label="color" style="background-color:black">
							<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="clear-line"></div>
		<div class="clear"></div>
		
		<div class="row toolbar-action-style">
			<div class="col-xs-6">
				<small><?php echo lang('designer_clipart_edit_text_style'); ?></small>
				<div id="text-style">
					<span id="text-style-i" class="text-update btn btn-default btn-xs glyphicons italic glyphicons-12" data-event="click" data-label="styleI"></span>
					<span id="text-style-b" class="text-update btn btn-default btn-xs glyphicons bold glyphicons-12" data-event="click" data-label="styleB"></span>							
					<span id="text-style-u" class="text-update btn btn-default btn-xs glyphicons text_underline glyphicons-12" data-event="click" data-label="styleU"></span>
				</div>
			</div>
			<div class="col-xs-6">
				<small><?php echo lang('designer_clipart_edit_text_align'); ?></small>
				<div id="text-align">
					<span id="text-align-left" class="text-update btn btn-default btn-xs glyphicons align_left glyphicons-12" data-event="click" data-label="alignL"></span>
					<span id="text-align-center" class="text-update btn btn-default btn-xs glyphicons align_center glyphicons-12" data-event="click" data-label="alignC"></span>
					<span id="text-align-right" class="text-update btn btn-default btn-xs glyphicons align_right glyphicons-12" data-event="click" data-label="alignR"></span>
				</div>
			</div>
		</div>
		
		<div class="clear"></div>
				
		<div class="row toolbar-action-size">
			<div class="col-xs-3 col-lg-3 align-center">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_width'); ?></small>
					<input type="text" size="2" id="text-width" readonly disabled>
				</div>
			</div>
			<div class="col-xs-3 col-lg-3 align-center">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_height'); ?></small>
					<input type="text" size="2" id="text-height" readonly disabled>
				</div>
			</div>
			<div class="col-xs-6 col-lg-6 align-left">
				<div class="form-group">
					<small><?php echo lang('designer_clipart_edit_unlock_proportion'); ?></small><br />
					<input type="checkbox" class="ui-lock" id="text-lock" />
				</div>
			</div>
		</div>
		
		<div class="row toolbar-action-rotate">					
			<div class="form-group col-xs-12">
				<small><?php echo lang('designer_clipart_edit_rotate'); ?></small>
				<div class="">
					<span class="rotate-values"><input type="text" value="0" class="input-small rotate-value" id="text-rotate-value" />&deg;</span>
					<span class="rotate-refresh glyphicons refresh"></span>
				</div>								
			</div>
		</div>
				
		<div class="row toolbar-action-outline">				
			<div class="form-group col-xs-12">
				<small><?php echo lang('designer_clipart_edit_out_line'); ?></small>
				<div class="option-outline">							
					<div class="list-colors">
						<a class="dropdown-color bg-none" data-label="outline" data-placement="top" href="javascript:void(0)" data-color="none">
							<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>
						</a>
					</div>
					<div class="dropdown-outline">
						<a data-toggle="dropdown" class="dg-outline-value" href="javascript:void(0)"><span class="outline-value pull-left">0</span> <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s pull-right"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><div id="dg-outline-width"></div></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
				
		<?php $addons->text(); ?>		
	</div>
</div>