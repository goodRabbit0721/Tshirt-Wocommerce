<?php
/**
 * Single Post Rating
 *
 * @package SimpleMag
 * @since 	SimpleMag 3.0
**/

// Post Rating - Defined by post author in admin post edit page
if ( get_field('enable_rating') == '1' ) {
	
	global $ti_option;
	
	// Add class if rating box is outputed below the post content
	if ( $ti_option['single_rating_box'] == 'rating_bottom' ) {
		$rating_bottom = ' single-rating-bottom';
	}
	?>
	
	<div class="single-box single-rating<?php echo isset( $rating_bottom ) ? $rating_bottom : ' single-rating-top'; ?>">
		<div class="clearfix inner">
			<div class="entry-breakdown inview">
			
				<h3 class="title">
					<?php _e( 'Our Rating', 'themetext' ); ?>
				</h3>
				
				<?php
				$score_output = get_field( 'rating_module' );
				if( $score_output ){
					foreach( $score_output as $row ) {
				?>
					<div class="item clearfix">
						<h4>
							<span class="total"><?php echo $row['score_number']; ?></span>
							<?php echo $row['score_label']; ?>
						</h4>
						<div class="score-outer">
							<div class="score-line" style="width:<?php echo $row['score_number']; ?>0%;"></div>
						</div>
					</div>
				<?php 
					}
				} ?>
				
			</div>
            
			<div class="rating-score-box" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				
				<?php
                // Output rating note
                $rating_note = get_field('rating_note');
                if ( $rating_note ){
                    echo '<p class="description" itemprop="description">' . $rating_note . '</p>';
                }
                ?>
                <?php $show_total = apply_filters( 'ti_score_total', '' ); // Call total score calculation function ?>
                <meta itemprop="worstRating" content="1" />
                <meta itemprop="bestRating" content="10" />
                <span itemprop="ratingValue" class="hidden"><?php echo number_format( $show_total, 1, '.', '' ); ?></span>
                <div class="score">
                    <input class="knob" data-width="74" data-height="74" data-displayInput="true" data-readonly="true" data-fgColor="<?php echo $ti_option['main_site_color']; ?>" data-bgColor="#ffffff" data-thickness=".20" value="<?php echo number_format( $show_total, 1, '.', '' ); ?>" />
                </div>
					
			</div>
		</div>
	</div>
<?php } ?>