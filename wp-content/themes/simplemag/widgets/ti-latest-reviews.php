<?php
/*
 * Plugin Name: Latest Reviews Widget
 * Plugin URI: http://www.themesindep.com
 * Description: A widget that show latest posts with reviews
 * Version: 1.0
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */

class TI_Latest_Reviews extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'ti_latest_reviews', // Base ID
			__( 'TI Latest Reviews', 'themetext' ), // Name
			array( 'description' => __( 'Display the latest posts with reviews', 'themetext' ), ) // Args
		);
		
	}

	
	/**
	 * Front-end display of widget
	**/
	public function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Latest Reviews' );
		$items_num = isset( $instance['items_num'] ) ? esc_attr( $instance['items_num'] ) : '5';
		
		/** 
		 * Latest Reviews
		**/
		global $post;
		$ti_latest_reviews = new WP_Query(
			array(
				'post_type' => 'post',
				'meta_key' => 'enable_rating',
				'meta_value' => 1,
				'posts_per_page' => $items_num,
				'post__not_in' => array( $post->ID ),
				'ignore_sticky_posts' => 1
			)
		);

		if ( $ti_latest_reviews->have_posts() ):

			echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title;
			?>
            
	            <ul class="score-box">
	            <?php while ( $ti_latest_reviews->have_posts() ) : $ti_latest_reviews->the_post(); ?>
	                <?php $show_total = apply_filters( 'ti_score_total', '' ); // Call total score calculation function ?>
	                <li class="clearfix">
	                    <span class="total"><?php echo number_format( $show_total, 1, '.', '' ); ?></span>
	                    <a href="<?php the_permalink(); ?>">
	                        <?php if ( strlen( $post->post_title ) > 25 ) { echo substr( the_title( $before = '', $after = '', FALSE ), 0, 25 ) . '...'; } else { the_title(); } ?>
	                    </a>
	                    <div class="score-outer">
	                    	<div class="score-line" style="width:<?php echo number_format( $show_total, 1, '', '' ); ?>%;"><span></span></div>
	                    </div>
	                </li>
	            <?php endwhile; ?>			
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
		$instance['items_num'] = strip_tags( $new_instance['items_num'] );
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	public function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => 'Latest Reviews',
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
			<input type="text" id="<?php echo $this->get_field_id( 'items_num' ); ?>" name="<?php echo $this->get_field_name( 'items_num' ); ?>" value="<?php echo $instance['items_num']; ?>" size="1" />
		</p>
	<?php
	}

}
register_widget( 'TI_Latest_Reviews' );