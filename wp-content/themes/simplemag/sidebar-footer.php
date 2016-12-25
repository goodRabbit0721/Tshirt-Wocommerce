<?php
/**
 * The template for displaying the footer.
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/

/* The footer widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
if (   ! is_active_sidebar( 'sidebar-3'  )
	&& ! is_active_sidebar( 'sidebar-4' )
	&& ! is_active_sidebar( 'sidebar-5'  )
)
	return;
// If we get this far, we have widgets. Let do this.
?>

<div class="footer-sidebar">
    <div id="supplementary" class="wrapper clearfix columns<?php ti_footer_sidebar_class(); ?>">
        <?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
        <div class="widget-area widget-area-1" role="complementary">
            <?php dynamic_sidebar( 'sidebar-3' ); ?>
        </div><!-- #first .widget-area -->
        <?php endif; ?>
    
        <?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
        <div class="widget-area widget-area-2" role="complementary">
            <?php dynamic_sidebar( 'sidebar-4' ); ?>
        </div><!-- #second .widget-area -->
        <?php endif; ?>
    
        <?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
        <div class="widget-area widget-area-3" role="complementary">
            <?php dynamic_sidebar( 'sidebar-5' ); ?>
        </div><!-- #third .widget-area -->
        <?php endif; ?>
    </div><!-- #supplementary -->
</div>