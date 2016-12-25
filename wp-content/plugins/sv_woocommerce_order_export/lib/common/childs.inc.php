<?php

	class sv_woocommerce_order_export_childs{
		private $childs			= false;
		
		public function __construct(){
			
		}
		public function get_childs(){
			return $this->childs;
		}
		public function get_childs_by($order_id,$item_id=false){
			if($item_id){
				return $this->childs[$order_id][$item_id];
			}else{
				return (isset($this->childs[$order_id]) ? $this->childs[$order_id] : false);
			}
		}
		public function add_child($order_id,$item_id,$fields){
			$row										= (isset($this->childs[$order_id][$item_id]) ? count($this->childs[$order_id][$item_id]) : 0);
			$this->childs[$order_id][$item_id][$row]	= $fields;
			
			return $row;
		}
		public function update_child($order_id,$item_id,$fields,$row){
			if(isset($this->childs[$order_id][$item_id][$row]) && is_array($this->childs[$order_id][$item_id][$row])){
				$fields									= array_merge($this->childs[$order_id][$item_id][$row],$fields);
			}
			$this->childs[$order_id][$item_id][$row]	= $fields;
			
			return $row;
		}
	}

?>