<?php
/*
Filter Name: Filter by product author (currently logged in user)
Filter URI: http://straightvisions.com
Description: strips orders which do not contain products from this author|strips products from other authors|adds a new field: total_filtered|replaces widget output with filtered output
Version: 1.0.0
Author: Matthias Reuter
Author URI: http://straightvisions.com
Class Name: sv_woocommerce_order_export_filter_product_author_logged_in
*/

class sv_woocommerce_order_export_filter_product_author_logged_in{
	private $totals			= array();
	private $totals_tax		= array();
	
	public function __construct(){
		$this->fields_add_filter();
	}
	private function fields_add_filter(){
		// excel
		add_filter('sv_woocommerce_order_export_filter::order_id',array($this,'sv_woocommerce_order_export_filter_order_id'),false,3);
		add_filter('sv_woocommerce_order_export_filter::invoice_id',array($this,'sv_woocommerce_order_export_filter_invoice_id'),false,3);
		add_filter('sv_woocommerce_order_export_filter::total',array($this,'sv_woocommerce_order_export_filter_total'),false,3);
		add_filter('sv_woocommerce_order_export_filter::total_tax',array($this,'sv_woocommerce_order_export_filter_total_tax'),false,3);
		
		// widget
		add_filter('sv_woocommerce_order_export_get_order_objects_stats_total',array($this,'sv_woocommerce_order_export_get_order_objects_stats_total'),false,4);
		add_filter('sv_woocommerce_order_export_trigger_full',function() { return true; });
	}
	public function sv_woocommerce_order_export_filter_order_id($order_id,$order,$filter){
		$contains_items = array();
		foreach($order['items'] as $item_id => $item){
			$product = $filter->data->get_product($item['product_id']);
			
			if($product->post->post_author == get_current_user_id()){
				$contains_items[] = $product->post->post_author;
						
				// set new order total and total tax
				$this->totals[$order_id]			= floatval($this->totals[$order_id])+floatval($item['line_total']);
				$this->totals_tax[$order_id]		= floatval($this->totals_tax[$order_id])+floatval($item['line_tax'])+floatval($item['line_total']);
			}else{
				$filter->strip_item($order_id,$item_id);
			}
		}
		
		if(count($contains_items) === 0){
			$filter->strip_order($order_id);
		}

		return $order_id;
	}
	public function sv_woocommerce_order_export_filter_invoice_id($invoice_ids){
		$invoice_ids	= unserialize($invoice_ids);
		if(is_array($invoice_ids)){
			$invoice_id		= $invoice_ids[get_current_user_id()];
		}
		if($invoice_id){
			return $invoice_id;
		}else{
			return __('n.A.', 'sv_woocommerce_order_export');
		}
	}
	public function sv_woocommerce_order_export_get_order_objects_stats_total($total,$order,$items,$common){
		$total = 0;
		if($items){
			foreach($items as $item_id => $item){
				$product = $common->get_product($item['product_id']);
				if($product->post->post_author == get_current_user_id()){
					$total = $total+round((floatval($item['line_tax'])+floatval($item['line_total'])),2);
				}
			}
		}
		
		return $total;
	}
	public function sv_woocommerce_order_export_filter_total($total,$order,$filter){
		if(isset($this->totals[$order['order']->id]) && $this->totals[$order['order']->id] > 0){
			return $this->totals[$order['order']->id];
		}else{
			return 0;
		}
	}
	public function sv_woocommerce_order_export_filter_total_tax($total_tax,$order,$filter){
		if(isset($this->totals_tax[$order['order']->id]) && $this->totals_tax[$order['order']->id] > 0){
			return $this->totals_tax[$order['order']->id];
		}else{
			return 0;
		}
	}
}

?>