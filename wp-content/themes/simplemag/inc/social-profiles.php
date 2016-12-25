<?php 
/**
 * Social profiles links 
 * Refer to Theme Options, Header tab
 *
 * @package SimpleMag
 * @since 	SimpleMag 2.0
**/

global $ti_option;

$profiles = $ti_option['social_profile_url'];

echo '<ul class="social">
		<li><a href="#" class="icomoon-share social-share-link"></a>
			<ul>';
			foreach ( $profiles as $key => $value ) {
				if ( !empty ( $value ) ) { 
					echo '<li><a href="' . esc_url( $value ) . '" class="icomoon-' . $key . '" target="_blank"></a></li>'; 
				}
			}
echo '		</ul>
		</li>
	</ul>';