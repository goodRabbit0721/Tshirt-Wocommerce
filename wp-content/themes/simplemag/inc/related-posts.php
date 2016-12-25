<?php
/**
 * Relatest Posts from
 * the same Category or the same Tag
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/

global $ti_option; 

if ( $ti_option['single_related_posts_show_by'] == 'related_cat' ) {
	$ti_taxs = get_the_category( $post->ID ); // Display related posts by category
} else {
	$ti_taxs = wp_get_post_tags( $post->ID ); // Display related posts by tag
}


if ( $ti_taxs ) {

	$ti_tax_ids = array();

	foreach($ti_taxs as $individual_tax) $ti_tax_ids[] = $individual_tax->term_id;

	$posts_to_show = $ti_option['single_related_posts_to_show'];

	if ( $ti_option['single_related_posts_show_by'] == 'related_cat' ) { 
		// Loop argumnetsnts show posts by category
		$args = array(
			'category__in' => $ti_tax_ids,
			'post__not_in' => array( $post->ID ),
			'posts_per_page' => $posts_to_show,
			'ignore_sticky_posts' => 1
		);
	} else { 
		// Loop argumnetsnts show posts by category
		$args = array(
			'tag__in' => $ti_tax_ids,
			'post__not_in' => array( $post->ID ),
			'posts_per_page' => $posts_to_show,
			'ignore_sticky_posts' => 1
		);
	}

	$ti_related_posts = new WP_Query( $args );
?>
	
    <div class="single-box related-posts">
    
        <h3 class="title"><?php _e( 'You may also like', 'themetext' ); ?></h3>
    
        <div class="grids entries">
            <div class="carousel">
            
            <?php 
            if( $ti_related_posts->have_posts() ) : 
				while ( $ti_related_posts->have_posts() ) : $ti_related_posts->the_post(); ?>
		
				<div class="item">
					  <figure class="entry-image">
						  <a href="<?php the_permalink(); ?>">
							<?php 
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'rectangle-size-small' );
							} elseif( first_post_image() ) { // Set the first image from the editor
								echo '<img src="' . first_post_image() . '" class="wp-post-image" />';
							} ?>
						  </a>
					  </figure>
					  <header class="entry-header">
						  <h4>
							  <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
						  </h4>
					  </header>
				</div>
			
				<?php endwhile; ?>
            
            	<?php wp_reset_postdata(); ?>
            
            <?php endif; ?>
            
            </div>
         </div>
        <a class="prev carousel-nav" href="#"><i class="icomoon-chevron-left"></i></a>
        <a class="next carousel-nav" href="#"><i class="icomoon-chevron-right"></i></a>
         
    </div><!-- .single-box .related-posts -->

<?php } ?>