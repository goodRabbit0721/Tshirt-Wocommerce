<?php
/**
 * SimpleMag functions and definitions
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/
include_once ( 'cropupload_helper.php' );

/* Install plugins for theme use */
include_once ( 'admin/tgm/tgm-init.php' );


/* Content Width */
if ( ! isset( $content_width ) ) 
	$content_width = 1050; /* pixels */


/* Theme Setup */
function ti_theme_setup() {

	/* Register Menus  */
	register_nav_menus( array(
		'main_menu' => __( 'Main Menu', 'themetext' ), // Main site menu
		'secondary_menu' => __( 'Secondary Menu', 'themetext' ), // Main site menu
		'footer_menu' => __( 'Footer Menu', 'themetext' ), // Footer site menu
	));

	/*  Post Formats */
	add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio' ) );

	/* Images */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'rectangle-size', 330, 220, true );
	add_image_size( 'rectangle-size-small', 296, 197, true );
	add_image_size( 'masonry-size', 330, 9999 );
	add_image_size( 'medium-size', 690, 9999 );
	add_image_size( 'big-size', 1050, 9999 );
	global $ti_option;
	add_image_size( 'gallery-carousel', 9999, $ti_option['site_carousel_height'] );

	/* Enable post and comment RSS feed links */
	add_theme_support( 'automatic-feed-links' );

	/* Theme localization */
	load_theme_textdomain( 'themetext', get_template_directory() . '/languages' );
	
}
add_action( 'after_setup_theme', 'ti_theme_setup' );


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
**/
function ti_wp_title( $title, $sep ) {

	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'simpletheme' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'ti_wp_title', 10, 2 );



/**
 * Add classes to the body tag
**/
function ti_body_classes( $classes ){

	global $post, $ti_option;
	
	if ( !is_rtl() ) {
		$classes[] = 'ltr';
	}

	// Page Name as body class name
	if ( is_page() ) {
		$page_name = $post->post_name;
		$classes[] = 'page-'.$page_name;
 	} 

	// If page have sidebar enabled
	if ( get_field( 'page_sidebar' ) == 'page_sidebar_on' || get_field( 'comp_page_sidebar' ) == 'comp_sidebar_page' || get_field('category_slider', 'category_' . get_query_var('cat') ) == 'cat_slider_on' && get_field('category_slider_position', 'category_' . get_query_var('cat') ) == 'cat_slider_above' && get_field( 'category_sidebar', 'category_' . get_query_var('cat') ) == 'cat_sidebar_on' ) { 
		$classes[] = 'with-sidebar';
	}

	// Text Alignmnet Left for the whole site
	if ( $ti_option['text_alignment'] == '2' ) {
		$classes[] = 'text-left';
	}

	return $classes;
}
add_filter( 'body_class', 'ti_body_classes' );



/**
 * Add classes to top strip based on Theme Options selections
**/
function ti_top_strip_class(){

	global $ti_option;

	// Hide/Show top strip
	if ( $ti_option['site_top_strip'] == 0 ) { echo ' hide-strip'; }

	// Make top strip fixed
	if ( $ti_option['site_fixed_menu'] == '2' ) { echo ' top-strip-fixed'; }

	// If top strip have white background
	if ( $ti_option['site_top_strip_bg'] == '#ffffff') { echo ' color-site-white'; }

}


/**
 * Posts Meta Data. Category and Date.
**/
function ti_meta_data(){
	
	global $ti_option;
	
	// Category Name
	if ( is_single() ) {
		if ( $ti_option['single_post_cat_name'] == true ) {
			echo '<span class="entry-category">'; the_category(', '); echo '</span>';
		}
	} else {
		echo '<span class="entry-category">'; the_category(', '); echo '</span>';
	}
	
	// Date
	$publish_date = '<time class="entry-date updated" datetime="' . get_the_time( 'c' ) . '" itemprop="datePublished">' . get_the_time( get_option( 'date_format' ) ) . '</time>';
	
	if ( is_home() || is_front_page() || is_page() ) {
		if ( $ti_option['home_post_date'] == 1 ) {
    		echo $publish_date;
		}
	} 
	if ( is_category() || is_tag() || is_author() ) {
		if ( $ti_option['archive_post_date'] == 1 ) {
    		echo $publish_date;
		}
	}
	if ( is_single() ) {
		if ( $ti_option['single_post_date'] == 1 ) {
    		echo $publish_date;
		}
	}
}


/**
 * Calculate to total score for posts with Rating feature is enabled
 *
 * Applies to:
 * 1. Latest Reviews & Latest Posts sections
 * 2. Latest Reviews widget
 * 3. Single Post
**/
function ti_rating_calc() {

    $score_rows = get_field( 'rating_module' );
    $score = array();
    
    // Loop through the scores
    if ( $score_rows ){
        foreach( $score_rows as $key => $row ){
            $score[$key] = $row['score_number'];
        }
    
	    $score_items = count( $score ); // Count the scores
	    $score_sum = array_sum( $score ); // Get the scores summ
	    $score_total = $score_sum / $score_items; // Get the score result

	    return $score_total;
	}
}
add_filter( 'ti_score_total', 'ti_rating_calc' );




/**
 * Add Previous & Next links to a numbered link list 
 * of wp_link_pages() if single post is paged
 */
function ti_wp_link_pages( $args ){

    global $page, $numpages, $more;

    if ( !$args['next_or_number'] == 'next_and_number' ){
        return $args;
    }
	
	// Keep numbers for the main part
    $args['next_or_number'] = 'number'; 
    if (!$more){
        return $args;
    }
	
	// If previous page exists
    if( $page-1 ){
        $args['before'] .= _wp_link_page($page-1) . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';
    }

	// If next page exists
    if ( $page<$numpages ){
        $args['after'] = _wp_link_page($page+1) . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>' . $args['after'];
    }

    return $args;
}
add_filter( 'wp_link_pages_args', 'ti_wp_link_pages' );



/* Theme Options */
require_once( 'admin/theme-options.php' );


/* Custom Fields */
include_once( 'admin/acf/acf.php' );
include_once( 'admin/acf-fields/acf-fields.php' );
include_once( 'admin/acf-fields/add-ons/acf-wp-editor/acf-wp_wysiwyg.php' );
define( 'ACF_LITE', true );


/* Includes */
include_once( 'inc/user-fields.php' );
include_once( 'inc/mega-menu.php' );
include_once( 'inc/styling-options.php' );
include_once( 'inc/pagination.php' );
global $ti_option;
if ( $ti_option['site_custom_gallery'] == true ) {
	include_once( 'inc/wp-gallery.php' );
}


