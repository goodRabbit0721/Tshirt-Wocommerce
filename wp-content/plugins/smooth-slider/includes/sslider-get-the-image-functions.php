<?php
/**
 * Almost all of the below code belongs to 'Get the Image' WordPress plugin by Justin Tadlock. This is the perfect code for extracting post images, I have just changed the function * names specific to Smooth Slider, as someone would like to use the 'Get the Image' WordPress plugin separately. 
 * Reference plugin - Get The Image
 * Reference Plugin URI - http://justintadlock.com/archives/2008/05/27/get-the-image-wordpress-plugin
 * Get the Image Plugin Author: Justin Tadlock
 * Get the Image Plugin Author URI: http://justintadlock.com
 *
 * Get the Image was created to solve a problem in the WordPress community about how to handle
 * post-specific images. It was created to be a highly-intuitive image script that loads images that are 
 * related to specific posts in some way.  It creates an image-based representation of a WordPress 
 * post (or any post type).
 *
 * @copyright 2008 - 2010
 * @version 0.5
 * @author Justin Tadlock
 * @link http://justintadlock.com/archives/2008/05/27/get-the-image-wordpress-plugin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

/* Adds theme support for post images. */
add_theme_support( 'post-thumbnails' );

/**
 * This is a highly intuitive function that gets images.  It first calls for custom field keys. If no 
 * custom field key is set, check for the_post_thumbnail().  If no post image, check for images 
 * attached to post. Check for image order if looking for attached images.  Scan the post for 
 * images if $image_scan = true.  Check for default image if $default_image = true. If an image 
 * is found, call smooth_sslider_display_the_image() to format it.
 *
 * @since 0.1
 * @global $post The current post's DB object.
 * @param array $args Parameters for what image to get.
 * @return string|array The HTML for the image. | Image attributes in an array.
 */
function smooth_sslider_get_the_image( $args = array() ) {
	global $post;
	if(isset($post->ID))$post_id = $post->ID;
	else $post_id = 0;
	$permalink = get_permalink( $post_id );

	/* Set the default arguments. */
	$defaults = array(
		'custom_key' => array( 'Thumbnail', 'thumbnail' ),
		'post_id' => $post_id,
		'attachment' => true,
		'the_post_thumbnail' => true, // WP 2.9+ image function
		'default_size' => false, // Deprecated 0.5 in favor of $size
		'size' => 'thumbnail',
		'default_image' => false,
		'order_of_image' => 1,
		'link_to_post' => true,
		'image_class' => false,
		'image_scan' => false,
		'width' => false,
		'height' => false,
		'format' => 'img',
		'echo' => true,
		'permalink' => $permalink,
		'style'=>'',
		'a_attr'=> ''
	);

	/* Allow plugins/themes to filter the arguments. */
	$args = apply_filters( 'smooth_sslider_get_the_image_args', $args );

	/* Merge the input arguments and the defaults. */
	$args = wp_parse_args( $args, $defaults );
	/*print_r($args);*/

	/* If $default_size is given, overwrite $size. */
	if ( !empty( $args['default_size'] ) )
		$args['size'] = $args['default_size'];

	/* If $format is set to 'array', don't link to the post. */
	if ( 'array' == $args['format'] )
		$args['link_to_post'] = false;

	/* Extract the array to allow easy use of variables. */
	extract( $args );

		/* If a custom field key (array) is defined, check for images by custom field. */
		if ( $custom_key )
			$image = smooth_sslider_image_by_custom_field( $args );

		/* If no image found and $the_post_thumbnail is set to true, check for a post image (WP feature). */
		if ( empty($image) && $the_post_thumbnail )
			$image = smooth_sslider_image_by_the_post_thumbnail( $args );

		/* If no image found and $attachment is set to true, check for an image by attachment. */
		if ( empty($image) && $attachment )
			$image = smooth_sslider_image_by_attachment( $args );

		/* If no image found and $image_scan is set to true, scan the post for images. */
		if ( empty($image) && $image_scan )
			$image = smooth_sslider_image_by_scan( $args );

		/* If no image found and a $default_image is set, get the default image. */
		if ( empty($image) && $default_image )
			$image = smooth_sslider_image_by_default( $args );

		/* If an image is returned, run it through the display function. */
		if ( isset($image) )
			$image = smooth_sslider_display_the_image( $args, $image );
		else $image='';

	/* Allow plugins/theme to override the final output. */
	$image = apply_filters( 'smooth_sslider_get_the_image', $image );
	
	/*print_r($image);*/

	/* Display the image if $echo is set to true and the $format isn't an array. Else, return the image. */
	if ( 'array' == $format ) {
		$atts = wp_kses_hair( $image, array( 'http' ) );

		foreach ( $atts as $att )
			$out[$att['name']] = $att['value'];

		$out['url'] = $out['src']; // @deprecated 0.5 Use 'src' instead of 'url'.
		return $out;
	}
	elseif ( $echo )
		echo $image;
	else
		return $image;
}

