<?php
/*
 * Plugin Name: About The Site Widget
 * Plugin URI: http://www.themesindep.com
 * Description: A widget that show latest posts
 * Version: 1.1
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */
 
/**
 * Register the Widget
 */
class TI_About_Site extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'ti-about-site', // Base ID
			__( 'TI About The Site', 'themetext' ), // Name
			array( 'description' => __( 'Display info about your magazine. Such as logo, text & social profile links', 'themetext' ), ) // Args
		);
		
	}

	function ti_sp_array( $instance = array() ) {

		return array(
			'rssurl' => array(
				'title' => __('RSS URL', 'themetext'),
				'class' => 'feed',
			),
			'twitter' => array(
				'title' => __('Twitter', 'themetext'),
				'class' => 'twitter',
			),
			'facebook' => array(
				'title' => __('Facebook', 'themetext'),
				'class' => 'facebook',
			),
			'google' => array(
				'title' => __('Google+', 'themetext'),
				'class' => 'google-plus',
			),
			'linkedin' => array(
				'title' => __('LinkedIn', 'themetext'),
				'class' => 'linkedin',
			),
			'pinterest' => array(
				'title' => __('Pinterest', 'themetext'),
				'class' => 'pinterest',
			),
			'bloglovin' => array(
				'title' => __('Bloglovin', 'themetext'),
				'class' => 'bloglovin'
			),
			'tumblr' => array(
				'title' => __('Tumblr', 'themetext'),
				'class' => 'tumblr',
			),
			'instagram' => array(
				'title' => __('Instagram', 'themetext'),
				'class' => 'instagram',
			),
			'flickr' => array(
				'title' => __('Flickr', 'themetext'),
				'class' => 'flickr',
			),
			'youtube' => array(
				'title' => __('YouTube', 'themetext'),
				'class' => 'youtube',
			),
			'behance' => array(
				'title' => __('Behance', 'themetext'),
				'class' => 'behance',
			),
			'dribbble' => array(
				'title' => __('Dribbble', 'themetext'),
				'class' => 'dribbble',
			),
			'soundcloud' => array(
				'title' => __('Sound Cloud', 'themetext'),
				'class' => 'soundcloud',
			),
			'lastfm' => array(
				'title' => __('Last.fm', 'themetext'),
				'class' => 'lastfm',
			),
			'app-net' => array(
				'title' => __('App.net', 'themetext'),
				'class' => 'app-net',
			),
			'apple' => array(
				'title' => __('Apple', 'themetext'),
				'class' => 'apple',
			),
			'windows' => array(
				'title' => __('Windows', 'themetext'),
				'class' => 'windows',
			),
			'android' => array(
				'title' => __('Android', 'themetext'),
				'class' => 'android',
			),
			'stumbleupon' => array(
				'title' => __('StumbleUpon', 'themetext'),
				'class' => 'stumbleupon',
			),
		);
	}
	

	public function widget($args, $instance) {

		extract($args);

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'About The Site' );
		$new_window = isset( $instance['new_window'] ) ? 'target="_blank"' : false;
		$center_icons = isset( $instance['center_icons'] ) ? ' social-center' : false;
		$logo_url = isset( $instance['logo_url'] );

		if (function_exists('icl_translate')) { // If WPML is installed
			$free_text = icl_translate('themetext', "free_text", $instance['free_text']); 
		} else {
			$free_text = $instance['free_text'];
		}

		echo $before_widget;
	

			if ( $title ) echo $before_title . $title . $after_title;
			
			// Display the Logo
			if ( !empty ( $instance['logo_url'] ) ) {
				printf( '<img src="%s" alt="%s" />', esc_url( $instance['logo_url'] ), get_bloginfo( 'name' ) );
			}
			
			// Text about the site
			if ( !empty ( $free_text ) ) {
				printf( '%s', wpautop( $free_text ) );
			}
			
			// Display the social links
			echo '<ul class="social' . $center_icons . ' clearfix">';
			foreach ( $this->ti_sp_array( $instance ) as $key => $data ) {
				if ( !empty ( $instance[$key] ) ) {
					printf( '<li><a href="%s" aria-hidden="true" class="icomoon-%s" %s></a></li>', esc_url( $instance[$key] ), esc_attr( $data['class'] ), $new_window );
				}
			}
			echo '</ul>';
			

		echo $after_widget;

	}
	

	public function update($new_instance, $old_instance) {
		return $new_instance;
	}
	

	public function form($instance) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => 'About The Site',
			'logo_url' => '',
			'free_text' => '',
			'rssurl' => '',
			'twitter' => '',
			'facebook' => '',
			'google' => '',
			'linkedin' => '',
			'pinterest' => '',
			'bloglovin' => '',
			'tumblr' => '',
			'instagram' => '',
			'flickr' => '',
			'vimeo' => '',
			'youtube' => '',
			'behance' => '',
			'dribbble' => '',
			'soundcloud' => '',
			'lastfm' => '',
			'app-net' => '',
			'apple' => '',
			'windows' => '',
			'android' => '',
			'stumbleupon' => '',
			'center_icons' => false,
			'new_window' => false,
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'themetext'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
        <p>
        	<label for="<?php echo $this->get_field_id('logo_url'); ?>"><?php _e('Logo URL', 'themetext'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('logo_url'); ?>" name="<?php echo $this->get_field_name('logo_url'); ?>" value="<?php echo $instance['logo_url']; ?>" class="widefat" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('free_text'); ?>"><?php _e('Short text about the site', 'themetext'); ?>:</label>
			<textarea id="<?php echo $this->get_field_id('free_text'); ?>" name="<?php echo $this->get_field_name('free_text'); ?>" class="widefat" style="height:80px;"><?php echo $instance['free_text']; ?></textarea>
        </p>
        <p>
        	<input type="checkbox" id="<?php echo $this->get_field_id( 'center_icons' ); ?>" name="<?php echo $this->get_field_name( 'center_icons' ); ?>" <?php if( $instance['center_icons'] == true ) echo 'checked'; ?> /> 
			<label for="<?php echo $this->get_field_id( 'center_icons' ); ?>"><?php _e('Center the social icons', 'themetext'); ?></label>
        </p>
        <p>
        	<input type="checkbox" id="<?php echo $this->get_field_id( 'new_window' ); ?>" name="<?php echo $this->get_field_name( 'new_window' ); ?>" <?php if( $instance['new_window'] == true ) echo 'checked'; ?> /> 
			<label for="<?php echo $this->get_field_id( 'new_window' ); ?>"><?php _e('Open social links in new window', 'themetext'); ?></label>
        </p>
		
		<?php foreach ( $this->ti_sp_array( $instance ) as $key => $data ) { ?>
		<p>
			<label for="<?php echo $this->get_field_id($key); ?>"><?php echo $data['title']; ?></label>
			<input type="text" id="<?php echo $this->get_field_id($key); ?>" name="<?php echo $this->get_field_name($key); ?>" value="<?php echo esc_url($instance[$key]); ?>" class="widefat" />
		</p>
        <?php }
		
	}
}
register_widget( 'TI_About_Site' );