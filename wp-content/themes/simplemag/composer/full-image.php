<?php 
/**
 * Full Width Image
 * Page Composer Section
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/
?>

<section class="home-section full-width-image">

    <header class="section-header">
        <?php $image_bg = get_sub_field( 'full_image_upload' ); ?>
        <div class="title-with-bg" style="background-image:url(<?php echo $image_bg['url']; ?>);">
            <div class="inner">
                <h2 class="title"><?php the_sub_field( 'full_image_main_title' ); ?></h2>
                <?php if( get_sub_field( 'full_image_sub_title' ) ): ?>
                <span class="sub-title"><?php the_sub_field( 'full_image_sub_title' ); ?></span>
                <?php endif; ?>
            
                <?php 
                // Button
                $button_text = get_sub_field( 'full_image_button_text' );
                $button_url = get_sub_field( 'full_image_button_url' );
                
                if ( $button_text == true ) {
                    echo '<a class="read-more" href="' . esc_url( $button_url ) . '">' . $button_text .'</a>';
                }
                ?>
            </div>
        </div>
    </header>

</section><!-- Full Width Image -->