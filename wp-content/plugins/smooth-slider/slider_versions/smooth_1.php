<?php 
function smooth_global_data_processor( $slides, $smooth_slider, $out_echo, $set='',$data=array()){
	//If no Skin specified, consider Default
	$skin='default';
	if(isset($smooth_slider['stylesheet'])) $skin=$smooth_slider['stylesheet'];
	if(empty($skin))$skin='default';
	
	//Always include Default Skin
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/default/functions.php');
	//Include Skin function file
	if($skin!='default' and file_exists(dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php'))require_once ( dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php');
	
	//Skin specific data processor and html generation
	$data_processor_fn='smooth_data_processor_'.$skin;
	if(!function_exists($data_processor_fn))$data_processor_fn='smooth_data_processor_default';
	$r_array=$data_processor_fn($slides, $smooth_slider,$out_echo);
	return $r_array;	
}
function smooth_global_posts_processor( $posts, $smooth_slider, $out_echo, $set='',$data=array() ){   
	//If no Skin specified, consider Default
	 /* Added function call to skin for 2.6 */
	$skin='default';
	global $smooth_slider;
	if(isset($smooth_slider['stylesheet'])) $skin=$smooth_slider['stylesheet'];
	if(empty($skin))$skin='default';
	
	//Always include Default Skin
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/default/functions.php');
	//Include Skin function file
	if($skin!='default' and file_exists(dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php'))require_once ( dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php');
	//Skin specific post processor and html generation
	$post_processor_fn='smooth_post_processor_'.$skin;
	if(!function_exists($post_processor_fn))$post_processor_fn='smooth_post_processor_default';
	$r_array=$post_processor_fn($posts, $smooth_slider,$out_echo);
	return $r_array;	
}
function get_global_smooth_slider($slider_handle,$r_array,$smooth_slider, $set='', $echo='1', $data=array()){
	//If no Skin specified, consider Default
	 /* Added function for call to skin 2.6*/ 
	$skin='default';
	global $smooth_slider,$post;
	$smooth_slider_style='';
	$slider_id=isset($data['slider_id'])?$data['slider_id']:'';
	if(is_singular()) {	
		$smooth_slider_style = get_post_meta($post->ID,'_smooth_slider_style',true);
		//for compatibility with lower versions of Smooth Slider
		if( empty($smooth_slider_style) ) $smooth_slider_style=get_post_meta($post->ID,'slider_style',true);
	}
	if(empty($smooth_slider_style)){
		if(isset($smooth_slider['stylesheet'])) $skin=$smooth_slider['stylesheet'];
	}
	else{
		$skin=$smooth_slider_style;
	}
	if(empty($skin))$skin='default';
 	//Include CSS
	wp_enqueue_style( 'smooth_'.$skin, smooth_slider_plugin_url( 'css/skins/'.$skin.'/style.css' ),false,SMOOTH_SLIDER_VER, 'all');
	//Always include Default Skin
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/default/functions.php');
	//Include Skin function file
	if($skin!='default' and file_exists(dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php'))
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php');
	//Skin specific post processor and html generation
	$get_processor_fn='smooth_slider_get_'.$skin;
	if(!function_exists($get_processor_fn))$get_processor_fn='smooth_slider_get_default';
	
	$r_array=$get_processor_fn($slider_handle,$r_array,$slider_id,$echo);
	return $r_array;	
}
//Basic Smooth Slider
function carousel_posts_on_slider($max_posts, $offset=0, $slider_id = '1',$out_echo = '1', $set='', $data=array() ) {
    global $smooth_slider,$default_slider,$wpdb;
	foreach($default_slider as $key=>$value){
		if(!isset($smooth_slider[$key])) $smooth_slider[$key]='';
	}
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;
	$post_table = $table_prefix."posts";
	$rand = $smooth_slider['rand'];
	if(isset($rand) and $rand=='1'){
	  $orderby = 'RAND()';
	}
	else {
	  $orderby = 'a.slide_order ASC, a.date DESC';
	}
	//WPML
	if( function_exists('icl_plugin_action_links') ) {
		$tr_table = $table_prefix."icl_translations";
		$posts = $wpdb->get_results("SELECT b.* FROM 
                     $table_name a 
		 LEFT OUTER JOIN $post_table b 
			ON a.post_id = b.ID 
		 LEFT OUTER JOIN $tr_table t 
			ON a.post_id = t.element_id 
		 WHERE ((b.post_status = 'publish' AND t.language_code = '".ICL_LANGUAGE_CODE."') OR (b.post_type='attachment' AND b.post_status = 'inherit'))
		 AND a.slider_id = '$slider_id' AND (a.expiry IS NULL OR a.expiry='0000-00-00' OR DATE(a.expiry) >= DATE(NOW()))
		 ORDER BY ".$orderby." LIMIT $offset, $max_posts", OBJECT);
	}
	else {
	$posts = $wpdb->get_results("SELECT b.* FROM 
	        $table_name a LEFT OUTER JOIN $post_table b 
		ON a.post_id = b.ID 
		WHERE (b.post_status = 'publish' OR (b.post_type='attachment' AND b.post_status = 'inherit')) AND a.slider_id = '$slider_id' AND (a.expiry IS NULL OR a.expiry='0000-00-00' OR DATE(a.expiry) >= DATE(NOW()) ) ORDER BY ".$orderby." LIMIT $offset, $max_posts", OBJECT);
	}
	$r_array=smooth_global_posts_processor( $posts, $smooth_slider, $out_echo, $set, $data );
	return $r_array;
}
function get_smooth_slider($slider_id='',$offset=0) {
	global $smooth_slider; 
 
	if($smooth_slider['multiple_sliders'] == '1' and is_singular()){
		global $post;
		$post_id = $post->ID;
		if(ss_slider_on_this_post($post_id))
			$slider_id = get_slider_for_the_post($post_id);
	}
	if(empty($slider_id) or !isset($slider_id))  $slider_id = '1';
	if(!empty($slider_id)){
		$set='';
		$data=array();
		$data['slider_id']=$slider_id;
		$slider_handle='smooth_slider_'.$slider_id;
		$data['slider_handle']=$slider_handle;
		$r_array = carousel_posts_on_slider($smooth_slider['no_posts'], $offset, $slider_id, '0', $set, $data ); 
		get_global_smooth_slider($slider_handle,$r_array,$smooth_slider,$set,$echo='1',$data);
	} //end of not empty slider_id condition
}
//For displaying category specific posts in chronologically reverse order, from Smooth Slider 2.3.3
function carousel_posts_on_slider_category($max_posts='5', $catg_slug='', $offset=0, $out_echo = '1', $set='', $data=array()) {
    global $smooth_slider,$default_slider;
	foreach($default_slider as $key=>$value){
		if(!isset($smooth_slider[$key])) $smooth_slider[$key]='';
	}
	global $wpdb, $table_prefix;
	
	if (!empty($catg_slug)) {
		$category = get_category_by_slug($catg_slug); 
		$slider_cat = $category->term_id;
	}
	else {
		$category = get_the_category();
		$slider_cat = $category[0]->cat_ID;
	}
	//WPML
	if( function_exists('icl_plugin_action_links') ) {
		$tr_table = $table_prefix."icl_translations";
		$slider_cat = $wpdb->get_var("
						SELECT element_id 
						FROM $tr_table 
						WHERE element_type = 'tax_category' 
						AND language_code = '".ICL_LANGUAGE_CODE."' 
						AND trid = ( 	SELECT trid 
								FROM $tr_table 
								WHERE element_type = 'tax_category' 
								AND element_id = $slider_cat
							)
					");
	}	
	//WPML END
	$rand = $smooth_slider['rand'];
	if(isset($rand) and $rand=='1'){
	  $orderby = '&orderby=rand';
	}
	else {
	  $orderby = '';
	}
	//extract posts
	$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset.'&category='.$slider_cat.$orderby);
	
	$r_array=smooth_global_posts_processor( $posts, $smooth_slider, $out_echo, $set, $data );
	return $r_array;
}
function get_smooth_slider_category($catg_slug,$offset=0) {
	global $smooth_slider; 
	$set='';
	$data=array();
	$slider_handle='foto_slider_'.$catg_slug;
    $data['slider_handle']=$slider_handle;
	$r_array = carousel_posts_on_slider_category($smooth_slider['no_posts'], $catg_slug, $offset, '0', $set, $data); 
	get_global_smooth_slider($slider_handle,$r_array,$smooth_slider,$set,$echo='1',$data);
} 
//For displaying recent posts in chronologically reverse order, from Smooth Slider 2.4
function carousel_posts_on_slider_recent($max_posts='5', $offset=0, $out_echo = '1', $set='', $data=array()) {
    global $smooth_slider;
	$rand = isset($smooth_slider['rand'])?$smooth_slider['rand']:'';
	if(isset($rand) and $rand=='1'){
	  $orderby = '&orderby=rand';
	}
	else {
	  $orderby = '';
	}
	//WPML
	if( function_exists('icl_plugin_action_links') ) {
		global $wpdb, $table_prefix;
		$post_table = $table_prefix."posts";
		$tr_table = $table_prefix."icl_translations";
		$posts=$wpdb->get_results("SELECT *
			FROM $post_table AS p
			LEFT OUTER JOIN $tr_table AS t 
			ON p.ID = t.element_id 
			WHERE t.element_type = 'post_post' 
			AND t.language_code = '".ICL_LANGUAGE_CODE."' 
			AND p.post_status = 'publish' 
			ORDER BY p.post_date DESC 
			LIMIT $offset, $max_posts
		");
	}
	else {
	//extract posts data
		$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset.$orderby);
	}
	$r_array=smooth_global_posts_processor( $posts, $smooth_slider, $out_echo, $set,$data );
	return $r_array;
}
function get_smooth_slider_recent($offset=0) {
	global $smooth_slider;  
	$set='';
	$data=array();
	$slider_handle='smooth_slider_recent';
	$data['slider_handle']=$slider_handle;
	$r_array = carousel_posts_on_slider_recent($smooth_slider['no_posts'], $offset, '0', $set,$data);
	get_global_smooth_slider($slider_handle,$r_array,$smooth_slider,$set,$echo='1',$data);
}
require_once (dirname (__FILE__) . '/shortcodes_1.php');
require_once (dirname (__FILE__) . '/widgets_1.php');

function smooth_slider_enqueue_scripts() {
	wp_enqueue_script( 'jquery');
}
add_action( 'init', 'smooth_slider_enqueue_scripts' );

//admin settings
function smooth_slider_admin_scripts() {
global $smooth_slider;
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( isset($_GET['page']) && ('smooth-slider-admin' == $_GET['page'] or 'smooth-slider-settings' == $_GET['page'] )  ) {
	wp_register_script('jquery', false, false, false, false);
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'smooth_slider_admin_js', smooth_slider_plugin_url( 'js/admin.js' ),
		array('jquery'), SMOOTH_SLIDER_VER, false); 
	wp_enqueue_script( 'jquery.bpopup.min', smooth_slider_plugin_url( 'js/jquery.bpopup.min.js' ),'', SMOOTH_SLIDER_VER, false);
	wp_enqueue_style( 'smooth_slider_admin_css', smooth_slider_plugin_url( 'css/admin.css' ),
		false, SMOOTH_SLIDER_VER, 'all');
	}
  }
}
add_action( 'admin_init', 'smooth_slider_admin_scripts' );

function smooth_slider_admin_head() {
global $smooth_slider;
if ( is_admin() ){ // admin actions
	if ( isset($_GET['page']) && ('smooth-slider-admin' == $_GET['page'] or 'smooth-slider-settings' == $_GET['page'])) {
	$smooth_slider_curr=get_option('smooth_slider_options');
	$active_tab=(isset($smooth_slider_curr['active_tab']))?$smooth_slider_curr['active_tab']:0;
			if ( isset($_GET['page']) && ('smooth-slider-admin' == $_GET['page']) ){ if(isset($_POST['active_tab']) ) $active_tab=$_POST['active_tab'];else $active_tab = 0;}
			if(empty($active_tab)){$active_tab=0;}
?>
			<script type="text/javascript">
		    // <![CDATA[
		jQuery(document).ready(function() { 
		           jQuery("#slider_tabs").tabs({fx: { opacity: "toggle", duration: 300}, active: <?php echo $active_tab;?> }).addClass( "ui-tabs-vertical-left ui-helper-clearfix" );
			 jQuery( "#slider_tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
					<?php 	if ( isset($_GET['page']) && (( 'smooth-slider-settings' == $_GET['page']) or ('smooth-slider-admin' == $_GET['page']) ) ) { ?>
		jQuery( "#slider_tabs" ).on( "tabsactivate", function( event, ui ) { 
		jQuery( "#smooth_activetab, .smooth_activetab" ).val( jQuery( "#slider_tabs" ).tabs( "option", "active" ) ); 
						});
		   <?php      }
			  $sliders = ss_get_sliders(); 
			  foreach($sliders as $slider){ ?>
		            jQuery("#sslider_sortable_<?php echo $slider['slider_id'];?>").sortable();
		            jQuery("#sslider_sortable_<?php echo $slider['slider_id'];?>").disableSelection();
				    <?php } ?>
			  jQuery( ".uploaded-images" ).sortable({ items: ".addedImg" });

		});
			</script> <?php
	}
  // Sliders page only
    if ( isset($_GET['page']) && 'smooth-slider-admin' == $_GET['page'] ) {?>
<script type="text/javascript"> 
       function confirmRemove()
        {
            var agree=confirm("This will remove selected Posts/Pages from Slider.");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmRemoveAll()
        {
            var agree=confirm("Remove all Posts/Pages from Smooth Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmSliderDelete()
        {
            var agree=confirm("Delete this Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function slider_checkform ( form )
        {
          if (form.new_slider_name.value == "") {
            alert( "Please enter the New Slider name." );
            form.new_slider_name.focus();
            return false ;
          }
          return true ;
        }
        </script>
<?php
   } //Sliders page only
   
   // Settings page only
  if ( isset($_GET['page']) && 'smooth-slider-settings' == $_GET['page']  ) {
$smooth_slider_curr=get_option('smooth_slider_options');
		wp_print_scripts( 'wp-color-picker' );
		wp_print_styles( 'wp-color-picker' );
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.wp-color-picker-field').wpColorPicker();
});

function confirmSettingsImport()
        {
            var agree=confirm("Reset these Settings to Imported Settings Set??");
            if (agree)
            return true ;
            else
            return false ;
}
</script>
<style type="text/css">
.color-picker-wrap {
		position: absolute;
 		display: none; 
		background: #fff;
		border: 3px solid #ccc;
		padding: 3px;
		z-index: 1000;
	}
#sldr_message {background-color:#FEF7DA;clear:both;width:72%;}
#sldr_close {float:right;} 
</style>
<?php
   } //for smooth slider option page
 }//only for admin
?>
<style type="text/css">#adminmenu #toplevel_page_smooth-slider-admin div.wp-menu-image:before { content: "\f233"; }</style>
<?php
}
add_action('admin_head', 'smooth_slider_admin_head');

//get inline css with style attribute attached
function smooth_get_inline_css($echo='0'){
    global $smooth_slider,$default_slider,$post;
	foreach($default_slider as $key=>$value){
		if(!isset($smooth_slider[$key])) $smooth_slider[$key]='';
	}
		//If no Skin specified, consider Default
	$skin='default';
	$smooth_slider_style='';
	
	if(is_singular()) {	
		$smooth_slider_style = get_post_meta($post->ID,'_smooth_slider_style',true);
		//for compatibility with lower versions of Smooth Slider
		if( empty($smooth_slider_style) ) $smooth_slider_style=get_post_meta($post->ID,'slider_style',true);
	}

	if(empty($smooth_slider_style)){
		if(isset($smooth_slider['stylesheet'])) $skin=$smooth_slider['stylesheet'];
	}
	else{
		$skin=$smooth_slider_style;
	}
	if(empty($skin))$skin='default';
	
	//Always include Default Skin
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/default/functions.php');
	//Include Skin function file
	if($skin!='default' and file_exists(dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php'))require_once ( dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php');
	
	//Skin specific data processor and html generation
	$data_processor_fn='smooth_post_processor_'.$skin;
	if(function_exists($data_processor_fn))$default=true;
	else $default=false;
	$smooth_slider_css=array(
		'smooth_slider'=>'',
		'smooth_slider_handle'=>'',
		'sldr_title'=>'',
		'smooth_slideri'=>'',
		'smooth_slider_h2'=>'',
		'smooth_slider_h2_a'=>'',
		'smooth_slider_span'=>'',
		'smooth_slider_thumbnail'=>'',
		'smooth_slider_p_more'=>'',
		'smooth_text'=>'',
		'smooth_next'=>'',
		'smooth_prev'=>''
		);
	if($default){
		$style_start= ($echo=='0') ? 'style="':'';
		$style_end= ($echo=='0') ? '"':'';
	//smooth_slider
		$smooth_slider_css['smooth_slider']=$style_start.'max-width:'.$smooth_slider['width'].'px;height:'.$smooth_slider['height'].'px;min-height:'.$smooth_slider['height'].'px;background-color:'. ( ($smooth_slider['bg'] == '1') ? "transparent" : $smooth_slider['bg_color'] ) .';border:'. $smooth_slider['border'].'px solid '.$smooth_slider['brcolor'].';'.$style_end;
		
		if ($smooth_slider['title_fstyle'] == "bold" or $smooth_slider['title_fstyle'] == "bold italic" ){$slider_title_font = "bold";} else { $slider_title_font = "normal"; }
		if ($smooth_slider['title_fstyle'] == "italic" or $smooth_slider['title_fstyle'] == "bold italic" ){$slider_title_style = "italic";} else {$slider_title_style = "normal";}
	//sldr_title	
		$smooth_slider_css['sldr_title']=$style_start.'font-family:'.$smooth_slider['title_font'].', Arial, Helvetica, sans-serif;font-size:'. $smooth_slider['title_fsize'].'px;font-weight:'.$slider_title_font.';font-style:'.$slider_title_style.';color:'.$smooth_slider['title_fcolor'].';'.$style_end;

		if ($smooth_slider['bg'] == '1') { $smooth_slideri_bg = "transparent";} else { $smooth_slideri_bg = $smooth_slider['bg_color']; }
	//smooth_slideri
		$smooth_slider_css['smooth_slideri']=$style_start.'max-width:'. ( ($smooth_slider['prev_next'] == 1) ? ( $smooth_slider['width'] - 48 ): $smooth_slider['width'] ) .'px;margin:0px '. ( ($smooth_slider['prev_next'] == 1) ? "24": "0" ) .'px 0px '. ( ($smooth_slider['prev_next'] == 1) ? "24": "0" ) .'px;'.$style_end;
		
		if ($smooth_slider['ptitle_fstyle'] == "bold" or $smooth_slider['ptitle_fstyle'] == "bold italic" ){$ptitle_fweight = "bold";} else {$ptitle_fweight = "normal";}
		if ($smooth_slider['ptitle_fstyle'] == "italic" or $smooth_slider['ptitle_fstyle'] == "bold italic"){$ptitle_fstyle = "italic";} else {$ptitle_fstyle = "normal";}
	if ($smooth_slider['img_align'] == "none"){$margin="1em 0 5px 0;";}else{$margin="0 0 5px 0;";}
	//smooth_slider_h2
		$smooth_slider_css['smooth_slider_h2']=$style_start.'clear:none;line-height:'. ($smooth_slider['ptitle_fsize'] + 3) .'px;font-size:'.$smooth_slider['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';color:'.$smooth_slider['ptitle_fcolor'].$style_end;
		
	//smooth_slider_h2 a
		$smooth_slider_css['smooth_slider_h2_a']=$style_start.'color:'.$smooth_slider['ptitle_fcolor'].';font-size:'.$smooth_slider['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';'.$style_end;
	
		if ($smooth_slider['content_fstyle'] == "bold" or $smooth_slider['content_fstyle'] == "bold italic" ){$content_fweight= "bold";} else {$content_fweight= "normal";}
		if ($smooth_slider['content_fstyle']=="italic" or $smooth_slider['content_fstyle'] == "bold italic"){$content_fstyle= "italic";} else {$content_fstyle= "normal";}
	//smooth_slider_span
		$smooth_slider_css['smooth_slider_span']=$style_start.'font-size:'.$smooth_slider['content_fsize'].'px;font-weight:'.$content_fweight.';font-style:'.$content_fstyle.';color:'. $smooth_slider['content_fcolor'].';'.$style_end;
		
		if($smooth_slider['img_align'] == "left") {$thumb_margin_right= "10";} else {$thumb_margin_right= "0";}
		if($smooth_slider['img_align'] == "right") {$thumb_margin_left = "10";} else {$thumb_margin_left = "0";}
		if($smooth_slider['img_size'] == '1'){ $thumb_width= 'width:'. $smooth_slider['img_width'].'px;';} else{$thumb_width='';}
	//smooth_slider_thumbnail
		$smooth_slider_css['smooth_slider_thumbnail']=$style_start.'float:'.$smooth_slider['img_align'].';margin:0 '.$thumb_margin_right.'px 0 '.$thumb_margin_left.'px;max-height:'.$smooth_slider['img_height'].'px;border:'.$smooth_slider['img_border'].'px solid '.$smooth_slider['img_brcolor'].';'.$thumb_width.$style_end;

//smooth_slider_eshortcode
		$smooth_slider_css['smooth_slider_eshortcode']=$style_start.'float:'.$smooth_slider['img_align'].';margin:0 '.$thumb_margin_right.'px 0 '.$thumb_margin_left.'px;height:'.$smooth_slider['img_height'].'px;border:'.$smooth_slider['img_border'].'px solid '.$smooth_slider['img_brcolor'].';'.$thumb_width.$style_end;
	
	//smooth_slider_p_more
		$smooth_slider_css['smooth_slider_p_more']=$style_start.'color:'.$smooth_slider['readmorecolor'].';font-family:'.$smooth_slider['content_font'].';font-size:'.$smooth_slider['content_fsize'].'px;margin-left: 10px;'.$style_end;
		
		$smooth_slider_css['sldrlink']=$style_start.'padding-right:'. ( ($smooth_slider['prev_next'] == 1) ? "10" : "0" ) .'px;"';
		$smooth_slider_css['sldrlink_a']='style="color:'.$smooth_slider['content_fcolor'].' !important;'.$style_end;
	
	}
	return $smooth_slider_css;
}
function smooth_slider_css() {
global $smooth_slider;
$css=$smooth_slider['css'];
if($css and !empty($css)){?>
 <style type="text/css"><?php echo $css;?></style>
<?php }
}
add_action('wp_head', 'smooth_slider_css');
add_action('admin_head', 'smooth_slider_css');
?>
