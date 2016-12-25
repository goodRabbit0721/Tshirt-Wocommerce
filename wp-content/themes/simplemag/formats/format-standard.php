<?php 
/** 
 * Standard post format
 *
 * @package SimpleMag
 * @since 	SimpleMag 2.2
**/ 
global $ti_option;

if ( $ti_option['single_featured_image'] == 1 ) {

	if ( $ti_option['single_media_position'] == 'useperpost' && get_post_meta( $post->ID, 'post_media_position', true ) == 'media_full_width' || $ti_option['single_media_position'] == 'fullwidth' ) {
		$standard_size = 'big-size';
	}
	elseif(  $ti_option['single_media_position'] == 'useperpost' && get_post_meta( $post->ID, 'post_media_position', true ) == 'media_above_content' || $ti_option['single_media_position'] == 'abovecontent' ) {
		$standard_size = 'medium-size';
	}

	if ( has_post_thumbnail() ) {
		echo '<figure class="base-image">';
			the_post_thumbnail( $standard_size, array('itemprop' => 'image') );
			if (get_post(get_post_thumbnail_id() )->post_excerpt != ''){
				echo '<span class="icon"></span><figcaption class="image-caption">' . get_post(get_post_thumbnail_id())->post_excerpt . '</figcaption>';
			}
		echo '</figure>';
	}
	
}