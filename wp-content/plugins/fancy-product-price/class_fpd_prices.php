<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


if (!class_exists('FPD_Prices')) {

    Class FPD_Prices {

        protected $_table;

        public function __construct() {
            global $wpdb;
            $this->_table = $wpdb->prefix . 'fpd_prices';
        }

        public function check_fancybox_id($_fancy_product_id) {
            global $wpdb;
            if (fpd_table_exists(FPD_PRODUCTS_TABLE)) {
                $_sql = "SELECT * FROM " . FPD_PRODUCTS_TABLE . " WHERE ID = '$_fancy_product_id' ";
                return $wpdb->get_row($_sql) != null;
            }
        }

        public function sql_query($params, $_is_update = false) {
            $fpd_id = mysql_real_escape_string($params['fpd_id']);
            $fancy_product_id = mysql_real_escape_string($params['fancy_product_id']);
            $is_color = mysql_real_escape_string($params['is_color']);
            $qty = mysql_real_escape_string($params['qty']);
            $base_price = mysql_real_escape_string($params['base_price']);
            $front_color_print = mysql_real_escape_string($params['front_color_print']);
            $front_multi_color_print = mysql_real_escape_string($params['front_multi_color_print']);
            $back_color_print = mysql_real_escape_string($params['back_color_print']);
            $back_multi_color_print = mysql_real_escape_string($params['back_multi_color_print']);
            if (!$_is_update) {
                return "INSERT INTO $this->_table "
                        . "(fancy_product_id,"
                        . "is_color,"
                        . "qty,"
                        . "base_price,"
                        . "front_color_print,"
                        . "front_multi_color_print,"
                        . "back_color_print,"
                        . "back_multi_color_print) "
                        . "VALUES "
                        . "('$fancy_product_id',"
                        . "'$is_color',"
                        . "'$qty',"
                        . "'$base_price',"
                        . "'$front_color_print',"
                        . "'$front_multi_color_print',"
                        . "'$back_color_print',"
                        . "'$back_multi_color_print')";
            } else {
                return "UPDATE $this->_table  SET"
                        . " fancy_product_id='$fancy_product_id',"
                        . " is_color='$is_color',"
                        . " qty='$qty',"
                        . " base_price='$base_price',"
                        . " front_color_print='$front_color_print',"
                        . " front_multi_color_print='$front_multi_color_print',"
                        . " back_color_print='$back_color_print',"
                        . " back_multi_color_print='$back_multi_color_print'"
                        . " where id = '$fpd_id'  ";
            }
        }

        public function get_price_by_id($fpd_id) {
            global $wpdb;
            $sql = "select * from $this->_table where id=" . $fpd_id;
            return $wpdb->get_row($sql);
        }

        public function insert_price($params) {
            global $wpdb;
            if (!$this->check_fancybox_id($params['fancy_product_id'])) {
                return false;
            }
            if ($this->check_exist($params['fancy_product_id'], $params['is_color'], $params['qty'])) {
                return false;
            }
            if ($wpdb->query($this->sql_query($params, false))) {
                return $wpdb->insert_id;
            }
            return false;
        }

        public function update_price($params) {
            global $wpdb;
            if (!$this->check_fancybox_id($params['fancy_product_id'])) {
                return false;
            }
            if ($this->check_exist_by_id($params['fancy_product_id'], $params['is_color'], $params['qty'], $params['fpd_id'])) {
                return false;
            }
            return $wpdb->query($this->sql_query($params, true));
        }

        public function check_exist_by_id($fancy_product_id, $is_color, $qty, $fpd_id) {
            global $wpdb;
            $_sql = "select * from $this->_table where fancy_product_id='$fancy_product_id' "
                    . " and is_color='$is_color'"
                    . " and qty='$qty' ";
            $row = $wpdb->get_row($_sql);
            if ($row != null) {
                if ($row->id == $fpd_id) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
		public function check_exist_($fancy_product_id, $is_color, $qty) {
            global $wpdb;
            $_sql = "select * from $this->_table where fancy_product_id='$fancy_product_id' "
                    . " and is_color='$is_color'"
                    . " and qty='$qty' ";
            return $wpdb->get_row($_sql);
        }
        public function check_exist($fancy_product_id, $is_color, $qty) {
            global $wpdb;
            $_sql = "select * from $this->_table where fancy_product_id='$fancy_product_id' "
                    . " and is_color='$is_color'"
                    . " and qty='$qty' ";
            return $wpdb->get_row($_sql) != null;
        }

    }

}
