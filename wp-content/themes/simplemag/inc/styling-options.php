<?php
/**
 * Theme Styling Options Helpers
 * Refer to Theme Options
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.2
**/
function ti_custom_styling() { 
global $ti_option;
?>
<style type="text/css">
.main-menu > ul > li:hover > a {color:<?php echo $ti_option['main_menu_links_color']['hover']; ?>;}.secondary-menu ul > li:hover > a {color:<?php echo $ti_option['site_top_strip_links']['hover']; ?>;}.main-menu > ul > .link-arrow > a:after{border-color:transparent transparent <?php echo $ti_option['main_sub_menu_pointer']; ?>;}.main-menu > ul > li > .sub-menu{border-top-color:<?php echo $ti_option['main_sub_menu_pointer']; ?>;}.modern .content-over-image figure:before{opacity:<?php echo $ti_option['slider_tint_strength']; ?>;}.top-strip #searchform input, .top-strip #searchform button{color:<?php echo $ti_option['site_top_strip_links']['regular']; ?>}.modern .content-over-image:hover figure:before{opacity:<?php echo $ti_option['slider_tint_strength_hover']; ?>;}.main-menu .sub-menu .sub-links a:after{background-color:<?php echo $ti_option['main_sub_links_left']['regular']; ?>}.sidebar .widget{border-bottom:1px solid <?php echo $ti_option['sidebar_border']['border-color']; ?>;}.footer-sidebar .widget_rss li:after,.footer-sidebar .widget_pages li a:after,.footer-sidebar .widget_nav_menu li a:after,.footer-sidebar .widget_categories ul li:after, .footer-sidebar .widget_recent_entries li:after,.footer-sidebar .widget_recent_comments li:after{background-color:<?php echo $ti_option['footer_links']['regular']; ?>;}.footer-sidebar .widget_ti_latest_comments .comment-text:after{border-bottom-color:<?php echo $ti_option['footer_color']; ?>;}.footer-sidebar .widget_ti_latest_comments .comment-text:before{border-bottom-color:<?php echo $ti_option['footer_border']['border-color']; ?>;}.footer-sidebar .widget_ti_latest_comments .comment-text{border-color:<?php echo $ti_option['footer_border']['border-color']; ?>;}
.sub-menu-columns .sub-menu .sub-links > .menu-item-has-children > a {color:<?php echo $ti_option['main_sub_links_left']['hover']; ?>;}
<?php if ($ti_option['titles_background_switch'] == true && $ti_option['titles_background_image'] == true  ){ ?>
.title-with-sep{background:url("<?php echo get_template_directory_uri(); ?>/images/section-header.png") repeat-x 0 50%;}
<?php } ?>
<?php if ($ti_option['titles_background_switch'] == true && $ti_option['titles_background_image'] == false ){ ?>
.title-with-sep{background:url("<?php echo $ti_option['titles_background_upload']['url']; ?>") repeat-x 50%;}
<?php } ?>
@media only screen and (min-width: 751px) {#gallery-carousel,#gallery-carousel .gallery-item{height:<?php echo $ti_option['site_carousel_height'] ?>px;}}
<?php if ( $ti_option['custom_css'] != '' ) { ?>
/* Custom CSS */
<?php echo $ti_option['custom_css']; ?>
<?php } ?>
</style>
<?php } ?>
<?php add_action( 'wp_head', 'ti_custom_styling' ); ?>