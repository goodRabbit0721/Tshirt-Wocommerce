<?php
/**
 * Plugin Name: WooCommerce Custom Product Designer
 * Plugin URI: http://tshirtecommerce.com
 * Description: WooCommerce Custom Product Designer this plugin help you build a full website powerful with custom product online and sale.
 * Version: 4.0.1
 * Author: tshirtecommerce.com
 * Author URI: http://tshirtecommerce.com
 * License: GPL2
 */

// install plugin
function my_plugin_activate() {
	add_option( 'Activated_Plugin', 'Plugin-Slug' );

	$path = ABSPATH .'tshirtecommerce';
	if (file_exists($path) === false)
	{
		WP_Filesystem();
		$file = dirname(__FILE__).'/core.zip';

		$unzipfile = unzip_file( $file, ABSPATH);		
	}

	global $wpdb;
	$check = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='design-your-own'");
	if ($check == '')
	{
		$post = array(
			'post_name' => 'design-your-own',
			'post_status' => 'publish',
			'post_title' => 'design your own',
			'post_type' => 'page',
			'post_date' => date('Y-m-d H:i:s'),
		);      

		$page = wp_insert_post($post, false);		

	}

	// set permission

	$folder = array(
		'data/', 
		'uploaded/', 
		'cache/', 
		'cache/cart/', 
		'cache/design/', 
		'image-tool/', 
		'image-tool/cache/', 
		'admin/data/',
		'admin/config/'

	);

	setPermission($folder);

	$files = array(
		'data/lang.ini', 
		'data/arts.json', 
		'data/categories_art.json', 
		'data/colors.json', 
		'data/font_categories.json', 
		'data/fonts.json', 
		'data/products.json', 
		'data/settings.json',
		'image-tool/cache/timthumb_cacheLastCleanTime.touch', 
		'admin/data/lang.ini',
		'admin/data/font_categories.json',		
		'admin/config/config.php'
	);
	setPermission($files, 0755);

}
register_activation_hook( __FILE__, 'my_plugin_activate' );


function setPermission($file, $mode = 0755)
{
	if (is_array($file))
	{
		foreach($file as $f)
		{
			$path = ABSPATH .'tshirtecommerce/'.$f;
			if (file_exists($path) !== false)
			{
				chmod($path, $mode);
			}

		}

	}

	else

	{

		$path = ABSPATH .'tshirtecommerce/'.$file;

		if (file_exists($path) !== false)

		{

			chmod($path, $mode);

		}

	}

}



// call to add-on options

$filelist = glob(dirname(__FILE__) .'/includes/' . "*.php");

if (count($filelist))

{

	foreach($filelist as $file)

	{

		include_once($file);

	}

}



/*

 *

 * admin setting

*/

add_action( 'admin_menu', 'online_designer_menu' );

function online_designer_menu() {	

	if ( current_user_can( 'shop_manager' ) )

	{

		add_menu_page( 'T-shirt eCommerce', 'Tshirt eCommerce', 'shop_manager', 'online_designer', 'designer_manage', plugins_url( 'icon.png', __FILE__ ), 25 );	

	}

	if ( current_user_can( 'administrator' ) )

	{

		add_menu_page( 'T-shirt eCommerce', 'Tshirt eCommerce', 'administrator', 'online_designer', 'designer_manage', plugins_url( 'icon.png', __FILE__ ), 25 );	

		add_submenu_page( 'online_designer', 'T-shirt eCommerce', 'Settings', 'administrator', 'admin.php?page=online_designer_config', 'online_designer_config');

		add_submenu_page( 'online_designer', 'T-shirt eCommerce', 'Update', 'administrator', 'admin.php?page=online_designer_update', 'online_designer_update');	

	}

}





if(!ini_get('allow_url_fopen') )

{

	function my_admin_error_notice() {

		$class = "error";

		$message = 'Your server not support <strong>allow_url_fopen</strong>. Please upload your hosting with <strong>allow_url_fopen = ON</strong>. Click <a href="http://tshirtecommerce.com/wp-content/uploads/2015/04/allow_url_fopen.png" target="_blank"><strong>HERE</strong></a>!';

		echo"<div class=\"$class\"> <p>$message</p></div>"; 

	}

	add_action( 'admin_notices', 'my_admin_error_notice' ); 

}



function designer_manage()

{

	$check 	= true;

	$path 	= ABSPATH .'tshirtecommerce';

	if (file_exists($path) === false)

	{

		WP_Filesystem();

		$file = dirname(__FILE__).'/tshirtecommerce.zip';

		

		$unzipfile = unzip_file( $file, ABSPATH);

		

		if ( !$unzipfile ) {

			$check = false;

		}

	}

		

	if ( is_super_admin() )

	{

		$user 	= wp_get_current_user();
		
		echo '<script>
			var loginTshirtVariable = {login: true, email: "'.$user->data->user_email.'", id: "'.$user->data->ID.'"};
		</script>';

		$_SESSION['is_admin'] = array(

			'login' => true,

			'email' => $user->data->user_email,

			'id' => $user->data->ID,

		);		

	}

	else

	{

		$_SESSION['is_admin'] = false;

	}

	if ($check == true)

	{

		$url = site_url('tshirtecommerce/admin/index.php?session_id='.session_id());		

		echo '<style>#wpcontent{padding-left:0;}#wpbody-content{padding-bottom:0;}#wpwrap > div#wpfooter{display: none;}</style>';

		echo "<script type='text/javascript'>function setHeightF(height){document.getElementById('tshirtecommerce-build').setAttribute('height', height + 'px');}</script>";

		echo '<iframe id="tshirtecommerce-build" width="100%" height="800px" src="'.$url.'"></iframe>';

	}

	else

	{

		$download = plugin_dir_url(__FILE__) . 'tshirtecommerce.zip';

		echo 'Sorry, your server not support unzipping the file. Please click <a href="'.$download.'"><strong>here</strong></a> to download file, unzip and upload to path '.ABSPATH;

	}		

}



