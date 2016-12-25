<?php
//For media files
function smooth_slider_media_lib_edit($form_fields, $post){
global $wp_version;
if ( version_compare( $wp_version, '3.5', '<' ) ) : // Using WordPress less than 3.5
global $smooth_slider;
if (current_user_can( $smooth_slider['user_level'] )) {
    if ( substr($post->post_mime_type, 0, 5) == 'image') {
		$post_id = $post->ID;
		$extra = "";

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
		
			  
	  $form_fields['slider'] = array(
              'label'      => __('Check the box and select the slider','smooth-slider'),
              'input'      => 'html',
              'html'       => "<input type='checkbox' style='margin-top:6px;' name='attachments[".$post->ID."][slider]' value='slider' ".$extra." /> &nbsp; <strong>".__( 'Add this Image to ', 'smooth-slider' )."</strong>",
              'value'      => 'slider'
           );
	  
	  $sname_html='';
 
	  foreach ($sliders as $slider) { 
	    	 if(in_array($slider['slider_id'],$post_slider_arr)){$selected = 'selected';} else{$selected='';}
        	$sname_html =$sname_html.'<option value="'.$slider['slider_id'].'" '.$selected.'>'.$slider['slider_name'].'</option>';
      	   } 
	  $form_fields['slider_name[]'] = array(
              'label'      => __(''),
              'input'      => 'html',
              'html'       => '<select name="attachments['.$post->ID.'][slider_name][]" multiple="multiple" size="2" style="height:4em;">'.$sname_html.'</select>',
              'value'      => ''
           );
     
	 $sslider_link= get_post_meta($post_id, 'slide_redirect_url', true);  
	 $sslider_nolink=get_post_meta($post_id, 'sslider_nolink', true);
	 if($sslider_nolink=='1'){$checked= "checked";} else {$checked= "";}
	 $form_fields['sslider_link'] = array(
              'label'      => __('Slide Link URL','smooth-slider'),
              'input'      => 'html',
              'html'       => "<input type='text' style='clear:left;' class='text urlfield' name='attachments[".$post->ID."][sslider_link]' value='" . esc_attr($sslider_link) . "' /><br /><small>".__( '(If left empty, it will be by default linked to attachment permalink.)', 'smooth-slider' )."</small>",
              'value'      => $sslider_link
           );
     $form_fields['sslider_nolink'] = array(
              'label'      => __('Do not link this slide to any page(url)','smooth-slider'),
              'input'      => 'html',
              'html'       => "<input type='checkbox' name='attachments[".$post->ID."][sslider_nolink]' value='1' ".$checked." />",
              'value'      => 'slider'
           );
  }
  else {
     unset( $form_fields['slider'] );
	 unset( $form_fields['slider_name[]'] );
	 unset( $form_fields['sslider_link'] );
	 unset( $form_fields['sslider_nolink'] );
  }

  
}
endif; //less than WP 3.5
return $form_fields;
}

add_filter('attachment_fields_to_edit', 'smooth_slider_media_lib_edit', 10, 2);

function smooth_slider_media_lib_save($post, $attachment){
global $wp_version;
if ( version_compare( $wp_version, '3.5', '<' ) ) : // Using WordPress less than 3.5
global $smooth_slider;
if (current_user_can( $smooth_slider['user_level'] )) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;
	$post_id=$post['ID'];
	
	if(isset($attachment['slider']) and !isset($attachment['slider_name'])) {
	  $slider_id = '1';
	  if(is_post_on_any_slider($post_id)){
	    	 $wpdb->delete( $table_name, array( 'post_id' => $post_id ), array( '%d' ) );
	  }
	  
	  if(isset($attachment['slider']) and $attachment['slider'] == "slider" and !slider($post_id,$slider_id)) {
		$dt = date('Y-m-d H:i:s');
		$wpdb->query( 
			$wpdb->prepare( 
				"INSERT INTO $table_name
				(post_id, date, slider_id)
				VALUES ( %d, %s, %d )", 
				$post_id,
				$dt,
				$slider_id
			) 
		);
	  }
	}
	if(isset($attachment['slider']) and $attachment['slider'] == "slider" and isset($attachment['slider_name'])){
	  $slider_id_arr = $attachment['slider_name'];
	  $post_sliders_data = ss_get_post_sliders($post_id);
	  
	  foreach($post_sliders_data as $post_slider_data){
		if(!in_array($post_slider_data['slider_id'],$slider_id_arr)) {
			$wpdb->delete( $table_name, array( 'post_id' => $post_id ), array( '%d' ) );
		}
	  }
    
		foreach($slider_id_arr as $slider_id) {
			if(!slider($post_id,$slider_id)) {
				$dt = date('Y-m-d H:i:s');
				$wpdb->query( 
					$wpdb->prepare( 
						"INSERT INTO $table_name
						(post_id, date, slider_id)
						VALUES ( %d, %s, %d )", 
						$post_id,
						$dt,
						$slider_id
					) 
				);
			}
		}
	}
	
	$sslider_link = get_post_meta($post_id,'slide_redirect_url',true);
	$link=$attachment['sslider_link'];
	//if(!isset($link) or empty($link) ){$link=get_attachment_link($post_id);} //from 2.3.3
	if($sslider_link != $link and isset($link) and !empty($link)) {
	  update_post_meta($post_id, 'slide_redirect_url', $link);	
	}
	
	$sslider_nolink = get_post_meta($post_id,'sslider_nolink',true);
	if($sslider_nolink != $attachment['sslider_nolink']) {
	  update_post_meta($post_id, 'sslider_nolink', $attachment['sslider_nolink']);	
	}
}	
endif; //less than WP 3.5
return $post;		
} 

add_filter('attachment_fields_to_save', 'smooth_slider_media_lib_save', 10, 2);
?>
