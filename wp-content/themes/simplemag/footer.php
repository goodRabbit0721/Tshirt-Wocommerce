<?php
/**
 * The template for displaying the footer.
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
global $ti_option;

global $post;


$product = wc_get_product( $post->ID );
$is_design_now =$post->ID==2167;

//echo $post->ID;

global $wp;
// $paramatters = split('[/.-]', $wp->request);
// if($paramatters[0] == 'shop' || $post->ID == 1896) {

//   $is_design_now = false;


// }

?>		
<?php if (!$is_design_now) { ?>
		<div class="wrap-footer">
			<div class="wrapper">
			<div class="row">
				<?php echo do_shortcode( '[static_block_content id="2596"]' ); ?>
			</div>
			</div>
		</div>
<?php } ?>
        <footer id="footer" class="no-print animated" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/WPFooter">

        	<?php if ( $ti_option['footer_image_ad']['url'] == true || ! empty ( $ti_option['footer_code_ad'] ) ) { ?>
            <div class="advertisement">
                <div class="wrapper">
        			<?php
                    $footer_ad = $ti_option['footer_image_ad'];
                    // Image Ad
                    if ( $footer_ad['url'] == true ) { ?>
                        <a href="<?php echo $footer_ad['url']; ?>" rel="nofollow" target="_blank">
                            <img src="<?php echo $footer_ad['url']; ?>" width="<?php echo $footer_ad['width']; ?>" height="<?php echo $footer_ad['height']; ?>" alt="<?php _e( 'Advertisement', 'themetext' ); ?>" />
                        </a>
                    <?php 
        			// Code Ad
                    } elseif( $ti_option['footer_code_ad'] == true ) {
                        echo $ti_option['footer_code_ad'];
                    } ?>
                </div>
            </div><!-- .advertisment -->
            <?php } ?>

            <?php get_sidebar( 'footer' ); // Output the footer sidebars ?>
<?php


?>

			<?php
			/*
            <div class="footer_banner" style="<?php echo $is_design_now?'display: none;':''; ?>">
                <div class="wrapper">
					<div class="row">
						<div class="col-md-4 algin_left"><img src="<?php echo get_template_directory_uri()?>/images/startrack.png" /></div>
						<div class="col-md-4 algin_center"><img src="<?php echo get_template_directory_uri()?>/images/australian_owned.png" /></div>
						<div class="col-md-4 algin_right"><img src="<?php echo get_template_directory_uri()?>/images/pay_visa.png" /></div>
						<!--
						<div class="col-md-4 algin_right"><img src="<?php echo get_template_directory_uri()?>/images/paypal.png" /><img src="<?php echo get_template_directory_uri()?>/images/visa_master.png" /></div>
						-->
					</div>
                </div>
            </div>
           */
			?>
        </footer><!-- #footer -->
    </div><!-- .site-content -->
</section><!-- #site -->


<?php wp_footer(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/ion.rangeSlider-2.1.2/css/normalize.css" >
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/ion.rangeSlider-2.1.2/css/ion.rangeSlider.css" >
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/ion.rangeSlider-2.1.2/css/ion.rangeSlider.skinModern.css" >

<script src="<?php echo get_template_directory_uri()?>/ion.rangeSlider-2.1.2/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>


  <!-- Latest compiled and minified JavaScript -->
<script src="<?php echo get_template_directory_uri()?>/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri()?>/jquery-validation/jquery.validate.min.js"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/jquery-validation/css/cmxform.css" >
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/jquery-validation/css/cmxformTemplate.css" >


<?php

if(!$is_design_now):
?>
<script>
	jQuery(document).ready(function() {
 
var  mn = jQuery("#masthead");
    mns = "sticky";

jQuery(window).scroll(function() {
  

var documentHeight = jQuery(document).height(); //retrieve current document height
    
    var windowHeight = jQuery(window).height(); //retrieve current window height
    
    var scrollTop = jQuery(window).scrollTop();
  if( jQuery(this).scrollTop() > 0 && ((documentHeight - windowHeight) >165)) {
    mn.addClass(mns);
   // jQuery('#branding').css('display','none');
     
  } else if(jQuery(this).scrollTop() ==0) {

    mn.removeClass(mns);
    //jQuery('#branding').css('display','block');
  }
});
});
</script>
<?php
else:
?>
<script>
/*
jQuery(document).ready(function() {
var stickyNavTop = jQuery('#masthead').offset().top;
 
var stickyNav = function(){
	if(is_allow_sticky){
		var scrollTop = jQuery(window).scrollTop();      
		if (scrollTop > stickyNavTop+100) { 
			jQuery('#masthead').addClass('sticky');
		} else {
			jQuery('#masthead').removeClass('sticky'); 
		}
	}

};
 
stickyNav();
 
jQuery(window).scroll(function() {
    stickyNav();
});
});
*/
</script>
<?php
endif;
?>
<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-74693421-1', 'auto');
  ga('send', 'pageview');
</script>

 		
</body>
</html>