// update function

function online_designer_update()

{

	// update file

	$msg = '';

	if (!empty($_GET['action']))

	{

		$action = $_GET['action'];

		$version = $_GET['file'];

		$file = 'http://updates.tshirtecommerce.com/wp/'.$version;

		$data = openURL($file);

		if ($data != false)

		{		

			$path = ABSPATH . $version;

			if(file_put_contents($path, $data))

			{

				WP_Filesystem();

				$unzipfile = unzip_file( $path, ABSPATH);

				if ( $unzipfile ) {

					$msg = 'Update successful!';

				}

				else

				{

					$msg = 'Sorry, you can not update because your server not allow writable file. You can download, upload to root folder and unzip.';

				}

			}

			else

			{

				$msg = 'Sorry, you can not update because your server not allow writable file. You can download, upload to root folder and unzip.';

			}

		}

	}

	echo '<h2>Update Plugin ';

	$file = plugin_dir_path( __FILE__ ) . 'version.json';

	if (file_exists($file))

	{

		$version = json_decode(file_get_contents($file));

		

		echo '<a href="#" class="add-new-h2"><small>(Using version: '.$version->version.')</small></a>';

		

	}

	echo '</h2>';

	if ($msg != '')

	{

		echo "<div id='notice' class='updated fade'><p>".$msg."</p></div>";

	}

	try {		

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_URL, 'http://updates.tshirtecommerce.com/wp/updates.json');

		$content = curl_exec($curl);

		curl_close($curl);

		

		if ($content !== false)

		{

			$data 		= json_decode($content);

			

			if (count($data))

			{

				echo '<div id="poststuff">';

				echo 	'<div class="metabox-holder columns-2" style="width: 90%;">';

				

				foreach($data as $key => $value)

				{

					echo 		'<div class="postbox-container meta-box-sortables ui-sortable">';

					echo 			'<div class="postbox">'

										.'<div style="float:right;padding-top: 5px;padding-right: 10px;line-height: 22px;">'

											.'<a class="button button-primary button-small" href="admin.php?page=admin.php?page=online_designer_update&action=update&file='.$value->file.'">Update</a>'

											.' or <a target="_blank" href="http://updates.tshirtecommerce.com/wp/'.$value->file.'">download</a>'

										.'</div>';

					echo 				'<h3 class="hndle ui-sortable-handle">'

										.'<span>Version '.$value->version.' <small>'.$value->date.'</small></span>'

										.'</h3>';

					echo 				'<div class="inside" style="overflow:auto;max-height:200px;">'.openURL('http://updates.tshirtecommerce.com/wp/'.$value->info).'</div>';

					echo 			'</div>';

					echo 		'</div>';

				}

				

				echo 	'</div>';

				echo '</div>';

			}

		}

		else

		{

			echo '<h2>Please load page again!</h2>';

		}

	}

	catch (Exception $e) {

		echo '<h2>Please load again!</h2>';

	}

}



function collect_file($url){

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch, CURLOPT_AUTOREFERER, false);

	curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");

	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

	curl_setopt($ch, CURLOPT_HEADER, 0);

	$result = curl_exec($ch);

	curl_close($ch);

	return($result);

}

function write_to_file($text,$new_filename){

	$fp = fopen($new_filename, 'w');

	fwrite($fp, $text);

	fclose($fp);

}







// save setting

