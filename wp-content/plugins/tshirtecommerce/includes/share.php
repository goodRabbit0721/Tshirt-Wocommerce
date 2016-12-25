<?php
function shareLink($design_id = ''){
	$design_id = $_GET['design_id'];
	
	$opt_val = get_option( 'online_designer' );	
	if (isset($opt_val['url']) && $opt_val['url'] > 0)
	{
		$page = get_page_link($opt_val['url']);
	}
	else
	{
		global $wpdb;
		$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='design-your-own'");
		$page = get_page_link($id);
	}
		
	if ($design_id != '')
	{
		$page = add_query_arg( array('design'=>$design_id), $page );
	}	
	wp_redirect( $page );
	exit;
}

if (isset($_GET['design_id']))
{	
	// check home page
	add_filter('the_posts', 'shareLink');	
}
?>