/* Widgets */
include_once( 'widgets/ti-video.php' );
include_once( 'widgets/ti-authors.php' );
include_once( 'widgets/ti-about-site.php' );
include_once( 'widgets/ti-latest-posts.php' );
include_once( 'widgets/ti-code-banner.php' );
include_once( 'widgets/ti-image-banner.php' );
include_once( 'widgets/ti-latest-reviews.php' );
include_once( 'widgets/ti-featured-posts.php' );
include_once( 'widgets/ti-most-commented.php' );
include_once( 'widgets/ti-latest-comments.php' );
include_once( 'widgets/ti-latest-category-posts.php' );


/* Register jQuery Scripts and CSS Styles */
include_once( 'inc/register-scripts.php' );


/**
 * Excerpt length
 * Excerpt more
*/
// Excerpt Length
function ti_excerpt_length( $length ) {
	global $ti_option;
	return $ti_option['site_wide_excerpt_length'];
}
add_filter( 'excerpt_length', 'ti_excerpt_length' );

// Excerpt more
function ti_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'ti_excerpt_more' );


/**
 * Different image size based on layout selection for Homepage, Categories and Posts Page
*/
function ti_layout_based_post_image() {

	$itemprop = array('itemprop' => 'image');

	if ( has_post_thumbnail() ) { // Set Featured Image
		// Images for Posts Page or if this page is used as Homepage with "Your latest posts" option
		if ( is_home() ) {
			global $ti_option;
            if ( $ti_option['posts_page_layout'] == 'grid-layout' || $ti_option['posts_page_layout'] == 'list-layout' ) {
                the_post_thumbnail( 'rectangle-size', $itemprop );
            } elseif (  $ti_option['posts_page_layout'] == 'classic-layout' ) {
                the_post_thumbnail( 'big-size', $itemprop );
            } else {
                the_post_thumbnail( 'masonry-size', $itemprop );
            }
        // Images for Homepage used with page composer and Categories
        } else {
        	if ( get_sub_field ( 'latest_posts_layout' ) == 'list-layout' || get_field ( 'category_posts_layout', 'category_' . get_query_var('cat') ) == 'list-layout' || get_sub_field ( 'latest_posts_layout' ) == 'grid-layout' || get_field ( 'category_posts_layout', 'category_' . get_query_var('cat') ) == 'grid-layout' ) {
        		the_post_thumbnail( 'rectangle-size', $itemprop );
			} elseif ( get_sub_field ( 'latest_posts_layout' ) == 'classic-layout' || get_field ( 'category_posts_layout', 'category_' . get_query_var('cat') ) == 'classic-layout' ) {
				the_post_thumbnail( 'big-size', $itemprop );
			} else {
				the_post_thumbnail( 'masonry-size', $itemprop );
			}
    	}
    } elseif( first_post_image() ) { // Set the first image from the editor
        echo '<img src="' . first_post_image() . '" class="wp-post-image" alt="' . get_the_title() . '" />';
    }
}


/**
 * Get The First Image From a Post
 */
function first_post_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	if( preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ) ){
		$first_img = $matches[1][0];
		return $first_img;
	}
}



/* Define sidebars */
function register_theme_sidebars() {

	if ( function_exists('register_sidebars') ) {
		
		// Sidebar for blog section of the site
		register_sidebar(
		   array(
			'name' => __( 'Magazine', 'themetext' ),
			'id' => 'sidebar-1',
			'description'   => __( 'Sidebar for categories and single posts', 'themetext' ),		   
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		   )
		);

		register_sidebar(
		   array(
			'name' => __( 'Pages', 'themetext' ),  
			'id' => 'sidebar-2',
			'description'   => __( 'Sidebar for static pages', 'themetext' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		   )
		);

		register_sidebar(
		   array(
			'name' => __( 'Footer Area One', 'themetext' ),  
			'id' => 'sidebar-3',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		   )
		);
		
		register_sidebar(
		   array(
			'name' => __( 'Footer Area Two', 'themetext' ),
			'id' => 'sidebar-4',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		   )
		);
		
		register_sidebar(
		   array(
			'name' => __( 'Footer Area Three', 'themetext' ),  
			'id' => 'sidebar-5',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		   )
		);

	}

}
add_action( 'widgets_init', 'register_theme_sidebars' );


/* Count the number of footer sidebars to enable dynamic classes for the footer */
function ti_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = ' col-1';
			break;
		case '2':
			$class = ' col-2';
			break;
		case '3':
			$class = ' col-3';
			break;
	}

	if ( $class )
		echo $class;
}


/**
 * Remove rel attribute from the category list
 */
function remove_category_list_rel( $output ) {
    return str_replace( 'rel="category tag"', '', $output );
}
add_filter( 'wp_list_categories', 'remove_category_list_rel' );
add_filter( 'the_category', 'remove_category_list_rel' );
/**
 * Remove review woocommerce
 */
add_filter( 'woocommerce_product_tabs', 'sb_woo_remove_reviews_tab', 98);
function sb_woo_remove_reviews_tab($tabs) {
	unset($tabs['reviews']);
	return $tabs;
}

add_action('woocommerce_before_main_content', create_function('', 'echo "<div class=\"wrapper\">";'), 1);

add_action('woocommerce_after_main_content', create_function('', 'echo "</div>";'), 1);




/* Function add product */
if (!function_exists('addwooParentProduct')):
    
    function addwooParentProduct($data) {
		 $current_user = wp_get_current_user();
        if (!($current_user instanceof WP_User))
            return;
        $post = array(
            'post_author' => $current_user->ID,
            'post_content' => $data['content'],
            'post_status' => "publish",
            'post_title' => $data['name'],
            'post_parent' => '',
            'post_type' => "product",
        );
        //Create post
        $new_post_id = wp_insert_post($post, $wp_error);

        wp_set_object_terms($new_post_id, 8, 'product_cat');
        wp_set_object_terms($new_post_id, 'variable', 'product_type');
        
        update_post_meta($new_post_id, '_visibility', 'hidden');
        update_post_meta($new_post_id, '_stock_status', 'instock');
        update_post_meta($new_post_id, 'total_sales', '0');
        update_post_meta($new_post_id, '_downloadable', 'no');
        update_post_meta($new_post_id, '_virtual', 'no');
        update_post_meta($new_post_id, '_regular_price', '1');
        update_post_meta($new_post_id, '_purchase_note', "");
        update_post_meta($new_post_id, '_featured', "no");
        update_post_meta($new_post_id, '_weight', "");
        update_post_meta($new_post_id, '_length', "");
        update_post_meta($new_post_id, '_width', "");
        update_post_meta($new_post_id, '_height', "");
        update_post_meta($new_post_id, '_sku', "");
		$attributes = array('sales_goal'=>$data['sales_goal'],'campaign_length'=>$data['campaign_length']);
		foreach ($attributes as $name => $value) {
			update_post_meta($new_post_id, '_'.$name, $value);
		}
		update_post_meta($new_post_id, '_is_campaign', 1);
		update_post_meta($new_post_id, '_campaign_status', 1);
		//update_post_meta($new_post_id, '_profit', 30);
		//update_post_meta($new_post_id, '_price', $data['price']);
        return $new_post_id;
	}
