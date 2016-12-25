<?php 
/**
 * Additional user profile fields
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/

/* User Profile Settings */
function user_social_profile_fields( $user ) {
?>
    <h3><?php _e('Social Profiles', 'themetext'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th>
            	<label for="sptwitter"><?php _e('Twitter', 'themetext'); ?>
            	</label>
            </th>
            <td>
                <input type="text" name="sptwitter" id="sptwitter" value="<?php echo esc_attr( get_the_author_meta( 'sptwitter', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th>
            	<label for="spfacebook"><?php _e('Facebook', 'themetext'); ?>
            	</label>
            </th>
            <td>
                <input type="text" name="spfacebook" id="spfacebook" value="<?php echo esc_attr( get_the_author_meta( 'spfacebook', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th>
            	<label for="spgoogle"><?php _e('Google+', 'themetext'); ?>
            	</label>
            </th>
            <td>
                <input type="text" name="spgoogle" id="spgoogle" value="<?php echo esc_attr( get_the_author_meta( 'spgoogle', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th>
            	<label for="sppinterest"><?php _e('Pinterest', 'themetext'); ?>
            	</label>
            </th>
            <td>
                <input type="text" name="sppinterest" id="sppinterest" value="<?php echo esc_attr( get_the_author_meta( 'sppinterest', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th>
            	<label for="splinkedin"><?php _e('LinkedIn', 'themetext'); ?>
            	</label>
            </th>
            <td>
                <input type="text" name="splinkedin" id="splinkedin" value="<?php echo esc_attr( get_the_author_meta( 'splinkedin', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="spinstagram"><?php _e('Instagram', 'themetext'); ?>
                </label>
            </th>
            <td>
                <input type="text" name="spinstagram" id="spinstagram" value="<?php echo esc_attr( get_the_author_meta( 'spinstagram', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php }
 
function save_user_social_profile_fields( $user_id ) {
    
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    
    update_user_meta( $user_id, 'sptwitter', $_POST['sptwitter'] );
	update_user_meta( $user_id, 'spfacebook', $_POST['spfacebook'] );
	update_user_meta( $user_id, 'spgoogle', $_POST['spgoogle'] );
	update_user_meta( $user_id, 'sppinterest', $_POST['sppinterest'] );
	update_user_meta( $user_id, 'splinkedin', $_POST['splinkedin'] );
    update_user_meta( $user_id, 'spinstagram', $_POST['spinstagram'] );
}
 
add_action( 'show_user_profile', 'user_social_profile_fields' );
add_action( 'edit_user_profile', 'user_social_profile_fields' );
 
add_action( 'personal_options_update', 'save_user_social_profile_fields' );
add_action( 'edit_user_profile_update', 'save_user_social_profile_fields' );
