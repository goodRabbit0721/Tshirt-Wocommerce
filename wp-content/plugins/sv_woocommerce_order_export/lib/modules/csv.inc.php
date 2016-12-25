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
		
		$this->init();
		$this->header();
		$this->content();
		$this->footer();
		$this->shutdown();
	}
	private function init(){
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=sv_woocommerce_order_export_'.$_POST['date_range'].'.csv');
		echo "\xEF\xBB\xBF"; // UTF-8 BOM

		// create a file pointer connected to the output stream
		$this->output = fopen('php://output', 'w');
	}
	private function header(){
		// set titles
		$i = 0;
		foreach($this->titles['fields'] as $field_id => $field_settings){
			if($this->data->export_field_visible($field_id,$field_settings)){
				$this->field_titles[]	= ((isset($field_settings['name']) && strlen($field_settings['name']) > 0) ? $field_settings['name'] : $field_id);
				$this->fields[]			= $field_id;
				$i++;
			}
		}
		
		// output the column headings
		fputcsv($this->output, $this->field_titles);
	}
	private function content(){
		
		// run all filters
		foreach($this->orders as $order_id => $order){ // all orders
			$values									= array();
			foreach($this->get_fields() as $field_position => $field_id){ // all active fields
				if(method_exists($this->filter,$field_id)){
					$values[$field_position]		=  preg_replace("/[\r\n]+/", " ",call_user_func(array($this->filter,$field_id),$order));
				}elseif(
					isset($this->globalSettings['fields'][$field_id]['filter']) &&
					isset($this->data->filters_loaded[$this->globalSettings['fields'][$field_id]['filter']]) &&
					method_exists($this->data->filters_loaded[$this->globalSettings['fields'][$field_id]['filter']],'get_data')
				){
					$values[$field_position]		=  preg_replace("/[\r\n]+/", " ",$this->data->filters_loaded[$this->globalSettings['fields'][$field_id]['filter']]->get_data($field_id,$order));
				}
			}
			if(!$this->filter->is_order_stripped($order_id)){
				$this->orders_filtered[$order_id]	= $values;
			}
		}

		// after filters may have cleaned up output, it's time to actually run it.
		$i											= 2;
		foreach($this->orders_filtered as $order_id => $fields){ // all orders
			fputcsv($this->output, $fields);
			
			// childs
			$i										= $this->processChilds($order_id,$i);
		}
	}
	private function processChilds($order_id,$i){
		if(is_array($this->childs->get_childs_by($order_id))){
			foreach($this->childs->get_childs_by($order_id) as $item){
				foreach($item as $options){
					foreach($this->get_fields() as $field_position => $field_id){
						@$out[$field_position]	= preg_replace("/[\r\n]+/", " ",$options[$field_id]);
					}
					
					fputcsv($this->output, $out);
					$i++; // next row
				}
			}
		}
		return $i;
	}
	private function footer(){
		
	}
	private function shutdown(){
		die();
	}
}

$module = new sv_woocommerce_order_export_module_csv();

?>