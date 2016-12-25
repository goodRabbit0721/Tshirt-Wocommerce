<?php
/**
 * Defult Header
 * Left align logo, right aligned ad unit
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/
global $ti_option;
?>

<div class="header header-banner">

    <div class="inner">
        <div class="inner-cell">
    
            <a class="logo" href="<?php echo home_url( '/' ); ?>">
                <img src="<?php echo $ti_option['site_logo']['url']; ?>" alt="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>" width="<?php echo $ti_option['site_logo']['width']; ?>" height="<?php echo $ti_option['site_logo']['height']; ?>" />
            </a><!-- Logo -->
            
            <?php
            // Show or Hide site tagline under the logo based on Theme Options
            if( $ti_option['site_tagline'] == true ) {
            ?>
            <span class="tagline" itemprop="description"><?php bloginfo( 'description' ); ?></span>
            <?php } ?>
        
         </div>
     	
		<?php
        $header_ad = $ti_option['header_image_ad'];
        // Image Ad
        if ( $header_ad['url'] == true ) { ?>
        <div class="inner-cell">
            <div class="ad-block">
                <a href="<?php echo $ti_option['header_image_ad_url']; ?>" rel="nofollow" target="_blank">
                    <img src="<?php echo $header_ad['url']; ?>" width="<?php echo $header_ad['width']; ?>" height="<?php echo $header_ad['height']; ?>" alt="<?php _e( 'Advertisement', 'themetext' ); ?>" />
                </a>
             </div>
        </div>
        
		<?php 
        // Code Ad
        } elseif( $ti_option['header_code_ad'] == true ) { ?>
        <div class="inner-cell">
            <div class="ad-block">
        		<?php echo $ti_option['header_code_ad']; ?>
             </div>
        </div>
		<?php } ?>
     
	</div>
</div><!-- .header-banner -->