endif;

/* Function add product */
if (!function_exists('addwooProduct')):
    
    function addwooProduct($data) {
        $current_user = wp_get_current_user();
        if (!($current_user instanceof WP_User))
            return;
    
            
        $post = array(
            'post_author' => $current_user->ID,
            'post_content' => $data['content'],
            'post_status' => "publish",
            'post_title' => $data['name'],
            'post_parent' => '',
            'post_type' => "product",
        );
        //Create post
        $new_post_id = wp_insert_post($post, $wp_error);

        wp_set_object_terms($new_post_id, 8, 'product_cat');
        wp_set_object_terms($new_post_id, 'variable', 'product_type');
        
		update_post_meta($new_post_id, '_des_intro', $data['post_name']);
        update_post_meta($new_post_id, '_visibility', 'hidden');
        update_post_meta($new_post_id, '_stock_status', 'instock');
        update_post_meta($new_post_id, 'total_sales', '0');
        update_post_meta($new_post_id, '_downloadable', 'no');
        update_post_meta($new_post_id, '_virtual', 'no');
        update_post_meta($new_post_id, '_regular_price', '1');
        update_post_meta($new_post_id, '_purchase_note', "");
        update_post_meta($new_post_id, '_featured', "no");
        update_post_meta($new_post_id, '_weight', "");
        update_post_meta($new_post_id, '_length', "");
        update_post_meta($new_post_id, '_width', "");
        update_post_meta($new_post_id, '_height', "");
        update_post_meta($new_post_id, '_sku', "");
		
		 $i = 0;
		$attributes = array('sales_goal'=>$data['sales_goal'],'campaign_length'=>$data['campaign_length']);
		
		
        //$size_meta_design = get_post_meta($data['design_id'],'_available_sizes',true);
        //$size_meta_design = 'M|L|XL';
		//Array for setting attributes
		//$size_attributes =  explode('|',$size_meta_design);
		$size_attributes = $data['sizes'];
		wp_set_object_terms($new_post_id, $size_attributes, 'size');
		
		wp_set_object_terms($new_post_id, $data['colors'], 'color');
	
		$color_space = implode(' | ', $data['colors']);
        $size_space =  implode(' | ', $size_attributes);
        
		$product_attributes = array('size'=>array(
				'name'=>'Size',
				'value'=> $size_space,
				'position'=>'0',
				'is_visible'=>'1',
				'is_variation'=>'1',
				'is_taxonomy'=>'0',
				),'color'=>array(
				'name'=>'Color',
				'value'=> $color_space,
				'position'=>'0',
				'is_visible'=>'1',
				'is_variation'=>'1',
				'is_taxonomy'=>'0',
		));
						
		update_post_meta($new_post_id, '_product_attributes', $product_attributes);
		
		//###################### Add Variation post types for sizes #############################
//insert 5 variations post_types for 2xl, xl, lg, md, sm:
	$i=1;
    $n_size = count($size_attributes);
    while ($i<= $n_size ) {
        $my_post = array(
        'post_title'=> 'Variation #' . $i . ' of '.$n_size.' for prdct#'. $new_post_id,
        'post_name' => 'product-' . $new_post_id . '-variation-' . $i,
        'post_status' => 'publish',
        'post_parent' => $new_post_id,//post is a child post of product post
        'post_type' => 'product_variation',//set post type to product_variation
        'guid'=>home_url() . '/?product_variation=product-' . $new_post_id . '-variation-' . $i
        );

        //Insert ea. post/variation into database:
        $attID = wp_insert_post( $my_post );
        $logtxt .= "Attribute inserted with ID: $attID\n";
        for($j=1;$j<=$n_size;$j++){
             $variation_id = $new_post_id + $j;
             addVariationProductChildren($variation_id, $size_attributes[$n_size-$j],$data, $size_attributes);
        }
        $i++;
    }
		foreach ($attributes as $name => $value) {
			update_post_meta($new_post_id, '_'.$name, $value); 
		}
		update_post_meta($new_post_id, '_is_campaign', 1);
		update_post_meta($new_post_id, '_campaign_status', 1);
		update_post_meta($new_post_id, '_profit', 30);
		update_post_meta($new_post_id, '_price', $data['price']);
        return $new_post_id;
    }

endif;
/* Function add variation product */
if (!function_exists('addVariationProduct')) :
function addVariationProduct(){


}
endif;
/* Function add variation product */
if (!function_exists('addVariationProductChildren')) :
function addVariationProductChildren($variation_child_id,$size,$data,$avail_attributes){
    //Create sm variation for ea product_variation:
    update_post_meta( $variation_child_id, 'attribute_size', $size);
    update_post_meta( $variation_child_id, '_price', $data['price'] );
    update_post_meta( $variation_child_id, '_regular_price', $data['price']);
    wp_set_object_terms($variation_child_id, $avail_attributes, 'size');
    $thedata = Array('size'=>Array(
    'name'=>'Size',
    'value'=>'',
    'is_visible' => '1', 
    'is_variation' => '1',
    'is_taxonomy' => '1'
    ),
	'color'=>Array(
    'name'=>'Color',
    'value'=>'',
    'is_visible' => '1', 
    'is_variation' => '1',
    'is_taxonomy' => '1'
    ));
    update_post_meta( $variation_child_id,'_product_attributes',$thedata);

}
endif;
/* Function save product images */
if (!function_exists('saveProductImage')) :
	function saveProductImage($src_image,$i,$n,$product_id,$campaign_data) {
		$upload_dir = wp_upload_dir();
			
		$_w = imagesx($src_image);
		$_h = imagesy($src_image);
		
		$dst_y =$i*($_h/$n)- $_h/$n;
		
		$dst_height = $_h/$n;
		$image_temp = imagecreatetruecolor($_w, $dst_height);
		
		imagealphablending( $image_temp, false );
		imagesavealpha( $image_temp, true );
		imagecopyresampled($image_temp, $src_image, 0, 0, 0, $dst_y, $_w, $dst_height, $_w, $dst_height);
		
		$upload_path = str_replace('/', DIRECTORY_SEPARATOR, $upload_dir['path']) . DIRECTORY_SEPARATOR;

        $filename = preg_replace('/[^A-Za-z0-9 _ .-]/', '',$i).'.png';
        $hashed_filename = md5($filename . microtime()) . '_' . $filename;
		imagepng($image_temp,$upload_path . $hashed_filename);
		
		
		$file = array();
        $file['error'] = ''; 
        $file['tmp_name'] = $upload_path . $hashed_filename;
        $file['name'] = $hashed_filename;
        $file['type'] = 'image/jpg';
        $file['size'] = filesize($upload_path . $hashed_filename);      
        $filename_ = wp_handle_sideload($file, array('test_form' => false));
       
        $file_name = $filename_['file'];

        $filetype = wp_check_filetype(basename($file_name), null);

        $wp_upload_dir = wp_upload_dir();

        $attachment = array(
            'guid' => $wp_upload_dir['url'] . '/' . basename($file_name),
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $campaign_data['name'] ),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $file_name, $product_id);
		
		$attach_data = wp_generate_attachment_metadata($attach_id, $file_name);
        wp_update_attachment_metadata($attach_id, $attach_data);
		return  $attach_id;
	}
