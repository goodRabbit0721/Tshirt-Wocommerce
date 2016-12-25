<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
 $is_fancy_product = get_post_meta( $product->id, '_fancy_product' ,true)=='yes';
 if(!$is_fancy_product):
?>
<div class="price_wrapper" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<p class="price">AUD <?php echo $product->get_price_html(); ?></p>
	<!--<div class="price_note">plus $11 shipping</div>-->

	<meta itemprop="price" content="<?php echo $product->get_price(); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>
<?php endif; ?>