<?php
/*
 * Plugin Name: Most Commented Posts Widget
 * Plugin URI: http://www.themesindep.com
 * Description: A widget that show the most commented posts
 * Version: 1.0
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */

class TI_Most_Commented extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'ti_most_commented', // Base ID
			__( 'TI Most Commented Posts', 'themetext' ), // Name
			array( 'description' => __( 'Show a list of the most commented posts with comments count', 'themetext' ), ) // Args
		);
		
	}

	
	/**
	 * Front-end display of widget
	**/
	public function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '' );
		$items_num = isset( $instance['items_num'] ) ? esc_attr( $instance['items_num'] ) : '';
		
		/** 
		 * Posts by comment count
		**/
		global $post;
		$ti_most_commented = new WP_Query(
			array(
				'post_type' => 'post',
				'order' => 'DECS',
				'orderby' => 'comment_count',
				'posts_per_page' => $items_num
			)
		);

		if ( $ti_most_commented->have_posts() ) :

			echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title;
			?>
            
            <ul>
			<?php $com = 1; ?>
            <?php while ( $ti_most_commented->have_posts() ) : $ti_most_commented->the_post(); ?>
                <li class="clearfix score-<?php echo $com++;?>">
                	<span>
                    	<i><?php comments_number( '0', '1', '%' ); ?></i>
                    </span>
                    <a href="<?php the_permalink(); ?>">
                    	<?php if ( strlen( $post->post_title ) > 30 ) { echo substr( the_title( $before = '', $after = '', FALSE ), 0, 30 ) . '...'; } else { the_title(); } ?>
                    </a>
                </li>
            <?php endwhile; $com++;  ?>
            </ul>

		    <?php 
			echo $after_widget;
			wp_reset_postdata();

		endif;
		
	}
	
	
	/**
	 * Sanitize widget form values as they are saved
	**/
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();

		/* Strip tags to remove HTML. For text inputs and textarea. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['items_num'] = $new_instance['items_num'];
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	public function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => 'Most Commented',
			'items_num' => '5',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themeText'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
        	<label for="<?php echo $this->get_field_id( 'items_num' ); ?>"><?php _e('Maximum posts to show:', 'themetext'); ?></label>
			<select id="<?php echo $this->get_field_id( 'items_num' ); ?>" name="<?php echo $this->get_field_name( 'items_num' ); ?>" class="widefat">
            	<?php for ( $num=1; $num<=15; $num++ ){ ?>
				<option value="<?php echo $num; ?>" <?php if ( $instance["items_num"] == $num ) echo 'selected="selected"'; ?>><?php echo $num; ?></option>
                <?php } ?>
			</select>
		</p>
	<?php
	}

}
register_widget( 'TI_Most_Commented' );