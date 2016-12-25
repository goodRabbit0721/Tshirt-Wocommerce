<?php
/**
 * The main template file
 *
 * @package SimpleMag
 * @since   SimpleMag 1.0
**/
get_header();
global $ti_option;
?>

<?php $archive_sidebar = get_field( 'page_sidebar', get_option('page_for_posts') ); ?>

    <section id="content" role="main" class="clearfix animated">
    
		<?php if ( $ti_option['posts_page_title'] == 'full_width_title' ) : ?>
        <header class="entry-header page-header">
            <div class="wrapper title-with-sep page-title">
                <h1 class="entry-title">
                    <?php
                    $posts_page_id = get_option( 'page_for_posts' );
                    echo get_the_title( $posts_page_id );
                    ?>
                </h1>
            </div>
        </header>
        <?php endif; ?>
        
        <div class="wrapper">
		<?php
        // Enable/Disable sidebar based on the field selection
        if ($archive_sidebar):
        ?>
            <div class="grids">
                <div class="grid-8 column-1">
		<?php endif; ?>
                
                <?php if ( $ti_option['posts_page_title'] == 'above_content_title' ) : ?>
                <header class="entry-header page-header">
                    <div class="title-with-sep page-title">
                        <h1 class="entry-title">
							<?php
                            $posts_page_id = get_option( 'page_for_posts' ); 
                            echo get_the_title( $posts_page_id ); 
                            ?>
                        </h1>
                    </div>
                </header>
                <?php endif; ?>
                
                <div class="grids <?php echo $ti_option['posts_page_layout']; ?> entries">
					<?php 
                    if ( have_posts() ) : while ( have_posts()) : the_post();
                    	get_template_part( 'content', 'post' );
                    endwhile;
                    ?>
                </div>
                
                <?php ti_pagination(); ?>
                
                <?php else : ?>
                
                <p class="message">
                	<?php _e( 'Sorry, no posts were found', 'themetext' ); ?>
                </p>
                
                <?php endif;?>
                
                <?php
                // Enable/Disable sidebar based on the field selection
                if ($archive_sidebar):
                ?>
                </div><!-- .grid-8 -->
                
                <?php get_sidebar(); ?>
            
            </div><!-- .grids -->
    		<?php endif; ?>
        
        </div><!-- .wrapper -->
    </section><!-- #content -->
    
<?php get_footer(); ?>