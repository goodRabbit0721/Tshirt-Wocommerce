<?php 
/**
 * Gallery (Carousel) post format
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.3
**/ 

// Check if gallery was uploaded
if( get_field( 'post_upload_gallery' ) ) : ?>

    <div id="gallery-carousel">
        <ul class="carousel">
        <?php
        // Output the uploaded images as gallery
        $images = get_field( 'post_upload_gallery' );
        if ( $images ):
            foreach( $images as $image ):
    		
                if ( !empty ( $image['caption'] ) ) {
                    $alt = $image['caption'];
    			} elseif ( !empty ( $image['alt'] ) ) {
    				$alt = $image['alt'];
                } else {
                    $alt = $image['title'];
                }
    			
    			$img_width = $image['sizes']['gallery-carousel-width'];
    			$img_height = $image['sizes']['gallery-carousel-height'];
    			echo '<li class="gallery-item">
    					<figure>
    						<img src="' . $image['url'] . '" alt="' . $alt . '" width="' . $img_width . '" height="' . $img_height . '" />';
    						if ( $image['caption'] ) {
    						echo '<span class="icon"></span><figcaption class="image-caption">' . $image['caption'] . '</figcaption>';
    						}
    					echo'
    					</figure>
    				</li>';
    			
            endforeach;
        endif;
        
        ?>
        </ul>
        <a class="carousel-nav prev" href="#"><i class="icomoon-chevron-left"></i></a>
        <a class="carousel-nav next" href="#"><i class="icomoon-chevron-right"></i></a>
    </div>

<?php endif; ?>