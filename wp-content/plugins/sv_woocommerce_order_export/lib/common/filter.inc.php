<?php

class sv_woocommerce_order_export_filter{
	private $multiple_data_handler		= 'data_implode';
	public $data						= false;
	private $stripped_orders			= array();
	private $stripped_items				= array();
	public $module						= NULL;
	
	public function __construct($module){
		global $wpdb;
		$this->wpdb						= $wpdb;
		$this->data						= $module->data;
		$this->globalSettings			= $module->globalSettings;
		$this->multiple_data_handler	= (isset($this->globalSettings['settings']['nested']) ? $this->globalSettings['settings']['nested'] : 'data_implode');
		$this->module					= $module;
	}
	// helper methods
	public function is_order_stripped($order_id){
		if(isset($this->stripped_orders[$order_id]) && $this->stripped_orders[$order_id] === true){
			return true;
		}else{
			return false;
		}
	}
	public function is_item_stripped($order_id,$item_id){
		if(isset($this->stripped_items[$order_id][$item_id])){
			return true;
		}else{
			return false;
		}
	}
	public function strip_order($order_id){
		$this->stripped_orders[$order_id]				= true;
	}
	public function strip_item($order_id,$item_id){
		$this->stripped_orders[$order_id][$item_id]		= true;
	}
	public function get_multiple_data_handler(){
		return $this->multiple_data_handler;
	}
	public function set_multiple_data_handler($handler){
		$this->multiple_data_handler = $handler;
	}
	public function data_implode($field,$order){
		// @todo: allow custom implode char via user settings
		$items_combined					= array();
		foreach($order['items'] as $item_id => $item){
			if(!$this->is_item_stripped($order['order']->get_order_number(),$item_id)){
				$items_combined[]		= call_user_func(array($this,$field),$item,$this);
			}
		}
		return implode('|',$items_combined);
	}
	public function data_new_row($field,$order){
		foreach($order['items'] as $item_id => $item){
			if(!$this->is_item_stripped($order['order']->get_order_number(),$item_id)){
				$field_map = array(
					'item_id'					=> 'items_ids',
					'item_name'					=> 'items_name',
					'item_author'				=> 'items_author',
					'item_meta'					=> 'items_meta',
					'item_quantity'				=> 'items_quantity',
					'item_total'				=> 'items_totals',
					'item_total_tax'			=> 'items_totals_tax',
					'item_total_tax_percent'	=> 'items_totals_tax_percent',
					'item_sku'					=> 'items_sku',
					'item_link'					=> 'items_link',
					'item_total_sales'			=> 'items_total_sales',
				);
				
				$this->module->childs->update_child($order['order']->get_order_number(),$item_id,array('order_id' => call_user_func(array($this,'order_id'),$order)),'item_data');
				$this->module->childs->update_child($order['order']->get_order_number(),$item_id,array($field_map[$field] => call_user_func(array($this,$field),$item,$this)),'item_data');
			}
		}
		
		return '';
	}
	public function data_array($field,$order){
		// @todo: allow custom implode char via user settings
		$items_combined					= array();
		foreach($order['items'] as $item_id => $item){
			if(!$this->is_item_stripped($order['order']->get_order_number(),$item_id)){
				$items_combined[]		= call_user_func(array($this,$field),$item,$this);
			}
		}
		return $items_combined;
	}
	// order field methods
	public function order_id($order){
		return apply_filters(__METHOD__,$order['order']->get_order_number(),$order,$this);
	}
	public function invoice_id($order){
		// WooCommerce Germanized
		if(isset($GLOBALS['woocommerce_germanized'])){
			$results		= $this->wpdb->get_results('SELECT post_id,meta_key FROM '.$this->wpdb->postmeta.' WHERE meta_value="'.$order['order']->get_order_number().'"',ARRAY_A);
			foreach($results as $result){
				if($result['meta_key'] == '_invoice_order'){
					$invoice_post_id = $result['post_id'];
				}
			}
			$output = intval(get_post_meta($invoice_post_id,'_invoice_number',true));
		}elseif(get_post_meta($order['order']->get_order_number(),'sv_woo_order_export_invoice_id',true)){
			$output = get_post_meta($order['order']->get_order_number(),'sv_woo_order_export_invoice_id',true);
		}else{
			$output = __('n.A.', 'sv_woocommerce_order_export');
		}
		return apply_filters(__METHOD__,$output);
	}
	public function order_date($order){
		return apply_filters(__METHOD__,$order['order']->post->post_date,$order,$this);
	}
	public function order_status($order){
		return apply_filters(__METHOD__,$order['order']->post->post_status,$order,$this);
	}
	public function payment_method($order){
		return apply_filters(__METHOD__,$order['order']->payment_method,$order,$this);
	}
	public function download_permissions_granted($order){
		return apply_filters(__METHOD__,$order['order']->download_permissions_granted,$order,$this);
	}
	public function billing_first_name($order){
		return apply_filters(__METHOD__,$order['order']->billing_first_name,$order,$this);
	}
	public function billing_last_name($order){
		return apply_filters(__METHOD__,$order['order']->billing_last_name,$order,$this);
	}
	public function billing_full_name($order){
		return apply_filters(__METHOD__,$order['order']->billing_first_name.' '.$order['order']->billing_last_name,$order,$this);
	}
	public function billing_email($order){
		return apply_filters(__METHOD__,$order['order']->billing_email,$order,$this);
	}
	public function billing_phone($order){
		return apply_filters(__METHOD__,$order['order']->billing_phone,$order,$this);
	}
	public function billing_address_1($order){
		return apply_filters(__METHOD__,$order['order']->billing_address_1,$order,$this);
	}
	public function billing_address_2($order){
		return apply_filters(__METHOD__,$order['order']->billing_address_2,$order,$this);
	}
	public function billing_full_address($order){
		return apply_filters(__METHOD__,$order['order']->billing_address_1.' '.$order['order']->billing_address_2,$order,$this);
	}
	public function billing_postcode($order){
		return apply_filters(__METHOD__,$order['order']->billing_postcode,$order,$this);
	}
	public function billing_city($order){
		return apply_filters(__METHOD__,$order['order']->billing_city,$order,$this);
	}
	public function billing_country($order){
		return apply_filters(__METHOD__,$order['order']->billing_country,$order,$this);
	}
	public function shipping_first_name($order){
		return apply_filters(__METHOD__,$order['order']->billing_first_name,$order,$this);
	}
	public function shipping_last_name($order){
		return apply_filters(__METHOD__,$order['order']->shipping_last_name,$order,$this);
	}
	public function shipping_full_name($order){
		return apply_filters(__METHOD__,$order['order']->shipping_address_1.' '.$order['order']->shipping_address_2,$order,$this);
	}
	public function shipping_email($order){
		return apply_filters(__METHOD__,$order['order']->shipping_email,$order,$this);
	}
	public function shipping_address_1($order){
		return apply_filters(__METHOD__,$order['order']->shipping_address_1,$order,$this);
	}
	public function shipping_address_2($order){
		return apply_filters(__METHOD__,$order['order']->shipping_address_2,$order,$this);
	}
	public function shipping_full_address($order){
		return apply_filters(__METHOD__,$order['order']->shipping_address_1.' '.$order['order']->shipping_address_2,$order,$this);
	}
	public function shipping_postcode($order){
		return apply_filters(__METHOD__,$order['order']->shipping_postcode,$order,$this);
	}
	public function shipping_city($order){
		return apply_filters(__METHOD__,$order['order']->shipping_city,$order,$this);
	}
	public function shipping_country($order){
		return apply_filters(__METHOD__,$order['order']->shipping_country,$order,$this);
	}
	public function order_comments($order){
		return apply_filters(__METHOD__,$order['order']->customer_note,$order,$this);
	}
	public function total($order){
		return apply_filters(__METHOD__,$order['order']->get_total(),$order,$this);
	}
	public function total_tax($order){
		return apply_filters(__METHOD__,$order['order']->get_total_tax(),$order,$this);
	}
	// item field methods
	private function item_id($item){
		return apply_filters(__METHOD__,$item['product_id'],$item,$this);
	}
	public function items_ids($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_id',
				$order
			),
			$order
		);
	}
	private function item_total($item){
		return apply_filters(__METHOD__,$item['line_total'],$item,$this);
	}
	public function items_totals($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_total',
				$order
			),
			$order
		);
	}
	private function item_total_tax($item){
		return apply_filters(__METHOD__,floatval($item['line_tax'])+floatval($item['line_total']),$item,$this);
	}
	public function items_totals_tax($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_total_tax',
				$order
			),
			$order
		);
	}
	private function item_total_tax_percent($item){
		return apply_filters(__METHOD__,round((floatval($item['line_tax'])/floatval($item['line_total'])),2),$item,$this);
	}
	public function items_totals_tax_percent($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_total_tax_percent',
				$order
			),
			$order
		);
	}
	private function item_name($item){
		return apply_filters(__METHOD__,$item['name'],$item,$this);
	}
	public function items_name($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_name',
				$order
			),
			$order
		);
	}
	private function item_author($item){
		return apply_filters(__METHOD__,get_post_field('post_author', $item['product_id']),$item,$this);
	}
	public function items_author($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_author',
				$order
			),
			$order
		);
	}
	private function item_meta($item){
		// remove default meta fields
		unset($item['name']);
		unset($item['type']);
		unset($item['item_meta']);
		unset($item['item_meta_array']);
		unset($item['qty']);
		unset($item['tax_class']);
		unset($item['product_id']);
		unset($item['variation_id']);
		unset($item['line_subtotal']);
		unset($item['line_total']);
		unset($item['line_subtotal_tax']);
		unset($item['line_tax']);
		unset($item['line_tax_data']);
		
		$meta = array();
		foreach($item as $meta_name => $meta_value){
			$meta[]	= $meta_name.'='.$meta_value;
		}
		return apply_filters(__METHOD__,implode(';',$meta),$item,$this);
	}
	public function items_meta($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_meta',
				$order
			),
			$order,
			$this
		);
	}
	private function item_quantity($item){
		return apply_filters(__METHOD__,$item['qty'],$item,$this);
	}
	public function items_quantity($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_quantity',
				$order
			),
			$order
		);
	}
	private function item_sku($item){
		$p = $this->data->get_product($item['product_id']);
		
		return apply_filters(__METHOD__,$p->get_sku(),$item,$this);
	}
	public function items_sku($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_sku',
				$order
			),
			$order
		);
	}
	private function item_link($item){
		$p = $this->data->get_product($item['product_id']);
		
		return apply_filters(__METHOD__,$p->get_permalink(),$item,$this);
	}
	public function items_link($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_link',
				$order
			),
			$order
		);
	}
	private function item_total_sales($item){
		$p = $this->data->get_product($item['product_id']);
		
		return apply_filters(__METHOD__,get_post_meta($item['product_id'], 'total_sales', true),$item,$this);
		return apply_filters(__METHOD__,$p->_total_sales,$item,$this);
	}
	public function items_total_sales($order){
		return apply_filters(
			__METHOD__,
			call_user_func(
				array($this,$this->get_multiple_data_handler()),
				'item_total_sales',
				$order
			),
			$order
		);
	}
	
	// subscriptions
	public function subscription_id($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_id'],$subscription,$this);
	}
	public function subscription_parent_order($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_parent_order'],$subscription,$this);
	}
	public function subscription_user_email($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_user_email'],$subscription,$this);
	}
	public function subscription_name($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_name'],$subscription,$this);
	}
	public function subscription_product_id($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_product_id'],$subscription,$this);
	}
	public function subscription_variation_id($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_variation_id'],$subscription,$this);
	}
	public function subscription_total($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_total'],$subscription,$this);
	}
	public function subscription_tax($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_tax'],$subscription,$this);
	}
	public function subscription_has_trial($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_has_trial'],$subscription,$this);
	}
	public function subscription_status($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_status'],$subscription,$this);
	}
	public function subscription_failed_payment_count($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_failed_payment_count'],$subscription,$this);
	}
	public function subscription_completed_payment_count($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_completed_payment_count'],$subscription,$this);
	}
	public function subscription_needs_payment($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_needs_payment'],$subscription,$this);
	}
	public function subscription_start_date($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_start_date'],$subscription,$this);
	}
	public function subscription_end_date($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_end_date'],$subscription,$this);
	}
	public function subscription_trial_end_date($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_trial_end_date'],$subscription,$this);
	}
	public function subscription_next_payment_date($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_next_payment_date'],$subscription,$this);
	}
	public function subscription_last_payment_date($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_last_payment_date'],$subscription,$this);
	}
	public function subscription_is_download_permitted($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_is_download_permitted'],$subscription,$this);
	}
	public function subscription_sign_up_fee($subscription){
		return apply_filters(__METHOD__,$subscription['subscription_sign_up_fee'],$subscription,$this);
	}
	public function subscription_payment_method($subscription){
		return apply_filters(__METHOD__,get_post_meta($subscription['subscription_id'], '_payment_method', true),$subscription,$this);
	}
}

?>