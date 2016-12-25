<?php 
function ss_get_sliders(){
	global $wpdb,$table_prefix;
	$slider_meta = $table_prefix.SLIDER_META; 
	$sql = "SELECT * FROM $slider_meta ";
 	$sliders = $wpdb->get_results($sql, ARRAY_A);
	return $sliders;
}
function smooth_dateformat_PHP_to_jQueryUI($php_format)
{
    $SYMBOLS_MATCHING = array(
        // Day
        'd' => 'dd',
        'D' => 'D',
        'j' => 'd',
        'l' => 'DD',
        'N' => '',
        'S' => '',
        'w' => '',
        'z' => 'o',
        // Week
        'W' => '',
        // Month
        'F' => 'MM',
        'm' => 'mm',
        'M' => 'M',
        'n' => 'm',
        't' => '',
        // Year
        'L' => '',
        'o' => '',
        'Y' => 'yy',
        'y' => 'y',
        // Time
        'a' => '',
        'A' => '',
        'B' => '',
        'g' => '',
        'G' => '',
        'h' => '',
        'H' => '',
        'i' => '',
        's' => '',
        'u' => ''
    );
    $jqueryui_format = "";
    $escaping = false;
    for($i = 0; $i < strlen($php_format); $i++)
    {
        $char = $php_format[$i];
        if($char === '\\') // PHP date format escaping character
        {
            $i++;
            if($escaping) $jqueryui_format .= $php_format[$i];
            else $jqueryui_format .= '\'' . $php_format[$i];
            $escaping = true;
        }
        else
        {
            if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
            if(isset($SYMBOLS_MATCHING[$char]))
                $jqueryui_format .= $SYMBOLS_MATCHING[$char];
            else
                $jqueryui_format .= $char;
        }
    }
    return $jqueryui_format;
}
function get_slider_posts_in_order($slider_id) {
    	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;
	$slider_posts = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $table_name WHERE slider_id = %d ORDER BY slide_order ASC, date DESC",$slider_id ), OBJECT);
	return $slider_posts;
}
function get_smooth_slider_name($slider_id) {
    	global $wpdb, $table_prefix;
    	$slider_name = '';
	$table_name = $table_prefix.SLIDER_META;
	$slider_obj = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $table_name WHERE slider_id = %d",$slider_id ), OBJECT);
	if (isset ($slider_obj[0]))$slider_name = $slider_obj[0]->slider_name;
	return $slider_name;
}
function ss_get_post_sliders($post_id){
    	global $wpdb,$table_prefix;
	$slider_table = $table_prefix.SLIDER_TABLE; 
	$post_sliders =$wpdb->get_results($wpdb->prepare( "SELECT * FROM $slider_table WHERE post_id = %d",$post_id ), ARRAY_A);
	return $post_sliders;
}
function ss_get_prev_slider(){
    	global $wpdb,$table_prefix;
	$slider_table = $table_prefix.PREV_SLIDER_TABLE; 
	$sql = "SELECT * FROM $slider_table";
	$prev_slider_data = $wpdb->get_results($sql, ARRAY_A);
	return $prev_slider_data;
}
function ss_post_on_slider($post_id,$slider_id){
    	global $wpdb,$table_prefix;
	$slider_postmeta = $table_prefix.SLIDER_POST_META;
    	$result = $wpdb->query($wpdb->prepare( "SELECT * FROM $slider_postmeta WHERE post_id = %d AND slider_id = %d",$post_id,$slider_id ));
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function ss_slider_on_this_post($post_id){
    	global $wpdb,$table_prefix;
	$slider_postmeta = $table_prefix.SLIDER_POST_META;
    	$result = $wpdb->query($wpdb->prepare( "SELECT * FROM $slider_postmeta WHERE post_id = %d",$post_id ));
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
//Checks if the post is already added to slider
function slider($post_id,$slider_id = '1') {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;
	$result = $wpdb->query($wpdb->prepare( "SELECT id FROM $table_name WHERE post_id = %d AND slider_id = %d",$post_id, $slider_id ));
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_post_on_any_slider($post_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;
	$result = $wpdb->query($wpdb->prepare( "SELECT * FROM $table_name WHERE post_id = %d LIMIT 1",$post_id ));
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_slider_on_slider_table($slider_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_TABLE;
	$result = $wpdb->query($wpdb->prepare( "SELECT * FROM $table_name WHERE slider_id = %d LIMIT 1",$slider_id ));
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_slider_on_meta_table($slider_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_META;
	$result = $wpdb->query($wpdb->prepare( "SELECT * FROM $table_name WHERE slider_id = %d LIMIT 1",$slider_id ));
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_slider_on_postmeta_table($slider_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_POST_META;
	$result = $wpdb->query($wpdb->prepare( "SELECT * FROM $table_name WHERE slider_id = %d LIMIT 1",$slider_id ));
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function get_slider_for_the_post($post_id) {
    	global $wpdb, $table_prefix;
	$table_name = $table_prefix.SLIDER_POST_META;
	$slider_postmeta = $wpdb->get_row($wpdb->prepare( "SELECT slider_id FROM $table_name WHERE post_id = %d LIMIT 1",$post_id ), ARRAY_A);
	$slider_id = $slider_postmeta['slider_id'];
	return $slider_id;
}
function smooth_slider_word_limiter( $text, $limit = 50 ) {
    $text = str_replace(']]>', ']]&gt;', $text);
	//Not using strip_tags as to accomodate the 'retain html tags' feature
	//$text = strip_tags($text);
	
    $explode = explode(' ',$text);
    $string  = '';

    $dots = '...';
    if(count($explode) <= $limit){
        $dots = '';
    }
    for($i=0;$i<$limit;$i++){
       if (isset ($explode[$i]))
		$string .= $explode[$i]." ";
    }
    if ($dots) {
        $string = substr($string, 0, strlen($string));
    }
    return $string.$dots;
}
function sslider_admin_url( $query = array() ) {
	global $plugin_page;

	if ( ! isset( $query['page'] ) )
		$query['page'] = $plugin_page;

	$path = 'admin.php';

	if ( $query = build_query( $query ) )
		$path .= '?' . $query;

	$url = admin_url( $path );

	return esc_url_raw( $url );
}
function smooth_slider_table_exists($table, $db) { 
	$tables = mysql_list_tables ($db); 
	while (list ($temp) = mysql_fetch_array ($tables)) {
		if ($temp == $table) {
			return TRUE;
		}
	}
	return FALSE;
}
function add_cf5_column_if_not_exist($table_name, $column_name, $create_ddl) {
     	global $wpdb, $debug;
      	foreach ($wpdb->get_col("DESC $table_name", 0) as $column ) {
		if ($debug) echo("checking $column == $column_name<br />");
		if ($column == $column_name) {
			return true;
		}
     	}
      	//didn't find it try to create it.
      	$q = $wpdb->query($create_ddl);
      	// we cannot directly tell that whether this succeeded!
	foreach ($wpdb->get_col("DESC $table_name", 0) as $column ) {
	  if ($column == $column_name) {
	     return true;
	  }
	}
      	return false;
}
add_action( 'wp_ajax_smooth_update_review_me', 'smooth_update_review_me' );
function smooth_update_review_me() {
	$smooth_slider=array();
	$smooth_slider = get_option('smooth_slider_options');
	$reviewme=(isset($_POST['reviewme']))?($_POST['reviewme']):(strtotime("now"));
	if($reviewme>0){
		$updated_reviewme=$smooth_slider['reviewme']=strtotime("+1 week", $reviewme);
	}
	else{
		$updated_reviewme=$smooth_slider['reviewme']=$reviewme;	
	}
	update_option('smooth_slider_options',$smooth_slider);
	die();
}
?>