/* Internal Functions */

/**
 * Calls images by custom field key.  Script loops through multiple custom field keys.
 * If that particular key is found, $image is set and the loop breaks.  If an image is 
 * found, it is returned.
 *
 * @since 0.3
 * @param array $args
 * @return array|bool
 */
function smooth_sslider_image_by_custom_field( $args = array() ) {

	/* If $custom_key is a string, we want to split it by spaces into an array. */
	if ( !is_array( $args['custom_key'] ) )
		$args['custom_key'] = preg_split( '#\s+#', $args['custom_key'] );

	/* If $custom_key is set, loop through each custom field key, searching for values. */
	if ( isset( $args['custom_key'] ) ) {
		foreach ( $args['custom_key'] as $custom ) {
			$image = get_metadata( 'post', $args['post_id'], $custom, true );
			if ( $image )
				break;
		}
	}

	/* If a custom key value has been given for one of the keys, return the image URL. */
	if ( $image )
		return array( 'url' => $image );

	return false;
}

/**
 * Checks for images using a custom version of the WordPress 2.9+ get_the_post_thumbnail()
 * function.  If an image is found, return it and the $post_thumbnail_id.  The WordPress function's
 * other filters are later added in the smooth_sslider_display_the_image() function.
 *
 * @since 0.4
 * @param array $args
 * @return array|bool
 */
function smooth_sslider_image_by_the_post_thumbnail( $args = array() ) {

	/* Check for a post image ID (set by WP as a custom field). */
	$post_thumbnail_id = get_post_thumbnail_id( $args['post_id'] );

	/* If no post image ID is found, return false. */
	if ( empty( $post_thumbnail_id ) )
		return false;
	/* Added for category Slider  */
	if( !wp_attachment_is_image($post_thumbnail_id) ) 
		return false;
	/* Apply filters on post_thumbnail_size because this is a default WP filter used with its image feature. */
	$size = apply_filters( 'post_thumbnail_size', $args['size'] );

	/* Get the attachment image source.  This should return an array. */
	$image = wp_get_attachment_image_src( $post_thumbnail_id, $size );

	/* Get the attachment excerpt to use as alt text. */
	$alt = trim( strip_tags( get_post_field( 'post_excerpt', $post_thumbnail_id ) ) );

	/* Return both the image URL and the post thumbnail ID. */
	return array( 'url' => $image[0], 'post_thumbnail_id' => $post_thumbnail_id, 'alt' => $alt );
}

/**
 * Check for attachment images.  Uses get_children() to check if the post has images 
 * attached.  If image attachments are found, loop through each.  The loop only breaks 
 * once $order_of_image is reached.
 *
 * @since 0.3
 * @param array $args
 * @return array|bool
 */
function smooth_sslider_image_by_attachment( $args = array() ) {

	/* Get attachments for the inputted $post_id. */
	$attachments = get_children( array( 'post_parent' => $args['post_id'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) );
	$i = 0;
	/* If no attachments are found, check if the post itself is an attachment and grab its image. */
	if ( empty( $attachments ) && $args['size'] ) {
		if ( 'attachment' == get_post_type( $args['post_id'] ) ) {
			$image = wp_get_attachment_image_src( $args['post_id'], $args['size'] );
			$alt = trim( strip_tags( get_post_field( 'post_excerpt', $args['post_id'] ) ) );
		}
	}

	/* If no attachments or image is found, return false. */
	if ( empty( $attachments ) && empty( $image ) )
		return false;

	/* Loop through each attachment. Once the $order_of_image (default is '1') is reached, break the loop. */
	foreach ( $attachments as $id => $attachment ) {
		if ( ++$i == $args['order_of_image'] ) {
			$image = wp_get_attachment_image_src( $id, $args['size'] );
			$alt = trim( strip_tags( get_post_field( 'post_excerpt', $id ) ) );
			break;
		}
	}

	/* Return the image URL. */
	return array( 'url' => $image[0], 'alt' => $alt );
}

/**
 * Scans the post for images within the content.  Not called by default with smooth_sslider_get_the_image().
 * Shouldn't use if using large images within posts, better to use the other options.
 *
 * @since 0.3
 * @global $post The current post's DB object.
 * @param array $args
 * @return array|bool
 */
function smooth_sslider_image_by_scan( $args = array() ) {

	/* Search the post's content for the <img /> tag and get its URL. */
	preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );

	/* If there is a match for the image, return its URL. */
	if ( isset( $matches ) )
		if( isset($matches[1][0]) )
			return array( 'url' => $matches[1][0] );

	return false;
}

