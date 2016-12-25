<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * API
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

error_reporting(0);
session_start();

define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

include_once ROOT .DS. 'includes' .DS. 'functions.php';

// call language
$dg = new dg();
$lang = $dg->lang();

$site_url = $dg->url();

// get design
$cache = $dg->cache('admin');

$path = ROOT .DS. 'cache' .DS. 'admin';
$files = $dg->getFiles($path);
$designs = array();

if ($files !== false)
{
	$i = 0;
	foreach($files as $file)
	{
		$key = str_replace('.txt', '', $file);
		$designs[$key] = $cache->get($key);
	}
}

// Paginition.
$url 	= $_SERVER['REQUEST_URI'];
$params = explode('admin.php/', $url);
if (count($params) > 1)
{
	$segment = (int) $params[1];
}
else
{
	$segment = '';
}


$perpage = 12;
$segment_product = 0;
$segment_design = 0;

if($segment != '')
{
	if(isset($_GET['page']) && $_GET['page'] == 'product')
		$_SESSION['segment_product'] = $segment;
	else
		$_SESSION['segment_design'] = $segment;
	
	if(!empty($_SESSION['segment_product']))
		$segment_product = $_SESSION['segment_product'];
	if(!empty($_SESSION['segment_design']))
		$segment_design = $_SESSION['segment_design'];
}
else
{
	$_SESSION['segment_product'] = 0;
	$_SESSION['segment_design'] = 0;
}


// Design template.
$sort_design = array();
if (count($designs)) 
{
	$data = array();
	$i = 0;
	foreach($designs as $key => $design)
	{
		foreach($design as $k => $v)
		{
			$data[$i] = $v;
			$data[$i]['id'] = $k;
			$data[$i]['key'] = $key;
			$i++;
		}
	}
	
	//sort array().
	for($i = count($data)-1; $i>=0; $i--)
	{
		$sort_design[] = $data[$i];
	}
}

$pagedesign = array(); 
	
$j = 1;
foreach($sort_design as $key=>$val)
{
	if($j > $segment_design && $j <= ($perpage+$segment_design))
		$pagedesign[] = $val;
	$j++;
}

if($perpage < count($sort_design))
	$page_design = $perpage;
else
	$page_design = 0;
$designs = $pagedesign;
$total_design = count($sort_design);
?>

<?php if(!isset($_GET['page'])){ ?>
<div id="products-admin" role="tabpanel">
<?php } ?>
	<ul class="nav nav-tabs" role="tablist">		
		<li role="presentation" class="active"><a href="#design-template" aria-controls="design-template" role="tab" data-toggle="tab">Design Template</a></li>
	</ul>
	
	<div class="tab-content" style="max-height: auto; overflow: unset;">		
		<div role="tabpanel" class="tab-pane active" id="design-template">
			<div class="design-tab-content">
				<div style="max-height: 400px; overflow: auto;">
					<?php foreach($designs as $key => $value) { ?>
					
						<div class="col-xs-4 col-sm-3 col-md-2" style="margin-right: -4px;">
							<div class="form-group">
								<div class="img-thumbnail">
									<a class="add-link modal-link" href="#" data-id="<?php echo $value['key'].':'.$value['id'].':'.$value['product_id'].':'.$value['product_options']; ?>" data-title="<?php echo $value['id']; ?>">
										<img src="<?php echo imageURL($value['image'], $site_url); ?>" class="img-responsive" alt="<?php echo $value['id']; ?>">
										<br />
										Add Design
									</a>
																	
									<a href="<?php echo $site_url.'tshirtecommerce/index.php?user='.$value['key'].'&id='.$value['id'].'&product='.$value['product_id'].'&color='.$value['product_options'].'&parent_id='.$value['parent_id']; ?>" class="pull-right" target="_blank">View Design</a>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="dataTables_paginate paging_bootstrap pull-right">
					<div class="col-md-12">
						<?php
							if(!empty($page_design))
							{
								$page = $total_design/$page_design;
								if($page > (int)$page)
									$page = (int)$page + 1;
								$start = $segment_design/$page_design;
								
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
									
								echo '<ul class="pagination" style="margin-top: 10px; margin-bottom: 0px;">';
								if($segment_design != 0)
								{
									if($pageall)
										echo '<li><a href="javascript:void(0);" onclick="pagination(0, \'design\')"><span aria-hidden="true">&laquo;</span></a></li>';
									echo '<li><a href="javascript:void(0);" onclick="pagination('.($segment_design-$page_design).', \'design\')"><span aria-hidden="true">&laquo;</span></a></li>';
								}
								for($i = $start; $i<$page; $i++)
								{
									if(($i)*$page_design == $segment_design && $div == 0)
										echo '<li class="active"><a href="javascript:void(0);">'.($i+1).'</a></li>';
									elseif(($i+$div-1)*$page_design == $segment_design && $div != 0)
										echo '<li class="active"><a href="javascript:void(0);">'.($i+1).'</a></li>';
									else
										echo '<li><a href="javascript:void(0);" onclick="pagination('.($i*$page_design).', \'design\')">'.($i+1).'</a></li>';
								}
								if(($segment_design+$page_design) < $total_design)
								{
									echo '<li><a href="javascript:void(0);" onclick="pagination('.($segment_design+$page_design).', \'design\')"><span aria-hidden="true">&raquo;</span></a></li>';
									if($pageall)
										echo '<li><a href="javascript:void(0);" onclick="pagination('.($total_design-$page_design).', \'design\')"><span aria-hidden="true">&raquo;</span></a></li>';
								}
							}
						?>
					</div>
			   </div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery('.add-link').click(function(){
			app.admin.add(this);
		});
	</script>
<?php if(!isset($_GET['page'])){ ?>
</div>

<script type="text/javascript">
	function pagination(segment, page)
	{
		jQuery.ajax({
			type: "GET",
			url: '<?php echo $dg->url(); ?>tshirtecommerce/admin.php/'+segment+'?page='+page,
			dataType: 'html',
			beforeSend: function(){
				jQuery('#product-design').css('opacity', '0.1');
			},
			success: function(data){
				if(data != '')
				{
					jQuery('#products-admin').html(data);
				}
				jQuery('#product-design').css('opacity', '1');
				app.admin.ini();
			},
		});
	}
</script>
<?php } ?>