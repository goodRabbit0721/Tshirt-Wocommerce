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
<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script> <!-- Fixed url file validate.js-->

<?php if ($data['error'] != '') {?>
	<div class="col-md-12"><div class="alert alert-danger"><?php echo $data['error']; ?></div></div>
<?php } ?>

<?php if (!empty($data['msg'])) {?>
	<div class="col-md-12"><div class="alert alert-success"><?php echo $data['msg']; ?></div></div>
<?php } ?>

<form action="<?php echo site_url('index.php/settings/editfont/'.$data['id']); ?>" method="POST" class="fr-edit-fonts form-horizontal" id="panel-form" enctype="multipart/form-data">

	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square icon-external-link-sign"></i>
				<?php if($data['id'] == '')echo lang('fonts_add'); else echo lang('fonts_edit');?>
			</div>
			<div class="modal-body">					
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">
							<?php echo lang('title'); ?><span style="color: #e6674a;">*</span>
						</label>
						<div class="col-sm-6">	
							<input type="text" class="form-control validate required" name="title" data-minlength="2" data-minlength="200" data-msg="<?php echo lang('fonts_edit_title_validate'); ?>" placeholder="<?php echo lang('title'); ?>" value="<?php echo $data['font']['title']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							<?php echo lang('fonts_subtitle'); ?><span style="color: #e6674a;">*</span>
						</label>
						<div class="col-sm-6">	
							<input type="text" class="form-control validate required" name="subtitle" data-minlength="2" data-minlength="200" data-msg="<?php echo lang('fonts_edit_subtitle_validate'); ?>" placeholder="<?php echo lang('fonts_subtitle'); ?>" value="<?php echo $data['font']['subtitle']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							<?php echo lang('fonts_select_woff'); ?> <i class="glyphicon glyphicon-question-sign tooltips" data-original-title="<?php echo lang('fonts_font_suports_woff')?>"></i>
						</label>
						<div class="col-sm-4">	
							<input type="file" id="font-woff" name="font_woff">
						</div>
						<div class="col-sm-2">
							<?php 
								$fonts_name = json_decode($data['font']['filename'], true);
								if(is_array($fonts_name)){
									foreach ($fonts_name as $k=>$v){
										if($k != 'ttf'){
											echo $v;
										}
									}
								}
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							<?php echo lang('fonts_select_ttf'); ?> <i class="glyphicon glyphicon-question-sign tooltips" data-original-title="<?php echo lang('fonts_font_suports_ttf')?>"></i>
						</label>
						<div class="col-sm-4">	
							<input type="file" id="font-ttf" name="font_ttf">
						</div>
						<div class="col-sm-2">
							<?php
								$fonts_name = json_decode($data['font']['filename'], true);
								if(is_array($fonts_name)){
									foreach ($fonts_name as $k=>$v){
										if($k != 'woff'){
											echo $v;
										}
									}
								}
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">
							<?php echo lang('fonts_select_thumb'); ?> <i class="glyphicon glyphicon-question-sign tooltips" data-original-title="<?php echo lang('fonts_font_suports_thumb')?>"></i>
						</label>
						<div class="col-sm-4">	
							<input type="file" id="font-thumb" name="thumb">
						</div>
						<div class="col-sm-2">
							<?php 
								if($data['font']['thumb'] != '')
								{
									echo '<img style="height: 20px;" src="'.str_replace('admin/', '', site_url()).'data/fonts/'.$data['font']['thumb'].'" alt="">';
								}
							?>
						</div>
					</div>
					<div class="col-sm-4"></div>
					<div class="col-sm-8">
						<span class="help-block"><?php echo lang('fonts_convert_font_first'); ?> <a target="_blank" href="http://www.fontsquirrel.com/tools/webfont-generator"><?php echo lang('fonts_click_here'); ?></a> <?php echo lang('fonts_convert_font_last'); ?></span>
					</div>
					<div class="form-group">
						<div class="col-md-10">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary" onclick="return submitFont();"><?php echo lang('save'); ?></button>
								<a class="btn modal-close btn-danger" href="<?php echo site_url('index.php/settings/fonts') ?>"><?php echo lang('close'); ?></a>
							</div>
						</div>
					</div>
				</div>				
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div style="margin-bottom: 0px;" class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square icon-external-link-sign"></i>
				<?php echo lang('categories')?>
				<div class="modal-header"></div>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="col-md-12">
						<p class="text-muted"><small><?php lang('fonts_edit_font_choose_a_category'); ?></small></p>
					</div>
					
					<div class="col-md-9">
						<select class="form-control font-cate_id" id="list-cate-font" name="cate_id">
							<?php 
								foreach($data['categories'] as $key=>$val)
								{
									if($key == $data['font']['cate_id'])
										echo '<option selected="selected" value="'.$key.'" rel="'.$val.'">'.$val.'</option>';
									else	
										echo '<option value="'.$key.'" rel="'.$val.'">'.$val.'</option>';
								}
							?>
						</select>
					</div>
					<div class="col-md-3" style="padding: 5px 0px;">
						<a href="javascript:void(0)" onclick="editCateFont()" class="btn btn-primary btn-xs tooltips" data-toggle="modal" data-target="#modal_edit_cate" data-toggle="tooltip" data-placement="top" title="<?php lang('fonts_edit_edit_cate_tooltip');?>"><i class="fa fa-pencil-square-o"></i></a>
						<a onclick="actionCate('remove')" href="javascript:void(0)" class="btn btn-bricky btn-xs tooltips" data-toggle="tooltip" data-placement="top" title="<?php lang('fonts_edit_remove_cate_tooltip');?>"><i class="fa fa-trash-o"></i></a>
					</div>
					
					<div class="col-md-12">
						<a href="javascript:void(0);" onclick="addCate()" style="float: left;"><?php lang('add_cate')?></a>
					</div>
				</div>
			</div>
		</div>	
	</div>
