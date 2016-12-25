<?php
/**
 * The template for displaying search forms in _s
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.1
 */
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<input type="text" name="s" id="s" value="<?php _e( 'Search...', 'themetext' ); ?>" onfocus="if(this.value=='<?php _e( 'Search...', 'themetext' ); ?>')this.value='';" onblur="if(this.value=='')this.value='<?php _e( 'Search...', 'themetext' ); ?>';" />
    <button type="submit">
    	<i class="icomoon-search"></i>
    </button>
</form>