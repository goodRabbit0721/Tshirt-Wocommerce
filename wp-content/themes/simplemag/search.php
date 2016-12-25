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
		<div class="middle-container hero-banner" >
			<div class="wrapper">
				<h2 class="main-title">LOOKING FOR SOMETHING?</h2>
				<div class="sub-main-title">We'll help you find it</div>
				<div class="bottom-rectangle bottom-rectangle-gday"></div>
			</div>
		</div>
		
			<div class="wrapper know-page">
                    <h1 class="search-result-title">Search result for "<?php echo $_GET['s']; ?>"</h1>
					<?php if (have_posts()) : ?>
                    
                    <div class="entries list-layout">
                    
					<?php while ( have_posts() ) : the_post(); ?>

                    <?php //print_r($post->id); ?>
                        
                    <article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?>>
                    
                        <figure class="entry-image">
                        
                            <a href="<?php the_permalink(); ?>">
                                <?php 
                                if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( );
                                } elseif( first_post_image() ) { // Set the first image from the editor
                                    echo '<img src="' . first_post_image() . '" class="wp-post-image" />';
                                } ?>
                            </a>
                            
                            <?php
                            // Add icon to different post formats
                            if ( 'gallery' == get_post_format() ): // Gallery
                                echo '<i class="icomoon-camera-retro"></i>';
                            elseif ( 'video' == get_post_format() ): // Video
                                echo '<i class="icomoon-camera"></i>';
                            elseif ( 'audio' == get_post_format() ): // Audio
                                echo '<i class="icomoon-music"></i>';
                            endif;
                            ?>
                    
                        </figure>
						
                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                            </h2>
                        </header>
                        
                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                            
                        </div>
                        
                    </article>
                    
                    <?php endwhile; ?>
            
					</div>
                    
				<?php else : ?>
            
					<p class="message"><?php _e( 'Sorry, nothing found', 'themetext' ); ?></p>
            
                <?php endif; ?>
					
			</div>
		
		
    </section><!-- #content -->
<?php get_footer(); ?>