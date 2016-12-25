<?php 
function smooth_post_processor_default( $posts, $smooth_slider,$out_echo){
	$skin='default';
	global $smooth_slider;
	$smooth_slider_css = smooth_get_inline_css();
	$html = '';
	$smooth_sldr_j = 0;
	
	foreach($posts as $post) {
		$post_id = $post->ID;
		$post_title = stripslashes($post->post_title);
		$post_title = str_replace('"', '', $post_title);
		//filter hook
		$post_title=apply_filters('smooth_post_title',$post_title,$post_id,$smooth_slider,$smooth_slider_css);
		$slider_content = $post->post_content;	
//2.1 changes start
		$slide_redirect_url = get_post_meta($post_id, 'slide_redirect_url', true);
		$sslider_nolink = get_post_meta($post_id,'sslider_nolink',true);
		trim($slide_redirect_url);
		if(!empty($slide_redirect_url) and isset($slide_redirect_url)) {
		   $permalink = $slide_redirect_url;
		}
		else{
		   $permalink = get_permalink($post_id);
		}
		if($sslider_nolink=='1'){
		  $permalink='';
		}
		
		//filter hook
		$permalink=apply_filters('smooth_permalink',$permalink,$post_id,$smooth_slider,$smooth_slider_css);
//2.1 changes end	
	   	$smooth_sldr_j++;
		$html .= '<div class="smooth_slideri" '.$smooth_slider_css['smooth_slideri'].'>
			<!-- smooth_slideri -->';
		if($permalink!='') {
			$html .= '<h2 '.$smooth_slider_css['smooth_slider_h2'].'><a '.$smooth_slider_css['smooth_slider_h2_a'].' href="'.$permalink.'">'.$post_title.'</a></h2><p '.$smooth_slider_css['smooth_slider_span'].'> '.$slider_excerpt.'</p>';
		}else{
			$html .= '<h2 id="banner'.-$post_id.'" class="banner_hero_title hiw-step" '.$smooth_slider_css['smooth_slider_h2'].'>'.$post_title.'</h2>';
		}	
		$thumbnail = get_post_meta($post_id, $smooth_slider['img_pick'][1], true);
		//$image_control = get_post_meta($post_id, 'slider_image_control', true);
		
		if ($smooth_slider['content_from'] == "slider_content") {
		    $slider_content = get_post_meta($post_id, 'slider_content', true);
		}
		if ($smooth_slider['content_from'] == "excerpt") {
		    $slider_content = $post->post_excerpt;
		}
		
		$slider_content = strip_shortcodes( $slider_content );
		
		$slider_content = stripslashes($slider_content);
		$slider_content = str_replace(']]>', ']]&gt;', $slider_content);

		$slider_content = str_replace("\n","<br />",$slider_content);
        $slider_content = strip_tags($slider_content, $smooth_slider['allowable_tags']);
		
		//filter hook
		$slider_content=apply_filters('smooth_slide_excerpt',$slider_content,$post_id,$smooth_slider,$smooth_slider_css);
		if(!isset($smooth_slider['img_pick'][0])) $smooth_slider['img_pick'][0]='';
		if(!isset($smooth_slider['img_pick'][2])) $smooth_slider['img_pick'][2]='';
		if(!isset($smooth_slider['img_pick'][3])) $smooth_slider['img_pick'][3]='';
		if(!isset($smooth_slider['img_pick'][5])) $smooth_slider['img_pick'][5]='';		
					
		if($smooth_slider['img_pick'][0] == '1'){
		 $custom_key = array($smooth_slider['img_pick'][1]);
		}
		else {
		 $custom_key = '';
		}
		
		if($smooth_slider['img_pick'][2] == '1'){
		 $the_post_thumbnail = true;
		}
		else {
		 $the_post_thumbnail = false;
		}
		
		if($smooth_slider['img_pick'][3] == '1'){
		 $attachment = true;
		 $order_of_image = $smooth_slider['img_pick'][4];
		}
		else{
		 $attachment = false;
		 $order_of_image = '1';
		}
		
		if($smooth_slider['img_pick'][5] == '1'){
			 $image_scan = true;
		}
		else {
			 $image_scan = false;
		}
		
		if($smooth_slider['img_size'] == '1'){
		 $gti_width = $smooth_slider['img_width'];
		}
		else {
		 $gti_width = false;
		}
		
		if($smooth_slider['crop'] == '0'){
		 $extract_size = 'full';
		}
		elseif($smooth_slider['crop'] == '1'){
		 $extract_size = 'large';
		}
		elseif($smooth_slider['crop'] == '2'){
		 $extract_size = 'medium';
		}
		else{
		 $extract_size = 'thumbnail';
		}
		
		$img_args = array(
			'custom_key' => $custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => $extract_size,
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => false,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'smooth_slider_thumbnail',
			'image_scan' => $image_scan,
			'width' => $gti_width,
			'height' => false,
			'echo' => false,
			'permalink' => $permalink,
			'style'=> $smooth_slider_css['smooth_slider_thumbnail']
		);
		$smooth_slide_image=smooth_sslider_get_the_image($img_args);
		//filter hook
		$smooth_slide_image=apply_filters('smooth_slide_image',$smooth_slide_image,$post_id,$smooth_slider,$smooth_slider_css);
		
		$thumbnail_image=get_post_meta($post_id, '_disable_image', true);
		if($thumbnail_image!='1')
			$html .=  $smooth_slide_image;
		
		/* Added for embeding any shortcode on slide - start */
		$smooth_eshortcode=get_post_meta($post_id, '_smooth_embed_shortcode', true);
		if(!empty($smooth_eshortcode)){
			$shortcode_html=do_shortcode($smooth_eshortcode);
			$html.='<div class="smooth_slider_thumbnail"><div class="smooth_slider_eshortcode" '.$smooth_slider_css['smooth_slider_eshortcode'].'>'.$shortcode_html.'</div></div>';
		}	
		/* Added for embeding any shortcode on slide - end */

			$content_limit=$smooth_slider['content_limit'];
			$content_chars=$smooth_slider['content_chars'];
			if(empty($content_limit) && !empty($content_chars)){ 
				$slider_excerpt = substr($slider_content,0,$content_chars);
			}
			else{ 
				$slider_excerpt = smooth_slider_word_limiter( $slider_content, $limit = $content_limit);
			}
			if(!isset($slider_excerpt))$slider_excerpt='';
		  		
		if ($smooth_slider['image_only'] == '1') { 
			$html .= '<!-- /smooth_slideri -->
			</div>';
		}
		else {
		   if($permalink!='') {
			$html .= '<p class="smooth_more"><a href="'.$permalink.'" '.$smooth_slider_css['smooth_slider_p_more'].'>'.$smooth_slider['more'].'</a></p>
			
				<!-- /smooth_slideri -->
			</div>'; 
		   }
		   else{
		   $html .= '<p class="banner_hero_text hiw-title" '.$smooth_slider_css['smooth_slider_span'].'> '.$slider_excerpt.'</p>
				<!-- /smooth_slideri -->
			</div>';    
		   }
	      }
	}
	if($out_echo == '1') {
	   echo $html;
	}
	$r_array = array( $smooth_sldr_j, $html);
	$r_array=apply_filters('smooth_r_array',$r_array,$posts, $smooth_slider);
	return $r_array;
	
}
function smooth_slider_get_default($slider_handle,$r_array,$slider_id='',$echo='1') {
	$skin='default';
	global $smooth_slider,$default_slider; 
	foreach($default_slider as $key=>$value){
		if(!isset($smooth_slider[$key])) $smooth_slider[$key]='';
	}
	$smooth_sldr_j = $r_array[0];
	$smooth_slider_css = smooth_get_inline_css();
	$html='';
	if(isset($smooth_sldr_j) && $smooth_sldr_j >= 1) : //is slider empty?
	wp_enqueue_script( 'smooth', smooth_slider_plugin_url( 'js/smooth.js' ),array('jquery'), SMOOTH_SLIDER_VER, false);
	wp_enqueue_script( 'smooth-dim', smooth_slider_plugin_url( 'js/dim.js' ),array('jquery'), SMOOTH_SLIDER_VER, false);
	wp_enqueue_script( 'jquery.touchwipe', smooth_slider_plugin_url( 'js/jquery.touchwipe.js' ),array('jquery'), SMOOTH_SLIDER_VER, false);
/* Changed fouc code start 2.6 - Bug fix in 2.6.2.1 */	
	if(!isset($smooth_slider['fouc']) or $smooth_slider['fouc']=='' or $smooth_slider['fouc']=='0' ){
			$fouc_dom='jQuery("html").addClass("smooth_slider_fouc");jQuery(".smooth_slider_fouc .smooth_slider").hide();';
			$fouc_ready='jQuery(document).ready(function() {
		   		jQuery(".smooth_slider_fouc .smooth_slider").show();
			});';
		}	
		else{
			$fouc_dom=$fouc_ready='';
		}
