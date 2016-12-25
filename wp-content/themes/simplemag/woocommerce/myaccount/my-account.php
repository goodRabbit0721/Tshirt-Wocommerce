<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();
?>
<h1 class="my-account-welcome">G'DAY, <?php echo $current_user->display_name; ?></h1>
<p class="myaccount_user">
	<?php
	printf(
		__( 'Not %1$s? <a href="%2$s">Sign out</a>.', 'woocommerce' ) . ' ',
		$current_user->display_name,
		wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) )
	);

	printf( __( 'Welcome to your account dashboard where you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>.', 'woocommerce' ),
		wc_customer_edit_account_url()
	);
	?>
</p>
<?php do_action( 'woocommerce_before_my_account' ); ?>
<?php
$current_user = wp_get_current_user();
        if (!($current_user instanceof WP_User))
            return;
    
$args = array(
    'author'     =>  $current_user->ID,
    'post_type'  => 'product',
	'posts_per_page' => -1
);

$author_posts = get_posts( $args );


?>


		<?php wc_get_template( 'myaccount/my-products.php' ); ?>




<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<?php wc_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'woocommerce_after_my_account' ); ?>

<style>
.nav.nav-stacked{padding-left: 0px;}
.c-right{
    background: #f5f7f7;
    padding: 20px;
	}
</style>