function online_designer_config() {



    //must check that the user has the required capability 

    if (!current_user_can('manage_options'))

    {

      wp_die( __('You do not have sufficient permissions to access this page.') );

    }



    // variables for the field and option names 

    $opt_name = 'online_designer';

    $hidden_field_name 		= 'mt_submit_hidden';

    $data_field_name_url	= 'designer[url]';

    $data_field_name_start	= 'designer[btn-start]';

    $data_field_name_custom	= 'designer[btn-custom]';



    // Read in existing option value from database

    $opt_val = get_option( $opt_name );



    // See if the user has posted us some information

    // If they did, this hidden field will be set to 'Y'

    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

        // Read their posted value

        $opt_val = $_POST[ 'designer' ];



        // Save the posted value in the database

        update_option( $opt_name, $opt_val );



        // Put an settings updated message on the screen



	?>	

	<?php

    }

	if ($opt_val['btn-custom'] == '')

		$opt_val['btn-custom'] = 'Custom Design';

	if ($opt_val['btn-start'] == '')

		$opt_val['btn-start'] = 'Start Design';



    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header



    echo "<h2>" . __( 'T-Shirt eCommerce - Settings', 'menu-test' ) . "</h2>";



    // settings form

	$pages = get_pages();	

    ?>	

	<form name="form1" method="post" action="">

		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

		

		<table class="form-table">

			<tbody>

				<tr valign="top">

					<th scope="row" class="titledesc"><?php _e("Product Designer Page", 'menu-test' ); ?> </th>

					

					<td class="forminp-text">

						<select name="<?php echo $data_field_name_url; ?>" class="chosen_select_nostd">

							

						<?php if ( count($pages) > 0){ ?>

							<?php foreach($pages as $page) { 

								if ($opt_val['url'] == $page->ID) $selected = 'selected="selected"';

								else $selected = '';

							?>

								<option <?php echo $selected; ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>

							<?php } ?>

						

						<?php } ?>

							

						</select>										

					</td>

				</tr>

				

				<tr valign="top">

					<th scope="row" class="titledesc"><?php _e("Lable of button Start", 'menu-test'); ?> </th>

					<td class="forminp-text">

						<input type="text" name="<?php echo $data_field_name_start; ?>" value="<?php echo $opt_val['btn-start']; ?>" />						

					</td>

				</tr>

				

				<tr valign="top">

					<th scope="row" class="titledesc"><?php _e("Lable of button Custom", 'menu-test'); ?> </th>

					<td class="forminp-text">

						<input type="text" name="<?php echo $data_field_name_custom; ?>" value="<?php echo $opt_val['btn-custom']; ?>" />						

					</td>

				</tr>

				

				<?php do_action( 'tshirtecommerce_setting', $opt_val ); ?>

			</tbody>

		</table>		

		<hr />



		<p class="submit">

			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />

		</p>



	</form>

</div>



<?php

 

}



// show link setting in page plugins

function online_designer_settings_link($actions, $file) 

{

	if(false !== strpos($file, 'online_designer'))

		$actions['settings'] = '<a href="options-general.php?page=online_designer">Settings</a>';

	return $actions; 

}

add_filter('plugin_action_links', 'online_designer_settings_link', 2, 2);



function my_plugin_admin_notices()

{

	echo "<div id='notice' class='updated fade'><p>My Plugin is not configured yet. Please do it now.</p></div>";

}





// add link of product

if(!function_exists('wc_custom_product_data_fields'))

{

  function wc_custom_product_data_fields()

  {



		$custom_product_data_fields = array();



		$custom_product_data_fields[] = array(

			  'tab_name'    => __('T-Shirt eCommerce', 'wc_cpdf'),

		);



		$custom_product_data_fields[] = array(

			  'id'          => '_product_id',

			  'type'        => 'hidden',                 

			  'class'       => 'large',

		);

		

		$custom_product_data_fields[] = array(

			  'id'          => '_product_title_img',

			  'type'        => 'image',

			  'class'       => 'large'

		);		

		return $custom_product_data_fields;

  }

}



// check WordPress version

if(!function_exists('wc_productdata_options_wp_requred'))

{

  function wc_productdata_options_wp_requred()

  {

	global $wp_version;

	$plugin = plugin_basename(__FILE__);

	$plugin_data = get_plugin_data(__FILE__, false);



	if(version_compare($wp_version, "3.3", "<"))

	{

		if(is_plugin_active($plugin))

		{

		deactivate_plugins($plugin);

		wp_die("'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress Admin</a>.");

		}

	}

  }

  add_action('admin_init', 'wc_productdata_options_wp_requred');

}



// Checks if the WooCommerce plugins is installed and active.

if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){

  // Include shortcode functions file

  require_once dirname( __FILE__ ) . '/class-wc-product-data-fields.php';

}

// show button design
//add_action( 'woocommerce_single_product_summary', 'design_button', 30 );
add_action( 'woocommerce_after_shop_loop_item_title', 'design_button', 10 );
add_action( 'woocommerce_before_add_to_cart_button', 'design_button', 30 );
function design_button() {

	global $wc_cpdf, $wpdb;	

	$product_id = get_the_ID();
	$link = $wc_cpdf->get_value($product_id, '_product_id');
	if ($link != '')
	{
		$opt_val = get_option( 'online_designer' );	
		if (isset($opt_val['url']) && $opt_val['url'] > 0)
		{
			$page = get_page_link($opt_val['url']);
		}
		else
		{
			$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='design-your-own'");
			$page = get_page_link($id);
		}		
		$array = explode(':', $link);

		if (empty($opt_val['btn-start']) || $opt_val['btn-start'] == '')
			$opt_val['btn-start'] = 'Start Design';

		if (empty($opt_val['btn-custom']) || $opt_val['btn-custom'] == '')
			$opt_val['btn-custom'] = 'Custom Design';

		$price = get_post_meta( get_the_ID(), '_regular_price');
		if (isset($price[0]) && $price[0] > 0)
		{
			echo '<div class="woocommerce_msrp">';

			// load product attribute
			if (is_product())
			{
				do_action( 'tshirtecommerce_product_attribute', $array );
			}
	
			if (count($array) > 1)
			{
				if (count($array) < 5)
				{
					$link = $link. ':'. $product_id;
				}
				$link = add_query_arg( array('design'=>$link), $page );
				echo '<a class="button e-custom-product" onclick="return loadProductDesign(this);" href="'.$link.'">'.$opt_val['btn-custom'].'</a>';			
			}
			else
			{
				$link = add_query_arg( array('product_id'=>$product_id), $page );
				echo '<a class="button e-custom-product" onclick="return loadProductDesign(this);" href="'.$link.'">'.$opt_val['btn-start'].'</a>';	
			}
			echo '</div><br />';
		}
		else
		{
			global $product;			
			if( $product->is_type( 'simple' ) )
			{
				echo '<span style="color: #ff0000;">Please add price of product</span>';
			}
			elseif( $product->is_type( 'variable' ) )
			{				
				if (count($array) > 1)
				{
					$link = $link. ':'. $product_id;
					$link = add_query_arg( array('design'=>$link), $page );					
					echo '<input type="hidden" value="'.$link.'" class="product-design-link">';
					//echo '<span>'.$opt_val['btn-custom'].'</span>';
				}
				else
				{
					echo '<input type="hidden" value="'.$page.'" class="product-design-link">';
					//echo '<span>'.$opt_val['btn-start'].'</span>';
				}				
			}
		}
	}
	else
	{
		remove_action( 'tshirtecommerce_product_attribute', 'designer_product_attribute');
	}
}

