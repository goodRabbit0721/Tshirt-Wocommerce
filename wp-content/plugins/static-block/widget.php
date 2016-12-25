<?php
class static_block_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'static_block_widget',


            __('Static Block Widget', 'static_block_widget_domain'),


            array( 'description' => __( 'Static Block Widget', 'static_block_widget_domain' ), )
        );
    }

    public function widget( $args, $instance ) {
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $details    = apply_filters( 'widget_details', $instance['details'] );
        $type       = apply_filters( 'widget_type', $instance['type'] );
        $id         = apply_filters( 'widget_id', $instance['id'] );
        ob_start();
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
        if ( ! empty( $details ) )
            echo '<div style="margin-bottom: 20px">'.$details.'</div>';
        if($id > 0){
            if($type == 1){
                echo do_shortcode('[static_block_content id="'.$id.'"]');
            }
            else if($type == 2){
                echo do_shortcode('[static_block_thumbnail id="'.$id.'"]');
            }
        }
        echo $args['after_widget'];
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
    }

    public function form( $instance ) {
        if ($instance) {
            $title = $instance[ 'title' ];
            $type = esc_attr($instance[ 'type' ]);
            $id = esc_attr($instance[ 'id' ]);
            $details = esc_textarea($instance[ 'details' ]);
        }
        else {
            $title = __( 'New title', 'static_block_widget_domain' );
            $type = __( '', 'static_block_widget_domain' );
            $id = __( '', 'static_block_widget_domain' );
            $details = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'details' ); ?>" name="<?php echo $this->get_field_name( 'details' ); ?>"><?php echo esc_attr( $details ); ?></textarea>
        <?php
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'DESC',
            'post_type'        => 'static-block',
            'post_status'      => 'publish',
        );
        $posts_array = get_posts( $args );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Static Block Type:' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
                <option value="-1" >Select</option>
                <option value="1" <?php if(esc_attr( $type ) == 1) echo "selected" ?>>Content</option>
                <option value="2" <?php if(esc_attr( $type ) == 2) echo "selected" ?>>Thumbnail</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Static Block ID:' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>">
                <option value="-1" >Select</option>
                <?php
                if ( $posts_array > 0 ){
                    ?>
                    <?php
                    foreach ( $posts_array as $post ) {
                        ?>
                        <option value="<?php echo $post->ID ?>" <?php if(esc_attr( $id ) == $post->ID) echo "selected" ?>><?php echo $post->post_title; ?></option>
                    <?php
                    }
                }
                ?>
            </select>
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['details'] = ( ! empty( $new_instance['details'] ) ) ? $new_instance['details'] : '';
        $instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
        $instance['id'] = ( ! empty( $new_instance['id'] ) ) ? strip_tags( $new_instance['id'] ) : '';
        return $instance;
    }
}

function static_block_load_widget() {
    register_widget( 'static_block_widget' );
}
add_action( 'widgets_init', 'static_block_load_widget' );
