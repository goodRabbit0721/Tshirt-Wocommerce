<?php
/*
Plugin Name: Smooth Slider
Plugin URI: http://slidervilla.com/smooth-slider/
Description: Smooth slider adds a responsive featured content on image slider using shortcode, widget and template tags. Create and embed featured content slider, recent post slider, category slider in less than 60 seconds.
Version: 2.8.4
Author: SliderVilla
Text Domain: smooth-slider
Author URI: http://slidervilla.com/
Wordpress version supported: 2.9 and above
*/

/*  Copyright 2009-2014  SliderVilla  (email : support@slidervilla.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//Please visit Plugin page http://slidervilla.com/smooth-slider/ for Changelog
//on activation
//defined global variables and constants here
global $smooth_slider,$default_slider,$smooth_db_version,$default_smooth_slider_settings;
$smooth_slider = get_option('smooth_slider_options');
$smooth_db_version='2.8.4'; //current version of smooth slider database 
define('SLIDER_TABLE','smooth_slider'); //Slider TABLE NAME
define('PREV_SLIDER_TABLE','slider'); //Slider TABLE NAME
define('SLIDER_META','smooth_slider_meta'); //Meta TABLE NAME
define('SLIDER_POST_META','smooth_slider_postmeta'); //Meta TABLE NAME
define("SMOOTH_SLIDER_VER","2.8.4",false);//Current Version of Smooth Slider
if ( ! defined( 'SMOOTH_SLIDER_PLUGIN_BASENAME' ) )
	define( 'SMOOTH_SLIDER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'SMOOTH_SLIDER_CSS_DIR' ) ){
	if(isset($smooth_slider['ver']) && $smooth_slider['ver']=='step') define( 'SMOOTH_SLIDER_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/styles/' );
	else define( 'SMOOTH_SLIDER_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/skins/' );
}
 
// Need to delete the previously created options in old versions and create only one option field for Smooth Slider
$default_smooth_slider_settings=$default_slider = array();
$default_smooth_slider_settings=$default_slider = array('speed'=>'7', 
	'no_posts'=>'5', 
	'bg_color'=>'#ffffff', 
	'height'=>'250',
	'width'=>'450',
	'border'=>'0',
	'brcolor'=>'#dddddd',
	'prev_next'=>'0',
	'goto_slide'=>'1',
	'title_text'=>'Featured Posts',
	'title_from'=>'0',
	'title_font'=>'Georgia',
	'title_fsize'=>'20',
	'title_fstyle'=>'bold',
	'title_fcolor'=>'#000000',
	'ptitle_font'=>'Trebuchet MS',
	'ptitle_fsize'=>'14',
	'ptitle_fstyle'=>'bold',
	'ptitle_fcolor'=>'#000000',
	'img_align'=>'left',
	'img_height'=>'120',
	'img_width'=>'165',
	'img_border'=>'1',
	'img_brcolor'=>'#000000',
	'content_font'=>'Verdana',
	'content_fsize'=>'12',
	'content_fstyle'=>'normal',
	'content_fcolor'=>'#333333',
	'content_from'=>'content',
	'content_chars'=>'300',
	'bg'=>'0',
	'image_only'=>'0',
	'allowable_tags'=>'',
	'more'=>'Read More',
	'img_size'=>'1',
	'img_pick'=>array('1','slider_thumbnail','1','1','1','1'), //use custom field/key, name of the key, use post featured image, pick the image attachment, attachment order,scan images
	'user_level'=>'edit_others_posts',
	'custom_nav'=>'',
	'crop'=>'0',
	'transition'=>'5',
	'autostep'=>'1',
	'multiple_sliders'=>'1',
	'navimg_w'=>'8',
	'navimg_ht'=>'8',
	'content_limit'=>'20',
	'stylesheet'=>'default',
	'shortcode'=>'1',
	'rand'=>'0',
	'ver'=>'j',
	'fouc'=>'0',
	'fx'=>'scrollHorz',
	'css'=>'',
	'active_tab'=>'0',
	'disable_preview'=>'0',
	'preview'=>'2',
	'slider_id'=>'1',
	'catg_slug'=>'',
	'popup'=>'1',
	'readmorecolor'=>'#0092E4',
	'noscript'=>'',
	'reviewme'=>strtotime("+1 week")
);
// Create Text Domain For Translations
load_plugin_textdomain('smooth-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

function install_smooth_slider() {
	global $wpdb, $table_prefix,$smooth_db_version;
	$installed_ver = get_option( "smooth_db_version" );
	if( $installed_ver != $smooth_db_version ) {
		$table_name = $table_prefix.SLIDER_TABLE;
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE $table_name (
						id int(5) NOT NULL AUTO_INCREMENT,
						post_id int(11) NOT NULL,
						date datetime NOT NULL,
						slider_id int(5) NOT NULL DEFAULT '1',
						UNIQUE KEY id(id)
					);";
			$rs = $wpdb->query($sql);
		
			$prev_table_name = $table_prefix.PREV_SLIDER_TABLE;
		
			if($wpdb->get_var("show tables like '$prev_table_name'") == $prev_table_name) {
				$prev_slider_data = ss_get_prev_slider();
				foreach ($prev_slider_data as $prev_slider_row){
					$prev_post_id = $prev_slider_row['id'];
					$prev_date_time = $prev_slider_row['date'];
					if ($prev_post_id) {
						$wpdb->query( 
							$wpdb->prepare( 
								"INSERT INTO $table_name
								(post_id,date)
								VALUES ( %d, %s )
								", 
								$prev_post_id,
								$prev_date_time
							) 
						);
					}
				}
			}
		}
		add_cf5_column_if_not_exist($table_name, 'slide_order', "ALTER TABLE ".$table_name." ADD slide_order int(5) NOT NULL DEFAULT '0';");
		if($wpdb->get_var("SHOW COLUMNS FROM $table_name LIKE 'expiry'") != 'expiry') {
			$sql = "ALTER TABLE $table_name
			ADD COLUMN expiry DATE DEFAULT NULL";
			$rs1 = $wpdb->query($sql);
		}
		//add_cf5_column_if_not_exist($table_name, 'expiry', "ALTER TABLE ".$table_name." ADD expiry datetime NOT NULL DEFAULT 'NULL';");		


	   	$meta_table_name = $table_prefix.SLIDER_META;
		if($wpdb->get_var("show tables like '$meta_table_name'") != $meta_table_name) {
			$sql = "CREATE TABLE $meta_table_name (
					slider_id int(5) NOT NULL AUTO_INCREMENT,
					slider_name varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
					UNIQUE KEY slider_id(slider_id)
				);";
			$rs2 = $wpdb->query($sql);
		
			$wpdb->query( 
				$wpdb->prepare( 
					"INSERT INTO $meta_table_name
					(slider_id,slider_name)
					VALUES ( %d, %s )", 
					1,
					'Smooth Slider'
				) 
			);
		}
		else{
			if($installed_ver<'2.8'){
				$sql = "ALTER TABLE $meta_table_name CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
				$rs3 = $wpdb->query($sql);
			}
		}
		$slider_postmeta = $table_prefix.SLIDER_POST_META;
		if($wpdb->get_var("show tables like '$slider_postmeta'") != $slider_postmeta) {
			$sql = "CREATE TABLE $slider_postmeta (
						post_id int(11) NOT NULL,
						slider_id int(5) NOT NULL default '1',
						UNIQUE KEY post_id(post_id)
					);";
			$rs4 = $wpdb->query($sql);
		}
	  
			global $default_slider;
	  
		   $smooth_slider = get_option('smooth_slider_options');
		   	   	   
		   $img_pick = $smooth_slider['img_pick'];
	  
	       if(is_array($img_pick)) {
		    $cskey = $smooth_slider['img_pick'][1];
		   }
		   else{
		    $cskey = 'slider_thumbnail';
		   }
	      
		   if(!is_array($img_pick)) {
		   //if(!isset($smooth_slider['img_pick'][0])) {
			   if($smooth_slider['img_pick'] == '1') {
				  $smooth_slider['img_pick'] = array('0',$cskey,'0','0','1','1');
			   }
			   elseif($smooth_slider['img_pick'] == '0'){
				  $smooth_slider['img_pick'] = array('1',$cskey,'0','0','1','0');
			   }
			   else {
				  $smooth_slider['img_pick'] = array('1',$cskey,'1','1','1','1');
			   }
		   }
		   
		   if(!$smooth_slider) {
		     $smooth_slider = array();
		   }
		   
		   //if($smooth_slider and !isset($smooth_slider['ver'])){
		      $smooth_slider['stylesheet']='default';
		   //}
		   
		   foreach($default_slider as $key=>$value) {
		      if(!isset($smooth_slider[$key])) {
			     $smooth_slider[$key] = $value;
			  }
		   }
		   	   
		   $smooth_slider['ver']='j';
	     
		 if($smooth_slider['user_level']<=10 and $smooth_slider['user_level'] >=1) {
			 if($smooth_slider['user_level']<=10 and $smooth_slider['user_level'] >7) {
			  $smooth_slider['user_level']='manage_options';
			 }
			 elseif($smooth_slider['user_level']<=7 and $smooth_slider['user_level'] >2){
			  $smooth_slider['user_level']='edit_others_posts';
			 } 
			  elseif($smooth_slider['user_level']==2){
			  $smooth_slider['user_level']='publish_posts';
			 } 
			 else {
			  $smooth_slider['user_level']='edit_posts';
			 }
		 }
		  
		   delete_option('smooth_slider_options');	  
		   update_option('smooth_slider_options',$smooth_slider);
		   update_option( "smooth_db_version", $smooth_db_version );
	}
}
register_activation_hook( __FILE__, 'install_smooth_slider' );
/* Added for auto update - start */
function smooth_update_db_check() {
    global $smooth_db_version;
    if (get_option('smooth_db_version') != $smooth_db_version) {
        install_smooth_slider();
    }
}
add_action('plugins_loaded', 'smooth_update_db_check');
/* Added for auto update - end */
require_once (dirname (__FILE__) . '/includes/smooth-slider-functions.php');
require_once (dirname (__FILE__) . '/includes/sslider-get-the-image-functions.php');

