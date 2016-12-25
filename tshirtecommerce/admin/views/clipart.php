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
<link rel="stylesheet" href="<?php echo site_url('assets/plugins/dynatree/src/skin-vista/ui.dynatree.css'); ?>">
<link href="<?php echo site_url('assets/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo site_url('assets/plugins/bootstrap-modal/css/bootstrap-modal.css'); ?>" rel="stylesheet" type="text/css"/>

<script src="<?php echo site_url('assets/js/dg-function.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/dynatree/src/jquery.dynatree.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/ui-modals.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script>
<script>
	var base_url = '<?php echo site_url(); ?>';	
	var url_assets = '<?php echo site_url('assets/js/'); ?>';	
	jQuery(document).ready(function() {				
		dgUI.category.tree('#tree6', 'clipart');
		dgUI.category.lang.msg = '<?php lang('category_msg'); ?>';
		dgUI.category.lang.msga = '<?php lang('category_msga'); ?>';
		dgUI.category.lang.title = '<?php lang('title'); ?>';
		dgUI.category.lang.add_title = '<?php lang('add_title'); ?>';		
		dgUI.category.lang.confirm_delete = '<?php lang('confirm_delete'); ?>';
		dgUI.category.ini();
		dgUI.art.ini();		
	});
</script>
<div class="row">
	<!-- start: LIST CATEGORIES TREE -->
	<div class="col-md-3">		
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-list"></i>
				<?php lang('menu_left_categories'); ?>
			</div>
			<div class="panel-body">
				<div class="row">
					<center>
						<a class="btn btn-primary btn-xs dgUI-category" rel="add" title="<?php lang('add'); ?>" href="javascript:;">
							<i class="glyphicon glyphicon-plus"></i>
						</a>						
						<a class="btn btn-bricky btn-xs dgUI-category" rel="remove" title="<?php lang('delete'); ?>" href="javascript:;">
							<i class="glyphicon glyphicon-trash"></i>
						</a>
						<a class="btn btn-green btn-xs dgUI-category" rel="edit" title="<?php lang('edit'); ?>" href="javascript:;">
							<i class="glyphicon glyphicon-pencil"></i>
						</a>					
					</center>
				</div>
				
				<div class="row">
					<div id="tree6">						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end: LIST CATEGORIES TREE -->
	
	
	<!-- start: LIST CLIPART -->
	<div class="col-md-9">	
		<form id="artform" action="<?php echo site_url('index.php/clipart'); ?>" method="post" name="artform">
			<?php if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="clip-list"></i>				
					<?php echo $data['sub_title']; ?>
				</div>
				<div class="panel-body">				
					<div class="row">														
						<!-- begin tools -->
						<div class="col-md-4 text-right pull-right">
							<a href="<?php echo site_url('index.php/clipart/edit'); ?>" class="btn btn-primary" title="<?php lang('add'); ?>">
								<i class="glyphicon glyphicon-plus"></i>					
							</a>						
							<button type="button" onclick="submit_delete()" class="btn btn-bricky dgUI-art" title="<?php lang('delete'); ?>">
								<i class="glyphicon glyphicon-trash"></i>
							</button>						
						</div>
						
						<!-- end tools -->
					</div>
					<br />
					<div class="clear-line"></div>
					<!-- load clipart -->
					<div class="row" id="clipart-rows">
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
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- end: LIST CLIPART -->
	
</div>
<div id="ajax-modal" class="modal fade" tabindex="-1" style="display: none;"></div>
<script type="text/javascript">
	function submit_delete()
	{
		var cf = confirm('<?php lang('confirm_delete'); ?>');
		if(cf)
		{
			jQuery('#artform').attr('action', '<?php echo site_url('index.php/clipart/delete'); ?>');
			jQuery('#artform').submit();
		}
	}
</script>