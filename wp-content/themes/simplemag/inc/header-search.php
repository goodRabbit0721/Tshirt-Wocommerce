<?php
/**
 * Defult Header
 * Left align logo, right aligned search and social profiles
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/
global $ti_option;
?>

<div class="header header-search">

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
        // Social Profiles
        if( $ti_option['top_social_profiles'] == 1 ) { ?>
        <div class="inner-cell">
        	<?php get_template_part ( 'inc/social', 'profiles' ); ?>
        </div>
        <?php } ?>
		
		<div class="inner-cell search-form-cell">
			<?php 
            // Search Form
            get_search_form(); ?>
        </div>
    </div>

</div><!-- .header-search -->