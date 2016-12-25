<?php
/*
  Plugin Name: Static Block
  Plugin URI: http://tanzilur.com
  Description: Using this free static block plugin you can show static content in many pages and widgets.
  Version: 1.1
  Author: Mohammad Tanzilur Rahman
  Author URI: http://tanzilur.com
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'STATIC_BLOCK_PLUGIN_DIR' ) )
    define( 'STATIC_BLOCK_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
require_once STATIC_BLOCK_PLUGIN_DIR . '/widget.php';

function static_block_get_version(){
	if (!function_exists( 'get_plugins' ) )
	    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}

function static_block_init(){
    $labels = array(
        'name' => _x('Static Blocks', 'post type general name'),
        'singular_name' => _x('Static Block', 'post type singular name'),
        'add_new' => _x('Add New', 'Static Block'),
        'add_new_item' => __('Add New Static Block'),
        'edit_item' => __('Edit Static Block'),
        'new_item' => __('New Static Block'),
        'view_item' => __('View Static Block'),
        'search_items' => __('Search Static Block'),
        'not_found' =>  __('No Static Block found'),
        'not_found_in_trash' => __('No Static Block found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Static Blocks'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-grid-view',
        'exclude_from_search' => true,
        'supports' => array('title','editor','thumbnail'),
        'rewrite' => array(
            'slug'       => 'static-block',
            'with_front' => FALSE,
        )
    );
    register_post_type('static-block',$args);
}
add_action('init', 'static_block_init');

add_action('admin_head', 'wpds_custom_admin_post_css');
function wpds_custom_admin_post_css() {

    global $post_type;

    if ($post_type == 'static-block') {
        echo "<style>#edit-slug-box {display:none;}</style>";
    }
}
function my_remove_meta_boxes() {
        remove_meta_box('slugdiv', 'static-block', 'normal');
}
add_action( 'admin_menu', 'my_remove_meta_boxes' );
function static_block_meta_setup(){
    global $post;
?>
    <style>
        .full-text{
            width: 100%;
        }
        #post-body #normal-sortables{min-height: 0;}
    </style>
    <div class="portfolio_meta_control">
        <div style="margin-bottom: 10px">
            <label>Content Shortcode</label>
            <input type="text" disabled="disabled" class="widefat" value='[static_block_content id="<?php echo $post->ID; ?>"]' />
        </div>
        <div style="margin-bottom: 10px">
            <label>Featured Image Shortcode</label>
            <input type="text" disabled="disabled" class="widefat" value='[static_block_thumbnail id="<?php echo $post->ID; ?>"]' />
        </div>
    </div>
<?php
    echo '<input type="hidden" name="meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function static_block_meta_save($post_id){
    if (!isset($_POST['meta_noncename']) || !wp_verify_nonce($_POST['meta_noncename'], __FILE__)) {
        return $post_id;
    }
	if ('static-block' != $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_page', $post_id)) {
		return $post_id;
    }
    if (defined('DOING_AUTOSAVE') == DOING_AUTOSAVE) {
		return $post_id;
    }
}
function static_block_meta_init(){
    add_meta_box('static_block_meta', 'Shortcodes', 'static_block_meta_setup', 'static-block', 'advanced', 'core');
    add_action('save_post','static_block_meta_save');
}
add_action('admin_init','static_block_meta_init');


$plugin = plugin_basename(__FILE__);

add_filter( 'manage_edit-static-block_columns', 'static_block_columns' );
add_action( 'manage_static-block_posts_custom_column', 'static_block_add_columns' );


// CUSTOM COLUMNS
function static_block_columns( $columns ){
    $columns['content_shortcode'] = 'Content Shortcode';
    $columns['thumbnail_shortcode'] = 'Thumbnail Shortcode';
    return $columns;
}

// CUSTOM COLUMN DATA
function static_block_add_columns( $column ){
    global $post;

    if ( $column == 'content_shortcode' ) {
        echo '[static_block_content id="' . $post->ID . '"]';
    }
    if(	$column == 'thumbnail_shortcode'){
        echo '[static_block_thumbnail id="' . $post->ID . '"]';
    }
}


function static_block_content($atts, $content=NULL){
    $atts = shortcode_atts( array(
        'id' => ''
    ), $atts, 'static_block_content' );
    $id = $atts['id'];
    $post = get_post($id);
    ob_start();
    echo apply_filters( 'the_content', $post->post_content );
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode('static_block_content','static_block_content');

function static_block_thumbnail($atts, $content=NULL){
    $atts = shortcode_atts( array(
        'id' => ''
    ), $atts, 'static_block_thumbnail' );
    $id = $atts['id'];
    ob_start();
    echo get_the_post_thumbnail( $id, 'full' );
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode('static_block_thumbnail','static_block_thumbnail');

add_filter( 'media_buttons', 'media_button', 31 );
add_action( 'admin_footer',	'static_block');

function media_button() {

    // don't show on dashboard (QuickPress)
    $current_screen = get_current_screen();
    if ( 'dashboard' == $current_screen->base )
        return;

    // don't display button for users who don't have access
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
        return;

    // do a version check for the new 3.5 UI
    $version	= get_bloginfo('version');

    if ($version < 3.5) {
        // show button for v 3.4 and below
        echo '<a href="#TB_inline?width=450&inlineId=static_block_build_form" class="thickbox shortcode_clear" id="add_static_block" title="Static Block Selector Form">
		Add Static Block</a>';
    } else {
        // display button matching new UI
        $img = '<style>#static-block-media-button::before { font: 400 18px/1 dashicons; content: \'\f509\'; }</style><span class="wp-media-buttons-icon" id="static-block-media-button"></span>';
        echo '<a href="#TB_inline?width=450&inlineId=static_block_build_form" class="thickbox shortcode_clear static_block_btn button" id="add_static_block" title="Add Static Block">
		'. $img .' Add Static Block</a>';
    }

}
function static_block() {

    // don't load form on non-editing pages
    $current_screen = get_current_screen();
    if ( 'post' !== $current_screen->base )
        return;

    // don't display form for users who don't have access
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
        return;

    ?>
    <style>
        .static-block-clear{float:none; clear:both;height:0; overflow: hidden;}
        #TB_ajaxContent{margin: 0 auto;}
        .static_popup_option{width:100%;margin-top: 20px;text-align: right}
        .static_popup_option .static_popup_label{display:block;line-height:28px;width:30%;float: left;}
        .static_popup_option .static_popup_label span{display: block;margin-right: 20px;}
        .static_popup_option .static_popup_content{width:70%;float: left;clear:none;}
    </style>
	<script type="text/javascript">
		function InsertStaticBlock() {
			//select field options
            var output = '';
            var staticBlockType = jQuery('#static_block_builder select#staticBlockType').val();
			var staticBlockID   = jQuery('#static_block_builder select#staticBlockID').val();

			if(staticBlockType == 1){
                output = '[static_block_content ';
                output += 'id="' + staticBlockID + '"';
                output += ']';
            }else if(staticBlockType == 2){
                output = '[static_block_thumbnail ';
                output += 'id="' + staticBlockID + '"';
                output += ']';
            }

			window.send_to_editor(output);
		}
		jQuery(document).ready(function($){
            $(window).resize(function() {
				var formHeight	= $('div#TB_window').height() * 0.9;
				var formWidth	= $('div#TB_window').width() * 0.9;

				$("#TB_ajaxContent").animate({
					height:	formHeight,
					width:	formWidth
				}, {
					duration: 100
				});
			});
		})
	</script>
	<div id="static_block_build_form" style="display:none;margin: 0 auto;">
		<div id="static_block_builder">
			<div class="static_popup_option">
				<label for="staticBlockType" class="static_popup_label"><span>Static Block Type</span></label>
				<select name="staticBlockType" id="staticBlockType" class="widefat static_popup_content">
                	<option value="0">Select your option</option>
					<option value="1">Content</option>
					<option value="2">Thumbnail</option>
				</select>
				<div class="static-block-clear"></div>
			</div>
			<div class="static_popup_option">
				<label for="staticBlockID" class="static_popup_label"><span>Static Block ID</span></label>
				<select name="staticBlockID" id="staticBlockID" class="widefat static_popup_content">
                	<option>Select</option>
                    <?php
                    $args = array(
                        'posts_per_page'   => -1,
                        'orderby'          => 'title',
                        'order'            => 'DESC',
                        'post_type'        => 'static-block',
                        'post_status'      => 'publish',
                    );
                    $posts_array = get_posts( $args );
                    foreach($posts_array as $post){
                        echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
                    }
                    ?>
				</select>
				<div class="static-block-clear"></div>
			</div>
            <div class="static_popup_option">
                <input class="button button-large" type="button" value="<?php _e('Insert'); ?>" onclick="InsertStaticBlock();"/>
                <input class="button button-large" type="button" value="<?php _e('Cancel'); ?>" onclick="tb_remove(); return false;"/>
            </div>
		</div>
   </div>
<?php
}

?>
