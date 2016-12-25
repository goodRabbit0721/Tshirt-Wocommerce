<?php // This function displays the page content for the Smooth Slider Options submenu
function smooth_slider_create_multiple_sliders() {
global $smooth_slider;
?>
<div class="wrap smooth_sliders_create" id="smooth_sliders_create" style="clear:both;">
<h2 style="float:left;"><?php _e('Sliders Created','smooth-slider'); ?></h2>
<?php 
if (isset($_POST['remove_posts_slider'])) {
   if (isset($_POST['slider_posts']) ) {
       global $wpdb, $table_prefix;
       $table_name = $table_prefix.SLIDER_TABLE;
	   $current_slider = isset($_POST['current_slider_id'])?$_POST['current_slider_id']:'';
	   $current_slider = intval($current_slider);
	   foreach ( $_POST['slider_posts'] as $post_id=>$val ) {
		$wpdb->query( 
			$wpdb->prepare( 
				"
				DELETE FROM $table_name
				 WHERE post_id = %d
				 AND slider_id = %d
				",
				$post_id, $current_slider
			)
		);
	   }
   }
 if (isset ($_POST['remove_all'])) {
   if ($_POST['remove_all'] == __('Remove All at Once','smooth-slider')) {
       global $wpdb, $table_prefix;
       $table_name = $table_prefix.SLIDER_TABLE;
	   $current_slider = isset($_POST['current_slider_id'])?$_POST['current_slider_id']:'';
	   $current_slider = intval($current_slider);
	   if(is_slider_on_slider_table($current_slider)) {
		   $wpdb->delete( $table_name, array( 'slider_id' => $current_slider ), array( '%d' ) );
	   }
   }
}
 if (isset ($_POST['remove_all'])) {
   if ($_POST['remove_all'] == __('Delete Slider','smooth-slider')) {
       	$slider_id = isset($_POST['current_slider_id'])?$_POST['current_slider_id']:'';
	$slider_id = intval($slider_id);
       
       global $wpdb, $table_prefix;
       $slider_table = $table_prefix.SLIDER_TABLE;
       $slider_meta = $table_prefix.SLIDER_META;
	   $slider_postmeta = $table_prefix.SLIDER_POST_META;
	   if(is_slider_on_slider_table($slider_id)) {
		   $wpdb->delete( $slider_table, array( 'slider_id' => $slider_id ), array( '%d' ) );
	   }
	   if(is_slider_on_meta_table($slider_id)) {
		   $wpdb->delete( $slider_meta, array( 'slider_id' => $slider_id ), array( '%d' ) );
	   }
	   if(is_slider_on_postmeta_table($slider_id)) {
		   $wpdb->delete( $slider_postmeta, array( 'slider_id' => $slider_id ), array( '%d' ) );
	   }
   }
}
}
if (isset($_POST['create_new_slider'])) {
   	$slider_name = $_POST['new_slider_name'];
   	global $wpdb,$table_prefix;
   	$slider_meta = $table_prefix.SLIDER_META;
      	$wpdb->query( 
		$wpdb->prepare( 
			"INSERT INTO $slider_meta
			(slider_name)
			VALUES ( %s )", 
			$slider_name
		) 
	);
}
if (isset($_POST['reorder_posts_slider'])) {
   $i=1;
   global $wpdb, $table_prefix;
   $table_name = $table_prefix.SLIDER_TABLE;
   foreach ($_POST['order'] as $slide_order) {
    	$slide_order = intval($slide_order);
 	$wpdb->update( 
		$table_name, 
		array( 
			'slide_order' => $i
		), 
		array( 'post_id' => $slide_order ), 
		array( 
			'%d'
		), 
		array( '%d' ) 
	);
    	$i++;
  }
}
/*Added for rename slider-2.6-start*/
if ((isset ($_POST['rename_slider'])) and ($_POST['rename_slider'] == __('Rename','smooth-slider'))) {
	$slider_name = $_POST['rename_slider_to'];
	$slider_id = isset($_POST['current_slider_id'])?$_POST['current_slider_id']:'';
	$slider_id = intval($slider_id);
	if( !empty($slider_name) ) {
		global $wpdb,$table_prefix;
		$slider_meta = $table_prefix.SLIDER_META;
		$wpdb->update( 
			$slider_meta, 
			array( 
				'slider_name' => $slider_name
			), 
			array( 'slider_id' => $slider_id ), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
	}
}
/*Added for rename slider-2.6-end*/

/* Added for upload media save-2.6 */
if ( isset($_POST['addSave']) and ($_POST['addSave']=='Save') ) {
	$images=(isset($_POST['imgID']))?$_POST['imgID']:array();
	$slider_id = isset($_POST['current_slider_id'])?$_POST['current_slider_id']:'';
	$slider_id = intval($slider_id);
	$ids=array_reverse($images);
	global $wpdb,$table_prefix;
	foreach($ids as $id){
		$title=(isset($_POST['title'][$id]))?$_POST['title'][$id]:'';
		$desc=(isset($_POST['desc'][$id]))?$_POST['desc'][$id]:'';
		$link=(isset($_POST['link'][$id]))?$_POST['link'][$id]:'';
		$nolink=(isset($_POST['nolink'][$id]))?$_POST['nolink'][$id]:'';
		$attachment = array(
			'ID'           => $id,
			'post_title'   => $title,
			'post_content' => $desc
		);
		wp_update_post( $attachment );
		update_post_meta($id, 'smooth_slide_redirect_url', $link);
		update_post_meta($id, 'smooth_sslider_nolink', $nolink);
		if(!slider($id,$slider_id)) {
				$dt = date('Y-m-d H:i:s');
				$table_name=$table_prefix.SLIDER_TABLE;
				$wpdb->query( 
					$wpdb->prepare( 
						"INSERT INTO $table_name
						(post_id, date, slider_id)
						VALUES ( %d, %s, %d )", 
						$id,
						$dt,
						$slider_id
					) 
				);
		}
	}
}
/*   upload media end 2.6 */
?>
<div style="clear:left"></div>
<?php $url = sslider_admin_url( array( 'page' => 'smooth-slider-settings' ) );?>
<a class="svorangebutton" href="<?php echo $url; ?>" title="<?php _e('Settings Page for Smooth Slider where you can change the color, font etc. for the sliders','smooth-slider'); ?>"><?php _e('Go to Smooth Slider Settings page','smooth-slider'); ?></a>
<?php $sliders = ss_get_sliders(); ?>
<div style="clear:right"></div>
<div id="slider_tabs">
        <ul class="ui-tabs">
        <?php foreach($sliders as $slider){?>
            <li class="yellow"><a href="#tabs-<?php echo $slider['slider_id'];?>"><?php echo $slider['slider_name'];?></a></li>
        <?php } ?>
        <?php if(isset($smooth_slider['multiple_sliders']) && $smooth_slider['multiple_sliders'] == '1') {?>
            <li class="green"><a href="#new_slider"><?php _e('Create New Slider','smooth-slider'); ?></a></li>
        <?php } ?>
        </ul>

<?php foreach($sliders as $slider){?>
<div id="tabs-<?php echo $slider['slider_id'];?>" style="width:56%;">
<strong>Quick Embed Shortcode:</strong>
<div class="admin_shortcode">
<pre style="padding: 10px 0;">[smoothslider id='<?php echo $slider['slider_id'];?>']</pre>
</div>
<!-- Add bulk images start 2.6-->
<?php 
if ( ! did_action( 'wp_enqueue_media' ) ) wp_enqueue_media();
wp_enqueue_script( 'media-uploader', smooth_slider_plugin_url( 'js/media-uploader.js' ),array( 'jquery', 'iris' ), SMOOTH_SLIDER_VER, false);
?>
	<h3 class="sub-heading" style="margin-left:0px;"><?php _e('Add Images to','smooth-slider'); ?> <?php echo $slider['slider_name'];?> (Slider ID = <?php echo $slider['slider_id'];?>)</h3>

	<div class="uploaded-images">
		<form method="post" class="addImgForm">
			<div style="clear:left;margin-top:20px;" class="image-uploader">
				<input type="submit" class="upload-button slider_images_upload" name="slider_images_upload" value="Upload" />
			</div>
			<input type="hidden" name="current_slider_id" value="<?php echo $slider['slider_id'];?>" />
			<input type="hidden" name="active_tab" class="smooth_activetab" value="0" />
		</form>
	</div>
<!-- Add bulk images end 2.6-->
<form action="" method="post">
<?php settings_fields('smooth-slider-group'); ?>

<input type="hidden" name="remove_posts_slider" value="1" />
<div id="tabs-<?php echo $slider['slider_id'];?>">
<h3><?php _e('Posts/Pages Added To','smooth-slider'); ?> <?php echo $slider['slider_name'];?><?php _e('(Slider ID','smooth-slider'); ?> = <?php echo $slider['slider_id'];?>)</h3>
<p><em><?php _e('Check the Post/Page and Press "Remove Selected" to remove them From','smooth-slider'); ?> <?php echo $slider['slider_name'];?>. <?php _e('Press "Remove All at Once" to remove all the posts from the','smooth-slider'); ?> <?php echo $slider['slider_name'];?>.</em></p>

    <table class="widefat">
    <thead class="blue"><tr><th><?php _e('Post/Page Title','smooth-slider'); ?></th><th><?php _e('Author','smooth-slider'); ?></th><th><?php _e('Post Date','smooth-slider'); ?></th><th><?php _e('Remove Post','smooth-slider'); ?></th></tr></thead><tbody>

<?php  
	/*global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;*/
	$slider_id = $slider['slider_id'];
	//$slider_posts = $wpdb->get_results("SELECT post_id FROM $table_name WHERE slider_id = '$slider_id'", OBJECT); 
    $slider_posts=get_slider_posts_in_order($slider_id); ?>
	
    <input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
    
<?php    $count = 0;	
	foreach($slider_posts as $slider_post) {
	  $slider_arr[] = $slider_post->post_id;
	  $post = get_post($slider_post->post_id);
		if(isset($post) and isset($slider_arr)){
		if (in_array($post->ID, $slider_arr) ) {
		  $count++;
		  $sslider_author = get_userdata($post->post_author);
          $sslider_author_dname = $sslider_author->display_name;
		  echo '<tr' . ($count % 2 ? ' class="alternate"' : '') . '><td><strong>' . $post->post_title . '</strong><a href="'.get_edit_post_link( $post->ID, $context = 'display' ).'" target="_blank"> '.__( '(Edit)', 'smooth-slider' ).'</a> <a href="'.get_permalink( $post->ID ).'" target="_blank"> '.__( '(View)', 'smooth-slider' ).' </a></td><td>By ' . $sslider_author_dname . '</td><td>' . date('l, F j. Y',strtotime($post->post_date)) . '</td><td><input type="checkbox" name="slider_posts[' . $post->ID . ']" value="1" /></td></tr>'; 
		  }
		}
	}
		
	if ($count == 0) {
		echo '<tr><td colspan="4">'.__( 'No posts/pages have been added to the Slider - You can add respective post/page to slider on the Edit screen for that Post/Page', 'smooth-slider' ).'</td></tr>';
	}
	echo '</tbody><tfoot class="blue"><tr><th>'.__( 'Post/Page Title', 'smooth-slider' ).'</th><th>'.__( 'Author', 'smooth-slider' ).'</th><th>'.__( 'Post Date', 'smooth-slider' ).'</th><th>'.__( 'Remove Post', 'smooth-slider' ).'</th></tr></tfoot></table>'; 
    
	echo '<div class="submit">';
	
	if ($count) {echo '<input type="submit" value="'.__( 'Remove Selected', 'smooth-slider' ).'" onclick="return confirmRemove()" /><input type="submit" name="remove_all" value="'.__( 'Remove All at Once', 'smooth-slider' ).'" onclick="return confirmRemoveAll()" />';}
	
	if($slider_id != '1') {
	   echo '<input type="submit" value="'.__( 'Delete Slider', 'smooth-slider' ).'" name="remove_all" onclick="return confirmSliderDelete()" />';
	}
	
	echo '</div>';
?>    
    </tbody></table>
	<input type="hidden" name="active_tab" class="smooth_activetab" value="0" />
 </form>
 
 
 <form action="" method="post">
    <input type="hidden" name="reorder_posts_slider" value="1" />
    <h3 class="sub-heading" style="margin-left:0px;"><?php _e('Reorder the Posts/Pages Added To','smooth-slider'); ?> <?php echo $slider['slider_name'];?>(Slider ID = <?php echo $slider['slider_id'];?>)</h3>
    <p><em><?php _e('Click on and drag the post/page title to a new spot within the list, and the other items will adjust to fit.','smooth-slider'); ?> </em></p>
    <ul id="sslider_sortable_<?php echo $slider['slider_id'];?>" style="color:#326078;overflow: auto;">    
    <?php  
    	$slider_id = $slider['slider_id'];
	$slider_posts=get_slider_posts_in_order($slider_id);?>
        
        <input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
        
    <?php    $count = 0;	
        foreach($slider_posts as $slider_post) {
          $slider_arr[] = $slider_post->post_id;
          $post = get_post($slider_post->post_id);
	if(isset($post) and isset($slider_arr)){	  
          if ( in_array($post->ID, $slider_arr) ) {
              $count++;
              $sslider_author = get_userdata($post->post_author);
              $sslider_author_dname = $sslider_author->display_name;
              echo '<li id="'.$post->ID.'" class="reorder"><input type="hidden" name="order[]" value="'.$post->ID.'" /><strong> &raquo; &nbsp; ' . $post->post_title . '</strong></li>'; 
          		}
        		}
	}
            
        if ($count == 0) {
            echo '<li>'.__( 'No posts/pages have been added to the Slider - You can add respective post/page to slider on the Edit screen for that Post/Page', 'smooth-slider' ).'</li>';
        }
		        
        echo '</ul><div class="submit">';
        
        if ($count) {echo '<input type="submit" value="Save the order"  />';}
                
        echo '</div>';
    ?>    
       </div>   
	<input type="hidden" name="active_tab" class="smooth_activetab" value="0" />    
  </form>
<!-- Added for rename slider -start -->
	 <h3 class="sub-heading" style="margin:40px 0px 5px 0;"><?php _e('Rename Slider','smooth-slider'); ?></h3>
	<form action="" method="post"> 
		<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php _e('Rename Slider to','smooth-slider'); ?></th>
			<td><input type="text" name="rename_slider_to" class="regular-text" value="<?php echo $slider['slider_name'];?>" /></td>
			</tr>
		</table>
		<input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
		<input type="submit" value="<?php _e('Rename','smooth-slider'); ?>"  name="<?php _e('rename_slider','smooth-slider'); ?>" />
	
		<input type="hidden" name="active_tab" class="smooth_activetab" value="0" />
                <input type="hidden" name="smooth_slider_options[reviewme]" id="smooth_reviewme" value="<?php echo $smooth_slider['reviewme']; ?>" /> 
	
	</form>


<?php 
	$now=strtotime("now");
	$reviewme=$smooth_slider['reviewme'];
        if($reviewme!=0 and $reviewme<$now) {
		echo "<div id='reviewme' style='border:1px solid #ccc;padding:10px;background:#fff;margin-top:2%;float: left;width: 95%;'>
		<p>".__('Hey, I noticed you have created an awesome slider using Smooth Slider and using it for more than a week. Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', 'smooth-slider')."</p>
		<p>".__("~ Tejaswini from SliderVilla","smooth-slider")."</p>
			<ul><li><a href='https://wordpress.org/support/view/plugin-reviews/smooth-slider?filter=5' target='_blank' title='".__('Please review and rate Smooth Slider on WordPress.org', 'smooth-slider')."'>".__('Ok, you deserve it', 'smooth-slider')."</a></li>
			<li><a id='later' href='#' title='".__('Rate Smooth Slider at some other time!', 'smooth-slider')."'>".__('Nope, maybe later', 'smooth-slider')."</a></li>
			<li><a id='already' href='#' title='".__('Click this if you have already rated us 5-star!', 'smooth-slider')."'>".__('I already did', 'smooth-slider'). "</a></li></ul></div>";
   }
?>

<!-- Added for rename slider -end -->	
</div> 
 
<?php } ?>



<?php if(isset($smooth_slider['multiple_sliders']) && $smooth_slider['multiple_sliders'] == '1') {?>
    <div id="new_slider" style="width:56%;">
    <form action="" method="post" onsubmit="return slider_checkform(this);" >
    <h3><?php _e('Enter New Slider Name','smooth-slider'); ?></h3>
    <input type="hidden" name="create_new_slider" value="1" />
    
    <input name="new_slider_name" class="regular-text code" value="" style="clear:both;" />
    
    <div class="submit"><input type="submit" value="<?php _e('Create New','smooth-slider'); ?>" name="create_new" /></div>
    
    </form>
    </div>
<?php }?> 

</div>


<div id="poststuff" class="metabox-holder has-right-sidebar" style="float:left;width:25%;max-width:300px;min-width: 255px;margin-top:20px;"> 
		
		<div class="postbox"> 
		<h3 class="hndle"><span><?php _e('About this Plugin:','smooth-slider'); ?></span></h3> 
		<div class="inside">
                <ul>
                <li><a href="http://slidervilla.com/smooth-slider" title="<?php _e('Smooth Slider Homepage','smooth-slider'); ?>" ><?php _e('Plugin Homepage','smooth-slider'); ?></a></li>
                <li><a href="http://wordpress.org/support/plugin/smooth-slider" title="<?php _e('Support Forum for Smooth Slider','smooth-slider'); ?>
" ><?php _e('Support Forum','smooth-slider'); ?></a></li>
                <li><a href="http://slidervilla.com/about-us/" title="<?php _e('Smooth Slider Author Page','smooth-slider'); ?>" ><?php _e('About the Author','smooth-slider'); ?></a></li>
		<li><a href="http://www.clickonf5.org/go/smooth-slider/" title="<?php _e('Donate if you liked the plugin and support in enhancing Smooth Slider and creating new plugins','smooth-slider'); ?>" ><?php _e('Donate with Paypal','smooth-slider'); ?></a></li>
		<li><strong>Current Version: <?php echo SMOOTH_SLIDER_VER;?></strong></li>
                </ul> 
            	</div> 
		</div>
                      
		<div class="postbox" style="margin:10px 0;"> 
				
     		  <div class="inside">
				<div style="margin:10px auto;">
							<a href="http://slidervilla.com" title="Premium WordPress Slider Plugins" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/banner-premium.png');?>" alt="Premium WordPress Slider Plugins" width="100%" /></a>
				</div>
				</div></div>
     
     <div style="clear:left;"></div>
 </div> <!--end of poststuff --> 


</div> <!--end of float wrap -->
<?php	
}
?>
