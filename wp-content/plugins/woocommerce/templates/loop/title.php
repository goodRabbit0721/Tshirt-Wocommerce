<?php
/**
 * Product loop title
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<h3><?php the_title(); ?></h3>
<?php
global $product;

//print_r($product->post->post_date);
$length = get_post_meta($product->id,'_campaign_length',true);

$date = new DateTime();
$date->setTimestamp(get_post_time('U', true));
	
$date->add(new DateInterval('P'.$length.'D'));

$today = new DateTime();
//$postdate = new DateTime("$product->post->post_date");

$interval = $today->diff($date);
echo "<div class='end_date_product'>Ends in ".$interval->format('%a days')."</div>";

?>