endif;
/* Function save product images */
if (!function_exists('saveProductImages')) :

    function saveProductImages($view_size,$product_id,$campaign_data) {
       
		
		$attach_ids= array();
		$all_image_front = array();
        $all_image_back = array();
        $all_image_front = $campaign_data['all_image_front'];
        $all_image_back = $campaign_data['all_image_back']; 
        
        if($campaign_data['feature_side']=='back'){
            $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $campaign_data['image_back']));      
            $src_image = imagecreatefromstring($decoded);        
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);
            
            $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $campaign_data['image_front']));      
            $src_image = imagecreatefromstring($decoded);
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);
            
            for ($i=0; $i < count($all_image_front) ; $i++) {
            if($all_image_back[$i] != "" && $all_image_back[$i] != $campaign_data['image_back']) {
        	$decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$all_image_back[$i]));      
            $src_image = imagecreatefromstring($decoded);        
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);
            }            	
        	if($all_image_front[$i] != "" && $all_image_front[$i] != $campaign_data['image_front']) {
        	$decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$all_image_front[$i]));      
            $src_image = imagecreatefromstring($decoded);        
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);
            }
          }

        }else{
            $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $campaign_data['image_front']));      
            $src_image = imagecreatefromstring($decoded);
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);
            
            $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $campaign_data['image_back']));      
            $src_image = imagecreatefromstring($decoded);        
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);

            for ($i=0; $i < count($all_image_front) ; $i++) {
        	if($all_image_front[$i] != "" && $all_image_front[$i] != $campaign_data['image_front']) {
        	$decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$all_image_front[$i]));      
            $src_image = imagecreatefromstring($decoded);        
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);
            }
            if($all_image_back[$i] != "" && $all_image_back[$i] != $campaign_data['image_back']) {
        	$decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$all_image_back[$i]));      
            $src_image = imagecreatefromstring($decoded);        
            $attach_ids[] = saveProductImage($src_image,1,1,$product_id,$campaign_data);
            }
        }
        }
        
        /*
		if($campaign_data['feature_side']=='back'){
			for($i=$view_size;$i>=1;$i--){
				
					$attach_ids[] = saveProductImage($src_image,$i,$view_size,$product_id,$campaign_data);
				
				
			}
		}else{
			for($i=1;$i<=$view_size;$i++){
				
					$attach_ids[] = saveProductImage($src_image,$i,$view_size,$product_id,$campaign_data);
				
				
			}
		}
        */
		return $attach_ids;
	}
endif;
/* Function add quiz point */
if (!function_exists('get_color_name')) :
	function get_color_name($colors){
		if( fpd_not_empty(fpd_get_option( 'fpd_hex_names' )) ) {
				$hex_names = '{}';
				if( fpd_not_empty(fpd_get_option( 'fpd_hex_names' )) ) {
					$hex_names = '{"'.str_replace('#', '', fpd_get_option( 'fpd_hex_names' ) ) ;
					$hex_names = str_replace(':', '":"', $hex_names);
					$hex_names = str_replace(',', '","', $hex_names);
					$hex_names .= '"}';
				}
			$hex_colors = json_decode(stripslashes($hex_names),true);
		}
		$color_names = array();
		foreach($colors as $color){
			$color_names[]=$hex_colors[$color];
		}
		return $color_names;
	}