add_action( 'woocommerce_before_single_variation', 'design_button_variable');
function design_button_variable()
{
	global $wc_cpdf, $wpdb, $product;

	$link = $wc_cpdf->get_value(get_the_ID(), '_product_id');	

	if ($link != '' && $product->is_type( 'variable' ))
	{
		$opt_val = get_option( 'online_designer' );
		if (empty($opt_val['btn-custom']) || $opt_val['btn-custom'] == '')
			$opt_val['btn-custom'] = 'Custom Design';
		

		$html = '';
		$html .= '<div class="woocommerce_msrp pull-right">';					
		$html .= '<a class="button" onclick="variationProduct(this)" href="javascript:void(0);">'.$opt_val['btn-custom'].'</a>';						
		$html .= '</div><br />';
		echo $html;
	}
}





// add js to site

function theme_name_scripts() {

	wp_enqueue_script( 'designer_app', plugins_url( 'assets/js/app.js', __FILE__ ), array(), '1.0.0', true );		

	wp_enqueue_style( 'designer_css', plugins_url( 'assets/css/font-end.css', __FILE__ ) );		

}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

add_action( 'init', 'tshirtecommerce_setcookie' );
function tshirtecommerce_setcookie() {
	setcookie( 'design_session_id', 'tesing', time() + 3600, COOKIEPATH, COOKIE_DOMAIN   );
}

// add js to admin

add_action( 'admin_init', 'designer_plugin_admin_init' );

function designer_plugin_admin_init() 

{

	if (!session_id())

	{

		session_start();

	}

	wp_register_style( 'designer_css_bootstrap', plugins_url('assets/css/bootstrap.min.css', __FILE__) );	

	wp_register_script( 'designer_js_bootstrap', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ) );		

	wp_register_script( 'designer_api', plugins_url( 'assets/js/app.js', __FILE__ ) );

}



// ajax get all product design

// link wp-admin/admin-ajax.php?action=woo_products_action

add_action( 'wp_ajax_woo_products_action', 'wp_ajax_woo_products' );

add_action( 'wp_ajax_nopriv_woo_products_action', 'wp_ajax_woo_products' );

function wp_ajax_woo_products()

{

	global $wc_cpdf;

	$args = array( 'post_type' => 'product', 'posts_per_page' => 1000);

	$products = get_posts( $args );

		

	//get product design

	$design = array();

	$design_ids = array();

	foreach ($products as $product)

	{	

		$ids = $wc_cpdf->get_value($product->ID, '_product_id');

		if ($ids != '')

		{

			$temp = explode(':', $ids);

			if (count($temp) == 1)

			{

				$design[$ids] = $product->ID;

				$design_ids[] = $ids;

			}

		}	

	}

	include_once(dirname(__FILE__).'/helper/functions.php');

	$json = ABSPATH .'tshirtecommerce/data/products.json';

	$array = array(

		'products' => array()

	);

	if (file_exists($json))

	{

		$string = file_get_contents($json);

		if ($string != false)

		{

			$products = json_decode($string);

			if ( isset($products->products) && count($products->products) > 0)

			{

				// find categories

				$cate_products = $design_ids;

				if (isset($_POST['id']) && $_POST['id'] > 0)

				{

					$category_id = $_POST['id'];

					$cate_file = ABSPATH .'tshirtecommerce/data/product_categories.json';				

					if (file_exists($cate_file))

					{

						$product_ids = array();

						$content = file_get_contents($cate_file);

						if ($content != false)

						{

							$data = json_decode($content);

							

							for($i=0; $i < count($data); $i++)

							{

								if ($data[$i]->cate_id == $category_id && !in_array($data[$i]->product_id, $product_ids))

								{

									$product_ids[] = $data[$i]->product_id;

								}

							}

						}

						$cate_products = $product_ids;

					}

				}

								

				define('ROOT', ABSPATH .'tshirtecommerce');

				define('DS', DIRECTORY_SEPARATOR);

				include_once (ROOT .DS. 'includes' .DS. 'functions.php');

				$dg = new dg();

				$lang = $dg->lang('lang.ini', false);

				

				foreach($products->products as $product)

				{

					if ( in_array($product->id, $design_ids) && in_array($product->id, $cate_products) )

					{

						$product->parent_id = $design[$product->id];

												

						if (isset($product->attributes->name))

						{

							$product->attribute = getAttributes_ajax($product->attributes);

						}

						else

						{

							$product->attribute = '';

						}



						$product->attribute .= quantity_ajax($product->min_order, $lang['quantity'], $lang['min_quantity']);

							

						$array['products'][] = $product;

					}

				}

			}

		}

	}

	echo json_encode($array);

	die();

}



