<?php
function return_global_smooth_slider($slider_handle,$r_array,$smooth_slider,$set,$data=array()){
	$slider_html='';
	$slider_html=get_global_smooth_slider($slider_handle,$r_array,$smooth_slider,$set,$echo='0',$data);
	return $slider_html;
}
//Basic Shortcode
function return_smooth_slider($slider_id='',$offset='0', $data=array()) {
	global $smooth_slider; 
	$slider_html='';
	if($smooth_slider['multiple_sliders'] == '1' and is_singular()){
		global $post;
		$post_id = $post->ID;
		if(ss_slider_on_this_post($post_id))
			$slider_id = get_slider_for_the_post($post_id);
	}
	if((!is_singular() or $smooth_slider['multiple_sliders'] != '1') and (empty($slider_id) or !isset($slider_id))){
		$slider_id = '1';
	}
	if(!empty($slider_id)){ 
		$set='';
		$data['slider_id']=$slider_id;
		$slider_handle='smooth_slider_'.$slider_id;
		$data['slider_handle']=$slider_handle;
		$r_array = carousel_posts_on_slider($smooth_slider['no_posts'], $offset, $slider_id, $echo = '0', $set,$data); 
		$slider_handle='smooth_slider_'.$slider_id;
		$slider_html=return_global_smooth_slider($slider_handle,$r_array,$slider_id,$set,$data);
	} //end of not empty slider_id condition
	return $slider_html;
}

function smooth_slider_simple_shortcode($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'offset'=> '',
	), $atts));

	return return_smooth_slider($id);
}
add_shortcode('smoothslider', 'smooth_slider_simple_shortcode');

//Category shortcode
function return_smooth_slider_category($catg_slug='',$offset=0, $data=array()) {
	global $smooth_slider; 
	$slider_html='';
	$set='';
	$slider_handle='smooth_slider_'.$catg_slug;
	$data['slider_handle']=$slider_handle;
	$r_array = carousel_posts_on_slider_category($smooth_slider['no_posts'], $catg_slug, $offset, '0',$set,$data); 
	$slider_html=return_global_smooth_slider($slider_handle,$r_array,$slider_id='',$set,$data);
	return $slider_html;
}

function smooth_slider_category_shortcode($atts) {
	extract(shortcode_atts(array(
		'catg_slug' => '',
		'offset' => '',
	), $atts));

	return return_smooth_slider_category($catg_slug,$offset);
}
add_shortcode('smoothcategory', 'smooth_slider_category_shortcode');

//Recent Posts Shortcode
function return_smooth_slider_recent($offset=0, $data=array()) {
	global $smooth_slider; 
	$slider_html='';
	$set='';
	$slider_handle='smooth_slider_recent';
	$data['slider_handle']=$slider_handle;
	$r_array = carousel_posts_on_slider_recent($smooth_slider['no_posts'], $offset, '0',$set,$data);
	$slider_html=return_global_smooth_slider($slider_handle,$r_array,$slider_id='',$set,$data);
	return $slider_html;
}

function smooth_slider_recent_shortcode($atts) {
	extract(shortcode_atts(array(
		'offset' => '0',
	), $atts));
	return return_smooth_slider_recent($offset);
}
add_shortcode('smoothrecent', 'smooth_slider_recent_shortcode');
?>