endif;
/* Function add quiz point */
if (!function_exists('postCampaign')) :

    function postCampaign() {
        global $wpdb;
		
	    $current_user = wp_get_current_user();
		
		

		
        if (!$current_user->ID):
			$response = array(
            'status'=> 'error',
			'message' =>'Please login!');
            wp_send_json($response);
		else:
		
        $post_date = date('Y-m-d H:i:s');
       

        $campaign_data = json_decode(stripslashes($_POST['data']),true);
		

		$attach_ids_ = array();
		$design_ids_ = array();
		$all_image_back = array();
		$all_image_front = array();
		$color_check = array();
		$tmp_array = array();
		$array_push_front = array();
		$array_push_back = array();
		foreach($campaign_data['design'] as $design_product){
			$design_product['post_name'] =  $design_product['design_name'];
			$design_product['name'] =  $campaign_data['name'];
			$design_product['sales_goal'] = $campaign_data['sales_goal'];
			$design_product['content'] = $campaign_data['content'];
			$design_product['campaign_length'] = $campaign_data['campaign_length'];
			$design_product['image_back'] = urldecode($design_product['image_back']);
            $design_product['image_front'] = urldecode($design_product['image_front']);
			$design_product['colors'] = $design_product['colors'];
			$design_product['sizes'] = $design_product['sizes'];
			$design_product['feature_side'] =$campaign_data['feature_side'];
            $design_product['design_id'] =$campaign_data['design_id'];
            
            $colorcheck = $design_product['color_check'];
            $color_check = explode(',',$colorcheck);
            for ($i=0; $i < count($color_check); $i++) { 
            	if($color_check[$i] == "")
            	{unset($color_check[$i]);}
            }
            $all_image_back = explode(',',$design_product['all_image_back']);
            $all_image_back =  array_unique($all_image_back);
            for ($i=0; $i < count($all_image_back); $i++) { 
            	unset($tmp_array);
            	$tmp_array = explode("_break_", $all_image_back[$i]);
            	for ($j=0; $j < count($color_check); $j++) { 
            		if($tmp_array[0] == $color_check[$j] && $tmp_array[1] != $design_product['image_back'])
            		{
            			array_push($array_push_back,$tmp_array[1]);
            		}
            	}
            	
            }

            for ($i=0; $i < count($array_push_back) ; $i++) { 
            	$array_push_back[$i] = urldecode($array_push_back[$i]);
            }
            $all_image_front = explode(',',$design_product['all_image_front']);
            $all_image_front =  array_unique ($all_image_front);

            for ($i=0; $i < count($all_image_front); $i++) { 
            	unset($tmp_array);
            	$tmp_array = explode("_break_", $all_image_front[$i]);
            	for ($j=0; $j < count($color_check); $j++) { 
            		if($tmp_array[0] == $color_check[$j] && $tmp_array[1] != $design_product['image_front'])
            		{
            			array_push($array_push_front,$tmp_array[1]);
            		}
            	}

            }

        	for ($i=0; $i < count($array_push_front) ; $i++) { 
        		$array_push_front[$i] = urldecode($array_push_front[$i]);
        	}
        	$design_product['all_image_back'] = $array_push_back;
            $design_product['all_image_front'] =$array_push_front;

			$product_id = addwooProduct($design_product);		
			$attach_ids = saveProductImages($_POST['view_size'],$product_id,$design_product);
			$attach_ids_ = array_merge($attach_ids_,$attach_ids);
			
			add_post_meta($product_id, '_thumbnail_id', $attach_ids[0]);
			
			add_post_meta($product_id, '_design_name', $design_product['design_name']);
			add_post_meta($product_id, '_design_data', serialize(json_decode(stripslashes($_POST['products']), true)));
			update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attach_ids ) );
			$design_ids_[]=$product_id;
		}
		foreach($design_ids_ as $design_id){
			add_post_meta($design_id, '_design_ids', $design_ids_);
		}
		
		

        $response = array(
            'post_date' => $post_date,
            'status'=> 'success',
            'product_title' => get_the_title($design_ids_[0]),
            'product_link' => get_permalink($design_ids_[0]),
            'product_image' =>'',
        );
        wp_send_json($response);
		endif;
    }

    add_action('wp_ajax_postCampaign', 'postCampaign');
    add_action('wp_ajax_nopriv_postCampaign', 'postCampaign');
endif;



add_action('init', 'wpse_74054_add_author_woocommerce', 999 );

function wpse_74054_add_author_woocommerce() {
    add_post_type_support( 'product', 'author' );
}



