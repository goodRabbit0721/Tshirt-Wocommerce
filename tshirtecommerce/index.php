<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE
 *
 */
require('../wp-blog-header.php');

error_reporting(0);
session_start();
date_default_timezone_set('America/Los_Angeles');
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

include_once ROOT .DS. 'includes' .DS. 'functions.php';
include_once ROOT .DS. 'includes' .DS. 'addons.php';

// call language
$dg = new dg();
$lang = $dg->lang();

// call products
$products	= $dg->getProducts();
$product	= $products[0];

if (isset($_GET['product']))
{
	$product_id = $_GET['product'];
}

if (isset($_GET['cart_id']))
{
	$cache 	= $dg->cache('cart');
	$design = $cache->get($_GET['cart_id']);
	if ($design != null && isset($design['item']) && isset($design['item']['product_id']))
	{
		$product_id = $design['item']['product_id'];
	}
}

for($i=0; $i< count($products); $i++)
{
	if ($product_id == $products[$i]->id)
		$product = $products[$i];
}

// get attribute
if (isset($product->attributes->name))
{
	$product->attribute = $dg->getAttributes($product->attributes);
}
else
{
	$product->attribute = '';
}
$product->attribute .= $dg->quantity($product->min_order, lang('quantity', true), lang('min_quantity', true));

// get getSetting
$settings			= $dg->getSetting();
$settings->site_url = $dg->url();

$dg->settings		= $settings;

// fix link with www
if(preg_match('/www/', $_SERVER['HTTP_HOST']))
{
	$temp = explode('//www.', $settings->site_url);
	if (count($temp) == 1)
	{
		$settings->site_url = str_replace('http://', 'http://www.', $settings->site_url);
	}
}
else
{
	$settings->site_url = str_replace('//www.', '//', $settings->site_url);
}

// check session
if (isset($_SESSION['is_logged']) && $_SESSION['is_logged'] !== false)
{
	$is_logged = $_SESSION['is_logged'];	
	$user = md5($is_logged['id']);
}
else
{
	$user = 0;
}

