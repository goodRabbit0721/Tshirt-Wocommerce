<?php 
/**
 * Video post format
 * Display video embed code from custom meta field
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/ 

// Output the video by page url
$video_embed = wp_oembed_get( get_post_meta( $post->ID, 'add_video_url', true ) );
echo '<figure class="video-wrapper">' . $video_embed . '</figure>';
?>