function woo_add_custom_general_fields() {

  global $woocommerce, $post;
  
   echo '<div class="options_group">';

    woocommerce_wp_text_input( 
		array( 
			'id'          => '_available_sizes', 
			'label'       => __( 'Available Sizes', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Space by |. eg: S|X|XL ', 'woocommerce' ) ,
			'type'              => 'text'
		)
	);
  
  echo '</div>';
  echo '<div class="options_group">';

    woocommerce_wp_text_input( 
		array( 
			'id'          => '_des_intro', 
			'label'       => __( 'Short Intro', 'woocommerce' ), 
			'placeholder' => '',
			'type'              => 'text'
		)
	);
  
  echo '</div>';
  
   echo '<div class="options_group">';
  // Select
	woocommerce_wp_select( 
	array( 
		'id'      => '_campaign_status', 
		'label'   => __( 'Campaign status', 'woocommerce' ), 
		'options' => array(0=>'Disable',1=>'Enable')
		)
	);
	echo '</div>';
	
	
  echo '<div class="options_group">';
  // Select
	woocommerce_wp_select( 
	array( 
		'id'      => '_is_campaign', 
		'label'   => __( 'Campaign product', 'woocommerce' ), 
		'options' => array(0=>'False',1=>'True')
		)
	);
	echo '</div>';
  
  echo '<div class="options_group">';
  //		$attributes = array('sales_goal'=>$data['sales_goal'],'campaign_length'=>$data['campaign_length']);

  // Custom fields will be created here...
  woocommerce_wp_text_input( 
		array( 
			'id'          => '_sales_goal', 
			'label'       => __( 'Sales goal', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter the Sales goal here.', 'woocommerce' ) ,
			'type'              => 'number', 
			'custom_attributes' => array(
				'step' 	=> 'any',
				'min'	=> '3'
			) 
		)
	);
  
  echo '</div>';
  echo '<div class="options_group">';
  //		$attributes = array('sales_goal'=>$data['sales_goal'],'campaign_length'=>$data['campaign_length']);

  // Custom fields will be created here...
  woocommerce_wp_text_input( 
		array( 
			'id'          => '_profit', 
			'label'       => __( 'Profit', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter the Profit here.', 'woocommerce' ) ,
			'type'              => 'number', 
			'custom_attributes' => array(
				'step' 	=> 'any',
				'min'	=> '0'
			) 
		)
	);
  
  echo '</div>';
  echo '<div class="options_group">';
  // Select
	$list_length = array(3,5,7,10,14,21,28);
	$options = array();
	foreach($list_length as $n_date){
									$date = new DateTime($post->post_date);
									$date->add(new DateInterval('P'.$n_date.'D'));
									$name = $n_date.' Days';
								$options[$n_date]=$name;	
	}
	woocommerce_wp_select( 
	array( 
		'id'      => '_campaign_length', 
		'label'   => __( 'Campaign length', 'woocommerce' ), 
		'options' => $options
		)
	);
	echo '</div>';
}
// Text Field
function woo_add_custom_general_fields_save( $post_id ){
	
	$woocommerce_text_field1 = $_POST['_sales_goal'];
	if( !empty( $woocommerce_text_field1 ) )
		update_post_meta( $post_id, '_sales_goal', esc_attr( $woocommerce_text_field1 ) );
	
	$woocommerce_text_field2 = $_POST['_is_campaign'];
	update_post_meta( $post_id, '_is_campaign', esc_attr( $woocommerce_text_field2 ) );
	
	$woocommerce_text_field3= $_POST['_campaign_length'];
	if( !empty( $woocommerce_text_field3 ) )
		update_post_meta( $post_id, '_campaign_length', esc_attr( $woocommerce_text_field3 ) );
	
	$woocommerce_text_field4= $_POST['_profit'];
	if( !empty( $woocommerce_text_field4 ) )
		update_post_meta( $post_id, '_profit', esc_attr( $woocommerce_text_field4 ) );
	
	$woocommerce_text_field5= $_POST['_campaign_status'];
	update_post_meta( $post_id, '_campaign_status', esc_attr( $woocommerce_text_field5 ) );
		
		
	$woocommerce_text_field5 = $_POST['_available_sizes'];
	update_post_meta( $post_id, '_available_sizes', esc_attr( $woocommerce_text_field5 ) );
	
    $woocommerce_text_field6 = $_POST['_des_intro'];
	update_post_meta( $post_id, '_des_intro', esc_attr( $woocommerce_text_field6 ) );
	
    
}
	
// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );

function postCompleteCountdown(){
	$product_id= urldecode($_POST['product_id']);
	update_post_meta($product_id, '_campaign_status', 0);
}

add_action('wp_ajax_postCompleteCountdown', 'postCompleteCountdown');
add_action('wp_ajax_nopriv_postCompleteCountdown', 'postCompleteCountdown');

function add_countdown(){
	global $post, $product;
	
	
	$length = get_post_meta($product->id,'_campaign_length',true);
	
	if($length>0):
	$date=new DateTime();
	$date->setTimestamp(get_post_time('U', true));
	$current_date = new DateTime();
	$date->add(new DateInterval('P'.$length.'D'));
	$seconds = $date->getTimestamp() - current_time( 'timestamp',true );
    $seconds = $seconds>0?$seconds:0;
	
	$_campaign_status = get_post_meta($product->id,'_campaign_status',true);
	$_is_campaign = get_post_meta($product->id,'_is_campaign',true);
	if($_is_campaign==1&&$_campaign_status!=1){
		$seconds=0;
	}
	
	?>
	
	<div class="countdown-area">
		<h4 class="campaign-end">CAMPAIGN ENDS</h4>
		<div id="campaign_countdown">
		</div>
	</div>

	<div class="product-detail-social-icons">
		<h4 class="campaign-end">SHARE THIS CAMPAIGN</h4>
		<ul class="product-detail-social">
		<li><a class="fa-facebook-official" href="https://www.facebook.com/TeeM8-417954508400981/"><span class="icon-span">Facebook</span></a></li>
		<li><a class="fa fa-instagram" href="https://www.instagram.com/teem8aus/" aria-hidden="true"><span class="icon-span">Instagram</span></a></li>
		<li><a class="fa-twitter" href="https://twitter.com/TeeM8aus"><span class="icon-span">Twitter</span></a></li>
		</ul>
	</div>
	<div class="clear"></div>
	<!-- <div class="shipping_note">Orders will ship 5-10 business days after the end of the campaign.</div> -->
	<script type="application/javascript">
	function countdownComplete()
	{		
		jQuery('.single_variation_wrap').html('');
		jQuery( '<p class="stock out-of-stock">This campaign has ended</p>').insertAfter( ".variations_form" );
		jQuery.ajax({
                                type: "POST",
                                url: ajax_login_object.ajaxurl,
                                data: {
                                    'action': "postCompleteCountdown",
                                    'product_id': <?php echo $post->ID; ?>
                                },
                                success: function (response) {
                                    
                                },
                                error: function (data) {
                                    
                                }
            });
	}
	var myCountdown1 = new Countdown({
										time: <?php echo $seconds; ?>, 
										width:300, 
										height:60,  
										rangeHi:"day",
										target	 	: "campaign_countdown",
										hideLine: true,
										<?php if( $seconds>0):?>
										onComplete	: countdownComplete,
										<?php endif; ?>
										padding : 0.8, numbers		: 	{
														font 	: '"Lato Bold", "Helvetica Neue", Arial, sans-serif',
														color	: "#0075cc",
														bkgd	: "#92c7e7",
														fontSize : 200,
														rounded	: 0.15,				
														shadow	: {
																	x : 0,			
																	y : 0,			
																	s : 0,			
																	c : "#fff",	
																	a : 0			
																	}
														},
										labels : {
													textScale : 0.8,
													offset : 5
												} 
									});
	</script>
	<?php
	endif;
}
function get_data_campaign(){
	global $post, $product;
	$_design_ids = get_post_meta($product->id,'_design_ids',true);
	$_sales_goal = get_post_meta($product->id,'_sales_goal',true);
	$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
		'numberposts' => -1,
		'post_type'   => wc_get_order_types( 'view-orders' ),
		'post_status' => array_keys( wc_get_order_statuses() )
	) ) );
	$product_orders = array();
	foreach ( $customer_orders as $customer_order ) {
		$order = wc_get_order( $customer_order );
		foreach( $order->get_items() as $item_id => $item ) {
			$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item);
			if(!in_array($_product->id,$_design_ids)) continue;
			
			$_is_campaign =  get_post_meta($_product->id,'_is_campaign',true);
			$_post= get_post($_product->id);
			
			if($_is_campaign){
				$_profit =  get_post_meta($_product->id,'_profit',true);
				
				if(!empty($product_order)){
					$_product_order = $product_order;
					$data = array('qty'=>$_product_order['qty']+$item['qty'],'subtotal'=>$_product_order['subtotal']+$item['line_subtotal'],
					'profit'=>$_profit,"sales_goal"=>$_product_order['sales_goal'],'is_purchase'=>$_product_order['is_purchase']);
					$product_order=$data;
				}else
				{
					$data = array('qty'=>$item['qty'],'subtotal'=>$item['line_subtotal'],'profit'=>$_profit,"sales_goal"=>$_sales_goal,'is_purchase'=>false);
					$product_order=$data;
				}
				
			} 
		} 
	}
	if(empty($product_order)){
			$product_order = array('qty'=>0,
			'subtotal'=>0,
			'profit'=>0,
			"sales_goal"=>$_sales_goal,
			'is_purchase'=>false);
	}
	if($product_order['qty']>=$product_order['sales_goal']){
			$product_order['is_purchase'] = true;
		}
	
		
	return $product_order;
}
//
function add_presistent_timer()
{
	global $post, $product;
    global $ti_option;
	?>
	</div>
	<br>
	<style>
	.presistent_timer_stats{
	font-weight: bold;
    font-size: 15px;
	}
	.presistent_timer_stats{
	font-weight: bold;
    font-size: 15px;
	}
	.more-info-right{
		float: right;
	}
	</style>
	<?php
	$product_order =get_data_campaign();
	?>
	<div class="presistent_timer" style='display:none'>

		<div class="presistent_timer_stats"><span>
		Only <?php echo  $product_order['sales_goal'] - $product_order['qty']; ?> more sales needed to reach our print goal</span></div>
		<p class="campaign_transaction_subtext">
		<span>Buy with confidence. You won’t be charged unless we go to print.</span>
		<a class="more-info-right" href="#more-info-content">More info</a>
		</p>
        <div style='display:none'>
			<div id='more-info-content' style='padding:10px; background:#fff;'>   
                <div style="text-align: center;">
                <img src='<?php echo $ti_option['site_logo']['url']; ?>' />
                </div>
                <div style="border: 1px solid #000;
    padding: 20px 40px;">
                <p style="font-size: 18px;">
                The success of TeeM8 is we provide an easy to use platform where there is no risk to designers in bringing their creative designs to market and no risk to buyers in purchasing those great creations. 
                </p>
                <p style="font-size: 16px; padding: 0px 10px;">
                You can buy this garment with confidence. If we don’t produce the garments, you won’t be charged.
                </p>
                <p style="font-size: 13px; padding: 0px 10px;">
    Our production team require a minimum number of garments to print or embroider to cover production costs. If not enough of this design sells to cover those costs, the design will be cancelled and you will be fully refunded.
                </p>
                </div>
			</div>
		</div>
	</div>
   <script type="application/javascript">
   jQuery(document).ready(function(){
       jQuery(".more-info-right").colorbox({inline:true, width:"90%",close:false});
   });
   </script>
	<div>
	<?php
	
}

