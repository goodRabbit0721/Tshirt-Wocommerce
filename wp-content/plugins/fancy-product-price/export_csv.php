<?php

define('BLOCK_LOAD', true);
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );

$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);



$table = 'tee_fpd_prices';
$out = 'fancy_product_id,is_color,qty,base_price,front_color_print,front_multi_color_print,back_color_print,back_multi_color_print';
$res = $wpdb->get_results("select * from $table ORDER BY ID DESC ");

$count = count($res);

if ($count > 0) {
    foreach ($res as $rs) {
        $row = (array) $rs;
        $out.="\n" . $row["fancy_product_id"] . ',' . $row["is_color"] . ',' . $row["qty"] . ',' . $row["base_price"] . ',' . $row["front_color_print"] . ',' . $row["front_multi_color_print"] . ',' . $row["back_color_print"] . ',' . $row["back_multi_color_print"];
    }
}

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($out));
// Output to browser with appropriate mime type, you choose ;)
header("Content-type: text/csv");
//header("Content-type: text/csv");
//header("Content-type: application/csv");
$filename = "export-fpd-prices.csv";
header("Content-Disposition: attachment; filename=$filename");
echo $out;
exit;
?>