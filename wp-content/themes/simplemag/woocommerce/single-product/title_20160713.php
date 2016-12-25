<?php
/**
 * Single Product title
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
 $is_fancy_product = get_post_meta( $product->id, '_fancy_product' ,true)=='yes';
 if(!$is_fancy_product):
?>
<h1 itemprop="name" class="product_title entry-title product_title_pc"><?php // the_title(); ?></h1>
<?php endif; ?>
<?php
global $post;

?>

<?php // the_content(); ?>