add_action('woocommerce_product_meta_end', 'add_presistent_timer');


add_action('woocommerce_product_meta_end', 'add_countdown');

add_action('woocommerce_before_single_product', 'calculator_champaign_status');
function calculator_champaign_status()
{
	global $post, $product;
	$_campaign_status = get_post_meta($product->id,'_campaign_status',true);
	$_is_campaign = get_post_meta($product->id,'_is_campaign',true);
	if($_is_campaign==1&&$_campaign_status!=0){
		$length = get_post_meta($product->id,'_campaign_length',true);
		if($length>0){
		$date=new DateTime();
		$date->setTimestamp(get_post_time('U', true));
		$current_date = new DateTime();
		$date->add(new DateInterval('P'.$length.'D'));
		$seconds = $date->getTimestamp() - current_time( 'timestamp',true );
			if($seconds<=0){
				update_post_meta($post->ID, '_campaign_status', 0);
			}
		}
	}
}


function add_product_footer(){
	global $post, $product;
		
	 //$is_fancy_product = get_post_meta( $product->id, '_fancy_product' ,true)=='yes';
	 
	 $_is_campaign = get_post_meta($product->id,'_is_campaign',true);
	
	 if($_is_campaign):
	 $length = get_post_meta($product->id,'_campaign_length',true);
		if($length>0){
		$date=new DateTime();
		$date->setTimestamp(get_post_time('U', true));
		$date->add(new DateInterval('P'.$length.'D'));
			$time_end_campaign =  $date->format('F j');
		}
	?>
	
	<div class="product-detail col-md-7">
		<h1 itemprop="name" class="product_title entry-title product_title_pc"><?php  the_title(); ?></h1>
		<?php  the_content(); ?>
		<p><strong>Shipping Info</strong><br>
		Printed &amp; shipped from Marrickville, Sydney Australia by Startrack or Australia Post Registered Mail, within 5-10 business days after the campaign ends, <?php echo $time_end_campaign ; ?><br>
        </p>
        <p>
        <strong>Shipping Rate</strong><br>
		Flat Fee – $11 first item + $4 per additional item, Australia Wide<br>
        </p><p>
		<strong>Quality Guarantee</strong><br>
		If you are not 100% satisfied with the quality of your item, return it, and we will refund your purchase price. <a href="/teem8s-quality-guarantee/" target="_blank">View Details</a></p><br><br><br>
		<?php

	// would echo post 7's content up until the <!--more--> tag
	//$post_603 = get_post(603); 
//	$excerpt = $post_603->post_content;
	//echo apply_filters('the_content', $post_603->post_content);



	// would get post 12's entire content after which you
	// can manipulate it with your own trimming preferences
	//$post_12 = get_post(12); 
	//$trim_me = $post_12->post_content;
	//my_trim_function( $trim_me );

	?>
	<div class="col-md-5">
	
	
	</div>
	</div>
	<?php
	endif;
}

add_action('woocommerce_after_single_product', 'add_product_footer');


function ajax_check_user_logged_in() {
    echo is_user_logged_in()?'yes':'no';
    die();
}
add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');



function ajax_login_init(){

    wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/custom.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');

    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __('Sending user info, please wait...')
    ));

    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
	add_action( 'wp_ajax_nopriv_ajaxRegister', 'ajax_register' );
}

// Execute the action only if the user isn't logged in

add_action('init', 'ajax_login_init');

function ajax_register(){
	
	check_ajax_referer( 'ajax-register-nonce', 'security' );
	$response = array();
	$userdata = array();
	$userdata['user_email'] = $_POST['user_email'];
	$userdata['user_login'] = $_POST['user_login'];
	$userdata['user_pass'] = $_POST['user_pass'];
	
	if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $userdata['user_login'])) {
		$response['status'] = 'error';
		$response['message'] =  "Username should be from 6 to 32 characters long, starts with letters and must not contain any special character." ;
	}
	elseif( email_exists( $userdata['user_email'] )) {
		$response['status'] = 'error';
		$response['message'] =  "Looks like you have already registered with this email address." ;
		
	}
	elseif(username_exists( $userdata['user_login'] )){
		   $response['status'] = 'error';
		$response['message'] =  "This username has been taken, please try another one." ;
	}
	elseif ( $_POST['user_pass'] !== $_POST['user_pass_confirm'] ) {
		$response['status'] = 'error';
		$response['message'] =  "Passwords must match." ;
	}
	elseif ( strlen( $_POST['user_pass'] ) < 6 ) {
		$response['status'] = 'error';
		$response['message'] =  "Passwords must be at least eight characters long" ;
	}else{
		$user_id = wp_insert_user($userdata);
		 if($user_id){
			$info = array();
			$info['user_login'] = $userdata['user_login'];
			$info['user_password'] = $userdata['user_pass'];
			$info['remember'] = true;
			$user_signon = wp_signon( $info, false );
			 if ( is_wp_error($user_signon) ){
				$response['status'] = 'error';
			    $response['message'] =  'Wrong username or password.';
			} else {
				
				$response['status'] = 'success';
			    $response['message'] =  'Created and Login successful, redirecting...';
			}
		 }else{
			$response['status'] = 'error';
			$response['message'] =  "Can't create your account!" ;
		 }
	}
	echo json_encode($response);
	die();
}
function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
    }

    die();
}


