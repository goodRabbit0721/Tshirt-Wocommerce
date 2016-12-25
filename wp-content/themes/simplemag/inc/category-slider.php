<?php
/**
 * Category Slider
 *
 * @package SimpleMag
 * @since 	SimpleMag 2.0
**/
global $ti_option;
?>


<?php if ( ! is_paged() ): // Output the slider only on first category page ?>

<section class="category-slider">

    <div class="flexslider posts-slider loading">
        <ul class="slides">
    
    <?php 
	$cat = get_query_var( 'cat' );
    $ti_cat_slider = new WP_Query(
        array(
            'post_type' => 'post',
            'meta_key' => 'category_slider_add',
            'meta_value' => 1,
            'posts_per_page' => 10,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $cat,
                )
            )
        )
    );
    
	if ( $ti_cat_slider->have_posts()) : while ( $ti_cat_slider->have_posts() ) : $ti_cat_slider->the_post(); ?>
    
        <li <?php post_class("content-over-image"); ?>>
        
            <figure>
                <a class="entry-link" href="<?php the_permalink(); ?>"></a>
                <?php if ( has_post_thumbnail() ) { ?>
                    <?php the_post_thumbnail( 'big-size' ); ?>
                <?php } else { ?>
                    <img class="alter" src="<?php echo get_template_directory_uri(); ?>/images/pixel.gif" alt="<?php the_title(); ?>" />
                <?php } ?>
            </figure>
            
            <header class="entry-header">
                <div class="inner">
                    <div class="inner-cell">
                        <div class="entry-meta">
							<?php if( $ti_option['site_author_name'] == 1 ) { ?>
                            <span class="entry-author">
                                <?php _e( 'By','themetext' ); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php the_author_meta( 'display_name' ); ?></a>
                            </span>
                            <?php } ?>
                            <?php ti_meta_data(); ?>
                        </div>
                        <h2 class="entry-title">
                            <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                        </h2>
                        <a class="read-more" href="<?php the_permalink() ?>"><?php _e( 'Read More', 'themetext' ); ?></a>
            		</div>
                </div>
            </header>
            
        </li>
        
    <?php endwhile; endif; ?>
    
    <?php wp_reset_query(); ?>
    
    </ul>
    
</section><!-- Slider -->

<?php endif; ?>