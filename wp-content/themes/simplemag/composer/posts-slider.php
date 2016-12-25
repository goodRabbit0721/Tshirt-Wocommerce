<?php 
/**
 * Posts Slider
 * Page Composer Section
 *
 * @package SimpleMag
 * @since   SimpleMag 1.1
**/
global $ti_option;
?>

<section class="posts-slider-section">
    
    <?php
    /** 
     * Add posts to slider only if the 'Add To Slider' 
     * custom field checkbox was checked on the Post edit page
    **/
    $slides_num = get_sub_field( 'slides_to_show' );
    $ti_posts_slider = new WP_Query(
        array(
            'post_type' => 'post',
            'posts_per_page' => $slides_num,
            'meta_key' => 'homepage_slider_add',
            'meta_value' => '1'
        )
    );
    ?>

    <?php if ( $ti_posts_slider->have_posts() ) : ?>

        <div class="flexslider posts-slider loading">
            <ul class="slides">
            
            <?php while ( $ti_posts_slider->have_posts() ) : $ti_posts_slider->the_post(); ?>
            
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
                                    <span class="vcard author">
                                        <?php _e( 'By','themetext' ); ?>
                                        <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="url fn n" rel="author">
                                            <?php the_author_meta( 'display_name' ); ?>
                                        </a>
                                    </span>
                                    <?php } ?>
                                    <?php ti_meta_data(); ?>
                                </div>
                                <h2 class="entry-title">
                                    <?php the_title(); ?>
                                </h2>
                                <a class="read-more" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'themetext' ); ?></a>
                            </div>
                        </div>
                    </header>
                    
                </li>
                
            <?php endwhile; ?>

            <?php wp_reset_postdata(); ?>
            
            </ul>
        </div>
    
    <?php else: ?>
        
        <p class="message">
            <?php _e( 'Sorry, there are no posts in the slider', 'themetext' ); ?>
        </p>
         
    <?php endif; ?>

</section><!-- Slider -->