<?php
/* Template Name: Sizing Info */

get_header();
global $ti_option;
?>

    <section id="content" role="main" class=" woocommerce choose_design_page clearfix animated">

        <?php
        /**
         * If Featured Image is uploaded set it as a background
         * and change page title color to white
         * */
        if (has_post_thumbnail()) {
            $page_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'big-size');
            $page_bg_image = 'style="background-image:url(' . $page_image_url[0] . ');"';
            $title_with_bg = 'title-with-bg';
        } else {
            $title_with_bg = 'wrapper title-with-sep';
        }

        remove_filter ('the_content', 'wpautop');


        ?>

        <header class="entry-header page-header choose_design_page-header" style="">
            <div class="page-title">
                <div class="wrapper">
                    <h1 class="entry-title choose_design_title" style=""><?php the_title(); ?>...</h1>
                </div>
            </div>
        </header>


        <?php
        if (have_posts()) : while (have_posts()) : the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="page-content">
                    <div class="intro_design">
                        <div class="wrapper">
                            <?php
                            echo get_post_meta(get_the_ID(), 'top_content', true);
                            // the_content(); ?>
                        </div>
                    </div>

                    <div class="wrapper">
                        <?php the_content(); ?>
                        
                        <?php
                        // Set up the objects needed
                        ////$my_wp_query = new WP_Query();
                     //   $all_wp_pages = $my_wp_query->query(array('post_type' => 'page'));


                        $sizing =  get_page_by_title('Sizing Info');


                    //    $sizing_children = get_page_children( $sizing->ID, $all_wp_pages );
                         $args = array(
                            'sort_order' => 'asc',
                            'sort_column' => 'post_title',
                            'hierarchical' => 1,
                            'exclude' => '',
                            'include' => '',
                            'meta_key' => '',
                            'meta_value' => '',
                            'authors' => '',
                            'child_of' => $sizing->ID,
                            'parent' => -1,
                            'exclude_tree' => '',
                            'number' => '',
                            'offset' => 0,
                            'post_type' => 'page',
                            'post_status' => 'publish'
                        );
                        $pages = get_pages($args);
                        ?>
                        <div class="box_desgin_choose">
                            <ul class="categories_menu">
                                <?php
                                $first_id = 0;
                                $i_counter=0;
                                foreach ($pages as $children) {
                                    if($first_id==0 ) $first_id =$children->ID;
                                    $i_counter++;
                                    ?>
                                    <li class="no_<?php echo $i_counter ?> <?php echo $first_id!=0&&$first_id ==$children->ID?'active':''; ?>" data-cate-id="<?php echo $children->ID; ?>" >
                                        <a href="javascript:void(0)" data-cate-id="<?php echo $children->ID;?>" ><?php echo $children->post_title; ?></a>
                                    </li>
                                    <?php
                                }
                                ?>
                                <div style="clear: both"></div>
                            </ul>
                            <div style="clear: both"></div>
                            <div class="content_tabs">
                                <?php

                                    foreach ($pages as $children) {
                                        ?>
                                        <div class="child_cate_menu  child_cate_<?php echo $children->ID; ?> ">
                                            <?php
                                            
                                            $content = $children->post_content;
                                            $content = apply_filters('the_content', $content);
                                            $content = str_replace(']]>', ']]>', $content);
                                            echo $content;
                                            ?>
                                        </div>
                                        <?php
                                    }
                                wp_reset_query();
                                ?>
                                <div style="clear: both"></div>
                            </div>
                        </div>

                        <script>
                            jQuery('.categories_menu li a').click(function(){
                                change_to_tab(jQuery(this).data('cate-id'));
                                return false;
                            });
                            function change_to_tab(cat_id){
                                jQuery('.child_cate_menu').css('display','none');
                                jQuery('.categories_menu li').removeClass('active');
                                jQuery('.categories_menu li').each(function(index){
                                    if(jQuery(this).data('cate-id')==cat_id){
                                        jQuery(this).addClass('active');
                                    }
                                });
                                jQuery('.child_cate_'+cat_id).css('display','block');

                                if(jQuery('.child_cate_'+cat_id+ ' ul li').length>0){

                                }else{
                                    jQuery('#category_product_result').html('');
                                }

                            }

                            jQuery(document).ready(function () {
                                jQuery( ".categories_menu li.active a" ).each(function( index ) {
                                    change_to_tab(jQuery(this).data('cate-id'));
                                });
                            });

                        </script>

                    </div>


                </div>
            </article>

        <?php endwhile;
        endif; ?>

        <?php
        // Enable/Disable comments
        if ($ti_option['site_page_comments'] == 1) {
            comments_template();
        }
        ?>


    </section><!-- #content -->

<?php get_footer(); ?>