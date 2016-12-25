<?php 
/**
 * Posts Slider & Two Latest Posts
 * Page Composer Section
 *
 * @package SimpleMag
 * @since   SimpleMag 3.0
**/
global $ti_option;
?>

<section class="wrapper home-section slider-latest">
    
    <div class="grids">
        <div class="grid-8 columns column-1">

            <?php
            /** 
             * Add posts to slider only if the 'Add To Slider' 
             * custom field checkbox was checked on the Post edit page
            **/
            $slides_num = get_sub_field( 'slides_to_show' );
            $ti_slider_combined = new WP_Query(
                array(
                    'post_type' => 'post',
                    'posts_per_page' => $slides_num,
                    'meta_key' => 'homepage_slider_add',
                    'meta_value' => '1'
                )
            );
            ?>

            <?php if ( $ti_slider_combined->have_posts() ) : ?>
                
            <div class="flexslider posts-slider loading">
                <ul class="slides">
                
                <?php while ( $ti_slider_combined->have_posts() ) : $ti_slider_combined->the_post(); ?>
                
                    <li <?php post_class("content-over-image"); ?>>
                        <figure>
                            <a class="entry-link" href="<?php the_permalink(); ?>"></a>
                            <?php if ( has_post_thumbnail() ) { ?>
                            	<?php the_post_thumbnail( 'medium-size' ); ?>
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
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <a class="read-more" href="<?php the_permalink() ?>"><?php _e( 'Read More', 'themetext' ); ?></a>
                                </div>
                            </div>
                        </header>
                    </li>
                    
                <?php endwhile; ?>

                <?php wp_reset_postdata(); ?>
                
                </ul>
            </div><!-- Slider -->
        
            <?php else: ?>
                
            <p class="message">
                <?php _e( 'Sorry, there are no posts in the slider', 'themetext' ); ?>
            </p>
                 
            <?php endif; ?>

        </div><!-- Grid 8 -->

        <div class="grid-4 columns column-2 entries">

            <?php
            /** 
             * Add posts to this section only if the 'Make Featured'
             * custom field checkbox was checked on the Post edit page
            **/
            $ti_latest_combined = new WP_Query(
                array(
                    'post_type' => 'post',
                    'posts_per_page' => 2
                )
            );
           
            if ( $ti_latest_combined->have_posts() ) :
                while ( $ti_latest_combined->have_posts() ) : $ti_latest_combined->the_post(); ?>
            
                <article class="content-over-image">
                    <figure>
                        <a class="entry-link" href="<?php the_permalink(); ?>"></a>
                            <?php 
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'rectangle-size' );
                            } elseif( first_post_image() ) { // Set the first image from the editor
                                echo '<img src="' . first_post_image() . '" class="wp-post-image" alt="' . get_the_title() . '" />';
                            } ?>
                        </a>
                    </figure>
                    
                    <header class="entry-header">
                        <div class="inner">
                            <div class="inner-cell">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                    		</div>
                        </div>
                    </header>
                </article>
                
                <?php endwhile; ?>

                <?php wp_reset_postdata(); ?>
                
                <?php else: ?>
                
                <p class="message">
                    <?php _e( 'There are no latest posts yet', 'themetext' ); ?>
                </p>
                
            <?php endif; ?>

        </div><!-- Grid 4 -->

    </div><!-- Grids -->

</section>