//This adds the post to the slider
function add_to_slider($post_id) {
global $smooth_slider;
	
	if(isset($_POST['sldr-verify']) and current_user_can( $smooth_slider['user_level'] ) ) {
		global $wpdb, $table_prefix, $post;
		$table_name = $table_prefix.SLIDER_TABLE;
	
		if( !isset($_POST['smooth-slider']) and  is_post_on_any_slider($post_id) ){
			$wpdb->delete($table_name, array('post_id' => $post_id)); 
		}
	
		if(isset($_POST['smooth-slider']) and !isset($_POST['smooth_slider_name'])) {
		  $slider_id = '1';
		  if(is_post_on_any_slider($post_id)){
		     $wpdb->delete($table_name, array('post_id' => $post_id)); 
		  }
		  
		  if(isset($_POST['smooth-slider']) and $_POST['smooth-slider'] == "smooth-slider" and !slider($post_id,$slider_id)) {
			$dt = date('Y-m-d H:i:s');
			$wpdb->insert($table_name, array('post_id' => $post_id, 'date' => $dt, 'slider_id' => $slider_id)); 
		  }
		}
		if(isset($_POST['sslider_expiry']) ) {
			$expiry=$_POST['sslider_expiry'];
			if(!empty($expiry)){
				$date=$expiry;
				$dt = date("Y-m-d",strtotime($date));
				$wpdb->update($table_name, array('expiry' => $dt), array('post_id' => $post_id)); 
				update_post_meta($post_id, '_sslider_expiry', $dt);
			}
			else{
				$wpdb->update($table_name, array('expiry' => NULL), array('post_id' => $post_id)); 
				update_post_meta($post_id, '_sslider_expiry', '');
			}
		}
		if(isset($_POST['smooth-slider']) and $_POST['smooth-slider'] == "smooth-slider" and isset($_POST['smooth_slider_name'])){
		  $slider_id_arr = $_POST['smooth_slider_name'];
		  $post_sliders_data = ss_get_post_sliders($post_id);
		  
		  foreach($post_sliders_data as $post_slider_data){
			if(!in_array($post_slider_data['slider_id'],$slider_id_arr)) {
			  $wpdb->delete($table_name, array('post_id' => $post_id)); 
			}
		  }

			foreach($slider_id_arr as $slider_id) {
				if(!slider($post_id,$slider_id)) {
					$dt = date('Y-m-d H:i:s');
					$wpdb->insert($table_name, array('post_id' => $post_id, 'date' => $dt, 'slider_id' => $slider_id)); 
				}
			}
		}
	
		$table_name = $table_prefix.SLIDER_POST_META;
		if(isset($_POST['smooth_display_slider']) and !isset($_POST['smooth_display_slider_name'])) {
		  $slider_id = '1';
		}
		if(isset($_POST['smooth_display_slider']) and isset($_POST['smooth_display_slider_name'])){
		  $slider_id = $_POST['smooth_display_slider_name'];
		}
	  	if(isset($_POST['smooth_display_slider'])){	
			  if(!ss_post_on_slider($post_id,$slider_id)) {
				$wpdb->delete($table_name, array('post_id' => $post_id)); 
				$wpdb->insert($table_name, array('post_id' => $post_id, 'slider_id' => $slider_id)); 
			  }
		}

		$thumbnail_key = $smooth_slider['img_pick'][1];
		$sslider_thumbnail = get_post_meta($post_id,$thumbnail_key,true);
		$post_slider_thumbnail=isset($_POST['sslider_thumbnail'])?$_POST['sslider_thumbnail']:'';
		if($sslider_thumbnail != $post_slider_thumbnail) {
		  update_post_meta($post_id, $thumbnail_key, $post_slider_thumbnail);	
		}
	
		$sslider_link = get_post_meta($post_id,'slide_redirect_url',true);
		$link=isset($_POST['sslider_link'])?$_POST['sslider_link']:'';
		//$sldr_post=get_post($post_id);
		//if((!isset($link) or empty($link)) and $sldr_post->post_status == 'publish'  ){$link=get_permalink($post_id);}//from 2.3.3
		if($sslider_link != $link) {
		  update_post_meta($post_id, 'slide_redirect_url', $link);	
		}
	
		$sslider_expiry = get_post_meta($post_id,'sslider_expiry',true);
		$post_sslider_expiry = isset($_POST['sslider_expiry'])?$_POST['sslider_expiry']:'';
		if($sslider_expiry != $post_sslider_expiry) {
		  update_post_meta($post_id, '_sslider_expiry', $post_sslider_expiry);	
		}	

		$sslider_nolink = get_post_meta($post_id,'sslider_nolink',true);
		$post_sslider_nolink = isset($_POST['sslider_nolink'])?$_POST['sslider_nolink']:'';
		if($sslider_nolink != $post_sslider_nolink) {
		  update_post_meta($post_id, 'sslider_nolink', $post_sslider_nolink);	
		}
		/* Added for embed shortcode - start */
		$disable_image = get_post_meta($post_id,'_disable_image',true);
		$post_disable_image = isset($_POST['disable_image'])?$_POST['disable_image']:'';
		if($disable_image != $post_disable_image ) {
		  update_post_meta($post_id, '_disable_image', $post_disable_image );	
		}
		$smooth_sslider_eshortcode = get_post_meta($post_id,'_smooth_embed_shortcode',true);
		$post_smooth_sslider_eshortcode = isset($_POST['smooth_sslider_eshortcode'])?$_POST['smooth_sslider_eshortcode']:'';
		if($smooth_sslider_eshortcode != $post_smooth_sslider_eshortcode) {
		  update_post_meta($post_id, '_smooth_embed_shortcode', $post_smooth_sslider_eshortcode);	
		}
		$slider_style = get_post_meta($post_id,'_smooth_slider_style',true);
		$post_slider_style=isset($_POST['_smooth_slider_style'])?$_POST['_smooth_slider_style']:'';
		if($slider_style != $post_slider_style) {
		  update_post_meta($post_id, '_smooth_slider_style', $post_slider_style);	
		}
		/* Added for embed shortcode -end */
	
	  } //sldr_verify
}

