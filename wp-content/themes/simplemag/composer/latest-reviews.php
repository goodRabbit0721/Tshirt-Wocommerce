<?php 
/**
 * Featured Posts
 * Page Composer Section
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
global $ti_option;
?>

<section class="wrapper home-section latest-reviews">
            		
	<?php if( get_sub_field( 'reviews_main_title' ) ) { ?>
    <header class="section-header">
        <div class="title-with-sep">
            <h2 class="title"><?php the_sub_field( 'reviews_main_title' ); ?></h2>
        </div>
        <?php if( get_sub_field( 'reviews_sub_title' ) ) { ?>
        <span class="sub-title"><?php the_sub_field( 'reviews_sub_title' ); ?></span>
        <?php } ?>
    </header>
    <?php } ?>
    
    
    <?php
    /** 
     * Posts with reviews.
	 * Display posts only if Rating is enabled
    **/
	$posts_to_show = get_sub_field( 'reviews_posts_per_page' );
	$ti_latest_reviews = new WP_Query(
		array(
			'post_type' => 'post',
			'meta_key' => 'enable_rating',
			'meta_value' => '1',
			'posts_per_page' => $posts_to_show
		)
	);
    ?>

    <?php if ( $ti_latest_reviews->have_posts() ) : ?>
    
        <div class="grids entries">
           
    		<?php while ( $ti_latest_reviews->have_posts() ) : $ti_latest_reviews->the_post(); ?>

                <article <?php post_class("grid-4"); ?>>
                
                    <figure class="entry-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php 
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'rectangle-size' );
                            } elseif( first_post_image() ) { // Set the first image from the editor
                                echo '<img src="' . first_post_image() . '" class="wp-post-image" alt="' . get_the_title() . '" />';
                            } ?>
                        </a>
                        
                        <?php $show_total = apply_filters( 'ti_score_total', '' ); // Call total score calculation function ?>
                        <div class="score-line" style="width:<?php echo number_format( $show_total, 1, '', '' ); ?>%;">
                            <span><i><?php echo number_format( $show_total, 1, '.', '' ); ?></i></span>
                        </div>
                    </figure>
                    
                    <header class="entry-header">
                        <div class="entry-meta">
                           <?php ti_meta_data(); ?>
                        </div>
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <?php if( $ti_option['site_author_name'] == 1 ) { ?>
                        <span class="vcard author">
                            <?php _e( 'By','themetext' ); ?>
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="url fn n" rel="author">
                                <?php the_author_meta( 'display_name' ); ?>
                            </a>
                        </span>
                        <?php } ?>
                    </header>
                    
                    <?php if ( get_sub_field( 'reviews_excerpt' ) == 'enable' ) { ?>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                    <?php } ?>
                        
                </article>
            
            <?php endwhile; ?>
            
    		<?php wp_reset_postdata(); ?>
            
         </div>
        
    <?php else: ?>
      
        <p class="message">
            <?php _e( 'There are no reviews yet', 'themetext' ); ?>
        </p>
        
    <?php endif; ?>

</section><!-- Featured Posts -->