// add ajax

add_action( 'wp_ajax_designer_action', 'wp_ajax_designer' );

function wp_ajax_designer()

{

	global $wpdb; // this is how you get access to the database

	$key = $_POST['key'];

	if ($key == '1')

	{

		$link = $_POST['link'];		

		echo openURL($link);

	}

	else

	{

		$url = site_url('tshirtecommerce/admin.php?key=').$key;		

		echo openURL($url);

	}

	die();

}



function openURL($url)

{

	$data = false;

	if( function_exists('curl_exec') )

	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);

		curl_close($ch);

	}

	if( $data == false && function_exists('file_get_contents') )

	{

		$data = file_get_contents($url);

	}

	return $data;	

}



add_filter('the_content', 'add_shortcode_to_page_design');

function add_shortcode_to_page_design($content )

{

	global $post;

	$designer = get_option( 'online_designer' );



	if (isset($designer['url']) && $designer['url'] > 0)

	{

		$id = $designer['url'];

	}

	else

	{

		$id = 'design-your-own';

	}	

	if ( is_page( $id ) & $post->post_type == 'page')

	{	

		if ($post->ID == $id || $post->post_name == $id)

		{			

			if ( has_shortcode($content, 'tshirtecommerce') )

			{

				return $content;

			}

			else

			{

				$content = '[tshirtecommerce id="0"]';

				return $content;

			}

		}		

	}

	return $content;

}



function register_session(){

    if( !session_id() )
        session_start();
		
	wc_setcookie( 'design_session_id', session_id() );

}

add_action('init','register_session');



// add page designer

function tshirtecommerce_func( $atts )

{

	global $woocommerce;

	$html = '';

	$cart_url = $woocommerce->cart->get_cart_url();

	$product_id = 0;

	if (isset($_GET['product_id']))

	{

		$product_id	 	= $_GET['product_id'];

	}

	else if(isset($_GET['design']))

	{

		$design = explode(':', $_GET['design']);

		if (isset($design[4]))

			$product_id = $design[4];

	}

	if ($product_id == 0)

	{

		if ( isset($atts['id']) && $atts['id'] > 0)

		{

			$product_id	= $atts['id'];

		}

		else

		{

			global $wc_cpdf;

			$args = array( 'post_type' => 'product', 'posts_per_page' => 1000);

			$products = get_posts( $args );

			foreach ($products as $product)

			{	

				$id = $wc_cpdf->get_value($product->ID, '_product_id');

				if ($id != '')

				{					

					$product_id = $product->ID;

					break;

				}

			}

		}

	}

	if ($product_id == 0)

	{

		$html .= '<div class="alert alert-danger" role="alert">Please add product design in woocommerce. <a href="https://www.youtube.com/watch?v=VJIOYJ3pSzk">View Video</a></div>';

	}

	else

	{

		$options_data = maybe_unserialize(get_post_meta($product_id, 'wc_productdata_options', true));

		if ( isset($options_data[0]) && isset($options_data[0]['_product_id']) &&  $options_data[0]['_product_id'] > 0)

		{

			$product_design = $options_data[0]['_product_id'];

		}

		

		// get link of page design

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

		

		$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', '', 'woocommerce' ), '' );

		

		$html .= '<script type="text/javascript">var woo_url_cart = "'.$cart_url.'"; var wp_ajaxurl = "'.admin_url('admin-ajax.php').'"; var urlDesign = "'.$page.'"; var text_cart_added=\''.$added_text.'\'</script>';

		$url = '';

		if (isset($_GET['design']))

		{			

			$design = $_GET['design'];

			$designs = explode(':', $design);			

			if (count($designs) == 5)

			{

				$url = site_url('tshirtecommerce/index.php?product='.$designs[2].'&color='.$designs[3].'&user='.$designs[0].'&id='.$designs[1].'&parent='.$designs[4]);

			}

			else

			{

				$url = site_url('tshirtecommerce/index.php?product='.$product_design.'&parent='.$product_id);		

			}

		}

		else

		{

			$url = site_url('tshirtecommerce/index.php?product='.$product_design.'&parent='.$product_id);

			

			// allow edit design in cart

			if (isset($_GET['cart_id']))

			{

				$url = $url.'&cart_id='.$_GET['cart_id'];

			}

		}

		if (isset($_GET['variation_id']))

		{

			$url = $url . '&variation_id='.$_GET['variation_id'];

			$html .= '<script type="text/javascript">var product_variation = '.$_GET['variation_id'].';</script>';

		}

		if (isset($_GET['attributes']))

		{

			$attribute = $_GET['attributes'];

			

			$attrs = explode(';', $attribute);

			$html .= '<script type="text/javascript">var product_attributes = {};';

			for($i=0; $i<count($attrs); $i++)

			{

				$field 	= explode('|', $attrs[$i]);

				$html 	.= 'product_attributes["'.$field[0].'"]="'.$field[1].'"; ';

			}

			$html .= '</script>';

			

			$url = $url . '&attributes='.$attribute;

		}

		if ( is_user_logged_in() ) 

		{

			$user 	= wp_get_current_user();

			$logged = array(

				'login' => true,

				'email' => $user->data->user_email,

				'id' => $user->data->ID,

				'is_admin' => false,

			);

			if ( is_super_admin() )

			{

				$logged['is_admin'] = true;

			}

			$_SESSION['is_logged'] = $logged;

		} 

		else

		{

			$_SESSION['is_logged'] = false;

		}		

		$url	= apply_filters( 'tshirt_set_url_designer', $url );

		

		$html .= '<div class="row-designer"></div>';

		$html .= "<link rel='stylesheet' href='".plugins_url('tshirtecommerce/assets/css/mobile.css')."' type='text/css' media='all' />";		

		$html .= '<script type="text/javascript">var urlDesignload = "'.$url.'"; var urlBack = "'.get_permalink($product_id).'"</script>';

		

		do_action( 'tshirtecommerce_html', $opt_val );

	}

	return $html;

}