</form>
<div class="col-md-3 pull-right">
	<div id="add_form"></div>
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
	
	jQuery('.fr-edit-fonts').validate();
	
	function submitFont()
	{
		<?php
			if($data['id'] == '')
			{
		?>
				var woff = jQuery('#font-woff').val().split('.').pop().toLowerCase();
				if(jQuery.inArray(woff, ['woff']) == -1) {
					alert('<?php echo lang('fonts_validate_file_woff_msg'); ?>');
					return false;
				}
				var ttf = jQuery('#font-ttf').val().split('.').pop().toLowerCase();
				if(jQuery.inArray(ttf, ['ttf']) == -1) {
					alert('<?php echo lang('fonts_validate_file_ttf_msg'); ?>');
					return false;
				}
				var image = jQuery('#font-thumb').val().split('.').pop().toLowerCase();
				if(jQuery.inArray(image, ['gif','png','jpg','jpeg']) == -1) {
					alert('<?php echo lang('fonts_validate_file_image_msg'); ?>');
					return false;
				}
				if(woff != '' && ttf != '' && image != '')
				{
					woff = document.getElementById('font-woff').files[0].size/1024;
					ttf = document.getElementById('font-ttf').files[0].size/1024;
					image = document.getElementById('font-thumb').files[0].size/1024;
					if(woff > 2048)
					{
						alert('<?php echo lang('fonts_validate_file_woff_size_msg'); ?>');
						return false;
					}else if(image > 2048)
					{
						alert('<?php echo lang('fonts_validate_file_ttf_size_msg'); ?>');
						return false;
					}else if(image > 2048)
					{
						alert('<?php echo lang('fonts_validate_file_image_size_msg'); ?>');
						return false;
					}else
					{
						return true;
					}
				}
		<?php
			}else
			{
		?>
				var woff = jQuery('#font-woff').val().split('.').pop().toLowerCase();
				if(woff != '')
				{
					if(jQuery.inArray(woff, ['woff']) == -1) {
						alert('<?php echo lang('fonts_validate_file_woff_msg'); ?>');
						return false;
					}
					woff = document.getElementById('font-woff').files[0].size/1024;
				}
				var ttf = jQuery('#font-ttf').val().split('.').pop().toLowerCase();
				if(ttf != '')
				{
					if(jQuery.inArray(ttf, ['ttf']) == -1) {
						alert('<?php echo lang('fonts_validate_file_ttf_msg'); ?>');
						return false;
					}
					ttf = document.getElementById('font-ttf').files[0].size/1024;
				}
				var image = jQuery('#font-thumb').val().split('.').pop().toLowerCase();
				if(image != '')
				{
					if(jQuery.inArray(image, ['gif','png','jpg','jpeg']) == -1) {
						alert('<?php echo lang('fonts_validate_file_image_msg'); ?>');
						return false;
					}
					image = document.getElementById('font-thumb').files[0].size/1024;
				}
				if(woff == '' && ttf == '' && image == '')
					return true;
					
				if(woff != '' && ttf != '' && image != '')
				{
					if(woff > 2048)
					{
						alert('<?php echo lang('fonts_validate_file_woff_size_msg'); ?>');
						return false;
					}else if(image > 2048)
					{
						alert('<?php echo lang('fonts_validate_file_ttf_size_msg'); ?>');
						return false;
					}else if(image > 2048)
					{
						alert('<?php echo lang('fonts_validate_file_image_size_msg'); ?>');
						return false;
					}else
					{
						return true;
					}
				}else
				{
					alert('<?php echo lang('fonts_validate_choose_file_upload_msg'); ?>');
					return false;
				}
		<?php 
			}
		?>
	}

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
		html = html + '<div class="col-md-5"></div>';
		html = html + '<div class="col-md-7">';
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