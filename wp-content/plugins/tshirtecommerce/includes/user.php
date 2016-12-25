<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-01-17
 *
 * API user
		- Check login
		- get userinfo
		- create account
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
// get user info
add_action( 'wp_ajax_tshirt_user_is_login', 'tshirt_user_is_login' );
add_action( 'wp_ajax_nopriv_tshirt_user_is_login', 'tshirt_user_is_login' );
function tshirt_user_is_login()
{
	global $wpdb;
	
	$data = array();
	if ( is_user_logged_in() )
	{
		$data['logged'] = true;
		$user 			= wp_get_current_user();
		$data['user'] 	= array(
			'id' => $user->data->ID,
			'username' => $user->data->user_login,
			'email' => $user->data->user_email,
			'key' => md5($user->data->ID)
		);
		
		if (!session_id())
			session_start();
		
		$logged = array(
			'login' => true,
			'email' => $user->data->user_email,
			'id' => $user->data->ID,
			'is_admin' => false,
		);
		if ( is_super_admin() )
		{
			$logged['is_admin'] = true;
		}
		$_SESSION['is_logged'] = $logged;
	}
	else
	{
		$data['logged'] = false;
	}
	
	echo json_encode($data);
	wp_die();
}


// login website
// check user login
add_action( 'wp_ajax_tshirt_login', 'tshirt_login' );
add_action( 'wp_ajax_nopriv_tshirt_login', 'tshirt_login' );
function tshirt_login()
{
	$return = array();
	if( !empty($_REQUEST['username']) && !empty($_REQUEST['password']) && trim($_REQUEST['username']) != '' && trim($_REQUEST['password'] != '') )
	{
		$credentials = array('user_login' => $_REQUEST['username'], 'user_password'=> $_REQUEST['password'], 'remember' => true);
		$loginResult = wp_signon($credentials);
		if ( strtolower(get_class($loginResult)) == 'wp_user' )
		{			
			$return['result']	= true;
			$user 				= $loginResult;
			$return['user'] 	= array(
				'id' => $user->data->ID,
				'username' => $user->data->user_login,
				'email' => $user->data->user_email,
				'key' => md5($user->data->ID)
			);
		}
		elseif ( strtolower(get_class($loginResult)) == 'wp_error' )
		{
			$return['result'] = false;
			$return['error'] = $loginResult->get_error_message();
		}
		else
		{			
			$return['result'] = false;
			$return['error'] = __('An undefined error has ocurred', 'login-with-ajax');
		}
		
	}
	else 
	{				
		$return['result'] = false;
		$return['error'] = __('An undefined error has ocurred', 'login-with-ajax');
	}
	
	echo json_encode($return);
	wp_die();
}

// logout
function tshirt_e_logout() {
    if (isset($_SESSION['is_admin']))
	{
		unset($_SESSION['is_admin']);
	}
	
	if (isset($_SESSION['is_logged']))
	{
		unset($_SESSION['is_logged']);
	}
	
	if (isset($_SESSION['admin']))
	{
		unset($_SESSION['admin']);
	}
}
add_action('wp_logout', 'tshirt_e_logout');


// create account
add_action( 'wp_ajax_tshirt_register', 'tshirt_register' );
add_action( 'wp_ajax_nopriv_tshirt_register', 'tshirt_register' );
function tshirt_register()
{
	$return = array();	 
	if( get_option('users_can_register') )
	{
		$errors = register_new_user($_REQUEST['username'], $_REQUEST['email']);
		if ( !is_wp_error($errors) )
		{
			//Success
			$return['result'] 	= true;
			$return['message'] 	= __('Registration complete. Please check your e-mail.','login-with-ajax');
			//add user to blog if multisite
			if( is_multisite() )
			{
				add_user_to_blog(get_current_blog_id(), $errors, get_option('default_role'));
			}
			// set password
			wp_set_password( $_REQUEST['password'], $errors );
			
			//login
			$credentials = array('user_login' => $_REQUEST['username'], 'user_password'=> $_REQUEST['password'], 'remember' => true);
			$loginResult = wp_signon($credentials);
			if ( strtolower(get_class($loginResult)) == 'wp_user' )
			{	
				$return['result']	= true;
				$user 				= $loginResult;
				$return['user'] 	= array(
					'id' => $user->data->ID,
					'username' => $user->data->user_login,
					'email' => $user->data->user_email,
					'key' => md5($user->data->ID)
				);
			}
		}
		else
		{
			//Something's wrong
			$return['result'] 	= false;
			$return['error'] 	= $errors->get_error_message();
		}
		$return['action'] = 'register';
	}
	else
	{
		$return['result'] = false;
		$return['error'] = __('Registration has been disabled.','login-with-ajax');
	}
	
	echo json_encode($return);
	wp_die();
}

add_action( 'edit_user_profile_update', 'save_e_leve_profile_fields' );
function save_e_leve_profile_fields( $user_id )
{
	if ( is_super_admin() ) {
		update_user_meta( $user_id, 'level', sanitize_text_field( $_POST['level'] ) );
	}
}

add_action( 'show_user_profile', 'e_leve_extra_profile_fields' );
add_action( 'edit_user_profile', 'e_leve_extra_profile_fields' );
function e_leve_extra_profile_fields( $user ) { ?>

	<h3>User Shop Level</h3>
	<table class="form-table">

		<tr>
			<th><label for="twitter">Level</label></th>
			<td>
				<?php 	if ( is_super_admin() ) { ?>
				<input type="text" name="level" value="<?php echo esc_attr( get_the_author_meta( 'level', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please choose level of user.</span>
				<?php } else { ?>
				<input type="text" name="level" value="<?php echo esc_attr( get_the_author_meta( 'level', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">1</span>
				<?php } ?>
			</td>
		</tr>

	</table>
<?php } ?>