<?php // Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'smooth_slider_settings');
  add_action( 'admin_init', 'register_mysettings' ); 
} 
// function for adding settings page to wp-admin
function smooth_slider_settings() {
	add_menu_page( 'Smooth Slider', 'Smooth Slider', 'manage_options','smooth-slider-admin', 'smooth_slider_create_multiple_sliders');
	add_submenu_page('smooth-slider-admin', 'Smooth Sliders', 'Sliders', 'manage_options', 'smooth-slider-admin', 'smooth_slider_create_multiple_sliders');
	add_submenu_page('smooth-slider-admin', 'Smooth Slider Settings', 'Settings', 'manage_options', 'smooth-slider-settings', 'smooth_slider_settings_page');
}
include('sliders.php');
// This function displays the page content for the Smooth Slider Options submenu
function smooth_process_set_requests(){
	global $smooth_slider;
//Export Settings
	if(isset($_POST['export'])){
		if ($_POST['export']=='Export') {
			@ob_end_clean();
			
			// required for IE, otherwise Content-Disposition may be ignored
			if(ini_get('zlib.output_compression'))
			ini_set('zlib.output_compression', 'Off');
			
			header('Content-Type: ' . "text/x-csv");
			header('Content-Disposition: attachment; filename="smooth-settings.csv"');
			header("Content-Transfer-Encoding: binary");
			header('Accept-Ranges: bytes');

			/* The three lines below basically make the
			download non-cacheable */
			header("Cache-control: private");
			header('Pragma: private');
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

			$exportTXT='';$i=0;
			$slider_options='smooth_slider_options';
			$slider_curr=get_option($slider_options);
			foreach($slider_curr as $key=>$value){
				if($i>0) $exportTXT.="\n";
				if(!is_array($value)){
					$exportTXT.=$key.",".$value;
				}
				else {
					$exportTXT.=$key.',';
					$j=0;
					if($value) {
						foreach($value as $v){
							if($j>0) $exportTXT.="|";
							$exportTXT.=$v;
							$j++;
						}
					}
				}
				$i++;
			}
			$exportTXT.="\n";
			$exportTXT.="slider_name,smooth";
			print($exportTXT); 
			exit();
		}
	}	
	
}
add_action('load-smooth-slider_page_smooth-slider-settings','smooth_process_set_requests');
function smooth_slider_settings_page() {
global $smooth_slider,$default_slider; 
//print_r($default_slider);
//die("test");

/* Skins settings File 2.6 */
$directory = SMOOTH_SLIDER_CSS_DIR;
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { 
	if($file != "sample")
     require_once ( dirname( dirname(__FILE__) ) . '/css/skins/'.$file.'/settings.php'); 
  
	} }
    closedir($handle);
}
//Reset Settings
if (isset ($_POST['smooth_reset_settings_submit'])) {
	if ( $_POST['smooth_reset_settings']!='n' ) {
		$smooth_reset_settings=$_POST['smooth_reset_settings'];
		$options='smooth_slider_options';
		$optionsvalue=get_option($options);
		if( $smooth_reset_settings == 'g' ){
			$new_settings_value=$default_slider;
			update_option($options,$new_settings_value);
		}
		elseif(!is_numeric($smooth_reset_settings)) {
			$skin=$smooth_reset_settings;
			$new_settings_value=$default_slider;
			$skin_defaults_str='default_settings_'.$skin;
			global ${$skin_defaults_str};
			if(count(${$skin_defaults_str})>0){
				foreach(${$skin_defaults_str} as $key=>$value){
					$new_settings_value[$key]=$value;	
				}
				$new_settings_value['stylesheet']=$skin;
			}	
			update_option($options,$new_settings_value);
		}
	}
}
$new_settings_msg='';
//Import Settings
if (isset ($_POST['import'])) {
	if ($_POST['import']=='Import') {
		$imported_settings_message='';
		$csv_mimetypes = array('text/csv','text/x-csv','text/plain','application/csv','text/comma-separated-values','application/excel','application/vnd.ms-excel','application/vnd.msexcel','text/anytext','application/octet-stream','application/txt');
		if ($_FILES['settings_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['settings_file']['tmp_name']) && in_array($_FILES['settings_file']['type'], $csv_mimetypes) ) { 
			$imported_settings=file_get_contents($_FILES['settings_file']['tmp_name']);
			$settings_arr=explode("\n",$imported_settings);
			$slider_settings=array();
			foreach($settings_arr as $settings_field){
				$s=explode(',',$settings_field);
				$inner=explode('|',$s[1]);
				if(count($inner)>1)	$slider_settings[$s[0]]=$inner;
				else $slider_settings[$s[0]]=$s[1];
			}
			$options='smooth_slider_options';
			
			if( $slider_settings['slider_name'] == 'smooth' )	{
				update_option($options,$slider_settings);
				$new_settings_msg='<div id="message" class="updated fade" style="clear:left;"><h3>'.__('Settings imported successfully ','smooth-slider').'</h3></div>';
				$imported_settings_message='<div style="clear:left;color:#006E2E;"><h3>'.__('Settings imported successfully ','smooth-slider').'</h3></div>';
			}
			else {
				$new_settings_msg=$imported_settings_message='<div id="message" class="error fade" style="clear:left;"><h3>'.__('Settings imported do not match to Smooth Slider Settings. Please check the file.','smooth-slider').'</h3></div>';
				$imported_settings_message='<div style="clear:left;color:#ff0000;"><h3>'.__('Settings imported do not match to Smooth Slider Settings. Please check the file.','smooth-slider').'</h3></div>';
			}
		}
		else{
			$new_settings_msg=$imported_settings_message='<div id="message" class="error fade" style="clear:left;"><h3>'.__('Error in File, Settings not imported. Please check the file being imported. ','smooth-slider').'</h3></div>';
			$imported_settings_message='<div style="clear:left;color:#ff0000;"><h3>'.__('Error in File, Settings not imported. Please check the file being imported. ','smooth-slider').'</h3></div>';
		}
	}
}
$smooth_slider=get_option('smooth_slider_options');
foreach($default_slider as $key=>$value){
	if(!isset($smooth_slider[$key])) $smooth_slider[$key]='';
}
?>
<div class="wrap" style="clear:both;">
<h2 class="top_heading"><?php _e('Smooth Slider Settings ','smooth-slider');?> </h2>
<div class="svilla_cl"></div>

<?php $url = sslider_admin_url( array( 'page' => 'smooth-slider-admin' ) );?>

<?php if ($smooth_slider['disable_preview'] != '1'){ ?>
<div id="settings_preview"><h2 class="heading"><?php _e('Preview','smooth-slider'); ?></h2> 
<?php 
if ($smooth_slider['preview'] == "0")
	get_smooth_slider($smooth_slider['slider_id']);
elseif($smooth_slider['preview'] == "1")
	get_smooth_slider_category($smooth_slider['catg_slug']);
else
	get_smooth_slider_recent();
?> 
</div>
<?php }?>
<?php echo $new_settings_msg;?>

<div id="smooth_settings">
<form method="post" action="options.php" id="smooth_slider_form">
<?php settings_fields('smooth-slider-group'); ?>

<div id="slider_tabs">
        <ul class="ui-tabs">
            <li class="green" style="font-weight:bold;font-size:12px;"><a href="#basic">Basic Settings</a></li>
            <li class="pink" style="font-weight:bold;font-size:12px;"><a href="#slides">Slides Panel</a></li>
	    <li class="yellow" style="font-weight:bold;font-size:12px;"><a href="#preview">Preview</a></li>
	    <li class="asbestos" style="font-weight:bold;font-size:12px;"><a href="#cssvalues">Generated CSS</a></li>
        </ul>

<div id="basic">
<div class="sub_settings toggle_settings">
<h2 class="sub-heading"><?php _e('Basic Controls','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"> </h2> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Select Skin','smooth-slider'); ?> </th>
<td><select name="smooth_slider_options[stylesheet]" id="smooth_slider_stylesheet" onchange="return checkskin(this.value);">
<?php 
$directory = SMOOTH_SLIDER_CSS_DIR;
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { ?>
      <option value="<?php echo $file;?>" <?php if ($smooth_slider['stylesheet'] == $file){ echo "selected";}?> ><?php echo $file;?></option>
 <?php  } }
    closedir($handle);
}
?>
</select>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="smooth_slider_autostep"><?php _e(' Enable autostepping of slides','smooth-slider'); ?></label></th>
<td> 
<input name="smooth_slider_options[autostep]" type="checkbox" id="smooth_slider_autostep" value="1" <?php checked("1", $smooth_slider['autostep']); ?> /> 
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Slide Transition Effect','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[fx]" >
<option value="scrollHorz" <?php if ($smooth_slider['fx'] == "scrollHorz"){ echo "selected";}?> ><?php _e('Scroll Horizontally','smooth-slider'); ?></option>
<option value="scrollVert" <?php if ($smooth_slider['fx'] == "scrollVert"){ echo "selected";}?> ><?php _e('Scroll Vertically','smooth-slider'); ?></option>
<option value="turnUp" <?php if ($smooth_slider['fx'] == "turnUp"){ echo "selected";}?> ><?php _e('Turn Up','smooth-slider'); ?></option>
<option value="turnDown" <?php if ($smooth_slider['fx'] == "turnDown"){ echo "selected";}?> ><?php _e('Turn Down','smooth-slider'); ?></option>
<option value="fade" <?php if ($smooth_slider['fx'] == "fade"){ echo "selected";}?> ><?php _e('Fade','smooth-slider'); ?></option>
<option value="uncover" <?php if ($smooth_slider['fx'] == "uncover"){ echo "selected";}?> ><?php _e('Uncover Slide','smooth-slider'); ?></option>
</select>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('Select the Transition Effect from six different effects.','smooth-slider'); ?> 
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Slide Pause Interval','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[speed]" id="smooth_slider_speed" class="small-text" value="<?php echo $smooth_slider['speed']; ?>" min="1" /> &nbsp;<?php _e('(in secs)','smooth-slider'); ?> 
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('Enter number of secs you want the slider to stop before sliding to next slide.','smooth-slider'); ?> 
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Slide Animation Length','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[transition]" id="smooth_slider_transition" class="small-text" value="<?php echo $smooth_slider['transition']; ?>" min="1" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('The duration of Slide Animation in milliseconds. Lower value indicates fast animation','smooth-slider'); ?> 
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Number of Posts in the Slideshow','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[no_posts]" id="smooth_slider_no_posts" class="small-text" value="<?php echo $smooth_slider['no_posts']; ?>" min="1" />
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Slide Background Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[bg_color]" id="smooth_slider_bg_color" value="<?php echo$smooth_slider['bg_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" /></div></div></br></br>
<label for="smooth_slider_bg"><input name="smooth_slider_options[bg]" type="checkbox" id="smooth_slider_bg" value="1" <?php checked('1', $smooth_slider['bg']); ?>  /><?php _e(' Use Transparent Background','smooth-slider'); ?></label> </td>
</tr>
 
<tr valign="top">
<th scope="row"><?php _e('Min. Slider Height','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[height]" id="smooth_slider_height" class="small-text" value="<?php echo $smooth_slider['height']; ?>" min="1" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>


<tr valign="top">
<th scope="row"><?php _e('Slider Width','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[width]" id="smooth_slider_width" class="small-text" value="<?php echo $smooth_slider['width']; ?>" min="1" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Border Thickness','smooth-slider'); ?></th>
<td><input type="number" min="0" name="smooth_slider_options[border]" id="smooth_slider_border" class="small-text" value="<?php echo $smooth_slider['border']; ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('Enter 0 if no border is required','smooth-slider'); ?> 
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Border Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[brcolor]" id="smooth_slider_brcolor" value="<?php echo $smooth_slider['brcolor']; ?>" class="wp-color-picker-field" data-default-color="#dddddd" /></td>
</tr>

<tr valign="top"> 
<th scope="row"><?php _e('Navigation Buttons','smooth-slider'); ?></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Navigation Buttons','smooth-slider'); ?></span></legend> 
<label for="smooth_slider_prev_next"> 
<input name="smooth_slider_options[prev_next]" type="checkbox" id="smooth_slider_prev_next" value="1" <?php checked("1", $smooth_slider['prev_next']); ?> /> 
 <?php _e('Show Prev/Next navigation arrows','smooth-slider'); ?></label><br /> 
<label for="smooth_slider_goto_slide"><?php _e('Show go to slide number links or images','smooth-slider'); ?></label><br />
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="0" <?php checked('0', $smooth_slider['goto_slide']); ?>  /> <?php _e('None ','smooth-slider'); ?><br /> 
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="1" <?php checked('1', $smooth_slider['goto_slide']); ?>  /> <?php _e('Numbers','smooth-slider'); ?> <br /> 
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="2" <?php checked('2', $smooth_slider['goto_slide']); ?>  /> <?php _e('Custom Images for Navigation','smooth-slider'); ?> <br /> <input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="4" <?php checked('4', $smooth_slider['goto_slide']); ?>  /> <?php _e('Fixed Images for Navigation','smooth-slider'); ?> <br /> &nbsp; &nbsp; &nbsp; <?php _e('Size: ','smooth-slider'); ?><input type="number" name="smooth_slider_options[navimg_w]" id="smooth_slider_navimg_w" class="small-text" value="<?php echo $smooth_slider['navimg_w']; ?>" min="1" />&nbsp;X&nbsp;<input type="number" name="smooth_slider_options[navimg_ht]" id="smooth_slider_navimg_ht" class="small-text" value="<?php echo $smooth_slider['navimg_ht']; ?>" min="1" /> <?php _e('px','smooth-slider'); ?><br /> 
<input name="smooth_slider_options[goto_slide]" type="radio" id="smooth_slider_goto_slide" value="3" <?php checked('3', $smooth_slider['goto_slide']); ?>  /> <?php _e('Enter Custom Text or HTML','smooth-slider'); ?>  
<input type="text" name="smooth_slider_options[custom_nav]" class="regular-text code" value="<?php echo htmlentities($smooth_slider['custom_nav'], ENT_QUOTES); ?>" />
</fieldset></td> 
</tr> 

</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m toggle_settings">
<h2 class="sub-heading"><?php _e('Miscellaneous','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Retain these html tags','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[allowable_tags]" class="regular-text code" value="<?php echo $smooth_slider['allowable_tags']; ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('Put the tags like &lt;br&gt;&lt;a&gt;&ltp&gt; to retain them.Do not separate them using commas, neither use ⁄ anywhere.','smooth-slider'); ?>
	</div>
</span>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Continue Reading Text','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[more]" class="regular-text code" value="<?php echo $smooth_slider['more']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Color of "Continue Reading Text"','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[readmorecolor]" id="smooth_slider_readmorecolor" value="<?php echo$smooth_slider['readmorecolor']; ?>" class="wp-color-picker-field" data-default-color="#3F4C6B" /></div></td>
</tr>
	

<tr valign="top">
<th scope="row"><?php _e('Minimum User Level to add Post to the Slider','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[user_level]" style="width:100%;">
<option value="manage_options" <?php if ($smooth_slider['user_level'] == "manage_options"){ echo "selected";}?> ><?php _e('Administrator','smooth-slider'); ?></option>
<option value="edit_others_posts" <?php if ($smooth_slider['user_level'] == "edit_others_posts"){ echo "selected";}?> ><?php _e('Editor and Admininstrator','smooth-slider'); ?></option>
<option value="publish_posts" <?php if ($smooth_slider['user_level'] == "publish_posts"){ echo "selected";}?> ><?php _e('Author, Editor and Admininstrator','smooth-slider'); ?></option>
<option value="edit_posts" <?php if ($smooth_slider['user_level'] == "edit_posts"){ echo "selected";}?> ><?php _e('Contributor, Author, Editor and Admininstrator','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Randomize Slides in Slider','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[rand]" type="checkbox" value="1" <?php checked('1', $smooth_slider['rand']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('check this if you want the slides added to appear in random order','smooth-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Text to display in the JavaScript disabled browser','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[noscript]" class="regular-text code" value="<?php echo $smooth_slider['noscript']; ?>" /></td>
</tr>

<tr valign="top" style="display:none;">
<th scope="row"><?php _e('Add Shortcode Support','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[shortcode]" type="checkbox" value="1" <?php checked('1', $smooth_slider['shortcode']); ?>  />&nbsp;<?php _e('check this if you want to use Smooth Slider Shortcode i.e [smoothslider]','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Multiple Slider Feature','smooth-slider'); ?></th>
<td><label for="smooth_slider_multiple"> 
<input name="smooth_slider_options[multiple_sliders]" type="checkbox" id="smooth_slider_multiple" value="1" <?php checked("1", $smooth_slider['multiple_sliders']); ?> /> 
 <?php _e('Enable Multiple Slider Function on Edit Post/Page','smooth-slider'); ?></label></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Enable FOUC','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[fouc]" type="checkbox" value="1" <?php checked('1', $smooth_slider['fouc']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('(check this if you would not want to disable Flash of Unstyled Content in the slider when the page is loaded)','smooth-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Custom Styles','smooth-slider'); ?></th>
<td><textarea name="smooth_slider_options[css]"  rows="5" class="regular-text code"><?php echo $smooth_slider['css']; ?></textarea>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('(custom css styles that you would want to be applied to the slider elements)','roster-slider'); ?>
	</div>
</span>
</td>
</tr>

</table>
</div>

</div><!--#basics-->

<div id="slides">
<div class="sub_settings toggle_settings">
<h2 class="sub-heading"><?php _e('Slider Title','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 
<p><?php _e('Customize the looks of the main title of the Slideshow from here','smooth-slider'); ?></p> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Default Title Text','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[title_text]" id="smooth_slider_title_text" value="<?php echo $smooth_slider['title_text']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Pick Slider Title From','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[title_from]" >
<option value="0" <?php if ($smooth_slider['title_from'] == "0"){ echo "selected";}?> ><?php _e('Default Title Text','smooth-slider'); ?></option>
<option value="1" <?php if ($smooth_slider['title_from'] == "1"){ echo "selected";}?> ><?php _e('Slider Name','smooth-slider'); ?></option>
</select>
</td>
</tr>

<!--
<tr valign="top">
<th scope="row"><?php _e('Font','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[title_font]" id="smooth_slider_title_font" >
<option value="Arial,Helvetica,sans-serif" <?php if ($smooth_slider['title_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
<option value="Verdana,Geneva,sans-serif" <?php if ($smooth_slider['title_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
<option value="Tahoma,Geneva,sans-serif" <?php if ($smooth_slider['title_font'] == "Tahoma,Geneva,sans-serif"){ echo "selected";}?> >Tahoma,Geneva,sans-serif</option>
<option value="Trebuchet MS,sans-serif" <?php if ($smooth_slider['title_font'] == "Trebuchet MS,sans-serif"){ echo "selected";}?> >Trebuchet MS,sans-serif</option>
<option value="'Century Gothic','Avant Garde',sans-serif" <?php if ($smooth_slider['title_font'] == "'Century Gothic','Avant Garde',sans-serif"){ echo "selected";}?> >'Century Gothic','Avant Garde',sans-serif</option>
<option value="'Arial Narrow',sans-serif" <?php if ($smooth_slider['title_font'] == "'Arial Narrow',sans-serif"){ echo "selected";}?> >'Arial Narrow',sans-serif</option>
<option value="'Arial Black',sans-serif" <?php if ($smooth_slider['title_font'] == "'Arial Black',sans-serif"){ echo "selected";}?> >'Arial Black',sans-serif</option>
<option value="'Gills Sans MT','Gills Sans',sans-serif" <?php if ($smooth_slider['title_font'] == "'Gills Sans MT','Gills Sans',sans-serif"){ echo "selected";} ?> >'Gills Sans MT','Gills Sans',sans-serif</option>
<option value="'Times New Roman',Times,serif" <?php if ($smooth_slider['title_font'] == "'Times New Roman',Times,serif"){ echo "selected";}?> >'Times New Roman',Times,serif</option>
<option value="Georgia,serif" <?php if ($smooth_slider['title_font'] == "Georgia,serif"){ echo "selected";}?> >Georgia,serif</option>
<option value="Garamond,serif" <?php if ($smooth_slider['title_font'] == "Garamond,serif"){ echo "selected";}?> >Garamond,serif</option>
<option value="'Century Schoolbook','New Century Schoolbook',serif" <?php if ($smooth_slider['title_font'] == "'Century Schoolbook','New Century Schoolbook',serif"){ echo "selected";}?> >'Century Schoolbook','New Century Schoolbook',serif</option>
<option value="'Bookman Old Style',Bookman,serif" <?php if ($smooth_slider['title_font'] == "'Bookman Old Style',Bookman,serif"){ echo "selected";}?> >'Bookman Old Style',Bookman,serif</option>
<option value="'Comic Sans MS',cursive" <?php if ($smooth_slider['title_font'] == "'Comic Sans MS',cursive"){ echo "selected";}?> >'Comic Sans MS',cursive</option>
<option value="'Courier New',Courier,monospace" <?php if ($smooth_slider['title_font'] == "'Courier New',Courier,monospace"){ echo "selected";}?> >'Courier New',Courier,monospace</option>
<option value="'Copperplate Gothic Bold',Copperplate,fantasy" <?php if ($smooth_slider['title_font'] == "'Copperplate Gothic Bold',Copperplate,fantasy"){ echo "selected";}?> >'Copperplate Gothic Bold',Copperplate,fantasy</option>
<option value="Impact,fantasy" <?php if ($smooth_slider['title_font'] == "Impact,fantasy"){ echo "selected";}?> >Impact,fantasy</option>
</select>
</td>
</tr>
-->

<tr valign="top">
<th scope="row"><?php _e('Font Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[title_fcolor]" id="smooth_slider_title_fcolor" value="<?php echo$smooth_slider['title_fcolor']; ?>" class="wp-color-picker-field" data-default-color="#000000" /></div></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Font Size','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[title_fsize]" id="smooth_slider_title_fsize" class="small-text" value="<?php echo $smooth_slider['title_fsize']; ?>" min="1" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[title_fstyle]" id="smooth_slider_title_fstyle" >
<option value="bold" <?php if ($smooth_slider['title_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','smooth-slider'); ?></option>
<option value="bold italic" <?php if ($smooth_slider['title_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','smooth-slider'); ?></option>
<option value="italic" <?php if ($smooth_slider['title_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','smooth-slider'); ?></option>
<option value="normal" <?php if ($smooth_slider['title_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','smooth-slider'); ?></option>
</select>
</td>
</tr>
</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m toggle_settings">
<h2 class="sub-heading"><?php _e('Post Title','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 
<p><?php _e('Customize the looks of the title of each of the sliding post here','smooth-slider'); ?></p> 
<table class="form-table">

<!--<tr valign="top">
<th scope="row"><?php _e('Font','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[ptitle_font]" id="smooth_slider_ptitle_font" >
<option value="Arial,Helvetica,sans-serif" <?php if ($smooth_slider['ptitle_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
<option value="Verdana,Geneva,sans-serif" <?php if ($smooth_slider['ptitle_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
<option value="Tahoma,Geneva,sans-serif" <?php if ($smooth_slider['ptitle_font'] == "Tahoma,Geneva,sans-serif"){ echo "selected";}?> >Tahoma,Geneva,sans-serif</option>
<option value="Trebuchet MS,sans-serif" <?php if ($smooth_slider['ptitle_font'] == "Trebuchet MS,sans-serif"){ echo "selected";}?> >Trebuchet MS,sans-serif</option>
<option value="'Century Gothic','Avant Garde',sans-serif" <?php if ($smooth_slider['ptitle_font'] == "'Century Gothic','Avant Garde',sans-serif"){ echo "selected";}?> >'Century Gothic','Avant Garde',sans-serif</option>
<option value="'Arial Narrow',sans-serif" <?php if ($smooth_slider['ptitle_font'] == "'Arial Narrow',sans-serif"){ echo "selected";}?> >'Arial Narrow',sans-serif</option>
<option value="'Arial Black',sans-serif" <?php if ($smooth_slider['ptitle_font'] == "'Arial Black',sans-serif"){ echo "selected";}?> >'Arial Black',sans-serif</option>
<option value="'Gills Sans MT','Gills Sans',sans-serif" <?php if ($smooth_slider['ptitle_font'] == "'Gills Sans MT','Gills Sans',sans-serif"){ echo "selected";} ?> >'Gills Sans MT','Gills Sans',sans-serif</option>
<option value="'Times New Roman',Times,serif" <?php if ($smooth_slider['ptitle_font'] == "'Times New Roman',Times,serif"){ echo "selected";}?> >'Times New Roman',Times,serif</option>
<option value="Georgia,serif" <?php if ($smooth_slider['ptitle_font'] == "Georgia,serif"){ echo "selected";}?> >Georgia,serif</option>
<option value="Garamond,serif" <?php if ($smooth_slider['ptitle_font'] == "Garamond,serif"){ echo "selected";}?> >Garamond,serif</option>
<option value="'Century Schoolbook','New Century Schoolbook',serif" <?php if ($smooth_slider['ptitle_font'] == "'Century Schoolbook','New Century Schoolbook',serif"){ echo "selected";}?> >'Century Schoolbook','New Century Schoolbook',serif</option>
<option value="'Bookman Old Style',Bookman,serif" <?php if ($smooth_slider['ptitle_font'] == "'Bookman Old Style',Bookman,serif"){ echo "selected";}?> >'Bookman Old Style',Bookman,serif</option>
<option value="'Comic Sans MS',cursive" <?php if ($smooth_slider['ptitle_font'] == "'Comic Sans MS',cursive"){ echo "selected";}?> >'Comic Sans MS',cursive</option>
<option value="'Courier New',Courier,monospace" <?php if ($smooth_slider['ptitle_font'] == "'Courier New',Courier,monospace"){ echo "selected";}?> >'Courier New',Courier,monospace</option>
<option value="'Copperplate Gothic Bold',Copperplate,fantasy" <?php if ($smooth_slider['ptitle_font'] == "'Copperplate Gothic Bold',Copperplate,fantasy"){ echo "selected";}?> >'Copperplate Gothic Bold',Copperplate,fantasy</option>
<option value="Impact,fantasy" <?php if ($smooth_slider['ptitle_font'] == "Impact,fantasy"){ echo "selected";}?> >Impact,fantasy</option>
</select>
</td>
</tr>-->

<tr valign="top">
<th scope="row"><?php _e('Font Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[ptitle_fcolor]" id="smooth_slider_ptitle_fcolor" value="<?php echo$smooth_slider['ptitle_fcolor']; ?>" class="wp-color-picker-field" data-default-color="#000000" /></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Size','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[ptitle_fsize]" id="smooth_slider_ptitle_fsize" class="small-text" value="<?php echo $smooth_slider['ptitle_fsize']; ?>" min="1" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[ptitle_fstyle]" id="smooth_slider_ptitle_fstyle" >
<option value="bold" <?php if ($smooth_slider['ptitle_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','smooth-slider'); ?></option>
<option value="bold italic" <?php if ($smooth_slider['ptitle_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','smooth-slider'); ?></option>
<option value="italic" <?php if ($smooth_slider['ptitle_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','smooth-slider'); ?></option>
<option value="normal" <?php if ($smooth_slider['ptitle_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','smooth-slider'); ?></option>
</select>
</td>
</tr>
</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m toggle_settings">
<h2 class="sub-heading"><?php _e('Thumbnail Image','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 
<p><?php _e('Customize the looks of the thumbnail image for each of the sliding post here','smooth-slider'); ?></p> 
<table class="form-table">

<tr valign="top"> 
<th scope="row"><?php _e('Image Pick Preferences','smooth-slider'); ?> <small><?php _e('(The first one is having priority over second, the second having priority on third and so on)','smooth-slider'); ?></small></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Image Pick Sequence','smooth-slider'); ?> <small><?php _e('(The first one is having priority over second, the second having priority on third and so on)','smooth-slider'); ?></small> </span></legend> 
<input name="smooth_slider_options[img_pick][0]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][0]); ?>  /> <?php _e('Use Custom Field/Key','smooth-slider'); ?> &nbsp; &nbsp; <br/> <br/>
<?php _e('Name of the Custom Field/Key','smooth-slider'); ?><input type="text" name="smooth_slider_options[img_pick][1]" class="text" value="<?php echo $smooth_slider['img_pick'][1]; ?>" /><br /> <br/>
<input name="smooth_slider_options[img_pick][2]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][2]); ?>  /> <?php _e('Use Featured Post/Thumbnail (Wordpress 3.0 +  feature)','smooth-slider'); ?>&nbsp; <br /> <br/>
<input name="smooth_slider_options[img_pick][3]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][3]); ?>  /> <?php _e('Consider Images attached to the post','smooth-slider'); ?> &nbsp; &nbsp; <br/><br/>
<?php _e('Order of the Image attachment to pick','smooth-slider'); ?><input type="text" name="smooth_slider_options[img_pick][4]" class="small-text" value="<?php echo $smooth_slider['img_pick'][4]; ?>" /> &nbsp; <br /><br/>
<input name="smooth_slider_options[img_pick][5]" type="checkbox" value="1" <?php checked('1', $smooth_slider['img_pick'][5]); ?>  /> <?php _e('Scan images from the post, in case there is no attached image to the post','smooth-slider'); ?>&nbsp; 
</fieldset></td> 
</tr> 

<tr valign="top">
<th scope="row"><?php _e('Align to','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[img_align]" id="smooth_slider_img_align" >
<option value="left" <?php if ($smooth_slider['img_align'] == "left"){ echo "selected";}?> ><?php _e('Left','smooth-slider'); ?></option>
<option value="right" <?php if ($smooth_slider['img_align'] == "right"){ echo "selected";}?> ><?php _e('Right','smooth-slider'); ?></option>
<option value="none" <?php if ($smooth_slider['img_align'] == "none"){ echo "selected";}?> ><?php _e('None','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Wordpress Image Extract Size','smooth-slider'); ?>
</th>
<td><select name="smooth_slider_options[crop]" id="smooth_slider_img_crop" >
<option value="0" <?php if ($smooth_slider['crop'] == "0"){ echo "selected";}?> ><?php _e('Full','smooth-slider'); ?></option>
<option value="1" <?php if ($smooth_slider['crop'] == "1"){ echo "selected";}?> ><?php _e('Large','smooth-slider'); ?></option>
<option value="2" <?php if ($smooth_slider['crop'] == "2"){ echo "selected";}?> ><?php _e('Medium','smooth-slider'); ?></option>
<option value="3" <?php if ($smooth_slider['crop'] == "3"){ echo "selected";}?> ><?php _e('Thumbnail','smooth-slider'); ?></option>
</select>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('This is for fast page load, in case you choose \'Custom Size\' setting from below, you would not like to extract \'full\' size image from the media library. In this case you can use, \'medium\' or \'thumbnail\' image. This is because, for every image upload to the media gallery WordPress creates four sizes of the same image. So you can choose which to load in the slider and then specify the actual size.','smooth-slider'); ?>
	</div>
</span>

</td>
</tr>


<tr valign="top"> 
<th scope="row"><?php _e('Image Size','smooth-slider'); ?></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Image Size','smooth-slider'); ?></span></legend> 
<input name="smooth_slider_options[img_size]" type="radio" value="0" <?php checked('0', $smooth_slider['img_size']); ?>  /> <?php _e('Original Size','smooth-slider'); ?> 
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('(In this case, the size would be equal to the extracted image (full/large/medium/thumbnail) from the above settings','smooth-slider'); ?> 
	</div>
</span>
<br />
<input name="smooth_slider_options[img_size]" type="radio" value="1" <?php checked('1', $smooth_slider['img_size']); ?>  /> <?php _e('Custom Size:','smooth-slider'); ?>&nbsp; 
<label for="smooth_slider_options[img_width]"><?php _e('Width','smooth-slider'); ?></label>
<input type="number" name="smooth_slider_options[img_width]" class="small-text" value="<?php echo $smooth_slider['img_width']; ?>" min="1" />&nbsp;<?php _e('px','smooth-slider'); ?> &nbsp;&nbsp; 
</fieldset></td> 
</tr> 

<tr valign="top">
<th scope="row"><?php _e('Maximum Height of the Image','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[img_height]" class="small-text" value="<?php echo $smooth_slider['img_height']; ?>" min="1" />&nbsp;<?php _e('px','smooth-slider'); ?>
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('(This is necessary in order to keep the maximum image height in control)','smooth-slider'); ?> 
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Border Thickness','smooth-slider'); ?></th>
<td><input type="number" min="0" name="smooth_slider_options[img_border]" id="smooth_slider_img_border" class="small-text" value="<?php echo $smooth_slider['img_border']; ?>" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('px  (put 0 if no border is required)','smooth-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Border Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[img_brcolor]" id="smooth_slider_img_brcolor" value="<?php echo$smooth_slider['img_brcolor']; ?>" class="wp-color-picker-field" data-default-color="#000000" /></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Make pure Image Slider','smooth-slider'); ?></th>
<td><input name="smooth_slider_options[image_only]" type="checkbox" value="1" <?php checked('1', $smooth_slider['image_only']); ?>  />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('(check this to convert Smooth Slider to Image Slider with no content)','smooth-slider'); ?> 
	</div>
</span>
</td>
</tr>
</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</div>

<div class="sub_settings_m toggle_settings">
<h2 class="sub-heading"><?php _e('Slider Content','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 
<p><?php _e('Customize the looks of the content of each of the sliding post here','smooth-slider'); ?></p> 
<table class="form-table">
<!--
<tr valign="top">
<th scope="row"><?php _e('Font','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[content_font]" id="smooth_slider_content_font" >
<option value="Arial,Helvetica,sans-serif" <?php if ($smooth_slider['content_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
<option value="Verdana,Geneva,sans-serif" <?php if ($smooth_slider['content_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
<option value="Tahoma,Geneva,sans-serif" <?php if ($smooth_slider['content_font'] == "Tahoma,Geneva,sans-serif"){ echo "selected";}?> >Tahoma,Geneva,sans-serif</option>
<option value="Trebuchet MS,sans-serif" <?php if ($smooth_slider['content_font'] == "Trebuchet MS,sans-serif"){ echo "selected";}?> >Trebuchet MS,sans-serif</option>
<option value="'Century Gothic','Avant Garde',sans-serif" <?php if ($smooth_slider['content_font'] == "'Century Gothic','Avant Garde',sans-serif"){ echo "selected";}?> >'Century Gothic','Avant Garde',sans-serif</option>
<option value="'Arial Narrow',sans-serif" <?php if ($smooth_slider['content_font'] == "'Arial Narrow',sans-serif"){ echo "selected";}?> >'Arial Narrow',sans-serif</option>
<option value="'Arial Black',sans-serif" <?php if ($smooth_slider['content_font'] == "'Arial Black',sans-serif"){ echo "selected";}?> >'Arial Black',sans-serif</option>
<option value="'Gills Sans MT','Gills Sans',sans-serif" <?php if ($smooth_slider['content_font'] == "'Gills Sans MT','Gills Sans',sans-serif"){ echo "selected";} ?> >'Gills Sans MT','Gills Sans',sans-serif</option>
<option value="'Times New Roman',Times,serif" <?php if ($smooth_slider['content_font'] == "'Times New Roman',Times,serif"){ echo "selected";}?> >'Times New Roman',Times,serif</option>
<option value="Georgia,serif" <?php if ($smooth_slider['content_font'] == "Georgia,serif"){ echo "selected";}?> >Georgia,serif</option>
<option value="Garamond,serif" <?php if ($smooth_slider['content_font'] == "Garamond,serif"){ echo "selected";}?> >Garamond,serif</option>
<option value="'Century Schoolbook','New Century Schoolbook',serif" <?php if ($smooth_slider['content_font'] == "'Century Schoolbook','New Century Schoolbook',serif"){ echo "selected";}?> >'Century Schoolbook','New Century Schoolbook',serif</option>
<option value="'Bookman Old Style',Bookman,serif" <?php if ($smooth_slider['content_font'] == "'Bookman Old Style',Bookman,serif"){ echo "selected";}?> >'Bookman Old Style',Bookman,serif</option>
<option value="'Comic Sans MS',cursive" <?php if ($smooth_slider['content_font'] == "'Comic Sans MS',cursive"){ echo "selected";}?> >'Comic Sans MS',cursive</option>
<option value="'Courier New',Courier,monospace" <?php if ($smooth_slider['content_font'] == "'Courier New',Courier,monospace"){ echo "selected";}?> >'Courier New',Courier,monospace</option>
<option value="'Copperplate Gothic Bold',Copperplate,fantasy" <?php if ($smooth_slider['content_font'] == "'Copperplate Gothic Bold',Copperplate,fantasy"){ echo "selected";}?> >'Copperplate Gothic Bold',Copperplate,fantasy</option>
<option value="Impact,fantasy" <?php if ($smooth_slider['content_font'] == "Impact,fantasy"){ echo "selected";}?> >Impact,fantasy</option>
</select>
</td>
</tr>
-->

<tr valign="top">
<th scope="row"><?php _e('Font Color','smooth-slider'); ?></th>
<td><input type="text" name="smooth_slider_options[content_fcolor]" id="smooth_slider_content_fcolor" value="<?php echo$smooth_slider['content_fcolor']; ?>" class="wp-color-picker-field" data-default-color="#333333" /></div></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Size','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[content_fsize]" id="smooth_slider_content_fsize" class="small-text" value="<?php echo $smooth_slider['content_fsize']; ?>" min="1" />&nbsp;<?php _e('px','smooth-slider'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Font Style','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[content_fstyle]" id="smooth_slider_content_fstyle" >
<option value="bold" <?php if ($smooth_slider['content_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','smooth-slider'); ?></option>
<option value="bold italic" <?php if ($smooth_slider['content_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','smooth-slider'); ?></option>
<option value="italic" <?php if ($smooth_slider['content_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','smooth-slider'); ?></option>
<option value="normal" <?php if ($smooth_slider['content_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Pick content From','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[content_from]" id="smooth_slider_content_from" >
<option value="slider_content" <?php if ($smooth_slider['content_from'] == "slider_content"){ echo "selected";}?> ><?php _e('Slider Content Custom field','smooth-slider'); ?></option>
<option value="excerpt" <?php if ($smooth_slider['content_from'] == "excerpt"){ echo "selected";}?> ><?php _e('Post Excerpt','smooth-slider'); ?></option>
<option value="content" <?php if ($smooth_slider['content_from'] == "content"){ echo "selected";}?> ><?php _e('From Content','smooth-slider'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Maximum content size (in words)','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[content_limit]" id="smooth_slider_content_limit" class="small-text" value="<?php echo $smooth_slider['content_limit']; ?>" min="1" />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If the number of Words are not specified in this field, the below field i.e. the \'Maximum Content Size in Chracters\' will be considered.','smooth-slider'); ?> 
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Maximum content size (in characters)','smooth-slider'); ?></th>
<td><input type="number" name="smooth_slider_options[content_chars]" id="smooth_slider_content_chars" class="small-text" value="<?php echo $smooth_slider['content_chars']; ?>" min="1" />&nbsp;<?php _e('characters','smooth-slider'); ?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr>

</table>
</div>

</div><!--#slides-->

<div id="preview">
<div class="sub_settings toggle_settings">
<h2 class="sub-heading"><?php _e('Preview on Settings Panel','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 

<table class="form-table">

<tr valign="top"> 
<th scope="row"><label for="smooth_slider_disable_preview"><?php _e('Disable Preview Section','smooth-slider'); ?></label></th> 
<td> 
<input name="smooth_slider_options[disable_preview]" type="checkbox" id="smooth_slider_disable_preview" value="1" <?php checked("1", $smooth_slider['disable_preview']); ?> />
<span class="moreInfo">
	&nbsp; <span class="trigger"> ? </span>
	<div class="tooltip">
	<?php _e('If disabled, the \'Preview\' of Slider on this Settings page will be removed.','smooth-slider'); ?>
	</div>
</span>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Type of Smooth Slider','smooth-slider'); ?></th>
<td><select name="smooth_slider_options[preview]" id="smooth_slider_preview" id="smooth_slider_preview" onchange="checkpreview(this.value);">
<option value="2" <?php if ($smooth_slider['preview'] == "2"){ echo "selected";}?> ><?php _e('Recent Posts Slider','smooth-slider'); ?></option>
<option value="1" <?php if ($smooth_slider['preview'] == "1"){ echo "selected";}?> ><?php _e('Category Slider','smooth-slider'); ?></option>
<option value="0" <?php if ($smooth_slider['preview'] == "0"){ echo "selected";}?> ><?php _e('Custom Slider','smooth-slider'); ?></option>
</select>
</td>
</tr>
<?php  
 /* Added for category selection in Meta Box 2.6*/
//category slug
$categories = get_categories();
$scat_html='<option value="" selected >Select the Category</option>';

foreach ($categories as $category) { 
 if(urldecode($category->slug)==$smooth_slider['catg_slug']){$selected = 'selected';} else{$selected='';}
 $scat_html =$scat_html.'<option value="'.urldecode($category->slug).'" '.$selected.'>'. $category->name .'</option>';
} 
//fetching slider names 2.6
global $smooth_slider;
if($smooth_slider['multiple_sliders'] == '1') {	
			$slider_id = $smooth_slider['slider_id'];	
			$sliders = ss_get_sliders();
			$sname_html='<option value="0" selected >Select the Slider</option>';
	 		
		  foreach ($sliders as $slider) { 
			 if($slider['slider_id']==$slider_id){$selected = 'selected';} else{$selected='';}
			 $sname_html =$sname_html.'<option value="'.$slider['slider_id'].'" '.$selected.'>'.$slider['slider_name'].'</option>';
		  } 
}
?> 

<!-- Added for category selection in Meta Box 2.6-->
<tr valign="top" class="smooth_slider_params"> 
<th scope="row"><?php _e('Preview Slider Params','smooth-slider'); ?></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Preview Slider Params','smooth-slider'); ?></span></legend> 

<label for="smooth_slider_options[slider_id]" class="smooth_sid"><?php _e('Select Slider Name','smooth-slider'); ?></label>
<select id="smooth_slider_id" name="smooth_slider_options[slider_id]" class="smooth_sid"><?php echo $sname_html;?></select>

<label for="smooth_slider_options[catg_slug]" class="smooth_catslug"><?php _e('Select Category','smooth-slider'); ?></label>
<select id="smooth_slider_catslug" name="smooth_slider_options[catg_slug]" class="smooth_catslug"><?php echo $scat_html;?></select>
</fieldset></td> 
</tr> 


</table>
<p class="submit">
<input type="submit" class="button-primary" id="preview_save" value="<?php _e('Save Changes') ?>" />
</p>
</div>

<div class="sub_settings_m toggle_settings">
<h2 class="sub-heading"><?php _e('Shortcode','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 
<p><?php _e('Paste the below shortcode on Page/Post Edit Panel to get the slider as shown in the above Preview','smooth-slider'); ?></p>
<?php if ($smooth_slider['preview'] == "0") 
	$preview='[smoothslider id="'.$smooth_slider['slider_id'].'"]';
elseif($smooth_slider['preview'] == "1")
	$preview='[smoothcategory catg_slug="'.$smooth_slider['catg_slug'].'"]';
else
	$preview='[smoothrecent]';
echo "<p>".$preview."</p>";
?>
</div>

<div class="sub_settings_m toggle_settings">
<h2 class="sub-heading"><?php _e('Template Tag','smooth-slider'); ?><img src="<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>" class="toggle_img"></h2> 
<p><?php _e('Paste the below template tag in your theme template file like index.php or page.php at required location to get the slider as shown in the above Preview','smooth-slider'); ?></p><br />
<?php 
if ($smooth_slider['preview'] == "0")
	echo '<code>&lt;?php if(function_exists("get_smooth_slider")){get_smooth_slider($slider_id="'.$smooth_slider['slider_id'].'");}?&gt;</code>';
elseif($smooth_slider['preview'] == "1")
	echo '<code>&lt;?php if(function_exists("get_smooth_slider_category")){get_smooth_slider_category($catg_slug="'.$smooth_slider['catg_slug'].'");}?&gt;</code>';
else
	echo '<code>&lt;?php if(function_exists("get_smooth_slider_recent")){get_smooth_slider_recent();}?&gt;</code>';
?>
</div>

</div><!-- preview tab ends-->

<div id="cssvalues">
<div class="sub_settings">
<h2 class="sub-heading"><?php _e('CSS Generated thru these settings','thumbel-slider'); ?></h2> 
<p><?php _e('Save Changes for the settings first and then view this data. You can use this CSS in your \'custom\' stylesheets if you use other than \'default\' value for the Stylesheet folder.','thumbel-slider'); ?></p> 
<?php $smooth_slider_css = smooth_get_inline_css($echo='1'); ?>
<div style="font-family:monospace;font-size:13px;background:#ddd;">
.smooth_slider{<?php echo $smooth_slider_css['smooth_slider'];?>} <br />
.smooth_slider .sldr_title{<?php echo $smooth_slider_css['sldr_title'];?>} <br />
.smooth_slider .smooth_slideri{<?php echo $smooth_slider_css['smooth_slideri'];?>} <br />
.smooth_slider .smooth_slider_thumbnail{<?php echo $smooth_slider_css['smooth_slider_thumbnail'];?>} <br />
.smooth_slider .smooth_slideri h2{<?php echo $smooth_slider_css['smooth_slider_h2'];?>} <br />
.smooth_slider .smooth_slideri h2 a{<?php echo $smooth_slider_css['smooth_slider_h2_a'];?>} <br />
.smooth_slider .smooth_slideri span{<?php echo $smooth_slider_css['smooth_slider_span'];?>} <br />
.smooth_slider .smooth_slideri p.more{<?php echo $smooth_slider_css['smooth_slider_p_more'];?>} <br />
.smooth_slider .smooth_next{<?php echo $smooth_slider_css['smooth_next'];?>} <br />
.smooth_slider .smooth_prev{<?php echo $smooth_slider_css['smooth_prev'];?>} 
.smooth_slider .smooth_slider_eshortcode{<?php echo $smooth_slider_css['smooth_slider_eshortcode'];?>}
.smooth_slider .smooth_more a{<?php echo $smooth_slider_css['smooth_slider_p_more'];?>}
</div>
</div>
</div> <!--#cssvalues-->
<div class="svilla_cl"></div><div class="svilla_cr"></div>

</div> <!--end of #slider_tabs-->

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
<input type="hidden" name="smooth_slider_options[active_tab]" id="smooth_activetab" value="<?php echo $smooth_slider['active_tab']; ?>" />
<input type="hidden" name="smooth_slider_options[reviewme]" id="smooth_reviewme" value="<?php echo $smooth_slider['reviewme']; ?>" /> 
<input type="hidden" name="smooth_slider_options[popup]" id="smoothpopup" value="<?php echo $smooth_slider['popup']; ?>" />
<input type="hidden" name="hidden_preview" id="hidden_preview" value="<?php echo $smooth_slider['preview']; ?>" />
<input type="hidden" name="hidden_category" id="hidden_category" value="<?php echo $smooth_slider['catg_slug']; ?>" />
<input type="hidden" name="hidden_sliderid" id="hidden_sliderid" value="<?php echo $smooth_slider['slider_id']; ?>" />

</form>
<!-- Added for shortcode to show on save of settings-->
<div id="saveResult"></div>

<!--Form to reset Settings set-->
<form style="float:left;width:100%;" action="" method="post">
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Reset Settings to','smooth-slider'); ?></th>
<td><select name="smooth_reset_settings" id="smooth_slider_reset_settings" >
<option value="n" selected ><?php _e('None','smooth-slider'); ?></option>
<option value="g" ><?php _e('Global Default','smooth-slider'); ?></option>
<?php 
$directory = SMOOTH_SLIDER_CSS_DIR;
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { 
	if($file!="default" && $file!="sample")      
	{?>
      <option value="<?php echo $file;?>"><?php echo "'".$file."' skin";?></option>
 <?php } } }
    closedir($handle);
}
?>
</select>
</td>
</tr>
</table>

<p class="submit">
<input name="smooth_reset_settings_submit" type="submit" class="button-primary" value="<?php _e('Reset Settings') ?>" />
</p>
</form>

<div class="svilla_cl"></div>

<div style="border:1px solid #ccc;padding:10px;background:#fff;margin-top:2%;float: left;width: 95%;" id="import">
<?php if (isset ($imported_settings_message))echo $imported_settings_message;?>
<h3><?php _e('Import Settings Set by uploading a Settings File','smooth-slider'); ?></h3>
<form style="margin-right:10px;font-size:14px;" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
<input type="file" name="settings_file" id="settings_file" style="font-size:13px;width:50%;padding:0 5px;" />
<input type="submit" value="Import" name="import"  onclick="return confirmSettingsImport()" title="<?php _e('Import Settings from a file','smooth-slider'); ?>" class="button-primary" />
</form>
</div>

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



</div> <!--end of float left -->
<!-- Added for validations - start -->
<script type="text/javascript">
<?php 
/* To fetch Skin Specific attributes 2.6 */
$directory = SMOOTH_SLIDER_CSS_DIR;
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { 
	$default_settings_str='default_settings_'.$file;
	global ${$default_settings_str};
      	echo 'var '.$default_settings_str.' = '.json_encode(${$default_settings_str}).';';
 } }
    closedir($handle);
}
?>
/* To populate Skin Specific attributes 2.6 */
function checkskin(skin){ 
	var skin_array = window['default_settings_'+skin];       
	for (var key in skin_array) {
		var html_element='smooth_slider_'+key;
		document.getElementById(html_element).value = skin_array[key];
	}
	
}
jQuery(document).ready(function($) {
<?php if(isset($_GET['settings-updated'])) { if($_GET['settings-updated'] == 'true' and $smooth_slider['popup'] == '1' ) { 
?>
jQuery('#saveResult').html("<div id='popup'><div class='modal_shortcode'>Quick Embed Shortcode</div><span class='button b-close'><span>X</span></span></div>");
				jQuery('#popup').append('<div class="modal_preview"><?php echo $preview;?></div>');				
				jQuery('#popup').bPopup({
		    			opacity: 0.6,
					position: ['35%', '35%'],
		    			positionStyle: 'fixed', //'fixed' or 'absolute'			
					onClose: function() { return true; }
				});

<?php }} ?>

	/* jquery code moved to admin.js -2.6 */
	
/* Added for settings tab collapse and expand - 2.6 start */
	jQuery(this).find(".sub-heading").on("click", function(){
		var wrap=jQuery(this).parent('.toggle_settings'),
		tabcontent=wrap.find("p, table, code");
		tabcontent.toggle();
		var imgclass=wrap.find(".toggle_img");
		if (tabcontent.css('display') == 'none') {
			imgclass.attr("src", imgclass.attr("src").replace("<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>", "<?php echo smooth_slider_plugin_url( 'images/info.png' ); ?>"));
		} else {
			imgclass.attr("src", imgclass.attr("src").replace("<?php echo smooth_slider_plugin_url( 'images/info.png' ); ?>", "<?php echo smooth_slider_plugin_url( 'images/close.png' ); ?>"));
		}
	});
	/* Added for settings tab collapse and expand - 2.6 end */

		
	});
</script>
<!-- Added for validation - end -->
</div> <!--end of float wrap -->

<div id="poststuff" class="metabox-holder has-right-sidebar" style="float:left;width:100%;max-width:300px;min-width:inherit;"> 
        <div class="postbox" style="margin:0 0 10px 0;"> 
	<h3 class="hndle"><span></span><?php _e('Quick Embed Shortcode','smooth-slider'); ?></h3> 
	<div class="inside" id="shortcodeview">
	<?php 
	if ($smooth_slider['preview'] == "0")
		echo '[smoothslider id="'.$smooth_slider['slider_id'].'"]';
	elseif($smooth_slider['preview'] == "1")
		echo '[smoothcategory catg_slug="'.$smooth_slider['catg_slug'].'"]';
	else
		echo '[smoothrecent]';
	?>
</div></div>

<div class="postbox" style="margin:10px 0;"> 
	<h3 class="hndle"><span></span><?php _e('Quick Embed Template Tag','smooth-slider'); ?></h3> 
	<div class="inside">
	<?php 
	if ($smooth_slider['preview'] == "0")
		echo '<code>&lt;?php if( function_exists("get_smooth_slider") ){ get_smooth_slider( $slider_id="'.$smooth_slider['slider_id'].'"); } ?&gt;</code>';
	elseif($smooth_slider['preview'] == "1")
		echo '<code>&lt;?php if( function_exists( "get_smooth_slider_category" ) ){ get_smooth_slider_category( $catg_slug="'.$smooth_slider['catg_slug'].'"); } ?&gt;</code>';
	else
		echo '<code>&lt;?php if( function_exists( "get_smooth_slider_recent" ) ){ get_smooth_slider_recent(); } ?&gt;</code>';
	?>
</div></div>    

<form style="margin-right:10px;font-size:14px;width:100%;" action="" method="post">
<a class="svilla_button svilla_gray_button" href="<?php echo $url; ?>" title="<?php _e('Go to Sliders page where you can re-order the slide posts, delete the slides from the slider etc.','smooth-slider'); ?>"><?php _e('Go to Sliders Admin','smooth-slider'); ?></a>
<input type="submit" value="Export" name="export" title="<?php _e('Export this Settings Set to a file','smooth-slider'); ?>" class="svilla_button" />
<a href="#import" title="<?php _e('Go to Import Settings Form','smooth-slider'); ?>" class="svilla_button">Import</a>
<div class="svilla_cl"></div>
</form>
	<div class="postbox" style="margin:10px 0;"> 
				<div class="inside">
				<div style="margin:10px auto;">
							<a href="http://slidervilla.com" title="Premium WordPress Slider Plugins" target="_blank"><img src="<?php echo smooth_slider_plugin_url('images/banner-premium.png');?>" alt="Premium WordPress Slider Plugins" width="100%" /></a>
				</div>
				<p><a href="http://slidervilla.com/" title="Recommended WordPress Sliders" target="_blank">SliderVilla slider plugins</a> are feature rich and stylish plugins to embed a nice looking featured content slider in your existing or new theme template. 100% customization options available on WordPress Settings page of the plugin.</p>
						<p><strong>Stylish Sliders, <a href="http://slidervilla.com/blog/testimonials/" target="_blank">Happy Customers</a>!</strong></p>
                        <p><a href="http://slidervilla.com/" title="Recommended WordPress Sliders" target="_blank">For more info visit SliderVilla</a></p>
            </div></div>
         
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

	<div class="postbox">
 <div class="inside"> 
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8046056">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</div> 

 </div> <!--end of poststuff --> 

<?php	
}
function register_mysettings() { // whitelist options
  register_setting( 'smooth-slider-group', 'smooth_slider_options' );
}
?>
