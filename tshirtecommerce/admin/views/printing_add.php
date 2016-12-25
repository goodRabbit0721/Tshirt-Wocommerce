<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

if ( ! defined('ROOT') ) exit('No direct script access allowed');

$id = 0;
$title = '';
$printing_code = '';
$price_type = '';
$short_description = '';
$description = '';
$view = 0;
$price_extra = 0;
$values = new stdclass();
//$enable = false;
$msg = '';

$printing_types = array();
if( isset( $data['types'] ) && count($data['types']) > 0)
{
	$printing_types = $data['types'];
	$price_type = $printing_types[0]->id;
}
if ( isset( $data['printing'] ) )
{
	$printing = $data['printing'];
	
	$id = $printing->id;
	$title = $printing->title;
	$printing_code = $printing->printing_code;
	$price_type = $printing->price_type;
	if ( property_exists( $printing, 'short_description' ) ) $short_description = $printing->short_description;
	$description = $printing->description;
	if ( property_exists( $printing, 'view' ) ) $view = $printing->view;
	if ( property_exists( $printing, 'values' ) ) $values = $printing->values;
	//if ( property_exists ( $printing, 'enable' ) ) $enable = $printing->enable;
	if ( property_exists( $printing, 'price_extra' ) ) $price_extra = $printing->price_extra;
}

if(isset($data['msg'])) $msg = $data['msg'];

?>
<script src="<?php echo site_url('assets/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
tinymce.init({
	selector: ".text-edittor",
	menubar: false,
	toolbar_items_size: 'small',
	statusbar: false,
	height : 150,
	convert_urls: false,
	plugins: [
		"advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code fullscreen",
		"insertdatetime table contextmenu paste"
	],
	toolbar: "code | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | dgmedia"
});
</script>
<form id="printing-form" method="post" name="printingForm" action="<?php echo site_url('index.php/printing/update'); ?>">
<?php if (isset($msg) && $msg !== '') { ?>
<div class="row">
	<div class="col-md-12">
		<div class=" alert alert-<?php if($msg == '0') echo 'warning'; else echo 'success'; ?> alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<?php 
				if($msg == '0') echo $addons->lang['addon_printing_message_update_fail']; 
				if($msg == '1') echo $addons->lang['addon_printing_message_update_success']
			?>
		</div>
	</div>
</div>
<?php } ?>
<div class='row' style='margin-right:0!important'>
	<p class='pull-right'>
		<a href='javascript:void(0)' id='printing-submit' class='btn btn-primary'>
			<?php echo $addons->lang['addon_printing_button_save'] ?>
		</a>
		<a href='<?php echo site_url('index.php/printing'); ?>' class='btn btn-danger'>
			<?php echo $addons->lang['addon_printing_button_cancel'] ?>
		</a>
	</p>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<i class='fa fa-external-link-square icon-external-link-sign'></i>
		<?php if ( $id == 0 ) : ?>
		<div class='col-sm-12'><strong><?php echo $addons->lang['addon_printing_type_title_add'] ?></strong></div>
		<?php else : ?>
		<div class='col-sm-12'><strong><?php echo $addons->lang['addon_printing_type_title_update'] ?><strong></div>
		<?php endif; ?>
	</div>
	<div class="modal-body">
		<div class="panel-body">
			<div class='row col-sm-12'>
				<input class='hidden' type='text' name='printing_id' value='<?php echo $id; ?>' />
				<div class='form-group'>
					<label class='control-label'><strong><?php echo $addons->lang['addon_printing_label_title'] ?></strong></label>
					<input type='text' id='printing-title' name='printing_title' class='form-control' value='<?php echo $title; ?>' />
					<label style='text-align:left;' class='control-label'>
						<span id='printing-title-help' class='printing-title hidden' style='color:#f00'><?php echo $addons->lang['addon_printing_label_printing_title_help'] ?></span>
					</label>
				</div>
				<div class='form-group'>
					<label class='control-label'><strong><?php echo $addons->lang['addon_printing_label_printing_code'] ?></strong></label>
					<input type='text' id='printing-code' maxlength='5' name='printing_code' class='form-control printing-code' value='<?php echo $printing_code; ?>' />
					<label style='text-align:left;' class='control-label'>
						<span class='help-block printing-code-help'><?php echo $addons->lang['addon_printing_label_printing_code_help'] ?></span>
					</label>
				</div>
				<div class='form-group'>
					<label class='control-label'><strong><?php echo $addons->lang['addon_printing_label_short_description'] ?></strong></label>
					<textarea class="form-control" name='printing_short_description' rows="3"><?php echo trim($short_description); ?></textarea>
				</div>
				<div class='form-group'>
					<label class='control-label'><strong><?php echo $addons->lang['addon_printing_label_description'] ?></strong></label>
					<textarea class="form-control text-edittor" name='printing_description' rows="3"><?php echo trim($description); ?></textarea>
					
					<p class="help-block"><?php echo $addons->lang['addon_printing_des_description'] ?></p>
				</div>
				
				<hr />
				
				<?php if (count($printing_types) > 0) : ?>
				<label class='control-label'><strong><?php echo $addons->lang['addon_printing_label_price_type'] ?></strong></label>
				<p class="text-muted"><?php echo $addons->lang['addon_printing_label_price_type_des'] ?></p>
				<br />
				<?php foreach($printing_types as $type) : ?>
					<div class='form-group'>
						<div class='radio'>
							<label>
								<input name='price_type' type='radio' onchange='printings_change_price_type(this)'
									value='<?php echo $type->id ?>' <?php if($price_type == $type->id) echo 'checked'; ?> />
									<?php echo $type->title ?>
							</label>
							<p class="help-block price-type-help" style="font-weight:normal!important"><?php echo $type->description ?></p>
						</div>
					</div>
				<?php endforeach;?>
				

				<div class='form-group'>
					<label class='control-label'><strong><?php echo $addons->lang['addon_printing_type_title_enable_views'] ?></strong></label>
					<select name='printings_view' class='form-control' onchange='printings_view_change(this)'>
						<option value='0' <?php if ( $view == 0 ) echo 'selected'; ?> >
							<?php echo $addons->lang['addon_printing_type_title_no'] ?>
						</option>
						<option value='1' <?php if ( $view == 1 ) echo 'selected'; ?> >
							<?php echo $addons->lang['addon_printing_type_title_yes'] ?>
						</option>
					</select>
				</div>
				<?php endif; ?>
				<div class='form-group'>
					<label class='control-label'>
						<strong><?php echo $addons->lang['addon_printing_label_extra_price'] ?></strong>
					</label>
					<input name='price_extra' value='<?php echo $price_extra ?>' class='form-control price-extra' type='text' 
						onkeypress='return printing_validate_extra(event, this)' onblur='printings_check_blank(this)' />
					<span class='help-block'><?php echo $addons->lang['addon_printing_label_price_extra_help'] ?></span>
				</div>
				<div id='div-printings-load-view'>
				<?php
					// sort $printing_types
					if (count($data['types']) > 0 && count( $values ) > 0 )
					{
						foreach ( $printing_types as $type)
						{
							$file = dirname( ROOT ) . DS . 'addons' . DS . 'printings' . DS . 'view' . DS . $type->id . '.php';
							if ( file_exists( $file ) ) include( $file );
						}
					}
				?>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<script>