/* Changed fouc code end 2.6 */		
	$html.='<script type="text/javascript">';
	$html.=$fouc_ready;
	$html.='jQuery(document).ready(function() {
		jQuery("#'.$slider_handle.'").smooth({ 
			fx: "'.$smooth_slider['fx'].'",
			speed:"'.$smooth_slider['transition'] * 100 .'",
			timeout: '. ( ($smooth_slider['autostep'] == '1') ? ( $smooth_slider['speed'] * 1000 ) :  0 ) .',';
		if ($smooth_slider['prev_next'] == 1){ 
			$html.='next:   "#'.$slider_handle.'_next", 
			prev:"#'.$slider_handle.'_prev",';
		} 
		
		if ($smooth_slider['goto_slide'] == "1" or $smooth_slider['goto_slide'] == "2" or $smooth_slider['goto_slide'] == "4"){ 
			$html.='pager: "#'.$slider_handle.'_nav",';
		} 
		
		if ($smooth_slider['goto_slide'] == 1) {
			$html.=' pagerAnchorBuilder: function(idx, slide) { 
					return \'<a class="sldr\'+(idx+1)+\' smooth_slider_nnav" href="#">\'+(idx+1)+\'</a>\'; 
				},'; 
		}
		if ($smooth_slider['goto_slide'] == 2) {
			$html.='pagerAnchorBuilder: function(idx, slide) { 
					return \'<a class="sldr\'+(idx+1)+\' smooth_slider_inav" style="background-image:url('.  smooth_slider_plugin_url( 'images/' ).'slide\'+(idx+1)+\'.png);background-position:0 0;width:'. $smooth_slider['navimg_w'].'px;height:'.$smooth_slider['navimg_ht'].'px;" href="#"></a>\'; 
				}, ';
		}	
		if ($smooth_slider['goto_slide'] == 4) {
			$html.='pagerAnchorBuilder: function(idx, slide) { 
					return \'<a class="sldr\'+(idx+1)+\' smooth_slider_inav smooth_slider_bnav" style="width:'. $smooth_slider['navimg_w'].'px;height:'.$smooth_slider['navimg_ht'].'px;" href="#"></a>\'; 
				}, ';
		}	

		$html.='pause: 1
			,slideExpr: "div.smooth_slideri"
		});
		jQuery("#'.$slider_handle.'").touchwipe({
			wipeLeft: function() {
				jQuery("#'.$slider_handle.'").smooth("next");
			},
			wipeRight: function() {
				jQuery("#'.$slider_handle.'").smooth("prev");
			},
			preventDefaultEvents: false
		});';
		if ($smooth_slider['goto_slide'] == 2 or $smooth_slider['goto_slide'] == 4 ) { 
			$html.='jQuery("head").append("<style type=\"text/css\">#'.$slider_handle.' .smooth_nav a.smooth_slider_inav.activeSlide{background-position:-'.$smooth_slider['navimg_w'].'px 0 !important;}</style>");';
		}	
		
		if(!empty($smooth_media_queries)){
		//	$html.='jQuery("head").append("<style type=\"text/css\">'. $smooth_media_queries .'</style>");';
		}
		if($smooth_slider['prev_next']==1) $navArr=1;
		else $navArr=0;
		$html.='jQuery("#'.$slider_handle.'").smoothSlider({
					sliderWidth		:'.$smooth_slider['width'].',
					sliderHeight		:'.$smooth_slider['height'].',
					navArr			:'.$navArr.',
					img_align		:"'.$smooth_slider['img_align'].'"
			});';
		
			
	$html.='});';
	//Action hook
	do_action('smooth_global_script',$slider_handle,$smooth_slider);
	$html.='</script><noscript><p><strong>'.$smooth_slider['noscript'].'</strong></p></noscript>';
	
	$html.='<div id="'.$slider_handle.'" class="smooth_slider" '.$smooth_slider_css['smooth_slider'].'>';
	//die('test '.$slider_id);
	if( $smooth_slider['title_from']=='1' and !empty($slider_id) ){ $sldr_title = get_smooth_slider_name($slider_id);}
	else {$sldr_title = $smooth_slider['title_text']; }
	if(!empty($sldr_title)) { 
		$html.='<div class="sldr_title" '.$smooth_slider_css['sldr_title'].'>'.$sldr_title.'</div> ';
	}
	
	$html.='<div class="smooth_sliderb">'.$r_array[1].'</div>';
	
	if ($smooth_slider['goto_slide'] == 1 or $smooth_slider['goto_slide'] == 2 or $smooth_slider['goto_slide'] == 4 ) { 
		$html.='<div id="'.$slider_handle.'_nav" class="smooth_nav"></div>';
	} 
	if ($smooth_slider['goto_slide'] == 3) { 	 
		$html.='<div id="'.$slider_handle.'_nav" class="smooth_nav">'.$smooth_slider['custom_nav'].'</div>';
	}
	if ($smooth_slider['prev_next'] == 1){
		$html.='<div id="'.$slider_handle.'_next" class="smooth_next"></div>
			<div id="'.$slider_handle.'_prev" class="smooth_prev"></div>';
	} 
	
	$html.='<div class="sldr_clearlt"></div><div class="sldr_clearrt"></div>
