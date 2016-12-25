<?php
/* Template Name: Know It All */

get_header();
global $ti_option;
?>
	<section id="content" role="main" class="clearfix animated">

        <?php
        /**
         * If Featured Image is uploaded set it as a background
         * and change page title color to white
        **/
        if ( has_post_thumbnail() ) {
            $page_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'big-size' );
            $page_bg_image = 'style="background-image:url(' . $page_image_url[0] . ');"';
            $title_with_bg = 'title-with-bg';
        } else {
            $title_with_bg = 'wrapper title-with-sep';
        } ?>
		<?php
        /**
         * If Featured Image is uploaded set it as a background
         * and change page title color to white
        **/
        if ( has_post_thumbnail() ) {
            $page_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'big-size' );
            $page_bg_image = 'style="background-image:url(' . $page_image_url[0] . ');"';
            $title_with_bg = 'title-with-bg';
        } else {
            $title_with_bg = 'wrapper title-with-sep';
        } 

		$top_banner = get_post_meta( get_the_ID(), 'banner_top' );
		$bottom_banner = get_post_meta( get_the_ID(), 'banner_bottom' );
		$sub_title = get_post_meta( get_the_ID(), 'sub_title' );
		?>
		<div class="middle-container hero-banner" style="background-image:url(<?php echo $top_banner[0]; ?>); background-position:center;">
			<div class="wrapper">
				<h2 class="main-title"><?php echo get_the_title(); ?></h2>
				<div class="sub-main-title"><?php echo $sub_title[0]; ?>&nbsp;</div>
				<div class="bottom-rectangle bottom-rectangle-gday"></div>
			</div>
		</div>
		
			<div class="wrapper know-page">
					<?php 
					if (have_posts()) : while (have_posts()) : the_post();
					?>
					
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
							<div class="page-content">
								<?php the_content(); ?>
							</div>
							
						</article>
					
					<?php endwhile; endif; ?>
					
					<?php 
					// Enable/Disable comments
					if ( $ti_option['site_page_comments'] == 1 ) {
						comments_template();
					}
					?>
			</div>
		
		<div class="row-large hero-banner-bottom" style="background-image:url(<?php echo $bottom_banner[0]; ?>); background-position:center;">	
			<div class="wrapper">
				<div class="top-rectangle"></div>
			</div>
		</div>
    </section><!-- #content -->
<?php get_footer(); ?>