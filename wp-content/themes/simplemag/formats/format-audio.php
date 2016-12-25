<?php 
/**
 * Audio format post
 * Display audio embed code from SoundCloud from custom meta field
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.4
**/ 
	
// Output SoundCloud iframe by page url
$audio_embed = wp_oembed_get( get_post_meta( $post->ID, 'add_audio_url', true ) );
echo $audio_embed;
?>