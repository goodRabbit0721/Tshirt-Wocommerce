<?php 
/**
 * Author Box for single post
 *
 * @package SimpleMag
 * @since 	SimpleMag 1.0
**/ 
?>

<div class="single-box author-box single-author-box">

	<ul class="author-tabs-button clearfix">
        <li><a href="#author-bio"><?php _e( 'Bio', 'themetext' ); ?></a></li>
        <li><a href="#author-posts"><?php _e( 'Latest Posts', 'themetext' ); ?></a></li>
    </ul>
    
    <div class="author-tabs-content">
    	<div class="clearfix inner">
            
			<div id="author-bio">
                <div class="avatar">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                		<?php echo get_avatar( get_the_author_meta( 'email' ), '70' ); ?>
                    </a>
                </div><!-- .avatar -->
                <div class="author-info" itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">
                    <span class="vcard author">
                        <span class="fn"> 
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" itemprop="url">
                                <span itemprop="name"><?php the_author_meta( 'display_name' ); ?></span>
                            </a>
                        </span>
                    </span>
                    <p itemprop="description">
						<?php the_author_meta( 'description' ); ?>
                    </p>
                </div><!-- .info -->
                <ul class="author-social">
					<?php if ( get_the_author_meta( 'user_url' ) != '' ) { ?>
                        <li>
                            <a class="user-url" href="<?php echo wp_kses( get_the_author_meta( 'user_url' ), null ); ?>">
                                <?php printf( esc_attr__( 'Website', 'themetext'), get_the_author() ); ?>
                            </a>
                        </li>
                    <?php } ?>
                    
                    <?php if ( get_the_author_meta( 'sptwitter' ) != '' ) { ?>
                        <li>
                            <a class="twitter-link" href="https://twitter.com/<?php echo wp_kses( get_the_author_meta( 'sptwitter' ), null ); ?>">
                                <?php printf( esc_attr__( 'Twitter', 'themetext'), get_the_author() ); ?>
                            </a>
                        </li>
                    <?php } ?>
            
                    <?php if ( get_the_author_meta( 'spfacebook' ) != '' ) { ?>
                        <li>
                            <a class="facebook-link" href="http://facebook.com/<?php echo wp_kses( get_the_author_meta( 'spfacebook' ), null ); ?>">
                                <?php printf( esc_attr__( 'Facebook', 'themetext'), get_the_author() ); ?>
                            </a>
                        </li>
                    <?php } ?>
                    
                    <?php if ( get_the_author_meta( 'spgoogle' ) != '' ) { ?>
                        <li>
                            <a class="google-link" href="http://plus.google.com/<?php echo wp_kses( get_the_author_meta( 'spgoogle' ), null ); ?>?rel=author">
                                <?php printf( esc_attr__( 'Google+', 'themetext'), get_the_author() ); ?>
                            </a>
                        </li>
                    <?php } ?>
            
                    <?php if ( get_the_author_meta( 'sppinterest' ) != '' ) { ?>
                        <li>
                            <a class="pinterest-link" href="http://pinterest.com/<?php echo wp_kses( get_the_author_meta( 'sppinterest' ), null ); ?>">
                                <?php printf( esc_attr__( 'Pinterest', 'themetext'), get_the_author() ); ?>
                            </a>
                         </li>
                    <?php } ?>
                    
                    <?php if ( get_the_author_meta( 'splinkedin' ) != '' ) { ?>
                        <li>
                            <a class="linkedin-link" href="http://linkedin.com/in/<?php echo wp_kses( get_the_author_meta( 'splinkedin' ), null ); ?>">
                                <?php printf( esc_attr__( 'LinkedIn', 'themetext'), get_the_author() ); ?>
                            </a>
                         </li>
                    <?php } ?>

                    <?php if ( get_the_author_meta( 'spinstagram' ) != '' ) { ?>
                        <li>
                            <a class="instagram-link" href="http://instagram.com/<?php echo wp_kses( get_the_author_meta( 'spinstagram' ), null ); ?>">
                                <?php printf( esc_attr__( 'Instagram', 'themetext'), get_the_author() ); ?>
                            </a>
                         </li>
                    <?php } ?>
                </ul><!-- .author-social -->
            </div><!-- #author-bio -->
      
    		<div id="author-posts">
                <div class="avatar">
                	<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                		<?php echo get_avatar( get_the_author_meta( 'email' ), '109' ); ?>
                    </a>
                </div><!-- .avatar -->
                <div class="author-info">
					<?php _e( 'Latest Posts By', 'themetext' ); ?>
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author">
                       <?php printf( __( '%s', 'themetext' ), get_the_author() ); ?>
                    </a>
                    <ul>
					<?php
                        $latest_by_author = new WP_Query( array (
                            'posts_per_page' => 3,
                            'author' => get_the_author_meta( 'ID' )
                        ));
                        while ( $latest_by_author->have_posts() ) : $latest_by_author->the_post();
                    ?>
                         <li>
                         	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                         </li>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    ?>
                	</ul>
                </div>
    		</div><!-- #author-posts -->
            
    	</div>
    </div>
    
</div><!-- .tabs -->