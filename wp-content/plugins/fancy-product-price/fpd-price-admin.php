<?php
$_where = '';
$_url = '';
if (isset($_REQUEST['product_filter']) || isset($_REQUEST['is_color'])) {

    $product_filter = mysql_real_escape_string($_REQUEST['product_filter']);
    $is_color = mysql_real_escape_string($_REQUEST['is_color']);
    $_where = "where 1  ";
    if ($product_filter != '') {
        $_where.= " AND fancy_product_id = '$product_filter'";
        $_url.="&product_filter=$product_filter";
    }
    if ($is_color != '') {
        $_where.= " AND is_color = '$is_color'";
        $_url.="&is_color=$is_color";
    }
}
if ($_POST) {
    if ($_POST["chkid"]) {
		
        foreach ($_POST["chkid"] as $rs) {
			
            $sql = "delete from $table_price where id=" . $rs;
            $wpdb->query($sql);
//mysql_query($sql);
        }
    }
//header("location:".$_POST["doRedirect"]);
//return;
}
include("mypager.php");
$items = mysql_num_rows(mysql_query("SELECT * FROM $table_price $_where ")); // number of total rows in the database

if ($items > 0) {
    $p = new pagination;
    $p->items($items);
    $p->limit(20); // Limit entries per page

    $p->target("admin.php?page=fancy-product-price" . $_url);
    $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
    $p->calculate(); // Calculates what to show
    $p->parameterName('paging');
    $p->adjacents(1); //No. of page away from the current page

    if (!isset($_GET['paging'])) {
        $p->page = 1;
    } else {
        $p->page = $_GET['paging'];
    }

//Query for limit paging
    $limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;
}
$res = $wpdb->get_results("select * from $table_price $_where  ORDER BY id DESC $limit");
?>

