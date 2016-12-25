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

<?php echo $css = ''; ?>

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
		<?php if (isset($data['fonts'])) foreach ($data['fonts'] as $key=>$val) { ?>
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
							<a class="btn btn-success btn-xs tooltips" data-original-title="<?php lang('click_publish');?>" onclick="action('unpublish', this)"><?php lang('publish'); ?></a>
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
	</tbody>
</table>
<link href='https://fonts.googleapis.com/css?family=<?php echo $css; ?>' rel='stylesheet' type='text/css'>
<div class="row">
	<div class="dataTables_paginate paging_bootstrap" style="float: right;">
		<div class="col-md-12">
			<?php
				if(!empty($data['page']))
				{
					$page = $data['total']/$data['page'];
					if($page > (int)$page)
						$page = (int)$page + 1;
					$start = $data['segment']/$data['page'];
					
					$div = 0;
					if($start > (int)$start)
					{
						$div = $start - (int)$start;
						$start = (int)$start + 1;
					}
					if($page > 5)
					{
						$pageall = true;
						if($start > 1)
						{
							$start = $start - 2;
							if($page > $start+5)
								$page = $start+5;
						}else
						{
							$start = 0;
							$page = 5;
						}
					}else
					{
						$pageall = false;
						$start = 0;
					}
						
					echo '<ul class="pagination">';
					if($data['segment'] != 0)
					{
						if($pageall)
							echo '<li><a href="javascript:void(0);" onclick="pagination(0)"><span aria-hidden="true">&laquo;</span></a></li>';
						echo '<li><a href="javascript:void(0);" onclick="pagination('.($data['segment']-$data['page']).')"><span aria-hidden="true">&laquo;</span></a></li>';
					}
					for($i = $start; $i<$page; $i++)
					{
						if(($i)*$data['page'] == $data['segment'] && $div == 0)
							echo '<li class="active"><a href="javascript:void(0);">'.($i+1).'</a></li>';
						elseif(($i+$div-1)*$data['page'] == $data['segment'] && $div != 0)
							echo '<li class="active"><a href="javascript:void(0);">'.($i+1).'</a></li>';
						else
							echo '<li><a href="javascript:void(0);" onclick="pagination('.($i*$data['page']).')">'.($i+1).'</a></li>';
					}
					if(($data['segment']+$data['page']) < $data['total'])
					{
						echo '<li><a href="javascript:void(0);" onclick="pagination('.($data['segment']+$data['page']).')"><span aria-hidden="true">&raquo;</span></a></li>';
						if($pageall)
							echo '<li><a href="javascript:void(0);" onclick="pagination('.($data['total']-$data['page']).')"><span aria-hidden="true">&raquo;</span></a></li>';
					}
				}
			?>
		</div>
   </div>
</div>

<!-- end: PAGE CONTENT-->