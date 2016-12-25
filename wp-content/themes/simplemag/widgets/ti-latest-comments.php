<?php
/*
 * Plugin Name: Latest Comments
 * Plugin URI: http://www.themesindep.com
 * Description: Display the most latest comments with avatar
 * Version: 1.0
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */

class TI_Latest_Comments extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'ti_latest_comments', // Base ID
			__( 'TI Latest Comments', 'themetext' ), // Name
			array( 'description' => __( 'Display the most latest comments ', 'themetext' ), ) // Args
		);
		
	}

	
	/**
	 * Front-end display of widget
	**/
	public function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Latest Comments' );
		$comments_show = isset( $instance['comments_show'] ) ? esc_attr( $instance['comments_show'] ) : '5';
		
		// Get the comments
		$recent_comments = get_comments( array(
		  'number' => $comments_show,
		  'status' => 'approve',
		  'type' => 'comment'
		) );

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		?>
        
            <ul>
	            <?php 
				$commentnum = 1;
				foreach ($recent_comments as $comment){ ?>
				<li>
	                <header class="clearfix">
	                	<figure>
	                        <a href="<?php echo get_permalink( $comment->comment_post_ID ); ?>">
	                            <?php echo get_avatar( $comment->comment_author_email, '40' ); ?>
	                        </a>
	                    </figure>
	                    <span class="commentnum">
	                        <?php echo $commentnum++; ?>
	                    </span>
	                    <span class="comment-author">
	                        <?php echo( $comment->comment_author ); ?>
	                    </span>
	                    <a class="comment-post" href="<?php echo get_permalink( $comment->comment_post_ID );?>#comment-<?php echo $comment->comment_ID; ?>">
	                        <?php echo get_the_title( $comment->comment_post_ID ); ?>
	                    </a>
	                </header>
	                <div class="comment-text">
	                	<?php echo wp_trim_words( $comment->comment_content, 30 ); ?>
	                </div>
				</li>
				<?php } ?>
			</ul>
		
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
		$instance['comments_show'] = strip_tags( $new_instance['comments_show'] );
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	public function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => 'Latest Comments',
			'comments_show' => '5',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themeText'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comments_show' ); ?>"><?php _e('Comments to show:', 'themetext'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'comments_show' ); ?>" name="<?php echo $this->get_field_name( 'comments_show' ); ?>" value="<?php echo $instance['comments_show']; ?>" size="1" />
		</p>
		<p>
	<?php
	}

}
register_widget( 'TI_Latest_Comments' );