add_shortcode( 'tshirtecommerce', 'tshirtecommerce_func' );



// ajax add to cart

add_action( 'wp_ajax_woocommerce_add_to_cart_variable_rc', 'woocommerce_add_to_cart_variable_rc_callback' );

add_action( 'wp_ajax_nopriv_woocommerce_add_to_cart_variable_rc', 'woocommerce_add_to_cart_variable_rc_callback' );

function woocommerce_add_to_cart_variable_rc_callback() {

	global $woocommerce; 	

	ob_start();

	$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );

	$quantity = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );

	$variation_id = $_POST['variation_id'];

	$variation  = $_POST['variation'];

	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

	$cart = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation);

	if ( $passed_validation && $cart) 

	{

		do_action( 'woocommerce_ajax_added_to_cart', $product_id );

		

		if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {

			wc_add_to_cart_message( $product_id );

		}		



		// Return fragments

		WC_AJAX::get_refreshed_fragments();

	} 

	else 

	{

		$this->json_headers();



		// If there was an error adding to the cart, redirect to the product page to show any errors

		$data = array(

			'error' => true,

			'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )

		);			

	}	

	die();

}



// add custom when add to cart

add_action( 'woocommerce_add_to_cart', 'save_custom_field_design', 1, 5 );

function save_custom_field_design( $cart_item_key, $product_id = null, $quantity= null, $variation_id= null, $variation= null ) {

	if (empty($_POST['price']))

		$price = 0;

	else

		$price = $_POST['price'];

	if (empty($_POST['rowid']))

		$rowid = '';

	else

		$rowid = $_POST['rowid'];

	if (empty($_POST['color_hex']))

		$color_hex = '';

	else

		$color_hex = $_POST['color_hex'];

	if (empty($_POST['color_title']))

		$color_title = '';

	else

		$color_title = $_POST['color_title'];

	if (empty($_POST['teams']))

		$teams = '';

	else

		$teams = $_POST['teams'];

	if (empty($_POST['options']))

		$options = '';

	else

		$options = $_POST['options'];

	if (empty($_POST['images']))

		$images = '';

	else

		$images = $_POST['images'];

	$data_items = array(

		'design_price' => $price,

		'design_id' => $rowid,

		'color_hex' => $color_hex,

		'color_title' => $color_title,

		'teams' => $teams,

		'options' => $options,

		'images' => $images,

	);

	$data_items = apply_filters( 'tshirtecommerce_product_set_attribute', $data_items, $product_id);

	WC()->session->set( $cart_item_key.'_designer', $data_items );

}



// add link allow edit design in page cart

function woocommerce_cart_item_name_edit_design( $title, $cart_item, $cart_item_key ) {

    $data = WC()->session->get( $cart_item_key.'_designer');

	if ($data != null && count($data) > 0 && isset($data['design_id']) && $data['design_id'] != '' && $data['design_id'] != 'blank')

	{

		$product_id = $cart_item['product_id'];

		$opt_val = get_option( 'online_designer' );

		$page = get_page_link($opt_val['url']);

		$link = add_query_arg( array('product_id'=>$product_id, 'cart_id'=>$data['design_id']), $page );

		

		define('ROOT', ABSPATH .'tshirtecommerce');

		define('DS', DIRECTORY_SEPARATOR);

		include_once (ROOT .DS. 'includes' .DS. 'functions.php');

		$dg = new dg();

		$lang = $dg->lang('lang.ini', false);



		$html = '<a href="'.$link.'" title="'.$lang['designer_cart_edit_des'].'">'.$lang['designer_cart_edit'].'</a>';

		return $html;

	}

	else

	{

		return '';

	}

}

add_filter( 'woocommerce_cart_item_name', 'woocommerce_cart_item_name_edit_design', 10, 3 );



// show info in cart