//Removes the post from the slider, if you uncheck the checkbox from the edit post screen
function remove_from_slider($post_id) {
	if(isset($_POST['sldr-verify'])) {
		global $wpdb, $table_prefix;
		$table_name = $table_prefix.SLIDER_TABLE;
	
		// authorization
		if (!current_user_can('edit_post', $post_id))
			return $post_id;
		// origination and intention
		if (!wp_verify_nonce($_POST['sldr-verify'], 'SmoothSlider'))
			return $post_id;
	
	    	if(empty($_POST['smooth-slider']) and is_post_on_any_slider($post_id)) {
			$wpdb->delete( $table_name, array( 'post_id' => $post_id ), array( '%d' ) );
		}
	
		$display_slider = isset($_POST['display_slider'])?$_POST['display_slider']:'';
		$table_name = $table_prefix.SLIDER_POST_META;
		if(empty($display_slider) and ss_slider_on_this_post($post_id)){
		 	$wpdb->delete( $table_name, array( 'post_id' => $post_id ), array( '%d' ) );
		}
	}
} 
  
function delete_from_slider_table($post_id){
    	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;
    	if(is_post_on_any_slider($post_id)) {
		$wpdb->delete( $table_name, array( 'post_id' => $post_id ), array( '%d' ) );
	}
	$table_name = $table_prefix.SLIDER_POST_META;
    	if(ss_slider_on_this_post($post_id)) {
		$wpdb->delete( $table_name, array( 'post_id' => $post_id ), array( '%d' ) );
	}
}

