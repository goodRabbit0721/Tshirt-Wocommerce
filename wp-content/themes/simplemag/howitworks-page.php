<?php
 /* Template Name: How it works */ 

get_header();
global $ti_option; 
?>
	
	<section id="content" role="main" class="clearfix  howitworks-page animated">

  
		<div class="wrapper">
        <h1 class="howitworks-pagetitle"><?php the_title(); ?>...</h1>  
		</div>		
		<?php 
                if (have_posts()) : while (have_posts()) : the_post();
					

                ?>
		<div class="howitworks-block-top">
				<div class="wrapper">
				<?php echo get_post_meta(get_the_ID() , 'top_content', true); ?>
				</div>
		</div>
		<div class="howitworks-block-content">
			<div class="wrapper">
			<?php remove_filter ('the_content', 'wpautop'); ?>
			<?php the_content(); ?>
			</div>
		</div>
		 <?php endwhile; endif; ?>
        		
                <?php 
				// Enable/Disable comments
				if ( $ti_option['site_page_comments'] == 1 ) {
					comments_template();
				}
				?>
    </section><!-- #content -->

<?php get_footer(); ?>