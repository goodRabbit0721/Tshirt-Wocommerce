<?php
/**
 * FlexSlider Widget
 *
 * @since 0.1
 */
class Arconix_Flexslider_Widget extends WP_Widget {

    /**
     * Holds widget settings defaults, populated in constructor.
     *
     * @since   0.1
     *
     * @access  protected
     * @var     array   $defaults
     */
    protected $defaults = array();

    /**
     * Constructor. Set the default widget options and create the widget
     *
     * @since   0.1
     * @version 1.0.0
     */
    public function __construct() {

        $this->defaults = array(
            'title'             => '',
            'type'              => 'slider',
            'post_type'         => 'post',
            'category_name'     => '',
            'tag'               => '',
            'posts_per_page'    => '5',
            'orderby'           => 'date',
            'order'             => 'DESC',
            'image_size'        => 'medium',
            'link_image'        => 0,
            'show_caption'      => 'none',
            'show_content'      => 'none'
        );

        $widget_ops = array(
            'classname'         => 'flexslider_widget',
            'description'       => __( 'Responsive slider able to showcase any post type', 'acfs' )
        );

        parent::__construct( 'arconix-flexslider', __( 'Arconix Flexslider', 'acfs' ), $widget_ops );
    }

    /**
     * Registers the widget with the WordPress Widget API.
     *
     * @since 1.0.0
     */
    public static function register() {
        register_widget( __CLASS__ );
    }

    /**
     * Widget Output
     *
     * @since   0.1
     * @version 1.0.1
     *
     * @param   array     $args        Display arguments including before_title, after_title, before_widget, and after_widget.
     * @param   array     $instance    The settings for the particular instance of the widget
     */
    function widget( $args, $instance ) {
        // Load the javascript if it hasn't been overridden
        if( wp_script_is( 'arconix-flexslider-js', 'registered' ) ) wp_enqueue_script( 'arconix-flexslider-js' );

        extract( $args, EXTR_SKIP );

        // Merge with defaults
        $instance = wp_parse_args( $instance, $this->defaults );

        // Before widget (defined by themes)
        echo $before_widget;

        // Title of widget (before and after defined by themes)
        if( ! empty( $instance['title'] ) )
            echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

        // Run our query and output our results
        $fs = new Arconix_FlexSlider();
        $fs->loop( $instance, true );

        // After widget (defined by themes)
        echo $after_widget;
    }

    /**
     * Update a particular instance.
     *
     * @since   0.1
     * @version 1.0.0
     *
     * @param   array $new_instance     New settings for this widget as input by the user via form()
     * @param   array $old_instance     Existing settings for this widget
     *
     * @return  array $new_instance     Settings to save or bool false to cancel saving
     */
    function update( $new_instance, $old_instance ) {

        $new_instance['title'] = strip_tags( $new_instance['title'] );
        $new_instance['posts_per_page'] = absint( $new_instance['posts_per_page'] );
        $new_instance['category_name'] = strip_tags( $new_instance['category_name'] );
        $new_instance['tag'] = strip_tags( $new_instance['tag'] );

        return $new_instance;
    }

    /**
     * Widget form
     *
     * @since   0.1
     * @version 1.0.0
     * @param   array   $instance   Current settings
     */
    function form( $instance ) {

        // Merge with defaults
        $instance = wp_parse_args( $instance, $this->defaults ); ?>

        <!-- Title: Input Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'acfs' ); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>

        <!-- Configuration: Select Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Configuration', 'acfs' ); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
                <?php
                $configs = array( 'slider', 'carousel' );
                foreach( $configs as $config )
                    echo '<option value="' . $config . '" ' . selected( $config, $instance['type'], FALSE ) . '>' . $config . '</option>';
                ?>
            </select>
        </p>

        <!-- Post Type: Select Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post Type', 'acfs' ); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
                <?php
                $types = $this->get_modified_post_type_list();
                foreach( $types as $type )
                    echo '<option value="' . $type . '" ' . selected( $type, $instance['post_type'], FALSE ) . '>' . $type . '</option>';
                ?>
            </select>
        </p>

        <!-- Posts Number: Input Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e( 'Number of items to show:', 'acfs' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text" value="<?php echo $instance['posts_per_page']; ?>" size="3" /></p>
        </p>

        <!-- Category: Input Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'category_name' ); ?>"><?php _e( 'Show posts only from a specific category or comma separated categories (use the slug form)', 'acfs' ); ?>:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'category_name' ); ?>" name="<?php echo $this->get_field_name( 'category_name' ); ?>" value="<?php echo $instance['category_name']; ?>" />
        </p>

        <!-- Tag: Input Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php _e( 'Show posts only from a specific tag or comma separated tags (use the slug form)', 'acfs' ); ?>:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php echo $instance['tag']; ?>" />
        </p>

