<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT')) exit('No direct script access allowed');

?>
<script src="<?php echo site_url('assets/js/dg-function.js'); ?>"></script>
<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
<div class="row">
	<div class="col-md-5 pull-right text-right">
		<button type="button" class="btn btn-primary" data-loading-text="<?php lang('loading');?>"  autocomplete="off" onclick="dgUI.product.fonts.save(this)"><?php lang('save')?></button>
		<div class="alert alert-success" role="alert" style="padding: 10px 12px; display: none;"><?php lang('saved')?></div>
		<a href="<?php echo site_url('index.php/settings/fonts'); ?>" class="btn btn-danger" ><?php lang('cancel'); ?></a>
	</div>
</div>

<hr />

<div id="ajax-modal" class="panel panel-default">
    <div class="panel-heading">
		<i class="fa fa-external-link-square icon-external-link-sign"></i>
		<?php lang('fonts_system'); ?>
		<div class="panel-tools">
			<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>			
		</div>
	</div>
	<div class="modal-body">
		<h4><?php lang('fonts_choose_system_font');?></h4>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label><?php lang('fonts_edit_script');?></label>
					<select class="form-control fonts-categories" onchange="dgUI.product.fonts.ajax(0)">
					
					<?php foreach($data['google'] as $key => $value) { ?>
						<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
					<?php } ?>
					
					</select>
				</div>
				
				<div class="form-group">
					<label><?php lang('fonts_edit_find_font');?> <strong id="fonts-counts"><?php echo count($data['google']['latin']); ?></strong> <?php lang('fonts_edit_font_show');?></label>
					<input type="text" class="form-control input-sm" onkeyup="dgUI.product.color.find('key', this)">
				</div>
			</div>				
			<div class="col-md-6">					
				<div class="form-group">
					<label><strong><?php lang('fonts_edit_font_added');?></strong></label>
					<p class="text-muted"><small><?php lang('fonts_edit_font_click_on_each');?></small></p>					
					<ul class="fonts" id="list-font-add"></ul>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label><strong><?php lang('fonts_edit_font_list_categories'); ?></strong></label>
					<p class="text-muted"><small><?php lang('fonts_edit_font_choose_a_category'); ?></small></p>
					
					<div class="row">
						<div class="col-md-9">
							<select class="form-control font-cate_id" id="list-cate-font">
								<?php 
									foreach($data['categories'] as $key=>$val)
									{
										echo '<option value="'.$key.'" rel="'.$val.'">'.$val.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-3" style="padding: 5px 0px;">
							<a href="javascript:void(0)" onclick="editCateFont()" class="btn btn-primary btn-xs tooltips" data-toggle="modal" data-target="#modal_edit_cate" data-toggle="tooltip" data-placement="top" title="<?php lang('fonts_edit_edit_cate_tooltip');?>"><i class="fa fa-pencil-square-o"></i></a>
							<a onclick="actionCate('remove')" href="javascript:void(0)" class="btn btn-bricky btn-xs tooltips" data-toggle="tooltip" data-placement="top" title="<?php lang('fonts_edit_remove_cate_tooltip');?>"><i class="fa fa-trash-o"></i></a>
						</div>
					</div>
					<div class="row col-md-12">
						<a href="javascript:void(0);" onclick="addCate()" style="float: left;"><?php lang('add_cate')?></a>
					</div>
				</div>
				
				<div class="form-group">
					<div id="add_form"></div>
				</div>
			</div>
		</div>
		
		<hr />
		
		<div class="row">
			<div class="col-md-12">				
				<ul class="colors" id="list-fonts"></ul>					
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12 text-center">
				<br />
				<button type="button" class="btn btn-primary" onclick="dgUI.product.fonts.load()"><?php lang('load_more'); ?></button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_edit_cate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo site_url('index.php/settings/catefont'); ?>" method="post" id="fr-edit-cate">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php lang('fonts_edit_edit_cate_title');?></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-4"><?php lang('fonts_edit_form_cate_title');?></label>
						<div class="col-sm-8">
							<input id="edit-cate-title" type="text" class="form-control input-sm validate required" data-msg="<?php lang('fonts_edit_edit_cate_validate');?>" data-maxlength="50" data-minlength="2" name="catename"/>
						</div>
					</div>
					<input id="edit-cate-font" type="hidden" name="id" value=""/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="actionCate('edit')"><?php lang('save');?></button>
					<button type="button" class="btn btn-danger modal-close" data-dismiss="modal"><?php lang('close');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div style="display: none;">
	<form action="<?php echo site_url('index.php/settings/delcatefont'); ?>" method="post" id="fr-del-cate">
		<input id="del-cate-font" type="hidden" name="id" value=""/>
	</form>
</div>

<script type="text/javascript">
var base_url 	= '<?php echo str_replace('admin/', '', site_url()); ?>';
var fonts 	= [];
var fonts_added 	= '<?php echo $data['fonts']; ?>';
var page 	= 0;
jQuery(document).ready(function() {
	dgUI.product.fonts.ajax(0);
});

	function addCate() {
		var html = '';
		html = html + '<div id="tab-content-lang" class="tab-content form-horizontal">';
		html = html + '<span class="help-block"><i class="glyphicon glyphicon-info-sign"></i> <?php lang('fonts_choose_category');?></span>';
		html = html + '<form method="post" action="<?php echo site_url('index.php/settings/catefont')?>" id="form-add" class="form-add">';
		html = html + '<div id="title">';
		html = html + '<div class="form-group">';
		html = html + '<div class="col-md-12">';
		html = html + '<input type="text" name="catename" id="fonts_title" data-maxlength="50" data-minlength="2" data-msg="<?php lang('fonts_edit_edit_cate_validate')?>" class="form-control validate category_title" placeholder="<?php lang('fonts_edit_form_cate_title')?>" />';
		html = html + '</div>';
		html = html + '</div>';
		html = html + '</div>';
		
		html = html + '<div class="form-group">';
		html = html + '<div class="col-md-4"></div>';
		html = html + '<div class="col-md-8 pull-right">';
		html = html + '<a class="btn btn-default modal-close" onclick="closecate()"><?php lang('close');?></a>';
		html = html + '<button type="button" class="btn btn-primary pull-right" onclick="actionCate(\'add\')"><?php lang('save');?></button>';
		html = html + '</div>';
		html = html + '</div>';
		html = html + '</form>';
		
		html = html + '</div>';
		document.getElementById('add_form').innerHTML = html;
	}
	
	function editCateFont()
	{
		var cate = jQuery('#list-cate-font').val();
		var text = jQuery('#list-cate-font option:selected').text();
		jQuery('#edit-cate-font').val(cate);
		jQuery('#edit-cate-title').val(text);
	}
	
	function closecate() {
        document.getElementById('add_form').innerHTML = '';
    }
	
	function actionCate(type) 
	{
		if(type == 'edit')
		{
			var check = jQuery('#fr-edit-cate').validate({event: 'click'});
			var data = jQuery('#fr-edit-cate').serialize();
			var url = '<?php echo site_url('index.php/settings/catefont'); ?>';
		}else if(type == 'add')
		{
			var check = jQuery('#form-add').validate({event: 'click'});
			var data = jQuery('#form-add').serialize();
			var url = '<?php echo site_url('index.php/settings/catefont'); ?>';
		}else
		{
			var cate_id = jQuery('#list-cate-font').val();
			jQuery('#del-cate-font').val(cate_id);
			var check = confirm('<?php lang('fonts_delete_cate_confirm')?>');
			var data = jQuery('#fr-del-cate').serialize();
			var url = '<?php echo site_url('index.php/settings/delcatefont'); ?>';
		}
			
		if(check)
		{
			jQuery('.modal-close').trigger( "click" );
			jQuery.ajax({
				type: "POST",
				url: url,
				data: data,
				dataType: 'html',
				beforeSend: function(){
					jQuery('#panel-form,.modal-body').block({
						overlayCSS: {
							backgroundColor: '#fff'
						},
						message: '<img src="<?php echo site_url().'assets/images/loading.gif'?>" /> <?php lang('loading') ?>',
						css: {
							border: 'none',
							color: '#333',
							background: 'none'
						}
					});
				},
				success: function(data){
					if(data != '')
						jQuery('#list-cate-font').html(data);
					else
						alert('<?php lang('colors_update_cate_font_error_msg'); ?>');
					jQuery('#panel-form,.modal-body').unblock();
				},
			});
		}
    }
</script>