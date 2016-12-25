<?php
/**
 * Variable product add to cart
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<div class="campaign-add-to-cart">
<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>
	<?php 
		global $product;
		$_design_ids = get_post_meta($product->id,'_design_ids',true);
		
	?>
	<?php if(!empty($_design_ids)&&count($_design_ids)>1): ?>
			<table class="design_style" cellspacing="0">
			<tbody>
				<tr>
						<td class="label"><label class="campaign_option" for="select_style">Choose Item</label></td></tr><tr>
						<td class="value">
							
							<select name='select_style' autocomplete="off" id="select_style"  >
							<?php
								foreach($_design_ids as $_design_id){
									$selected ='';
									$_design_name = get_post_meta($_design_id,'_design_name',true);

									if($product->id==$_design_id) $selected = "selected='selected'";
									echo "<option ".$selected ." value='".get_permalink($_design_id)."'>".$_design_name."</option>";
								}
								  
							?> 
							</select>
							
							</td>
							</tr>
		        			</tbody>
				</table>
				<script type="text/javascript">
							jQuery(document).ready(function () {
								jQuery(document).on('change', '#select_style', function (e) {
									if(jQuery(this).val()!=''){
										document.location.href = jQuery(this).val();
									}
								});
							});
							
							</script>
		<?php endif; ?>
	<?php if( empty( $available_variations ) && false !== $available_variations ) : ?>
		<?php if(empty($_design_ids)): ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
		<?php endif; ?>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label class="campaign_option" for="<?php echo sanitize_title( $attribute_name ); ?>"><?php
						
						if(sanitize_title( $attribute_name )=='style'){
							echo "Choose style";
						}elseif(sanitize_title( $attribute_name )=='size'){
							echo "Choose Size";
						}elseif(sanitize_title( $attribute_name )=='color'){
							echo "Choose Colour";
						}else{
							echo wc_attribute_label( $attribute_name ); 	
						}
						?></label></td> </tr><tr>
						<td class="value">
							<?php
								$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
								wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
								echo end( $attribute_keys ) === $attribute_name ? '<a class="reset_variations" href="#">' . __( 'Clear selection', 'woocommerce' ) . '</a>' : '';
								
								if(sanitize_title( $attribute_name )=='size'){
									echo '<a target="_blank" class="sizing_info" href="/sizing-info">Sizing Info</a>';
								}
							?>
							
							
						</td>
					</tr>
		        <?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap" >
			<?php
				/**
				 * woocommerce_before_single_variation Hook
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				global $product;
				$_campaign_status = get_post_meta($product->id,'_campaign_status',true);
				$_is_campaign = get_post_meta($product->id,'_is_campaign',true);
				if(!($_is_campaign==1&&$_campaign_status!=1)):
					do_action( 'woocommerce_single_variation' );
				endif;
				/**
				 * woocommerce_after_single_variation Hook
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>
</div>
<?php
if($_is_campaign==1&&$_campaign_status!=1):
?>
	<p class="stock out-of-stock"><?php _e( 'This campaign has ended', 'woocommerce' ); ?></p>				
<?php		
endif;
?>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
