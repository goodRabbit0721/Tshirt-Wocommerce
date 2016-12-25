<?php
/*
  Plugin Name: Arconix FlexSlider
  Plugin URI: http://www.arconixpc.com/plugins/arconix-flexslider
  Description: A multi-purpose responsive jQuery slider that supports custom post types and responsive themes.

  Author: John Gardner
  Author URI: http://www.arconixpc.com

  Version: 1.0.1

  License: GPLv2 or later
  License URI: http://www.opensource.org/licenses/gpl-license.php
 */

class Arconix_Flexslider_Plugin {

    /**
     * Stores the current version of the plugin.
     *
     * @since   1.0.0
     * @access  private
     * @var     string      $version    Current plugin version
     */
    private $version;

    /**
     * The directory path to the plugin file's includes folder.
     *
     * @since   1.0.0
     * @access  private
     * @var     string      $inc    The directory path to the includes folder
     */
    private $inc;

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     */
    public function __construct() {
        $this->version = '1.0.1';
        $this->inc = trailingslashit( plugin_dir_path( __FILE__ ) . '/includes' );
        $this->load_dependencies();
        $this->load_admin();
    }

    /**
     * Load the required dependencies for the plugin.
     *
     * - Admin loads the backend functionality
     * - Public provides front-end functionality
     * - Dashboard Glancer loads the helper class for the admin dashboard
     *
     * @since   1.0.0
     */
    private function load_dependencies() {
        require_once( $this->inc . 'class-arconix-flexslider-admin.php' );
        require_once( $this->inc . 'class-arconix-flexslider-public.php' );
        require_once( $this->inc . 'class-arconix-flexslider-widget.php' );
    }

    /**
     * Load the Administration portion
     *
     * @since   1.0.0
     */
    private function load_admin() {
        new Arconix_Flexslider_Admin( $this->get_version() );
    }

    /**
     * Get the current version of the plugin
     *
     * @since   1.0.0
     * @return  string  Plugin current version
     */
    public function get_version() {
        return $this->version;
    }
}

/** Vroom vroom */
add_action( 'plugins_loaded', 'arconix_flexslider_plugin_run' );
function arconix_flexslider_plugin_run() {
    new Arconix_Flexslider_Plugin;
}