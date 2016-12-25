<?php
 /* Template Name: Home */ 

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
        } 

		$top_banner = get_post_meta( get_the_ID(), 'banner_top' );
		$bottom_banner= get_post_meta( get_the_ID(), 'banner_bottom' );
		?>
			<div class="middle-container hero-banner" style="background-image:url(<?php echo $top_banner[0]; ?>); background-position:center;">
					
							<?php //echo do_shortcode( '[static_block_content id="2574"]' ); ?>
				<div class="wrapper">
						<h2 class="main-title">GOT A GREAT T-SHIRT PRINT IDEA?</h2>
						<div class="sub-main-title">Design and sell your own T-shirts, No Risk, No Upfront Cost.</div>

						<div class="button_bottom_create">
                    		<a class="button js-create-it-now bottom" href="/design-now">MAKE IT HAPPEN</a>
                		</div>
				<div class="bottom-rectangle"></div>
				</div>
									
			</div>
            




            <div class="wrapper">
				 <div class="step-large">
					<?php echo do_shortcode( '[static_block_content id="989"]' ); ?>
				</div>
				
				<div class="step-medium">
					<?php echo do_shortcode( '[static_block_content id="2693"]' ); ?>
					<?php echo do_shortcode( '[static_block_content id="2694"]' ); ?>
					<?php echo do_shortcode( '[static_block_content id="2692"]' ); ?>

					<style type="text/css">
					    
						.slider {
							width: 100%;
					        margin: 0px auto;
						}

						.slick-dotted.slick-slider {
					    	margin-bottom: 35px;
						}

					    .slick-prev:before,
					    .slick-next:before {
					        color: black;
					    }
					  </style>
					<script src="http://www.teem8.com.au/wp-content/themes/simplemag/slick/slick/slick.js" type="text/javascript" charset="utf-8"></script>
					  <script type="text/javascript">
					    jQuery(document).on('ready', function() {
					      
					      jQuery(".center").slick({
					        dots: true,
					        infinite: true,
					        centerMode: true,
					        slidesToShow: 1,
					        slidesToScroll: 3,
							arrows: false
					      });
					    });
					  </script>
				</div>

				<!-- <div class="step-sm">
					<?php echo do_shortcode( '[static_block_content id="2693"]' ); ?>
					<?php //if( function_exists( "get_smooth_slider_recent" ) ){ get_smooth_slider_recent(); } ?>
					<?php echo do_shortcode( '[static_block_content id="2694"]' ); ?>
					<?php echo do_shortcode( '[static_block_content id="2692"]' ); ?>
				</div> -->


            </div>

			<div class="row-large">
				<div class="wrapper">
					<div class="top-rectangle"></div>
                	<?php echo do_shortcode( '[static_block_content id="2581"]' ); ?>
					<div class="bottom-rectangle bottom-rectangle-gday"></div>
            	</div>
			</div>

			<div class="wrapper">
                <?php echo do_shortcode( '[static_block_content id="2584"]' ); ?>
                
            </div>

			<div class="row-large">
				<div class="wrapper">
					<div class="top-rectangle"></div>
                	<?php echo do_shortcode( '[static_block_content id="2587"]' ); ?>
					<div class="bottom-rectangle bottom-rectangle-gday"></div>
            	</div>
			</div>

			<div class="wrapper top-seller">
					<div class="top-rectangle"></div>
					<h2 class="main-title">TOP SELLERS</h2>
					<div class="text-center">Have a look at these great shirts to design on</div>
                	<?php echo do_shortcode( '[static_block_content id="2589"]' ); ?>
						<div class="button_bottom_create">
                    		<a class="button js-create-it-now bottom" href="/design-now">START DESIGNING NOW</a>
                		</div>
			</div>

			<div class="row-large">
				<div class="wrapper">
					<div class="top-rectangle"></div>
                	<?php echo do_shortcode( '[static_block_content id="2595"]' ); ?>
					<div class="bottom-rectangle bottom-rectangle-gday"></div>
            	</div>
			</div>
			
			<div class="row-large niche">
				<div class="wrapper">
					<h2 class="main-title">WHAT ARE YOU INTO?</h2>
					<div class="sub-main-title">Here's some ideas to get your brain into gear.</div>
					
					<?php
						/*
						$cloud = array("HOTRODS","PUMPING IRON","CYCLING","FISHING","CAUSES","YOUR TEAM","NURSES","CELEBRATIONS","HORSES","PETS","CAMPING","HUMOUR","RUNNING","YOGA","FAMILY","MOTORCYCLES");
						foreach ($cloud as $key => $item){
							echo "<div class=\"button_bottom_create\"><a class=\"button js-create-it-now bottom\" href=\"/design-now\">$item</a></div>";
						}
						*/
					?>
					<div class="keywords">
						<div class="keywords-01">
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">EXERSIZE</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">FISHING</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">NURSING</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">PETS</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">RUNNING</span></div>
						</div>

						<div class="keywords-02">
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">FASHION</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">CAUSES</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">CELEBRATIONS</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">CAMPING</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">YOGA</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">FAMILY</span></div>
						</div>

						<div class="keywords-03">
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">CYCLING</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">YOUR TEAM</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">HORSES</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">HUMOUR</span></div>
						<div class="button_bottom_create"><span class="button js-create-it-now bottom">MOTORCYCLES</span></div>
						</div>

					</div>
            	</div>
			</div>

			<div class="row-large hero-banner-bottom" style="background-image:url(<?php echo $bottom_banner[0]; ?>); background-position:center;">		
				<div class="wrapper">
						<div class="top-rectangle"></div>
						<h2 class="main-title">SOUND GOOD?</h2>
						
						<div class="button_bottom_create">
                    		<a class="button js-create-it-now bottom" href="/design-now">LET'S GET STARTED</a>
                		</div>
				        
				</div>
			</div>	
            
    </section><!-- #content -->

