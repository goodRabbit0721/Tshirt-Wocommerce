<?php
if ($_POST) {
    $counter_update = 0;
    $counter_insert = 0;
    $counter_insert_f = 0;
    $counter_update_f = 0;
    if ($_FILES['csv-file']) {
        $csv_file = $_FILES['csv-file']['tmp_name'];
        $file = fopen($csv_file, "r");

        $i = 1;
        while (!feof($file)) {
            $row_data = fgetcsv($file);
            if ($i == 1) {
                $csv_header = $row_data;
            }

            if ($i > 1 && !empty($row_data) && count($csv_header) == count($row_data)) {
                $params = array(
                    'fancy_product_id' => $row_data[0],
                    'is_color' => $row_data[1],
                    'qty' => $row_data[2],
                    'base_price' => $row_data[3],
                    'front_color_print' => $row_data[4],
                    'front_multi_color_print' => $row_data[5],
                    'back_color_print' => $row_data[6],
                    'back_multi_color_print' => $row_data[7]
                );
				
                $_fpd_price = $fpd_prices_class->check_exist($params['fancy_product_id'], $params['is_color'], $params['qty']);
				
                if (!$fpd_prices_class->check_fancybox_id($params['fancy_product_id'])) {
                    $counter_insert_f++;
                } else {
                    if ($_fpd_price) {
						$_fpd_price = $fpd_prices_class->check_exist_($params['fancy_product_id'], $params['is_color'], $params['qty']);
                        //update
                        $params['fpd_id'] = $_fpd_price->id;
                        if ($fpd_prices_class->update_price($params)) {
                            $counter_update++;
                        } else {
                            $counter_update_f++;
                        }
                    } else {
                        //insert
                        if ($fpd_prices_class->insert_price($params)) {
                            $counter_insert++;
                        } else {
                            $counter_insert_f++;
                        }
                    }
                }
            }
            $i++;
        }
    }
}
?>
<div class="wrap">
    <h2>Import Prices</h2>
    <form name="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="post" autocomplete="off" enctype="multipart/form-data">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div id="postbox-container-2" class="postbox-container">
                        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                            <div id="" class="postbox ">
                                <div class="handlediv" title="Click to toggle">
                                    <br></div><h3 class="hndle ">
                                    <span>Import Infomation</span></h3>
                                <div class="inside">

                                    <div class="form-wrap">
                                        <div class="" >
                                            <label for="back_multi_color_print" >
                                                CSV file
                                            </label>
                                            <input accept=".csv" required="required" required type="file" name="csv-file" class="input_pfd_price" >
                                        </div>
                                        <br>
                                        <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Import">
                                    </div>
                                    <div class="notes">
                                        Please make correct CSV format following the sample: <a target="_blank" href="<?php echo get_home_url(); ?>/wp-content/plugins/fancy-product-price/pricing-format.csv">pricing-format.csv</a>
                                    </div>
                                    <div class="notes">
                                        <?php
                                        if (isset($counter_update)) {
                                            echo "Import counter: <br>";
                                            echo "Inserted: " . $counter_insert . " row(s)<br>";
                                            echo "Updated: " . $counter_update . " row(s)<br>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div></div>
                </div><!-- /post-body -->
                <br class="clear">
            </div><!-- /poststuff -->
    </form>

</div>
<style>
    .input_pfd_price{max-width: 100%;
                     width: 100%;}
</style>

