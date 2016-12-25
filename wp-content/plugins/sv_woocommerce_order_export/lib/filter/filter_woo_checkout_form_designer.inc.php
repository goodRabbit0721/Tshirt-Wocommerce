<?php
/*
Filter Name: Woo Checkout Form Designer support
Filter URI: http://straightvisions.com
Description: Allows export for additional order info generated through Woo Checkout Form Designer support
Version: 1.0.0
Author: Matthias Reuter
Author URI: http://straightvisions.com
Class Name: sv_woocommerce_order_export_filter_woo_checkout_form_designer
*/

class sv_woocommerce_order_export_filter_woo_checkout_form_designer{
	public function __construct(){
		// add new export fields
		add_filter('sv_woocommerce_order_export_get_default_export_fields',array($this,'add_default_export_fields'),1);
	}
	private function get_checkout_fields(){
		$fields = array_filter(get_option('thwcfd_checkout_fields', array()));
		
		return is_array($fields) ?  $fields : array();
	}
	public function add_default_export_fields($default){
		foreach($this->get_checkout_fields() as $section_name => $section){
			foreach($section as $field_name => $field){
				$fields['fields'][$field_name]['name']				= $field['label'];
				$fields['fields'][$field_name]['active']			= 1;
				$fields['fields'][$field_name]['filter']			= 'sv_woocommerce_order_export_filter_woo_checkout_form_designer';
			}
		}
		return array_merge_recursive($default, $fields);
	}
	public function get_data($field_id,$order){
		return get_post_meta($order['order']->get_order_number(), $field_id, true);
	}
}

?>