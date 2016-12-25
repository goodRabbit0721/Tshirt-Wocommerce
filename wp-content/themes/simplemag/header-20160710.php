<?php
/**
 * The Header for the theme
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/
?>
<!DOCTYPE html>
<!--[if lt IE 9]><html <?php language_attributes(); ?> class="oldie"><![endif]-->
<!--[if (gte IE 9) | !(IE)]><!--><html <?php language_attributes(); ?> class="modern"><!--<![endif]-->
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php global $ti_option; ?>
<link rel="shortcut icon" href="<?php echo $ti_option['site_favicon']['url']; ?>" />
<link rel="apple-touch-icon-precomposed" href="<?php echo $ti_option['site_retina_favicon']['url']; ?>" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/css/lt.css">

         
<?php wp_head(); ?>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/bootstrap/css/bootstrap.min.css" >
<!-- Optional theme -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/bootstrap/css/bootstrap-theme.min.css" >
 <script src="<?php echo get_template_directory_uri()?>/countdown_v5.0/countdown.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri()?>/cloudzoom/cloudzoom.css">
<script src="<?php echo get_template_directory_uri()?>/cloudzoom/jquery.cloudzoom.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri()?>/colorbox/colorbox.css">
<script src="<?php echo get_template_directory_uri()?>/colorbox/jquery.colorbox-min.js"></script>
<script src="<?php echo get_template_directory_uri()?>/owl.carousel.2.0.0-beta.2.4/owl.carousel.min.js"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/owl.carousel.2.0.0-beta.2.4/assets/owl.carousel.css" >
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri()?>/slick/slick/slick.css">
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri()?>/slick/slick/slick-theme.css">
 
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

    <div id="pageslide" class="st-menu st-effect">
    	<a href="#" id="close-pageslide"><i class="icomoon-remove-sign"></i></a>
    </div><!-- Sidebar in Mobile View -->
    
	<?php
    // Check for a layout options: Full Width or  Boxed
    if ( $ti_option['site_layout'] == '2' ) { $site_layout = ' class="layout-boxed"'; } else { $site_layout = ' class="layout-full"'; } ?>
    <section id="site"<?php echo isset( $site_layout ) ? $site_layout : ''; ?>>
        <div class="site-content">
    
            <header id="masthead" role="banner" class="clearfix<?php if ( $ti_option['site_main_menu'] == true ) { echo ' with-menu'; } ti_top_strip_class(); ?>" itemscope itemtype="http://schema.org/WPHeader">
                
                <div class="no-print top-strip">
                    <div class="wrapper clearfix">
						<div class="header_top_right">
                        <?php 
                        // Hide Search and Social Icons if header variation with search is selected
                        if ( $ti_option['site_header'] != 'header_search' ) {
                            
                            // Search Form
                            get_search_form();
                        
                            // Social Profiles
                            if( $ti_option['top_social_profiles'] == 1 ) {
                                get_template_part ( 'inc/social', 'profiles' );
                            }
                        }
                        ?>


                            <?php
						if(is_user_logged_in()){
						$user_ID = get_current_user_id();
						$user_info = get_userdata($user_ID);
							echo '<div class="top-welcome"><a href="/my-account">Hello, '.$user_info->user_login.'</a></div>';
						}else{
							echo '<div class="top-welcome"><a href="/my-account">Login</a></div>';
						}
						?>							
						</div>
						<div class="mb_nav">
							<a href="/"><img src="<?php bloginfo('template_directory'); ?>/images/nav_logo_mb.png" alt=""/></a>
						</div>
                        <a href="#" id="open-pageslide" data-effect="st-effect"><i class="icomoon-menu"></i></a>
                        <div class="top-links">
						
							<?php
							wp_nav_menu( array(
									'theme_location' => 'main_menu',
									'container' => false,
									'walker' => new TI_Menu()
								 ));
							?>
						</div>
                        
                        <?php
                        // Pages Menu
                        if ( has_nav_menu( 'secondary_menu' ) ) :
                            echo '<nav class="secondary-menu" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">';
                            wp_nav_menu( array(
                                'theme_location' => 'secondary_menu',
                                'container' => false,
                            ));
                           echo '</nav>';
                         endif;
                        ?>
                    </div><!-- .wrapper -->
                </div><!-- .top-strip -->
                <?php if (!is_front_page()): ?>
                <div id="branding" class="animated">
                    <div class="wrapper">
                    <?php
                      
                        
                        // Logo, Social Icons and Search
                        if ( $ti_option['site_header'] == 'header_search' ) {
                            get_template_part( 'inc/header', 'search' );
                        
                        // Logo and Ad unit
                        } elseif ( $ti_option['site_header'] == 'header_banner' ) {
                           // get_template_part( 'inc/header', 'banner' );
                        
                        // Default - Centered Logo and Tagline
                        } else { 
                            get_template_part( 'inc/header', 'default' );
                        }
					?>
                    </div>
                </div>
				<?php endif; ?>
            
            </header><!-- #masthead -->