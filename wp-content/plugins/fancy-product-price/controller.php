<?php
include('class_fpd_prices.php');

$fpd_prices_class = new FPD_Prices();
//var_dump($fpd_prices_class->check_exist(1,1,50));

global $wpdb;

$table_price = $wpdb->prefix . 'fpd_prices';

//
//
//
//$charset_collate = $wpdb->get_charset_collate();
//
//$sql_create = "CREATE TABLE IF NOT EXISTS  $table_price  ( 
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `fancy_product_id` int(11) NOT NULL,
//  `qty` int(11) NOT NULL,
//  `base_price` float(10,2) NOT NULL,
//  `base_price_color` float(10,2) NOT NULL,
//  `front_color_print` float(10,2) NOT NULL,
//  `front_multi_color_print` float(10,2) NOT NULL,
//  `back_color_print` float(10,2) NOT NULL,
//  `back_multi_color_print` float(10,2) NOT NULL,
//  PRIMARY KEY (`id`)
//) $charset_collate;";
//$wpdb->query($sql_create);
//
//$sql_upgrade = " ALTER TABLE  $table_price ADD  `is_color` INT( 1 ) NOT NULL DEFAULT  '0' AFTER  `fancy_product_id` ;
// ALTER TABLE  $table_price DROP  `base_price_color` ;";
//$wpdb->query($sql_upgrade);

/*
 * 
 */
if ($_REQUEST['action'] == 'edit') {
    include('edit_page.php');
} elseif ($_REQUEST['action'] == 'import') {
    include('import_page.php');
} else {
    include('fpd-price-admin.php');
}