<div class="wrap">  
    <?php
    echo "<h2 style='float: left;'>" . __('FPD Prices', 'oscimp_trdom');
    ?>
    <a href="<?php echo admin_url('admin.php?page=fancy-product-price&action=edit'); ?>" class="add-new-h2">Add New</a>

    <?php
    echo "</h2>";
    ?>
    <form name="oscimp_form" method="get" action="<?php echo admin_url('admin.php?page=fancy-product-price'); ?>" onsubmit="return postme()">  

        <p class="search-box">

            <label class="screen-reader-text" for="post-search-input">Search PFD:</label>

            <input name="page" value="fancy-product-price" type="hidden"/>
            <select id="post-search-input" name="product_filter">
                <option value="">--All--</option>
                <?php
                $products = array();
                if (fpd_table_exists(FPD_PRODUCTS_TABLE)) {
                    $products = $wpdb->get_results("SELECT * FROM " . FPD_PRODUCTS_TABLE . "  ORDER BY ID ASC");
                }
                foreach ($products as $product) {
                    if ($product_filter != '') {
                        ?>
                        <option <?php echo $product_filter == $product->ID ? 'selected' : ''; ?> value="<?php echo $product->ID; ?>">#<?php echo $product->ID; ?> - <?php echo $product->title; ?></option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $product->ID; ?>">#<?php echo $product->ID; ?> - <?php echo $product->title; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <select id="post-search-input" name="is_color">
                <option value="">--All--</option>
                <option <?php echo $is_color == '0' ? 'selected' : ''; ?> value="0">Base</option>
                <option <?php echo $is_color == '1' ? 'selected' : ''; ?> value="1">Colored</option>
            </select>
            <input type="submit" id="search-submit"  class="button" value="Filter"></p>
    </form>
	  <form name="oscimp_form" method="post" action="<?php echo admin_url('admin.php?page=fancy-product-price'); ?>" onsubmit="return postme()">  

                
    <div class="tablenav">
        <?php
        if ($res) {
            ?>  
            <div class='tablenav-pages'>
                <?php echo $p->show();  // Echo out the list of paging.    ?>
            </div>
            <div class="alignleft actions"> 
                  <select name="action"> 
                        <option value="" selected="selected">Bulk Actions</option> 
                        <option value="delete">Delete</option> 
                    </select>&nbsp;<input type="submit" value="Apply" name="doaction" id="doaction" class="button-secondary action" /> 
                  
                      <input type="button" value="Export as CSV" onclick="location.href = '<?= get_home_url() ?>/wp-content/plugins/fancy-product-price/export_csv.php';" name="doaction1" id="doaction1" class="button-secondary action" />
                    
                    <input type="hidden" name="doRedirect" value="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" />


            </div>
            <?
            }
            ?>
            <div class="tablenav-pages one-page"><span class="displaying-num">Showing: <?php echo count($res); ?>  of <?php echo $items; ?> row(s)</span></div>
            <br class="clear">

        </div >
        <table class="widefat post fixed fpd_price_table" cellspacing="0"> 
            <thead> 
                <tr> 
                    <th style="padding: 6px 0 5px;    vertical-align: middle;"  scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th> 
                    <th scope="col" id="id" class="manage-column column-id" style="">ID</th> 
                    <th scope="col" id="fancy_product_id" class="manage-column column-fancy_product_id" style="">Fancy Product</th> 
                    <th scope="col" id="is_color" class="manage-column column-is_color" style="">Price Type</th> 
                    <th scope="col" id="qty" class="manage-column column-qty" style="">Qty</th>
                    <th scope="col" id="base_price" class="manage-column column-base_price" style="">Base Price</th> 
                    <th scope="col" id="front_color_print" class="manage-column column-front_color_print" style="">Front Color Print</th> 
                    <th scope="col" id="front_multi_color_print" class="manage-column column-front_multi_color_print" style="">Front Multi Color Print</th> 
                    <th scope="col" id="back_color_print" class="manage-column column-back_color_print" style="">Back Color Print</th> 
                    <th scope="col" id="back_multi_color_print" class="manage-column column-back_multi_color_print" style="">Back Multi Color Print</th>
                    <th scope="col" id="back_multi_color_edit" class="manage-column column-edit" style="">Edit</th>

                </tr> 
            </thead> 

            <tfoot> 
                <tr> 
                <tr> 
                    <th style="padding: 6px 0 5px;    vertical-align: middle;" scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th> 
                    <th scope="col" id="id" class="manage-column column-id" style="">ID</th> 
                    <th scope="col" id="fancy_product_id" class="manage-column column-fancy_product_id" style="">Fancy Product</th> 
                    <th scope="col" id="is_color" class="manage-column column-is_color" style="">Price Type</th> 
                    <th scope="col" id="qty" class="manage-column column-qty" style="">Qty</th>
                    <th scope="col" id="base_price" class="manage-column column-base_price" style="">Base Price</th> 
                    <th scope="col" id="front_color_print" class="manage-column column-front_color_print" style="">Front Color Print</th> 
                    <th scope="col" id="front_multi_color_print" class="manage-column column-front_multi_color_print" style="">Front Multi Color Print</th> 
                    <th scope="col" id="back_color_print" class="manage-column column-back_color_print" style="">Back Color Print</th> 
                    <th scope="col" id="back_multi_color_print" class="manage-column column-back_multi_color_print" style="">Back Multi Color Print</th>
                    <th scope="col" id="back_multi_color_edit" class="manage-column column-edit" style="">Edit</th>

                </tr> 
            </tfoot> 
            <tbody>
                <?php
                if ($res) {
                    $i = 0;

                    foreach ($res as $rs) {
                        ?>
                        <tr>
                            <th style="padding-left: 0px;" scope="row" class="check-column"><input type="checkbox" name="chkid[]" value="<?= $rs->id ?>" /></th> 
                            <td class="post-title column-id"><?= $rs->id ?></td>
                            <td class="post-title column-fancy_product_id"><strong><?php
                                    if (fpd_table_exists(FPD_PRODUCTS_TABLE)) {
                                        $_product = $wpdb->get_row("SELECT * FROM " . FPD_PRODUCTS_TABLE . " WHERE ID = " . $rs->fancy_product_id);
                                        echo "<span class='fpd-item-id'>#" . $_product->ID . " - </span>";
                                        echo "<span class='fpd-product-title'>" . $_product->title . "</span>";
                                    }
                                    ?></strong>
                            </td>
                            <td class="post-title column-base_price_color"><?php
                                if ($rs->is_color == '1') {
                                    echo "Colored";
                                } else {
                                    echo "Base";
                                }
                                ?></td>

                            <td class="post-title column-qty"><?= $rs->qty ?></td>
                            <td class="post-title column-base_price"><?= $rs->base_price ?></td>
                            <td class="post-title column-front_color_print"><?= $rs->front_color_print ?></td>
                            <td class="post-title column-front_multi_color_print"><?= $rs->front_multi_color_print ?></td>
                            <td class="post-title column-back_color_print"><?= $rs->back_color_print ?></td>
                            <td class="post-title column-back_multi_color_print"><?= $rs->back_multi_color_print ?></td>
                            <td class="post-title column-edit"><a href="<?php echo $_SERVER['REQUEST_URI'] . '&action=edit&id=' . $rs->id; ?>">Edit</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="11" class="check-column" align="center" height="50" valign="middle"><strong>No Records</strong></td> 
                    </tr>    
                    <?
                    }
                    ?>
                </tbody>
            </table>
            <div class="tablenav">
                <div class="alignleft actions"> 
                    <a class="button button-primary" href="<?php echo $_SERVER['REQUEST_URI'] . '&action=import'; ?>">Import CSV </a>

                </div>
                <div class='tablenav-pages'>
                    <?php
                    if ($items > 0) {
                        echo $p->show();  // Echo out the list of paging.  
                    }
                    ?>
        </div> 
                </form>  		
    </div>


    <br class="clear" /> 


</div>  
<style>
    .fpd_price_table >tbody>:nth-child(odd) {
        background-color: #f9f9f9;
    }
    .fpd-item-id {
        font-size: 14px;
        opacity: .7;
        font-style: italic;
        line-height: 100%;
    }
</style>
<script language="javascript">


    function postme() {
        if (document.getElementById("action").value == "") {
            return false;
        }
    }

</script>