        <!-- Orderby: Select Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Select Orderby', 'acfs' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
                <?php
                $orderby_items = array( 'ID', 'author', 'title', 'name', 'date', 'modified', 'rand', 'comment_count', 'menu_order' );
                foreach( $orderby_items as $orderby_item )
                    echo '<option value="' . $orderby_item . '" ' . selected( $orderby_item, $instance['orderby'], FALSE ) . '>' . $orderby_item . '</option>';
                ?>
            </select>
        </p>

        <!-- Order: Select Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Select Order', 'acfs' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
                <?php
                $order_items = array( 'ASC', 'DESC' );
                foreach( $order_items as $order_item )
                    echo '<option value="' . $order_item . '" ' . selected( $order_item, $instance['order'], FALSE ) . '>' . $order_item . '</option>';
                ?>
            </select>
        </p>

        <!-- Image Size: Select Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size', 'acfs' ); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
                <?php
                $sizes = $this->get_image_sizes();
                foreach( $sizes as $name => $size )
                    echo '<option value="' . $name . '" ' . selected( $name, $instance['image_size'], FALSE ) . '>' . esc_html( $name ) . ' ( ' . $size['width'] . 'x' . $size['height'] . ' )</option>';
                    echo '<option value="full" ' . selected( "full", $instance['image_size'], FALSE ) . '>' . esc_html( 'full size', 'acfs' ) . '</option>'; ?>
            </select>
        </p>

        <!-- Image Link: Checkbox -->
        <p>
            <input id="<?php echo $this->get_field_id( 'link_image' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_image' ); ?>" value="1"<?php checked( $instance['link_image'] ); ?> />
            <label for="<?php echo $this->get_field_id( 'link_image' ); ?>"><?php _e( 'Hyperlink image to the permalink', 'acfs' ); ?></label>
        </p>

        <!-- Show Caption: Select Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'show_caption' ); ?>"><?php _e( 'Show caption', 'acfs' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'show_caption' ); ?>" name="<?php echo $this->get_field_name( 'show_caption' ); ?>">
            <?php
            $captions = array( 'none', 'post title', 'post content', 'post excerpt', 'image title', 'image caption' );
            foreach( $captions as $caption )
                echo '<option value="' . $caption . '" ' . selected( $caption, $instance['show_caption'], FALSE ) . '>' . $caption . '</option>';
            ?>
            </select>
        </p>

        <!-- Show Content: Select Box -->
        <p>
            <label for="<?php echo $this->get_field_id( 'show_content' ); ?>"><?php _e( 'Show content', 'acfs' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'show_content' ); ?>" name="<?php echo $this->get_field_name( 'show_content' ); ?>">
                <?php
                $content_items = array( 'none', 'excerpt', 'content' );
                foreach( $content_items as $content_item )
                    echo '<option value="' . $content_item . '" ' . selected( $content_item, $instance['show_content'], FALSE ) . '>' . $content_item . '</option>';
                ?>
            </select>
        </p>
        <?php
    }

    /**
     * Returns registered image sizes.
     *
     * Gets the image sizes that have been added via `add_image_size()` and merges
     * them with the WordPress builtin image sizes.
     *
     * @since   0.1
     * @global  array   $_wp_additional_image_sizes     Additionally registered image sizes
     * @return  array                                   Two-dimensional, with width, height and crop sub-keys
     */
    function get_image_sizes() {

        global $_wp_additional_image_sizes;
        $additional_sizes = array();

        $builtin_sizes = array(
            'thumbnail' => array(
                'width' => get_option( 'thumbnail_size_w' ),
                'height' => get_option( 'thumbnail_size_h' ),
                'crop' => get_option( 'thumbnail_crop' ),
            ),
            'medium' => array(
                'width' => get_option( 'medium_size_w' ),
                'height' => get_option( 'medium_size_h' ),
            ),
            'large' => array(
                'width' => get_option( 'large_size_w' ),
                'height' => get_option( 'large_size_h' ),
            )
        );

        if( $_wp_additional_image_sizes )
            $additional_sizes = $_wp_additional_image_sizes;

        return array_merge( $builtin_sizes, $additional_sizes );
    }

    /**
     * Return a modified list of Post Types
     *
     * This function is primarily geared towards developers who do work for clients and want to restrict
     * the post types visible in the widget drop down. The default list includes the 2 WordPress post
     * types plus the post type for the popular plugin Contact Form 7. The list can be filtered to
     * add any other desired post types
     *
     * @example https://gist.github.com/j-gardner/10469315
     *
     * @since   0.1
     * @version 0.5
     *
     * @return  array   $post_types     Modified post_type list
     */
    function get_modified_post_type_list() {
        $post_types = get_post_types( '', 'names' );

        /* Post types we want excluded from the drop down */
        $excl_post_types = apply_filters( 'arconix_flexslider_exclude_post_types',
            array(
                'revision',
                'nav_menu_item',
                'wpcf7_contact_form'
            )
        );

        /** Loop through and exclude the items in the list */
        foreach( $excl_post_types as $excl_post_type ) {
            if( isset( $post_types[$excl_post_type] ) ) unset( $post_types[$excl_post_type] );
        }

        return $post_types;
    }

}