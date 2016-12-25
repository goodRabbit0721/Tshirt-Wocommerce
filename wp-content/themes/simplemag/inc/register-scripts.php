<?php
/**
 * Register jQuery scripts and 
 * CSS Styles only for the front-end
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/

function ti_theme_scripts(){
	
	/**
	 * Register CSS styles
	 */
	
	/* Main theme style */
	wp_enqueue_style('main-style', get_stylesheet_directory_uri() . '/style.css', 'style');


	/**
	 * Register jQuery scripts
	 */
	
	/* Blog single comments reply */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply', array(), '', true );
	}


	/* Knob (post rating) */
	if( is_single() && get_field( 'enable_rating' ) == 1 ) {
		wp_enqueue_script( 'knob', get_template_directory_uri() . '/js/jquery.knob.js', 'jquery', '1.2.0', true );
	}
	
	/**
	 * Flex Slider for posts and custom sliders and widgets
	**/
	wp_register_script( 'ti-flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', 'jquery', '2.2.2', true );
	
	// Pages with Page Composer and carousel section enabled
	if( is_page() ) {
		while( have_rows( 'page_composer' ) ) : the_row();
			if ( get_row_layout() == 'hp_posts_slider' || get_row_layout() == 'custom_slider' ):
				wp_enqueue_script( 'ti-flexslider' );
			endif;
		endwhile;
	}

	// Categories Slider
	if ( is_category() && get_field('category_slider', 'category_' . get_query_var('cat') ) == 'cat_slider_on' ){
		wp_enqueue_script( 'ti-flexslider' );
	}

	// If Featured Posts, Latest Posts and Latest By Category widgets are active
	if ( is_active_widget( '', '', 'ti_latest_posts' ) || is_active_widget( '', '', 'ti_featured_posts' ) || is_active_widget( '', '', 'ti_latest_cat_posts' ) ){
			wp_enqueue_script( 'ti-flexslider' );
	}


	/**
	 * CarouFredSel for gallery carousle, related posts nad authors widget
	**/
	wp_register_script( 'caroufredsel', get_template_directory_uri() . '/js/jquery.caroufredsel.js', 'jquery', '6.2.1', true );

	// Pages with Page Composer and carousel section enabled
	if( is_page() ) {
		while( have_rows( 'page_composer' ) ) : the_row();
			if ( get_row_layout() == 'hp_posts_carousel' ):
				wp_enqueue_script( 'caroufredsel' );
			endif;
		endwhile;
	} 

	// Single Post with gallery or related posts enabled
	if ( is_single() ) {
		global $ti_option;
		if ('gallery' == get_post_format() || $ti_option['single_related'] == 1 ) {
			wp_enqueue_script( 'caroufredsel' );
		}
	}

	// If authors widget is active
	if ( is_active_widget( '', '', 'ti_site_authors' ) ) {
			wp_enqueue_script( 'caroufredsel' );
	}

	/* IE scripts */
	if(preg_match('/(?i)msie [6-9]/',$_SERVER['HTTP_USER_AGENT'])) { // if IE<=9
		wp_enqueue_script( 'ti-ie' , get_template_directory_uri() . '/js/oldie.js', '', '' );
	}
		
	/* jQuery plugins */
	wp_enqueue_script( 'ti-assets', get_template_directory_uri() . '/js/jquery.assets.js', 'jquery', '1.0', true );
		
	/* Custom jQuery scripts */
	wp_enqueue_script( 'ti-custom', get_template_directory_uri() . '/js/jquery.custom.js', 'jquery', '1.0', true );
	
	/* Always load only the latest jQuery library version */
	wp_enqueue_script( 'jquery' );
	
}
	
add_action( 'wp_enqueue_scripts', 'ti_theme_scripts' );


/* Header custom JS */
function header_scripts(){
	global $ti_option;
	if ( ! empty ( $ti_option['custom_js_header'] ) ){
		echo '<script type="text/javascript">'."\n",
				$ti_option['custom_js_header']."\n",
			 '</script>'."\n";
	}
}
add_action('wp_head', 'header_scripts');


/* Footer custom JS */
function footer_scripts(){
	global $ti_option;
	if ( ! empty ( $ti_option['custom_js_footer'] ) ) {
		echo '<script type=\'text/javascript\'>'.$ti_option['custom_js_footer'].'</script>'."\n";
	}
}
add_action( 'wp_footer', 'footer_scripts', 100 );