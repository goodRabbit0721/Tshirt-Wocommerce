<div id="screen_colors_body" style="display:none;">
	<div id="screen_colors">
		<div class="screen_colors_top">
			<div class="col-xs-5 col-md-5 text-left" id="screen_colors_images">
			</div>
			<div class="col-xs-7 col-md-7 text-left">
				<h4><?php echo lang('designer_color_select_ink_colors'); ?></h4>
				<span class="help-block"><?php echo lang('designer_color_select_the_colors_that_appear'); ?></span>
				<span class="help-block"><?php echo lang('designer_color_this_helps_us_determine'); ?></span>
				<p><strong> <?php echo lang('designer_color_note'); ?></strong></p>
				<span id="screen_colors_error"></span>
				<div id="screen_colors_list" class="list-colors"></div>
			</div>
		</div>
		<div class="screen_colors_botton">
			<button type="button" class="btn btn-primary" onclick="design.item.setColor()"><?php echo lang('designer_color_choose_colors'); ?></button>
		</div>
	</div>
</div>