/**
 * Used for setting a default image.  The function simply returns the image URL it was
 * given in an array.  Not used with smooth_sslider_get_the_image() by default.
 *
 * @since 0.3
 * @param array $args
 * @return array
 */
function smooth_sslider_image_by_default( $args = array() ) {
	return array( 'url' => $args['default_image'] );
}

/**
 * Formats an image with appropriate alt text and class.  Adds a link to the post if argument 
 * is set.  Should only be called if there is an image to display, but will handle it if not.
 *
 * @since 0.1
 * @param array $args
 * @param array $image Array of image info ($image, $classes, $alt, $caption).
 * @return string $image Formatted image (w/link to post if the option is set).
 */
function smooth_sslider_display_the_image( $args = array(), $image = false ) {

	/* If there is no image URL, return false. */
	if ( empty( $image['url'] ) )
		return false;

	/* Extract the arguments for easy-to-use variables. */
	extract( $args );

	/* If there is alt text, set it.  Otherwise, default to the post title. */
	$image_alt = ( isset( $image['alt'] ) ? $image['alt'] : apply_filters( 'the_title', get_post_field( 'post_title', $post_id ) ) );

	/* If there is a width or height, set them as HMTL-ready attributes. */
	$width = ( isset( $width ) ? ' width="' . esc_attr( $width ) . '"' : '' );
	$height = ( isset( $height ) ? ' height="' . esc_attr( $height ) . '"' : '' );
	$style = ( isset( $style ) ?   ' '.$style   : '' );
	$a_attr = ( isset( $a_attr ) ?   ' '.$a_attr   : '' );

	/* Loop through the custom field keys and add them as classes. */
	if ( is_array( $custom_key ) ) {
		foreach ( $custom_key as $key )
			$classes[] = str_replace( ' ', '-', strtolower( $key ) );
	}

	/* Add the $size and any user-added $image_class to the class. */
	$classes[] = $size;
	$classes[] = $image_class;

	/* Join all the classes into a single string and make sure there are no duplicates. */
	$class = join( ' ', array_unique( $classes ) );

	/* If there is a $post_thumbnail_id, apply the WP filters normally associated with get_the_post_thumbnail(). */
	if (isset ($image['post_thumbnail_id'])) {
		if ( $image['post_thumbnail_id'] )
			do_action( 'begin_fetch_post_thumbnail_html', $post_id, $image['post_thumbnail_id'], $size );
	}

	/* Add the image attributes to the <img /> element. */
	$html = '<img src="' . $image['url'] . '" alt="' . esc_attr( strip_tags( $image_alt ) ) . '" class="' . esc_attr( $class ) . '"' . $width . $height . $style .' />';

	/* If $link_to_post is set to true, link the image to its post. */
	if ( $link_to_post ) {
		$html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( apply_filters( 'the_title', get_post_field( 'post_title', $post_id ) ) ) . '" '.$a_attr.'>' . $html . '</a>';}
	else {
	    if($permalink!='') {
	      $html = '<a href="' . $permalink . '" title="' . esc_attr( apply_filters( 'the_title', get_post_field( 'post_title', $post_id ) ) ) . '" '.$a_attr.'>' . $html . '</a>';
		}
	}

	/* If there is a $post_thumbnail_id, apply the WP filters normally associated with get_the_post_thumbnail(). */
	if (isset ($image['post_thumbnail_id'])) {
		if ( $image['post_thumbnail_id'] )
			do_action( 'end_fetch_post_thumbnail_html', $post_id, $image['post_thumbnail_id'], $size );
	}

	/* If there is a $post_thumbnail_id, apply the WP filters normally associated with get_the_post_thumbnail(). */
	if (isset ($image['post_thumbnail_id'])) {
		if ( $image['post_thumbnail_id'] )
			$html = apply_filters( 'post_thumbnail_html', $html, $post_id, $image['post_thumbnail_id'], $size, '' );
	}

	return $html;
}

/**
 * Get the image with a link to the post.  Use smooth_sslider_get_the_image() instead.
 *
 * @since 0.1
 * @deprecated 0.3
 */
function smooth_sslider_get_the_image_link( $deprecated = '', $deprecated_2 = '', $deprecated_3 = '' ) {
	smooth_sslider_get_the_image();
}

?>
