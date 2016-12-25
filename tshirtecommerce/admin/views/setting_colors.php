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
<script src="<?php echo site_url('assets/js/jscolor.js'); ?>"></script>
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
		jQuery('.form-color').submit(function() {
			return false;
		});
			
		jQuery('.txt_search').keyup(function(e){
			if(e.keyCode == 13)
			{
				pagination(0);
			}
		});
	});
</script>
<div class="col-md-12">
<form class="form-color" id="panel-form" method="POST" action="<?php echo site_url('index.php/settings/colors'); ?>" onsubmit="return false;" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-sm-2">
					<?php $option = array('5'=>5, '10'=>10, '15'=>15, '20'=>20, '25'=>25, '100'=>100,'all'=>lang('all', true));?>
					<select class="form-control option_colors" name="per_page">
						<?php
							foreach($option as $key=>$val)
							{
								if($key == '10')
									echo '<option value="'.$key.'" selected="">'.$val.'</option>';
								else
									echo '<option value="'.$key.'">'.$val.'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-sm-4">
					<input type="text" name="search_color" value="" class="form-control txt_search" placeholder="<?php lang('color_search'); ?>">
				</div>
				<div class="col-sm-2">
					<button class="btn btn-primary" type="button" onclick="pagination(0)"><?php echo lang('search'); ?></button>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<p style="text-align:right;">
				<a href="https://www.youtube.com/watch?v=UAy0S5MT6u4" class="btn btn-default" target="_blank"><?php lang('video_tutorial'); ?> <i class="fa fa-youtube-play icon-red"></i></a>
				<a class="btn btn-primary tooltips" title="<?php echo lang('add'); ?>" href="javascript:;" onclick="UIModals.init('<?php echo site_url('index.php/settings/editcolor'); ?>')">
					<i class="glyphicon glyphicon-plus"></i>
				</a>
				<a href='javascript:void(0)' class='btn btn-success tooltips' onclick='uploadcsv()' title="<?php echo $addons->__('addon_color_btn_import') ?>">
					<i class="fa fa-upload"></i>
				</a>
				<input class='hidden' type='file' id='btnimport' accept='.csv' name="fileToImport" />
				<a href='<?php echo site_url('index.php/colors/export'); ?>' class='btn btn-info tooltips' title="<?php echo $addons->__('addon_color_btn_export') ?>">
					<i class="fa fa-download"></i>
				</a>
				<a class="btn btn-bricky tooltips" title="<?php echo lang('delete'); ?>" href="javascript:;" onclick="action('removeall', this)"> 
					<i class="fa fa-trash-o"></i>
				</a>
			</p>
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-external-link-square icon-external-link-sign"></i>
			<?php echo lang('colors'); ?>
			<div class="panel-tools">
				<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				<a class="btn btn-xs btn-link panel-refresh" href="#">
					<i class="fa fa-refresh"></i>
				</a>
				<a class="btn btn-xs btn-link panel-expand" href="#">
					<i class="fa fa-expand"></i>
				</a>
				<a class="btn btn-xs btn-link panel-close" href="#">
					<i class="fa fa-times"></i>
				</a>
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
					<th class="center"><?php echo lang('color_name'); ?></th>
					<th class="center"><?php echo lang('hex'); ?></th>
					<th class="center"><?php echo lang('action'); ?></th>
					</tr>
					</thead>
					<tbody>
						<?php if(isset($data['colors'])) {
							$i = 1;
							foreach ($data['colors'] as $key=>$color) { 
								if($i <= 10 ) {
						?>
									<tr>
										<td class="center checkbx">
											<label>
												<input type="checkbox" name="checkb[]" class="checkb" name="check" value="<?php echo $key; ?>">
											</label>
										</td>
										<td><?php echo $color['title']; ?></td>
										<td class="center"><span class="tooltips" style="margin: 5px auto; display: block; height: 25px; width: 50px; background: #<?php echo $color['hex']; ?>; border: 1px solid #CCCCCC;" data-original-title="#<?php echo $color['hex']; ?>"></span></td>
										<td class="center">
											<a href="javascript:;" class="btn btn-teal tooltips" data-original-title="<?php echo lang('edit');?>" onclick="UIModals.init('<?php echo site_url('index.php/settings/editcolor/'.$key);?>')">
												<i class="fa fa-edit"></i>
											</a>
											<a rel="del" class="btn btn-bricky tooltips" data-original-title="<?php echo lang('remove');?>" href="javascript:;" onclick="action('remove', this)">
											<i class="fa fa-times"></i></a>
										</td>
									</tr>
						<?php 	} 
								$i++; 
							} 
						} ?>    
					</tbody>
				</table>
				<div class="row">
					<div class="dataTables_paginate paging_bootstrap" style="float: right;">
						<div class="col-md-12">
							<?php
								if(count($data['colors']) > 10)
								{
									$count = count($data['colors'])/10;
									if($count > (int)$count)
										$count = (int)$count + 1;
									if($count > 5)
									{
										$pageall = true;
										$count = 5;
									}else
									{
										$pageall = false;
									}
									echo '<ul class="pagination">';
										for($i=1; $i<=$count; $i++)
										{
											if($i == 1)
												echo '<li class="active"><a href="javascript:void(0);">'.$i.'</a></li>';
											else
												echo '<li><a href="javascript:void(0);" onclick="pagination('.(($i-1)*10).')">'.$i.'</a></li>';
										}
									echo '<li>
											<a href="javascript:void(0);" aria-label="'.lang('next', true).'" onclick="pagination(10)">
												<span aria-hidden="true">&raquo;</span>
											</a>
										</li>';
									if($pageall)
										echo '<li><a href="javascript:void(0);" onclick="pagination('.(count($data['colors'])-10).')"><span aria-hidden="true">&raquo;</span></a></li>';
									echo '</ul>';
								}
							?>
						</div>
				   </div>
				</div>
			</div>
		</div>
	</div> 
</form>
</div>

<div id="ajax-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"></div>
<!-- end: PAGE CONTENT-->
<script type="text/javascript">
	jQuery('.option_colors').change(function(){
		pagination(0);
	});
	function uploadcsv()
	{
		jQuery('#btnimport').trigger('click');
	}
	jQuery('#btnimport').change(function() {
		//alert(jQuery(this).val()); 
		var formData = new FormData(jQuery('form')[0]);
		jQuery.ajax({
			type: "POST",
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			url: '<?php echo site_url('index.php/colors/import'); ?>',
			data: formData,
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
				pagination(0);
				jQuery('#panel-form,.modal-body').unblock();
			},
		});
		
	});
	function pagination(segment)
	{
		jQuery.ajax({
			type: "POST",
			url: '<?php echo site_url('index.php/settings/pagecolor/'); ?>'+segment,
			data: jQuery('.form-color').serialize(),
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
				{
					jQuery('#refresh').html(data);
				}
				jQuery('#panel-form,.modal-body').unblock();
			},
		});
	}
	
	function editColor()
	{
		var check = jQuery('#fr-color').validate({event: 'click'});
		
		if(check)
		{
			var url = jQuery('#fr-color').attr('action');
			jQuery('.modal-close').trigger( "click" );
			jQuery.ajax({
				type: "POST",
				url: url,
				data: jQuery('#fr-color').serialize(),
				dataType: 'html',
				beforeSend: function(){
					jQuery('#panel-form,.modal-body').block({
						overlayCSS: {
							backgroundColor: '#fff'
						},
						message: '<img src="<?php echo site_url().'assets/images/loading.gif'?>" /> <?php echo lang('loading') ?>',
						css: {
							border: 'none',
							color: '#333',
							background: 'none'
						}
					});
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
			var url = '<?php echo site_url('index.php/settings/removecolor'); ?>';
			check = confirm('<?php lang('colors_delete_font_confirm'); ?>');
		}
		if(type == 'remove' && check)
			jQuery(e).parent('td').parent('tr').children('.checkbx').children('label').children('.checkb').prop( "checked", true );
		
		if(check)
		{
			jQuery.ajax({
				type: "POST",
				url: url,
				data: jQuery('.form-color').serialize(),
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
					{
						jQuery('#refresh').html(data);
					}
					jQuery('#panel-form,.modal-body').unblock();
				},
			});
		}
	}
</script>