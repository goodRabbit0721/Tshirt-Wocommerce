<?php 
/**
 * Latest Posts by Format
 * Page Composer Section
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
**/
global $ti_option;

// Define section background color
if ( get_sub_field( 'format_background_color' ) != '' ) {
    $section_bg = 'style="background:' . get_sub_field( 'format_background_color' ) . ';"';
}

// Define section text color
if ( get_sub_field( 'format_text_color' ) != '' ) {
    $section_text = ' style="color:' . get_sub_field( 'format_text_color' ) . ';"';
}
$section_text_color = isset( $section_text ) ? $section_text : '';

// Add class if section background is not white or empty
if ( get_sub_field( 'format_background_color' ) != '#ffffff' && get_sub_field( 'format_background_color' ) != '' ) {
    $format_bg = ' format-bg';
}
?>
<section class="wrapper home-section format-posts<?php echo isset( $format_bg ) ? $format_bg : ''; ?>" <?php echo isset( $section_bg ) ? $section_bg : ''; ?>>

    <?php if( get_sub_field( 'format_main_title' ) ): ?>
    <header class="section-header">
        <div class="title-with-sep">
            <h2 class="title"<?php echo $section_text_color; ?>><?php the_sub_field( 'format_main_title' ); ?></h2>
        </div>
        <?php if ( get_sub_field( 'format_sub_title' ) ): ?>
        <span class="sub-title" <?php echo $section_text_color; ?>><?php the_sub_field( 'format_sub_title' ); ?></span>
        <?php endif; ?>
    </header>
    <?php endif; ?>
    
	<?php
	/**
	 * Get the format name which will filter the section
	 * Check if format is standard or something else
	**/
	$format_name = get_sub_field( 'format_section_name' );
	
	if ( get_sub_field( 'format_section_name' ) == 'standard' ):
		$format_args = array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' =>  array( 'post-format-video', 'post-format-gallery', 'post-format-audio' ),
				'operator' => 'NOT IN'
			);
	else:
		$format_args = array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-'.$format_name
			);
	endif;
	
	$posts_to_show = get_sub_field( 'format_posts_per_page' );
	$ti_format_posts = new WP_Query(
		array(
			'post_type' => 'post',
			'posts_per_page' => $posts_to_show,
			'tax_query' => array( $format_args )
		)
	);
	?>
            
    <div class="grids entries">
    
        <?php if ( $ti_format_posts->have_posts() ) : 
                while ( $ti_format_posts->have_posts() ) : $ti_format_posts->the_post(); ?>
    
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
                        <h2 class="entry-title" >
                            <a href="<?php the_permalink(); ?>" <?php echo $section_text_color; ?>><?php the_title(); ?></a>
                        </h2>
                    </header>
                </article>
        
        <?php endwhile; ?>
        
        <?php wp_reset_postdata(); ?>

    </div>
        
    <?php else: ?>
    
    <p class="message">
        <?php _e( 'There are no posts with this format yet', 'themetext' ); ?>
    </p>
        
    <?php endif; ?>
    
</section><!-- Latest by Format -->