/* Function getProductDetail */
if (!function_exists('getProductDetail')) :

    function getProductDetail() {
        global $wpdb;
        $product_id  = $_POST['product_id'];
        
        /* $query_args = array('posts_per_page' => -1, 'orderby' => 'ID',
        'order'   => 'ASC', 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'p' =>$product_id );
        $r = new WP_Query($query_args);
       */
        
         $_pf = new WC_Product_Factory();  
         $_product = $_pf->get_product($product_id);
         //$link = get_permalink($_product->post);
		 $link = "/design-now/?product_id=2175";
			
         $title = get_the_title($_product->post);
         $content = $_product->post->post_content;
         $content = apply_filters('the_content', $content);
         $content = str_replace(']]>', ']]&gt;', $content);
         $_des_intro = get_post_meta($product_id,'_des_intro',true);
         $hml= ' <div class="intro_category">
                                <div class="row ">                 
                                    <div class="col-md-9 intro_category_left">
                                    <h3 class="des_catetory_title">Styles and colours available in '.$title.'</h3>
                                    <div class="des_catetory_intro">View pricing in design window</div>
                                    </div>
                                    <div class="col-md-3 intro_category_right">
                                        <a class="link_category_design" href="'.$link.'">Start Designing Now!</a>
                                    </div>
                                </div>
                                </div>'.$content.'
                                <div style="clear:both;"></div><div class="bottom_link_design_now"><a class="link_category_design_2" href="'.$link.'">Start Designing Now</a></div>
                                ';
        $response = array(
            'status'=> 'success',
            'html' =>$hml,
            'message' =>$message
        );
        wp_send_json($response);
    }

    add_action('wp_ajax_getProductDetail', 'getProductDetail');
    add_action('wp_ajax_nopriv_getProductDetail', 'getProductDetail');
endif;


/* Function getBoxDesign */
if (!function_exists('getBoxDesign')) :

    function getBoxDesign() {
        global $wpdb;
        $cat_id  = $_POST['cate_id'];
		 $query_args = array('posts_per_page' => -1, 'orderby' => 'ID',
        'order'   => 'ASC', 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'product_cat',
                                                    'field' => 'id',
                                                    'terms' => array($cat_id)
                                        )));
                                        $r = new WP_Query($query_args);
    $hml = '';
    $link_first = '';
                                        if ($r->have_posts()) {
                                        while ($r->have_posts()) : $r->the_post();
                                        global $product; 
                                        if($link_first=='') $link_first= get_permalink();
    $hml.= ' <li style="text-align: center;
    font-weight: bold;" class="product type-product status-publish has-post-thumbnail product_cat-design shipping-taxable purchasable product-type-simple product-cat-design instock">
                                                <a href="'.get_permalink().'" title="'. esc_attr(get_the_title() ?  get_the_title() :  get_the_ID()) .'">';
                                              
                                                        if (has_post_thumbnail())
                                                          $hml.=  get_the_post_thumbnail(get_the_ID(), array(130, 105)).'</a>';
                                                        else
                                                            $hml.=  '<img src="' . woocommerce_placeholder_img_src() . '" alt="Placeholder" width="' . $woocommerce->get_image_size('shop_thumbnail_image_width') . '" height="' . $woocommerce->get_image_size('shop_thumbnail_image_height') . '" /></a>' ;
                                                            $colors = get_post_meta(get_the_ID(),'_available_colors',true);
                                                            $colors_array =  explode('|',$colors);
                                                            $color_html = '';
                                                            foreach($colors_array as $color){
                                                                if(trim($color)=='') continue;
                                                            $color_html.= '<li class="shirt-color-sample js-color" style="background-color:'.$color.';"></li>';
                                                            }
                                                       $hml.=   '<a href="'.get_permalink().'" class="product_title_green">'.get_the_title().'</a>';
                                                       $hml.=   '<ul class="colors-available">'.$color_html.'</ul>    ';
                                                        if (get_the_title()) 
                                                            $hml.=  '<div class="choose_design_des">'.get_the_content().'</div>';
                                                        else $hml.= (string) get_the_ID(); 
                                                      
                                                  $hml.=' </li>';                                     
                                         endwhile; 
                                        }
                                        
        $message='';
        if($hml==''){
            $message = 'No items in category.';
        }else{
           $category  = get_term( $cat_id, 'product_cat' );

            $hml= ' <div class="intro_category">
                                <div class="row ">                 
                                    <div class="col-md-9 intro_category_left">
                                    <h3 class="des_catetory_title">'.$category->name.'</h3>
                                    <div class="des_catetory_intro"> '.strip_tags(term_description($cat_id,'product_cat')).'</div>
                                    </div>
                                    <div class="col-md-3 intro_category_right">
                                        <a class="link_category_design" href="'.$link_first.'">Start Designing Now</a>
                                    </div>
                                </div>
                                </div><ul id="products_ajax" class="products">'.$hml.'</ul>
                                <div class="bottom_link_design_now"><a class="link_category_design_2" href="'.$link_first.'">Start Designing Now</a></div>
                                ';
        }
        $response = array(
            'status'=> 'success',
            'html' =>$hml,
            'message' =>$message
        );
        wp_send_json($response);
    }

    add_action('wp_ajax_getBoxDesign', 'getBoxDesign');
    add_action('wp_ajax_nopriv_getBoxDesign', 'getBoxDesign');
endif;


// Filter wp_nav_menu() to add additional links and other output
function new_nav_menu_items($items) {
    
        $homelink = ' <li id="menu-item-13" class="menu-item home-menu menu-item-type-custom menu-item-object-custom "><a href="/"><img src="'.get_template_directory_uri().'/images/home_menu.png" /></a></li>';
    
   
    $items = $homelink . $items;
    return $items;
}

add_filter('wp_nav_menu_items', 'new_nav_menu_items');

// Filter wp_nav_menu() to add additional login and out links and other output
add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );

function add_loginout_link( $items, $args ) {

    if (is_user_logged_in() && $args->theme_location == 'secondary_menu') {
		$items .= '<li><a href="'. site_url('my-account') .'">My Account</a></li>';
        $items .= '<li><a href="'. site_url('my-account/customer-logout/') .'">Log Out</a></li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'secondary_menu') {
        $items .= '<li><a href="'. site_url('/my-account') .'">Log In</a></li>';
    }
    return $items;
}