var printing_form_submit = true;
jQuery('input.printing-code').keyup( function() 
{	
	var name = jQuery( this ).val();
	var rep_space = jQuery( this ).val().replace(/ /g, "");
	var rep_special_char = rep_space.replace(/[^a-zA-Z 0-9]+/g, "");
	jQuery( this ).val( rep_special_char );
	
	var code = jQuery( this ).val().toUpperCase();
	if ( code.length < 3 ) 
	{
		jQuery('.printing-code-help').css('color', '#f00');
	}
	else 
	{
		jQuery('.printing-code-help').css('color', '#737373');
	}
	jQuery( this ).val(code);
} );

jQuery('#printing-submit').click( function() 
{
	var title = jQuery("#printing-title").val();
	var code = jQuery("#printing-code").val();
	if( title.length == 0 ) 
	{
		jQuery('#printing-title-help').removeClass('hidden');
		jQuery('#printing-title').focus();
		printing_form_submit = false;
	} else jQuery('#printing-code').focus();
	if ( code.length < 3 || code.length > 5) 
	{
		jQuery('.printing-code-help').css('color', '#f00');
		printing_form_submit = false;
		//jQuery('#printing-code').focus();
		//jQuery('#printing-title-help').addClass('hidden');
	}
	
	if ( printing_form_submit === true )
	{
		jQuery("#printing-form").submit();
		jQuery('.printing-code-help').css('color', '#737373');
		jQuery('#printing-title-help').addClass('hidden');
	}
});

jQuery("#printing-title").blur( function() {
	var title = jQuery(this).val();
	if( title.length == 0 ) 
	{
		jQuery('#printing-title-help').removeClass('hidden');
		//jQuery(this).focus();
	} 
	else
	{
		jQuery('#printing-title-help').addClass('hidden');
		
	}
});
jQuery("#printing-code").blur( function() {
	var code = jQuery(this).val();
	if(code.length < 3 || code.length > 5)
	{
		jQuery('.printing-code-help').css('color', '#f00');
		//jQuery(this).focus();
	}
	else
	{
		jQuery('.printing-code-help').css('color', '#737373');
		
		jQuery.ajax({
			type: "POST",
			url: '<?php echo site_url('index.php/printing/validate'); ?>',
			data: jQuery('#printing-form').serialize(),
			dataType: 'html',
			success: function(data){
				if ( data == 1 ) {
					printing_form_submit = false;
					jQuery('.printing-code-help').css('color', '#f00');
					jQuery('.printing-code-help').text('<?php echo $addons->lang['addon_printing_label_printing_code_validate'] ?>');
					jQuery('#printing-code').focus();
				} else {
					printing_form_submit = true;
					jQuery('.printing-code-help').css('color', '#737373');
					jQuery('.printing-code-help').text('<?php echo $addons->lang['addon_printing_label_printing_code_help'] ?>');
				}
			},
		});
	}
});
</script>