// load add-on
$addons = new addons();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title><?php echo setValue($settings, 'site_name', 'T-Shirt eCommerce'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1, minimum-scale=0.5, maximum-scale=1.0"/>
	<meta content="<?php echo setValue($settings, 'meta_description', 'T-Shirt eCommerce'); ?>" name="description" />
	<meta content="<?php echo setValue($settings, 'meta_keywords', 'T-Shirt eCommerce'); ?>" name="keywords" />
	
	<link type="text/css" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="all"/>
	<link type="text/css" href="assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" media="all" />
	<link type="text/css" href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" media="all" />
	<link type="text/css" href="assets/css/style-dev.css" rel="stylesheet" media="all">
	<link type="text/css" href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">	
	
	<?php echo $addons->css(); ?>
	
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	
	<?php echo $addons->view('lang-js'); ?>
	
	<script src="<?php echo 'assets/js/add-ons.js'; ?>"></script>
	<script src="<?php echo 'assets/js/jquery.ui.rotatable.js'; ?>"></script>	
	<script src="<?php echo 'assets/js/design.js'; ?>" type="text/javascript" charset="utf-8"></script>	
	<script src="<?php echo 'assets/js/main.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo 'assets/js/rgbcolor.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo 'assets/js/canvg.js'; ?>"></script>
	<script type="text/javascript" src="assets/plugins/perfect-scrollbar/perfect-scrollbar.js"></script>	
	<script type="text/javascript" src="assets/plugins/perfect-scrollbar/jquery.mousewheel.js"></script>
	
	<script type="text/javascript">
		<?php
		if ( isset($_GET['lang']) )
		{
			$lang_active = $_GET['lang'];
		}
		elseif(isset($_COOKIE['lang']))
		{
			$lang_active = $_COOKIE['lang'];
		}
		else
		{
			$lang_active = '';
		}
		?>
		var lang_active = '<?php echo $lang_active; ?>';
		var baseURL = '';
		var mainURL = '<?php echo $settings->site_url; ?>';
		var siteURL = '<?php echo str_replace('//tshirtecommerce', '/tshirtecommerce', $settings->site_url.'tshirtecommerce/'); ?>';
		var urlCase = '<?php echo str_replace('//tshirtecommerce', '/tshirtecommerce', $settings->site_url.'tshirtecommerce/'); ?>image-tool/thumbs.php';
		var user_id = '<?php echo $user; ?>';
		var currency_symbol = '<?php echo setValue($settings, 'currency_symbol', '$'); ?>';
		var parent_id = '<?php if (isset($_GET['parent'])) echo $_GET['parent']; else echo 0; ?>';
		
		var domain = '<?php echo $_SERVER['HTTP_HOST']; ?>';
		
		var urlDesign = '';
		if (typeof window.parent.urlDesign == 'undefined' || window.parent.urlDesign == '')
		{
			jQuery('#designer-alert').html(lang.text.setup).css('display', 'block');
		}
		else
		{
			urlDesign = window.parent.urlDesign;
		}		
	</script>
	<script src="<?php echo get_template_directory_uri() ?>/countdown_v5.0/countdown.js"
			type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/cloudzoom/cloudzoom.css">
	<script src="<?php echo get_template_directory_uri() ?>/cloudzoom/jquery.cloudzoom.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/colorbox/colorbox.css">
	<script src="<?php echo get_template_directory_uri() ?>/colorbox/jquery.colorbox-min.js"></script>
	<script src="<?php echo get_template_directory_uri() ?>/owl.carousel.2.0.0-beta.2.4/owl.carousel.min.js"></script>
	<link rel="stylesheet"
		  href="<?php echo get_template_directory_uri() ?>/owl.carousel.2.0.0-beta.2.4/assets/owl.carousel.css">
	<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<script type='text/javascript'>
		/* <![CDATA[ */
		var ajax_login_object = {
			"ajaxurl": "<?php echo str_replace('/', '\/', get_home_url()); ?>\/wp-admin\/admin-ajax.php",
			"redirecturl": "<?php echo str_replace('/', '\/', get_home_url()); ?>",
			"loadingmessage": "Sending user info, please wait..."
		};
		/* ]]> */
	</script>
	<?php echo $dg->theme('header'); ?>
</head>
<body  id="body_design_now">

<div class="campaign-subheader navbar">
	<div class="actions-bar">
		<ul id="launch-progression" class="nav">
			<li class="active-step">
				<a href="javascript:void(0);" data-step="1">
					<span class="progress-number">1</span>Create your Design</a>
			</li>
			<li>
				<a href="javascript:void(0);" data-step="2">
					<span class="progress-number">2</span>Set your Sales Goal
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" data-step="3">
					<span class="progress-number">3</span>Add your Marketing Description
				</a>
			</li>
		</ul>
		<div style="clear: both"></div>
	</div>
</div>
<div id="panel-design" class="container-fluid panel panel_1">
		<div id="dg-wapper" class="col-md-12">
			<div class="alert alert-danger" id="designer-alert" role="alert" style="display:none;"></div>
			<div id="dg-mask" class="loading"></div>
			
			<!-- BEGIN main of layout -->
			<div id="dg-designer">
				
				<!-- BEGIN left -->
				<?php $dg->view('tool_left'); ?>
				<!-- END left -->
				
				<!-- BEGIN left -->
				<?php $dg->view('too_center'); ?>
				<!-- END left -->
				
				<!-- BEGIN left -->
				<?php $dg->view('too_right'); ?>
				<!-- END left -->
				
			</div>
			<!-- END main of layout -->
			
		</div>
	</div>
	
	<!-- BEGIN confirm color of print -->
	<?php $dg->view('screen_colors'); ?>
	<!-- END confirm color of print -->
	
	<!-- BEGIN modal -->
	<div id="dg-modal">
		
		<!-- BEGIN product info -->
		<?php $dg->view('modal_product_info'); ?>
		<!-- END product info -->
		
		<!-- BEGIN product size -->
		<?php $dg->view('modal_product_size'); ?>
		<!-- END product size -->
		
		<!-- BEGIN Login -->
		<?php $dg->view('modal_login'); ?>
		<!-- END Login -->
		
		<!-- BEGIN products -->
		<?php $dg->view('modal_products'); ?>
		<!-- END products -->
		
		<!-- BEGIN clipart -->
		<?php $dg->view('modal_clipart'); ?>
		<!-- END clipart -->
		
		<!-- BEGIN Upload -->
		<?php $dg->view('modal_upload'); ?>
		<!-- END Upload -->
		
		<!-- BEGIN Note -->
		<?php $dg->view('modal_note'); ?>
		<!-- END Note -->
		
		<!-- BEGIN Help -->
		<?php $dg->view('modal_help'); ?>
		<!-- END Help -->
		
		<!-- BEGIN My design -->
		<?php $dg->view('modal_my_design'); ?>
		<!-- END My design -->
		
		<!-- BEGIN design ideas -->
		<?php $dg->view('modal_ideas'); ?>
		<!-- END design ideas -->
		
		<!-- BEGIN team -->
		<?php $dg->view('modal_team'); ?>
		<!-- END team -->
		
		<!-- BEGIN fonts -->
		<?php $dg->view('modal_fonts'); ?>
		<!-- END fonts -->
		
		<!-- BEGIN preview -->
		<?php $dg->view('modal_preview'); ?>
		<!-- END preview -->
		
		<!-- BEGIN Share -->
		<?php $dg->view('modal_share'); ?>
		<!-- END Share -->
		
		<?php $addons->view('modal'); ?>
	</div>
	<!-- END modal -->
	
	<!-- BEGIN popover -->
	<div class="popover right" id="dg-popover">
		<div class="arrow"></div>
		<h3 class="popover-title">
			<span><?php echo $lang['designer_clipart_edit_size_position']; ?></span> 
			<a href="javascript:void(0)" class="popover-close">
				<i class="glyphicons remove_2 glyphicons-12 pull-right"></i>
			</a>
		</h3>
		
		<div class="popover-content">
		
			<!-- BEGIN clipart edit options -->
			<?php $dg->view('popover_clipart'); ?>
			<!-- END clipart edit options -->
			
			<!-- BEGIN Text edit options -->
			<?php $dg->view('popover_text'); ?>
			<!-- END Text edit options -->
			
			<!-- BEGIN team edit options -->
			<?php $dg->view('popover_team'); ?>
			<!-- END team edit options -->
			
			<!-- BEGIN qrcode -->
			<?php $dg->view('popover_qrcode'); ?>
			<!-- END qrcode -->
			
			<?php $addons->view('popover'); ?>
		</div>
	</div>
	<!-- END popover -->
	
	<!-- BEGIN colors system -->
	<div class="o-colors" style="display:none;">		
		<div class="other-colors"></div>
	</div>
	<!-- END colors system -->
	
	<div id="cacheText"></div>
	
	<div id="id_login"></div>
	<div id="save-confirm" title="<?php echo lang('designer_user_login_now_or_sign_up'); ?>" style="display:none;">
		<p><?php echo lang('designer_saved_design'); ?></p>
	</div>
	
	<?php if (isset($product->design)) {?>
	<script type="text/javascript">
		<?php 
		$min_order 			= setValue($product, 'min_order', 1);
		$max_order 			= setValue($product, 'max_oder', 9999);
		$site_upload_max 	= setValue($settings, 'site_upload_max', 10);
		$site_upload_min 	= setValue($settings, 'site_upload_min', 0.05);
		
		$min_order	= (int) $min_order;
		$max_order	= (int) $max_order;
		if ($min_order < 1)
			$min_order = 1;
		
		if ($max_order < $min_order)
			$max_order = 9999;
		
		if ($site_upload_min < 0)
			$site_upload_min = 0.05;
		
		if ($site_upload_max < 0)
			$site_upload_max = 10;
		
		?>
		var min_order = <?php echo $min_order; ?>;
		var max_order = <?php echo $max_order; ?>;
		var product_id = '<?php echo $product->id; ?>';
		var print_type = '<?php echo setValue($product, 'print_type', 'screen'); ?>';
		var uploadSize = [];
		uploadSize['max']  = <?php echo $site_upload_max; ?>;
		uploadSize['min']  = <?php echo $site_upload_min; ?>;
		var items = {};
		items['design'] = {};
		<?php 
		$js = '';
		$elment = count($product->design->color_hex);
		for($i=0; $i<$elment; $i++)
		{			
			$js .= "items['design'][$i] = {};";
			$js .= "items['design'][$i]['color'] = \"".$product->design->color_hex[$i]."\";";
			$js .= "items['design'][$i]['title'] = \"".$product->design->color_title[$i]."\";";
			$postions	= array('front', 'back', 'left', 'right');
			foreach ($postions as $v)
			{
				$view = $product->design->$v;				
				if (count($view) > 0) 
				{
					if (isset($view[$i]) == true)
					{
						$item = (string) $view[$i];						
						$js .= "items['design'][".$i."]['".$v."']=\"".$item."\";";						
					}
					else
					{
						$js .= "items['design'][$i]['$v'] = '';";
					}
				}
				else
				{
					$js .= "items['design'][$i]['$v'] = '';";
				}				
			}
		}
		echo $js;
		?>
		items['area']	= {};
		items['area']['front']	= "<?php echo $product->design->area->front; ?>";
		items['area']['back']	= "<?php echo $product->design->area->back; ?>";
		items['area']['left']	= "<?php echo $product->design->area->left; ?>";
		items['area']['right']	= "<?php echo $product->design->area->right; ?>";		
		items['params']	= [];		
		items['params']['front']	= "<?php echo $product->design->params->front; ?>";		
		items['params']['back']	= "<?php echo $product->design->params->back; ?>";		
		items['params']['left']	= "<?php echo $product->design->params->left; ?>";		
		items['params']['right']	= "<?php echo $product->design->params->right; ?>";		
	</script>
	<?php } ?>
	<script type="text/javascript" src="assets/js/design_upload.js"></script>
	<?php echo $addons->js(); ?>	
<?php 
// load design
$color = '-1';
$design_id = '';
$designer_id = '';
if (isset($_GET['color']))
{
	$color = $_GET['color'];
}

if (isset($_GET['user']))
{
	$designer_id = $_GET['user'];
}

if (isset($_GET['id']))
{
	$design_id = $_GET['id'];
}
?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('[data-toggle="tooltip"]').tooltip();
		<?php if( $color  != '-1' ){ ?>
		design.imports.productColor('<?php echo $color; ?>');
		<?php } ?>
		
		<?php if( $design_id  != '' ){ ?>
		design.imports.loadDesign('<?php echo $design_id; ?>', '<?php echo $designer_id; ?>');
		<?php } ?>
		
		// load design cart
		<?php if (isset($_GET['cart_id'])) { ?>
		design.imports.cart('<?php echo $_GET['cart_id']; ?>');
		<?php } ?>
		window.parent.setHeigh(jQuery('#body_design_now').height());

	});
	</script>