function render_meta_on_cart_item( $title = null, $cart_item = null, $cart_item_key = null ) {

	if( $cart_item_key && is_cart() ) {

		

		$data = WC()->session->get( $cart_item_key.'_designer');		

		if ($data != null && count($data) > 0 && isset($data['design_id']) && $data['design_id'] != '')

		{

			echo '<p>'.$title.'</p>';

			if (isset($data['images']))

			{

				$images = json_decode(str_replace('\\', '', $data['images']));

				if (count($images))

				{

					echo '<p>';

					foreach($images as $view => $image)

					{

						echo ' <a href="'. site_url('tshirtecommerce').'/'.$image .'" rel="lightbox[pp_gal]" class="lightboxhover"><img src="'. site_url('tshirtecommerce').'/'.$image .'" class="light-dropshaddow" style="width:90px"></a>';

					}

					echo '</p>';

				}				

			}

			

			if (isset($data['teams']['name']) && count($data['teams']['name']) > 0)

			{

				echo '<table>'

					. 		'<thead>'

					. 			'<tr>'

					. 				'<th>Name</th>'

					. 				'<th>Number</th>'

					. 				'<th>Sizes</th>'

					. 			'</tr>'

					. 		'</thead>'

					. 		'<tbody>';

					

				for($i=1; $i<=count($data['teams']['name']); $i++ )

				{

					$size = explode('::', $data['teams']['size'][$i]);

					echo 		'<tr>'

						.			'<td>'.$data['teams']['name'][$i].'</td>'

						.			'<td>'.$data['teams']['number'][$i].'</td>'

						.			'<td>'.$size[0].'</td>'

						.		'</tr>';

				}

				

				echo 		'</tbody></table>';

			}

			

			echo '<dl class="variation">';

			

			if (isset($data['color_title']))

			{

				echo '<dt>Color: </dt>';

				echo '<dd>'.$data['color_title'].'</dd>';

			}

			

			

			

			if ($data['options'] != '' && $data['options'] != '[]')

			{

				if (is_string($data['options']))

					$options = json_decode( str_replace('\\"', '"', $data['options']), true);

				else

					$options = $data['options'];

								

				if (count($options) > 0)

				{

					foreach($options as $i => $option)

					{						

						

						if (isset($option['type']) && file_exists( dirname(__FILE__) .'/options/'.$option['type'].'.php' ) )

						{

							require_once(dirname(__FILE__) .'/options/'.$option['type'].'.php');

							continue;

						}

						

						if (isset($options[$i]) && isset($options[$i]['value']))

						{

							if (is_string($options[$i]['value']) && $options[$i]['value'] == '') continue;

							if (is_array($options[$i]['value']) && count($options[$i]['value']) == 0) continue;

								

							echo '<dt>'.$options[$i]['name'].': </dt>';

							

							echo '<dd>';

							if (is_array($options[$i]['value']))

							{							

								foreach ($options[$i]['value'] as $name => $value)

								{									

									if ($value == '') continue;

									

									if ($options[$i]['type'] == 'checkbox')

									{										

										echo $value. '; ';

									}

									else if ($options[$i]['type'] == 'textlist')

									{

										if ($value == '0') continue;

										

										echo $name.'  -  '.$value. '; ';

									}

									else

									{

										echo $name.'  -  '.$value. '; ';

									}

								}

							}

							else

							{

								echo $options[$i]['value'];

							}

							echo '</dd>';

						}

					}

				}

			}

			

			echo '</dl>';

		}

		else

		{

			echo $title;

		}

	}

	else

	{

		echo $title;

	}

}

add_filter( 'woocommerce_cart_item_name', 'render_meta_on_cart_item', 1, 3 );





// add data design to order

function tshirt_order_meta_handler( $item_id, $values, $cart_item_key ) {

	if( WC()->session->__isset( $cart_item_key.'_designer' ) ) {

		wc_add_order_item_meta( $item_id, "custom_designer", WC()->session->get( $cart_item_key.'_designer') );

	}

}

add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler', 1, 3 );







// show options in order

add_action( 'woocommerce_before_order_itemmeta', 'oder_item_view_diesign', 1, 3 );

function oder_item_view_diesign($item_id, $item, $product)

