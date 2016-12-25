<?php
/*
 * Plugin Name: Latest Category Posts Widget
 * Plugin URI: http://www.themesindep.com
 * Description: A widget that show latest posts from selected category
 * Version: 1.0
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */

class TI_Latest_Category_Posts extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	function __construct() {
		
		parent::__construct(
	 		'ti_latest_cat_posts', // Base ID
			__( 'TI Latest Posts By Category', 'themetext' ), // Name
			array( 'description' => __( 'Show latest posts from selected category', 'themetext' ), ) // Args
		);
		
	}

	
	/**
	 * Front-end display of widget
	**/
	function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Category Name' );
		$items_num = isset( $instance['items_num'] ) ? esc_attr( $instance['items_num'] ) : '3';
		$cat_name = isset( $instance['cat_name'] ) ? esc_attr( $instance['cat_name'] ) : '';
		$widget_type = isset( $instance['widget_type'] ) ? $instance['widget_type'] : 'flexslider';
		
		/**
		 * Latest Posts
		**/
		global $post;
		$ti_latest_cat_posts = new WP_Query(
			array(
				'post_type' => 'post',
				'cat' => $cat_name,
				'posts_per_page' => $items_num,
				'post__not_in' => array( $post->ID ),
				'ignore_sticky_posts' => 1
			)
		);

		if ( $ti_latest_cat_posts->have_posts() ):

			echo $before_widget;
            if ( $title ) echo $before_title . $title . $after_title; 
            ?>

            <div class="<?php echo $widget_type; ?>">

                <?php if ( $widget_type == 'flexslider' ) { $slides =' class="slides"'; } else { $slides =' class="clearfix"'; } ?>

                <ul<?php echo isset( $slides ) ? $slides : ''; ?>>
                    <?php while ( $ti_latest_cat_posts->have_posts() ) : $ti_latest_cat_posts->the_post(); ?>
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
                            <a class="widget-post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <?php
            echo $after_widget;
			wp_reset_postdata();
    
		endif;
		
	}
	
	
	/**
	 * Sanitize widget form values as they are saved
	**/
	function update( $new_instance, $old_instance ) {
		
		$instance = array();

		/* Strip tags to remove HTML. For text inputs and textarea. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['items_num'] = strip_tags( $new_instance['items_num'] );
		$instance['cat_name'] = strip_tags( $new_instance['cat_name'] );
		$instance['widget_type'] = $new_instance['widget_type'];
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => 'Category Name',
			'items_num' => '3',
			'cat_name' => '',
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
        	<label for="<?php echo $this->get_field_id( 'cat_name' ); ?>"><?php _e('Select Category:', 'themeText'); ?></label>
        	<select id="<?php echo $this->get_field_id( 'cat_name' ); ?>" name="<?php echo $this->get_field_name( 'cat_name' ); ?>" class="widefat">
        	<?php
			$categories = get_categories();
			  foreach( $categories as $category ) {
			?>
				<option value="<?php echo $output_categories[] = $category->cat_ID; ?>" <?php if ( $instance["cat_name"] == $category->cat_ID ) echo 'selected="selected"'; ?>>
					<?php echo $output_categories[$category->cat_ID] = $category->name; ?>
                </option>
			<?php } ?>
            </select>
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
register_widget( 'TI_Latest_Category_Posts' );