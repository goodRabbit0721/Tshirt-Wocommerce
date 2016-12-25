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
<!-- load clipart -->
<?php
if(isset($data['arts']))
{
	$arts = array_reverse($data['arts']);
	foreach($arts as $art)
	{							
		$images = imageArt($art);
?>
	<div class="col-md-2 col-sm-3 box-art">
		<a class="box-image" data-toggle="modal" href="javascript:void(0)" title="<?php echo $art->title; ?>">
			<img src="<?php echo $images->thumb; ?>" alt="" class="img-responsive">
		</a>
		<a class="box-publish" href="javascript:void(0)">
			<input class="checkb" type="checkbox" value="<?php echo $art->clipart_id; ?>" name="ids[]">						
		</a>								
		<a class="box-edit" href="<?php echo site_url('index.php/clipart/edit/'.$art->clipart_id); ?>">
			<i class="fa fa-pencil"></i>
		</a>
		<div class="box-detail-price"><?php echo $data['currency_symbol'].$art->price; ?></div>		
	</div>
<?php 
	}
}
?>

<!-- begin pagination -->
<div class="clear-line clear-line-head col-md-12"></div>
<div id="arts-pagination" class="pull-right col-md-12 text-right">
	
	<?php if ($data['page'] > 1) { ?>
	<ul class="pagination">
		
		<?php for($i=1; $i<=$data['page']; $i++) { ?>
			
			<?php if ($i == $data['index']){ ?>
				<li class="active"><a href="#"><?php echo $i; ?> <span class="sr-only"></span></a></li>
			<?php }else{ ?>
				<li><a href="<?php echo site_url('index.php/clipart/index/'.$i.'/'.$data['cateid']); ?>"><?php echo $i; ?></a></li>
			<?php } ?>
			
		<?php } ?>
		
	</ul>
	<?php } ?>
	
</div>
<!-- end pagination -->			