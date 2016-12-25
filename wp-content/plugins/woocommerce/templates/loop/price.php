<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

//print_r($product->post->post_date);
$length = get_post_meta($product->id,'_campaign_length',true);

$date = new DateTime();
$date->setTimestamp(get_post_time('U', true));
	
$date->add(new DateInterval('P'.$length.'D'));

$today = new DateTime();
//$postdate = new DateTime("$product->post->post_date");

$interval = $today->diff($date);
//echo "<div class='end_date_product'>End in ".$interval->format('%a days')."</div>";

?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price"><?php echo $price_html; ?></span>
<?php endif; ?>
