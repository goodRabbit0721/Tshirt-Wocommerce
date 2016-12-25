<?php
/*
 * Plugin Name: Site Authors
 * Plugin URI: http://www.themesindep.com
 * Description: A widget that displays all site authors and contributors
 * Version: 1.0
 * Author: ThemesIndep
 * Author URI: http://www.themesindep.com
 */

class TI_Authors extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'ti_site_authors', // Base ID
			__( 'TI Site Authors', 'themetext' ), // Name
			array( 'description' => __( 'Display the site authors, editors and contributors', 'themetext' ), ) // Args
		);

	}

	/**
	 * Front-end display of widget
	**/
	public function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Site Authors' );
		$type_admin = isset( $instance['type_admin'] ) ? esc_attr( $instance['type_admin'] ) : false;
		$type_author = isset( $instance['type_author'] ) ? esc_attr( $instance['type_author'] ) : false;
		$type_contributor = isset( $instance['type_contributor'] ) ? esc_attr( $instance['type_contributor'] ) : false;
		$type_editor = isset( $instance['type_editor'] ) ? esc_attr( $instance['type_editor'] ) : false;
		$widget_type = isset( $instance['widget_type'] ) ? $instance['widget_type'] : false;
		
		
		// Admins
		if ( $type_admin ):
			$admin_query = new WP_User_Query(
				array( 'role' => 'administrator', 'orderby' => 'post_count', 'order' => 'DESC' )
			);
			$admin = $admin_query->get_results();
		endif;

		// Authors
		if ( $type_author ):
			$author_query = new WP_User_Query(
				array( 'role' => 'author', 'orderby' => 'post_count', 'order' => 'DESC' )
			);
			$author = $author_query->get_results();
		endif;

		// Contributors
		if ( $type_contributor ):
			$contributor_query = new WP_User_Query(
				array( 'role' => 'contributor', 'orderby' => 'post_count', 'order' => 'DESC' )
			);
			$contributor = $contributor_query->get_results();
		endif;

		// Editors
		if ( $type_editor ):
			$editor_query = new WP_User_Query(
				array( 'role' => 'editor', 'orderby' => 'post_count', 'order' => 'DESC' )
			);
			$editor = $editor_query->get_results();
		endif;

		// Store all as site authors
		$site_authors = array_merge (
			isset( $admin ) ? $admin : array(),
			isset( $author ) ? $author : array(),
			isset( $contributor ) ? $contributor : array(),
			isset( $editor ) ? $editor : array()
		);


		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		?>
        
		<div class="inner">
			<ul class="<?php echo $widget_type; ?>">
			<?php
            foreach ( $site_authors as $author ):
                
                // Get the author ID
                $author_id = $author->ID;
				
                // Retrieve the gravatar image by author email address
                $author_avatar = get_avatar( get_the_author_meta( 'user_email', $author_id ), '78', '', get_the_author_meta( 'display_name', $author_id ) );
                ?>
                
                <li class="clearfix">
                    <a class="author-avatar" href="<?php echo get_author_posts_url( $author_id ); ?>" title="<?php echo get_the_author_meta( 'display_name', $author_id ); ?>" rel="author">
                        <?php echo $author_avatar; ?>
                    </a>
                    <?php if ( $widget_type == 'authors-list' ) { ?>
                        <span class="author-name">
                            <a href="<?php echo get_author_posts_url( $author_id ); ?>" rel="author">
                                <?php
                                $author_name = get_the_author_meta( 'first_name', $author_id );
                                $author_last_name = get_the_author_meta( 'last_name', $author_id );
                        
                                if ( $author_name || $author_last_name ) {
                                    echo '<span class="f-name">' . $author_name . '</span> <span class="l-name">' . $author_last_name . '</span>';
                                } else {
                                    echo get_the_author_meta( 'display_name', $author_id );
                                } ?>
                            </a>
                        </span>
	                <?php } ?>
                </li>
                        
            <?php 
            endforeach;
            wp_reset_postdata(); 
            ?>
            </ul>

            <?php if ( $widget_type == 'carousel' ) { ?>
            <div class="clearfix">
            	<a class="prev carousel-nav" href="#"><i class="icomoon-chevron-left"></i></a>
            	<a class="next carousel-nav" href="#"><i class="icomoon-chevron-right"></i></a>
            </div>
            <?php } ?>
            
		</div>
            
		<?php	
		echo $after_widget;
		
		
	}
	
	
	/**
	 * Sanitize widget form values as they are saved
	**/
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['type_admin'] = $new_instance['type_admin'];
		$instance['type_author'] = $new_instance['type_author'];
		$instance['type_contributor'] = $new_instance['type_contributor'];
		$instance['type_editor'] = $new_instance['type_editor'];
		$instance['widget_type'] = $new_instance['widget_type'];
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	public function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title' => ' Site Authors',
			'type_admin' => false,
			'type_author' => false,
			'type_contributor' => false,
			'type_editor' => false,
			'widget_type' => 'carousel'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'themeText'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<label><?php _e( 'Show:', 'themetext' ); ?></label><br />
			<input type="checkbox" id="<?php echo $this->get_field_id( 'type_admin' ); ?>" name="<?php echo $this->get_field_name( 'type_admin' ); ?>"  value="1" <?php if ($instance["type_admin"] == true) echo 'checked="checked"'; ?> />
     		<label for="<?php echo $this->get_field_id('type_admin'); ?>"><?php _e( 'Administrators', 'themetext' ); ?></label><br />

			<input type="checkbox" id="<?php echo $this->get_field_id( 'type_author' ); ?>" name="<?php echo $this->get_field_name( 'type_author' ); ?>"  value="1" <?php if ($instance["type_author"] == true) echo 'checked="checked"'; ?> />
     		<label for="<?php echo $this->get_field_id('type_author'); ?>"><?php _e( 'Authors', 'themetext' ); ?></label><br />

     		<input type="checkbox" id="<?php echo $this->get_field_id( 'type_contributor' ); ?>" name="<?php echo $this->get_field_name('type_contributor'); ?>"  value="1" <?php if ($instance["type_contributor"] == true) echo 'checked="checked"'; ?> />
     		<label for="<?php echo $this->get_field_id( 'type_contributor' ); ?>"><?php _e( 'Contributors', 'themetext' ); ?></label><br />

     		<input type="checkbox" id="<?php echo $this->get_field_id( 'type_editor' ); ?>" name="<?php echo $this->get_field_name( 'type_editor' ); ?>"  value="1" <?php if ($instance["type_editor"] == true) echo 'checked="checked"'; ?> />
     		<label for="<?php echo $this->get_field_id( 'type_editor' ); ?>"><?php _e( 'Editors', 'themetext' ); ?></label>
    	</p>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id( 'carousel' ); ?>" name="<?php echo $this->get_field_name( 'widget_type' ); ?>" <?php if ($instance["widget_type"] == 'carousel') echo 'checked="checked"'; ?> value="carousel" />
            <label for="<?php echo $this->get_field_id( 'carousel' ); ?>"><?php _e( 'Display as Slider', 'themetext' ); ?></label><br />
            
            <input type="radio" id="<?php echo $this->get_field_id( 'authors-list' ); ?>" name="<?php echo $this->get_field_name( 'widget_type' ); ?>" <?php if ($instance["widget_type"] == 'authors-list') echo 'checked="checked"'; ?> value="authors-list" />
            <label for="<?php echo $this->get_field_id( 'authors-list' ); ?>"><?php _e( 'Display as List', 'themetext' ); ?></label>
        </p>
	<?php
	}

}
register_widget( 'TI_Authors' );