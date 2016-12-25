<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-22
 *
 * API Theme
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

if ( isset($data['settings']['theme']) && isset($data['settings']['theme']['default']) )
{
	$settings	= $data['settings']['theme']['default'];
}
else
{
	$settings	= array();
}
?>

<style>
.fancybox-inner{max-height: 540px;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/plugins/pickColor/spectrum.css'); ?>">
<script type='text/javascript' src='<?php echo site_url('assets/plugins/pickColor/spectrum.js'); ?>'></script>
<script type="text/javascript" src="<?php echo site_url('assets/plugins/jquery-fancybox/jquery.fancybox.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo site_url('assets/plugins/jquery-fancybox/jquery.fancybox.css'); ?>">
 
<?php if (isset($theme_active)) { ?>
	
	<div class="form-group">
	
	<?php if ( isset( $theme_active['title'] ) ) { ?>
	<strong><?php echo $theme_active['title']; ?></strong>
	<?php } ?>
	
	<?php if ( isset( $theme_active['description'] ) ) { ?>
	<p class="help-block"><?php echo $theme_active['description']; ?></p>
	<?php } ?>
	
	</div>
	
	<!-- General -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="clip-settings"></i> Setting General
			<div class="panel-tools">
				<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>
			</div>
		</div>
		<div class="panel-body">
			
			<div class="row form-horizontal">
			
				<div class="form-group">
					<label class="col-sm-4 control-label">Background Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'general_background', 'FFFFFF'); ?>" name="setting[theme][default][general_background]">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Background Image</label>
					<div class="col-sm-8 background-image">
						<a href="javascript:void(0);" class="btn btn-default btn-sm" onclick="jQuery.fancybox( {height:'400px', href : '<?php echo site_url('index.php/media/modals/backgroundImg/1'); ?>', type: 'iframe'} );">Choose Image</a>
						
						<?php $theme_background = setValue($settings, 'general_image', ''); ?>
						<input type="hidden" class="theme-image" value="<?php echo $theme_background; ?>" name="setting[theme][default][general_image]">
						
						<?php if ($theme_background != '') { ?>
							<img src="<?php echo $theme_background; ?>" class="img-thumbnail" alt="" width="50" />
						<?php } ?>
						<a href="javascript:void(0);" onclick="themRemoveOption(this)"><i class="fa fa-trash-o"></i></a>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Text Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'general_text_color', '333333'); ?>" name="setting[theme][default][general_text_color]">
					</div>
				</div>				
			</div>
			
		</div>
	</div>
	
	<!-- Left Menu -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="clip-settings"></i> Left Menu
			<div class="panel-tools">
				<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>
			</div>
		</div>		
		<div class="panel-body">
			
			<div class="row form-horizontal">
			
				<div class="form-group">
					<label class="col-sm-4 control-label">Background Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'leftmenu_background', 'FFFFFF'); ?>" name="setting[theme][default][leftmenu_background]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Border Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'leftmenu_border', 'CCCCCC'); ?>" name="setting[theme][default][leftmenu_border]">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Text Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'leftmenu_text', '666666'); ?>" name="setting[theme][default][leftmenu_text]">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Text Color Hover</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'leftmenu_texthover', '333333'); ?>" name="setting[theme][default][leftmenu_texthover]">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Icon Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'leftmenu_icon', '666666'); ?>" name="setting[theme][default][leftmenu_icon]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Icon Color Hover</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'leftmenu_iconhover', '333333'); ?>" name="setting[theme][default][leftmenu_iconhover]">
					</div>
				</div>			
			</div>
			
		</div>
	</div>
	
	<!-- Button -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="clip-settings"></i> All Button
			<div class="panel-tools">
				<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>
			</div>
		</div>		
		<div class="panel-body">
			
			<div class="row form-horizontal">
			
				<div class="form-group">
					<label class="col-sm-4 control-label">Background Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'button_background', 'FFFFFF'); ?>" name="setting[theme][default][button_background]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Border Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'button_border', 'CCCCCC'); ?>" name="setting[theme][default][button_border]">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Text Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'button_text', '666666'); ?>" name="setting[theme][default][button_text]">
					</div>
				</div>				
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Icon Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'button_icon', '666666'); ?>" name="setting[theme][default][button_icon]">
					</div>
				</div>		
			</div>
			
		</div>
	</div>
	
	<!-- Accordion Box -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="clip-settings"></i> Accordion Box
			<div class="panel-tools">
				<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>
			</div>
		</div>		
		<div class="panel-body">
			
			<div class="row form-horizontal">
			
				<div class="form-group">
					<label class="col-sm-4 control-label">Background Head Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'box_head', 'FCFCFC'); ?>" name="setting[theme][default][box_head]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Background Content Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'box_content', 'FFFFFF'); ?>" name="setting[theme][default][box_content]">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Border Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'box_border', 'CCCCCC'); ?>" name="setting[theme][default][box_border]">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Text Hear Color</label>
					<div class="col-sm-8">
						<input type="text" class="colors" value="<?php echo setValue($settings, 'box_text', '666666'); ?>" name="setting[theme][default][box_text]">
					</div>
				</div>			
			</div>
			
		</div>
	</div>
<?php } ?>

<script type='text/javascript'>	
function backgroundImg(images)
{
	if(images.length > 0)
	{
		var e = jQuery('.theme-image');
		e.val(images[0]);
		if(e.parent().children('img').length > 0)
			e.parent().children('img').attr('src', images[0]);
		else
			e.parent().append('<img src="'+images[0]+'" class="img-thumbnail" alt="" width="50" />');
		jQuery.fancybox.close();
	}
}
function themRemoveOption(e)
{
	var elm = jQuery(e).parent();
	elm.find('img').remove();
	elm.find('input').val('');
}
jQuery(document).ready(function(){
	jQuery(".colors").spectrum({
		showPalette: true,
		showInput: true,
		preferredFormat: "hex",
		palette: [
			['FFFFFF', 'FCFCFC', 'CCCCCC', '333333'],
			['000000', '428BCA', 'F65E13', '2997AB'],
			['5CB85C', 'D9534F', 'F0AD4E', '5BC0DE'],
			['C3512F', '7C6853', 'F0591A', '2D5C88'],
			['4ECAC2', '435960', '734854', 'A81010'],
		]
	});
});
</script>