<?php
if ($_POST) {
    $fpd_id = mysql_real_escape_string($_POST['fpd_id']);
    if ($fpd_id != '') {

        if ($fpd_prices_class->check_exist_by_id($_POST['fancy_product_id'], $_POST['is_color'], $_POST['qty'], $fpd_id)) {
            $url_redirect = admin_url('admin.php?page=fancy-product-price&action=edit&id=' . $fpd_id . '&error=exist');
        } else {
            $fpd_prices_class->update_price($_POST);
        }
    } else {
        $fpd_id = $fpd_prices_class->insert_price($_POST);
        if ($fpd_id) {
            $url_redirect = admin_url('admin.php?page=fancy-product-price&action=edit&id=' . $fpd_id);
        } else {
            $url_redirect = admin_url('admin.php?page=fancy-product-price&action=edit&error=exist');
        }
    }
} else {

    $fpd_id = $_REQUEST['id'];
}

if (!empty($fpd_id)) {
    $pfd_price = $fpd_prices_class->get_price_by_id($fpd_id);
}
?>
<div class="wrap">
    <?php
    if (isset($pfd_price->id)):
        ?>
        <h2>Edit price</h2>
        <?php
    else:
        ?>
        <h2>Add new price </h2>
    <?php
    endif;
    ?>
    <form name="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="post" autocomplete="off">
        <input type="hidden" name="fpd_id" value="<?php echo isset($pfd_price->id) ? $pfd_price->id : ''; ?>" />
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div id="postbox-container-2" class="postbox-container">
                        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                            <div id="" class="postbox ">
                                <div class="handlediv" title="Click to toggle">
                                    <br></div><h3 class="hndle ">
                                    <span>Prices Infomation</span></h3>
                                <div class="inside">

                                    <div class="form-wrap">
                                        <?php
                                        if ($_GET['error'] == 'exist') {
                                            if ($_GET['id'] != ''):
                                                ?>
                                                <div style="color: red;">Can't update the price! Because it's exist in another row.</div>

                                                <?php
                                            else:
                                                ?>
                                                <div style="color: red;">Can't insert the price! Because it's exist.</div>
                                            <?php
                                            endif;
                                        }
                                        ?>
                                        <div class="" >
                                            <label for="fancy_product_id" >
                                                Fancy Product
                                            </label>
                                            <select id="fancy_product_id"  required="required" required name="fancy_product_id">
                                                <option value="">--Please select a FDP--</option>
                                                <?php
                                                $products = array();
                                                if (fpd_table_exists(FPD_PRODUCTS_TABLE)) {
                                                    $products = $wpdb->get_results("SELECT * FROM " . FPD_PRODUCTS_TABLE . "  ORDER BY ID ASC");
                                                }
                                                foreach ($products as $product) {
                                                    if (isset($pfd_price->fancy_product_id) && $pfd_price->fancy_product_id != '') {
                                                        ?>
                                                        <option <?php echo $pfd_price->fancy_product_id == $product->ID ? 'selected' : ''; ?> value="<?php echo $product->ID; ?>">#<?php echo $product->ID; ?> - <?php echo $product->title; ?></option>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <option value="<?php echo $product->ID; ?>">#<?php echo $product->ID; ?> - <?php echo $product->title; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="" >
                                            <label for="is_color" >
                                                Price Type
                                            </label>
                                            <select id="is_color" name="is_color">
                                                <option value="">--Please select a price type--</option>
                                                <option <?php echo $pfd_price->is_color == '0' ? 'selected' : ''; ?> value="0">Base</option>
                                                <option <?php echo $pfd_price->is_color == '1' ? 'selected' : ''; ?> value="1">Colored</option>
                                            </select>
                                        </div>
                                        <div class="" >
                                            <label for="qty" >
                                                Qty
                                            </label>
                                            <input required="required" required type="text" name="qty" class="input_pfd_price" id="qty" value="<?php echo isset($pfd_price->qty) ? $pfd_price->qty : ''; ?>">
                                        </div>
                                        <div class="" >
                                            <label for="base_price" >
                                                Base Price
                                            </label>
                                            <input required="required" required type="text" name="base_price" class="input_pfd_price" id="base_price" value="<?php echo isset($pfd_price->base_price) ? $pfd_price->base_price : ''; ?>">
                                        </div>
                                        <div class="" >
                                            <label for="front_color_print" >
                                                Front Color Print
                                            </label>
                                            <input required="required" required type="text" name="front_color_print" class="input_pfd_price" id="front_color_print" value="<?php echo isset($pfd_price->front_color_print) ? $pfd_price->front_color_print : ''; ?>">
                                        </div>
                                        <div class="" >
                                            <label for="front_multi_color_print" >
                                                Front Multi Color Print
                                            </label>
                                            <input required="required" required type="text" name="front_multi_color_print" class="input_pfd_price" id="front_multi_color_print" value="<?php echo isset($pfd_price->front_multi_color_print) ? $pfd_price->front_multi_color_print : ''; ?>">
                                        </div>
                                        <div class="" >
                                            <label for="back_color_print" >
                                                Back Color Print
                                            </label>
                                            <input required="required" required type="text" name="back_color_print" class="input_pfd_price" id="back_color_print" value="<?php echo isset($pfd_price->back_color_print) ? $pfd_price->back_color_print : ''; ?>">
                                        </div>
                                        <div class="" >
                                            <label for="back_multi_color_print" >
                                                Back Multi Color Print
                                            </label>
                                            <input required="required" required type="text" name="back_multi_color_print" class="input_pfd_price" id="back_multi_color_print" value="<?php echo isset($pfd_price->back_multi_color_print) ? $pfd_price->back_multi_color_print : ''; ?>">
                                        </div>
                                        <br>
                                        <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Save">
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


<script>
<?php
if (isset($url_redirect)) {
    echo 'window.location = "' . $url_redirect . '";';
}
?>
</script>