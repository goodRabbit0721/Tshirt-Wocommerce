<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-06-11
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
$settings = $GLOBALS['settings'];

$currency_postion = setValue($settings, 'currency_postion', 'left');
?>
<?php if ($currency_postion == 'left') { ?>
<style>
.price-sale-number{float: right;}
.price-old-number{float: right;text-decoration: line-through;}
#product-price-old{float:left;}
</style>
<script type="text/javascript">
var currency_postion = 'left';
</script>
<?php }else { ?>
<style>
.price-sale-number{float: left;}
.price-old-number{float: left;text-decoration: line-through;}
#product-price-old{float:left;}
</style>
<script type="text/javascript">
var currency_postion = 'right';
</script>
<?php } ?>