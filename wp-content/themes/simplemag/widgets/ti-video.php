<?php
/*
 * Plugin Name: Video Widget
 * Plugin URI: http://www.themesindep.com
 * Description: A widget that show video by page url
 * Version: 1.0
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */

class TI_Video_Embed extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'ti_video_embed', // Base ID
			__( 'TI Video', 'themetext' ), // Name
			array( 'description' => __( 'Add video from Vimeo, YouTube or other video site.', 'themetext' ), ) // Args
		);
		
	}

	
	/**
	 * Front-end display of widget
	**/
	public function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Video' );
		$video_url = isset( $instance['video_url'] ) ? esc_attr( $instance['video_url'] ) : '';
		$video_title = isset( $instance['video_title'] ) ? esc_attr( $instance['video_title'] ) : '';
		
		echo $before_widget;
			
		if ( $title ) echo $before_title . $title . $after_title;
		
        $video_embed =  wp_oembed_get( $video_url );
		echo '<figure class="video-wrapper">' .$video_embed. '</figure>';
        ?>
        
        <p class="video-title"><?php echo $video_title; ?></p>
            
	    <?php 
		echo $after_widget;
		
	}
	
	
	/**
	 * Sanitize widget form values as they are saved
	**/
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();

		/* Strip tags to remove HTML. For text inputs and textarea. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['video_url'] = strip_tags( $new_instance['video_url'] );
		$instance['video_title'] = strip_tags( $new_instance['video_title'] );
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	public function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => 'Video',
			'video_url' => '',
			'video_title' => 'Video Title',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themeText'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'video_url' ); ?>"><?php _e('Video page URL:', 'themetext'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'video_url' ); ?>" name="<?php echo $this->get_field_name( 'video_url' ); ?>" value="<?php echo esc_url($instance['video_url']); ?>" class="widefat" />
		</p>
		<p>
		<p>
			<label for="<?php echo $this->get_field_id( 'video_title' ); ?>"><?php _e('Video Title:', 'themetext'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'video_title' ); ?>" name="<?php echo $this->get_field_name( 'video_title' ); ?>" value="<?php echo $instance['video_title']; ?>" class="widefat" />
		</p>
	<?php
	}

}
register_widget( 'TI_Video_Embed' );