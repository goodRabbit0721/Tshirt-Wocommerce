<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	?>
	<div class="thumbnails  col-md-2 ">
		<h1 itemprop="name" class="entry-title product_title_mb"><?php the_title(); ?></h1>
		<div class="thumbnails_mb">
		<?php
			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'cloud-zoom-gallery' );

				if ( $loop == 0 || $loop % $columns == 0 )
					$classes[] = 'active';

				if ( ( $loop + 1 ) % $columns == 0 )
					$classes[] = 'last';

				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;

				$image_title 	= esc_attr( get_the_title( $attachment_id ) );
				$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
					'title'	=> $image_title,
					 'alt'	=> $image_title,
					 'class' =>"thumbnail_img"
					) );
				
				
				$medium=wp_get_attachment_image_src( $attachment_id,'medium');
				$thumbnail_src=$medium[0];
				$large=wp_get_attachment_image_src( $attachment_id,array(600,600));
				$image_large = $large[0];
				
				$image_class = esc_attr( implode( ' ', $classes ) );
				
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a  href="%s" class="%s" title="%s" rel="useZoom: \'zoom1\', smallImage: \'%s\'">%s</a>', $image_large, $image_class, $image_title,$image_large, $image ), $attachment_id, $post->ID, $image_class );

				$loop++;
			}

		?>
		</div>
	</div>

<style type="text/css">
					   
			.thumbnails-mobile {
				display: none;
			}
			.slider {
				/*width: 100%;
		        margin: 0px auto;*/
			}

			.slick-dotted.slick-slider {
		    	/*margin-bottom: 35px;*/
			}

			.slick-initialized .slick-slide {
				/*padding: 0px 30px;*/
			}

		    .slick-prev:before,
		    .slick-next:before {
		        color: black;
		    }
		    .slick-dots{
		    	/*top: 615px !important;*/
		    }
		    .slick-slider{
		    	display: none;
		    }
		</style>
	<div>
		<ul class="thumbnails-mobile">
		<?php
			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'cloud-zoom-gallery' );

				if ( $loop == 0 || $loop % $columns == 0 )
					$classes[] = 'active';

				if ( ( $loop + 1 ) % $columns == 0 )
					$classes[] = 'last';

				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;

				$image_title 	= esc_attr( get_the_title( $attachment_id ) );
				$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
					'title'	=> $image_title,
					 'alt'	=> $image_title,
					 'class' =>"thumbnail_img"
					) );
				
				
				$medium=wp_get_attachment_image_src( $attachment_id,'medium');
				$thumbnail_src=$medium[0];
				$large=wp_get_attachment_image_src( $attachment_id,array(600,600));
				$image_large = $large[0];
				
				$image_class = esc_attr( implode( ' ', $classes ) );
				
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li><a  href="%s" class="%s" title="%s" rel="useZoom: \'zoom1\', smallImage: \'%s\'">%s</a></li>', $image_large, $image_class, $image_title,$image_large, $image ), $attachment_id, $post->ID, $image_class );

				$loop++;
			}

		?>
		</ul>
		
		<script src="http://dev.teem8.com.au/wp-content/themes/simplemag/slick/slick/slick.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		    jQuery(document).on('ready', function() {
		      
		      jQuery(".thumbnails-mobile").slick({
		        dots: true,
		        infinite: true,
		        centerMode: true,
		        slidesToShow: 1,
		        slidesToScroll: 3,
				arrows: false,
				responsive: [
						    
				    {
				      breakpoint: 375,
				      settings: {
				        arrows: false,
				        dots: true,
        				infinite: true,
				        centerMode: true,
				        centerPadding: '40px',
				        slidesToShow: 1,
				        slidesToScroll: 3,
				      }
				    }
				  ]
		      });

		    });
		</script>
	</div>
					
	<?php
}
