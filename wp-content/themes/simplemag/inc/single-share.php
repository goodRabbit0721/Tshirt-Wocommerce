<?php
/**
 * Single Post Share Links
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/

global $ti_option;

// Add class if social share links is selected as Colorful
if ( $ti_option['single_social_style'] == 'social_colors' ) {
	$social_style = ' social-colors';
} else {
	$social_style = ' social-minimal';	
}

// Twitter username defined in Theme Option
if ( ! empty ( $ti_option['single_twitter_user'] ) ) {
    $twitter_user = $ti_option['single_twitter_user'];
}
?>

<div class="clearfix single-box social-box<?php echo isset( $social_style ) ? $social_style : ''; ?>">
	<h3 class="title"><?php _e( 'Share on', 'themetext' ); ?></h3>
    <ul>
        <li class="share-facebook">
            <a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" target="blank">
                <i class="icomoon-facebook"></i>
                <?php _e( 'Facebook', 'themetext' ); ?>
            </a>
        </li>
        <li class="share-twitter">
            <a href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>&amp;tw_p=tweetbutton&amp;url=<?php the_permalink(); ?><?php echo isset( $twitter_user ) ? '&amp;via='.$twitter_user : ''; ?>" target="_blank">
				<i class="icomoon-twitter"></i>
				<?php _e( 'Twitter', 'themetext' ); ?>
            </a>
        </li>
        <li class="share-pinterest">
            <?php 
            if ( has_post_thumbnail() ) {
                $pinimage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
                $showpinimage = $pinimage[0];
            } elseif( first_post_image() ) {
                $showpinimage = first_post_image();
            }
            ?>
            <a href="//pinterest.com/pin/create/button/?url=<?php the_permalink();?>&amp;media=<?php echo $showpinimage; ?>&amp;description=<?php the_title(); ?>" target="_blank">
				<i class="icomoon-pinterest"></i>
				<?php _e( 'Pinterest', 'themetext' ); ?>
            </a>
        </li>
        <li class="share-gplus">
            <a href="https://plusone.google.com/_/+1/confirm?hl=en-US&amp;url=<?php the_permalink(); ?>" target="_blank">
				<i class="icomoon-google-plus"></i>
				<?php _e( 'Google +', 'themetext' ); ?>
            </a>
        </li>
        <li class="share-linkedin">
            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;source=<?php bloginfo( 'name' ); ?>">
                <i class="icomoon-linkedin"></i>
                <?php _e( 'LinkedIn', 'themetext' ); ?>
            </a>
       </li>
        <li class="share-mail">
            <a href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>">
            	<i class="icomoon-envelope"></i>
				<?php _e( 'Email', 'themetext' ); ?>
            </a>
       </li>
    </ul>
</div><!-- social-box -->