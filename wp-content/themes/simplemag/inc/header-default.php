<?php
/**
 * Defult Header
 * Centered Logo
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/
global $ti_option;
global $post;
if( function_exists('get_product') ){
    $product = get_product( $post->ID );

}
//$product = wc_get_product( $post->ID );
//$is_fancy_product = get_post_meta( $product->id, '_fancy_product' ,true)=='yes';
//var_dump($product);

?>

<div class="header header-default">
    <?php
	if($post->ID==2167):
	?>
	<a class="logo" href="<?php echo home_url( '/' ); ?>">
        <img src="<?php echo get_template_directory_uri() ?>/images/teem8-logo-2.png" alt="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>"  height="<?php echo $ti_option['site_logo']['height']; ?>" />
    </a><!-- Logo -->
    <?php
	elseif(!$product->is_type( 'variable' )):

	?>
	<a class="logo" href="<?php echo home_url( '/' ); ?>">
        <img src="<?php echo $ti_option['site_logo']['url']; ?>" alt="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>" width="<?php echo $ti_option['site_logo']['width']; ?>" height="<?php echo $ti_option['site_logo']['height']; ?>" />
    </a><!-- Logo -->
	<?php
    else:
	endif;
    // Show or Hide site tagline under the logo based on Theme Options
    if( $ti_option['site_tagline'] == true ) {
    ?>
    <span class="tagline" itemprop="description"><?php bloginfo( 'description' ); ?></span>
    <?php } ?>
</div><!-- .header-default -->