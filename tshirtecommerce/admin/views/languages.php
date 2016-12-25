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
<!-- start: PAGE CONTENT -->

<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/ui-modals.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script>
<script type="text/javascript">
	jQuery(document).on('click change','input[name="check_all"]',function() {
		var checkboxes = $(this).closest('table').find(':checkbox').not($(this));
		if($(this).prop('checked')) {
		  checkboxes.prop('checked', true);
		} else {
		  checkboxes.prop('checked', false);
		}
	});
	jQuery(document).ready(function(){
		jQuery('.form-language').submit(function() {
			return false;
		});
	});
</script>
<div class="col-md-12">
<form class="form-languages" id="panel-form" method="POST" action="<?php echo site_url('index.php/settings/languages'); ?>" onsubmit="return false;">
	<div class="row">
		<div class="col-md-12">
			<p style="text-align:right;">
				<a class="btn btn-primary tooltips" title="<?php echo lang('add'); ?>" href="javascript:;" onclick="UIModals.init('<?php echo site_url('index.php/settings/editlanguage'); ?>')">
					<i class="glyphicon glyphicon-plus"></i>
				</a>				
			</p>
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-external-link-square icon-external-link-sign"></i>
			<?php echo lang('languages'); ?>
			<div class="panel-tools">
				<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>				
			</div>
		</div>

		<div class="panel-body" id="panelbody">
			<div id="refresh">
				<table id="sample-table-1" class="table table-bordered table-hover">
					<thead>
					<tr>
					<th class="center">
						<label>
							<input id="select_all" type="checkbox" name='check_all'>
						</label>
					</th>
					<th class="center"><?php echo lang('languages_name'); ?></th>
					<th class="center"><?php echo lang('languages_code'); ?></th>
					<th class="center"><?php echo lang('languages_file_name'); ?></th>
					<th class="center"><?php echo lang('languages_default'); ?></th>
					<th class="center"><?php echo lang('published'); ?></th>
					<th class="center"><?php echo lang('action'); ?></th>
					</tr>
					</thead>
					<tbody>
						<?php if(isset($data['languages'])) foreach ($data['languages'] as $key=>$language) { ?>
							<tr>
								<td class="center checkbx">
									<label>
										<input type="checkbox" name="checkb[]" class="checkb" name="check" value="<?php echo $key; ?>">
									</label>
								</td>
								<td><?php echo $language['title']; ?></td>
								<td class="center"><?php echo $language['code']; ?></td>
								
								<td class="center">
									<a href="<?php echo site_url('data/'.$language['file'], false); ?>" target="_blank" title="Click to download file">
										<?php echo $language['file']; ?>
									</a>
								</td>
								
								<td class="center">
									<?php if($language['default'] == 1){ ?>
										<a href="javascript:void(0);"><i style="font-size: 20px;" class="fa fa-check-square-o"></i></a>
									<?php }else{ ?>
										<a href="javascript:void(0);" onclick="editlanguage('default', <?php echo $key; ?>);"><i style="font-size: 20px;" class="fa fa-square-o"></i></a>
									<?php } ?>
								</td>
								
								<td class="center">
									<?php if(isset($language['published']) && $language['published'] == 0){ ?>
										<a href="<?php echo site_url('index.php/settings/publishLanguage/'.$language['code'].'/1'); ?>" class="btn btn-bricky btn-xs"><?php echo lang('unpublish'); ?></a>
									<?php }else{ ?>
										<a href="<?php echo site_url('index.php/settings/publishLanguage/'.$language['code'].'/0'); ?>" class="btn btn-success btn-xs"><?php echo lang('publish'); ?></a>
									<?php } ?>
								</td>
								
								<td class="center">
									<a href="javascript:;" class="btn btn-teal tooltips" data-original-title="<?php echo lang('edit');?>" onclick="UIModals.init('<?php echo site_url('index.php/settings/editlanguage/'.$key);?>')">
										<i class="fa fa-edit"></i>
									</a>									
								</td>
							</tr>
						<?php } ?>    
					</tbody>
				</table>
			</div>
		</div>
	</div> 
</form>
</div>

<div id="ajax-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"></div>
<!-- end: PAGE CONTENT-->
<script type="text/javascript">
	function editlanguage(type, id)
	{
		if(type == 'edit')
		{
			var check = jQuery('#fr-language').validate({event: 'click'});
			
			var code = jQuery('.languages_code').val();
			var filter = /^[a-z]+$/;
			if (filter.test(code) == false)
			{
				alert('<?php echo lang('languages_validate_lang_code'); ?>');
				return false;
			}			
			
		}
		else
		{
			var check = true;
		}
		
		if(check)
		{
			if(type == 'edit')
				var url = jQuery('#fr-language').attr('action');
			else
				var url = '<?php echo site_url('index.php/settings/languagedefault');?>/'+id;
			jQuery('.modal-close').trigger( "click" );
			jQuery.ajax({
				type: "POST",
				url: url,
				data: jQuery('#fr-language').serialize(),
				dataType: 'html',
				beforeSend: function(){
					jQuery('#panel-form,.modal-body').block({
						overlayCSS: {
							backgroundlanguage: '#fff'
						},
						message: '<img src="<?php echo site_url().'assets/images/loading.gif'?>" /> <?php echo lang('loading') ?>',
						css: {
							border: 'none',
							language: '#333',
							background: 'none'
						}
					});
					jQuery('.blockUI').css('background-color', 'transparent');
				},
				success: function(data){
					if(data != '')
					{
						jQuery('#refresh').html(data);
					}
					jQuery('#panel-form,.modal-body').unblock();
				},
			});
		}
	}
	
	function action(type, e)
	{	
		var check = true;
		if(type == 'remove' || type == 'removeall')
		{
			var url = '<?php echo site_url('index.php/settings/removelanguage'); ?>';
			check = confirm('<?php lang('languages_delete_font_confirm'); ?>');
		}
		if(type == 'remove' && check)
			jQuery(e).parent('td').parent('tr').children('.checkbx').children('label').children('.checkb').prop( "checked", true );
		
		if(check)
		{
			jQuery.ajax({
				type: "POST",
				url: url,
				data: jQuery('.form-languages').serialize(),
				dataType: 'html',
				beforeSend: function(){
					jQuery('#panel-form,.modal-body').block({
						overlayCSS: {
							backgroundlanguage: '#fff'
						},
						message: '<img src="<?php echo site_url().'assets/images/loading.gif'?>" /> <?php lang('loading') ?>',
						css: {
							border: 'none',
							language: '#333',
							background: 'none'
						}
					});
					jQuery('.blockUI').css('background-color', 'transparent');
				},
				success: function(data){
					if(data != '')
					{
						jQuery('#refresh').html(data);
					}
					jQuery('#panel-form,.modal-body').unblock();
				},
			});
		}
	}
</script>