// Slider checkbox on the admin page

function smooth_slider_edit_custom_box(){
   add_to_slider_checkbox();
}
/* Prints the edit form for pre-WordPress 2.5 post/page */
function smooth_slider_old_custom_box() {

  echo '<div class="dbx-b-ox-wrapper">' . "\n";
  echo '<fieldset id="myplugin_fieldsetid" class="dbx-box">' . "\n";
  echo '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">' . 
        __( 'Smooth Slider', 'smooth-slider' ) . "</h3></div>";   
   
  echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';

  // output editing form

  smooth_slider_edit_custom_box();

  // end wrapper

  echo "</div></div></fieldset></div>\n";
}

function smooth_slider_add_custom_box() {
 global $smooth_slider;
 if (current_user_can( $smooth_slider['user_level'] )) {
	if( function_exists( 'add_meta_box' ) ) {
	    $post_types=get_post_types(); 
		foreach($post_types as $post_type) {
		  add_meta_box( 'sslider_box1', __( 'Smooth Slider' , 'smooth-slider'), 'smooth_slider_edit_custom_box', $post_type, 'advanced' );
		}
		//add_meta_box( $id,   $title,     $callback,   $page, $context, $priority ); 
	} else {
		add_action('dbx_post_advanced', 'smooth_slider_old_custom_box' );
		add_action('dbx_page_advanced', 'smooth_slider_old_custom_box' );
	}
 }
}
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'smooth_slider_add_custom_box');

