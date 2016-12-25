<?php

class sv_woocommerce_order_export_module_csv{
	public $data				= false;
	private $filter				= false;
	private $alphabet			= array(
									'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
									'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
									);
	private $titles				= false;
	private $orders				= false;
	private $fields				= array(); // builded in this->header()
	private $orders_filtered	= array();
	private $output				= false;
	public $childs				= NULL;
	
	public function __construct(){
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/common/childs.inc.php');
		$this->childs			= new sv_woocommerce_order_export_childs();
	}
	// helpers
	private function get_fields(){
		return $this->fields;
	}
	private function get_column($i){
		if(isset($this->alphabet[$i])){
			return $this->alphabet[$i];
		}else{
			return false;
		}
	}
	private function get_column_count(){
		return count($this->get_fields());
	}
	// process export
	public function build($data){
		$this->data					= $data;
		if(isset($_POST['subscriptions']) && $_POST['subscriptions'] == 'all'){
			$this->titles			= $this->data->get_subscriptions_user_settings();
			$this->globalSettings	= $this->data->get_subscriptions_global_settings();
			$this->orders			= $this->data->get_subscriptions();
		}else{
			$this->titles			= $this->data->get_user_settings();
			$this->globalSettings	= $this->data->get_global_settings();
			$this->orders			= $this->data->get_orders();
		}
		
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/common/filter.inc.php');
		$this->filter = new sv_woocommerce_order_export_filter($this);
		
		$this->filter->set_multiple_data_handler('data_array');
		
		$this->init();
		$this->header();
		$this->content();
		$this->footer();
		$this->shutdown();
	}
	private function init(){
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: application/json; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.json');
	}
	private function header(){
		// set titles
		$i = 0;
		foreach($this->titles['fields'] as $field_id => $field_settings){
			if($this->data->export_field_visible($field_id,$field_settings)){
				$this->fields[]		= $field_id;
			}
		}
	}
	private function content(){
		// run all filters
		foreach($this->orders as $order_id => $order){ // all orders
			$values									= array();
			foreach($this->get_fields() as $field_position => $field_id){ // all active fields
				if(method_exists($this->filter,$field_id)){
					$values[$field_id]		= call_user_func(array($this->filter,$field_id),$order);
				}elseif(
					isset($this->globalSettings['fields'][$field_id]['filter']) &&
					isset($this->data->filters_loaded[$this->globalSettings['fields'][$field_id]['filter']]) &&
					method_exists($this->data->filters_loaded[$this->globalSettings['fields'][$field_id]['filter']],'get_data')
				){
					$values[$field_id]		= $this->data->filters_loaded[$this->globalSettings['fields'][$field_id]['filter']]->get_data($field_id,$order);
				}
				if(isset($this->filter->childs[$order_id]) && count($this->filter->childs[$order_id]) > 0){
					$values['_childs']				= $this->filter->childs[$order_id];
				}
			}
			if(!$this->filter->is_order_stripped($order_id)){
				$this->orders_filtered[$order_id]	= $values;
			}
		}

		// after filters may have cleaned up output, it's time to actually run it.
		echo json_encode($this->orders_filtered);
	}
	private function footer(){
		
	}
	private function shutdown(){
		die();
	}
}

$module = new sv_woocommerce_order_export_module_csv();

?>