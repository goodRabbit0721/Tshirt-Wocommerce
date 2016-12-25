<?php 
/**
 * Template Name: Page Composer
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
get_header(); ?>
	
    <section id="content" role="main" class="clearfix animated">
        
        <?php
        /**
         * Page Composer - Under the menu conditions
        **/
		if( have_rows( 'page_composer' ) ):

	        while( have_rows( 'page_composer' ) ) : the_row();
			
				// Posts Slider
				if( get_row_layout() == 'hp_posts_slider' ):

					/* Post Slider under the main menu */
					if ( get_sub_field ( 'posts_slider_position' ) == 'slider_under_menu' ):

						// Regular
						if ( get_sub_field ( 'posts_slider_type' ) == 'slider_content' ):
					
							echo '<div class="wrapper slider-under-menu">';
									get_template_part ( 'composer/posts', 'slider' );
							echo '</div>';
						
						// With two latest posts
						elseif ( get_sub_field ( 'posts_slider_type' ) == 'slider_and_latest' ):
							
							echo '<div class="wrapper slider-under-menu">';
									get_template_part ( 'composer/slider', 'latest' );
							echo '</div>';

						// Full width
						elseif ( get_sub_field ( 'posts_slider_type' ) == 'slider_full_width' ):
					
							get_template_part ( 'composer/posts', 'slider' );
						
						endif;

					endif;
				

				/* Post Carousel under the main menu */
				elseif ( get_row_layout() == 'hp_posts_carousel' ):
					
					if ( get_sub_field ( 'carousel_position' ) == 'carousel_under_menu' ):

						get_template_part ( 'composer/posts', 'carousel' );

					endif;


				/* Custom Slider under the main menu */
				elseif ( get_row_layout() == 'custom_slider' ):

					if ( get_sub_field ( 'custom_slider_position' ) == 'custom_under_menu' ):

						// Regular
						if ( get_sub_field ( 'custom_slider_type' ) == 'custom_slider_content' ):
							
							echo '<div class="wrapper slider-under-menu">';
								get_template_part ( 'composer/custom', 'slider' );
							echo '</div>';


						// Full Width
						elseif ( get_sub_field ( 'custom_slider_type' ) == 'custom_slider_full' ):
					
							get_template_part ( 'composer/custom', 'slider' );
						
						endif;

					endif;

				endif;
			
	        endwhile;

        endif;
        ?>
        
        <?php
        // Enable/Disable sidebar based on the field selection
		if ( get_field( 'comp_page_sidebar' ) == 'comp_sidebar_page' ):
		?>
        <div class="wrapper">
            <div class="grids">
                <div class="grid-8 column-1">
		<?php endif; ?>
            
        <?php 

		/**
		 *  Page Composer
		**/ 
		if( have_rows( 'page_composer' ) ):

			while( have_rows( 'page_composer' ) ) : the_row();
				
				
	            /* Posts Slider */ 
	            if( get_row_layout() == 'hp_posts_slider' ):
					
					if ( get_sub_field ( 'posts_slider_position' ) == 'slider_on_page' ):

						// Regular
						if ( get_sub_field ( 'posts_slider_type' ) == 'slider_content' ):
							
							echo '<div class="wrapper">';
								get_template_part ( 'composer/posts', 'slider' );
							echo '</div>';
							
						// With two latest posts
						elseif ( get_sub_field ( 'posts_slider_type' ) == 'slider_and_latest' ):
					
							get_template_part ( 'composer/slider', 'latest' );


						// Full Width
						elseif ( get_sub_field ( 'posts_slider_type' ) == 'slider_full_width' ):
					
							get_template_part ( 'composer/posts', 'slider' );
						
						endif;

					endif;



				// Posts Carousel
				elseif ( get_row_layout() == 'hp_posts_carousel' ):
			
					if ( get_sub_field ( 'carousel_position' ) == 'carousel_on_page' ):

						get_template_part ( 'composer/posts', 'carousel' );

					endif;



				/* Custom Slider */ 
				elseif( get_row_layout() == 'custom_slider' ):
					
					if ( get_sub_field ( 'custom_slider_position' ) == 'custom_on_page' ):

						// Regular
						if ( get_sub_field ( 'custom_slider_type' ) == 'custom_slider_content' ):
							
							echo '<div class="wrapper">';
								get_template_part ( 'composer/custom', 'slider' );
							echo '</div>';


						// Full Width
						elseif ( get_sub_field ( 'custom_slider_type' ) == 'custom_slider_full' ):
					
							get_template_part ( 'composer/custom', 'slider' );
						
						endif;

					endif;
					
					
					
				/* Featured Posts */ 
				elseif( get_row_layout() == 'hp_featured_posts' ):
					
					get_template_part ( 'composer/featured', 'posts' );



	            /* Latest posts by Category */ 
	            elseif( get_row_layout() == 'latest_by_category' ):
				
					get_template_part ( 'composer/category', 'posts' );
					
				
				
				/* Latest posts by Format */ 
				elseif( get_row_layout() == 'latest_by_format' ):
				
					get_template_part ( 'composer/format', 'posts' );
				
				
				
				/* Latest Reviews */ 
				elseif( get_row_layout() == 'latest_reviews' ):
				
					get_template_part ( 'composer/latest', 'reviews' );
                
				/* Latest Posts */ 
	            elseif( get_row_layout() == 'latest_posts' ):
					
					get_template_part ( 'composer/latest', 'posts' );
				


				/* Full Width Image */ 
	            elseif( get_row_layout() == 'full_width_image' ):
					
					get_template_part ( 'composer/full', 'image' );
				

				
				/* Static Image */ 
	            elseif( get_row_layout() == 'image_advertising' ):
					
					get_template_part ( 'composer/static', 'image' );
					
					
				
				/* Code Box */ 
	            elseif( get_row_layout() == 'code_advertising' ):
					
					get_template_part ( 'composer/code', 'box' );
				

				
				/* Title or Text */ 
	            elseif( get_row_layout() == 'title_or_text' ):
					
					get_template_part ( 'composer/title', 'text' );
					
				
				/* WP Editor */ 
	            elseif( get_row_layout() == 'wp_section_editor' ):
					
					get_template_part ( 'composer/wp', 'editor' );
					
				endif;
	            
			endwhile;

		endif;
		?>
        
        <?php
		// Enable/Disable sidebar based on the field selection
		if ( get_field( 'comp_page_sidebar' ) == 'comp_sidebar_page' ): 
		?>
				</div><!-- .grid-8 -->
		  
				<?php get_sidebar(); ?>
			</div><!-- .grids -->
		</div><!-- .wrapper -->
		<?php endif; ?>

		<?php 
		// Enable/Disable the Posts Page link. The Posts Page is defined in admin Settings -> Reading
		if ( get_field ( 'comp_posts_page_link' ) == 'comp_posts_page_on' ):
		?>
	    	<div class="wrapper all-news-link">
				<?php $posts_page_id = get_option( 'page_for_posts' ); ?>
            	<a class="read-more" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"><?php echo get_the_title( $posts_page_id ); ?></a>
            </div>
		<?php endif; ?>
        
    </section>

<?php get_footer(); ?>