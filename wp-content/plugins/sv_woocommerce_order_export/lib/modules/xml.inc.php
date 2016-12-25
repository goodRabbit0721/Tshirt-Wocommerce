<?php

class sv_woocommerce_order_export_module_xml{
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
	private $output				= array();
	public $childs				= NULL;
	
	public function __construct(){
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/common/childs.inc.php');
		$this->childs			= new sv_woocommerce_order_export_childs();
		
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/api/zipstream/zipstream.php');
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
		$this->data				= $data;
		$this->titles			= $this->data->get_user_settings();
		$this->globalSettings	= $this->data->get_global_settings();
		$this->orders			= $this->data->get_orders();
		
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

	}
	private function header(){
		// set titles
		foreach($this->titles['fields'] as $field_id => $field_settings){
			if($this->data->export_field_visible($field_id,$field_settings)){
				$this->fields[]		= $field_id;
			}
		}
	}
	private function content(){
		
		// run all filters
		foreach($this->orders as $order_id => $order){ // all orders
			$values										= array();
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
			}
			if(!$this->filter->is_order_stripped($order_id)){
				$this->orders_filtered[$order_id]		= $values;
			}
		}

		// after filters may have cleaned up output, it's time to actually run it.
		foreach($this->orders_filtered as $order_id => $fields){ // all orders
			$xml					= new DOMDocument('1.0', 'UTF-8');
			$xml->formatOutput		= true;
			
			// XML SCHEMA follows OpenTrans 2.1 standard.
			$order																					= $xml->createElement('ORDER');
				// attributes
				$order_version																		= $xml->createAttribute('version');
				$order_version->value																= '2.1';
				$order->appendChild($order_version);
				
				$order_type																			= $xml->createAttribute('type');
				$order_type->value																	= 'standard'; // @todo: Allow express type
				$order->appendChild($order_type);
			
				// order childs
				$orderHeader																		= $order->appendChild($xml->createElement('ORDER_HEADER'));
					// orderHeader childs
					$orderHeaderControlinfo															= $orderHeader->appendChild($xml->createElement('CONTROL_INFO'));
						// orderControlinfo childs
						$orderHeaderControlinfo->appendChild($xml->createElement('GENERATOR_INFO', 'SV WooCommerce Order Export, Author: Matthias Reuter, Contact: http://straightvisions.com'));
						$orderHeaderControlinfo->appendChild($xml->createElement('GENERATION_DATE', date(DATE_ISO8601)));
					$orderHeaderOrderinfo															= $orderHeader->appendChild($xml->createElement('CONTROL_INFO'));
						// orderHeaderOrderinfo childs
						$orderHeaderOrderinfo->appendChild($xml->createElement('ORDER_ID', $order_id));
						$orderHeaderOrderinfo->appendChild($xml->createElement('ORDER_DATE', $fields['order_date']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('DELIVERY_DATE', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('LANGUAGE', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('MIME_ROOT', $fields['']));
						$orderHeaderOrderinfoParties												= $orderHeaderOrderinfo->appendChild($xml->createElement('PARTIES'));
							// orderHeaderOrderinfoParties childs
							$orderHeaderOrderinfoPartiesParty										= $orderHeaderOrderinfoParties->appendChild($xml->createElement('PARTY'));
								// orderHeaderOrderinfoPartiesParty childs
								$orderHeaderOrderinfoPartiesPartyPartyid							= $orderHeaderOrderinfoPartiesParty->appendChild($xml->createElement('PARTY_ID', $_SERVER['HTTP_HOST']));
									// attributes
									$orderHeaderOrderinfoPartiesPartyPartyid_type					= $xml->createAttribute('type');
									$orderHeaderOrderinfoPartiesPartyPartyid_type->value			= 'party_specific'; // @todo: Allow express type
									$orderHeaderOrderinfoPartiesPartyPartyid->appendChild($orderHeaderOrderinfoPartiesPartyPartyid_type);
								$orderHeaderOrderinfoPartiesPartyPartyrole							= $orderHeaderOrderinfoPartiesParty->appendChild($xml->createElement('PARTY_ROLE', 'marketplace'));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('CUSTOMER_ORDER_REFERENCE', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('ORDER_PARTIES_REFERENCE', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('DOCEXCHANGE_PARTIES_REFERENCE', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('CURRENCY', $fields['']));
						$orderHeaderOrderinfo->appendChild($xml->createElement('PAYMENT', $fields['payment_method']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('TERMS_AND_CONDITIONS', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('PARTIAL_SHIPMENT_ALLOWED', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('TRANSPORT', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('INTERNATIONAL_RESTRICTIONS', $fields['']));
						//$orderHeaderOrderinfo->appendChild($xml->createElement('MIME_INFO', $fields['']));
						$orderHeaderOrderinfoRemarks												= $orderHeaderOrderinfo->appendChild($xml->createElement('REMARKS'));
							// orderHeaderOrderinfoRemarks childs
							$orderHeaderOrderinfoRemarks											= $orderHeaderOrderinfoRemarks->appendChild($xml->createElement('REMARKS', $fields['order_status']));
								// attributes
								$orderHeaderOrderinfoRemarks_type									= $xml->createAttribute('type');
								$orderHeaderOrderinfoRemarks_type->value							= 'general'; // @todo: Allow express type
								$orderHeaderOrderinfoRemarks->appendChild($orderHeaderOrderinfoRemarks_type);
							
							
							// order_status
						//$orderHeaderOrderinfo->appendChild($xml->createElement('HEADER_UDX', $fields['']));
				$orderItemlist																		= $order->appendChild($xml->createElement('ORDER_ITEM_LIST'));
					$i																				= 0;
					$amount																			= 0;
					foreach($fields['items_ids'] as $item_ids){
						// orderItemlist childs
						$orderItemlistOrderitem														= $orderItemlist->appendChild($xml->createElement('ORDER_ITEM'));
							// orderItemlistOrderitem childs
							$orderItemlistOrderitem->appendChild($xml->createElement('LINE_ITEM_ID', $fields['items_ids'][$i]));
							$orderItemlistOrderitemProductid										= $orderItemlistOrderitem->appendChild($xml->createElement('PRODUCT_ID'));
								// orderItemlistOrderitemProductid childs
								$orderItemlistOrderitemProductidSupplierpid							= $orderItemlistOrderitemProductid->appendChild($xml->createElement('SUPPLIER_PID', $fields['items_sku'][$i]));
									// attributes
									$orderItemlistOrderitemProductidSupplierpid_type				= $xml->createAttribute('type');
									$orderItemlistOrderitemProductidSupplierpid_type->value			= 'supplier_specific';
									$orderItemlistOrderitemProductidSupplierpid->appendChild($orderItemlistOrderitemProductidSupplierpid_type);
							//$orderItemlistOrderitem->appendChild($xml->createElement('PRODUCT_FEATURES'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('PRODUCT_COMPONENTS'));
							$orderItemlistOrderitem->appendChild($xml->createElement('QUANTITY', $fields['items_quantity'][$i]));
							$orderItemlistOrderitem->appendChild($xml->createElement('ORDER_UNIT', 1));
							//$orderItemlistOrderitem->appendChild($xml->createElement('PRODUCT_PRICE_FIX'));
							$orderItemlistOrderitem->appendChild($xml->createElement('PRICE_LINE_AMOUNT', $fields['items_totals_tax'][$i]));
							//$orderItemlistOrderitem->appendChild($xml->createElement('PARTIAL_SHIPMENT_ALLOWED'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('DELIVERY_DATE'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('PARTIAL_DELIVERY_LIST'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('SOURCING_INFO'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('CUSTOMER_ORDER_REFERENCE'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('ACCOUNTING_INFO'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('SHIPMENT_PARTIES_REFERENCE'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('TRANSPORT'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('INTERNATIONAL_RESTRICTIONS'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('SPECIAL_TREATMENT_CLASS'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('MIME_INFO'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('REMARKS'));
							//$orderItemlistOrderitem->appendChild($xml->createElement('ITEM_UDX'));
						$amount																		= $amount+$fields['items_totals_tax'][$i];
						$i++;
					}
				$orderOrdersummary																	= $order->appendChild($xml->createElement('ORDER_SUMMARY'));
					// orderOrdersummary childs
					$orderOrdersummary->appendChild($xml->createElement('TOTAL_ITEM_NUM',$i));
					$orderOrdersummary->appendChild($xml->createElement('TOTAL_AMOUNT',$amount));
				
			$xml->appendChild($order);
			
			$this->output[$order_id]																= $xml->saveXML();
		}
		
	}
	private function footer(){
		
	}
	private function shutdown(){
		//header('Content-Type: text/xml');
		
		if(count($this->output) > 0){
			//Create ZIP
			$zip = new ZipStream('sv_woocommerce_order_export_'.$_POST['date_range'].'.zip');
			
			foreach($this->output as $order_id => $xml){
				$zip->add_file($order_id.'.xml', $xml);
			}
			
			$zip->finish();
			
			die();
		}else{
			die('no data for that given date range.');
		}
	}
}

$module = new sv_woocommerce_order_export_module_xml();

?>