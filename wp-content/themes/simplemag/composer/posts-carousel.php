<?php 
/**
 * Homepage Posts Carosuel
 * Page Composer Section
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/
global $ti_option;
?>

<section class="posts-carousel">

    <?php
    /** 
     * Add posts to slider only if the 'Add To Slider' 
     * custom field checkbox was checked on the Post edit page
    **/
    $carousel_slides_num = get_sub_field( 'carousel_slides_to_show' );
    $ti_posts_carousel = new WP_Query(
        array(
            'post_type' => 'post',
            'posts_per_page' => $carousel_slides_num,
            'meta_key' => 'homepage_slider_add',
            'meta_value' => '1'
        )
    );
    ?>

    <?php if ( $ti_posts_carousel->have_posts() ) : ?>
    
    <div id="gallery-carousel" class="posts-slider">
    	<ul class="carousel">
        
        <?php while ( $ti_posts_carousel->have_posts() ) : $ti_posts_carousel->the_post(); ?>
        
            <li class="gallery-item content-over-image">
            
            	<figure>
                    <a class="entry-link" href="<?php the_permalink(); ?>"></a>
                    <?php 
                    if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'gallery-carousel' );
                    } ?>
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
                		</div>
                    </div>
                </header>
                
            </li>
            
        <?php endwhile; ?>
        
        <?php wp_reset_postdata(); ?>
        
        </ul>
        <a class="carousel-nav prev" href="#"><i class="icomoon-chevron-left"></i></a>
        <a class="carousel-nav next" href="#"><i class="icomoon-chevron-right"></i></a>
    </div>
    
    <?php else: ?>
        
        <p class="message">
			<?php _e( 'Sorry, there are no posts in the carousel', 'themetext' ); ?>
        </p>
         
    <?php endif; ?>

</section><!-- Posts Carousel -->