function add_to_slider_checkbox() {
	global $post, $smooth_slider;	
	//for WPML start
	if( function_exists('icl_plugin_action_links') ) {
		if( isset($_GET['source_lang']) && isset($_GET['trid']) ) {
			global $wpdb, $table_prefix;
			$id = $wpdb->get_var( "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid=".$_GET['trid']." AND language_code='".$_GET['source_lang']."'" );			
			$table_name = $table_prefix.SLIDER_TABLE;
			$q = "select * from $table_name where post_id=".$id;
			$res = $wpdb->get_results($q);
			if( count($res) > 0 ) {
				$sarr=array();
				foreach($res as $re) {
					$sarr[] = $re->slider_id;
				}
				echo "<script type='text/javascript'>";
				echo "jQuery(document).ready(function($) {";
					echo "jQuery('.smooth-psldr-post').prop('checked','true');";
					$sliders = ss_get_sliders();
					foreach ($sliders as $slider) { 
						if(in_array($slider['slider_id'],$sarr)) {
							echo "jQuery('#smooth_slider_name".$slider['slider_id']."').prop('checked','true');";
						}
					}
				echo "});";
				echo "</script>";
			}
		}
	}
	//for WPML end


	if (current_user_can( $smooth_slider['user_level'] )) {
		$extra = "";
		
		$post_id = $post->ID;
		
		if(isset($post->ID)) {
			$post_id = $post->ID;
			if(is_post_on_any_slider($post_id)) { $extra = 'checked="checked"'; }
		} 
		
		$post_slider_arr = array();
		
		$post_sliders = ss_get_post_sliders($post_id);
		if($post_sliders) {
			foreach($post_sliders as $post_slider){
			   $post_slider_arr[] = $post_slider['slider_id'];
			}
		}
		
		$sliders = ss_get_sliders();
		$wpDateFormat = get_option('date_format');
		$sslider_link= get_post_meta($post_id, 'slide_redirect_url', true);  
		$sslider_nolink=get_post_meta($post_id, 'sslider_nolink', true);
		$thumbnail_key = $smooth_slider['img_pick'][1];
                $sslider_thumbnail= get_post_meta($post_id, $thumbnail_key, true); 
		$sslider_disable_image=get_post_meta($post_id, '_disable_image', true);
		$smooth_embed_shortcode=get_post_meta($post_id, '_smooth_embed_shortcode', true);
		$sslider_expiry=get_post_meta($post_id, '_sslider_expiry', true);
		/* Post Meta Box Style */
		wp_enqueue_style( 'smooth-meta-box', smooth_slider_plugin_url( 'css/smooth-meta-box.css' ), false, SMOOTH_SLIDER_VER, 'all');
		wp_enqueue_script( 'jquery-ui-datepicker', false,array('jquery','jQuery-ui-core'),SMOOTH_SLIDER_VER, true); 
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		$dtpicker = smooth_dateformat_PHP_to_jQueryUI($wpDateFormat);
?>	
	  <?php	/* start tab 2.6 */ ?>	
             <script type="text/javascript">
		jQuery(document).ready(function($) {
			jQuery('#smooth_ExpiryDate').datepicker({
				dateFormat : "<?php echo $dtpicker ?>"
			});
			jQuery("#smooth_basic").css({"background":"#222222","color":"#ffffff"});
			jQuery("#smooth_basic").on("click", function(){ 
				jQuery("#smooth_basic_tab").fadeIn("fast");
				jQuery("#smooth_advaced_tab").fadeOut("fast");
				jQuery(this).css({"background":"#222222","color":"#ffffff"});
				jQuery("#smooth_advanced").css({"background":"buttonface","color":"#222222"});
			});
			jQuery("#smooth_advanced").on("click", function(){
				jQuery("#smooth_basic_tab").fadeOut("fast");
				jQuery("#smooth_advaced_tab").fadeIn("fast");
				jQuery(this).css({"background":"#222222","color":"#ffffff"});
				jQuery("#smooth_basic").css({"background":"buttonface","color":"#222222"});
				
			});
			jQuery(".show-all").click(function() {
				jQuery(this).fadeOut("fast");
				jQuery(".slider-name").fadeIn("slow");
				return false;
			});
			jQuery(".smooth-add-to-slider").click(function() {
				var added = 0;
				jQuery(".smooth-add-to-slider").each(function() {
					if(jQuery(this).prop("checked") == true) { added = added + 1; }
				});
				if( added >= 1 ) {
					jQuery(".smooth-psldr-post").prop("checked", true );
				} else { 
					jQuery(".smooth-psldr-post").prop("checked", false );
				}
			});
		}); 
		</script>
	    <?php	/* End tab 2.6 */ ?>
	<div style="border-bottom: 1px solid #ccc;padding-bottom: 0;padding-left: 10px;">
		<button type="button" id="smooth_basic" style="padding:5px 30px 5px 30px;margin: 0;cursor:pointer;border:0;outline:none;">Basic</button>
		<button type="button" id="smooth_advanced" style="padding:5px 30px 5px 30px;margin:0 0 0 10px;cursor:pointer;border:0;outline:none">Advanced</button>
		</div>
	<div id="smooth_basic_tab">
		<div id="slider_checkbox">
		<table class="form-table">
		</tr>
				<tr valign="top">
				<td scope="row">
					<input id="smooth-slider" name="smooth-slider" class="smooth-psldr-post" type="checkbox" value="smooth-slider" <?php echo $extra;?> >
					<label><?php _e('Add this post/page to','smooth-slider'); ?></label>
				</td>
				<td>
                		<?php $i = 0;
				foreach ($sliders as $slider) { 
					if($i < 3) $display="display:block;"; else $display="display:none;"; ?>
					<div style="margin-bottom: 16px;float: left;width: 100%;<?php echo $display;?>" class="slider-name">
					<span style="float: left;margin-right: 20px;min-width: 100px;"><?php echo $slider['slider_name'];?></span>
					<input id="smooth_slider_name<?php echo $slider['slider_id'];?>" name="smooth_slider_name[]" class="smooth-meta-toggle smooth-meta-toggle-round smooth-add-to-slider" type="checkbox" value="<?php echo $slider['slider_id'];?>" <?php if(in_array($slider['slider_id'],$post_slider_arr)){echo 'checked';} ?> >
					<label for="smooth_slider_name<?php echo $slider['slider_id'];?>"></label>
					</div>
                		<?php $i++;
				} if($i > 3) { ?>
					<a href="" class='show-all'><?php _e('Show All','smooth-slider'); ?></a>
				<?php } ?>
               				<input type="hidden" name="smooth-sldr-verify" id="smooth-sldr-verify" value="<?php echo wp_create_nonce('SmoothProSlider');?>" />
				</td>
				</tr>
	
	<tr valign="top">
		 <th scope="row"><label for="sslider_link"><?php _e('Slide Link URL ','smooth-slider'); ?></label></th>
                <td><input type="text" name="sslider_link" class="sslider_link" value="<?php echo $sslider_link;?>" size="50" /><small><?php _e('If left empty, it will be by default linked to the permalink.','smooth-slider'); ?></small> </td></tr>
                
	<tr valign="top">
		 <th scope="row"><label for="sslider_nolink"> <?php _e('Do not link this slide to any page(url)','smooth-slider'); ?></label></th>
                <td><input type="checkbox" name="sslider_nolink" class="sslider_nolink" value="1" <?php if($sslider_nolink=='1'){echo "checked";}?>  /> </td></tr>
		</table>
                  </div>
	</div>
	<div id="smooth_advaced_tab" style="display:none;">   
		<div class="slider_checkbox">
		<table class="form-table">
            <?php if($smooth_slider['multiple_sliders'] == '1') {?>
                <tr valign="top">
		<th scope="row"><label for="display_slider"><?php _e('Display ','smooth-slider'); ?>
		<select name="display_slider_name">
                <?php foreach ($sliders as $slider) { ?>
                  <option value="<?php echo $slider['slider_id'];?>" <?php if(ss_post_on_slider($post_id,$slider['slider_id'])){echo 'selected';} ?>><?php echo $slider['slider_name'];?></option>
                <?php } ?>
                </select><?php _e('on this Post/Page','smooth-slider'); ?></th> 
		<td><input type="checkbox" class="sldr_post" name="display_slider" value="1" <?php if(ss_slider_on_this_post($post_id)){echo "checked";}?> /><?php _e('(Add the Smooth Slider template tag manually on your page.php/single.php or whatever page template file)'); ?></label></td></tr>
          <?php } ?>
		  
		<tr valign="top">
		 <th scope="row"><label for="_smooth_slider_style"><?php _e('Stylesheet to use if slider is displayed on this Post/Page','smooth-slider'); ?> </label></th>
    <?php
        $slider_style = get_post_meta($post->ID,'_smooth_slider_style',true);
        ?>
        <td> <select name="_smooth_slider_style" >
			<?php 
            $directory = SMOOTH_SLIDER_CSS_DIR;
            if ($handle = opendir($directory)) {
                while (false !== ($file = readdir($handle))) { 
                 if($file != '.' and $file != '..') { ?>
                  <option value="<?php echo $file;?>" <?php if (($slider_style == $file) or (empty($slider_style) and $smooth_slider['stylesheet'] == $file)){ echo "selected";}?> ><?php echo $file;?></option>
             <?php  } }
                closedir($handle);
            }
            ?>
        </select> </td></tr>
          
	<input type="hidden" name="sldr-verify" id="sldr-verify" value="<?php echo wp_create_nonce('SmoothSlider');?>" />
	   	 <tr valign="top">
		 <th scope="row"><label for="sslider_thumbnail"><?php _e('Custom Thumbnail Image(url)','smooth-slider'); ?></label></th>
                	<td><input type="text" name="sslider_thumbnail" class="sslider_thumbnail" value="<?php echo $sslider_thumbnail;?>" size="50" />
                </td></tr>

		<tr valign="top">
		 <th scope="row"><label for="sslider_expiry"><?php _e('Expiry Date','smooth-slider'); ?></label></th>
                	<td><input type="text" name="sslider_expiry" id="smooth_ExpiryDate" class="sslider_expiry" value="<?php echo $sslider_expiry;?>" size="20" />
                </td></tr>

		<tr valign="top">
		<th scope="row"><label for="disable_image"><?php _e('Disable Thumbnail Image','smooth-slider'); ?> </label></th>
		<td><input type="checkbox" name="disable_image" value="1" <?php if($sslider_disable_image=='1'){echo "checked";}?>  /> </td>
		</tr>
              
                
		<!-- Added for video - Start -->
		<tr valign="top">
		<th scope="row"><label for="embed_shortcode"><?php _e('Embed Shortcode','smooth-slider'); ?> </label><br><br><div style="font-weight:normal;border:1px dashed #ccc;padding:5px;color:#666;line-height:20px;font-size:13px;">You can embed any type of shortcode e.g video shortcode or button shortcode which you want to be overlaid on the slide.</div></th>
		<td><textarea rows="4" cols="50" name="smooth_sslider_eshortcode"><?php echo htmlentities( $smooth_embed_shortcode, ENT_QUOTES);?></textarea></td>
		</tr>
		</table>
		<!-- Added for video - End -->

                 </div>
    </div>
        
<?php }
}

