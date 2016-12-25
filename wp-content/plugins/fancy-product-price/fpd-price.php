<?php

/*
  Plugin Name: Fancy Product Designer Price
  Plugin URI: http://www.teem8.com.au
  Description: Plugin Custom Prices for fancy-product-designer
  Author: teem8
  Version: 1.0
  Author URI:  http://www.teem8.com.au
 */

function fancy_product_price_admin() {
    global $wpdb;
    include('controller.php');
}

function fancy_product_price_actions() {
    add_menu_page("FPD Prices", "FPD Prices", 'administrator', "fancy-product-price", "fancy_product_price_admin");
}

add_action('admin_menu', 'fancy_product_price_actions');
?>
