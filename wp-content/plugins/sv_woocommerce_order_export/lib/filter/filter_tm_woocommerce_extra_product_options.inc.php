<?php
/*
Filter Name: TM WooCommerce Extra Product Options support
Filter URI: http://straightvisions.com
Description: Adds meta information as product-childs to the order-output. This filter requires the "items_meta" field to be set to visible.
Version: 1.0.0
Author: Matthias Reuter
Author URI: http://straightvisions.com
Class Name: sv_woocommerce_order_export_filter_tm_woocommerce_extra_product_options
*/

class sv_woocommerce_order_export_filter_tm_woocommerce_extra_product_options{
	public function __construct(){
		$this->fields_add_filter();
		
		// add new export fields
		add_filter('sv_woocommerce_order_export_get_default_export_fields',array($this,'add_default_export_fields'),1);
	}
	public function add_default_export_fields($default){
		return array_merge_recursive($default, array('fields' => array(
			'tm_order_id'			=> array('name' => 'tm_order_id', 'active' => 1),
			'tm_product_id'			=> array('name' => 'tm_product_id', 'active' => 1),
			'tm_variation_id'		=> array('name' => 'tm_variation_id', 'active' => 1),
			'tm_section_label'		=> array('name' => 'tm_section_label', 'active' => 1),
			'tm_name'				=> array('name' => 'tm_name', 'active' => 1),
			'tm_value'				=> array('name' => 'tm_value', 'active' => 1),
			'tm_price'				=> array('name' => 'tm_price', 'active' => 1),
			'tm_quantity'			=> array('name' => 'tm_quantity', 'active' => 1)
		)));
	}
	private function fields_add_filter(){
		add_filter('sv_woocommerce_order_export_filter::items_meta',array($this,'sv_woocommerce_order_export_filter_items_meta'),10,3);
	}
	public function sv_woocommerce_order_export_filter_items_meta($meta,$order,$filter){
		foreach($order['items'] as $item_id => $item){
			$fields = array();
			if(isset($item['item_meta']['_tmcartepo_data'])){
				$meta_array								= unserialize($item['item_meta']['_tmcartepo_data'][0]);
				
				if($meta_array){
					foreach($meta_array as $key => $option){
						$fields['order_id']				= $order['order']->get_order_number();
						$fields['tm_order_id']			= $order['order']->get_order_number();
						$fields['tm_product_id']		= $item['product_id'];
						$fields['tm_variation_id']		= $item['variation_id'];
						$fields['tm_section_label']		= $option['section_label'];
						$fields['tm_name']				= $option['name'];
						$fields['tm_value']				= $option['value'];
						$fields['tm_price']				= $option['price'];
						$fields['tm_quantity']			= $option['quantity'];

						$filter->module->childs->add_child($order['order']->get_order_number(),$item_id,$fields);
					}
				}
			}
		}
		return $meta;
	}
}

?>