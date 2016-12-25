<?php 
/**
 * The template for displaying attached images
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.4
**/ 
get_header(); ?>
	
	<section id="content" role="main" class="clearfix animated">
    	<div class="wrapper">
        	
            <header class="entry-header page-header">
                <div class="page-title">
					<h1 class="entry-title"><?php the_title(); ?></h1>
                </div>
            </header>
			
			<?php 
            if (have_posts()) :
                while (have_posts()) : the_post(); 
            ?>
            
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                    <?php if ( wp_attachment_is_image( $post->id ) ) : $att_image = wp_get_attachment_image_src( $post->id, 'big-size' ); ?>
                    <p class="attachment">
                        <a href="<?php echo wp_get_attachment_url( $post->id ); ?>" title="<?php the_title(); ?>" rel="attachment">
                            <img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>" class="attachment-medium" alt="<?php $post->post_excerpt; ?>" />
                        </a>
                    </p>
                    <?php else : ?>
                    <a href="<?php echo wp_get_attachment_url( $post->ID ) ?>" title="<?php echo esc_html( get_the_title($post->ID), 1 ) ?>" rel="attachment">
                        <?php echo basename( $post->guid ) ?>		
                    </a>
                    <?php endif; ?>
                    
                </article>
            
            <?php endwhile; endif; ?>
        
        </div>
    </section><!-- #content -->

<?php get_footer(); ?>