</div>';
	$html.='<script type="text/javascript">'.$fouc_dom.'</script>';
	if($echo == '1')  {echo $html; }
	else { return $html; }
	endif; //is slider empty?
}
function smooth_data_processor_default($slides, $smooth_slider,$out_echo){
  	$skin='default'; 
	global $smooth_slider,$data,$default_slider;
	$smooth_slider_css = smooth_get_inline_css();
	$html = '';
	$smooth_sldr_j = 0;
	$i=0;
	if(is_array($data)) extract($data,EXTR_PREFIX_ALL,'data');
	
  	foreach($default_slider as $key=>$value){
		        if(!isset($smooth_slider[$key])) $smooth_slider[$key]='';
	}
	$slider_handle='';
	if ( !empty($data_slider_handle) ) {
		$slider_handle=$data_slider_handle;
	}
	foreach($slides as $slide) {
		$id = $post_id = '';
		if (isset ($slide->ID)) {$id = $post_id = $slide->ID;}
		$post_title = stripslashes($slide->post_title);
		$post_title = str_replace('"', '', $post_title);
		//filter hook
		if (isset ($id)) $post_title=apply_filters('smooth_post_title',$post_title,$id,$smooth_slider,$smooth_slider_css);
		$slider_content = $slide->post_content;
		$smooth_slide_redirect_url = $slide->redirect_url;
		$smooth_sslider_nolink = $slide->nolink;
		trim($smooth_slide_redirect_url);
		if(!empty($smooth_slide_redirect_url) and isset($smooth_slide_redirect_url)) {
		   $permalink = $smooth_slide_redirect_url;
		}
		else{
		   $permalink = $slide->url;
		}
		if($smooth_sslider_nolink=='1'){
		  $permalink='';
		}
		
		$smooth_sldr_j++;
		$html .= '<div class="smooth_slideri" '.$smooth_slider_css['smooth_slideri'].'>
			<!-- smooth_slideri -->';
		if ($smooth_slider['content_from'] == "slider_content") {
			$slider_content = $slide->post_content;
		}
		if ($smooth_slider['content_from'] == "excerpt") {
			$slider_content = $slide->post_excerpt;
		}

		$slider_content = stripslashes($slider_content);
		$slider_content = str_replace(']]>', ']]&gt;', $slider_content);

		$slider_content = str_replace("\n","<br />",$slider_content);
		$slider_content = strip_tags($slider_content, $smooth_slider['allowable_tags']);
		
		$content_limit=$smooth_slider['content_limit'];
			$content_chars=$smooth_slider['content_chars'];
			if(empty($content_limit) && !empty($content_chars)){ 
				$slider_excerpt = substr($slider_content,0,$content_chars);
			}
			else{ 
				$slider_excerpt = smooth_slider_word_limiter( $slider_content, $limit = $content_limit);
			}
			if(!isset($slider_excerpt))$slider_excerpt='';
		  $slider_excerpt=apply_filters('smooth_slide_excerpt',$slider_excerpt,$post_id,$smooth_slider,$smooth_slider_css);
		  $slider_excerpt='<span '.$smooth_slider_css['smooth_slider_span'].'> '.$slider_excerpt.'</span>';
	
		//filter hook
		$slider_excerpt=apply_filters('smooth_slide_excerpt_html',$slider_excerpt,$post_id,$smooth_slider,$smooth_slider_css);
		
		//For media images
		if (isset ($slide->media)) $smooth_media = $slide->media;
		if (isset ($slide->media_image)) $smooth_media_image = $slide->media_image;
		$data_image_class=(!empty($data_image_class)?$data_image_class:'');
		$data_default_image=(!empty($data_default_image)?$data_default_image:'');	
		if( ((empty($smooth_media) or $smooth_media=='' or !($smooth_media)) and (empty($smooth_media_image) or $smooth_media_image=='' or !($smooth_media_image)) ) or $data_media!='1' ) {
			$width = $smooth_slider['img_width'];
			$height = $smooth_slider['img_height'];
			if($smooth_slider['crop'] == '0'){
			 $extract_size = 'full';
			}
			elseif($smooth_slider['crop'] == '1'){
			 $extract_size = 'large';
			}
			elseif($smooth_slider['crop'] == '2'){
			 $extract_size = 'medium';
			}
			else{
			 $extract_size = 'thumbnail';
			}
			
			$classes[] = $extract_size;
			$classes[] = 'smooth_slider_thumbnail';
			$classes[] = $data_image_class;
			$class = join( ' ', array_unique( $classes ) );
	
			preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $slide->content_for_image, $matches );
				
			$img_url=$data_default_image;
	
			/* If there is a match for the image, return its URL. */
			$order_of_image='';
			if(isset($data_order)) $order_of_image=$data_order;
			
			if($order_of_image > 0) $order_of_image=$order_of_image; 
			else $order_of_image = 0;
				
			if ( isset( $matches ) && count($matches[1])<=$order_of_image) $order_of_image=count($matches[1]);
			
			if ( isset( $matches ) && $matches[1][$order_of_image] )
				$img_url = $matches[1][$order_of_image];
			
			$width = ( ( $width ) ? ' width="' . esc_attr( $width ) . '"' : '' );
			$height = ( ( $height ) ? ' height="' . esc_attr( $height ) . '"' : '' );
			
			$img_html = '<img src="' . $img_url . '" class="' . esc_attr( $class ) . '"' . $width . $height . $smooth_slider_css['smooth_slider_thumbnail'] .' />';
			$smooth_large_image=$img_html;
		}
		else{
			$width = $smooth_slider['img_width'];
			$height = $smooth_slider['img_height'];
			$width = ( ( $width ) ? ' width="' . esc_attr( $width ) . '"' : '' );
			$height = ( ( $height ) ? ' height="' . esc_attr( $height ) . '"' : '' );
			
			if($smooth_slider['crop'] == '0'){
			 $extract_size = 'full';
			}
			elseif($smooth_slider['crop'] == '1'){
			 $extract_size = 'large';
			}
			elseif($smooth_slider['crop'] == '2'){
			 $extract_size = 'medium';
			}
			else{
			 $extract_size = 'thumbnail';
			}
			
			$classes[] = $extract_size;
			$classes[] = 'smooth_slider_thumbnail';
			$classes[] = $data_image_class;
			$class = join( ' ', array_unique( $classes ) );
			if(!empty($smooth_media_image)) {
				$smooth_large_image='<img src="'.$smooth_media_image.'" class="' . esc_attr( $class ) . '"' . $width . $height . '/>';
				$img_url=$smooth_media_image;
			}
			else {
				$smooth_large_image='<img src="'.$data_default_image.'" class="' . esc_attr( $class ) . '"' . $width . $height . '/>';
				$img_url=$data_default_image;
			}
		}
		
		if($permalink!='') {
		  $smooth_large_image = '<a href="' . $permalink . '" title="'.$post_title.'">' . $smooth_large_image . '</a>';
		}	
		//filter hook
		$smooth_large_image=apply_filters('smooth_large_image',$smooth_large_image,$post_id,$smooth_slider,$smooth_slider_css);
		$html.= $smooth_large_image;
		if ($smooth_slider['image_only'] == '1') { 
			$html .= '<!-- /smooth_slideri -->
			</div>';
		}
		else {
			if($permalink!='') {
			$html .= '<h2 '.$smooth_slider_css['smooth_slider_h2'].'><a '.$smooth_slider_css['smooth_slider_h2_a'].' href="'.$permalink.'">'.$post_title.'</a></h2><span '.$smooth_slider_css['smooth_slider_span'].'> '.$slider_excerpt.'</span>
				<p class="smooth_more"><a href="'.$permalink.'" '.$smooth_slider_css['smooth_slider_p_more'].'>'.$smooth_slider['more'].'</a></p>
				<!-- /smooth_slideri -->
			</div>'; 
			}
			else{
			$html .= '<h2 '.$smooth_slider_css['smooth_slider_h2'].'>'.$post_title.'</h2><span '.$smooth_slider_css['smooth_slider_span'].'> '.$slider_excerpt.'</span>
				<!-- /smooth_slideri -->
				</div>';    
			}
		}
	}
	if($out_echo == '1') {
	   echo $html;
	}
	$r_array = array( $smooth_sldr_j, $html);
	$r_array=apply_filters('smooth_r_array',$r_array,$slides, $smooth_slider);
	return $r_array;
}
?>
