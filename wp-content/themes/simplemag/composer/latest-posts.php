<?php 
/**
 * Latest Posts
 * Page Composer Section
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
?>

<section class="wrapper home-section latest-posts">

	<?php if( get_sub_field( 'latest_main_title' ) ): ?>
    <header class="section-header">
        <div class="title-with-sep">
            <h2 class="title"><?php the_sub_field( 'latest_main_title' ); ?></h2>
        </div>
        <?php if ( get_sub_field( 'latest_sub_title' ) ): ?>
        <span class="sub-title"><?php the_sub_field( 'latest_sub_title' ); ?></span>
        <?php endif; ?>
    </header>
    <?php endif; ?>

    <?php
    /** 
     * Latest Posts
    **/
	$posts_to_show = get_sub_field( 'latest_posts_per_page' );

    // Exclude Categories from loop. Selected in Latest Post section.
    $latest_posts_exclude = get_sub_field( 'latest_posts_exclude' );
    if ( $latest_posts_exclude ):
        foreach( $latest_posts_exclude as $term ):
            $get_cats[] = '-'.$term->term_id;
        endforeach;
        $excluded_cats = implode( ",",$get_cats );
    else:
        $excluded_cats = '';
    endif;

    // Loop arguments
	$latest_posts_args = array(
			'posts_per_page' => $posts_to_show,
            'cat' => $excluded_cats,
            'post__not_in' => array( $post->ID ),
            'ignore_sticky_posts' => 1
	);
	$ti_latest_posts = new WP_Query( $latest_posts_args );

     // Enable post count for ads (lines 70 and 110)
    if ( get_sub_field( 'latest_insert_ad' ) != 'latest_no_ad_option' ) :
        $latest_postnum = 1; 
        $latest_showad = get_sub_field( 'latest_after_post_ad' );
    endif;
    ?>

	<?php 
	// Enable/Disable sidebar based on the field selection         
    if ( get_field ( 'comp_page_sidebar' ) == 'comp_sidebar_section' ):
	?>

    <div class="grids">
        <div class="grid-8 column-1">
            <div class="grids <?php the_sub_field( 'latest_posts_layout' ); ?> entries">
            
                <?php
               
                if ( $ti_latest_posts->have_posts() ) :

                    while ( $ti_latest_posts->have_posts() ) : $ti_latest_posts->the_post();

                        // Get all posts
                        get_template_part( 'content', 'post' );
                        
                        // Insert ad only if the option is not equal to No Ad option
                        if ( get_sub_field( 'latest_insert_ad' ) != 'latest_no_ad_option' ) :
                            include( locate_template( 'inc/insert-ad.php' ) );
                        endif;
                        
                    endwhile;
				?>
                
                <?php wp_reset_postdata(); ?>
            </div>

            <?php else: ?>
             
                <p class="grid-12 message">
                	<?php _e( 'Sorry, no posts were found', 'themetext' ); ?>
                </p>
                
             <?php endif; ?>     
         </div>

        <?php get_sidebar(); ?>

	</div>
    
    <?php else : ?>

        <div class="grids <?php the_sub_field( 'latest_posts_layout' ); ?> entries">

			<?php
            if ( $ti_latest_posts->have_posts() ) :

                while ( $ti_latest_posts->have_posts() ) : $ti_latest_posts->the_post();
            
                    // Get all posts
                    get_template_part( 'content', 'post' );
                    
                    // Insert ad only if the option is not equal to No Ad option
                    if ( get_sub_field( 'latest_insert_ad' ) != 'latest_no_ad_option' ) :
                        include( locate_template( 'inc/insert-ad.php' ) );
                    endif;

            endwhile;
            ?>
            
            <?php wp_reset_postdata(); ?>
            
         </div>
         
		 <?php else: ?>
         
            <p class="message">
            	<?php _e( 'Sorry, no posts were found', 'themetext' ); ?>
            </p>
            
         <?php endif; ?>
    
    <?php endif; ?>
    
</section><!-- Latest Posts -->