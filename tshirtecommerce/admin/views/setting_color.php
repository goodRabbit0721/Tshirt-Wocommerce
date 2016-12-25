<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 ?>
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
		<?php if(isset($data['colors'])) foreach ($data['colors'] as $key=>$color) { ?>
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
		<?php } ?>    
	</tbody>
</table>
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