<?php
/**
 * The archive
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
get_header(); ?>

	<section id="content" role="main" class="clearfix animated">
    	<div class="wrapper">

		<?php if (have_posts()) : ?>
            
            <header class="entry-header page-header">
                <div class="title-with-sep page-title">
                    <h1 class="entry-title">
						<?php if (is_category()) { ?>
                        <?php single_cat_title(); ?>
                        
                        <?php } elseif(is_tag()) { ?>
                        <?php single_tag_title(); ?>
                
                        <?php } elseif (is_day()) { ?>
                        <?php the_time('F jS, Y'); ?>
                
                        <?php } elseif (is_month()) { ?>
                        <?php the_time('F, Y'); ?>
                
                        <?php } elseif (is_year()) { ?>
                        <?php the_time('Y'); ?>
                        
                        <?php } elseif ( get_post_format() ) { ?>
                        <?php echo get_post_format(); ?>
                        
                        <?php } elseif (is_author()) { ?>
                        <?php _e ( 'Author Archive', 'themetext' ); ?>
        
                        <?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
                        <?php } ?>
                    </h1>
                </div>
            </header>

            <?php if ( category_description() ) { ?>
            <div class="cat-description">
                <?php echo category_description(); ?>
            </div>
            <?php } ?>
            
            <?php 
			// Slider with "Full Width" option selected only for category
            if( is_category() ) {
    			if( get_field('category_slider', 'category_' . get_query_var('cat') ) == 'cat_slider_on' && get_field('category_slider_position', 'category_' . get_query_var('cat') ) == 'cat_slider_full' ) {
    				get_template_part( 'inc/category', 'slider' );
    			}
            } ?>
            
			<?php
            // Enable/Disable sidebar based on the field selection
            if ( ! get_field( 'category_sidebar', 'category_' . get_query_var('cat') ) || get_field( 'category_sidebar', 'category_' . get_query_var('cat') ) == 'cat_sidebar_on' ):
            ?>
            <div class="grids">
                <div class="grid-8 column-1">
                <?php endif; ?>
                    
                    <?php 
					// Slider with "Above The Content" option selected only for category
                    if( is_category() ) {
    					if( get_field('category_slider', 'category_' . get_query_var('cat') ) == 'cat_slider_on' && get_field('category_slider_position', 'category_' . get_query_var('cat') ) == 'cat_slider_above' ) {
                			get_template_part( 'inc/category', 'slider' );
    					}
                    } ?>
                    
                    <?php
                        $cat_layout_field = get_field ( 'category_posts_layout', 'category_' . get_query_var('cat') );
						if ( $cat_layout_field == 'grid-layout' ) {
							$posts_layout = 'grid-layout';
						} elseif ( $cat_layout_field == 'list-layout' ) {
							$posts_layout = 'list-layout';
                        } elseif ( $cat_layout_field == 'classic-layout' ) {
                            $posts_layout = 'classic-layout';
						} else {
							$posts_layout = 'masonry-layout';
						}
					?>
                    
                    <div class="grids <?php echo $posts_layout ?> entries">
                    <?php 
					while (have_posts()) : the_post();
                
                        get_template_part( 'content', 'post' );
                        
                    endwhile; ?>
                    </div>
                    
                    <?php ti_pagination(); ?>
					
				<?php else:
                
					// Enable/Disable sidebar based on the field selection
					if ( ! get_field( 'category_sidebar', 'category_' . get_query_var('cat') ) || get_field( 'category_sidebar', 'category_' . get_query_var('cat') ) == 'cat_sidebar_on' ):
					?>
			<div class="grids">
				<div class="grid-8 column-1">
					<?php endif; ?>
                    	
						<p class="message"><?php _e( 'Sorry, no posts were found', 'themetext' ); ?></p>
                        
                <?php endif;
					
                // Enable/Disable sidebar based on the field selection
                if ( ! get_field( 'category_sidebar', 'category_' . get_query_var('cat') ) || get_field( 'category_sidebar', 'category_' . get_query_var('cat') ) == 'cat_sidebar_on' ):
                ?>
                </div><!-- .grid-8 -->
            
                <?php get_sidebar(); ?>
            </div><!-- .grids -->
            <?php endif; ?>
    
		</div>
    </section><!-- #content -->

<?php get_footer(); ?>