<div class="clearfix"></div>
<!--
		<div class="footer-container">
			<div class="wrapper">
			<div class="row footer-wrapper">
				<?php echo do_shortcode( '[static_block_content id="990"]' ); ?>
			</div>
            <div class="row ">
				<div class="col-md-3">
			
				<div class="footer-block-content">
					<div class="news_post_block">
							<?php 
							 	$args = array( 'category' => 1, 'post_type' =>  'post','posts_per_page'=>2 ); 
							    $postslist = get_posts( $args );    
							    foreach ($postslist as $post) :  setup_postdata($post); 
							    ?>
							    <div class="post_row">
							    	<a href="<?php the_permalink(); ?>">
							    	<?php 
							    		if ( has_post_thumbnail() ) {
												echo get_the_post_thumbnail( $post->ID, array(60,60) );
										}
										else
										{
											?>
												<img class="avatar" src="https://placeholdit.imgix.net/~text?txtsize=13&txt=avatar&w=60&h=60" />
											<?php
										}
							    	?>
									</a>
									<div class="content_post">
										<a href="<?php the_permalink(); ?>" class="news_post_title"><?php the_title(); ?></a>
										<div class="news_post_des"><?php echo wp_trim_words( get_the_content(), 10 ); ?></div>
									</div>
									<div class="clearfix"></div>
								</div> 
							    <?php endforeach;
							 ?>
					</div>
				</div>
				</div>

				<div class="col-md-3">
				
				<div class="footer-block-content">
					<?php echo do_shortcode( '[static_block_content id="992"]' ); ?>
				</div>
				</div>
				<div class="col-md-3">
				
				<div class="footer-block-content">
					<?php echo do_shortcode( '[static_block_content id="993"]' ); ?>
				</div>
				</div>
				<div class="col-md-3">
				
				<div class="footer-block-content">
					<?php echo do_shortcode( '[static_block_content id="994"]' ); ?>
				</div>
				</div>
			</div>
               </div> 
				</div>
-->
<?php get_footer(); ?>