<?php $currency_code = 'AUD'; ?>
<form onsubmit="return false;" class="campaign-info" id="campaign-info" method="post">
	<div id="panel-pricing" class="panel panel_2" style="display: none;">
		<div class="row">
			<div class="col-md-6">
				<input type="hidden" id="h_product_price"
					   value="<?php echo $product->price; ?>"/>
				<input type="hidden" id="h_product_percent_profit" value="30"/>

				<div class="row">
					<div class="col-md-12 form-group">
						<label for="author"><strong>Set your Sales Goal </strong><span
								class="minimun_title"> - Minimum production run - 15 Pcs</span></label>
						<input id="sales_goal"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 form-group">
						<div class="input-group sales_goal_content">
							<span class="input-group-addon" id="basic-addon1"># of units</span>
							<input autocomplete="off" type="text" id="sales_goal_input"
								   value="15" name="sales_goal" class="form-control"
								   placeholder="# of units" aria-describedby="basic-addon1">
						</div>
					</div>

					<div class="col-md-8" style="    padding-left: 2%;">
						Your goal is the number of units you’re aiming to sell, but we’ll print
						your campaign as long as you sell enough to generate a profit. The
						minimum is 15 pcs.
					</div>
				</div>

				<div class="row " style="    padding-top: 25px;">
					<div class="col-md-12 form-group">
						<label for="price"><strong>Set your Sell Price </strong></label>
					</div>
				</div>
				<div class="row ">
					<div class="col-md-12 form-group">
						<label for="price"><strong>Apparel Options</strong></label>

					</div>
				</div>
				<div class="ssp_block product row box_design active" product_id="<?php echo $product_id; ?>">
					<div class="row ssp_block__top"
						 style="overflow: visible;   display: -webkit-box;    display: -webkit-flex;    display: -ms-flexbox;    position: relative; ">
						<div style="    margin-bottom: 2px;" class="col-md-8 form-group">
							<div style="box-sizing: border-box;">
								<div>
									<img style="float: left;     width: 100px;"
										 class="product_thumbnail"
										 src="<?php echo get_template_directory_uri() ?>/images/shirt-loading.gif"/>
									<input type="hidden" name="design_image_front"
										   id="design_image_front" value=""/>
									<input type="hidden" name="design_image_back"
										   id="design_image_back" value=""/>
									<input type="hidden" name="all_design_image_front"
										   id="all_design_image_front" value=""/>
									<input type="hidden" name="all_design_image_back"
										   id="all_design_image_back" value=""/>
									<input type="hidden" name="color_image_check"
										   id="color_image_check" value=""/>
								</div>
                                                        <span class="design-fpd-title"
															  style="font-size: 1.3em;    font-weight: 700;"><?php echo $product->post->post_title; ?></span>
								<div class="ssp_profit"
									 style="margin-top: -3px;    font-size: 1em;    font-weight: 600;    color: #6c7478;    display: -webkit-box;    display: -webkit-flex;    display: -ms-flexbox;    display: flex;">
									<span class="profit_per" style="font-weight: 800;"></span>
									<span>&nbsp;</span>
									<span>profit/sale</span>
								</div>
							</div>

						</div>
						<div style=" padding-left: 0%;   margin-bottom: 2px;"
							 class="col-md-4 price_content form-group price_content_3">
                                            <span class="currency_pre" style="style=" width: 30%;
												  float: left;"""><?php echo $currency_code; ?></span>
							<div class="input-group " style="    width: 70%;
    float: right;">
								<span class="input-group-addon" id="basic-addon1">$</span>
								<input autocomplete="off" class="form-control campaign_price"
									   autocomplete="false" type="text" aria-required="true"
									   size="30"
									   value="0" name="price" id="campaign_price"
									   aria-describedby="basic-addon1"
									   data-placement="right">
								<input type="hidden" value="" name="edited_price"
									   id="edited_price"/>
								<input autocomplete="off" type="hidden"
									   value="<?php echo $product->price; ?>" name="cost_price"
									   id="cost_price">

							</div>
							<?php /*
                            <div class="price_loading">
                                <img
                                    src="<?php echo get_template_directory_uri() ?>/images/mini_loaderwheel.gif"/>
                            </div>
 */ ?>
						</div>
					</div>
					<div class="row ">
						<div class="col-md-12 form-group"
							 style="margin-bottom: 0px;    margin-top: 10px;"
							 class="ssp_block ssp_block__bottom">
							<div>
								<div class="ssp_color">
								</div>

								<input class="shirt_color_seleted" autocomplete="off"
									   type="hidden" value=''/>
							</div>
						</div>
					</div>
				</div>

				<div class="style_adder">
					<div class="style_adder__panel">
						<div class="style_adder__cta">Add Style</div>
						<div class="style_adder__description">Optimize your campaign by adding an additional style
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7 form-group">
						<div><label>Estimated profit</label></div>
						<div class="price_content_2">
							<span class="currency_pre"><?php echo $currency_code; ?></span>
							<span id="global_profit">$0+</span>
						</div>
					</div>
					<div class="col-md-5 form-group">
						<div class="submit_container">
							<button type="button" class="button_transaction button"
									data-step="3">Next
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 ">
				<div class="flip-container">
					<div class="flip">
						<div class="front design_view">

						</div>
						<div class="back design_view">

						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
	<div id="panel-details" class="panel panel_3" style="display: none;">
		<div class="row">
			<div class="col-md-6">

				<div class="form-group">
					<label for="name">Campaign Title</label>
					<input class="form-control required" autocomplete="false" type="text"
						   aria-required="true" size="30" value="" name="name"
						   id="campaign_name" required minlength="3">
				</div>
				<div class="form-group">
					<label for="content">Description</label>

					<?php
					$content = '';
					$editor_id = 'campaign_content';
					$settings = array('media_buttons' => false,
						'teeny' => true);
					wp_editor($content, $editor_id, $settings);
					?>
				</div>
				<div class="form-group">
					<label for="content">Feature Side</label>
					<div>Choose which side of the garment you want to feature in your marketing
						campaign.
					</div>
					<input type="radio" id="front" name="feature_side" checked="checked"
						   value="front" aria-label="Front">
					<label for="front">Front</label>
					<input style="margin-left: 35px;" type="radio" id="back" name="feature_side"
						   value="back" aria-label="Back">
					<label for="back">Back</label>
				</div>
				<div class="form-group">
					<label class="control-label">Campaign Length</label>
					<div>Orders will ship 5-10 business days after the end of the campaign.
					</div>

					<div class="col-md-7 " style="margin-top: 10px; padding: 0px">
						<select tabindex="3" name="campaign_length" id="length" name="length"
								class="form-control">

							<?php
							$list_length = array(3, 5, 7, 10, 14, 21, 28);
							foreach ($list_length as $n_date) {
								$date = new DateTime();

								$date->add(new DateInterval('P' . $n_date . 'D'));
								?>
								<option value="<?php echo $n_date; ?>"><?php echo $n_date; ?>
									Days (Ending <?php echo $date->format('l, M d'); ?>)
								</option>
								<?php
							}
							?>

						</select>
					</div>
					<div class="col-md-5 " style="margin-top: 10px;     padding-right: 0px;">
						<div class="submit_container" style="margin: 0px">
							<button type="submit"
									class="button_transaction campaign_submit button">Launch
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">

				<div class="flip-container">
					<div class="flip">
						<div class="front design_view">

						</div>
						<div class="back design_view">

						</div>
					</div>
				</div>


			</div>
		</div>


	</div>

</form>
<a style='display:none' class='login_popup' href="#login_popup_content">Login Hidden</a></p>
<a style='display:none' class='loading_popup' href="#loading_popup_content">Login Hidden</a></p>
<div style='display:none'>
	<div id='login_popup_content' style='padding:10px; background:#fff;'>
		<div id="ajax_login_content" class="toggle_div">
			<h3><strong>Login with teeM8</strong></h3>
			<form onsubmit="return false;" novalidate="" class="ajax_login" style="min-height: 200px" id="ajax_login"
				  method="post">
				<div class="form-group">
					<input class="form-control required" placeholder="Username" autocomplete="false" type="text"
						   aria-required="true" size="30" value="" name="username" id="username" required=""
						   minlength="3">
				</div>
				<div class="form-group">
					<input class="form-control required" placeholder="Password" autocomplete="false" type="password"
						   aria-required="true" size="30" value="" name="password" id="password" required=""
						   minlength="3">
				</div>
				<div class="form-group" style="text-align: center;">
					<p>
						<a href="/my-account/lost-password/" target="_blank">Forgot your password?</a>
					</p>
					<p>
						<button type="submit" class=" button" data-step="2">Login to your account »</button>
						<?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
					</p>
					<p>
						<a href="javascript:void(0)" class="switch_login_register">Want to create a new account?</a>
					</p>
					<p class="status"></p>
				</div>
			</form>
		</div>

		<div id="ajax_register_content" class="toggle_div" style="display: none;">
			<h3><strong>Create an account with teeM8</strong></h3>
			<form onsubmit="return false;" novalidate="" class="ajax_register" id="ajax_register" method="post">
				<div class="form-group">
					<input class="form-control required" placeholder="Username" autocomplete="false" type="text"
						   aria-required="true" max-length="50" value="" name="user_login" id="user_login" required=""
						   minlength="3">
				</div>
				<div class="form-group">
					<input class="form-control required" placeholder="Email" autocomplete="false" type="email"
						   aria-required="true" max-length="50" value="" name="user_email" id="user_email" required=""
						   minlength="3">
				</div>
				<div class="form-group">
					<input class="form-control required" placeholder="Password: Must be longer than 6 characters."
						   autocomplete="false" type="password" aria-required="true" max-length="50" value=""
						   name="user_pass" id="user_pass" required="" minlength="6">
				</div>
				<div class="form-group">
					<input class="form-control required" placeholder="Confirm Password" autocomplete="false"
						   type="password" aria-required="true" max-length="50" value="" name="user_pass_confirm"
						   id="user_pass_confirm" required="" minlength="6">
				</div>
				<div class="form-group" style="text-align: center;">
					<p>
						<button type="submit" class=" button" data-step="2">Create your account »</button>
						<?php wp_nonce_field('ajax-register-nonce', 'security'); ?>
					</p>
					<p>
						<a href="javascript:void(0)" class="switch_login_register">Already have an account?</a>
					</p>
					<p class="status"></p>
				</div>
			</form>
		</div>
	</div>


	<div id='loading_popup_content' style='padding:10px; background:#fff;'>
		<p>
		<div class="submit_loading " style="   text-align: center;    padding-bottom: 50px;">
			Please wait...
			<div class="fpd-loading"></div>
		</div>
		<div id="panel-success" class="panel panel_4" style="display: none;">

		</div>
		</p>
	</div>
</div>
<script>
	var validate_campaign;
	var is_allow_sticky = false;
	var products_design = {};
	var default_colors_design = {};
	var colors_design = {}
	function get_products() {
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_login_object.ajaxurl,
			data: {
				'action': 'woo_products_action'
			},
			success: function (data) {
				products_design = data['products'];
				jQuery.each(data['products'], function (key, value) {
					colors_design[value['id']] = {
						'color_hex': value['design']['color_hex'],
						'color_title': value['design']['color_title']
					};

				});
			}
		});
	}
	jQuery(document).mouseup(function (e) {
		var container = jQuery('.colors.shirt-colors.containertip');

		if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0) // ... nor a descendant of the container
		{
			close_popup_color();
		}
	});
	function close_popup_color() {
		jQuery('.colors.shirt-colors.containertip').remove();
	}
	function remove_bg_selected(color_hex, container) {
		$container = jQuery(container);
		var color_selected = {};

		jQuery.each(get_bg_selected(container), function (key, value) {
			if (color_hex != key) {
				color_selected[key] = value;
			}
		});
		console.log('remove bg selected');
		console.log(color_hex);
		console.log('remove bg selected 2');
		console.log(color_selected);
		$container.find('.shirt_color_seleted').val(JSON.stringify(color_selected));
		show_bg_shirt(container, false);
	}

	function show_color_pick(el) {

		var product_id = jQuery(el).attr('product_id');
		var choose_bg = '';
		var text_color = '#fff';
		jQuery.each(colors_design[product_id]['color_hex'], function (index, value) {
			var color_hex = value;
			var color_title = colors_design[product_id]['color_title'][index];
			if (color_hex == 'FFFFFF') {
				text_color = '#000'
			} else {
				text_color = '#fff';
			}
			if (check_is_use(color_hex, el)) {
				choose_bg += '<li data-value="' + color_hex + '" data-texture="" class="shirt-color-sample selected js-color" title="' + color_title + '" style="background-color:#' + color_hex + ';color:' + text_color + ';" ><span >✓</span><span ></span></li>';
			} else {
				choose_bg += '<li data-value="' + color_hex + '" data-texture="" class="shirt-color-sample js-color" title="' + color_title + '" style="background-color:#' + color_hex + ';color:' + text_color + ';" ><span ></span><span ></span></li>';
			}
		});

		return choose_bg;
	}

	function set_bg_selected(color_hex, color_name, container) {
		$container = jQuery(container);
		var color_selected = get_bg_selected(container);

		if (color_selected == false) {
			color_selected = {};
		}
		if (!check_is_use(color_hex, container)) {
			color_selected[color_hex] = color_name;
		}
		$container.find('.shirt_color_seleted').val(JSON.stringify(color_selected));

	}

	function check_is_use(color, el) {
		var is_use = false;
		var color_selected = get_bg_selected(el);
		if (color_selected == false) {
			return false;
		}
		jQuery.each(get_bg_selected(el), function (key, value) {
			if (color == key) {
				is_use = true;
			}
		});
		return is_use;
	}

	function get_bg_selected(container) {
		$container = jQuery(container);
		if ($container.find('.shirt_color_seleted').val() != '') {
			return JSON.parse($container.find('.shirt_color_seleted').val());
		}
		return false;
	}
	function get_current_change(container,color_2,color_2_name){
		$container = jQuery(container);

		var color_1 = $container.find('.ssp_color > .shirt-color-sample.js-color.current').data('value');


		var color_selected_new ={};
		jQuery.each(get_bg_selected(container), function (key, value) {
			if (color_1 != key) {
				color_selected_new[key]=value;
			}else{
				if(!check_is_use(color_2, container)){
					color_selected_new[color_2]=color_2_name;
				}
			}
		});


		$container.find('.shirt_color_seleted').val(JSON.stringify(color_selected_new));

	}
	function show_bg_shirt(container, color_hex) {
		$container = jQuery(container);
		var choose_bg = '';
		var counter = 0;
		jQuery.each(get_bg_selected($container), function (key, value) {
			counter++;
		});
		jQuery.each(get_bg_selected($container), function (key, value) {

			var current_class = '';
			if (key == color_hex || counter == 1) {
				current_class = "current";
			}
			choose_bg += '<li data-value="' + key + '" data-texture="" class="shirt-color-sample js-color ' + current_class + '" title="' + value + '" style="background-color:#' + key + ';color:white;" ><span ></span><span ></span></li>';
		});
		for (i = 1; i <= 5 - counter; i++) {
			choose_bg += '<li data-value="none"  class="shirt-color-nocolor js-color" title="" style="background-color:transparent;" ><span></span><span></span></li>';
		}
		choose_bg += '<div class="color-picker color_picker_multi js-color-picker-multi" title="Change colour"><div class="color_picker_multi__more" ></div></div>';
		$container.find('.ssp_color').html(choose_bg);

	}

	function set_shirt_colour(current_color, container) {

		if (current_color == false) {
			var color_hex = get_shirt_colour(container);
		} else {
			var color_hex = current_color;
		}

		var color_name = '';
		var product_id = jQuery(container).attr('product_id');

		var index_color_hex = '';
		jQuery.each(colors_design[product_id]['color_hex'], function (key, value) {
			if (value == color_hex) {
				index_color_hex = key;
			}
		});
		color_name = colors_design[product_id]['color_title'][index_color_hex];
		set_bg_selected(color_hex, color_name, container);
		show_bg_shirt(container, color_hex);
	}

	// Add new design
	function get_product_id(index_parent) {
		var product_id = false;
		jQuery.each(products_design, function (index, value) {
			if (index_parent == index) {
				product_id = value['id'];
			}
		});
		return product_id;
	}

	function check_design_exist(index) {
		var prodct_id_current = get_product_id(index);
		var exist = false;
		jQuery(".box_design").each(function (index) {
			if (jQuery(this).attr('product_id') == prodct_id_current) {
				exist = true;
			}
		});
		return exist;

	}

	function show_product_select(container) {
		var div_before = '<div class="ssp_product_select" ><div id="ui" class="ssp_select_boxes"><select id="item-options-dropdown" class="form-control form__select_menu select_product_group" >';
		var div_after = '</select><button type="button" class="button button--primary ssp_add_button" title="Add" ></button></div></div>';
		var options = '';
		jQuery.each(products_design, function (index, value) {
			if (!check_design_exist(index)) {
				options += '<option value="' + value['id'] + '" name="' + value['title'] + '" >' + value['title'] + '</option>';
			}
		});
		jQuery(container).html(div_before + options + div_after);
	}

	function change_color_shirt(color_hex, container) {
		var index = get_index_color(color_hex, container);

		design.products.changeColorExtra(jQuery('#product-list-colors span').eq(index), index);
	}

	function get_index_color(color_hex, container) {
		var product_id = jQuery(container).attr('product_id');
		var index_return = 0;
		jQuery.each(colors_design[product_id]['color_hex'], function (index, value) {
			if (color_hex == value) {
				index_return = index;
			}

		});

		return index_return;
	}

	jQuery(document).on('click', '.containertip .shirt-color-sample.selected.js-color', function (e) {
		$_container = jQuery(this).parents(".box_design");
		var color_current = get_shirt_colour($_container);

		var color_hex = jQuery(this).data('value');
		//color_hex = color_hex.substring(1);
		if ($_container.find(".ssp_color  >  li.shirt-color-sample").length > 1) {
			remove_bg_selected(color_hex, $_container);
			close_popup_color();

			if (color_current == color_hex) {
				var color_hex_last = $_container.find(".ssp_color  >  li.shirt-color-sample").last().data('value');
				change_color_shirt(color_hex_last, $_container);
				$_container.find(".ssp_color  >  li.shirt-color-sample").last().addClass('current');

			} else if ($_container.find(".ssp_color  >  li.shirt-color-sample").length == 1) {
				var color_hex_last = $_container.find(".ssp_color  >  li.shirt-color-sample").first().data('value');
				change_color_shirt(color_hex_last, $_container);
			}

		}
		return false;
	});
	function select_current_color(color_hex, $_container) {
		jQuery($_container).find('.ssp_color > li.shirt-color-sample').removeClass('current');
		jQuery($_container).find('.ssp_color > li.shirt-color-sample').each(function () {
			var color = jQuery(this).data('value');
			if (color == color_hex) {
				jQuery(this).addClass('current');
			}
		});
	}

	jQuery(document).on('click', '.ssp_color > li.shirt-color-sample', function () {
		$_container = jQuery(this).parents(".box_design");
		var color_hex = jQuery(this).data('value');
		change_color_shirt(color_hex, $_container);
		select_current_color(color_hex, $_container)
		return false;
	});
	jQuery(document).on("mouseenter", '.ssp_color > li.shirt-color-sample', function () {
		var container = jQuery(this).parents(".box_design");
		if (container.find(".ssp_color  >  li.shirt-color-sample").length > 1) {
			jQuery(this).children("span:last-child").replaceWith('<div class="shirt-color-delete" title="Remove" >X</div>');
		}

	});
	jQuery(document).on("mouseleave", '.ssp_color > li.shirt-color-sample', function () {
		jQuery(this).children("div:last-child").replaceWith('<span ></span>');

	});
	jQuery(document).on('click', '.shirt-color-delete', function () {
		$_container = jQuery(this).parents(".box_design");
		var color_hex = jQuery(this).parent().data('value');
		var color_current = design.exports.productColor();

		remove_bg_selected(color_hex, $_container);

		var str_color = jQuery("#color_image_check").val();
		str_color = str_color.replace(color_hex, "");
		str_color = str_color.replace(",,", ",");
		jQuery("#color_image_check").val(str_color);

		close_popup_color();
		console.log('shirt-color-delete');
		console.log(color_current);
		console.log(color_hex);
		console.log(design.exports.productColor());

		if (color_current == color_hex) {

			var color_hex_last = $_container.find(".ssp_color  >  li.shirt-color-sample").last().data('value');
			console.log(color_hex_last);
			change_color_shirt(color_hex_last, $_container);
			$_container.find(".ssp_color  >  li.shirt-color-sample").last().addClass('current');
		} else if ($_container.find(".ssp_color  >  li.shirt-color-sample").length == 1) {
			var color_hex_last = $_container.find(".ssp_color  >  li.shirt-color-sample").first().data('value');
			change_color_shirt(color_hex_last, $_container);

		}
		return false;
	});


	var add_style_content = '<div class="style_adder" ><div class="style_adder__panel"><div class="style_adder__cta" >Add Style</div>										<div class="style_adder__description" >Optimize your campaign by adding an additional style</div></div></div>';
	jQuery(document).on('click', '.ssp_trashcan', function (e) {
		jQuery(this).parents(".box_design").remove();
		if (jQuery('.box_design.active').length == 0) {
			jQuery('.box_design').last().addClass('active');
			load_design_active();
		}
		if (jQuery('.style_adder').length < 1) {
			jQuery(add_style_content).insertAfter(jQuery('.box_design').last());
		}

	});
	jQuery(document).on('click', '.box_design', function (e) {
		if (!jQuery(this).hasClass('active')) {

			jQuery('.box_design').removeClass('active');
			jQuery(this).addClass('active');
			var product_id_active = jQuery(this).attr('product_id');
			product_id = product_id_active;
			load_design_active();

		}
	});

	jQuery(document).on('click', '.ssp_add_button', function (e) {

		jQuery('.box_design').last().clone().insertAfter(jQuery('.box_design').last());
		if (jQuery('.box_design').last().find('.ssp_trashcan').length == 0) {
			jQuery('<div class="ssp_trashcan" ></div>').insertAfter(jQuery('.box_design').last().find('.ssp_color'));

		}

		var product_id_active = jQuery(this).parents(".style_adder").find('#item-options-dropdown').val();
		console.log(jQuery(this).parents(".style_adder").find('#item-options-dropdown'));
		console.log('product_id');
		console.log(product_id);
		jQuery('.box_design').removeClass('active');
		jQuery(jQuery('.box_design').last()).addClass('active').attr('product_id', product_id_active);
		product_id = product_id_active;
		jQuery(jQuery('.box_design').last()).find('.shirt_color_seleted').val('');
		jQuery(jQuery('.box_design').last()).find('.ssp_color').html('');
		jQuery(this).parents(".style_adder").remove();
		load_design_active();
	});

	function load_design_active() {



		//console.log(fancyProductDesigner.getProduct());
		var container = jQuery('.box_design.active');
		var product_id_active = jQuery(container).attr('product_id');
		console.log(product_id_active);
		design.products.changeDesignExtra(product_id_active);

		if (jQuery(container).find('.ssp_color > li.shirt-color-sample.current').length) {
			var current_color = jQuery(container).find('.ssp_color > li.shirt-color-sample.current').first().data('value');
		} else {
			var current_color = false;
		}
		set_shirt_colour(current_color, container);
		change_color_shirt(current_color, container);
	}

	jQuery(document).on('click', '.style_adder', function (e) {
		if (!jQuery(this).has("button").length && jQuery('.style_adder').length < 2) {
			var design_length = jQuery('.box_design').length;
			var fancy_counter = 0;
			jQuery.each(products_design, function (index, value) {
				fancy_counter++;
			});
			if (design_length + 1 < fancy_counter) {
				jQuery(this).clone().insertAfter(this);
			}
			show_product_select(this);
		}
		window.parent.setHeigh(jQuery('#body_design_now').height());
	});
	jQuery(document).on('click', '.campaign_price', function (e) {
		return false;

	});

	jQuery(document).on('paste keyup', '#campaign_price', function (e) {
		jQuery("#edited_price").val(jQuery(document).find('#campaign_price').val());
		return false;
	});

	jQuery(document).on('change', '.campaign_price', function () {
		design.ajax.calculatorProfit(parseInt(jQuery('#sales_goal_input').val()));
	});

	jQuery('#campaign-info input').keydown(function (event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});
	function active_step(index) {
		if (index == 1) {
			is_allow_sticky = false;
			jQuery('#masthead').removeClass('sticky');
			jQuery('.footer_banner').css('display', 'none');
		} else {

			if(jQuery('#dg-popover').length>0){
				jQuery('#dg-popover').css('display', 'none');

			}
			is_allow_sticky = true;
			jQuery('.footer_banner').css('display', 'block');
		}
		var panel_no = index;
		jQuery('#launch-progression li').removeClass('active-step');
		var current = jQuery('#launch-progression li').get(index - 1);
		jQuery(current).addClass('active-step');
		jQuery('.panel').css('display', 'none');
		jQuery('.panel').each(function () {
			if (jQuery(this).hasClass('panel_' + panel_no)) {
				jQuery(this).css('display', 'block');
			}
		});
		validate_campaign.resetForm();
		design.ajax.addJs(this);
		window.parent.setHeigh(jQuery('#body_design_now').height());

		jQuery('.box_design').each(function () {
			set_shirt_colour(false, this);
		});

	}
	function get_shirt_colours(el) {
		var counter = 0;
		var colors = {};
		jQuery(el).find('.ssp_color .shirt-color-sample').each(function () {
			var color_title = jQuery(this).attr('title');
			colors[counter] = color_title;
			counter++;
		});

		return colors;
	}
	function get_array_sizes(product_id){
		var list_size = [];

		var index_size = 0;
		jQuery.each( design.products.product[product_id].attributes, function( key, value ) {
			if(key=='name'){
				jQuery.each( value, function( key, value ) {
					if(value=='Size'){
						index_size= key;
					}
				});
			}
			if(key=='titles'){
				list_size = value[index_size];
			}

		});
		/*
		 jQuery(design.products.product[product_id].size).find('td strong').each(function (index) {
		 if (jQuery(this).css('text-align') == 'center') {
		 list_size[count] = jQuery(this).html();
		 count++;
		 }
		 });
		 */
		return list_size;
	}
	function get_shirt_colour(container) {
		var product_id = jQuery(container).attr('product_id');
		var current_color;
		if(jQuery(container).find('.ssp_color > .shirt-color-sample.js-color.current').length>0){
			current_color = jQuery(container).find('.ssp_color > .shirt-color-sample.js-color.current').data('value');
		}else{
			current_color = colors_design[product_id]['color_hex'][0];
		}
		return current_color;
	}
	jQuery(document).ready(function () {
		get_products();
		design.products.productCate();




		jQuery(document).on('click', '.js-color-picker-multi, .shirt-color-nocolor', function () {
			$_container = jQuery(this).parents(".box_design");

			if ($_container.find('.colors.shirt-colors.containertip').length <= 0) {
				var html_color_pick = '<div class="colors shirt-colors containertip  containertip--open js-colors-popup" ><ul class="colors-in-use"></ul><ul class="colors-available">' + show_color_pick($_container) + '</ul></div>';
				$_container.find('.color_picker_multi__more').after(html_color_pick);
			}
			return false;

		});
		jQuery(document).on('click', '.containertip .shirt-color-sample.js-color', function (e) {
			var container = jQuery(this).parents(".box_design");
			if (!jQuery(this).hasClass('selected')) {
				var color_name = jQuery(this).attr('title');
				var color_hex = jQuery(this).data('value');
				console.log(color_name);
				console.log(color_hex);
				if (!container.hasClass('active')) {
					jQuery('.box_design').removeClass('active');
					container.addClass('active');
					set_bg_selected(color_hex, color_name, container);
					close_popup_color();
					show_bg_shirt(container, color_hex);
					change_color_shirt(color_hex, container);
				} else {
					set_bg_selected(color_hex, color_name, container);
					close_popup_color();
					show_bg_shirt(container, color_hex);
					change_color_shirt(color_hex, container)

				}

			}
			return false;
		});


		// Validate campaign form
		validate_campaign = jQuery("#campaign-info").validate({
			rules: {
				name: "required",
				content: "required",
				sales_goal: {
					required: true,
					digits: true,
					min: 15,
					max: 1000
				}
				,
				price: {
					required: true,
					number: true,
					min: function () {
						return 1;
						//return parseFloat(jQuery("#cost_price").val())
					}
				}
			},
			messages: {
				name: {
					required: "Please enter a campaign title"
				},
				content: {
					required: "Please enter a description"
				}
				,
				price: {
					required: "Please enter a selling price",
					min: function () {
						return '$' + jQuery("#cost_price").val() + " minimum"
					}
				}
			},
			errorPlacement: function (error, element) {
				if (element.attr("name") == "sales_goal") {
					error.insertAfter(".sales_goal_content");
				} else if (element.attr("name") == "price") {
					error.insertAfter(".price_content_3  .input-group ");

				} else {
					error.insertAfter(element);
				}
			}
		});

		jQuery('#launch-progression a, .button_transaction').not('.campaign_submit').click(function () {
			//console.log(JSON.parse(window.localStorage.getItem('fancy-product-designer-16')));
			active_step(jQuery(this).data('step'));
		});

		// Initial Popup
		jQuery(".login_popup").colorbox({
			inline: true, width: "50%",
			close: '×',
			onClosed: function () {
				if (login_success)
					jQuery(".loading_popup").click();
			}
		});
		jQuery(".loading_popup").colorbox({inline: true, width: "50%", close: '×'});

		// Initial Switch register & login
		jQuery(".switch_login_register").click(function () {
				jQuery(".toggle_div").toggle();
				jQuery(".login_popup").colorbox.resize();
				validate_login.resetForm();
				validate_register.resetForm();

			}
		);

		// Validate form login
		var validate_login = jQuery("#ajax_login").validate({
			rules: {
				username: "required",
				password: "required"
			},
			messages: {
				username: "Please enter a username",
				password: "Please enter a password"
			}


		});
		// Validate form register
		var validate_register = jQuery("#ajax_register").validate({
				rules: {
					user_login: "required",
					user_email: {
						required: true,
						email: true
					},
					user_pass: {
						required: true,
						minlength: 6
					},
					user_pass_confirm: {
						required: true,
						minlength: 6,
						equalTo: "#user_pass"
					}
				},
				messages: {
					user_pass: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long"
					},
					user_pass_confirm: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long",
						equalTo: "Please enter the same password as above"
					},
					user_email: "Please enter a valid email address",
					user_login: "Please enter a username"
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element);
					jQuery(".login_popup").colorbox.resize();
				}
			}
		);


		// Submit champagin ajax
		function submit_campaign() {
			tinyMCE.triggerSave();
			//var data_info = jQuery("#campaign-info").serialize();
			var data_design = {};
			//console.log(design.products.product);
			jQuery('.box_design').each(function (index) {
				var product_id = jQuery(this).attr('product_id');
				data_design[index] = {
					'design_name': jQuery(this).find('.design-fpd-title').html(),
					'cost_price': jQuery(this).find('#cost_price').val(),
					'price': jQuery(this).find('#campaign_price').val(),
					'colors': get_shirt_colours(this),
					'sizes': get_array_sizes(product_id),
					'image_back': jQuery(this).find('#design_image_back').val(),
					'image_front': jQuery(this).find('#design_image_front').val(),
					'all_image_back': jQuery(this).find('#all_design_image_back').val(),
					'all_image_front': jQuery(this).find('#all_design_image_front').val(),
					'color_check' : jQuery(this).find('#color_image_check').val()
				};
			});


			var data_submit = {
				'sales_goal': jQuery('#sales_goal').val(),
				'name': jQuery('#campaign_name').val(),
				'design_id': <?php
				echo $_GET['product']; ?>,
				'content': jQuery('#campaign_content').val(),
				'campaign_length': jQuery('#length').val(),
				'feature_side': jQuery('input[name=feature_side]:checked').val(),
				'design': data_design
			}


			var $submit_loading = jQuery(".submit_loading");
			$submit_loading.show();
			$submit_loading.css('visibility', 'visible');
			$submit_loading.css('opacity', '1');
			jQuery('#panel-success').html('');
			jQuery('#panel-success').hide();
			var json_product = JSON.stringify('');

			jQuery.ajax({
				type: "POST",
				url: ajax_login_object.ajaxurl,
				data: {
					'action': "postCampaign",
					'design_data': encodeURIComponent(''),
					'view_size': jQuery('.fpd-views-selection').children().size(),
					'data': JSON.stringify(data_submit),
					'products': json_product
				},
				success: function (response) {
					$submit_loading.hide();
					if (response.status == 'success') {
						window.parent.success_redirect(response.product_link);
						var html = '<h5>The campaign has been created.</h5>';
					} else {
						var html = '<h5 style="color: red;">' + response.message + '</h5>';
					}

					jQuery('#panel-success').html(html);
					jQuery('#panel-success').show();
				},
				error: function (data) {
					$submit_loading.hide();
				}
			});
		}

		// Register ajax add event
		jQuery('#ajax_register .button').click(function (e) {

			if (!jQuery("#ajax_register").valid()) {
				jQuery(".login_popup").colorbox.resize();
				return false;
			}
			var data_register = jQuery("#ajax_register").serialize();
			jQuery.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajax_login_object.ajaxurl,
				data: {
					'action': 'ajaxRegister', //calls wp_ajax_nopriv_ajaxlogin
					'user_login': jQuery('form#ajax_register #user_login').val(),
					'user_email': jQuery('form#ajax_register #user_email').val(),
					'user_pass': jQuery('form#ajax_register #user_pass').val(),
					'user_pass_confirm': jQuery('form#ajax_register #user_pass_confirm').val(),
					'security': jQuery('form#ajax_register #security').val()
				},
				success: function (data) {
					if (data.status == 'error') {
						var html_error = "<span class='error_message'>" + data.message + "</span>";
						jQuery('form#ajax_register p.status').html(html_error);
						jQuery(".login_popup").colorbox.resize();
					}
					if (data.status == 'success') {
						login_success = true;
						jQuery(".login_popup").colorbox.close();
						submit_campaign();
					}
				}
			});
			e.preventDefault();
		});

		// Login ajax add event
		jQuery('#ajax_login .button').click(function (e) {

			if (!jQuery("#ajax_login").valid()) {
				jQuery(".login_popup").colorbox.resize();
				return false;
			}

			var data_login = jQuery("#ajax_login").serialize();
			jQuery.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajax_login_object.ajaxurl,
				data: {
					'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
					'username': jQuery('form#ajax_login #username').val(),
					'password': jQuery('form#ajax_login #password').val(),
					'security': jQuery('form#ajax_login #security').val()
				},
				success: function (data) {
					jQuery('form#ajax_login p.status').text(data.message);
					if (data.loggedin == true) {
						login_success = true;
						jQuery(".login_popup").colorbox.close();
						submit_campaign();
					}
				}
			});
			e.preventDefault();
		});
		jQuery('input[name=feature_side]').click(function () {

			$_design = jQuery('.box_design.active');
			var front = decodeURIComponent($_design.find('#design_image_front').val());
			var back = decodeURIComponent($_design.find('#design_image_back').val());


			if (jQuery('input[name=feature_side]:checked').val() == 'front') {
				jQuery('.design_view.back').css('background-image', "url('" + back + "')");
				jQuery('.design_view.front').css('background-image', "url('" + front + "')");
			} else {
				jQuery('.design_view.back').css('background-image', "url('" + front + "')");
				jQuery('.design_view.front').css('background-image', "url('" + back + "')");
			}
		});
		// Add event ajax submit campaign
		jQuery('.campaign_submit').click(function () {

			if (!jQuery("#campaign-info").valid()) {
				return false;
			}

			var data = {
				action: 'is_user_logged_in'
			};
			jQuery.post(ajax_login_object.ajaxurl, data, function (response) {
				if (response == 'yes') {
					jQuery(".loading_popup").click();
					submit_campaign();
				} else {
					jQuery(".login_popup").click();
				}
			});
			return false;
		});
		var ionslider = jQuery("#sales_goal").ionRangeSlider({
			min: 0,
			from_shadow: true,
			from_min: 15,
			max: 1000,
			grid: true,
			grid_num: 10,
			hide_min_max: true,
			from: 15,
			onFinish: function (data) {

				if (data.from < 15) {
					var ionslider = $("#sales_goal").data("ionRangeSlider");
					ionslider.update({from: 15});
					jQuery('#sales_goal_input').val(15);
					design.ajax.getPrice();
					design.ajax.calculatorProfit(15);
				} else {
					jQuery('#sales_goal_input').val(data.from);
					design.ajax.getPrice();
					design.ajax.calculatorProfit(data.from);
				}
			},
			onChange: function (data) {

			}
		});
		jQuery("#sales_goal_input").change(function () {
			var ionslider = jQuery("#sales_goal").data("ionRangeSlider");
			if (jQuery(this).val() < 15) {
				ionslider.update({from: parseInt(15)});
				design.ajax.getPrice();
				design.ajax.calculatorProfit(15);
			} else {
				design.ajax.getPrice();
				design.ajax.calculatorProfit(jQuery(this).val());

				ionslider.update({from: parseInt(jQuery(this).val())});
			}
		});
	});

</script>

<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/ion.rangeSlider-2.1.2/css/normalize.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/ion.rangeSlider-2.1.2/css/ion.rangeSlider.css">
<link rel="stylesheet"
	  href="<?php echo get_template_directory_uri() ?>/ion.rangeSlider-2.1.2/css/ion.rangeSlider.skinModern.css">

<script
	src="<?php echo get_template_directory_uri() ?>/ion.rangeSlider-2.1.2/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>


<!-- Latest compiled and minified JavaScript -->
<script src="<?php echo get_template_directory_uri() ?>/jquery-validation/jquery.validate.min.js"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/jquery-validation/css/cmxform.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/jquery-validation/css/cmxformTemplate.css">


<script type='text/javascript' src='<?php echo get_home_url(); ?>/wp-includes/js/utils.min.js?ver=4.4.2'></script>
<script type='text/javascript' src='<?php echo get_home_url(); ?>/wp-admin/js/editor.min.js?ver=4.4.2'></script>
<script type='text/javascript'>
	/* <![CDATA[ */
	var quicktagsL10n = {
		"closeAllOpenTags": "Close all open tags",
		"closeTags": "close tags",
		"enterURL": "Enter the URL",
		"enterImageURL": "Enter the URL of the image",
		"enterImageDescription": "Enter a description of the image",
		"textdirection": "text direction",
		"toggleTextdirection": "Toggle Editor Text Direction",
		"dfw": "Distraction-free writing mode",
		"strong": "Bold",
		"strongClose": "Close bold tag",
		"em": "Italic",
		"emClose": "Close italic tag",
		"link": "Insert link",
		"blockquote": "Blockquote",
		"blockquoteClose": "Close blockquote tag",
		"del": "Deleted text (strikethrough)",
		"delClose": "Close deleted text tag",
		"ins": "Inserted text",
		"insClose": "Close inserted text tag",
		"image": "Insert image",
		"ul": "Bulleted list",
		"ulClose": "Close bulleted list tag",
		"ol": "Numbered list",
		"olClose": "Close numbered list tag",
		"li": "List item",
		"liClose": "Close list item tag",
		"code": "Code",
		"codeClose": "Close code tag",
		"more": "Insert Read More tag"
	};
	/* ]]> */
</script>
<script type='text/javascript' src='<?php echo get_home_url(); ?>/wp-includes/js/quicktags.min.js?ver=4.4.2'></script>
<script type='text/javascript'>
	/* <![CDATA[ */
	var wpLinkL10n = {
		"title": "Insert\/edit link",
		"update": "Update",
		"save": "Add Link",
		"noTitle": "(no title)",
		"noMatchesFound": "No results found."
	};
	/* ]]> */
</script>
<script type='text/javascript' src='<?php echo get_home_url(); ?>/wp-includes/js/wplink.min.js?ver=4.4.2'></script>

<script type="text/javascript">
	tinyMCEPreInit = {
		baseURL: "<?php echo get_home_url(); ?>/wp-includes/js/tinymce",
		suffix: ".min",
		mceInit: {
			'campaign_content': {
				theme: "modern",
				skin: "lightgray",
				language: "en",
				formats: {
					alignleft: [{
						selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
						styles: {textAlign: "left"}
					}, {selector: "img,table,dl.wp-caption", classes: "alignleft"}],
					aligncenter: [{
						selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
						styles: {textAlign: "center"}
					}, {selector: "img,table,dl.wp-caption", classes: "aligncenter"}],
					alignright: [{
						selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
						styles: {textAlign: "right"}
					}, {selector: "img,table,dl.wp-caption", classes: "alignright"}],
					strikethrough: {inline: "del"}
				},
				relative_urls: false,
				remove_script_host: false,
				convert_urls: false,
				browser_spellcheck: true,
				fix_list_elements: true,
				entities: "38,amp,60,lt,62,gt",
				entity_encoding: "raw",
				keep_styles: false,
				cache_suffix: "wp-mce-4208-20151113",
				preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
				end_container_on_empty_block: true,
				wpeditimage_disable_captions: false,
				wpeditimage_html5_captions: false,
				plugins: "colorpicker,lists,fullscreen,image,wordpress,wpeditimage,wplink",
				wp_lang_attr: "en-AU",
				content_css: "http://www.teem8.com.au/wp-includes/css/dashicons.min.css?ver=4.4.2,http://www.teem8.com.au/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.4.2",
				selector: "#campaign_content",
				resize: "vertical",
				menubar: false,
				wpautop: true,
				indent: false,
				toolbar1: "bold,italic,underline,blockquote,strikethrough,bullist,numlist,alignleft,aligncenter,alignright,undo,redo,link,unlink,fullscreen",
				toolbar2: "",
				toolbar3: "",
				toolbar4: "",
				tabfocus_elements: ":prev,:next",
				body_class: "campaign_content locale-en-au"
			}
		},
		qtInit: {
			'campaign_content': {
				id: "campaign_content",
				buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
			}
		},
		ref: {
			plugins: "colorpicker,lists,fullscreen,image,wordpress,wpeditimage,wplink",
			theme: "modern",
			language: "en"
		},
		load_ext: function (url, lang) {
			var sl = tinymce.ScriptLoader;
			sl.markDone(url + '/langs/' + lang + '.js');
			sl.markDone(url + '/langs/' + lang + '_dlg.js');
		}
	};
</script>
<script type='text/javascript'
		src='<?php echo get_home_url(); ?>/wp-includes/js/tinymce/tinymce.min.js?ver=4208-20151113'></script>
<script type='text/javascript'
		src='<?php echo get_home_url(); ?>/wp-includes/js/tinymce/plugins/compat3x/plugin.min.js?ver=4208-20151113'></script>
<script type='text/javascript'>
	tinymce.addI18n('en', {
		"Align center": "Align centre",
		"Ok": "OK",
		"Bullet list": "Bulleted list",
		"Color": "Colour",
		"Custom color": "Custom colour",
		"No color": "No colour",
		"Spellcheck": "Check Spelling",
		"Row properties": "Table row properties",
		"Cell properties": "Table cell properties",
		"Border color": "Border colour",
		"Paste row before": "Paste table row before",
		"Paste row after": "Paste table row after",
		"Cut row": "Cut table row",
		"Copy row": "Copy table row",
		"Merge cells": "Merge table cells",
		"Split cell": "Split table cell",
		"Center": "Centre",
		"Background color": "Background colour",
		"Text color": "Text colour",
		"Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.": "Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.\n\nIf you\u2019re looking to paste rich content from Microsoft Word, try turning this option off. The editor will clean up text pasted from Word automatically.",
		"Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help": "Rich Text Area. Press Alt-Shift-H for help",
		"You have unsaved changes are you sure you want to navigate away?": "The changes you made will be lost if you navigate away from this page.",
		"Your browser doesn't support direct access to the clipboard. Please use the Ctrl+X\/C\/V keyboard shortcuts instead.": "Your browser does not support direct access to the clipboard. Please use keyboard shortcuts or your browser\u2019s edit menu instead.",
		"Edit ": "Edit"
	});
	tinymce.ScriptLoader.markDone('<?php echo get_home_url(); ?>/wp-includes/js/tinymce/langs/en.js');
</script>
<script type='text/javascript'>
	tinymce.addI18n('en', {
		"Align center": "Align centre",
		"Ok": "OK",
		"Bullet list": "Bulleted list",
		"Color": "Colour",
		"Custom color": "Custom colour",
		"No color": "No colour",
		"Spellcheck": "Check Spelling",
		"Row properties": "Table row properties",
		"Cell properties": "Table cell properties",
		"Border color": "Border colour",
		"Paste row before": "Paste table row before",
		"Paste row after": "Paste table row after",
		"Cut row": "Cut table row",
		"Copy row": "Copy table row",
		"Merge cells": "Merge table cells",
		"Split cell": "Split table cell",
		"Center": "Centre",
		"Background color": "Background colour",
		"Text color": "Text colour",
		"Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.": "Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.\n\nIf you\u2019re looking to paste rich content from Microsoft Word, try turning this option off. The editor will clean up text pasted from Word automatically.",
		"Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help": "Rich Text Area. Press Alt-Shift-H for help",
		"You have unsaved changes are you sure you want to navigate away?": "The changes you made will be lost if you navigate away from this page.",
		"Your browser doesn't support direct access to the clipboard. Please use the Ctrl+X\/C\/V keyboard shortcuts instead.": "Your browser does not support direct access to the clipboard. Please use keyboard shortcuts or your browser\u2019s edit menu instead.",
		"Edit ": "Edit"
	});
	tinymce.ScriptLoader.markDone('<?php echo get_home_url(); ?>/wp-includes/js/tinymce/langs/en.js');
</script>
<script type="text/javascript">
	var ajaxurl = "/wp-admin/admin-ajax.php";
	( function () {
		var init, id, $wrap;

		if (typeof tinymce !== 'undefined') {
			for (id in tinyMCEPreInit.mceInit) {
				init = tinyMCEPreInit.mceInit[id];
				$wrap = tinymce.$('#wp-' + id + '-wrap');

				if (( $wrap.hasClass('tmce-active') || !tinyMCEPreInit.qtInit.hasOwnProperty(id) ) && !init.wp_skip_init) {
					tinymce.init(init);

					if (!window.wpActiveEditor) {
						window.wpActiveEditor = id;
					}
				}
			}
		}

		if (typeof quicktags !== 'undefined') {
			for (id in tinyMCEPreInit.qtInit) {
				quicktags(tinyMCEPreInit.qtInit[id]);

				if (!window.wpActiveEditor) {
					window.wpActiveEditor = id;
				}
			}
		}
	}());
</script>
</body>
</html>