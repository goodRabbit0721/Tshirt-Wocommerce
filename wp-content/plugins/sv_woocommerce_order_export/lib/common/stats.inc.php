<?php

	class sv_woocommerce_order_export_stats{
		private $stats		= array();
	
		public function __construct(){
			$this->stats	= get_option('sv_woocommerce_order_export_stats');
		}
		public function reset_transient($transient){
			unset($this->stats[$transient]);
		}
		public function get_stats(){
			return $this->stats;
		}
		public function set_stats($transient,$group,$subgroup=false,$field,$value,$action=false){
			if($transient){
				if($action == 'add'){
					if(!$subgroup){
						$this->stats[$transient][$group][$field]			= @$this->stats[$transient][$group][$field]+floatval($value);
					}else{
						$this->stats[$transient][$group][$subgroup][$field]	= @$this->stats[$transient][$group][$subgroup][$field]+floatval($value);
					}
				}else{ // just set to new value
					if(!$subgroup){
						$this->stats[$transient][$group][$field]			= floatval($value);
					}else{
						$this->stats[$transient][$group][$subgroup][$field]	= floatval($value);
					}
				}
			}
		}
		public function get_stats_group($transient,$group){
			return (isset($this->stats[$transient][$group]) ? $this->stats[$transient][$group] : false);
		}
		public function get_stats_subgroup($transient,$group,$subgroup=false){
			return (isset($this->stats[$transient][$group][$subgroup]) ? $this->stats[$transient][$group][$subgroup] : false);
		}
		public function get_stats_field($transient,$group,$subgroup=false,$field){
			if(!$subgroup){
				return (isset($this->stats[$transient][$group][$field]) ? $this->stats[$transient][$group][$field] : false);
			}else{
				return (isset($this->stats[$transient][$group][$subgroup][$field]) ? $this->stats[$transient][$group][$subgroup][$field] : false);
			}
		}
	}

?>