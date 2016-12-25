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

$css = '';
?>

<!-- start: PAGE CONTENT -->					

<script type="text/javascript">
	var loadingImage = '<?php echo base_url('assets/images/loading.gif'); ?>'; 
	var closeButton = '<?php echo base_url('assets/images/close.gif'); ?>';
	var url = '<?php echo site_url(); ?>';
</script>
<div class="col-md-12">
	<form class="fr-fonts" id="panel-form" action="<?php echo site_url('index.php/settings/fonts'); ?>" method="POST">
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-sm-2">
					<?php $option = array('5'=>5, '10'=>10, '15'=>15, '20'=>20, '25'=>25, '100'=>100,'all'=>lang('all', true));?>
					<select class="form-control option_fonts" name="per_page">
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
					<input type="text" name="search_font" class="form-control txt_search" placeholder="<?php lang('fonts_search'); ?>">
				</div>
				<div class="col-sm-4">
					<select class="form-control option_fonts" name="option_font">
						<option value=""><?php echo lang('all', true); ?></option>
						<?php	
							foreach($data['cates'] as $key=>$val)
							{
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-sm-2">
					<button type="button" class="btn btn-primary btn-search" onclick="pagination(0)"><?php lang('search'); ?></button>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<p style="text-align:right;">
				<a href="https://www.youtube.com/watch?v=CeYebHfUh5U" target="_blank" class="btn btn-default"><?php lang('video_tutorial'); ?> <i class="fa fa-youtube-play icon-red"></i></a>
				<a class="btn btn-primary tooltips" href="<?php echo site_url('index.php/settings/addgooglefont')?>" data-placement="top" data-original-title="<?php lang('fonts_add_google'); ?>">
					<i class="glyphicon glyphicon-plus"></i> <?php echo lang('fonts_add_google_font'); ?>
				</a>
				<a class="btn btn-primary tooltips" href="<?php echo site_url('index.php/settings/editfont')?>" data-placement="top" data-original-title="<?php lang('fonts_add_new'); ?>">
					<i class="glyphicon glyphicon-plus"></i>
				</a>
				<a class="btn btn-green tooltips" href="javascript:void(0);" data-original-title="<?php lang('publish');?>" onclick="action('publishall', this)">
					<i class="glyphicon glyphicon-ok-sign"></i>
				</a>
				<a class="btn btn-danger tooltips" href="javascript:void(0);" data-original-title="<?php lang('unpublish');?>" onclick="action('unpublishall', this)">
					<i class="clip-radio-checked"></i>
				</a>
				<a class="btn btn-bricky tooltips" href="javascript:void(0);" data-original-title="<?php lang('delete');?>" onclick="action('removeall', this)"> 
					<i class="fa fa-trash-o"></i>
				</a>
			</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-external-link-square icon-external-link-sign"></i>
			<?php lang('fonts'); ?>
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
		<div class="panel-body modal-body">
			<div id="refresh">
				<table id="sample-table-1" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="center">
								<label>
									<input id="select_all" type="checkbox" name='check_all'>
								</label>
							</th>
							<th class="center"><?php lang('fonts_name'); ?></th>
							<th class="center"><?php lang('file_name'); ?></th>
							<th class="center"><?php lang('thumb'); ?></th>
							<th class="center"><?php lang('categories'); ?></th>
							<th class="center"><?php lang('published'); ?></th>
							<th class="center"><?php lang('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 0; if (isset($data['fonts'])) foreach ($data['fonts'] as $key=>$val) { ?>
							<?php 
								if($i < 10){
							?>
								<tr>
									<td class="center checkbx">
										<label>
											<input type="checkbox" name="checkb[]" class="checkb" name="check" value="<?php echo $key; ?>">
										</label>
									</td>
									<td><?php echo $val['title']; ?></td>
									<td class="center">
										<?php
											if($val['type'] == 'google')
											{
												if ($css == '')
												{
													$css = str_replace(' ', '+', $val['title']);
												}
												else
												{
													$css = $css .'|'. str_replace(' ', '+', $val['title']);
												}
												echo 'Google font';
											}else
											{
												$filename = json_decode($val['filename']);
												if(count($filename))
												{
													$file_name = '';
													$i = 0;
													foreach($filename as $v)
													{
														if($i == 0)
															$file_name .= $v.'/';
														else
															$file_name .= $v;
														$i++;
													}
													echo $file_name;
												}
											}
										?>
									</td>
									<td class="center">
										<?php 
											if($val['type'] == 'google'){
										?>
											<span style="font-family:'<?php echo $val['title']; ?>'"><?php echo $val['title']; ?></span>
										<?php 
											}else
											{
										?>
											<img src="<?php echo str_replace('admin/', '', site_url()).'data/fonts/'.$val['thumb']; ?>" alt="">
										<?php } ?>
									</td>
									<td class="center"><?php echo $val['catename']; ?></td>
									<td class="center"><?php if ($val['published'] == 1) { ?>					   
											<a class="btn btn-success btn-xs tooltips" data-original-title="<?php lang('click_unpublish');?>" onclick="action('unpublish', this)"><?php lang('publish'); ?></a>
										<?php } else { ?>
											<a class="btn btn-danger btn-xs tooltips" data-original-title="<?php lang('click_publish');?>" onclick="action('publish', this)"><?php lang('unpublish'); ?></a>
										<?php } ?>
									</td>
									<td class="center">
										<?php
											if($val['type'] == '')
											{
										?>
												<a href="<?php echo site_url('index.php/settings/editfont/'.$val['id']); ?>" class="btn btn-primary tooltips" data-original-title="<?php echo lang('edit');?>" data-placement="top">
													<i class="fa fa-edit"></i>
												</a>
										<?php
											}
										?>
										<a class="btn btn-bricky tooltips" data-placement="top" data-original-title="<?php lang('remove');?>" href="javascript:void(0);" onclick="action('remove', this)">
											<i class="fa fa-times"></i>
										</a>
									</td>
								</tr>
							<?php } ?>    
							<?php $i++; } ?>    
					</tbody>
				</table>
				<link href='https://fonts.googleapis.com/css?family=<?php echo $css; ?>' rel='stylesheet' type='text/css'>
				<div class="row">
					<div class="dataTables_paginate paging_bootstrap" style="float: right;">
						<div class="col-md-12">
							<?php
								if(count($data['fonts']) > 10)
								{
									$count = count($data['fonts'])/10;
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
										echo '<li><a href="javascript:void(0);" onclick="pagination('.(count($data['fonts'])-10).')"><span aria-hidden="true">&raquo;</span></a></li>';
									echo '</ul>';
								}
							?>
						</div>
				   </div>
				</div>
			</div>
			</form>
		</div>     
	</div>           
</div>

<!-- end: PAGE CONTENT-->
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.fr-fonts').submit(function() {
			return false;
		});
			
		jQuery('.txt_search').keyup(function(e){
			if(e.keyCode == 13)
			{
				pagination(0);
			}
		});
	});
	
	jQuery('.option_fonts').change(function(){
		pagination(0);
	});
	function pagination(segment)
	{
		jQuery.ajax({
			type: "POST",
			url: '<?php echo site_url('index.php/settings/pagefont/'); ?>'+segment,
			data: jQuery('.fr-fonts').serialize(),
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
	
	jQuery(document).on('click change','input[name="check_all"]',function() {
		var checkboxes = $(this).closest('table').find(':checkbox').not($(this));
		if($(this).prop('checked')) {
		  checkboxes.prop('checked', true);
		} else {
		  checkboxes.prop('checked', false);
		}
	});
	
	function action(type, e)
	{	
		var check = true;
		if(type == 'publish' || type == 'publishall')
		{
			var url = '<?php echo site_url('index.php/settings/publish'); ?>';
		}else if(type == 'unpublish' || type == 'unpublishall')
		{
			var url = '<?php echo site_url('index.php/settings/unpublish'); ?>';
		}else
		{
			var url = '<?php echo site_url('index.php/settings/removefont'); ?>';
			check = confirm('<?php lang('fonts_delete_font_confirm'); ?>');
		}
		if((type == 'publish' || type == 'unpublish' || type == 'remove') && check)
			jQuery(e).parent('td').parent('tr').children('.checkbx').children('label').children('.checkb').prop( "checked", true );
		
		if(check)
		{
			jQuery.ajax({
				type: "POST",
				url: url,
				data: jQuery('.fr-fonts').serialize(),
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