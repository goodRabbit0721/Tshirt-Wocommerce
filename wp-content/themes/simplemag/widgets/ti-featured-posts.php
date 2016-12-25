<?php
/*
 * Plugin Name: Featured Posts Widget
 * Plugin URI: http://www.themesindep.com
 * Description: A widget that show latest posts
 * Version: 1.1
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */

class TI_Featured_Posts extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'ti_featured_posts', // Base ID
			__( 'TI Featured Posts', 'themetext' ), // Name
			array( 'description' => __( 'Show featured posts, defined in post edit page', 'themetext' ), ) // Args
		);
		
	}

	
	/**
	 * Front-end display of widget
	**/
	public function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Featured Posts' );
		$items_num = isset( $instance['items_num'] ) ? esc_attr( $instance['items_num'] ) : '3';
		$widget_type = isset( $instance['widget_type'] ) ? $instance['widget_type'] : 'flexslider';
		
		
		/** 
		 * Latest Posts
		**/

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
			            
		/** 
		 * Latest Posts
		**/
		global $post;
		$ti_featured_posts = new WP_Query(
			array(
				'post_type' => 'post',
				'meta_key' => 'featured_post_add',
        		'meta_value' => '1',
				'posts_per_page' => $items_num
			)
		);
		?>

		<div class="<?php echo $widget_type; ?>">

			<?php if ( $widget_type == 'flexslider' ) { $slides =' class="slides"'; } else { $slides =' class="clearfix"'; } ?>
            
			<ul<?php echo isset( $slides ) ? $slides : ''; ?>>

			<?php while ( $ti_featured_posts->have_posts() ) : $ti_featured_posts->the_post(); ?>
				<li>
					<?php if ( has_post_thumbnail() ) { ?>
                    	<figure class="entry-image">
                    		<a href="<?php the_permalink(); ?>">
                            	<?php the_post_thumbnail( 'rectangle-size' ); ?>
                            </a>
            			</figure>
                    <?php } elseif( first_post_image() ) { // Set the first image from the editor ?>
						<figure class="entry-image">
                    		<a href="<?php the_permalink(); ?>">
                    			<img src="<?php echo first_post_image(); ?>" class="wp-post-image" alt="<?php the_title(); ?>" />
                    		</a>
                		</figure>
					<?php } ?>
					<?php if ( $widget_type == 'widget-posts-entries' ) { echo '<span>'; the_category(', '); echo '</span>'; } ?>
					<a class="widget-post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>		                        
				</li>
			<?php endwhile; ?>

			<?php wp_reset_postdata();?>

			</ul>
		</div>

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
		$instance['items_num'] = strip_tags( $new_instance['items_num'] );
		$instance['widget_type'] = $new_instance['widget_type'];
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	public function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => 'Featured Posts',
			'items_num' => '3',
			'widget_type' => 'flexslider'
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
		<p>        
        	<input type="radio" id="<?php echo $this->get_field_id( 'flexslider' ); ?>" name="<?php echo $this->get_field_name( 'widget_type' ); ?>" <?php if ($instance["widget_type"] == 'flexslider') echo 'checked="checked"'; ?> value="flexslider" />
            <label for="<?php echo $this->get_field_id( 'flexslider' ); ?>"><?php _e( 'Display posts as Slider', 'themetext' ); ?></label><br />
            
			<input type="radio" id="<?php echo $this->get_field_id( 'widget-posts-entries' ); ?>" name="<?php echo $this->get_field_name( 'widget_type' ); ?>" <?php if ($instance["widget_type"] == 'widget-posts-entries') echo 'checked="checked"'; ?> value="widget-posts-entries" />
            <label for="<?php echo $this->get_field_id( 'widget-posts-entries' ); ?>"><?php _e( 'Display posts as List', 'themetext' ); ?></label><br />
            
            <input type="radio" id="<?php echo $this->get_field_id( 'widget-posts-classic-entries' ); ?>" name="<?php echo $this->get_field_name( 'widget_type' ); ?>" <?php if ($instance["widget_type"] == 'widget-posts-classic-entries') echo 'checked="checked"'; ?> value="widget-posts-classic-entries" />
            <label for="<?php echo $this->get_field_id( 'widget-posts-classic-entries' ); ?>"><?php _e( 'Display posts as Classic List', 'themetext' ); ?></label>
        </p>
	<?php
	}

}
register_widget( 'TI_Featured_Posts' );