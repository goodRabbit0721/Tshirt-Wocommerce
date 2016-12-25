<?php 
/**
 * Latest Posts by Category
 * Page Composer Section
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
global $ti_option;
?>

<section class="wrapper home-section category-posts">

    <?php if( get_sub_field( 'category_main_title' ) ): ?>
    <header class="section-header">
        <div class="title-with-sep">
            <h2 class="title"><?php the_sub_field( 'category_main_title' ); ?></h2>
        </div>
        <?php if ( get_sub_field( 'category_sub_title' ) ): ?>
        <span class="sub-title"><?php the_sub_field( 'category_sub_title' ); ?></span>
        <?php endif; ?>
    </header>
    <?php endif; ?>

    <div class="grids entries">
		<?php 
		/**
		 * Select how many posts to show and 
		 * get the category id which will filter the section
		**/
		$cats_to_show = get_sub_field( 'category_posts_per_page');
		$cat_id = get_sub_field( 'category_section_name' );
		
		$ti_cat_posts = new WP_Query(
			array(
				'posts_per_page' => $cats_to_show,
				'cat' =>  $cat_id
			)
		);
			
		if ( $ti_cat_posts->have_posts() ) :
			while ( $ti_cat_posts->have_posts() ) : $ti_cat_posts->the_post();
		?>
        
            <article <?php post_class("grid-4"); ?>>
            
            	<figure class="entry-image">
                    <a href="<?php the_permalink(); ?>">
						<?php
						if ( has_post_thumbnail() ) {
                        	the_post_thumbnail( 'rectangle-size' );
                        } elseif( first_post_image() ) { // Set the first image from the editor
							echo '<img src="' . first_post_image() . '" class="wp-post-image" alt="' . get_the_title() . '" />';
						} ?>
					</a>
                </figure>
                
                <header class="entry-header">
                    <div class="entry-meta">
                       <?php ti_meta_data(); ?>
                    </div>
                    <h2 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <?php if( $ti_option['site_author_name'] == 1 ) { ?>
                    <span class="vcard author">
                        <?php _e( 'By','themetext' ); ?>
                        <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="url fn n" rel="author">
                            <?php the_author_meta( 'display_name' ); ?>
                        </a>
                    </span>
                    <?php } ?>
                </header>
                
                <?php if( get_sub_field( 'category_excerpt' ) == 'enable' ) { ?>
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div>
                <?php } ?>

            </article>
            
        <?php endwhile; ?>
        
        <?php wp_reset_postdata(); ?>
      
    </div>

        <?php
        // Enable/Disable the button
        if ( get_sub_field( 'category_button' ) == 'cat_but_enable' ) {
            // Link to the selected category
            $category_id = get_sub_field( 'category_section_name' );
            $category_link = get_category_link( $category_id ); 
            ?>
            <div class="composer-button clearfix">
                <a class="read-more" href="<?php echo esc_url( $category_link ); ?>"><?php _e( 'View The Category', 'themetext' ); ?></a>
            </div>
        <?php } ?>
        
       
      <?php else: ?>
        	
      <p class="message">
        <?php _e( 'This category does not contain any posts yet', 'themetext' ); ?>
      </p>
            
      <?php endif; ?>
    
</section><!-- Latest by category -->