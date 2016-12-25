<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( isset($settings->theme) && isset($settings->theme->default) )
{
	$options = $settings->theme->default;
}
else
{
	$options = array();
}
?>
<link type="text/css" href="<?php echo 'themes/default/css/style.css'; ?>" rel="stylesheet" media="all" />
<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

<style type="text/css">
	
	/* background color */
	<?php if (isset($options->general_background) && $options->general_background != '') { ?>
	.container-fluid{background-color:<?php echo $options->general_background; ?>!important;}
	<?php } ?>
	
	/* background image */
	<?php if (isset($options->general_image) && $options->general_image != '') { ?>
	.container-fluid{background-image:url('<?php echo $options->general_image; ?>')!important;background-position: center center;}
	<?php } ?>
	
	/* text color */
	<?php if (isset($options->general_text_color) && $options->general_text_color != '') { ?>
	body{color:<?php echo $options->general_text_color; ?>}
	<?php } ?>
	
	/* left menu background */
	<?php if (isset($options->leftmenu_background) && $options->leftmenu_background != '') { ?>
	#dg-left .menu-left > li{background:<?php echo $options->leftmenu_background; ?>}
	<?php } ?>
	
	/* left menu border */
	<?php if (isset($options->leftmenu_border) && $options->leftmenu_border != '') { ?>
	.menu-left > li{border-bottom:1px solid <?php echo $options->leftmenu_border; ?>}
	#dg-left .dg-box:first-child{border:1px solid <?php echo $options->leftmenu_border; ?>}
	<?php } ?>
	
	/* left text color */
	<?php if (isset($options->leftmenu_text) && $options->leftmenu_text != '') { ?>
	#dg-left .menu-left > li a{color:<?php echo $options->leftmenu_text; ?>}	
	<?php } ?>
	
	/* left text color hover */
	<?php if (isset($options->leftmenu_texthover) && $options->leftmenu_texthover != '') { ?>
	#dg-left .menu-left > li a:hover{color:<?php echo $options->leftmenu_texthover; ?>}	
	<?php } ?>
	
	/* left icon color */
	<?php if (isset($options->leftmenu_icon) && $options->leftmenu_icon != '') { ?>
	#dg-left .menu-left li a i::before{color:<?php echo $options->leftmenu_icon; ?>}	
	<?php } ?>
	
	/* left icon color hover */
	<?php if (isset($options->leftmenu_iconhover) && $options->leftmenu_iconhover != '') { ?>
	#dg-left .menu-left > li a:hover i::before{color:<?php echo $options->leftmenu_iconhover; ?>}	
	<?php } ?>
	
	/* BEGIN BUTTON */
	<?php if (isset($options->button_background) && $options->button_background != '') { ?>
	.btn.btn-default{background-color:<?php echo $options->button_background; ?>}	
	<?php } ?>
	
	<?php if (isset($options->button_border) && $options->button_border != '') { ?>
	.btn.btn-default{border:1px solid <?php echo $options->button_border; ?>}	
	<?php } ?>
	
	<?php if (isset($options->button_text) && $options->button_text != '') { ?>
	.btn.btn-default{color:<?php echo $options->button_text; ?>}	
	<?php } ?>
	
	<?php if (isset($options->button_icon) && $options->button_icon != '') { ?>
	.btn.btn-default i::before{color:<?php echo $options->button_icon; ?>}	
	<?php } ?>
	
	
	/* BEGIN Accordion Box */
	<?php if (isset($options->box_head) && $options->box_head != '') { ?>
	.dg-box .ui-accordion .ui-accordion-header{background:<?php echo $options->box_head; ?>}	
	<?php } ?>
	
	<?php if (isset($options->box_content) && $options->box_content != '') { ?>
	.ui-accordion .ui-accordion-content{background:<?php echo $options->box_content; ?>}	
	<?php } ?>
	
	<?php if (isset($options->box_border) && $options->box_border != '') { ?>
	.dg-box{border:1px solid <?php echo $options->box_border; ?>}	
	.dg-box .ui-accordion .ui-accordion-header{border-bottom:1px solid <?php echo $options->box_border; ?>}	
	<?php } ?>
	
	<?php if (isset($options->box_text) && $options->box_text != '') { ?>
	.dg-box .ui-accordion .ui-accordion-header{color:<?php echo $options->box_text; ?>}	
	<?php } ?>
	
	<?php if (isset($options->box_content) && $options->box_content != '') { ?>
	.product-prices{background:<?php echo $options->box_content; ?>}	
	<?php } ?>
	
	
	/* fix mobile */
	@media screen and (max-width: 770px) {
		
		/* left menu background */
		<?php if (isset($options->leftmenu_background) && $options->leftmenu_background != '') { ?>
		#dg-left .menu-left li a i{background:<?php echo $options->leftmenu_background; ?>}
		#dg-left .menu-left > li{background:none;}
		<?php } ?>
		
		/* left menu border */
		<?php if (isset($options->leftmenu_border) && $options->leftmenu_border != '') { ?>
		.menu-left > li{border:0;}
		#dg-left .dg-box:first-child{border:0;}
		#dg-left .menu-left li a i{border:1px solid <?php echo $options->leftmenu_border; ?>;}
		#dg-popover > div.popover-content{border-bottom:1px solid <?php echo $options->leftmenu_border; ?>;border-top:1px solid <?php echo $options->leftmenu_border; ?>}
		<?php } ?>
		
		<?php if (isset($options->button_background) && $options->button_background != '') { ?>
		div.dg-options-toolbar{background-color:<?php echo $options->button_background; ?>}	
		#dg-popover .dg-options-toolbar .btn.active{color:<?php echo $options->button_text; ?>}	
		#dg-popover .dg-options-toolbar .btn.active i::before{color:<?php echo $options->button_text; ?>}	
		<?php } ?>	
		
		<?php if (isset($options->button_text) && $options->button_text != '') { ?>
		#dg-popover .dg-options-toolbar .btn.active{color:<?php echo $options->button_text; ?>}	
		#dg-popover .dg-options-toolbar .btn.active i::before{color:<?php echo $options->button_text; ?>}	
		<?php } ?>		
		
	}
</style>