//CSS for the checkbox on the admin page
function slider_checkbox_css() {
?><style type="text/css" media="screen">#slider_checkbox{margin: 5px 0 10px 0;padding:3px;font-weight:bold;}#slider_checkbox input,#slider_checkbox select{font-weight:bold;}#slider_checkbox label,#slider_checkbox input,#slider_checkbox select{vertical-align:top;}</style>
<?php
}

add_action('publish_post', 'add_to_slider');
add_action('publish_page', 'add_to_slider');
add_action('edit_post', 'add_to_slider');
add_action('publish_post', 'remove_from_slider');
add_action('edit_post', 'remove_from_slider');
add_action('deleted_post','delete_from_slider_table');

add_action('edit_attachment', 'add_to_slider');
add_action('delete_attachment','delete_from_slider_table');

function smooth_slider_plugin_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7.1
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}
function get_string_limit($output, $max_char)
{
    $output = str_replace(']]>', ']]&gt;', $output);
    $output = strip_tags($output);

  	if ((strlen($output)>$max_char) && ($espacio = strpos($output, " ", $max_char )))
	{
        $output = substr($output, 0, $espacio).'...';
		return $output;
   }
   else
   {
      return $output;
   }
}

function smooth_slider_get_first_image($post) {
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1] [0];
	return $first_img;
}
add_filter( 'plugin_action_links', 'sslider_plugin_action_links', 10, 2 );

function sslider_plugin_action_links( $links, $file ) {
	if ( $file != SMOOTH_SLIDER_PLUGIN_BASENAME )
		return $links;

	$url = sslider_admin_url( array( 'page' => 'smooth-slider-settings' ) );

	$settings_link = '<a href="' . esc_attr( $url ) . '">'
		. esc_html( __( 'Settings') ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}
require_once (dirname (__FILE__) . '/slider_versions/smooth_1.php');
require_once (dirname (__FILE__) . '/settings/settings.php');
require_once (dirname (__FILE__) . '/includes/media-images.php');
?>
