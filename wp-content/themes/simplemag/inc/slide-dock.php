<?php 
/**
 * Random Posts slide dock. Appears in single.php
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
global $ti_option;
?>

<div class="widget slide-dock">

    <a class="close-dock" href="#" title="Close"><i class="icomoon-remove-sign"></i></a>
    <h3><?php _e( 'More Stories', 'themetext' ); ?></h3>
    
    <div class="entries">
    
    <?php
	
        $ti_random_post = new WP_Query(
            array(
                'post_type' => 'post',
                'post__not_in' => array( $post->ID ),
                'orderby' => 'rand',
                'posts_per_page' => 1,
                'ignore_sticky_posts' => 1
            )
        );
		
        while ( $ti_random_post->have_posts() ) : $ti_random_post->the_post(); ?>
        
        <article>
        	<figure class="entry-image">
                <a href="<?php the_permalink(); ?>">
                    <?php
					if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'rectangle-size' );
                    } elseif( first_post_image() ) { // Set the first image from the editor
						echo '<img src="' . first_post_image() . '" class="wp-post-image" />';
					} ?>
                </a>
            </figure>
            
            <header class="entry-header">
                <h4>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h4>
            </header>
            
            <div class="entry-summary">
                <?php the_excerpt(); ?>
            </div>
        </article>
        
    <?php endwhile; ?>
    
	<?php wp_reset_postdata(); ?>
    
    </div>
    
</div><!-- .slide-dock -->