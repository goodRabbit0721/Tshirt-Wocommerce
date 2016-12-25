<?php
/**
 * Content Post
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
?>

<article <?php post_class("grid-4"); ?> itemscope itemtype="http://schema.org/Article">

    <figure class="entry-image">
    	<a href="<?php the_permalink(); ?>">
			<?php
            // Different image size based on layout selection for Homepage, Categories and Posts Page
            echo ti_layout_based_post_image();
			
            // Add icon to different post formats
            if ( 'gallery' == get_post_format() ): // Gallery
                echo '<i class="icomoon-camera-retro"></i>';
            elseif ( 'video' == get_post_format() ): // Video
                echo '<i class="icomoon-camera"></i>';
            elseif ( 'audio' == get_post_format() ): // Audio
                echo '<i class="icomoon-music"></i>';
            endif;
            ?>
    	</a>

        <?php 
        /* Show post rating if the feature was enabled */
        if ( get_field('enable_rating') == '1' ) {
            
            $show_total = apply_filters( 'ti_score_total', '' ); // Call total score calculation function ?>

            <div class="score-line" style="width:<?php echo number_format( $show_total, 1, '', '' ); ?>%;">
                <span><i><?php echo number_format( $show_total, 1, '.', '' ); ?></i></span>
            </div>
        <?php } ?>
    </figure>
        
    <header class="entry-header">
        <div class="entry-meta">
           <?php ti_meta_data(); ?>
        </div>
        <h2 class="entry-title" itemprop="headline">
            <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </h2>
        <?php global $ti_option; ?>
        <?php if( $ti_option['site_author_name'] == 1 ) { ?>
        <span class="vcard author">
            <?php _e( 'By','themetext' ); ?>
            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="url fn n" rel="author">
                <?php the_author_meta( 'display_name' ); ?>
            </a>
        </span>
        <?php } ?>
    </header>
	    
    <?php if( $ti_option['site_wide_excerpt'] == 1 ) { // Enable/Disable the excerpt site wide ?>
    <div class="entry-summary" itemprop="text">
        <?php the_excerpt(); ?>
        <?php if( $ti_option['read_more_link'] == 1 ) { ?> 
        	<a class="read-more-link" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'themetext' ); ?></a>
        <?php } ?>
    </div>
    <?php } ?>
    
</article>