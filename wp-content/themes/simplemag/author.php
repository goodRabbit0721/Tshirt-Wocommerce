<?php 
/**
 * Author template. Display the author 
 * info and all posts by the author
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/ 
get_header(); ?>
		
	<section id="content" role="main" class="clearfix animated author-page">
    	<div class="wrapper">
    		
            <?php 
			
			if ( have_posts() ) : ?>
			
            <div class="grids">
                <div class="grid-4 columns column-1 sidebar-fixed">
                    <div class="author-box">
                        <div class="inner">
                        
                            <?php $curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) ); ?>
                            
                            <div class="avatar">
                                <?php echo get_avatar( $curauth->ID, 277 ); ?>
                            </div>
                            
                            <div class="author-info" itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">
                                <h1>
                                    <span class="vcard author">
                                        <span class="fn" itemprop="name"><?php echo $curauth->display_name; ?></span>
                                    </span>
                                </h1>
                                <p itemprop="description">
                                    <span><?php echo $curauth->user_description; ?>
                                </p>
                            </div>
                            
                            <ul class="author-social">
                                <?php if ( $curauth->user_url != '' ) { ?>
                                    <li>
                                        <a href="<?php echo $curauth->user_url; ?>"><i class="icomoon-info"></i></a>
                                    </li>
                                <?php } ?>
                                <?php if ( $curauth->sptwitter != '' ) { ?>
                                    <li>

                                        <a href="https://twitter.com/<?php echo $curauth->sptwitter; ?>"><i class="icomoon-twitter"></i></a>
                                    </li>
                                <?php } ?>
                                <?php if ( $curauth->spfacebook != '' ) { ?>
                                    <li>
                                        <a href="http://facebook.com/<?php echo $curauth->spfacebook; ?>"><i class="icomoon-facebook"></i></a>
                                    </li>
                                <?php } ?>
                                <?php if ( $curauth->spgoogle != '' ) { ?>
                                    <li>
                                        <a href="http://plus.google.com/<?php echo $curauth->spgoogle; ?>?rel=author"><i class="icomoon-google-plus"></i></a>
                                    </li>
                                <?php } ?>
                                <?php if ( $curauth->sppinterest != '' ) { ?>
                                    <li>
                                        <a href="http://pinterest.com/<?php echo $curauth->sppinterest; ?>"><i class="icomoon-pinterest"></i></a>
                                    </li>
                                <?php } ?>
                                <?php if ( $curauth->splinkedin != '' ) { ?>
                                    <li>
                                        <a href="http://linkedin.com/in/<?php echo $curauth->splinkedin; ?>"><i class="icomoon-linkedin"></i></a>
                                    </li>
                                <?php } ?>
                                <?php if ( $curauth->spinstagram != '' ) { ?>
                                    <li>
                                        <a href="http://instagram.com/<?php echo $curauth->spinstagram; ?>"><i class="icomoon-instagram"></i></a>
                                    </li>
                                <?php } ?>
                            </ul>

                        </div>
                    </div>
                </div><!-- .grid-4 -->
                
                <div class="grid-8 columns column-2">
                
                    <div class="grids masonry-layout entries">
                        <?php 
                            while ( have_posts() ) : the_post();
                                get_template_part( 'content', 'post' );
                            endwhile; 
                        ?>
                    </div>
                
                </div><!-- .grid-8 -->
            </div><!--.grids-->
                   
			<?php ti_pagination(); ?>
            
            <?php else: ?>
			<p class="message"><?php _e('This author has no posts yet', 'themetext' ); ?></p>
    		<?php endif; ?>
            
    	</div>
    </section>
		

<?php get_footer(); ?>