<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

?>
<div class=" col-md-7">
	<div class="row">
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>
	<div class=" col-md-10">
	<?php
		if ( has_post_thumbnail() ) {

			//$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
            $image_title 	= '';
			$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	=> $image_title,
				'alt'	=> $image_title,
				'style' => 'width:98%; min-width: 300px;'
				) );
			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}

			echo preg_replace( array( '#srcset=".*?"#', '#sizes=".*?"#' ), array('',''), apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a class="cloud-zoom" href="%s" itemprop="image" id="zoom1" title="%s" rel="" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $post->ID ));
		} else {
			echo preg_replace( array( '#srcset=".*?"#', '#sizes=".*?"#' ), array('',''), apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID ));


		}
	?>


	</div>
	</div>

</div>

<script>

 jQuery(function(){
		jQuery('.thumbnails a').click(function(){
			jQuery('.thumbnails a').removeClass('active');
			jQuery(this).addClass('active');
		});
    });

 </script>
