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

<table class="table table-bordered table-hover" id="sample-table-1">
	<thead>
		<tr>
			<th class="center" width="5%">
				<input type="checkbox" onclick="dgUI.checkAll(this)" id="select_all">
			</th>
			<th class="center"><?php lang('product_name'); ?></th>
			<th width="10%" class="center"><?php lang('product_create_design'); ?></th>
			<th width="10%" class="center"><?php lang('product_sku'); ?></th>
			<th width="10%" class="center"><?php lang('product_sale_price'); ?></th>
			<th width="20%" class="center"><?php lang('product_image'); ?></th>				
			<th width="10%" class="center"><?php lang('published'); ?></th>
			<th width="10%" class="center"><?php lang('action'); ?></th>
			<th width="5%" class="center"><?php lang('id'); ?></th>
		</tr>
	</thead>
	<tbody>	
	<?php if (isset($data['products']) && count($data['products']) > 0) { ?>
	<?php foreach ($data['products'] as $key=>$product) { ?>
		
		<tr>
			<td class="center">
				<input type="checkbox" class="checkb" value="<?php echo $product->id; ?>" name="ids[]" />
			</td>
			<td>
				<a href="<?php echo site_url('index.php/product/edit/' . $product->id); ?>" title=""><?php echo $product->title; ?></a>
			</td>
			<td class="center">
				<a href="<?php echo $data['url_design'].'&product='.$product->id; ?>" title="" target="_bank"><?php lang('view'); ?></a>
			</td>
			<td class="center">
				<?php echo $product->sku; ?>
			</td>
			<td class="center">
				<?php if ($product->sale_price > 0) echo $product->sale_price; else echo $product->price; ?>
			</td>
			<td class="center">
				<img src="<?php echo imageURL($product->image); ?>" alt="" width="150"/>
			</td>			
			<td class="center">
				<?php if ($product->published == 1){ ?>
					<a href="<?php echo site_url('index.php/product/unpublish/' . $product->id); ?>" class="btn btn-success btn-xs"><?php lang('publish'); ?></a>
				<?php }else{ ?>
					<a href="<?php echo site_url('index.php/product/publish/' . $product->id); ?>" class="btn btn-bricky btn-xs"><?php lang('unpublish'); ?></a>
				<?php } ?>
			</td>
			<td class="center">
				<div class="btn-group">
					<button type="button" class="btn btn-teal btn-xs">
						<i class="glyphicon glyphicon-cog"></i>
					</button>
					<button type="button" class="btn btn-teal btn-xs dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo site_url('index.php/product/edit/' . $product->id); ?>"><i class="fa fa-edit"></i> <?php lang('edit'); ?></a></li>							
						<li><a href="<?php echo site_url('index.php/product/copy/' . $product->id); ?>"><i class="fa fa-copy"></i> <?php lang('copy'); ?></a></li>
						<li><a href="<?php echo site_url('index.php/product/delete/' . $product->id); ?>" onclick="return confirm('<?php lang('confirm_delete'); ?>')"><i class="glyphicon glyphicon-trash"></i> <?php lang('remove'); ?></a></li> 							
					</ul>
				</div>
			</td>
			<td class="center">
				<?php echo $product->id; ?>
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
<?php } ?>