{

	$data = WC_Abstract_Order::get_item_meta( $item_id, "custom_designer", true );

	// product design

	if (isset($data['design_id']) && $data['design_id']!= '' && $data != null && count($data) > 0)

	{

		if (isset($data['images']))

		{

			$images = json_decode(str_replace('\\', '', $data['images']));

			if (count($images))

			{

				echo '<table><tr>';

				foreach($images as $view => $image)

				{

					echo '<td>';

					echo '<img style="width: 100px;" src="'. site_url('tshirtecommerce').'/'.$image .'" with="100">';

					echo '<br /><a target="_blank" href="'.site_url('tshirtecommerce/design.php?key='.$data['design_id'].'&view='.$view).'">Download Design</a>';

					echo '</td>';

				}

				echo '</tr></table>';

				

				// add link design

				global $wc_cpdf;		

				$product_id = $wc_cpdf->get_value($product->id, '_product_id');

				$ids = explode(':', $product_id);

				if (count($ids) > 2)

				{

					$product_id = $ids[2];

				}				

				$opt_val = get_option( 'online_designer' );	

				$page = get_page_link($opt_val['url']);				

				$link = add_query_arg( array('product_id'=>$product->id, 'cart_id'=>$data['design_id']), $page );

				echo '<p><center><a target="_blank" href="'.$link.'"><strong>View Design</strong></a></center></p><hr />';

			}

			else

			{

				echo '<p>'

					.'Download design:'

					.' <a href="'.site_url('tshirtecommerce/design.php?key='.$data['design_id']).'&view=front" target="_blank" title="click to view design"><strong>Front</strong></a> - '

					.' <a href="'.site_url('tshirtecommerce/design.php?key='.$data['design_id']).'&view=back" target="_blank" title="click to view design"><strong>Back</strong></a> - '

					.' <a href="'.site_url('tshirtecommerce/design.php?key='.$data['design_id']).'&view=left" target="_blank" title="click to view design"><strong>Left</strong></a> - '

					.' <a href="'.site_url('tshirtecommerce/design.php?key='.$data['design_id']).'&view=right" target="_blank" title="click to view design"><strong>Right</strong></a>'

				. '</p>';

			}

		}

		

		if (isset($data['teams']['name']) && count($data['teams']['name']) > 0)

		{

			echo '<table>'

				. 		'<thead>'

				. 			'<tr>'

				. 				'<th>Name</th>'

				. 				'<th>Number</th>'

				. 				'<th>Sizes</th>'

				. 			'</tr>'

				. 		'</thead>'

				. 		'<tbody>';

				

			for($i=1; $i<=count($data['teams']['name']); $i++ )

			{

				$size = explode('::', $data['teams']['size'][$i]);

				echo 		'<tr>'

					.			'<td>'.$data['teams']['name'][$i].'</td>'

					.			'<td>'.$data['teams']['number'][$i].'</td>'

					.			'<td>'.$size[0].'</td>'

					.		'</tr>';

			}

			

			echo 		'</tbody></table>';

		}

				

		if (isset($data['color_title']))

		{

			echo '<p>Color: '.$data['color_title'].'</p>';

		}

		

		if ($data['options'] != '' && $data['options'] != '[]')

		{

			if (is_string($data['options']))

				$options = json_decode( str_replace('\\"', '"', $data['options']), true);

			else

				$options = $data['options'];

			

			

			if (count($options) > 0)

			{

				foreach($options as $i => $option)

				{

					

					if (isset($option['type']) && file_exists( dirname(__FILE__) .'/options/'.$option['type'].'.php' ) )

					{

						require_once(dirname(__FILE__) .'/options/'.$option['type'].'.php');

						continue;

					}

					

					if (isset($options[$i]) && isset($options[$i]['value']))

					{

						if (is_string($options[$i]['value']) && $options[$i]['value'] == '') continue;

						if (is_array($options[$i]['value']) && count($options[$i]['value']) == 0) continue;

							

						echo '<dt>'.$options[$i]['name'].': </dt>';

						

						echo '<dd>';

						if (is_array($options[$i]['value']))

						{							

							foreach ($options[$i]['value'] as $name => $value)

							{									

								if ($value == '') continue;

								

								if ($options[$i]['type'] == 'checkbox')

									echo $value. '; ';

								else if ($options[$i]['type'] == 'textlist')

								{

									if ($value == '0') continue;

									

									echo $name.'  -  '.$value. '; ';

								}

								else

								{

									echo $name.'  -  '.$value. '; ';

								}

							}

						}

						else

						{

							echo $options[$i]['value'];

						}

						echo '</dd>';

					}

				}

			}

		}		

	}

	else

	{

		// get design idea

		

		global $wc_cpdf;		

		$product_id = $wc_cpdf->get_value($product->id, '_product_id');

		

		if ($product_id != '')

		{

			$opt_val = get_option( 'online_designer' );	

			$page = get_page_link($opt_val['url']);				

			$link = add_query_arg( array('design'=>$product_id, 'parent_id'=>$product->id), $page );

			

			$ids = explode(':', $product_id);

			echo '<p>'

					.'<a href="'.$link.'" target="_blank" title="click to view design"><strong>View design</strong></a> or Download design:'

					.' <a href="'.site_url('tshirtecommerce/design.php?idea=1&key='.$product_id).'&view=front" target="_blank" title="click to view design"><strong>Front</strong></a> - '

					.' <a href="'.site_url('tshirtecommerce/design.php?idea=1&key='.$product_id).'&view=back" target="_blank" title="click to view design"><strong>Back</strong></a> - '

					.' <a href="'.site_url('tshirtecommerce/design.php?idea=1&key='.$product_id).'&view=left" target="_blank" title="click to view design"><strong>Left</strong></a> - '

					.' <a href="'.site_url('tshirtecommerce/design.php?idea=1&key='.$product_id).'&view=right" target="_blank" title="click to view design"><strong>Right</strong></a>'

				. '</p>';

		}

	}

}

// design many product
function tshirt_force_individual_cart_items($cart_item_data, $product_id)
{
	$unique_cart_item_key = md5( microtime().rand() );
	$cart_item_data['unique_key'] = $unique_cart_item_key;

	return $cart_item_data;
}

add_filter( 'woocommerce_add_cart_item_data','tshirt_force_individual_cart_items', 10, 2 );

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );
function add_custom_price( $cart_object ) {    
    foreach ( $cart_object->cart_contents as $key => $value ) {
		$data = WC()->session->get( $key.'_designer');
		if (isset($data['design_id']) && $data['design_id'] != '' && $data['design_price'] != '')
		{
			$value['data']->price = $data['design_price'];
		}

    }

}
?>