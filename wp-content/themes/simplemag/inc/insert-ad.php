<?php
/**
 * Insert Ads in Latest Posts section if
 * Image Ad or Code Ad options are selected.
 * Insert the ad after selected number of posts.
**/

if ( $latest_postnum == $latest_showad ) :

    echo '<article class="grid-4 hentry post-ad">';

        // Image Ad
        if ( get_sub_field( 'latest_insert_ad' ) == 'latest_image_ad_option' ): 
            
            $ad_image = get_sub_field( 'latest_image_ad' );
            $ad_image_link = get_sub_field( 'latest_image_ad_link' );
            if( !empty( $ad_image ) ):
                $size = 'full';
                if ( $ad_image_link != '' ){ echo '<a href="' . $ad_image_link . '">'; }
                    echo wp_get_attachment_image( $ad_image, $size );
                if ( $ad_image_link != '' ){ echo '</a>'; }
            endif;

        // Code Ad
        elseif ( get_sub_field( 'latest_insert_ad' ) == 'latest_code_ad_option' ):
            
           echo '<div class="code-ad">' . get_sub_field( 'latest_code_ad' ) . '</div>';

        endif;

    echo '</article>';

endif; $latest_postnum++;