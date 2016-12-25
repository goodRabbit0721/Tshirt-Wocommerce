<?php 
/**
 * Custom Slider
 * Page Composer Section
 *
 * @package SimpleMag
 * @since   SimpleMag 2.0
**/
?>

<section class="custom-slider">

    <?php if( get_sub_field( 'custom_add_new_slide' ) ) : ?>
    
    <div class="flexslider posts-slider loading">
        <ul class="slides">
    
        <?php while( has_sub_field('custom_add_new_slide' ) ) : ?>

            <li class="content-over-image">
                <figure>
                    <?php if ( get_sub_field( 'custom_slide_image' ) ) {
                        $attachment_id = get_sub_field( 'custom_slide_image' );
                        $image_size = 'big-size';
                        $slide_image = wp_get_attachment_image_src( $attachment_id, $image_size );
                    ?>
                        <img src="<?php echo $slide_image[0]; ?>" width="<?php echo $slide_image[1]; ?>" height="<?php echo $slide_image[2]; ?>" class="attachment-big-size wp-post-image" alt="<?php the_sub_field( 'custom_slide_title' ); ?>" />
                    <?php } else { ?>
                        <img class="alter" src="<?php echo get_template_directory_uri(); ?>/images/pixel.gif" alt="<?php the_title(); ?>" />
                    <?php } ?>

                    <?php if ( get_sub_field( 'custom_slide_url' ) ) { ?>
                        <a class="entry-link" href="<?php the_sub_field( 'custom_slide_url' ); ?>"></a>
                    <?php } ?>
                </figure>

                <header class="entry-header">
                    <div class="inner">
                        <div class="inner-cell">
                            <h2 class="entry-title">
                                <?php the_sub_field( 'custom_slide_title' ); ?>
                            </h2>                    
                            <?php if ( get_sub_field( 'custom_button_text' ) ) { ?>
                                <a class="read-more" href="<?php the_sub_field( 'custom_slide_url' ); ?>"><?php the_sub_field( 'custom_button_text' ); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </header>
            </li>

        <?php endwhile; ?>

        </ul>
     </div>
     
    <?php endif; ?>

</section><!-- Custom Slider -->