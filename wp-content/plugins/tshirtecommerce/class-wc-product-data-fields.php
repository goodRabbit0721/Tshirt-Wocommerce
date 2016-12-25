<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @class 		WC_Product_Data_Fields
 * @version		3.0.1
 * @category	Class
 * @author 		TShirt eCommerce Team
 */

if(!class_exists('WC_Product_Data_Fields')){

class WC_Product_Data_Fields {

	public static $plugin_prefix;
	public static $plugin_url;
	public static $plugin_path;
	public static $plugin_basefile;

	private $options_data = false;

	/**
	 * Gets things started by adding an action to initialize this plugin once
	 * WooCommerce is known to be active and initialized
	 */
	public function __construct()
	{
		WC_Product_Data_Fields::$plugin_prefix = 'wc_productdata_options_';
		WC_Product_Data_Fields::$plugin_basefile = plugin_basename(__FILE__);
		WC_Product_Data_Fields::$plugin_url = plugin_dir_url(WC_Product_Data_Fields::$plugin_basefile);
		WC_Product_Data_Fields::$plugin_path = trailingslashit(dirname(__FILE__));
		
		if ($GLOBALS['check_stting'] == true)
		{
			add_action('woocommerce_init', array(&$this, 'init'));
			$GLOBALS['check_stting'] = false;
		}
	}


	/**
	* Gets saved data
	* It is used for displaying the data value in template file
	*
	*/
	public function get_value($post_id, $field_id)
	{

		$meta_value = get_post_meta($post_id, 'wc_productdata_options', true);
		
		if (empty($meta_value[0])) return '';
		
		$meta_value = $meta_value[0];
		if (isset($meta_value[$field_id]))
			return $meta_value[$field_id];
		else
			return '';

	}


	/**
	 * Init WooCommerce Custom Product Data Fields extension once we know WooCommerce is active
	 */
	public function init(){

		add_action('woocommerce_product_write_panel_tabs', array($this, 'product_write_panel_tab'));
		add_action('woocommerce_product_write_panels', array($this, 'product_write_panel'));
		add_action('woocommerce_process_product_meta', array($this, 'product_save_data'), 10, 2);

	}


	/**
	 * Adds a new tab to the Product Data postbox in the admin product interface
	 */
	public function product_write_panel_tab()
	{

		$fields = wc_custom_product_data_fields();

		foreach ($fields as $field){

			if(isset($field['tab_name']) && $field['tab_name'] != '')
			{
				echo '<li class="wc_productdata_options_tab" id="tshirtecommerce_product"><a href="#wc_productdata_options_tab">'.$field['tab_name'].'</a></li>';
			}
		}
	}


	/**
	 * Adds the panel to the Product Data postbox in the product interface
	 */
	public function product_write_panel(){

		global $post;
		
		wp_enqueue_style( 'designer_css_bootstrap' );
		wp_enqueue_script( 'designer_js_bootstrap' );
		wp_enqueue_script( 'designer_api' );
		
		// Pull the field data out of the database
		$available_fields = array();
		$available_fields[] = maybe_unserialize(get_post_meta($post->ID, 'wc_productdata_options', true));

		if($available_fields){

			foreach($available_fields as $available_field){
				echo '<div id="wc_productdata_options_tab" class="panel woocommerce_options_panel">';					

				$fields = wc_custom_product_data_fields();				
				foreach ($fields as $field)
				{
					
					  if(empty($field['tab_name']))
					  {
							WC_Product_Data_Fields::wc_product_data_options_fields($field);
					  }
				}
				//$setting = get_option( 'online_designer' );					
				echo '<p class="form-field _product_link_field "><label for="_product_link" style="width: 500px;">'
					. 	'<a href="javascript:void(0)" key="123" class="btn btn-default" onclick="app.admin.product(this, 0)">Product Blank</a> '
					. 	'<a href="javascript:void(0)" key="123" class="btn btn-primary" onclick="app.admin.product(this, 1)">Admin Design</a> '
					. 	'<a href="javascript:void(0)" key="123" class="btn btn-success" onclick="app.admin.product(this, 2)">Users Design</a> '
					. 	' <a href="javascript:void(0)" key="123" class="btn btn-danger" onclick="app.admin.clear()">Clear</a>'
					. '</label></p>';
				echo '</div>';
			}
		}
	}


	/**
	* Create Fields
	*/
	public function wc_product_data_options_fields($field)
	{
	
		global $thepostid, $post, $woocommerce;

		$fieldtype = isset( $field['type'] ) ? $field['type'] : '';
		$field_id = isset( $field['id'] ) ? $field['id'] : '';

		$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;


		$options_data = maybe_unserialize(get_post_meta($thepostid, 'wc_productdata_options', true));

		switch($fieldtype)
		{
			case 'text':
				$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
				$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
				$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
				$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
				$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
				$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
				$field['type']          = isset( $field['type'] ) ? $field['type'] : 'text';

				$inputval = isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';

				echo '<p class="form-field '.esc_attr($field['id']).'_field '.esc_attr($field['wrapper_class']).'"><label for="'.esc_attr($field['id']).'">'.wp_kses_post($field['label']).'</label><input type="'.esc_attr($field['type']).'" class="'.esc_attr($field['class']).'" name="'.esc_attr($field['name']).'" id="'.esc_attr($field['id']).'" value="'.esc_attr($inputval).'" placeholder="'.esc_attr($field['placeholder']).'"'.(isset($field['style']) ? ' style="'.$field['style'].'"' : '').' /> ';

				if ( ! empty( $field['description'] ) ) 
				{
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) 
					{
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
					} 
					else 
					{
						echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
					}
				}

				echo '</p>';
				break;

			case 'number':
				$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
				$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
				$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
				$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
				$field['value']         = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );
				$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
				$field['type']          = isset( $field['type'] ) ? $field['type'] : 'text';

				$inputval = isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';

				echo '<p class="form-field '.esc_attr($field['id']).'_field '.esc_attr($field['wrapper_class']).'"><label for="'.esc_attr($field['id']).'">'.wp_kses_post($field['label']).'</label><input type="'.esc_attr($field['type']).'" class="'.esc_attr($field['class']).'" name="'.esc_attr($field['name']).'" id="'.esc_attr($field['id']).'" value="'.esc_attr($inputval).'" placeholder="'.esc_attr($field['placeholder']).'"'.(isset($field['style']) ? ' style="'.$field['style'].'"' : '').' /> ';

				if ( ! empty( $field['description'] ) )
				{

					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] )
					{
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
					} 
					else 
					{
						echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
					}

				}

				echo '</p>';
				break;

			case 'textarea':
				if(!$thepostid) $thepostid = $post->ID;
				if(!isset($field['placeholder'])) $field['placeholder'] = '';
				if(!isset($field['class'])) $field['class'] = 'short';
				if(!isset($field['value'])) $field['value'] = get_post_meta($thepostid, $field['id'], true);

				$inputval = isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';

				echo '<p class="form-field '.$field['id'].'_field"><label for="'.$field['id'].'">'.$field['label'].'</label><textarea class="'.$field['class'].'" name="'.$field['id'].'" id="'.$field['id'].'" placeholder="'.$field['placeholder'].'" rows="2" cols="20"'.(isset($field['style']) ? ' style="'.$field['style'].'"' : '').'">'.esc_textarea($inputval).'</textarea>';

				if ( ! empty( $field['description'] ) ) 
				{
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) 
					{
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
					} 
					else 
					{
						echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
					}
				}

				echo '</p>';
				break;


			case 'checkbox':
				$thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
				$field['class']         = isset( $field['class'] ) ? $field['class'] : 'checkbox';
				$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
				$field['value']         = isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';
				$field['cbvalue']       = isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'yes';
				$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];

				echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><input type="checkbox" class="' . esc_attr( $field['class'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . ' /> ';

				if ( ! empty( $field['description'] ) ) 
				{
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] )
					{
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
					} 
					else 
					{
						echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
					}

				}

				echo '</p>';
				break;

			case 'select':
				$thepostid 				  = empty( $thepostid ) ? $post->ID : $thepostid;
				$field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'select short';
				$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
				$field['value'] 		= isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';

				echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['class'] ) . '">';

				foreach ( $field['options'] as $key => $value )
				{
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';

				if ( ! empty( $field['description'] ) )
				{
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] )
					{
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
					}
					else 
					{
						echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
					}
				}
				echo '</p>';
				break;


			case 'radio':
				$thepostid 				= empty( $thepostid ) ? $post->ID : $thepostid;
				$field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'select short';
				$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
				$field['value'] 		= isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';
				$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];

				echo '<fieldset class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><legend style="float:left; width:150px;">' . wp_kses_post( $field['label'] ) . '</legend><ul class="wc-radios" style="width: 25%; float:left;">';

				foreach ( $field['options'] as $key => $value )
				{
					echo '<li style="padding-bottom: 3px; margin-bottom: 0;"><label style="float:none; width: auto; margin-left: 0;"><input
							name="' . esc_attr( $field['name'] ) . '"
							value="' . esc_attr( $key ) . '"
							type="radio"
							class="' . esc_attr( $field['class'] ) . '"
							' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
							/> ' . esc_html( $value ) . '</label>
					</li>';
				}
				echo '</ul>';

				if ( ! empty( $field['description'] ) )
				{
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] )
					{
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
					}
					else
					{
						echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
					}
				}

				echo '</fieldset>';
				break;


			case 'hidden':
				$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;
				$field['value'] = isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';
				$field['class'] = isset( $field['class'] ) ? $field['class'] : '';
				echo '<input type="hidden" class="' . esc_attr( $field['class'] ) . '" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) .  '" /> ';
				break;
				
			case 'image':
				$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;
				$field['value'] = isset( $options_data[0][$field_id] ) ? $options_data[0][$field_id] : '';
				$field['class'] = isset( $field['class'] ) ? $field['class'] : '';
				
				echo '<div class="form-field ' . esc_attr( $field['id'] ) . '_field ' . '' . '">';
				
				echo 	'<input type="hidden" class="' . esc_attr( $field['class'] ) . '" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) .  '" /> ';
				
				echo 	'<div class="row"><div class="col-md-5">';
				echo 		'<div class="img-thumbnail" id="add_designer_product">';
				if ($field['value'] != '')
				{
					$str = explode('::', $field['value']);
					if ( count($str) > 1)
					{			
						echo '<img src="'.$str[1].'" class="img-responsive" alt="">'
							.	'<br /><center>'.$str[0].'</center>';							
					}
				}
				echo 		'</div>';
				echo '</div></div>';
				
				echo '</div>';
				break;
		}
	}


	/**
	 * Saves the data inputed into the product boxes, as post meta data
	 * identified by the name 'wc_productdata_options'
	 *
	 * @param int $post_id the post (product) identifier
	 * @param stdClass $post the post (product)
	 */
	public function product_save_data($post_id, $post)
	{
		$options_value = array();

		/** field name in pairs array **/
		$data_args = array();
		
		foreach(wc_custom_product_data_fields() as $data)
		{
			if (isset($data['id']))
			{
				$name = $data['id'];
				$data_args[$data['id']] = stripslashes($_POST[$data['id']]);
			}
			
		}	
		
		$options_value[] = $data_args;

		// save the data to the database
		update_post_meta($post_id, 'wc_productdata_options', $options_value);
		
		// move cache to folder cart
		if ( isset($data_args['_product_id']) && $data_args['_product_id'] != '' )
		{
			$design_id = $data_args['_product_id'];
			$params = explode(':', $design_id);
			if (count($params) > 1)
			{
				$cache 		= $this->cache();
				$design 	= $cache->get($params[0]);
				if ( $design == null )
				{
					$cache 	= $this->cache('admin');
					$design 	= $cache->get($params[0]);
				}
				
				if ( $design != null )
				{
					if ( isset($design[$params[1]]) && count($design[$params[1]]) > 0)
					{
						$cache 	= $this->cache('cart');
						$key 	= md5($params[0].$params[1]);						
						$cache->set($key, $design[$params[1]]);
					}
				}
			}
		}
	}
	
	private function cache($folder = 'design')
	{
		$path = get_home_path() .'tshirtecommerce/';
		require_once $path . 'includes/libraries/phpfastcache.php';
		phpFastCache::setup("storage", "files");
		phpFastCache::setup("path", $path . 'cache');
		phpFastCache::setup("securityKey", $folder);
		$cache = phpFastCache();
		
		return $cache;
	}


}
}
/**
 * Instantiate Class
 */
$check_stting = true;
$wc_cpdf = new WC_Product_Data_Fields();