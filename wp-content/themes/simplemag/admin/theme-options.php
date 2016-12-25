<?php

/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: https://docs.reduxframework.com
 **/

if (!class_exists('Redux_Framework_sample_config')) {

    class Redux_Framework_sample_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            //$this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field set with compiler=>true is changed.

         * */
        /*
        function compiler_action($options, $css, $changed_values) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r($changed_values); // Values that have changed since the last save
            echo "</pre>";
            print_r($options); //Option values
            print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/../css/theme-styles.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             
        }
        */

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }




            /**
             * DECLARATION OF SECTIONS
            **/


            // General Settings
            $this->sections[] = array(
                'title'     => __('General Settings', 'redux-framework-demo'),
                'icon'      => 'el-icon-cogs',
                'fields'    => array(
                    array(
                        'id'        => 'site_favicon',
                        'type'      => 'media',
                        'title'     => __('Favicon', 'redux-framework-demo'),
                        'subtitle'  => __('Upload your site favicon in .ico format. Default favicon will be used unless you upload your own', 'redux-framework-demo'),
                        'default'   => array('url' => get_template_directory_uri() .'/images/favicon.ico'),                       
                    ),
                    array(
                        'id'        => 'site_retina_favicon',
                        'type'      => 'media',
                        'title'     => __('Retina Favicon', 'redux-framework-demo'),
                        'subtitle'  => __('The Retina version of your Favicon: 144x144px .png file required. Default favicon will be used unless you upload your own', 'redux-framework-demo'),
                        'default'   => array('url' => get_template_directory_uri() .'/images/retina-favicon.png'),                    
                    ),
                    array(
                        'id'        => 'site_sidebar_fixed',
                        'type'      => 'switch',
                        'title'     => __('Fixed Sidebar', 'redux-framework-demo'),
                        'subtitle'  => __('Make sidebar fixed site wide', 'redux-framework-demo'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'site_custom_gallery',
                        'type'      => 'switch',
                        'title'     => __('Custom Gallery', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable theme custom WordPress gallery', 'redux-framework-demo'),
                        'default'   => false,
                    ),
					array(
                        'id'        => 'site_carousel_height',
                        'type'      => 'text',
                        'title'     => __('Gallery Carousel Height', 'redux-framework-demo'),
                        'subtitle'  => __('Set the height of the gallery carousel. Applies to Posts Carousel section and Gallery Format post.', 'redux-framework-demo'),
                        'desc'      => __('After changing the height you need to run the Force Regenerate Thumbnails plugin.', 'redux-framework-demo'),
                        'validate'  => 'numeric',
                        'default'   => '580',
                    ),
                    array(
                        'id'        => 'site_page_comments',
                        'type'      => 'switch',
                        'title'     => __('Comments in static pages', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable comments in all static pages.', 'redux-framework-demo'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'copyright_text',
                        'type'      => 'textarea',
                        'title'     => __('Footer Text', 'redux-framework-demo'),
                        'subtitle'  => __('Your site footer copyright text', 'redux-framework-demo'),
                        'default'   => 'Powered by WordPress. <a href="http://www.themesindep.com">Created by ThemesIndep</a>',
                    ),
                ),
            );

            
            // Header
            $this->sections[] = array(
                'icon'      => 'el-icon-eye-open',
                'title'     => __('Header', 'redux-framework-demo'),
                'heading'   => __('Site Header Otions', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'site_logo',
                        'type'      => 'media',
                        'title'     => __('Site Logo', 'redux-framework-demo'),
                        'subtitle'  => __('Upload your site logo. Default logo will be used unless you upload your own', 'redux-framework-demo'),
                        'default'   => array(
                            'url'   => get_template_directory_uri() .'/images/logo.png',
                            'width' => '237',
                            'height' => '60',
                        )
                    ),
                    array(
                        'id'        => 'site_tagline',
                        'type'      => 'switch',
                        'title'     => __('Site Tagline', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the tagline under the logo', 'redux-framework-demo'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'site_top_strip',
                        'type'      => 'switch',
                        'title'     => __('Top Strip', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the top strip', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'site_main_menu',
                        'type'      => 'switch',
                        'title'     => __('Main Menu', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the main menu', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'site_mega_menu',
                        'type'      => 'switch',
                        'title'     => __('Mega Menu', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the mega menu', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'site_fixed_menu',
                        'type'      => 'image_select',
                        'title'     => __('Fixed Element', 'redux-framework-demo'),
                        'subtitle'  => __('Select header fixed element:<br />None, Top Strip, Main Menu', 'redux-framework-demo'),
                        'options'   => array(
                            '1' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-fixed-none.png'),
                            '2' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-fixed-top-menu.png'),
                            '3' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-fixed-main-menu.png'),
                        ), 
                        'default' => '1'
                    ),
                    array(
                        'id'        => 'site_header',
                        'type'      => 'image_select',
                        'title'     => __('Header Type', 'redux-framework-demo'),
                        'subtitle'  => __('Select header type:<br />Logo<br />Logo, social profiles and search<br /> Logo and Ad Unit', 'redux-framework-demo'),
                        'desc'      => __('To add the ad unit click on the Ad Units tab', 'redux-framework-demo'),
                        'options'   => array(
                            'header_default' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-logo-centered.png'),
                            'header_search' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-logo-social-search.png'),
                            'header_banner' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-logo-ad.png'),
                        ), 
                        'default' => 'header_default'
                    ),
                    
                    array(
                        'id'        => 'top_social_profiles',
                        'type'      => 'switch',
                        'title'     => __('Top Strip Social Profiles', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable top strip socical profiles', 'redux-framework-demo'),
                    ),
                        array(
                        'id'        => 'social_profile_url',
                        'title'     => __('Social Profiles URLs', 'redux-framework-demo'),
                        'subtitle'  => __('Enter full URLs of your social profiles', 'redux-framework-demo'),
                        'required'  => array('top_social_profiles', '=', '1'),
                        'type'      => 'text',
						'placeholder'      => '',
                        'options'   => array(
                            'feed' => 'RSS Feed',
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'google-plus' => 'Google+',
                            'linkedin' => 'LinkedIn',
                            'pinterest' => 'Pinterest',
                            'bloglovin' => 'Bloglovin',
                            'tumblr' => 'Tumblr',
                            'instagram' => 'Instagram', 
                            'flickr' => 'Flickr',
                            'vimeo' => 'Vimeo',
                            'youtube' => 'Youtube',
                            'behance' => 'Behance',
                            'dribbble' => 'Dribbble',
                            'soundcloud' => 'Soundcloud',
                            'lastfm' => 'LastFM'
                        ),
                        'default' => array(
                            'feed' => '',
                            'facebook' => '',
                            'twitter' => '',
                            'google-plus' => '',
                            'linkedin' => '', 
                            'pinterest' => '',
                            'bloglovin' => '',
                            'tumblr' => '',
                            'instagram' => '', 
                            'flickr' => '',
                            'vimeo' => '',
                            'youtube' => '',
                            'behance' => '',
                            'dribbble' => '',
                            'soundcloud' => '',
                            'lastfm' => ''
                        ),
                    )

                )
            );

            
            // Typography
            $this->sections[] = array(
                'icon'      => 'el-icon-font',
                'title'     => __('Typography', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'typography_info',
                        'type'      => 'info',
                        'desc'      => __('Standard System Fonts and Google Webfonts are avaialble in each Font Family dropdown', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'font_titles',
                        'type'      => 'typography',
                        'title'     => __('Titles &amp; Menus', 'redux-framework-demo'),
                        'subtitle'  => __('Specify titles &amp; menus font properties', 'redux-framework-demo'),
                        'google'    => true,
                        'color'     => false,
                        'text-align' => false,
                        'line-height' => false,
                        'font-size' => false,
                        'default'   => array(
                            'font-family'   => 'Oswald',
                            'font-weight'   => 'normal',
                        ),
                        'output' => array('h1, h2, h3, h4, h5, h6, .main-menu a, .secondary-menu a, .widget_pages, .widget_categories, .widget_nav_menu, .tagline, .sub-title, .entry-meta, .entry-note, .read-more, #submit, .ltr .single .entry-content > p:first-of-type:first-letter, input#s, .single-author-box .vcard, .comment-author, .comment-meta, .comment-reply-link, #respond label, .copyright, #wp-calendar tbody, .latest-reviews i, .score-box .total'),
                    ),
                    array(
                        'id'        => 'titles_size',
                        'type'      => 'typography',
                        'title'     => __('Titles Size', 'redux-framework-demo'),
                        'subtitle'  => __('Type titles &amp; headings font size', 'redux-framework-demo'),
                        'google'    => false,
                        'font-family' => false,
                        'font-style' => false,
                        'color'     => false,
                        'text-align' => false,
                        'line-height' => false,
                        'font-weight' => false,
                        'default'   => array(
                            'font-size' => '48px',
                        ),
						'output' => array('.title-with-sep, .title-with-bg, .classic-layout .entry-title, .posts-slider .entry-title')
                    ),
                     array(
                        'id'        => 'main_menu_font_size',
                        'type'      => 'typography',
                        'title'     => __('Main Menu Font Size', 'redux-framework-demo'),
                        'subtitle'  => __('Type main menu font size', 'redux-framework-demo'),
                        'google'    => false,
                        'font-family' => false,
                        'font-style' => false,
                        'color'     => false,
                        'text-align' => false,
                        'line-height' => false,
                        'font-weight' => false,
                        'default'   => array(
                            'font-size' => '18px'
                        ),
                        'output' => array('.main-menu > ul > li')
                    ),

                    array(
                        'id'        => 'font_text',
                        'type'      => 'typography',
                        'title'     => __('Body Text Font', 'redux-framework-demo'),
                        'subtitle'  => __('Specify body text font properties.', 'redux-framework-demo'),
                        'google'    => true,
                        'color'     => false,
                        'text-align' => false,
                        'line-height' => false,
                        'default'   => array(
                            'font-size'     => '16px',
                            'font-family'   => 'Lato',
                            'font-weight'   => 'normal',
                        ),
                        'output' => array('body'),
                    ),

                )
            );


            // Design Options
            $this->sections[] = array(
                'icon'      => 'el-icon-magic',
                'title'     => __('Design Options', 'redux-framework-demo'),
                'fields'    => array(
                    /* Text Alignment */
                    array(
                        'id'        => 'text_alignment',
                        'type'      => 'image_select',
                        'title'     => __('Text alignment', 'redux-framework-demo'),
                        'subtitle'  => __('Select your site text alignment. Centered or Left.', 'redux-framework-demo'),
                        'options'   => array(
                            '1' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-align-center.png'),
                            '2' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-align-left.png'),
                        ), 
                        'default' => '1'
                    ),

                    /* Site Layout */
                    array(
                        'id'        => 'site_layout',
                        'type'      => 'image_select',
                        'title'     => __('Site Layout', 'redux-framework-demo'),
                        'subtitle'  => __('Select site layout. Fullwidth or Boxed.', 'redux-framework-demo'),
                        'options'   => array(
                            '1' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-layout-full.png'),
                            '2' => array('img' => get_template_directory_uri() .'/admin/images/to-icon-layout-boxed.png'),
                        ), 
                        'default' => '1'
                    ),

                    /* Body Background */
                    array(
                        'id'        => 'site_body_bg',
                        'type'      => 'background',
                        'title'     => __('Body Background', 'redux-framework-demo'),
                        'subtitle'  => __('Pick a body background color or upload an image', 'redux-framework-demo'),
                        'default'  => array('background-color' => '#fff'),
                        'output'  => array('background-color' => 'body, .site-content, .layout-full .title-with-sep .title, .layout-full .title-with-sep .entry-title'),
                    ),

                    /* Main Colors */  
                    array(
                        'id'        => 'main_colors_start',
                        'type'      => 'section',
                        'title'     => __('Main Colors', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'main_site_color',
                                'type'      => 'color',
                                'title'     => __('Main Color', 'redux-framework-demo'),
                                'subtitle'  => __('Pick the main (accent) color for your site', 'redux-framework-demo'),
                                'default'   => '#ffcc0d',
                                'output'    => array( 'background-color' => '.entry-image, .paging-navigation .current, .link-pages span, .score-line span, .entry-breakdown .item .score-line, .widget_ti_most_commented span, .all-news-link .read-more' ),
                            ),
                            array(
                                'id'        => 'secondary_site_color',
                                'type'      => 'color',
                                'title'     => __('Secondary Color', 'redux-framework-demo'),
                                'subtitle'  => __('Pick the secondary color for your site (pagination numbers, most commented widget numbers, etc.)', 'redux-framework-demo'),
                                'default'   => '#000',
                                'output'    => array( 'color' => '.paging-navigation .current, .widget span i, .score-line span i, .all-news-link .read-more' ),
                            ),
                    array(
                        'id'        => 'main_colors_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Header */  
                    array(
                        'id'        => 'header_colors_start',
                        'type'      => 'section',
                        'title'     => __('Header', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'header_site_color',
                                'type'      => 'color',
                                'title'     => __('Header Background', 'redux-framework-demo'),
                                'subtitle'  => __('Pick the header background color', 'redux-framework-demo'),
                                'default'   => '#ffffff',
                                'output'    => array( 'background-color' => '#masthead, .main-menu-fixed' ),
                            ),
                    array(
                        'id'        => 'header_colors_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Top Strip */
                    array(
                        'id'        => 'top_strip_start',
                        'type'      => 'section',
                        'title'     => __('Top Strip', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'site_top_strip_bg',
                                'type'      => 'color',
                                'title'     => __('Background', 'redux-framework-demo'),
                                'subtitle'  => __('Top strip background color.', 'redux-framework-demo'),
                                'default'   => '#000',
                                'output'  => array( 'background-color' => '.top-strip, .secondary-menu .sub-menu, .top-strip #searchform input[type="text"], .top-strip .social li ul' ),
                            ),
                            array(
                                'id'        => 'site_top_strip_bottom_border',
                                'type'      => 'border',
                                'title'     => __('Bottom Border', 'redux-framework-demo'),
                                'subtitle'  => __('Bottom border color', 'redux-framework-demo'),
                                'output'  => array('.top-strip'),
                                'all'       => false,
                                'right'     => false,
                                'top'       => false,
                                'left'      => false,
                                'default'   => array(
                                    'border-color'  => '#000',
                                    'border-style'  => 'solid', 
                                    'border-bottom' => '0px',
                                )
                            ),
                            array(
                                'id'        => 'site_top_strip_links',
                                'type'      => 'link_color',
                                'title'     => __('Menu Links', 'redux-framework-demo'),
                                'subtitle'  => __('Menu links color', 'redux-framework-demo'),
                                'output'  => array('.secondary-menu a'),
                                'active'    => false,
                                'default'   => array(
                                    'regular'   => '#ffffff',
                                    'hover'     => '#ffcc0d'
                                )
                            ),
                            array(
                                'id'        => 'site_top_strip_border',
                                'type'      => 'color',
                                'title'     => __('Separators', 'redux-framework-demo'),
                                'subtitle'  => __('Menu items separators', 'redux-framework-demo'),
                                'default'   => '#333',
                                'output'    => array( 'border-color' => '.secondary-menu li, .top-strip #searchform input[type="text"]' ),
                            ),
                            array(
                                'id'        => 'site_top_strip_social',
                                'type'      => 'color',
                                'title'     => __('Social Icons', 'redux-framework-demo'),
                                'subtitle'  => __('Social icons styling', 'redux-framework-demo'),
                                'default'   => '#8c919b',
                                'output'    => array( 'color' => '.top-strip .social li a' ),
                            ),
                           
                    array(
                        'id'        => 'top_strip_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),
                   
                    /* Main Menu */
                    array(
                        'id'        => 'main_menu_start',
                        'type'      => 'section',
                        'title'     => __('Main Menu', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'main_menu_color',
                                'type'      => 'color',
                                'title'     => __('Background', 'redux-framework-demo'),
                                'subtitle'  => __('Main menu background color', 'redux-framework-demo'),
                                'default'   => '#fff',
                                'output'    => array( 'background-color' => '.main-menu,.sticky-active .main-menu-fixed' ),
                            ),
                            array(
                                'id'        => 'main_menu_links_color',
                                'type'      => 'link_color',
                                'title'     => __('Menu Links', 'redux-framework-demo'),
                                'subtitle'  => __('Main menu links color', 'redux-framework-demo'),
                                'output'  => array('.main-menu > ul > li > a'),
                                'active'    => false,
                                'default'   => array(
                                    'regular'   => '#000',
                                    'hover'     => '#333'
                                )
                            ),
                            array(
                                'id'        => 'main_menu_separator',
                                'type'      => 'color',
                                'title'     => __('Links Separator', 'redux-framework-demo'),
                                'subtitle'  => __('Links seprator color', 'redux-framework-demo'),
                                'default'   => '#eee',
                                'output'    => array( 'color' => '.main-menu > ul > li:after' ),
                            ),
                            array(
                                'id'        => 'main_menu_top_border',
                                'type'      => 'border',
                                'title'     => __('Top Border', 'redux-framework-demo'),
                                'subtitle'  => __('Main Menu top border', 'redux-framework-demo'),
                                'output'  => array('.main-menu'),
                                'all'       => false,
                                'right'     => false,
                                'bottom'    => false,
                                'left'      => false,
                                'default'   => array(
                                    'border-color'  => '#000', 
                                    'border-style'  => 'solid', 
                                    'border-top'    => '1px',
                                )
                            ),
                            array(
                                'id'        => 'main_menu_bottom_border',
                                'type'      => 'border',
                                'title'     => __('Bottom Border', 'redux-framework-demo'),
                                'subtitle'  => __('Main Menu bottom border', 'redux-framework-demo'),
                                'output'  => array('.main-menu'),
                                'all'       => false,
                                'right'     => false,
                                'top'       => false,
                                'left'      => false,
                                'default'   => array(
                                    'border-color'  => '#000', 
                                    'border-style'  => 'solid', 
                                    'border-bottom' => '3px',
                                )
                            ),
                    array(
                        'id'        => 'main_menu_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Main Menu Dropdown */
                    array(
                        'id'        => 'main_dropdown_start',
                        'type'      => 'section',
                        'title'     => __('Main Menu Dropdown', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            // General Settings
                            array(
                                'id'        => 'main_sub_menu_pointer',
                                'type'      => 'color',
                                'title'     => __('Pointer &amp; Top Border', 'redux-framework-demo'),
                                'subtitle'  => __('Pointer and top border color', 'redux-framework-demo'),
                                'default'   => '#ffcc0d',
                            ),
                            array(
                                'id'        => 'main_sub_border',
                                'type'      => 'border',
                                'title'     => __('Border', 'redux-framework-demo'),
                                'subtitle'  => __('Select the border styling', 'redux-framework-demo'),
                                'default'   => array(
                                    'border-color'  => '#000',
                                    'border-style'  => 'solid',
                                    'border-top'    => '0px', 
                                    'border-right'  => '0px', 
                                    'border-bottom' => '0px', 
                                    'border-left'   => '0px'
                                ),
                                'output' => array( 'border' => '.main-menu .sub-menu' ),
                            ),

                            // Left Column
                            array(
                                'id'        => 'main_sub_bg_left',
                                'type'      => 'color',
                                'title'     => __('Left Background', 'redux-framework-demo'),
                                'subtitle'  => __('Left column background color', 'redux-framework-demo'),
                                'default'   => '#000',
                                'output'  => array( 'background-color' => '.main-menu .sub-menu,.main-menu .sub-menu-two-columns .sub-menu:before' ),
                            ),
                            array(
                                'id'        => 'main_sub_links_left',
                                'type'      => 'link_color',
                                'title'     => __('Left Links Color', 'redux-framework-demo'),
                                'subtitle'  => __('Left Column links color', 'redux-framework-demo'),
                                'output'  => array('.sub-links li a'),
                                'active'    => false,
                                'default'   => array(
                                    'regular'   => '#ffffff',
                                    'hover'     => '#ffcc0d'
                                )
                            ),
                            array(
                                'id'        => 'main_sub_bg_sep',
                                'type'      => 'color',
                                'title'     => __('Separator Color', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for links separator', 'redux-framework-demo'),
                                'default'   => '#1e1e1e',
                                'output'  => array( 'background-color' => '.main-menu .sub-menu .sub-links a:after' ),
                            ),

                            // Right Column
                            array(
                                'id'        => 'main_sub_bg_right',
                                'type'      => 'color',
                                'title'     => __('Right Background', 'redux-framework-demo'),
                                'subtitle'  => __('Right Column background color', 'redux-framework-demo'),
                                'default'   => '#242628',
                                'output'  => array( 'background-color' => '.main-menu .sub-menu:after' ),
                            ),
                            array(
                                'id'        => 'main_sub_links_right',
                                'type'      => 'link_color',
                                'title'     => __('Right Links Color', 'redux-framework-demo'),
                                'subtitle'  => __('Right Column links color', 'redux-framework-demo'),
                                'output'  => array('.sub-posts li a'),
                                'active'    => false,
                                'default'   => array(
                                    'regular'   => '#ffffff',
                                    'hover'     => '#ffcc0d'
                                )
                            ),
                    array(
                        'id'        => 'main_dropdown_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Titles Background */
                    array(
                        'id'        => 'titles_bg_start',
                        'type'      => 'section',
                        'title'     => __('Titles Background', 'redux-framework-demo'),
                        'indent'    => false,
                    ),

                        array(
                            'id'        => 'titles_background_switch',
                            'type'      => 'switch',
                            'title'     => __('On/Off', 'redux-framework-demo'),
                            'subtitle'  => __('Turn the background image on or off', 'redux-framework-demo'),
                            'default'   => '1',
                        ),

                        array(
                            'id'        => 'titles_background_image',
                            'type'      => 'switch',
                            'title'     => __('Background Type', 'redux-framework-demo'),
                            'subtitle'  => __('Use deafult background or upload custom', 'redux-framework-demo'),
                            'required'  => array('titles_background_switch', '=', '1'),
                            'default'   => '1',
                            'on'        => 'Use Default',
                            'off'       => 'Upload Custom'
                             
                        ),
                        array(
                            'id'        => 'titles_background_upload',
                            'type'      => 'media',
                            'url'       => true,
                            'required'  => array('titles_background_image', '=', '0'),
                            'title'     => __('Upload Custom', 'redux-framework-demo'),
                            'subtitle'  => __('Upload custom background', 'redux-framework-demo'),
                            'default'   => '',       
                        ),

                    array(
                        'id'        => 'titles_bg_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Slider Tint */
                    array(
                        'id'        => 'slider_tint_start',
                        'type'      => 'section',
                        'title'     => __('Slider', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'slider_tint',
                                'type'      => 'color',
                                'title'     => __('Background', 'redux-framework-demo'),
                                'subtitle'  => __('Slider background color', 'redux-framework-demo'),
                                'default'   => '#000',
                                'output'    => array( 'background-color' => '.modern .content-over-image figure:before' ),
                            ),
                            array(
                                'id'            => 'slider_tint_strength',
                                'type'          => 'slider',
                                'title'         => __('Tint strength', 'redux-framework-demo'),
                                'subtitle'      => __('Slider tint regular strength', 'redux-framework-demo'),
                                'default'       => .1,
                                'min'           => 0,
                                'step'          => .1,
                                'max'           => 0,
                                'resolution'    => 0.1,
                                'display_value' => 'text',
                            ),
                            array(
                                'id'            => 'slider_tint_strength_hover',
                                'type'          => 'slider',
                                'title'         => __('Tint strength hover', 'redux-framework-demo'),
                                'subtitle'      => __('Slider tint regular strength mouse over', 'redux-framework-demo'),
                                'default'       => .7,
                                'min'           => 0,
                                'step'          => .1,
                                'max'           => 0,
                                'resolution'    => 0.1,
                                'display_value' => 'text',
                            ),
                    array(
                        'id'        => 'slider_tint_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Sidebar */
                    array(
                        'id'        => 'sidebar_border_start',
                        'type'      => 'section',
                        'title'     => __('Sidebar', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'sidebar_border',
                                'type'      => 'border',
                                'title'     => __('Sidebar Border', 'redux-framework-demo'),
                                'subtitle'  => __('Select sidebar border styling', 'redux-framework-demo'),
                                'default'   => array(
                                    'border-color'  => '#000',
                                    'border-style'  => 'solid',
                                    'border-top'    => '1px', 
                                    'border-right'  => '1px',
                                    'border-bottom' => '1px', 
                                    'border-left'   => '1px'
                                ),
                                'output' => array( 'border' => '.sidebar' ),
                            ),
                     array(
                        'id'        => 'sidebar_border_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Slide Dock */
                    array(
                        'id'        => 'slide_dock_start',
                        'type'      => 'section',
                        'title'     => __('Single Post Slide Dock', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'slide_dock_color',
                                'type'      => 'color',
                                'title'     => __('Background', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for the backgound', 'redux-framework-demo'),
                                'default'   => '#ffffff',
                                'output'    => array( 'background-color' => '.slide-dock' ),
                            ),
                            array(
                                'id'        => 'slide_dock_text',
                                'type'      => 'color',
                                'title'     => __('Titles, Text and Links', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for titles, text and links', 'redux-framework-demo'),
                                'default'   => '#000000',
                                'output'    => array( 'color' => '.slide-dock h3, .slide-dock a, .slide-dock p' ),
                            ),
                    array(
                        'id'        => 'slide_dock_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Widgetized Footer */
                    array(
                        'id'        => 'widgets_footer_start',
                        'type'      => 'section',
                        'title'     => __('Widgetized Footer', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'footer_color',
                                'type'      => 'color',
                                'title'     => __('Background', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for the backgound', 'redux-framework-demo'),
                                'default'   => '#242628',
                                'output'    => array( 'background-color' => '.footer-sidebar, .widget_ti_most_commented li a' ),
                            ),
                            array(
                                'id'        => 'footer_titles',
                                'type'      => 'color',
                                'title'     => __('Titles', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for widget titles', 'redux-framework-demo'),
                                'default'   => '#ffcc0d',
                                'output'    => array( 'color' => '.footer-sidebar .widget h3' ),
                            ),
                            array(
                                'id'        => 'footer_text',
                                'type'      => 'color',
                                'title'     => __('Text', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for widget text', 'redux-framework-demo'),
                                'default'   => '#8c919b',
                                'output'    => array( 'color' => '.footer-sidebar' ),
                            ),
                            array(
                                'id'        => 'footer_links',
                                'type'      => 'link_color',
                                'title'     => __('Links Color', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for widget links', 'redux-framework-demo'),
                                'output'  => array('.footer-sidebar .widget a'),
                                'active'    => false,
                                'default'   => array(
                                    'regular'   => '#8c919b',
                                    'hover'     => '#ffcc0d'
                                )
                            ),
                            array(
                                'id'        => 'footer_border',
                                'type'      => 'border',
                                'title'     => __('Borders Color', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for borders', 'redux-framework-demo'),
                                'default'   => array(
                                    'border-color'  => '#585b61',
                                    'border-style'  => 'dotted',
                                    'border-top'    => '1px',
                                    'border-right'  => '1px',
                                    'border-bottom' => '1px',
                                    'border-left'   => '1px'
                                ),
                                'output' => array( 'border' => '.widget-area-2, .widget-area-3, .footer-sidebar .widget' ),
                            ),
                    array(
                        'id'        => 'widgets_footer_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),

                    /* Footer */
                    array(
                        'id'        => 'site_footer_start',
                        'type'      => 'section',
                        'title'     => __('Footer', 'redux-framework-demo'),
                        'indent'    => false,
                    ),
                            array(
                                'id'        => 'site_footer_bg',
                                'type'      => 'color',
                                'title'     => __('Background', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for the backgound', 'redux-framework-demo'),
                                'default'   => '#222',
                                'output'    => array( 'background-color' => '.copyright' ),
                            ),
                            array(
                                'id'        => 'site_footer_text',
                                'type'      => 'color',
                                'title'     => __('Text and Links', 'redux-framework-demo'),
                                'subtitle'  => __('Pick a color for text and links', 'redux-framework-demo'),
                                'default'   => '#8c919b',
                                'output'    => array( 'color' => '.copyright, .copyright a' ),
                            ),
                    array(
                        'id'        => 'site_footer_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),
                    
                )
            );

            
            // Post Item
            $this->sections[] = array(
                'icon'      => 'el-icon-file-new',
                'title'     => __('Post Item', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'post_item_info',
                        'type'      => 'info',
                        'desc'      => __('All options for a post item on Homepage and Categories', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'home_post_date',
                        'type'      => 'switch',
                        'title'     => __('Homepage Post Date', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable homepage post date', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'archive_post_date',
                        'type'      => 'switch',
                        'title'     => __('Categories Post Date', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable categories post date', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'site_author_name',
                        'type'      => 'switch',
                        'title'     => __('Author name', 'redux-framework-demo'),
                        'subtitle'  => __('Post author name in all posts site wide', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'site_wide_excerpt',
                        'type'      => 'switch',
                        'title'     => __('Exceprt Site Wide', 'redux-framework-demo'),
                        'subtitle'  => __('Excerpt of all posts in all categories and Latest Posts section of Page Composer.', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'site_wide_excerpt_length',
                        'type'      => 'text',
                        'title'     => __('Excerpt Length', 'redux-framework-demo'),
                        'subtitle'  => __('Enter a number of words to limit the exceprt site wide', 'redux-framework-demo'),
                        'validate'  => 'numeric',
                        'default'   => '24',
                    ),
                    array(
                        'id'        => 'read_more_link',
                        'type'      => 'switch',
                        'title'     => __('Read More Link', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the Read More link in every post', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                )
            );
            
            
            // Single Post
            $this->sections[] = array(
                'icon'      => 'el-icon-file-edit',
                'title'     => __('Single Post', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'single_media_position',
                        'type'      => 'button_set',
                        'title'     => __('Media Position', 'redux-framework-demo'),
                        'subtitle'  => __('Select the media position.', 'redux-framework-demo'),
                        'desc'      => __('"Full Width" and "Above the Content" will work site wide. "Define Per Post" enables the "Media Position" option in "Post Options" box in each post. "Above the Content" option does not support Gallery post format.', 'redux-framework-demo'),
                        'options'   => array(
                            'fullwidth' => 'Full Width', 
                            'abovecontent' => 'Above the Content',
                            'useperpost' => 'Define Per Post'
                        ), 
                        'default'   => 'fullwidth'
                    ),
                    array(
                        'id'        => 'single_featured_image',
                        'type'      => 'switch',
                        'title'     => __('Featured Image', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable featured image', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_rating_box',
                        'type'      => 'button_set',
                        'title'     => __('Rating Box Position', 'redux-framework-demo'),
                        'subtitle'  => __('Specify where to show the rating box', 'redux-framework-demo'),
                        'options'   => array(
                            'rating_top' => 'Post Content Top', 
                            'rating_bottom' => 'Post Content Bottom',
                        ), 
                        'default'   => 'rating_top'
                    ),
                    array(
                        'id'        => 'single_author_name',
                        'type'      => 'switch',
                        'title'     => __('Author name', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable post author name', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_post_cat_name',
                        'type'      => 'switch',
                        'title'     => __('Category name', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the post category name', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_post_date',
                        'type'      => 'switch',
                        'title'     => __('Post Date', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the post date', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_tags_list',
                        'type'      => 'switch',
                        'title'     => __('Tags', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the tags list', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_social',
                        'type'      => 'switch',
                        'title'     => __('Social Share Links', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable social share links panel', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_social_style',
                        'type'      => 'button_set',
                        'title'     => __('Social Share Link Style', 'redux-framework-demo'),
                        'subtitle'  => __('Specify social share links panel styling', 'redux-framework-demo'),
                        'options'   => array(
                            'social_default' => 'Minimal',
                            'social_colors' => 'Colorful',
                        ), 
                        'default'   => 'social_default'
                    ),
                    array(
                        'id'        => 'single_twitter_user',
                        'type'      => 'text',
                        'title'     => __('Twitter Username', 'redux-framework-demo'),
                        'subtitle'  => __('Type your Twitter username for Twitter share link, without the at sign', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'single_author',
                        'type'      => 'switch',
                        'title'     => __('Author Box', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the author box', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_nav_arrows',
                        'type'      => 'switch',
                        'title'     => __('Previous Post / Next Post', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable Previous Post/Next Post navigation in single post page', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_slide_dock',
                        'type'      => 'switch',
                        'title'     => __('Slide Dock', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the slide dock in the bottom right corner', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_related',
                        'type'      => 'switch',
                        'title'     => __('Related Posts', 'redux-framework-demo'),
                        'subtitle'  => __('Enable or Disable the Related Posts box', 'redux-framework-demo'),
                        'default'   => 1,
                    ),
                    array(
                        'id'        => 'single_related_posts_show_by',
                        'type'      => 'button_set',
                        'title'     => __('Show Related Posts By', 'redux-framework-demo'),
                        'subtitle'  => __('Specify the Related Posts output', 'redux-framework-demo'),
                        'options'   => array(
                            'related_cat' => 'Category', 
                            'related_tag' => 'Tag',
                        ), 
                        'default'   => 'related_cat'
                    ),
                    array(
                        'id'        => 'single_related_posts_to_show',
                        'type'      => 'button_set',
                        'title'     => __('Number of Related Posts', 'redux-framework-demo'),
                        'subtitle'  => __('Specify the number Related Posts to output', 'redux-framework-demo'),
                        'options'   => array(
                            '3' => '3', 
                            '6' => '6',
                            '9' => '9',
                        ), 
                        'default'   => '3'
                    ),
                )
            );

            
            // Posts Page
            $this->sections[] = array(
                'icon'      => 'el-icon-file',
                'title'     => __('Posts Page', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'posts_page_info',
                        'type'      => 'info',
                        'desc'      => __('Posts Page is created under Settings &rarr; Reading', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'posts_page_title',
                        'type'      => 'button_set',
                        'title'     => __('Page Title', 'redux-framework-demo'),
                        'subtitle'  => __('Specify the page title behavior', 'redux-framework-demo'),
                        'options'   => array(
                            'no_title' => 'No Title',
                            'full_width_title' => 'Full Width',
                            'above_content_title' => 'Above The Content'
                        ),
                        'default'   => 'no_title'
                    ),
                    array(
                        'id'        => 'posts_page_layout',
                        'type'      => 'image_select',
                        'title'     => __('Posts Layout', 'redux-framework-demo'),
                        'subtitle'  => __('Select the page layout', 'redux-framework-demo'),
                        'options'   => array(
                            'masonry-layout' => array('img' => get_template_directory_uri() . '/admin/images/to-icon-post-masonry.png'),
                            'grid-layout' => array('img' => get_template_directory_uri() . '/admin/images/to-icon-post-grid.png'),
                            'list-layout' => array('img' => get_template_directory_uri() . '/admin/images/to-icon-post-list.png'),
                            'classic-layout' => array('img' => get_template_directory_uri() . '/admin/images/to-icon-post-classic.png'),
                        ), 
                        'default' => 'masonry-layout'
                    ),
                )
            );

            
            // Custom Code
            $this->sections[] = array(
                'icon'      => 'el-icon-glasses',
                'title'     => __('Custom Code', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'custom_css',
                        'type'      => 'textarea',
                        'title'     => __('Custom CSS', 'redux-framework-demo'),
                        'subtitle'  => __('Quickly add some CSS by adding it to this block.', 'redux-framework-demo'),
                        'rows'      => 20
                    ),
                    array(
                        'id'        => 'custom_js_header',
                        'type'      => 'textarea',
                        'title'     => __('Custom JavaScript/Analytics Header', 'redux-framework-demo'),
                        'subtitle'  => __('Paste here JavaScript and/or Analytics code wich will appear in the Header of your site. DO NOT include opening and closing script tags.', 'redux-framework-demo'),
                        'rows'      => 12
                    ),
                    array(
                        'id'        => 'custom_js_footer',
                        'type'      => 'textarea',
                        'title'     => __('Custom JavaScript/Analytics Footer', 'redux-framework-demo'),
                        'subtitle'  => __('Paste JavaScript and/or Analytics code wich will appear in the Footer of your site. DO NOT include opening and closing script tags.', 'redux-framework-demo'),
                        'rows'      => 12
                    ),
                )
            );


            // Ad Units
            $this->sections[] = array(
                'icon'      => 'el-icon-bullhorn',
                'title'     => __('Ad Units', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'ad_units_info',
                        'type'      => 'info',
                        'desc'      => __('Add ads in one of the two formats: Image Ad or Code Generated Ad', 'redux-framework-demo')
                    ),
                    // Header Ads
                    array(
                        'id'        => 'ad_header_start',
                        'type'      => 'section',
                        'title'     => __('Header Ad', 'redux-framework-demo'),
                        'indent'    => false,
                    ),

                        array(
                            'id'        => 'header_image_ad',
                            'type'      => 'media',
                            'url'       => true,
                            'placeholder'  => __('Click the Upload button to upload the ad image', 'redux-framework-demo'),
                            'title'     => __('Ad Image', 'redux-framework-demo'),
                            'subtitle'  => __('Best size for header ad image is 728x90', 'redux-framework-demo'),
                            'default'  => array(
                                'url' => '',
                            ),
                        ),

                        array(
                            'id'            => 'header_image_ad_url',
                            'type'          => 'text',
                            'title'         => __('Ad link', 'redux-framework-demo'),
                            'subtitle'      => __('Enter a full URL of ad link', 'redux-framework-demo'),
                            'placeholder'   => 'http://'
                        ),
            
                   
                        array(
                            'id'        => 'header_code_ad',
                            'type'      => 'textarea',
                            'title'     => __('Ad Code', 'redux-framework-demo'),
                            'subtitle'  => __('Paste the code generated ad. Best size is 728x90', 'redux-framework-demo')
                        ),

                    array(
                        'id'        => 'ad_header_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),
                       

                    // Single above the content
                    array(
                        'id'        => 'ad_single1_start',
                        'type'      => 'section',
                        'title'     => __('Single Post - Above the content', 'redux-framework-demo'),
                        'indent'    => false,
                    ),

                        array(
                            'id'        => 'single_image_top_ad',
                            'type'      => 'media',
                            'url'       => true,
                            'placeholder'  => __('Click the Upload button to upload the ad image', 'redux-framework-demo'),
                            'title'     => __('Ad Image', 'redux-framework-demo'),
                            'subtitle'  => __('Width limit is 1050 pixels', 'redux-framework-demo'),
                            'default'  => array(
                                'url' => '',
                            ),
                        ),

                        array(
                            'id'            => 'single_image_top_ad_url',
                            'type'          => 'text',
                            'title'         => __('Ad link', 'redux-framework-demo'),
                            'subtitle'      => __('Enter a full URL of ad link', 'redux-framework-demo'),
                            'placeholder'   => 'http://'
                        ),
            
                        array(
                            'id'        => 'single_code_top_ad',
                            'type'      => 'textarea',
                            'title'     => __('Ad Code', 'redux-framework-demo'),
                            'subtitle'  => __('Width limit is 1050 pixels.', 'redux-framework-demo')
                        ),

                    array(
                        'id'        => 'ad_single1_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),


                    // Single under the content
                    array(
                        'id'        => 'ad_single2_start',
                        'type'      => 'section',
                        'title'     => __('Single Post - Under the content', 'redux-framework-demo'),
                        'indent'    => false,
                    ),

                        array(
                            'id'        => 'single_image_bottom_ad',
                            'type'      => 'media',
                            'url'       => true,
                            'placeholder'  => __('Click the Upload button to upload the ad image', 'redux-framework-demo'),
                            'title'     => __('Ad Image', 'redux-framework-demo'),
                            'subtitle'  => __('Width limit is 1050 pixels', 'redux-framework-demo'),
                            'default'  => array(
                                'url' => '',
                            ),
                        ),

                        array(
                            'id'            => 'single_image_bottom_ad_url',
                            'type'          => 'text',
                            'title'         => __('Ad link', 'redux-framework-demo'),
                            'subtitle'      => __('Enter a full URL of ad link', 'redux-framework-demo'),
                            'placeholder'   => 'http://'
                        ),
            
                        array(
                            'id'        => 'single_code_bottom_ad',
                            'type'      => 'textarea',
                            'title'     => __('Ad Code', 'redux-framework-demo'),
                            'subtitle'  => __('Width limit is 1050 pixels.', 'redux-framework-demo')
                        ),

                    array(
                        'id'        => 'ad_single2_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),


                    // Footer
                    array(
                        'id'        => 'ad_footer_start',
                        'type'      => 'section',
                        'title'     => __('Footer', 'redux-framework-demo'),
                        'indent'    => false,
                    ),

                        array(
                            'id'        => 'footer_image_ad',
                            'type'      => 'media',
                            'url'       => true,
                            'placeholder'  => __('Click the Upload button to upload the ad image', 'redux-framework-demo'),
                            'title'     => __('Ad Image', 'redux-framework-demo'),
                            'subtitle'  => __('Width limit is 1050 pixels', 'redux-framework-demo'),
                            'default'  => array(
                                'url' => '',
                            ),
                        ),

                        array(
                            'id'            => 'footer_image_ad_url',
                            'type'          => 'text',
                            'title'         => __('Ad link', 'redux-framework-demo'),
                            'subtitle'      => __('Enter a full URL of ad link', 'redux-framework-demo'),
                            'placeholder'   => 'http://'
                        ),
            
                        array(
                            'id'        => 'footer_code_ad',
                            'type'      => 'textarea',
                            'title'     => __('Ad Code', 'redux-framework-demo'),
                            'subtitle'  => __('Width limit is 1050 pixels.', 'redux-framework-demo')
                        ),

                    array(
                        'id'        => 'ad_footer_end',
                        'type'      => 'section',
                        'indent'    => false,
                    ),
                )
            );

            
            // 404 Error Page
            $this->sections[] = array(
                'icon'      => 'el-icon-warning-sign',
                'title'     => __('Page 404', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'error_image',
                        'type'      => 'media',
                        'url'       => true,
                        'placeholder'  => __('Click the Upload button to upload the image', 'redux-framework-demo'),
                        'title'     => __('Upload Image', 'redux-framework-demo'),
                        'subtitle'  => __('Upload an image for the 404 error page', 'redux-framework-demo'),
                        'default'  => array(
                            'url' => get_template_directory_uri() . '/images/error-page.png',
                            'width' => '402',
                            'height' => '402',
                        ),
                    ),
                )
            );


            // Import/Export
            $this->sections[] = array(
                'title'     => __('Import / Export', 'redux-framework-demo'),
                'desc'      => __('Import and Export your settings from file, text or URL.', 'redux-framework-demo'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );           
        }


        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'ti_option',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'submenu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Theme Options', 'redux-framework-demo'),
                'page_title'        => __('Theme Options', 'redux-framework-demo'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'AIzaSyBCNNmid7eOngJUTGogTM9pd_O_SJUOSJE', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => false,                    // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/ThemesIndep',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/ThemesIndep',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );

            // Panel Intro text -> before the form
            /*if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Intro Text</p>', 'redux-framework-demo'), $v);
            } else {
                $this->args['intro_text'] = __('<p>Intro Text</p>', 'redux-framework-demo');
            }*/

            // Add content after the form.
            //$this->args['footer_text'] = __('<p>Footer Text</p>', 'redux-framework-demo');
        }

    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}

function ti_addPanelCSS() {
    wp_register_style(
        'redux-custom-css',
        get_template_directory_uri().'/admin/redux-custom.css',
        array( 'redux-css' ), // Be sure to include redux-css so it's appended after the core css is applied
        time(),
        'all'
    );  
    wp_enqueue_style('redux-custom-css');
}
// This example assumes your opt_name is set to redux_demo, replace with your opt_name value
add_action( 'redux/page/ti_option/enqueue', 'ti_addPanelCSS' );



/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;