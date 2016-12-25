<?php 
/**
 * Template Name: Sitemap
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
get_header(); ?>

	<section id="content" role="main" class="clearfix animated">
    	<div class="wrapper">
        	
            <header class="entry-header page-header">
                <div class="title-with-sep page-title">
					<h1 class="entry-title"><?php the_title(); ?></h1>
                </div>
            </header>

			<?php
			// Enable/Disable sidebar based on the field selection
			if ( get_field( 'page_sidebar' ) == 'page_sidebar_on' ):
			?>
            <div class="grids">
                <div class="grid-8 column-1">
            <?php endif; ?>
            
            <?php 
			if (have_posts()) :
				while (have_posts()) : the_post(); 
			?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if ( has_post_thumbnail() ) { ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'img_big' ); ?>
                        </a>
                    <?php } ?>
                	<div class="page-content">
                		<?php the_content(); ?>
                	</div>
                </article>
                
                <?php endwhile; endif; ?>
                
					<section class="sitemap">
                        <div class="row">
                            <h3 class="trigger entry-title active"><?php _e( 'Categories','themetext' ); ?></h3>
                            <ul>
                            	<?php wp_list_categories( array( 'title_li' => '' ) ); ?>
                            </ul>
                        </div>
                        
                        <div class="row">
                            <h3 class="trigger entry-title"><?php _e( 'Authors','themetext' ); ?></h3>
                            <ul>
                            	<?php wp_list_authors(); ?>
                            </ul>
                        </div>
                        
                        <div class="row">
                            <h3 class="trigger entry-title"><?php _e( 'Pages','themetext' ); ?></h3>
                            <ul>
                            	<?php wp_list_pages( array( 'title_li' => '' ) ); ?>
                            </ul>
                        </div>
                        
                        <div class="row">
                            <h3 class="trigger entry-title"><?php _e( 'Archives','themetext' ); ?></h3>
                            <ul>
                            	<?php wp_get_archives('type=monthly&show_post_count=true'); ?> 
                            </ul>
                        </div>    
             	</section>
                   
				<?php
				// Enable/Disable sidebar based on the field selection
				if ( get_field( 'page_sidebar' ) == 'page_sidebar_on' ):
				?>
                </div><!-- .grid-8 -->
            
                <div class="grid-4">
                    <?php get_sidebar(); ?>
                </div>
            </div><!-- .grids -->
            <?php endif; ?>
        
        </div>
    </section><!-- #content -->

<?php get_footer(); ?>