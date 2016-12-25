<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


if (!class_exists('FPD_Frontend_Product')) {

    class FPD_Frontend_Product {

        private $form_views = null;
        private $disable_export_btns = 0;

        public function __construct() {

            require_once(FPD_PLUGIN_DIR . '/inc/class-parameters.php');

            //CATALOG
            $catalog_button_pos = fpd_get_option('fpd_catalog_button_position');
            if ($catalog_button_pos == 'fpd-replace-add-to-cart') {
                add_filter('woocommerce_loop_add_to_cart_link', array(&$this, 'add_to_cart_cat_text'), 10, 2);
            } else {
                add_action('woocommerce_after_shop_loop_item', array(&$this, 'add_catalog_customize_button'), 20);
            }

            //SINGLE FANCY PRODUCT
            add_filter('body_class', array(&$this, 'add_fancy_product_class'));

            add_action('wp_head', array(&$this, 'remove_share_image_filter'), 1000);

            //before product container
            add_action('woocommerce_before_single_product', array(&$this, 'before_product_container'), 1);

            //add customize button
            if (fpd_get_option('fpd_start_customizing_button_position') == 'under-short-desc') {
                add_action('woocommerce_single_product_summary', array(&$this, 'add_customize_button'), 25);
            } else {
                add_action('woocommerce_after_add_to_cart_button', array(&$this, 'add_customize_button'), 0);
            }

            //add additional form fields to cart form
            add_action('woocommerce_before_add_to_cart_button', array(&$this, 'add_product_designer_form'));
            //php uploader - image upload
            add_action('wp_ajax_fpduploadimage', array(&$this, 'upload_image'));
            if (fpd_get_option('fpd_upload_designs_php_logged_in') == 0) {
                add_action('wp_ajax_nopriv_fpduploadimage', array(&$this, 'upload_image'));
            }

            //add share button
            if (fpd_get_option('fpd_sharing')) {
                add_filter('wp_get_attachment_url', array(&$this, 'set_product_image'));
                add_filter('post_type_link', array(&$this, 'reset_share_permalink'), 10, 2);
                add_action('woocommerce_share', array(&$this, 'add_share'));
                add_action('wp_ajax_fpd_createshareurl', array(&$this, 'create_share_url'));
                add_action('wp_ajax_nopriv_fpd_createshareurl', array(&$this, 'create_share_url'));
            }

            //order via shortcode
            add_shortcode('fpd', array(&$this, 'fpd_shortcode_handler'));
            add_shortcode('fpd_form', array(&$this, 'fpd_form_shortcode_handler'));
            add_action('wp_ajax_fpd_newshortcodeorder', array(&$this, 'create_shortcode_order'));
            add_action('wp_ajax_nopriv_fpd_newshortcodeorder', array(&$this, 'create_shortcode_order'));

            //upload image from social network
            add_action('wp_ajax_fpd_uploadsocialphoto', array(&$this, 'upload_social_photo'));
            add_action('wp_ajax_nopriv_fpd_uploadsocialphoto', array(&$this, 'upload_social_photo'));
        }

        //remove filter that resets the product image url before body starts
        public function remove_share_image_filter() {

            remove_filter('wp_get_attachment_url', array(&$this, 'set_product_image'));
        }

        //custom text for the add-to-cart button in catalog
        public function add_to_cart_cat_text($handler, $product) {

            if (is_fancy_product($product->id)) {
                return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button product_type_%s">%s</a>', esc_url(get_permalink($product->id)), esc_attr($product->id), esc_attr($product->get_sku()), esc_attr($product->product_type), esc_html(fpd_get_option('fpd_label_add_to_cart_text'))
                );
            }

            return $handler;
        }

        //add customize button add the end of catalog item
        public function add_catalog_customize_button() {

            global $product;

            if (is_fancy_product($product->id)) {

                printf('<a href="%s" rel="nofollow" class="button" style="width: 100%%; margin: 10px 0;">%s</a>', esc_url(get_permalink($product->id)), esc_html(fpd_get_option('fpd_label_add_to_cart_text'))
                );
            }
        }

        //add fancy-product class in body
        public function add_fancy_product_class($classes) {

            global $post;

            if (is_fancy_product($post->ID)) {

                $product_settings = new FPD_Product_Settings($post->ID);

                $classes[] = 'fancy-product';

                if ($product_settings->customize_button_enabled || (isset($_GET['cart_item_key']) && $product_settings->get_option('open_in_lightbox'))) {
                    $classes[] = 'fpd-customize-button-visible';
                } else {
                    $classes[] = 'fpd-customize-button-hidden';
                }

                //check if tablets are supported
                if (fpd_get_option('fpd_disable_on_tablets'))
                    $classes[] = 'fpd-hidden-tablets';


                //check if smartphones are supported
                if (fpd_get_option('fpd_disable_on_smartphones'))
                    $classes[] = 'fpd-hidden-mobile';

                if ($product_settings->get_option('fullwidth_summary'))
                    $classes[] = 'fpd-fullwidth-summary';
            }

            return $classes;
        }

        public function before_product_container() {

            global $post;

            if (is_fancy_product($post->ID)) {

                //add product designer
                $product_settings = new FPD_Product_Settings($post->ID);
                $position = $product_settings->get_option('placement');

                if ($position == 'fpd-replace-image') {
                    add_action('woocommerce_before_single_product_summary', array(&$this, 'add_product_designer'), 15);
                } else if ($position == 'fpd-under-title') {
                    add_action('woocommerce_single_product_summary', array(&$this, 'add_product_designer'), 6);
                } else if ($position == 'fpd-after-summary') {
                    add_action('woocommerce_after_single_product_summary', array(&$this, 'add_product_designer'), 1);
                } else {
                    add_action('fpd_product_designer', array(&$this, 'add_product_designer'));
                }

                //remove product image, there you gonna see the product designer
                if ($product_settings->get_option('hide_product_image') || ($position == 'fpd-replace-image' && (!$product_settings->customize_button_enabled))) {
                    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
                }
            }
        }

        //the actual product designer will be added
        public function add_product_designer() {

            global $post;
            $currency_code = "AUD";
            $product_settings = new FPD_Product_Settings($post->ID);

            $open_in_lightbox = $product_settings->get_option('open_in_lightbox') && trim($product_settings->get_option('start_customizing_button')) != '';

            if (is_fancy_product($product_settings->master_id) && (!$product_settings->customize_button_enabled || $open_in_lightbox)) {

                FPD_Scripts_Styles::$add_script = true;
                $selector = 'fancy-product-designer-' . $product_settings->master_id . '';

                //get availabe fonts
                $available_fonts = $product_settings->get_option('font_families[]') === false ? FPD_Fonts::get_enabled_fonts() : $product_settings->get_option('font_families[]');
                if (!is_array($available_fonts))
                    $available_fonts = str_split($available_fonts, strlen($available_fonts));

                //woocommerce
                if (get_post_type($post) === 'product') {
                    $this->output_wc_start();
                }

                //get assigned categories
                $fancy_content_ids = fpd_has_content($product_settings->master_id);
                if (!is_array($fancy_content_ids) || sizeof($fancy_content_ids) === 0) {
                    return;
                }

                //define the designer margins
                $designer_margins = $product_settings->get_option('designer_margin');
                $margin_styles = '';
                if (!empty($designer_margins)) {
                    @parse_str($designer_margins, $designer_margins);
                    foreach ($designer_margins as $margin_key => $margin_val) {
                        $margin_styles .= 'margin-' . $margin_key . ':' . $margin_val . 'px;';
                    }
                }

                //add class if lightbox is enabled
                $modal_box_css = $open_in_lightbox ? ' fpd-lightbox-enabled' : '';
                $source_type = get_post_meta($product_settings->master_id, 'fpd_source_type', true);
                ?>
                <div class="campaign-subheader navbar"> 
                    <div class="actions-bar">
                        <ul id="launch-progression" class="nav">
                            <li class="active-step">
                                <a href="javascript:void(0);" data-step="1">
                                    <span class="progress-number">1</span>Create your Design</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-step="2">
                                    <span class="progress-number">2</span>Set your Sales Goal
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-step="3">
                                    <span class="progress-number">3</span>Add your Marketing Description
                                </a>
                            </li>
                        </ul>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <div id="panel-design" class="panel panel_1">
                    <div id="<?php echo $selector; ?>" class="fpd-container <?php echo $product_settings->get_option('frame_shadow');
                echo $modal_box_css; ?>" style="float: <?php echo $product_settings->get_option('designer_floating'); ?>; <?php echo $margin_styles; ?>">
                <?php
                foreach ($fancy_content_ids as $fancy_content_id) {

                    if (empty($source_type) || $source_type == 'category') {

                        $fancy_category = new Fancy_Category($fancy_content_id);
                        echo '<div class="fpd-category" title="' . esc_attr($fancy_category->get_data()->title) . '">';

                        $fancy_products_data = $fancy_category->get_products();
                        foreach ($fancy_products_data as $fancy_product_data) {

                            echo $this->get_product_html($fancy_product_data->ID);
                        }

                        echo '</div>'; //category
                    } else {

                        echo $this->get_product_html($fancy_content_id);
                    }
                }

                //output designs
                if (!intval($product_settings->get_option('hide_designs_tab'))) {

                    require_once( FPD_PLUGIN_DIR . '/inc/class-designs.php' );

                    $fpd_designs = new FPD_Designs(
                            $product_settings->get_option('design_categories[]') ? $product_settings->get_option('design_categories[]') : array()
                            , $product_settings->get_image_parameters()
                    );
                    $fpd_designs->output();
                }
                ?>

                    </div>
                    <div style="    float: right;
                         min-width: 200px;
                         margin-top: 5px;
                         background: #f5f6f7;
                         border-radius: 5px;
                         border: 1px solid #d8dddf;
                         padding: 10px;
                         position: relative;">
                        <?php
                        global $product;
                        ?>
                        <h1 style="    font-size: 18px;    text-align: center;"><?php echo $product->post->post_title; ?></h1>
                        <div id="price_preview_content" style="opacity: 0;">
                            <p class="base-cost-intro" ><span style="color: #6c7478;
                                                              font-size: 14px;
                                                              font-weight: 700;">Base cost @ 30 units</span></p>
                            <div class="price_content_1">
                                <span class="currency_pre"><?php echo $currency_code; ?></span>
                            <span class="base-cost-display" id="price_preview" style="color: #2878a1;
                               font-size: 32px;
                               margin-bottom: 10px;
                               font-weight: 700;" >$0</span>
                            </div>
                        </div>
                        <div class="submit_container">
                            <button type="button" class="button_transaction button" data-step="2">Next</button>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <p class="fpd-not-supported-device-info">
                        <strong><?php echo fpd_get_option('fpd_label_not_supported_device_info'); ?></strong>
                    </p>

                </div>
                <form onsubmit="return false;"  class="campaign-info" id="campaign-info" method="post">
                    <div id="panel-pricing" class="panel panel_2" style="display: none;">
                        <div class="row" >
                            <div class="col-md-6">
                         <?php
                         global $product;
                         ?>
                                <input type="hidden" id="h_product_price" value="<?php echo $product->price; ?>" />
                                <input type="hidden" id="h_product_percent_profit" value="30" />

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="author"><strong>Set your Sales Goal </strong><span class="minimun_title"> - Minimum production run - 15 Pcs</span></label>
                                        <input id="sales_goal" />	
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <div class="input-group sales_goal_content">
                                            <span class="input-group-addon" id="basic-addon1"># of units</span>
                                            <input autocomplete="off" type="text" id="sales_goal_input" value="30" name="sales_goal"  class="form-control" placeholder="# of units" aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="col-md-8" style="    padding-left: 2%;">
                                        Your goal is the number of units you’re aiming to sell, but we’ll print your campaign as long as you sell enough to generate a profit. The minimum is 15 pcs.
                                    </div>
                                </div>	

                                <div class="row "  style="    padding-top: 25px;">
                                    <div class="col-md-12 form-group">
                                        <label for="price"><strong>Set your Sell Price </strong></label>
                                    </div>
                                </div>		
                                <div class="row ">
                                    <div class="col-md-12 form-group">
                                        <label for="price"><strong>Apparel Options</strong></label>

                                    </div>
                                </div>									
                                <div class="ssp_block product row box_design" fpd-index="0" >
									<div class="row ssp_block__top" style="overflow: visible;   display: -webkit-box;    display: -webkit-flex;    display: -ms-flexbox;    position: relative; ">
                                        <div style="    margin-bottom: 2px;" class="col-md-8 form-group">
                                            <div style="box-sizing: border-box;">
                                                <div>
                                                    <img style="float: left;     width: 100px;" class="product_thumbnail" src="<?php echo get_template_directory_uri()?>/images/shirt-loading.gif" />
													<input type="hidden" name="design_image_front" id="design_image_front" value="" />
                                                    <input type="hidden" name="design_image_back" id="design_image_back" value="" />
                                                </div>
                                                <span class="design-fpd-title" style="font-size: 1.3em;    font-weight: 700;"><?php echo $product->post->post_title; ?></span>
                                                <div class="ssp_profit" style="margin-top: -3px;    font-size: 1em;    font-weight: 600;    color: #6c7478;    display: -webkit-box;    display: -webkit-flex;    display: -ms-flexbox;    display: flex;">
                                                    <span class="profit_per" style="font-weight: 800;"></span>
                                                    <span>&nbsp;</span>
                                                    <span>profit/sale</span>
                                                </div>
                                            </div>


                                        </div>
                                        <div style=" padding-left: 0%;   margin-bottom: 2px;" class="col-md-4 price_content form-group price_content_3">
                                            <span class="currency_pre" style="style="    width: 30%;
                                                  float: left;"""><?php echo $currency_code; ?></span>
                                            <div class="input-group " style="    width: 70%;
    float: right;">
                                                <span class="input-group-addon" id="basic-addon1">$</span>
                                                <input autocomplete="off" class="form-control campaign_price"  autocomplete="false" type="text" aria-required="true" size="30" 
                                                       value="0" name="price" id="campaign_price" aria-describedby="basic-addon1"
                                                        data-placement="right"  >
                                                <input autocomplete="off"  type="hidden" value="<?php echo $product->price; ?>" name="cost_price" id="cost_price">

                                            </div>
											<div  class="price_loading" >
											<img src="<?php echo get_template_directory_uri()?>/images/mini_loaderwheel.gif" />
											</div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 form-group" style="margin-bottom: 0px;    margin-top: 10px;" class="ssp_block ssp_block__bottom" >
                                            <div>
                                                <div class="ssp_color">
                                                </div>
												
                                                <input class="shirt_color_seleted" autocomplete="off" type="hidden" value='' />
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
								
								<div class="style_adder" >
									<div class="style_adder__panel">
										<div class="style_adder__cta" >Add Style</div>
										<div class="style_adder__description" >Optimize your campaign by adding an additional style</div>
									</div>
								</div>								
                                <div class="row">
                                    <div class="col-md-7 form-group">
                                        <div><label >Estimated profit</label></div>
                                        <div class="price_content_2">
                                            <span class="currency_pre"><?php echo $currency_code; ?></span>
                                            <span id="global_profit">$0+</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 form-group">
                                        <div class="submit_container">
                                            <button type="button" class="button_transaction button" data-step="3">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="flip-container">
                                    <div class="flip">
                                        <div class="front design_view">

                                        </div>
                                        <div class="back design_view">

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div id="panel-details" class="panel panel_3" style="display: none;">
                        <div class="row" >
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="name">Campaign title</label>
                                    <input class="form-control required" autocomplete="false" type="text" aria-required="true" size="30" value="" name="name" id="campaign_name" required minlength="3">
                                </div>		
                                <div class="form-group">
                                    <label for="content">Description</label>
                                    <?php
                                    /*
                                    ?>
                                    <textarea class="form-control required"  autocomplete="false" aria-required="true" rows="8" cols="45" name="content" id="campaign_content" required minlength="3"></textarea>
    */ ?>
                                    <?php
                                    $content = '';
                                    $editor_id = 'campaign_content';
                                    $settings = array('media_buttons' => false,
                                        'teeny' => true);
                                    wp_editor($content, $editor_id, $settings);
                                    ?>
                                </div>
								<div class="form-group">
                                    <label for="content">Feature</label>
									<div>Choose which side of the garment you want to feature in your marketing campaign.</div>
                                    <input type="radio" id="front" name="feature_side" checked="checked" value="front" aria-label="Front">
									<label for="front">Front</label>
									<input style="margin-left: 35px;" type="radio" id="back" name="feature_side"  value="back" aria-label="Back">
									<label for="back">Back</label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Campaign length 
                                    </label>
                                    <div>Orders will ship 5-10 business days after the end of the campaign.</div>

                                    <div class="col-md-7 " style="margin-top: 10px; padding: 0px">
                                        <select tabindex="3" name="campaign_length"  id="length" name="length"  class="form-control">

                <?php
                $list_length = array(3, 5, 7, 10, 14, 21);
                foreach ($list_length as $n_date) {
                    $date = new DateTime();

                    $date->add(new DateInterval('P' . $n_date . 'D'));
                    ?>
                                                <option value="<?php echo $n_date; ?>" ><?php echo $n_date; ?> Days  (Ending  <?php echo $date->format('l, M d'); ?>)</option>
                    <?php
                }
                ?>

                                        </select>
                                    </div>
                                    <div class="col-md-5 " style="margin-top: 10px;     padding-right: 0px;">
                                        <div class="submit_container" style="margin: 0px">
                                            <button type="submit" class="button_transaction campaign_submit button" >Launch</button>
                                        </div>
                                    </div>
                                </div>	
                            </div>
                            <div class="col-md-6">

                                <div class="flip-container">
                                    <div class="flip">
                                        <div class="front design_view">

                                        </div>
                                        <div class="back design_view">

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>


                    </div>

                </form>
                <a style='display:none' class='login_popup' href="#login_popup_content">Login Hidden</a></p>
                <a style='display:none' class='loading_popup' href="#loading_popup_content">Login Hidden</a></p>
                <div style='display:none'>
                    <div id='login_popup_content' style='padding:10px; background:#fff;'>
                        <div id="ajax_login_content"  class="toggle_div">
                            <h3><strong>Login with teeM8</strong></h3>
                            <form onsubmit="return false;" novalidate="" class="ajax_login" style="min-height: 200px" id="ajax_login" method="post">
                                <div class="form-group">
                                    <input class="form-control required" placeholder="Username" autocomplete="false" type="text" aria-required="true" size="30" value="" name="username" id="username" required="" minlength="3">
                                </div>
                                <div class="form-group">
                                    <input class="form-control required" placeholder="Password" autocomplete="false" type="password" aria-required="true" size="30" value="" name="password" id="password" required="" minlength="3">
                                </div>
                                <div class="form-group" style="text-align: center;">
                                    <p>
                                        <a href="/my-account/lost-password/" target="_blank">Forgot your password?</a>
                                    </p>
                                    <p>
                                        <button type="submit" class=" button" data-step="2">Login to your account »</button>
                <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                                    </p>
                                    <p>
                                        <a href="javascript:void(0)" class="switch_login_register" >Want to create a new account?</a>								
                                    </p>
                                    <p class="status"></p>
                                </div>
                            </form>
                        </div>

                        <div id="ajax_register_content" class="toggle_div" style="display: none;">
                            <h3><strong>Create an account with teeM8</strong></h3>
                            <form onsubmit="return false;" novalidate="" class="ajax_register"  id="ajax_register" method="post">
                                <div class="form-group">
                                    <input class="form-control required" placeholder="Username" autocomplete="false" type="text" aria-required="true" max-length="50" value="" name="user_login" id="user_login" required="" minlength="3">
                                </div>
                                <div class="form-group">
                                    <input class="form-control required" placeholder="Email" autocomplete="false" type="email" aria-required="true" max-length="50" value="" name="user_email" id="user_email" required="" minlength="3">
                                </div>
                                <div class="form-group">
                                    <input class="form-control required" placeholder="Password: Must be longer than 6 characters." autocomplete="false" type="password" aria-required="true" max-length="50" value="" name="user_pass" id="user_pass" required="" minlength="6">
                                </div>
                                <div class="form-group">
                                    <input class="form-control required" placeholder="Confirm Password" autocomplete="false" type="password" aria-required="true" max-length="50" value="" name="user_pass_confirm" id="user_pass_confirm" required="" minlength="6">
                                </div>
                                <div class="form-group" style="text-align: center;">
                                    <p>
                                        <button type="submit" class=" button" data-step="2">Create your account »</button>
                                        <?php wp_nonce_field('ajax-register-nonce', 'security'); ?>
                                    </p>
                                    <p>
                                        <a href="javascript:void(0)" class="switch_login_register" >Already have an account?</a>								
                                    </p>
                                    <p class="status"></p>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div id='loading_popup_content' style='padding:10px; background:#fff;'>
                        <p>
                        <div class="submit_loading " style="   text-align: center;    padding-bottom: 50px;">
                            Please wait...
                            <div class="fpd-loading"></div>
                        </div>
                        <div id="panel-success" class="panel panel_4" style="display: none;">

                        </div>
                        </p>
                    </div>
                </div>
                <script type="text/javascript">
					var add_style_content = '<div class="style_adder" ><div class="style_adder__panel"><div class="style_adder__cta" >Add Style</div>										<div class="style_adder__description" >Optimize your campaign by adding an additional style</div></div></div>';
					var color_shirt = <?php echo FPD_Settings_Advanced_Colors::get_hex_names_object_string(); ?>;                                      
                    var validate_campaign;
                    var is_allow_sticky = false;
					var fancy_list ={<?php
					$arr_list=array();
					$arr_index=array();
					$_index = 0;
					foreach ($fancy_content_ids as $fancy_content_id) {
						$fancy_product = new Fancy_Product($fancy_content_id);
						$views_data = $fancy_product->get_views();
						if (!empty($views_data)) {
							$first_view = $views_data[0];	
							$fancy_content_id;
							$arr_list[] = '"'.$fancy_content_id.'":"'.esc_attr($first_view->title).'"';
							$arr_index[] = '"'.$_index.'":"'.$fancy_content_id.'"';
							$_index++;
						}
						
					}
					echo implode(',',$arr_list);
					?>};
				
					var fancy_index = {<?php echo implode(',',$arr_index); ?>};
					
                                        <?php

                                        function js_price($array_prices) {
                                            $price_for_qty = array();
                                            foreach ($array_prices as $qty => $values) {
                                                $array_value = array();
                                                foreach ($values as $key => $value) {
                                                    $array_value[] = "'$key':" . $value;
                                                }
                                                $price_for_qty[] = $qty . ':{' . implode(',', $array_value) . '}';
                                            }
                                            if (empty($array_prices))
                                                return "'error': true";
                                            return implode(',', $price_for_qty);
                                            ;
                                        }

                                        function js_price_fpd($array_base_prices, $array_color_prices) {

                                            return "{'base':{" . js_price($array_base_prices) . "},'color':{" . js_price($array_color_prices) . "}}";
                                        }

                                        global $wpdb;
                                        $table_price = $wpdb->prefix . 'fpd_prices';
                                        $array_prices = array();
                                        $fpd_index = 0;
                                        foreach ($fancy_content_ids as $fancy_content_id) {
                                            $_where = ' where fancy_product_id = ' . $fancy_content_id;
                                            $res = $wpdb->get_results("select * from $table_price $_where  ORDER BY qty ASC ");
                                            $array_base_prices = array();
                                            $array_color_prices = array();
                                            if ($res) {
                                                foreach ($res as $rs) {
                                                    if ($rs->is_color == 1) {
                                                        $array_color_prices[$rs->qty] = array(
                                                            'base_price' => $rs->base_price,
                                                            'front_color_print' => $rs->front_color_print,
                                                            'front_multi_color_print' => $rs->front_multi_color_print,
                                                            'back_color_print' => $rs->back_color_print,
                                                            'back_multi_color_print' => $rs->back_multi_color_print);
                                                    } else {
                                                        $array_base_prices[$rs->qty] = array(
                                                            'base_price' => $rs->base_price,
                                                            'front_color_print' => $rs->front_color_print,
                                                            'front_multi_color_print' => $rs->front_multi_color_print,
                                                            'back_color_print' => $rs->back_color_print,
                                                            'back_multi_color_print' => $rs->back_multi_color_print);
                                                    }
                                                }
                                            }

                                            $array_prices[$fpd_index] = js_price_fpd($array_base_prices, $array_color_prices);
                                            $fpd_index++;
                                        }
                                        ?>

										var fpd_prices = <?php
                                        $js_prices = array();
                                        foreach ($array_prices as $fdf_id => $prices) {
                                            $js_prices[] = $fdf_id . ':' . $prices;
                                        }
                                        echo '{' . implode(',', $js_prices) . '}';
                                        ?>;
										var  colors_design={};
                                        var  default_colors_design={};
										function get_name_color_by_hex(hex_color){
											return color_shirt[hex_color];
										}
										function initial_shirt_colors(){
											jQuery('.fpd-container > div.fpd-product').each(function(index){
                                                 var object_colors = {};
                                                  var default_color=false;
                                                jQuery(this).find('img').each(function(i) {
                                                    var color_string = jQuery(this).data('parameters').colors;
                                                 
                                                    if(color_string){
                                                    
                                                    var check_hex = color_string.charAt(0);
                                                    if(check_hex=='#'){
                                                     var color_array = color_string.split(","); 
                                                        
                                                         jQuery.each(color_array,function(key,value){
                                                            object_colors[value]=get_name_color_by_hex(value.substring(1));                                                            
                                                         });
                                                    }
                                                       
                                                    }
                                                   
                                                    if(jQuery(this).data('parameters').currentColor){
                                                         default_color_string = jQuery(this).data('parameters').currentColor;
                                                          var check_hex_default = default_color_string.charAt(0);
                                                            if(check_hex_default=='#'){
                                                                default_color = default_color_string;
                                                            }
                                                    }
                                                   
                                                    
                                                    
                                                });
                                                default_colors_design[index] =  default_color;
												colors_design[index]=object_colors;
											});
										}
										function get_bg_selected(container) {
											$container = jQuery(container);
											if($container.find('.shirt_color_seleted').val()!=''){
												return JSON.parse($container.find('.shirt_color_seleted').val());
											}
											return false;
                                        }
                                        function set_bg_selected(color_hex, color_name,container) {
											$container = jQuery(container);
                                            var color_selected = get_bg_selected(container);
											
											if(color_selected==false) {
												color_selected = {};
											}
                                            if (!check_is_use(color_hex,container)) {
                                                color_selected[color_hex] = color_name;
                                            }
                                            $container.find('.shirt_color_seleted').val(JSON.stringify(color_selected));
										   
                                        }
                                        function remove_bg_selected(color_hex,container) {
											$container = jQuery(container);
                                            var color_selected = {};
											
                                            jQuery.each(get_bg_selected(container), function (key, value) {
                                                if (color_hex != key) {
                                                    color_selected[key] = value;
                                                }
                                            });
                                            console.log('remove bg selected');
                                            console.log(color_hex);
                                            console.log('remove bg selected 2');
                                            console.log(color_selected);
                                            $container.find('.shirt_color_seleted').val(JSON.stringify(color_selected));
                                            show_bg_shirt(container,false);
                                        }
                                        function show_bg_shirt(container,color_hex) {
											$container = jQuery(container);
                                            var choose_bg = '';
                                            var counter = 0;
											jQuery.each(get_bg_selected($container), function (key, value) {
												counter++;  
											});
                                            jQuery.each(get_bg_selected($container), function (key, value) {
                                                
												var current_class='';
												if(key==color_hex||counter==1){
													current_class = "current";	
												}
                                                choose_bg += '<li data-value="#' + key + '" data-texture="" class="shirt-color-sample js-color '+current_class+'" title="' + value + '" style="background-color:#' + key + ';color:white;" ><span ></span><span ></span></li>';
                                            });
                                            for (i = 1; i <= 5 - counter; i++) {
                                                choose_bg += '<li data-value="none"  class="shirt-color-nocolor js-color" title="" style="background-color:transparent;" ><span></span><span></span></li>';
                                            }
                                            choose_bg += '<div class="color-picker color_picker_multi js-color-picker-multi" title="Change colour"><div class="color_picker_multi__more" ></div></div>';
                                            $container.find('.ssp_color').html(choose_bg);
                                        }
                                        function check_is_use(color,el) {
                                            var is_use = false;
											var color_selected = get_bg_selected(el);
											if(color_selected==false) {
												return false;
											}
                                            jQuery.each(get_bg_selected(el), function (key, value) {
                                                if (color == key) {
                                                    is_use = true;
                                                }
                                            });
                                            return is_use;
                                        }
                                        function show_color_pick(el) {
											
											var index_fpd = jQuery(el).attr('fpd-index');
                                            var choose_bg = '';
                                            var text_color = '#fff';
                                            jQuery.each(colors_design[index_fpd], function (key, value) {
                                                var color_hex = key;
                                                color_hex = color_hex.substring(1);
                                                if (color_hex == 'ffffff') {
                                                    text_color = '#000'
                                                } else {
                                                    text_color = '#fff';
                                                }
                                                if (check_is_use(color_hex,el)) {
                                                    choose_bg += '<li data-value="#' + color_hex + '" data-texture="" class="shirt-color-sample selected js-color" title="' + value + '" style="background-color:#' + color_hex + ';color:' + text_color + ';" ><span >✓</span><span ></span></li>';
                                                } else {
                                                    choose_bg += '<li data-value="#' + color_hex + '" data-texture="" class="shirt-color-sample js-color" title="' + value + '" style="background-color:#' + color_hex + ';color:' + text_color + ';" ><span ></span><span ></span></li>';
                                                }

                                            });
                                            return choose_bg;
                                        }
										function close_popup_color() {
											jQuery('.colors.shirt-colors.containertip').remove();
										}
										function set_shirt_colour(current_color,container){
                                        	
											if(current_color==false){
												var color_hex = get_shirt_colour(container);
											}else{
												var color_hex = current_color;	
											}
											
											var color_name = '';
											jQuery.each(color_shirt, function (key, value) {
												if(key == color_hex){
													color_name = value;
												}
											});
                                          
											set_bg_selected(color_hex, color_name,container);
											show_bg_shirt(container,color_hex);
										}
										function get_shirt_colour(container) {
											var current_color = false;
											if(jQuery(container).find('.ssp_color > li.shirt-color-sample.current').length>0){
												current_color =  jQuery(container).find('.ssp_color > li.shirt-color-sample.current').first().data('value').substring(1);
                                                current_color_2 = false;
                                                jQuery.each(fancyProductDesigner.getProduct(), function (index, elements) {
												jQuery.each(elements.elements, function (index, value) {
													if (value.type == 'image' && value.parameters.isInitial) {
														if(value.parameters.currentColor!=false){
														    current_color_2 = value.parameters.currentColor.substring(1);
                                                           
														}
													}
												});

												});
                                                if(current_color !=current_color_2&&current_color_2!=false){
                                                    remove_bg_selected(current_color,container);
                                                    current_color = current_color_2;
                                                    jQuery(container).find('.ssp_color > li.shirt-color-sample.current').first().data('value',current_color_2);
                                                    jQuery(container).find('.ssp_color > li.shirt-color-sample.current').first().css('background-color',current_color_2); 
                                                    
                                                }
                                                
											}
                                            if(!current_color){
                                            
												jQuery.each(fancyProductDesigner.getProduct(), function (index, elements) {
												jQuery.each(elements.elements, function (index, value) {
													if (value.type == 'image' && value.parameters.isInitial) {
														if(value.parameters.currentColor!=false){
															current_color = value.parameters.currentColor.substring(1);
															
														}
													}
												});

												});
                                                
                                                if(!current_color){
                                                  
                                                    var index_fpd = jQuery(container).attr('fpd-index');
                                                    
                                                    if(default_colors_design[index_fpd]!=false){
                                                        current_color = default_colors_design[index_fpd].substring(1);
                                                    }else{
                                                        jQuery.each(colors_design[index_fpd], function (key, value) {
                                                       
                                                        if(current_color==false){
                                                          var color_hex = key;
                                                                color_hex = color_hex.substring(1);
                                                                current_color = color_hex;
                                                            }
                                                        });
                                                    }
                                                }
											}
                                            
											
                                            console.log('dev444');
                                            console.log(current_color);
											return current_color;
										}
										var front;
										var back;
										function reload_thumbnail_image(container){
                                          
											$container = jQuery(container);
											jQuery(container).find('.design-fpd-title').html(get_title_fancy($container.attr('fpd-index')));
											$container.find('.price_content').css('display', 'none');
											$container.find('.price_loading').css('display', 'block');
											setTimeout(function () {
                                            //front= fancyProductDesigner.getProductDataURL();
                                            //back =front;
											 front = fancyProductDesigner.getProductFrontURL();
                                            back = fancyProductDesigner.getProductBackURL();
											$container.find('.product_thumbnail').attr('src',front);
                                            $container.find('#design_image_back').val(encodeURIComponent(back));
                                            $container.find('#design_image_front').val(encodeURIComponent(front));
											if(jQuery('input[name=feature_side]:checked').val()=='front'){
												jQuery('.design_view.back').css('background-image', "url('" + back + "')");
												jQuery('.design_view.front').css('background-image', "url('" + front + "')");
											}else{
												jQuery('.design_view.back').css('background-image', "url('" +  front+ "')");
												jQuery('.design_view.front').css('background-image', "url('" + back + "')");
											}
											
											$container.find('.price_content').css('display', 'table');
											$container.find('.price_loading').css('display', 'none');
											}, 800);
										}
										function get_element_color(){
											var title ='';
											jQuery.each(fancyProductDesigner.getProduct(), function (index, elements) {
												jQuery.each(elements.elements, function (index, value) {
													if (value.type == 'image' && value.parameters.isInitial) {
														if(value.parameters.colors!=false && typeof value.parameters.colors === "object"){
															title =  value.title;
														}
													}
												});

											});
											return title;
										}
										function get_object_colors(){
											var colors;
											jQuery.each(fancyProductDesigner.getProduct(), function (index, elements) {
												jQuery.each(elements.elements, function (index, value) {
													if (value.type == 'image' && value.parameters.isInitial) {
														if(value.parameters.colors!=false && typeof value.parameters.colors === "object"){
															colors =  value.parameters.colors;
														}
													}
												});

											});
											return colors;
										}
										
										function change_color_shirt(color_hex,container){
												//fancyProductDesigner.selectProduct(1);
												//console.log(fancyProductDesigner.getProduct());
											
												var el_title = get_element_color();
												
												var current_el = fancyProductDesigner.getElementByTitle(el_title);
												current_el.params.currentColor = '#'+color_hex;
												
												fancyProductDesigner.setElementParameters(el_title, current_el.params);
												
												reload_thumbnail_image(container);
										}
										function check_design_exist(fancy_product_id){
											var index_ = get_index_fancy(fancy_product_id);
											var exist = false;
											jQuery( ".box_design" ).each(function( index ) {
												if(jQuery( this ).attr('fpd-index')==index_){
													exist = true;
												}
											});
											return exist;
											
										}
										function show_product_select(container){
											var div_before = '<div class="ssp_product_select" ><div id="ui" class="ssp_select_boxes"><select id="item-options-dropdown" class="form-control form__select_menu select_product_group" >';
											var div_after = '</select><button type="button" class="button button--primary ssp_add_button" title="Add" ></button></div></div>';
											var  options='';
											jQuery.each(fancy_list, function (index, value) {
												if(!check_design_exist(index)){
														 options += '<option value="'+index+'" name="'+value+'" >'+value+'</option>';
												}
											});
											jQuery(container).html(div_before+options+div_after);
										}
										
										function load_style_tab(){
											
											var index_fpd = fancyProductDesigner.getcurrentIndexProduct();
											if(jQuery('.box_design').length==1&&jQuery('.box_design').first().attr('fpd-index')!=index_fpd){
												jQuery('.box_design').first().attr('fpd-index',index_fpd);
											}
											jQuery('.box_design').removeClass('active');
											jQuery('.box_design').each(function(){
												var fpd_index_ = jQuery( this ).attr('fpd-index');
												if(fpd_index_==index_fpd){
													jQuery( this ).addClass('active');
												}
											});
											var container = jQuery('.box_design.active');
											if(container.length==0){
												container = jQuery('.box_design').first().addClass('active');
											}
											reload_thumbnail_image(container);
                                            
                                            
											set_shirt_colour(false,container);
										}
										function load_design_active_color(){
											var container = jQuery('.box_design.active');
											fancyProductDesigner.selectProduct(container.attr('fpd-index'));
										}
										function load_design_active(){
											//console.log(fancyProductDesigner.getProduct());
											var container = jQuery('.box_design.active');
											fancyProductDesigner.selectProduct(container.attr('fpd-index'));
											
											setTimeout(function () {
													if(jQuery(container).find('.ssp_color > li.shirt-color-sample.current').length){
														var current_color =  jQuery(container).find('.ssp_color > li.shirt-color-sample.current').first().data('value').substring(1);
													}else{
														var current_color=false;
													}
													set_shirt_colour(current_color,container);
													setTimeout(function () {
														reload_thumbnail_image(container); 
														change_color_shirt(current_color,container);
														//reload_thumbnail_image(container); 														
													},200);
											}, 300);
											
											
										}
										function get_title_fancy(index){
											var title_fpd = '';
											
											jQuery.each(fancy_list, function (index_, value) {
												if(get_index_fancy(index_)==index){
													title_fpd =  value;
												} 
											});
											return title_fpd;
										}
										function get_index_fancy(fancy_product_id){
											var index_fpd_ = false;
											jQuery.each(fancy_index, function (index, value) {
												if(fancy_product_id==value){
													index_fpd_ =  index;
												} 
											});
											return index_fpd_;
										}
										jQuery(document).ready(function () {
										jQuery('input[name=feature_side]').click(function(){
											if(jQuery('input[name=feature_side]:checked').val()=='front'){
												jQuery('.design_view.back').css('background-image', "url('" + back + "')");
												jQuery('.design_view.front').css('background-image', "url('" + front + "')");
											}else{
												jQuery('.design_view.back').css('background-image', "url('" + front + "')");
												jQuery('.design_view.front').css('background-image', "url('" + back + "')");
											}
											
										});	
											
										jQuery(document).on('click', '.ssp_trashcan', function (e) {
											jQuery(this).parents( ".box_design" ).remove();
											if(jQuery('.box_design.active').length==0){
												jQuery('.box_design').last().addClass('active');
												load_design_active();
											}
											if(jQuery('.style_adder').length<1){
													jQuery(add_style_content).insertAfter(jQuery('.box_design').last());
											}
											
										});
										jQuery(document).on('click', '.ssp_add_button', function (e) {
											
											jQuery('.box_design').last().clone().insertAfter(jQuery('.box_design').last());	
											if(jQuery('.box_design').last().find('.ssp_trashcan').length==0)
											{
												jQuery('<div class="ssp_trashcan" ></div>').insertAfter(jQuery('.box_design').last().find('.ssp_color'));
													
											}
											
											var fancy_product_id = jQuery(this).parents( ".style_adder" ).find('#item-options-dropdown').val();
											
											jQuery('.box_design').removeClass('active');
											jQuery(jQuery('.box_design').last()).addClass('active').attr('fpd-index',get_index_fancy(fancy_product_id));
											jQuery(jQuery('.box_design').last()).find('.shirt_color_seleted').val('');
											jQuery(jQuery('.box_design').last()).find('.ssp_color').html('');
											jQuery(this).parents( ".style_adder" ).remove();
											load_design_active();
										});
										
										jQuery(document).on('click', '.box_design', function (e) {
											if(!jQuery(this).hasClass('active')){
												
											jQuery('.box_design').removeClass('active');
											jQuery(this).addClass('active');
											load_design_active();
											
											}
										});
										
										// Add new design
										jQuery(document).on('click', '.style_adder', function (e) {
											 if(!jQuery(this).has( "button" ).length && jQuery('.style_adder').length <2 ){
												 var design_length = jQuery('.box_design').length;
												 var fancy_counter =0;
												 jQuery.each(fancy_list, function (index, value) {
													 fancy_counter++;
												 });
												 if(design_length+1<fancy_counter){
													jQuery(this).clone().insertAfter(this);
												 }
												 show_product_select(this);
											 }
										});	
										jQuery(document).on('click', '.campaign_price', function (e) {
											return false;
											
										});
										
										
                                        jQuery(document).on('click', '.containertip .shirt-color-sample.js-color', function (e) {
                                            var container = jQuery(this).parents( ".box_design" );
                                            if (!jQuery(this).hasClass('selected')) {
                                                var color_name = jQuery(this).attr('title');
                                                var color_hex = jQuery(this).data('value');
												 color_hex = color_hex.substring(1);
												if(!container.hasClass('active')){
													jQuery('.box_design').removeClass('active');
													container.addClass('active');
													load_design_active_color();
													setTimeout(function () {
														set_bg_selected(color_hex, color_name,container);
														close_popup_color();													   
														show_bg_shirt(container,color_hex);												
														change_color_shirt(color_hex,container);
													}, 300);
												}else{
												set_bg_selected(color_hex, color_name,container);
												close_popup_color();
												show_bg_shirt(container,color_hex);												
												change_color_shirt(color_hex,container);
												
												}
												
                                            }
											return false;
                                        });
										
									
                                        jQuery(document).on('click', '.containertip .shirt-color-sample.selected.js-color', function (e) {
											$_container = jQuery(this).parents( ".box_design" );
											var color_current = get_shirt_colour($_container);

                                            var color_hex = jQuery(this).data('value');
                                            color_hex = color_hex.substring(1);
                                            if ($_container.find(".ssp_color  >  li.shirt-color-sample").length > 1) {
                                                remove_bg_selected(color_hex,$_container);
                                                close_popup_color();
												
												if(color_current==color_hex){
													 setTimeout(function () {
													 var color_hex_last  =$_container.find(".ssp_color  >  li.shirt-color-sample").last().data('value');
													 color_hex_last = color_hex_last.substring(1);
													 change_color_shirt(color_hex_last,$_container);
													 $_container.find(".ssp_color  >  li.shirt-color-sample").last().addClass('current');
													 
													 },100);
												}else if($_container.find(".ssp_color  >  li.shirt-color-sample").length==1){
													 setTimeout(function () {
													 var color_hex_last  =$_container.find(".ssp_color  >  li.shirt-color-sample").first().data('value');
													 color_hex_last = color_hex_last.substring(1);
													 change_color_shirt(color_hex_last,$_container);
													 },100);
												}
												
                                            }
											return false;
                                        });
										function select_current_color(color_hex,$_container){
											jQuery($_container).find('.ssp_color > li.shirt-color-sample').removeClass('current');
											jQuery($_container).find('.ssp_color > li.shirt-color-sample').each(function(){
												var color=jQuery(this).data('value').substring(1);
												if(color==color_hex){
													jQuery(this).addClass('current');
												}
											});
										}
										jQuery(document).on('click','.ssp_color > li.shirt-color-sample', function () {
											 $_container = jQuery(this).parents( ".box_design" );
											 var color_hex = jQuery(this).data('value');
											 color_hex = color_hex.substring(1);
											 change_color_shirt(color_hex,$_container);
											 select_current_color( color_hex,$_container)
											 return false;
										 });
                                        jQuery(document).on('click', '.js-color-picker-multi, .shirt-color-nocolor', function () {
											$_container = jQuery(this).parents( ".box_design" );
											
                                           if ($_container.find('.colors.shirt-colors.containertip').length <= 0) {
                                                var html_color_pick = '<div class="colors shirt-colors containertip  containertip--open js-colors-popup" ><ul class="colors-in-use"></ul><ul class="colors-available">' + show_color_pick($_container ) + '</ul></div>';
                                                $_container.find('.color_picker_multi__more').after(html_color_pick);
                                            }
											return false;
                                            //jQuery('.containertip').addClass('containertip--open');

                                        });
                                        jQuery(document).on("mouseenter", '.ssp_color > li.shirt-color-sample', function () {
											var container = jQuery(this).parents( ".box_design" );
                                            if (container.find(".ssp_color  >  li.shirt-color-sample").length > 1) {
                                                jQuery(this).children("span:last-child").replaceWith('<div class="shirt-color-delete" title="Remove" >X</div>');
                                            }

                                        });
                                        jQuery(document).on("mouseleave", '.ssp_color > li.shirt-color-sample', function () {
                                            jQuery(this).children("div:last-child").replaceWith('<span ></span>');

                                        });
											jQuery(document).on('click', '.shirt-color-delete', function () {
												$_container = jQuery(this).parents( ".box_design" );
												var color_hex = jQuery(this).parent().data('value');
												color_hex = color_hex.substring(1);
												var color_current = get_shirt_colour($_container);
												
												
												remove_bg_selected(color_hex,$_container);
												close_popup_color();
												if(color_current==color_hex){
													 setTimeout(function () {
													 var color_hex_last  =$_container.find(".ssp_color  >  li.shirt-color-sample").last().data('value');
													 color_hex_last = color_hex_last.substring(1);
													 change_color_shirt(color_hex_last,$_container);
													 $_container.find(".ssp_color  >  li.shirt-color-sample").last().addClass('current');
													 
													 },100);
												}else if($_container.find(".ssp_color  >  li.shirt-color-sample").length==1){
													 setTimeout(function () {
													 var color_hex_last  =$_container.find(".ssp_color  >  li.shirt-color-sample").first().data('value');
													 color_hex_last = color_hex_last.substring(1);
													 change_color_shirt(color_hex_last,$_container);
													 },100);
												}
												return false;
											});
										
										
											jQuery(document).mouseup(function (e)
											{
												var container = jQuery('.colors.shirt-colors.containertip');

												if (!container.is(e.target) // if the target of the click isn't the container...
														&& container.has(e.target).length === 0) // ... nor a descendant of the container
												{
													close_popup_color();
												}
											});
										});
										
									
						 function analytic_object_price_design(is_shirt_colour,_index) {
                        var index_fpd = _index;
						
                        if (is_shirt_colour) {
                            return fpd_prices[index_fpd]['color'];
                        } else {
                            return fpd_prices[index_fpd]['base'];
                        }
                    }				
                    function analytic_object_price(is_shirt_colour) {
                        var index_fpd = fancyProductDesigner.getcurrentIndexProduct();
						
                        if (is_shirt_colour) {
                            return fpd_prices[index_fpd]['color'];
                        } else {
                            return fpd_prices[index_fpd]['base'];
                        }
                    }
                    function check_imageupload(elements) {
                        var is_imageupload = false;
                        jQuery.each(elements, function (index, value) {
                            if (value.type == 'image' && !value.parameters.isInitial) {
                                console.log('Is image upload: ');
                                is_imageupload = true;
                            }
                        });
                        return is_imageupload;
                    }
                    function check_multicolor(elements) {
                        var colours = [];
                        i = 0;
                        jQuery.each(elements, function (index, value) {
                            var colour = value.parameters.currentColor;
                            if (colour == false)
                            {
                                colour = '#000000';
                            }
                            //isInitial
                            if (!value.parameters.isInitial) {
                                if (jQuery.inArray(colour, colours) <= -1) {
                                    colours[i] = colour;
                                    i++;
                                }
                            }
                        });
                        return colours;
                    }
                    function check_have_custom(elements) {
                        var _is_custom = false;
                        jQuery.each(elements, function (index, value) {

                            //isInitial
                            if (!value.parameters.isInitial) {
                                _is_custom = true;
                            }
                        });
                        return _is_custom;
                    }
					function get_base_price_design(is_shirt_colour,_index) {
						
                        var price_options = analytic_object_price_design(is_shirt_colour,_index);
                        var goal_total = parseInt(jQuery('#sales_goal_input').val());
                        var min_value = 0;
                        var min_price;
                        jQuery.each(price_options, function (index, value) {
                            if (index <= goal_total && index > min_value) {
                                min_value = index;
                            }
                        });
                        jQuery.each(price_options, function (index, value) {
                            if (index == min_value) {
                                min_price = value;
                            }
                        });
                        if (jQuery.isEmptyObject(min_price)) {
                            return 0;
                        }
                        return min_price['base_price'];
                    }
                    function get_base_price(is_shirt_colour) {
                        var price_options = analytic_object_price(is_shirt_colour);
                        var goal_total = parseInt(jQuery('#sales_goal_input').val());
                        var min_value = 0;
                        var min_price;
                        jQuery.each(price_options, function (index, value) {
                            if (index <= goal_total && index > min_value) {
                                min_value = index;
                            }
                        });
                        jQuery.each(price_options, function (index, value) {
                            if (index == min_value) {
                                min_price = value;
                            }
                        });
                        if (jQuery.isEmptyObject(min_price)) {
                            return 0;
                        }
                        return min_price['base_price'];
                    }
                    function analytic_prices_face(index, elements, is_shirt_colour) {

                        var colours = check_multicolor(elements.elements);
                        var is_imageupload = check_imageupload(elements.elements);
                        var is_custom = check_have_custom(elements.elements);

                        if (is_custom) {
                            var price_options = analytic_object_price(is_shirt_colour);
                            var goal_total = parseInt(jQuery('#sales_goal_input').val());
                            var min_value = 0;
                            var min_price;
                            jQuery.each(price_options, function (index, value) {
                                if (parseInt(index) <= goal_total && parseInt(index) > parseInt(min_value)) {
                                    min_value = parseInt(index);
                                }
                            });

                            jQuery.each(price_options, function (index, value) {
                                if (index == min_value) {
                                    min_price = value;
                                }
                            });
                        
                            if (jQuery.isEmptyObject(min_price)) {
                                return 0;
                            }

                            if (colours.length >= 2 || is_imageupload) {
                                if (index = 0) {
                                    return min_price['front_multi_color_print'];
                                } else {
                                    return min_price['back_multi_color_print'];
                                }
                            } else {
                                if (index = 0) {
                                    return min_price['front_color_print'];
                                } else {
                                    return min_price['back_color_print'];
                                }
                            }
                        } else {
                            return 0;
                        }
                    }
                    var printed_both = 0;
                    function analytic_prices(items, is_shirt_colour)
                    {
                        var price = 0;
                        jQuery.each(items, function (index, elements) {

                            price += analytic_prices_face(index, elements, is_shirt_colour);
                            if (index == 1) {
                                printed_both = analytic_prices_face(index, elements, is_shirt_colour);
                            }
                        });
                        return price;
                    }
					 function analytic_prices_face_design(index, elements, is_shirt_colour,design_index) {

                        var colours = check_multicolor(elements.elements);
                        var is_imageupload = check_imageupload(elements.elements);
                        var is_custom = check_have_custom(elements.elements);

                        if (is_custom) {
                            var price_options = analytic_object_price_design(is_shirt_colour,design_index);
                            var goal_total = parseInt(jQuery('#sales_goal_input').val());
                            var min_value = 0;
                            var min_price;
                            jQuery.each(price_options, function (index, value) {
                                if (parseInt(index) <= goal_total && parseInt(index) > parseInt(min_value)) {
                                    min_value = parseInt(index);
                                }
                            });

                            jQuery.each(price_options, function (index, value) {
                                if (index == min_value) {
                                    min_price = value;
                                }
                            });
                          
                            if (jQuery.isEmptyObject(min_price)) {
                                return 0;
                            }
                            if (colours.length >= 2 || is_imageupload) {
                                if (index = 0) {
									
                                    return min_price['front_multi_color_print'];
                                } else {
                                    return min_price['back_multi_color_print'];
                                }
                            } else {
                                if (index = 0) {
                                    return min_price['front_color_print'];
                                } else {
                                    return min_price['back_color_print'];
                                }
                            }
                        } else {
                            return 0;
                        }
                    }
					 function analytic_prices_design(items, is_shirt_colour,design_index)
                    {
                        var price = 0;
                        jQuery.each(items, function (index, elements) {

                            price += analytic_prices_face_design(index, elements, is_shirt_colour,design_index);
                            if (index == 1) {
                                printed_both = analytic_prices_face_design(index, elements, is_shirt_colour,design_index);
                            }
                        });
                        return price;
                    }
                    function check_shirt_colour() {
                        var shirt_colour = false;
                        jQuery.each(fancyProductDesigner.getProduct(), function (index, elements) {
                            jQuery.each(elements.elements, function (index, value) {
                                if (value.type == 'image' && value.parameters.isInitial) {
                                    if (value.parameters.currentColor != false && value.parameters.currentColor != '#ffffff') {
                                        shirt_colour = true;
                                    }
                                }
                            });

                        });
                        return shirt_colour;
                    }

                    function calculator_price(totalPrice_) {
                        jQuery('#price_preview_content').css('opacity', 0);
                        setTimeout(function () {
                            _calculator_price();
							profitCaculator(parseInt(jQuery('#sales_goal_input').val()));
                        }, 1000);

                    }
                    function price_format(price) {
                        return parseFloat(Math.round(price * 100) / 100).toFixed(2);
                    }
					function check_shirt_colour_design(el){
						var counter = 0;
						var last_color = false;
						jQuery(el).find('.ssp_color .shirt-color-sample').each(function(){
							counter++;
							last_color = jQuery(this).data('value');
						});
						if(last_color==false||last_color==''){
                        
                            jQuery.each(fancyProductDesigner.getProduct(), function (index, elements) {
												jQuery.each(elements.elements, function (index, value) {
													if (value.type == 'image' && value.parameters.isInitial) {
														if(value.parameters.currentColor!=false){
														    last_color = value.parameters.currentColor;
                                                           
														}
													}
												});

								});
                        }
                        var index_fpd = jQuery(el).attr('fpd-index');
                                   var default_color = false;                 
                               if(default_colors_design[index_fpd]!=false){
                                                        default_color = default_colors_design[index_fpd].substring(1);
                              }else{
                              jQuery.each(colors_design[index_fpd], function (key, value) {
                                                       
                             if(default_color==false){
                                        default_color = key;
                                                           
                                  }
                             });
                         }
                         console.log(default_color);
                         console.log(counter);
                         console.log(last_color);
						if(counter>1){
							return true;
						}else if(counter ==1 && last_color!=default_color){
                            return true;
                        }else if((counter == 0 && last_color!=default_color )&& last_color!=false ){
                            return true;
                        }else{
                            return false;
                        }
					}
					function get_shirt_colours(el){
						var counter = 0;
						var colors = {};
						jQuery(el).find('.ssp_color .shirt-color-sample').each(function(){
							var color_hex = jQuery(this).data('value');
							colors[counter] = color_hex.substring(1);
							counter++;
						});
						
						return colors;
					}
					function initial_design_active(){
						var no_active = true;
                        var index_fpd = fancyProductDesigner.getcurrentIndexProduct();
						jQuery('.box_design').each(function(index){
							if(jQuery(this).hasClass('active')){
								no_active=false;
                                 jQuery(this).attr('fpd-index',index_fpd);
							}
						});
						if(no_active){
							jQuery('.box_design').first().addClass('active');
                            jQuery('.box_design').first().attr('fpd-index',index_fpd);
                            
						}
					}
                    function _calculator_price(replace_price) {
						initial_design_active();
						jQuery('.box_design').each(function(index){
							var fancy_index = jQuery(this).attr('fpd-index');
							
							is_shirt_colour_design = check_shirt_colour_design(this);
                             console.log('is_shirt_colour_design');
                            console.log(is_shirt_colour_design);
							var totalPrice_design = 0;
							totalPrice_design = parseFloat(get_base_price_design(is_shirt_colour_design,fancy_index));	
						
							
							var extra_price_design = parseFloat(analytic_prices_design(fancyProductDesigner.getProduct(), is_shirt_colour_design,fancy_index));						
							totalPrice_design+= extra_price_design;							
							 var suggest_price_design = 0;
							if (is_shirt_colour_design) {
								if (printed_both) {
									suggest_price_design = 35;
								} else {
									suggest_price_design = 30;
								}

							} else {
								if (printed_both) {
									suggest_price_design = 30;
								} else {
									suggest_price_design = 25;
								}
							}
							jQuery(this).find('#cost_price').val(price_format(totalPrice_design));
							
							if (replace_price) {
								var price_calculator_design = price_format(parseFloat(jQuery(this).find("#campaign_price").val()));
								jQuery(this).find("#campaign_price").val(price_calculator_design);
                                //jQuery(this).find("#campaign_price").val(0);
							} else {
								if(jQuery(this).find("#campaign_price").val()==0){
									//jQuery(this).find("#campaign_price").val(suggest_price_design);
								}
								var price_calculator_design = suggest_price_design;
							}
							
							//jQuery(this).find('#campaign_price').attr('title', 'TeeM8 recommends $' + suggest_price_design);
							//jQuery(this).find('#campaign_price').attr('data-original-title', 'TeeM8 recommends $' + suggest_price_design);
                            if(jQuery(this).find("#campaign_price").val()==0){
                                jQuery(this).find('.profit_per').html('$' + 0);
                            }else{
                                var profit_price_design = price_format(price_calculator_design - totalPrice_design);
                                if(profit_price_design<0){
                                    profit_price_design=0;
                                }
                                jQuery(this).find('.profit_per').html('$' + profit_price_design);
                            }

							
							if(jQuery(this).hasClass('active')){
								var n_sale = jQuery('#sales_goal_input').val();
								jQuery('.base-cost-intro span').html('Base cost @ ' + n_sale + ' units');
								jQuery('#price_preview').html('$' + price_format(totalPrice_design));
								jQuery('#price_preview_content').css('opacity', 1);
							}
							
							
						});
						//jQuery('[data-toggle="tooltip"]').tooltip();
                    }
                    function calculator_profit(el) {
                        var price = parseFloat(jQuery(el).find("#campaign_price").val());
                        var cost = parseFloat(jQuery(el).find("#cost_price").val());

                        var profit_price = price_format(price - cost);
                        if(profit_price>0){
                            jQuery(el).find('.profit_per').html('$' + profit_price);
                        }else{
                            jQuery(el).find('.profit_per').html('$' + 0);
                        }

                    }
                    function profitCaculator(qty) {
						var total_profit_global_min  = 0;
						var total_profit_global_max  = 0;
						jQuery('.box_design').each(function(index){
							var  fancy_index =jQuery(this).attr('fpd-index');
							var price = parseFloat(jQuery(this).find("#campaign_price").val());
							var cost = parseFloat(jQuery(this).find("#cost_price").val());

							if (price < cost || isNaN(price)) {
								return;
							}
							var total_price = price * qty;
							var total_cost = cost * qty;
							var total_profit = total_price - total_cost;
							if(total_profit_global_min!=0){
								if(total_profit_global_min>total_profit){
									total_profit_global_min = total_profit;
								}
							}else{
								total_profit_global_min = total_profit;
							}
							
							if(total_profit_global_max!=0){
								if(total_profit_global_max<total_profit){
									total_profit_global_max = total_profit;
								}
							}else{
								total_profit_global_max = total_profit;
							}
							
							calculator_profit(this);
						});


						if(total_profit_global_max!=total_profit_global_min){
							 jQuery("#global_profit").html('$' + parseInt(total_profit_global_min) + '-$' + parseInt(total_profit_global_max) + '+');
						}else{
							 jQuery("#global_profit").html('$' + parseInt(total_profit_global_min) + '+');
						}
                       
                        _calculator_price(true);
                    }
                    function active_step(index) {
                        if (index == 1)
                        {
                            is_allow_sticky = false;
                            jQuery('#masthead').removeClass('sticky');
                            jQuery('.footer_banner').css('display', 'none');
                        } else {
                            is_allow_sticky = true;
                            jQuery('.footer_banner').css('display', 'block');
                        }
                        var panel_no = index;
                        jQuery('#launch-progression li').removeClass('active-step');
                        var current = jQuery('#launch-progression li').get(index - 1);
                        jQuery(current).addClass('active-step');
                        jQuery('.panel').css('display', 'none');
                        jQuery('.panel').each(function () {
                            if (jQuery(this).hasClass('panel_' + panel_no)) {
                                jQuery(this).css('display', 'block');
                            }
                        });
                        profitCaculator(parseInt(jQuery('#sales_goal_input').val()));
                        validate_campaign.resetForm();
                       
						load_style_tab();
						
						

                    }

                   jQuery(document).ready(function () {
							
							initial_shirt_colors();
                        jQuery('#campaign-info input').keydown(function (event) {
                            if (event.keyCode == 13) {
                                event.preventDefault();
                                return false;
                            }
                        });


                       // jQuery('[data-toggle="tooltip"]').tooltip();

                        var login_success = false;

                        // Add event step 
                        jQuery('#launch-progression a, .button_transaction').not('.campaign_submit').click(function () {
                            //console.log(JSON.parse(window.localStorage.getItem('fancy-product-designer-16')));
                            active_step(jQuery(this).data('step'));
                        });

                        // Initial Popup
                        jQuery(".login_popup").colorbox({inline: true, width: "50%",
                            close: '×',
                            onClosed: function () {
                                if (login_success)
                                    jQuery(".loading_popup").click();
                            }
                        });
                        jQuery(".loading_popup").colorbox({inline: true, width: "50%", close: '×'});

                        // Initial Switch register & login 
                        jQuery(".switch_login_register").click(function () {
                            jQuery(".toggle_div").toggle();
                            jQuery(".login_popup").colorbox.resize();
                            validate_login.resetForm();
                            validate_register.resetForm();

                        }
                        );

                        // Validate form login
                        var validate_login = jQuery("#ajax_login").validate({
                            rules: {
                                username: "required",
                                password: "required"
                            },
                            messages:
                                    {
                                        username: "Please enter a username",
                                        password: "Please enter a password"
                                    }


                        });
                        // Validate form register
                        var validate_register = jQuery("#ajax_register").validate({
                            rules: {
                                user_login: "required",
                                user_email: {
                                    required: true,
                                    email: true
                                },
                                user_pass: {
                                    required: true,
                                    minlength: 6
                                },
                                user_pass_confirm: {
                                    required: true,
                                    minlength: 6,
                                    equalTo: "#user_pass"
                                }
                            },
                            messages:
                                    {
                                        user_pass: {
                                            required: "Please provide a password",
                                            minlength: "Your password must be at least 5 characters long"
                                        },
                                        user_pass_confirm: {
                                            required: "Please provide a password",
                                            minlength: "Your password must be at least 5 characters long",
                                            equalTo: "Please enter the same password as above"
                                        },
                                        user_email: "Please enter a valid email address",
                                        user_login: "Please enter a username"
                                    },
                            errorPlacement: function (error, element) {
                                error.insertAfter(element);
                                jQuery(".login_popup").colorbox.resize();
                            }
                        }
                        );



                        // Validate campaign form
                        validate_campaign = jQuery("#campaign-info").validate({
                            rules: {
                                name: "required",
                                content: "required",
                                sales_goal: {
                                    required: true,
                                    digits: true,
                                    min: 15,
                                    max: 1000
                                }
								,
                                price: {
                                    required: true,
                                    number: true,
                                    min: function () {
                                        return 1;
                                        //return parseFloat(jQuery("#cost_price").val())
                                    }
                                }
                            },
                            messages:
                                    {
                                        name: {
                                            required: "Please enter a campaign title"
                                        },
                                        content: {
                                            required: "Please enter a description"
                                        }
										,
                                        price: {
                                            required: "Please enter a selling price",
                                            min: function () {
                                                return '$' + jQuery("#cost_price").val() + " minimum"
                                            }
                                        }
                                    },
                            errorPlacement: function (error, element) {
                                if (element.attr("name") == "sales_goal") {
                                    error.insertAfter(".sales_goal_content");
                                } else if (element.attr("name") == "price") {
                                    error.insertAfter(".price_content_3  .input-group ");

                                } else {
                                    error.insertAfter(element);
                                }
                            }
                        });

                        // Register ajax add event
                        jQuery('#ajax_register .button').click(function (e) {

                            if (!jQuery("#ajax_register").valid())
                            {
                                jQuery(".login_popup").colorbox.resize();
                                return false;
                            }
                            var data_register = jQuery("#ajax_register").serialize();
                            jQuery.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: ajax_login_object.ajaxurl,
                                data: {
                                    'action': 'ajaxRegister', //calls wp_ajax_nopriv_ajaxlogin
                                    'user_login': $('form#ajax_register #user_login').val(),
                                    'user_email': $('form#ajax_register #user_email').val(),
                                    'user_pass': $('form#ajax_register #user_pass').val(),
                                    'user_pass_confirm': $('form#ajax_register #user_pass_confirm').val(),
                                    'security': $('form#ajax_register #security').val()},
                                success: function (data) {
                                    if (data.status == 'error') {
                                        var html_error = "<span class='error_message'>" + data.message + "</span>";
                                        jQuery('form#ajax_register p.status').html(html_error);
                                        jQuery(".login_popup").colorbox.resize();
                                    }
                                    if (data.status == 'success') {
                                        login_success = true;
                                        jQuery(".login_popup").colorbox.close();
                                        submit_campaign();
                                    }
                                }
                            });
                            e.preventDefault();
                        });

                        // Login ajax add event
                        jQuery('#ajax_login .button').click(function (e) {

                            if (!jQuery("#ajax_login").valid())
                            {
                                jQuery(".login_popup").colorbox.resize();
                                return false;
                            }

                            var data_login = jQuery("#ajax_login").serialize();
                            jQuery.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: ajax_login_object.ajaxurl,
                                data: {
                                    'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                                    'username': $('form#ajax_login #username').val(),
                                    'password': $('form#ajax_login #password').val(),
                                    'security': $('form#ajax_login #security').val()},
                                success: function (data) {
                                    jQuery('form#ajax_login p.status').text(data.message);
                                    if (data.loggedin == true) {
                                        login_success = true;
                                        jQuery(".login_popup").colorbox.close();
                                        submit_campaign();
                                    }
                                }
                            });
                            e.preventDefault();
                        });

                        // Add event ajax submit campaign
                        jQuery('.campaign_submit').click(function () {

                            if (!jQuery("#campaign-info").valid()) {
                                return false;
                            }

                            var data = {
                                action: 'is_user_logged_in'
                            };
                            jQuery.post(ajax_login_object.ajaxurl, data, function (response) {
                                if (response == 'yes') {
                                    jQuery(".loading_popup").click();
                                    submit_campaign();
                                } else {
                                    jQuery(".login_popup").click();
                                }
                            });
                            return false;
                        });
                 
                        // Submit champagin ajax
                        function submit_campaign() {
                            tinyMCE.triggerSave();
                            //var data_info = jQuery("#campaign-info").serialize();
							var data_design = {};
							jQuery('.box_design').each(function(index){
								var fancy_index = jQuery(this).attr('fpd-index');
								data_design[fancy_index] ={
									'design_name': get_title_fancy(fancy_index),
									'cost_price': jQuery(this).find('#cost_price').val(),
									'price': jQuery(this).find('#campaign_price').val(),
									'colors': get_shirt_colours(this),
									'image_back': jQuery(this).find('#design_image_back').val(),
                                    'image_front': jQuery(this).find('#design_image_front').val()
                                    };
                                   
							});
                            
							var data_submit ={'sales_goal':jQuery('#sales_goal').val(),
							'name':jQuery('#campaign_name').val(),
                            'design_id': <?php 
                            global $post;
                            echo $post->ID; ?>,
							'content':jQuery('#campaign_content').val(),
							'campaign_length':jQuery('#length').val(),
							'feature_side': jQuery('input[name=feature_side]:checked').val(),
							'design': data_design
							}



                            var $submit_loading = $(".submit_loading");
                            $submit_loading.show();
                            $submit_loading.css('visibility', 'visible');
                            $submit_loading.css('opacity', '1');
                            jQuery('#panel-success').html('');
                            jQuery('#panel-success').hide();
                            var json_product = JSON.stringify(fancyProductDesigner.getProduct());

                            jQuery.ajax({
                                type: "POST",
                                url: ajax_login_object.ajaxurl,
                                data: {
                                    'action': "postCampaign",
                                  //  'design_data': encodeURIComponent(fancyProductDesigner.getProductDataURL()),
                                    'view_size': jQuery('.fpd-views-selection').children().size(),
                                    'data': JSON.stringify(data_submit),
                                    'products': json_product
                                },
                                success: function (response) {
                                    $submit_loading.hide();
                                    if (response.status == 'success') {
                                        document.location.href = response.product_link;
                                        var html = '<h5>The campaign has been created.</h5>';
                                    } else {
                                        var html = '<h5 style="color: red;">' + response.message + '</h5>';
                                    }

                                    jQuery('#panel-success').html(html);
                                    jQuery('#panel-success').show();
                                },
                                error: function (data) {
                                    $submit_loading.hide();
                                }
                            });
                        }

						jQuery(document).on('change','.campaign_price', function () {
								profitCaculator(parseInt(jQuery('#sales_goal_input').val()));
						});

						/*
                        jQuery("#campaign_price").change(function () {
                            profitCaculator(parseInt(jQuery('#sales_goal_input').val()));
                        });
						*/
                        var ionslider = jQuery("#sales_goal").ionRangeSlider({
                            min: 0,
                            max: 1000,
                            grid: true,
                            grid_num: 10,
                            hide_min_max: true,
                            from: 30,
                            onFinish: function (data) {

                                if (data.from < 15) {
                                    var ionslider = $("#sales_goal").data("ionRangeSlider");
                                    ionslider.update({from: 15});
                                    jQuery('#sales_goal_input').val(15);
                                    profitCaculator(15);
                                } else {
                                    jQuery('#sales_goal_input').val(data.from);
                                    profitCaculator(data.from);
                                }
                            },
                            onChange: function (data) {

                            }
                        });
                        jQuery("#sales_goal_input").change(function () {
                            var ionslider = $("#sales_goal").data("ionRangeSlider");
                            if (jQuery(this).val() < 15) {
                                jQuery('#sales_goal_input').val(15);
                                ionslider.update({from: parseInt(15)});
                                profitCaculator(15);
                            } else {
                                profitCaculator(jQuery(this).val());
                                ionslider.update({from: parseInt(jQuery(this).val())});
                            }
                        });
                    });
                    var fancyProductDesigner,
                            $selector,
                            $productWrapper,
                            $cartForm,
                            productCreated = false,
                            fpdPrice = 0,
                            isReady = false,
                            $modalWrapper = null;

                <?php echo fpd_get_option('fpd_jquery_no_conflict') === 'on' ? 'jQuery.noConflict();' : ''; ?>
                    jQuery(document).ready(function () {

                        //return;

                        $selector = jQuery('#<?php echo $selector; ?>');
                        $productWrapper = jQuery('.post-<?php echo $post->ID; ?>');
                        $cartForm = jQuery('[name="fpd_product"]:first').parents('form:first');

                        var buttonClass = "<?php echo esc_attr(fpd_get_option('fpd_start_customizing_css_class')) == '' ? 'fpd-modal-button' : trim(fpd_get_option('fpd_start_customizing_css_class')); ?>",
                                productDesignerWidth = <?php echo $product_settings->get_option('stage_width'); ?>,
                                customizeBtn = jQuery('#fpd-start-customizing-button');

                        if (jQuery('.fpd-lightbox-enabled').size() > 0) {

                            $modalWrapper = jQuery('body').append('<div class="fpd-product-lightbox fpd-modal-overlay"><div class="fpd-modal-wrapper"><div class="fpd-modal-buttons"><a href="#" id="fpd-modal-done" class="' + buttonClass + '"><?php echo fpd_get_option('fpd_label_lightbox_submit_button'); ?></a><a href="#" id="fpd-modal-cancel" class="' + buttonClass + '"><?php echo fpd_get_option('fpd_label_lightbox_cancel_button'); ?></a></div></div></div>').find('.fpd-modal-wrapper');

                            $selector.clone().prependTo($modalWrapper);
                            $selector.remove();
                            $selector = jQuery('#<?php echo $selector; ?>');

                            jQuery(window).resize(function () {
                                $modalWrapper.css('margin-left', -($modalWrapper.outerWidth() / 2) + 'px');
                            });

                            customizeBtn.click(function (evt) {

                                if (!isReady) {
                                    return false;
                                }

                                jQuery('html,body').addClass('fpd-modal-open');
                                $modalWrapper.parent('.fpd-modal-overlay').fadeIn(300, function () {
                                    jQuery('.fpd-context-dialog').removeClass('fpd-modal-hidden');
                                });
                                jQuery(window).resize();

                                evt.preventDefault();

                            });

                            $modalWrapper.on('click', '#fpd-modal-done', function (evt) {

                                jQuery('#fpd-modal-cancel').click();

                                if (<?php echo intval(fpd_get_option('fpd_lightbox_add_to_cart')); ?>) {
                                    $cartForm.find(':submit').click();
                                }

                                evt.preventDefault();

                            })
                                    .on('click', '#fpd-modal-cancel', function (evt) {

                                        fancyProductDesigner.closeDialog();
                                        jQuery('html,body').removeClass('fpd-modal-open');
                                        $modalWrapper.parent('.fpd-modal-overlay').fadeOut(200);
                                        evt.preventDefault();

                                    });

                        }

                        if (jQuery('.fpd-share-design').size() > 0) {

                            jQuery('#fpd-share-button').click(function (evt) {

                                evt.preventDefault();

                                var scale = $selector.width() > 800 ? Number(800 / $selector.width()).toFixed(2) : 1;
                                var data = {
                                    action: 'fpd_createshareurl',
                                    image: fancyProductDesigner.getProductDataURL('png', 'transparent', scale),
                                    product: JSON.stringify(fancyProductDesigner.getProduct()),
                                };

                                jQuery(".fpd-share-widget, .fpd-share-url").addClass('fpd-hidden');
                                jQuery('.fpd-share-process').removeClass('fpd-hidden');

                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function (response) {

                                    if (response.share_id !== undefined) {

                                        var pattern = new RegExp('(share_id=).*?(&|$)'),
                                                shareUrl = window.location.href;

                                        if (shareUrl.search(pattern) >= 0) {
                                            shareUrl = shareUrl.replace(pattern, '$1' + response.share_id + '$2');
                                        } else {
                                            shareUrl = shareUrl + (shareUrl.indexOf('?') > 0 ? '&' : '?') + 'share_id=' + response.share_id;
                                        }
                <?php $shares = fpd_get_option('fpd_sharing_social_networks'); ?>
                                        jQuery(".fpd-share-widget").empty().jsSocials({
                                            url: shareUrl,
                                            shares: <?php echo is_array($shares) ? json_encode($shares) : '[' . $shares . ']'; ?>,
                                            showLabel: false,
                                            text: "<?php echo fpd_get_option('fpd_label_sharing_default_text'); ?>"
                                        }).removeClass('fpd-hidden');
                                    }

                                    jQuery('.fpd-share-process').addClass('fpd-hidden');
                                    jQuery('.fpd-share-url').attr('href', shareUrl).text(shareUrl).removeClass('fpd-hidden');

                                }, 'json');



                            });

                        }

                        var customImagesParams = jQuery.extend(<?php echo $product_settings->get_image_parameters_string(); ?>, <?php echo $product_settings->get_custom_image_parameters_string(); ?>);

                        var socialPhotoAjaxSettingsOpt = {
                            url: "<?php echo plugins_url('/inc/get_image_data_url.php', FPD_PLUGIN_ROOT_PHP); ?>"
                        };
                        if ("<?php echo fpd_get_option('fpd_type_of_uploader'); ?>" == 'php') {
                            socialPhotoAjaxSettingsOpt = {
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                data: {
                                    action: 'fpd_uploadsocialphoto'
                                }
                            };
                        }


                        //call fancy product designer plugin
                        fancyProductDesigner = $selector.fancyProductDesigner({
                            width: productDesignerWidth,
                            stageHeight: <?php echo $product_settings->get_option('stage_height'); ?>,
                            imageDownloadable: <?php echo $this->disable_export_btns ? 0 : fpd_get_option('fpd_download_product_image'); ?>,
                            saveAsPdf: <?php echo $this->disable_export_btns ? 0 : fpd_get_option('fpd_pdf_button'); ?>,
                            printable: <?php echo $this->disable_export_btns ? 0 : fpd_get_option('fpd_print'); ?>,
                            allowProductSaving: <?php echo fpd_get_option('fpd_allow_product_saving'); ?>,
                            fonts: [<?php echo '"' . implode('", "', $available_fonts) . '"'; ?>],
                            templatesDirectory: "<?php echo plugins_url('/templates/', FPD_PLUGIN_ROOT_PHP); ?>",
                            phpDirectory: "<?php echo plugins_url('/inc/', FPD_PLUGIN_ROOT_PHP); ?>",
                            facebookAppId: "<?php echo fpd_get_option('fpd_facebook_app_id'); ?>",
                            responsiveScale: 2,
                            instagramClientId: "<?php echo fpd_get_option('fpd_instagram_client_id'); ?>",
                            instagramRedirectUri: "<?php echo fpd_get_option('fpd_instagram_redirect_uri'); ?>",
                            patterns: [<?php echo implode(',', $this->get_pattern_urls()); ?>],
                            viewSelectionPosition: "<?php echo $product_settings->get_option('view_selection_position'); ?>",
                            viewSelectionFloated: <?php echo $product_settings->get_option('view_selection_floated'); ?>,
                            zoomStep: <?php echo fpd_get_option('fpd_zoom_step'); ?>,
                            maxZoom: <?php echo fpd_get_option('fpd_max_zoom'); ?>,
                            tooltips: <?php echo intval(fpd_get_option('fpd_tooltips')); ?>,
                            hexNames: <?php echo FPD_Settings_Advanced_Colors::get_hex_names_object_string(); ?>,
                            selectedColor: "<?php echo fpd_get_option('fpd_selected_color'); ?>",
                            boundingBoxColor: "<?php echo fpd_get_option('fpd_bounding_box_color'); ?>",
                            outOfBoundaryColor: "<?php echo fpd_get_option('fpd_out_of_boundary_color'); ?>",
                            paddingControl: <?php echo fpd_get_option('fpd_padding_controls'); ?>,
                            replaceInitialElements: <?php echo $product_settings->get_option('replace_initial_elements'); ?>,
                            lazyLoad: <?php echo fpd_get_option('fpd_lazy_load'); ?>,
                            dialogBoxPositioning: "<?php echo $product_settings->get_option('dialog_box_positioning'); ?>",
                            socialPhotoAjaxSettings: socialPhotoAjaxSettingsOpt,
                            elementParameters: {
                                originX: "<?php echo fpd_get_option('fpd_common_parameter_originX'); ?>",
                                originY: "<?php echo fpd_get_option('fpd_common_parameter_originY'); ?>"
                            },
                            imageParameters: {
                                colorPrices: <?php echo $product_settings->get_option('enable_image_color_prices') ? FPD_Settings_Advanced_Colors::get_color_prices() : '{}'; ?>
                            },
                            textParameters: {
                                font: "<?php echo fpd_get_option('fpd_font'); ?>",
                                colorPrices: <?php echo $product_settings->get_option('enable_text_color_prices') ? FPD_Settings_Advanced_Colors::get_color_prices() : '{}'; ?>
                            },
                            customImageParameters: customImagesParams,
                            customTextParameters: <?php echo $product_settings->get_custom_text_parameters_string(); ?>,
                            labels: <?php
                echo FPD_Settings_Labels::get_labels_object_string(array(
                    'fpd_label_uploadedDesignSizeAlert' => array(
                        'minW' => $product_settings->get_option('uploaded_designs_parameter_minW'),
                        'minH' => $product_settings->get_option('uploaded_designs_parameter_minH'),
                        'maxW' => $product_settings->get_option('uploaded_designs_parameter_maxW'),
                        'maxH' => $product_settings->get_option('uploaded_designs_parameter_maxH'),
                    )
                ));
                ?>,
                            customAdds: {
                                uploads: <?php echo $product_settings->get_option('hide_custom_image_upload') ? 0 : intval(fpd_get_option('fpd_upload_designs')) ?>,
                                texts: <?php echo $product_settings->get_option('hide_custom_text') ? 0 : intval(fpd_get_option('fpd_custom_texts')) ?>,
                                facebook: <?php echo $product_settings->get_option('hide_facebook_tab') ? 0 : 1 ?>,
                                instagram: <?php echo $product_settings->get_option('hide_instagram_tab') ? 0 : 1 ?>
                            }
                        }).data('fancy-product-designer');

                        //when load from cart or order, use loadProduct
                        $selector.on('ready', function () {

                            if (jQuery('.fpd-lightbox-enabled').size() > 0) {
                                jQuery('.fpd-context-dialog').addClass('fpd-modal-hidden');
                            }

                            if (<?php echo $this->form_views === null ? 0 : 1; ?>) {
                                var views = <?php echo $this->form_views === null ? 0 : $this->form_views; ?>;
                                fancyProductDesigner.loadProduct(views);
                            }

                            //replace filereader uploader with php uploader
                            if ("<?php echo fpd_get_option('fpd_type_of_uploader'); ?>" == 'php') {

                                var $imageInput = jQuery('body').find('.fpd-input-image');

                                jQuery('body').find('.fpd-upload-form').off('change').change(function () {

                <?php
                $login_required = fpd_get_option('fpd_upload_designs_php_logged_in') !== 0 && !is_user_logged_in() ? 1 : 0;
                ?>

                                    if (<?php echo $login_required; ?>) {
                                        fancyProductDesigner.showModal("<?php _e('You need to be logged in to upload images!', 'radykal'); ?>");
                                        $imageInput.val('');
                                        return;
                                    }

                                    jQuery('body').find('.fpd-upload-form').ajaxSubmit({
                                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                        dataType: 'json',
                                        data: {
                                            action: 'fpduploadimage',
                                            product_id: <?php echo $post->ID; ?>
                                        },
                                        type: 'post',
                                        beforeSubmit: function (arr, $form, options) {

                                            $phpUploaderInfo.addClass('fpd-show-up')
                                                    .children('p:first').text('<?php _e('Uploading', 'radykal'); ?>' + ': ' + arr[0].value.name);
                                            $progressBar.children('.fpd-progress-bar-move').css('width', 0);

                                        },
                                        success: function (responseText, statusText) {

                                            if (responseText.code == 200) {
                                                //successfully uploaded
                                                fancyProductDesigner.addCustomImage(responseText.url, responseText.filename);
                                            } else {
                                                //failed
                                                fancyProductDesigner.showModal(responseText.message);
                                            }

                                            $imageInput.val('');
                                            $phpUploaderInfo.removeClass('fpd-show-up');

                                        },
                                        error: function () {

                                            $imageInput.val('');
                                            $phpUploaderInfo.removeClass('fpd-show-up');
                                            fancyProductDesigner.showModal("<?php _e('Server error: Image could not be uploaded, please try again!', 'radykal'); ?>");

                                        },
                                        uploadProgress: function (evt, pos, total, percentComplete) {
                                            $progressBar.children('.fpd-progress-bar-move').css('width', percentComplete + '%');
                                        }
                                    });

                                })

                                jQuery('body').append('<div class="fpd-php-uploader-info fpd-snackbar fpd-shadow-1"><p></p><div class="fpd-upload-progess-bar"><div class="fpd-progress-bar-bg"></div><div class="fpd-progress-bar-move"></div></div></div>');

                                $phpUploaderInfo = jQuery('body').children('.fpd-php-uploader-info');
                                $progressBar = $phpUploaderInfo.children('.fpd-upload-progess-bar');

                            }

                            //hide loading icon in customize btn
                            customizeBtn.removeClass('fpd-disabled')
                                    .text(customizeBtn.attr('title')).removeAttr('title');

                            isReady = true;

                        });

                    });

                </script>

                <?php
                //woocommerce
                if (get_post_type($post) === 'product') {
                    $this->output_wc_js();
                } else {
                    $this->output_shortcode_js();
                }
            }
        }

        public function reset_share_permalink($url, $post) {

            if (isset($post->ID) && is_fancy_product($post->ID) && isset($_GET['share_id'])) {
                $url = add_query_arg('share_id', $_GET['share_id'], $url);
            }

            return $url;
        }

        public function set_product_image($url) {

            global $post;

            if (isset($post->ID) && is_fancy_product($post->ID) && isset($_GET['share_id'])) {

                $transient_key = 'fpd_share_' . $_GET['share_id'];
                $transient_val = get_transient($transient_key);
                return $transient_val === false ? $url : $transient_val['image_url'];
            }

            return $url;
        }

        public function add_share() {

            global $post;

            $product_settings = new FPD_Product_Settings($post->ID);
            $open_in_lightbox = $product_settings->get_option('open_in_lightbox') && trim($product_settings->get_option('start_customizing_button')) != '';

            if (is_fancy_product($post->ID) && (!$product_settings->customize_button_enabled || $open_in_lightbox)) {

                echo $this->get_share_html();
            }
        }

        public function create_share_url() {

            if (!isset($_POST['image']) || !isset($_POST['product']))
                die;

            if (!preg_match('/data:([^;]*);base64,(.*)/', $_POST['image'], $matches)) {
                echo json_encode(array(
                    'error' => __('Image string is not a valid Data URL.', 'radykal')
                ));
                die;
            }

            $share_dir = WP_CONTENT_DIR . '/uploads/fpd_shares/';

            if (!file_exists($share_dir))
                wp_mkdir_p($share_dir);

            $today = date('Y-m-d');
            $timestamp = strtotime('now');

            if (!file_exists($share_dir . '/' . $today))
                wp_mkdir_p($share_dir . '/' . $today);

            // Decode the data
            $image_content = base64_decode($matches[2]);
            $image_name = $timestamp . ".png";
            //create png from decoded base 64 string and save the image in the parent folder
            $result = @file_put_contents($share_dir . '/' . $today . '/' . $image_name, $image_content);

            if ($result === false) {
                echo json_encode(array(
                    'error' => __('Image could not be created. Please try again!', 'radykal')
                ));
                die;
            }

            //set transient to store product
            $cache_days = intval(fpd_get_option('fpd_sharing_cache_days')) * DAY_IN_SECONDS;

            $transient_val = array(
                'image_url' => content_url('/uploads/fpd_shares/' . $today . '/' . $image_name),
                'product' => $_POST['product']
            );
            $transient_result = set_transient('fpd_share_' . $timestamp, $transient_val, $cache_days);

            if ($transient_result) {

                echo json_encode(array(
                    'share_id' => $timestamp,
                    'image_url' => content_url('/uploads/fpd_shares/' . $today . '/' . $image_name)
                ));
            }

            die;
        }

        public function fpd_shortcode_handler($atts) {

            extract(shortcode_atts(array(
                            ), $atts, 'fpd'));

            ob_start();

            echo $this->add_customize_button();
            echo $this->add_product_designer();

            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }

        public function fpd_form_shortcode_handler($atts) {

            extract(shortcode_atts(array(
                'button' => 'Send',
                'name_placeholder' => 'Enter your name here',
                'email_placeholder' => 'Enter your email here',
                'currency' => '$',
                            ), $atts, 'fpd_form'));

            ob_start();
            ?>
            <form name="fpd_shortcode_form">
            <?php if (!empty($currency)) : ?>
                    <p class="fpd-shortcode-price-wrapper"><span class="fpd-shortcode-price"></span><span class="fpd-shortcode-currency"><?php echo $currency ?></span></p>
            <?php endif; ?>
                <input type="text" name="fpd_shortcode_form_name" placeholder="<?php echo $name_placeholder ?>" class="fpd-shortcode-form-text-input" />
                <input type="email" name="fpd_shortcode_form_email" placeholder="<?php echo $email_placeholder ?>" class="fpd-shortcode-form-text-input" />
                <input type="hidden" name="fpd_product" />
                <input type="submit" value="<?php echo $button; ?>" class="fpd-disabled <?php echo fpd_get_option('fpd_start_customizing_css_class'); ?>" />
            </form>
            <?php
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }

        //adds a customize button to the summary
        public function add_customize_button() {

            global $post;
            $product_settings = new FPD_Product_Settings($post->ID);
            $open_in_lightbox = $product_settings->get_option('open_in_lightbox') && trim($product_settings->get_option('start_customizing_button')) != '';

            $fancy_content_ids = fpd_has_content($post->ID);
            if (!is_array($fancy_content_ids) || sizeof($fancy_content_ids) === 0) {
                return;
            }

            if ((is_fancy_product($post->ID) && ($product_settings->customize_button_enabled || $open_in_lightbox ))) {

                $button_class = trim(fpd_get_option('fpd_start_customizing_css_class')) == '' ? 'fpd-start-customizing-button' : fpd_get_option('fpd_start_customizing_css_class');
                $button_class .= $open_in_lightbox ? ' fpd-disabled' : '';
                $button_class .= fpd_get_option('fpd_start_customizing_button_position') === 'under-short-desc' ? ' fpd-block' : ' fpd-inline';
                $label = $open_in_lightbox ? '' : $product_settings->get_option('start_customizing_button');
                ?>
                <a href="<?php echo esc_url(add_query_arg('start_customizing', 'yes')); ?>" id="fpd-start-customizing-button" class="<?php echo $button_class; ?>" title="<?php echo $product_settings->get_option('start_customizing_button'); ?>"><?php echo $label; ?></a>
                    <?php
                }
            }

            //the additional form fields
            public function add_product_designer_form() {

                global $post;
                $product_settings = new FPD_Product_Settings($post->ID);
                $open_in_lightbox = $product_settings->get_option('open_in_lightbox') && trim($product_settings->get_option('start_customizing_button')) != '';

                if (is_fancy_product($post->ID) && (!$product_settings->customize_button_enabled || $open_in_lightbox)) {
                    ?>
                <input type="hidden" value="" name="fpd_product" />
                <input type="hidden" value="" name="fpd_product_price" />
                <input type="hidden" value="" name="fpd_product_thumbnail" />
                <input type="hidden" value="<?php echo isset($_GET['cart_item_key']) ? $_GET['cart_item_key'] : ''; ?>" name="fpd_remove_cart_item" />
                <?php
            }
        }

        private function get_pattern_urls() {

            $urls = array();

            $path = WP_CONTENT_DIR . '/uploads/fpd_patterns/';

            if (file_exists($path)) {
                $folder = opendir($path);

                $pic_types = array("jpg", "jpeg", "png");

                while ($file = readdir($folder)) {

                    if (in_array(substr(strtolower($file), strrpos($file, ".") + 1), $pic_types)) {
                        $urls[] = '"' . content_url('/uploads/fpd_patterns/' . $file, FPD_PLUGIN_ROOT_PHP) . '"';
                    }
                }

                closedir($folder);
            }

            return $urls;
        }

        private function get_product_html($product_id) {

            $fancy_product = new Fancy_Product($product_id);
            $views_data = $fancy_product->get_views();
            $output = '';

            if (!empty($views_data)) {

                $first_view = $views_data[0];
                $product_options = fpd_convert_obj_string_to_array($fancy_product->get_options());

                $view_options = fpd_convert_obj_string_to_array($first_view->options);
                $view_options = array_merge((array) $product_options, (array) $view_options);
                $view_options = Fancy_View::options_to_string($view_options);

                ob_start();
                echo "<div class='fpd-product' title='" . esc_attr($first_view->title) . "' title='" . esc_attr($first_view->title) . "' data-thumbnail='" . esc_attr($first_view->thumbnail) . "' data-options='" . $view_options . "'>";
                echo $this->get_element_anchors_from_view($first_view->elements);

                //sub views
                if (sizeof($views_data) > 1) {

                    for ($i = 1; $i < sizeof($views_data); $i++) {
                        $sub_view = $views_data[$i];

                        $view_options = fpd_convert_obj_string_to_array($sub_view->options);
                        $view_options = array_merge((array) $product_options, (array) $view_options);
                        $view_options = Fancy_View::options_to_string($view_options);
                        ?>
                        <div class="fpd-product" title="<?php echo esc_attr($sub_view->title); ?>" data-thumbnail="<?php echo esc_attr($sub_view->thumbnail); ?>" data-options='<?php echo $view_options; ?>'>
                        <?php
                        echo $this->get_element_anchors_from_view($sub_view->elements);
                        ?>
                        </div>
                        <?php
                    }
                }

                echo '</div>'; //product
                $output = ob_get_contents();
                ob_end_clean();
            }

            return $output;
        }

        private function get_element_anchors_from_view($elements) {

            //unserialize when necessary
            if (@unserialize($elements) !== false) {
                $elements = unserialize($elements);
            }

            $view_html = '';
            if (is_array($elements)) {
                foreach ($elements as $element) {
                    $element = (array) $element;
                    $view_html .= $this->get_element_anchor($element['type'], $element['title'], $element['source'], (array) $element['parameters']);
                }
            }

            return $view_html;
        }

        //return a single element markup
        private function get_element_anchor($type, $title, $source, $parameters) {

            $parameters_string = FPD_Parameters::convert_parameters_to_string($parameters, $type);

            if ($type == 'image') {

                return "<img data-src='$source' title='$title' data-parameters='$parameters_string' />";
            } else {
                $source = stripslashes($source);
                return "<span title='$title' data-parameters='$parameters_string'>$source</span>";
            }
        }

        //upload photo from social network
        public function upload_social_photo() {

            if (!isset($_POST['url']))
                die;

            $url = trim($_POST['url']);
            $ext = strtok(pathinfo($url, PATHINFO_EXTENSION), '?');
            $filename = strtotime('now') . '.' . $ext;
            $file_path = $this->get_upload_path($filename);

            $img_formats = array("png", "jpg", "jpeg", "svg");
            if (!in_array($ext, $img_formats)) {
                echo json_encode(array('error' => 'This is not an image file!'));
                die;
            }

            $result = false;
            if (function_exists('curl_exec')) {
                $ch = curl_init();
                $fp = fopen($file_path, 'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $result = curl_exec($ch);
                curl_close($ch);
                fclose($fp);
            }

            if ($result == false) {
                $result = file_get_contents($url);
                file_put_contents($file_path, $result);
            }

            $img_url = content_url() . '/uploads/fancy_products_uploads/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $filename;

            echo json_encode(array(
                'image_src' => $img_url,
                'filename' => $filename
            ));

            die;
        }

        //ajax image upload handler
        public function upload_image() {

            if (!class_exists('Fancy_Product')) {
                require_once(FPD_PLUGIN_DIR . '/inc/class-fancy-product.php');
            }

            $product_settings = new FPD_Product_Settings(intval($_POST['product_id']));

            $mb_size = intval(fpd_get_option('fpd_max_image_size'));
            $maximum_filesize = $mb_size * 1024 * 1000;

            foreach ($_FILES as $fieldName => $file) {

                $filename = $file['name'];

                //check if its an image
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!getimagesize($file['tmp_name']) && $ext !== 'svg') {
                    echo json_encode(array('code' => 500, 'message' => __('This file is not an image!', 'radykal'), 'filename' => $file['name']));
                    die;
                }

                //check for php errors
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    echo json_encode(array('code' => 500, 'message' => file_upload_error_message($file['error']), 'filename' => $filename));
                    die;
                }

                //check for maximum upload size
                if ($file['size'] > $maximum_filesize) {
                    echo json_encode(array('code' => 500, 'message' => sprintf(__('Uploaded image is too big! Maximum image size is %d MB!', 'radykal'), $mb_size), 'filename' => $filename));
                    die;
                }

                //check the minimum DPI
                $dpi = $this->get_image_dpi($file['tmp_name']);
                $min_dpi = fpd_get_option('fpd_minimum_dpi');
                if (isset($dpi[0]) && $dpi[0] !== 0 && $dpi[0] < $min_dpi) {

                    echo json_encode(array(
                        'code' => 500,
                        'message' => sprintf(__('The DPI of the uploaded image is too small! Minimum allowed DPI is %d.', 'radykal'), $min_dpi),
                        'filename' => $filename
                    ));

                    die;
                }

                //check dimensions
                $image_dimensions = getimagesize($file['tmp_name']);
                $filename = sanitize_file_name($filename);
                $file_path = $this->get_upload_path($filename);
                $filename = basename($file_path);

                if (@move_uploaded_file($file['tmp_name'], $file_path)) {

                    $img_url = content_url() . '/uploads/fancy_products_uploads/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $filename;

                    echo json_encode(array(
                        'code' => 200,
                        'url' => $img_url,
                        'filename' => preg_replace("/\\.[^.\\s]{3,4}$/", "", $filename),
                        'dim' => $image_dimensions
                    ));
                } else {

                    echo json_encode(array(
                        'error' => 2,
                        'message' => 'PHP Issue - move_uploaed_file failed',
                        'filename' => $filename
                    ));
                }
            }

            die;
        }

        //returns the upload path
        private function get_upload_path($filename) {

            $upload_path = WP_CONTENT_DIR . '/uploads/fancy_products_uploads/';

            if (!file_exists($upload_path))
                wp_mkdir_p($upload_path);

            $upload_path = $upload_path . '/' . date('Y') . '/';
            if (!file_exists($upload_path))
                wp_mkdir_p($upload_path);

            $upload_path = $upload_path . '/' . date('m') . '/';
            if (!file_exists($upload_path))
                wp_mkdir_p($upload_path);

            $upload_path = $upload_path . '/' . date('d') . '/';
            if (!file_exists($upload_path))
                wp_mkdir_p($upload_path);

            $file_url = $upload_path . $filename;

            $file_counter = 1;
            $real_filename = $filename;

            while (file_exists($file_url)) {
                $real_filename = $file_counter . '-' . $filename;
                $file_url = $upload_path . $real_filename;
                $file_counter++;
            }

            return $file_url;
        }

        private function get_image_dpi($filename) {

            $image = fopen($filename, 'r');
            $string = fread($image, 20);
            fclose($image);

            $data = bin2hex(substr($string, 14, 4));
            $x = substr($data, 0, 4);
            $y = substr($data, 0, 4);

            return array(hexdec($x), hexdec($y));
        }

        private function output_wc_start() {

            global $product, $woocommerce;

            $this->disable_export_btns = $product->is_downloadable() ? true : false;

            //added to cart, recall added product
            if (isset($_POST['fpd_product'])) {

                $views = $_POST['fpd_product'];
                $this->form_views = stripslashes($views);
            } else if (isset($_GET['cart_item_key'])) {

                //load from cart item
                $cart = $woocommerce->cart->get_cart();
                $cart_item = $cart[$_GET['cart_item_key']];
                if ($cart_item) {
                    if (isset($cart_item['fpd_data'])) {
                        $views = $cart_item['fpd_data']['fpd_product'];
                        $this->form_views = stripslashes($views);
                    }
                } else {
                    //cart item could not be found
                    echo '<p><strong>';
                    _e('Sorry, but the cart item could not be found!', 'radykal');
                    echo '</strong></p>';
                    return;
                }
            } else if (isset($_GET['order']) && isset($_GET['item_id'])) {

                //load ordered product in designer
                $order = new WC_Order($_GET['order']);
                $item_meta = $order->get_item_meta($_GET['item_id'], 'fpd_data');
                $this->form_views = $item_meta[0]["fpd_product"];

                if ($product->is_downloadable() && $order->is_download_permitted()) {
                    $this->disable_export_btns = false;
                    ?>
                    <br />
                    <a href="#" id="fpd-extern-download-pdf"><?php echo fpd_get_option('fpd_label_downLoadPDF'); ?></a>
                    <?php
                }
            } else if (isset($_GET['share_id'])) {

                $transient_key = 'fpd_share_' . $_GET['share_id'];
                $transient_val = get_transient($transient_key);
                if ($transient_val !== false)
                    $this->form_views = stripslashes($transient_val['product']);
            }
        }

        private function output_wc_js() {

            global $product;
            ?>
            <script type="text/javascript">

                //WOOCOMMERCE JS

                var wcPrice = <?php echo $product->get_price() ? $product->get_price() : 0; ?>,
                        currencySymbol = '<?php echo get_woocommerce_currency_symbol(); ?>',
                        decimalSeparator = "<?php echo get_option('woocommerce_price_decimal_sep'); ?>",
                        thousandSeparator = "<?php echo get_option('woocommerce_price_thousand_sep'); ?>",
                        numberOfDecimals = <?php echo get_option('woocommerce_price_num_decimals'); ?>,
                        currencyPos = "<?php echo get_option('woocommerce_currency_pos'); ?>",
                        firstViewImg = null;

                jQuery(document).ready(function () {

                    //reset image when variation has changed
                    $productWrapper.on('found_variation', '.variations_form', function () {

                        if (firstViewImg !== null) {
                            setTimeout(_setProductImage, 5);
                        }

                    });

                    jQuery('#fpd-extern-download-pdf').click(function (evt) {

                        evt.preventDefault();
                        if (productCreated) {
                            $selector.find('.fpd-save-pdf').mouseup();
                        } else {
                            fancyProductDesigner.showModal("<?php _e('The product is not created yet, try again when the product has been fully loaded into the designer', 'fpd_label'); ?>");
                        }


                    });

                    //calculate initial price
                    $selector.on('productCreate', function () {

                        productCreated = true;
                        fpdPrice = fancyProductDesigner.getPrice();
                        _setTotalPrice();
                        if (<?php echo $this->form_views === null ? 0 : 1; ?>) {
                            _setProductImage();
                        }

                    });

                    //check when variation has been selected
                    jQuery(document).on('found_variation', '.variations_form', function (evt, variation) {

                        if (variation.price_html) {

                            //- get last price, if a sale price is found, use it
                            //- set thousand and decimal separator
                            //- parse it as number
                            wcPrice = jQuery(variation.price_html).find('span:last').text().replace(currencySymbol, '').replace(thousandSeparator, '').replace(decimalSeparator, '.').replace(/[^\d.]/g, '');
                            _setTotalPrice();
                        }

                    });

                    //listen when price changes
                    $selector.on('priceChange', function (evt, sp, tp) {
                        fpdPrice = tp;
                        _setTotalPrice();

                    });
                    $selector.on('elementRemove', function (el) {
                        _setTotalPrice();
                    });

                    //fill custom form with values and then submit
                    $cartForm.on('click', ':submit', function (evt) {

                        evt.preventDefault();

                        if (!productCreated) {
                            return false;
                        }

                        var product = fancyProductDesigner.getProduct();
                        if (product != false) {

                            $cartForm.find('input[name="fpd_product"]').val(JSON.stringify(product));
                            $cartForm.find('input[name="fpd_product_thumbnail"]').val(fancyProductDesigner.getViewsDataURL('png', 'transparent', 0.3)[0]);
                            _setTotalPrice();
                            $cartForm.submit();
                            $('.single_add_to_cart_button').addClass('fpd-disabled');
                        }

                    });

                    //set product image
                    if ($modalWrapper !== null) {
                        $modalWrapper.on('click', '#fpd-modal-done', function (evt) {

                            if ($selector.parents('.woocommerce').size() > 0) {
                                _setProductImage();
                            }

                            evt.preventDefault();

                        });
                    }

                    //set total price depending from wc and fpd price
                    function _setTotalPrice() {

                        var totalPrice = parseFloat(wcPrice) + parseFloat(fpdPrice),
                                htmlPrice;

                        totalPrice = totalPrice.toFixed(numberOfDecimals);
                        htmlPrice = totalPrice.toString().replace('.', decimalSeparator);
                        if (thousandSeparator.length > 0) {
                            htmlPrice = _addThousandSep(htmlPrice);
                        }

                        if (currencyPos == 'right') {
                            htmlPrice = htmlPrice + currencySymbol;
                        } else if (currencyPos == 'right_space') {
                            htmlPrice = htmlPrice + ' ' + currencySymbol;
                        } else if (currencyPos == 'left_space') {
                            htmlPrice = currencySymbol + ' ' + htmlPrice;
                        } else {
                            htmlPrice = currencySymbol + htmlPrice;
                        }




                        //check if variations are used
                        if ($productWrapper.find('.variations_form').size() > 0) {
                            //check if amount contains 2 prices or sale prices. If yes different prices are used
                            if ($productWrapper.find('.price:first > .amount').size() == 2 || $productWrapper.find('.price:first ins > .amount').size() == 2) {
                                //different prices
                                $productWrapper.find('.single_variation .price .amount:last').html(htmlPrice);
                            } else {
                                //same price
                                $productWrapper.find('.price:first .amount:last').html(htmlPrice);
                            }

                        }
                        //no variations are used
                        else {
                            $productWrapper.find('.price:first .amount:last').html(htmlPrice);
                        }

                        $cartForm.find('input[name="fpd_product_price"]').val(fpdPrice);
                        calculator_price(totalPrice);

                    }
                    ;

                    function _addThousandSep(n) {

                        var rx = /(\d+)(\d{3})/;
                        return String(n).replace(/^\d+/, function (w) {
                            while (rx.test(w)) {
                                w = w.replace(rx, '$1' + thousandSeparator + '$2');
                            }
                            return w;
                        });

                    }
                    ;

                });

                function _setProductImage() {

                    if (jQuery('.fpd-lightbox-enabled').size() > 0 && <?php echo fpd_get_option('fpd_lightbox_update_product_image'); ?>) {
                        firstViewImg = fancyProductDesigner.getViewsDataURL('png', 'transparent')[0];
                        $productWrapper.find('div.images img:eq(0)').attr('src', firstViewImg).parent('a').attr('href', firstViewImg);
                    }

                }
                ;

            </script>
            <?php
        }

        private function output_shortcode_js() {
            ?>
            <script type="text/javascript">

                jQuery(document).ready(function () {

                    //calculate initial price
                    $selector.on('productCreate', function () {

                        productCreated = true;
                        $cartForm.find(':submit').removeClass('fpd-disabled');
                        fpdPrice = fancyProductDesigner.getPrice();
                        _setTotalPrice();


                    });

                    //listen when price changes
                    $selector.on('priceChange', function (evt, sp, tp) {

                        fpdPrice = tp;
                        _setTotalPrice();

                    });

                    jQuery('[name="fpd_shortcode_form"]').on('click', ':submit', function (evt) {

                        evt.preventDefault();

                        if (!productCreated) {
                            return false;
                        }

                        var product = fancyProductDesigner.getProduct(),
                                $submitBtn = $(this),
                                data = {
                                    action: 'fpd_newshortcodeorder'
                                };

                        if (product != false) {

                            var $nameInput = $cartForm.find('[name="fpd_shortcode_form_name"]').removeClass('fpd-error'),
                                    $emailInput = $cartForm.find('[name="fpd_shortcode_form_email"]').removeClass('fpd-error'),
                                    emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


                            if ($nameInput.val() === '') {
                                $nameInput.focus().addClass('fpd-error');
                                return false;
                            } else {
                                data.name = $nameInput.val();
                            }

                            if (!emailRegex.test($emailInput.val())) {
                                $emailInput.focus().addClass('fpd-error');
                                return false;
                            } else {
                                data.email = $emailInput.val();
                            }

                            data.product = JSON.stringify(product);
                            $submitBtn.addClass('fpd-disabled');
                            $selector.find('.fpd-full-loader').show();

                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function (response) {

                                fancyProductDesigner.showMessage(response.id ? response.message : response.error);
                                $submitBtn.removeClass('fpd-disabled');
                                $selector.find('.fpd-full-loader').hide();

                            }, 'json');

                            $nameInput.val('');
                            $emailInput.val('');

                        }

                    });

                    //set total price depending from wc and fpd price
                    function _setTotalPrice() {

                        $cartForm.find('.fpd-shortcode-price').text(parseFloat(fpdPrice).toFixed(2))
                                .parent().addClass('fpd-show-up');

                    }
                    ;

                });

            </script>
            <?php
        }

        public function create_shortcode_order() {

            if (!isset($_POST['product']))
                die;

            if (!class_exists('FPD_Shortcode_Order')) {
                require_once(FPD_PLUGIN_DIR . '/inc/class-shortcode-order.php');
            }

            $insert_id = FPD_Shortcode_Order::create($_POST['name'], $_POST['email'], $_POST['product']);

            if ($insert_id) {
                echo json_encode(array(
                    'id' => $insert_id,
                    'message' => fpd_get_option('fpd_label_order_success_sent'),
                ));
            } else {

                echo json_encode(array(
                    'error' => fpd_get_option('fpd_label_order_fail_sent'),
                ));
            }

            die;
        }

        private function get_share_html() {

            ob_start();
            ?>
            <div class="fpd-share-design fpd-clearfix">
                <a href="#" id="fpd-share-button" class="<?php echo fpd_get_option('fpd_start_customizing_css_class'); ?>" ><i class="fa fa-share-alt"></i><?php echo fpd_get_option('fpd_label_sharing_button'); ?></a>
                <div>
                    <p class="fpd-share-process fpd-hidden"><?php echo fpd_get_option('fpd_label_sharing_processing'); ?></p>
                    <div class="fpd-share-widget"></div>
                    <a href="" target="_blank" class="fpd-share-url fpd-hidden"></a>
                </div>
            </div>
            <?php
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }

        private function file_upload_error_message($error_code) {

            switch ($error_code) {
                case UPLOAD_ERR_INI_SIZE:
                    return __('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'radykal');
                case UPLOAD_ERR_FORM_SIZE:
                    return __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'radykal');
                case UPLOAD_ERR_PARTIAL:
                    return __('The uploaded file was only partially uploaded', 'radykal');
                case UPLOAD_ERR_NO_FILE:
                    return __('No file was uploaded', 'radykal');
                case UPLOAD_ERR_NO_TMP_DIR:
                    return __('Missing a temporary folder', 'radykal');
                case UPLOAD_ERR_CANT_WRITE:
                    return __('Failed to write file to disk', 'radykal');
                case UPLOAD_ERR_EXTENSION:
                    return __('File upload stopped by extension', 'radykal');
                default:
                    return __('Unknown upload error', 'radykal');
            }
        }

    }

}

new FPD_Frontend_Product();
?>