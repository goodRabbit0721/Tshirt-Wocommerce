<?php
/* Template Name: Choose Design */

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
                        <style>
                     
                        </style>
                            <div class="wrapper">

                        <div class="box_desgin_choose">
                                <ul class="categories_menu">
                                <?php
                                $category_id = get_post_meta(get_the_ID(), 'category_id', true);
                                $_cates = get_terms('product_cat', 'orderby=term_id&order=ASC&&hide_empty=0&hierarchical=0&parent=' . $category_id);
                               $first_id = 0;
                               $i_counter=0; 
                                foreach ($_cates as $cate) {
                                if($first_id==0 ) $first_id =$cate->term_id;
                                 $i_counter++; 
                                        ?>
                                                <li class="no_<?php echo $i_counter ?> <?php echo $first_id!=0&&$first_id ==$cate->term_id?'active':''; ?>" data-cate-id="<?php echo $cate->term_id; ?>" >
                                                    <a href="javascript:void(0)" data-cate-id="<?php echo $cate->term_id;?>" ><?php echo $cate->name; ?></a>
                                                </li>
                                        <?php
                                   
                                }
                                ?>
                                <div style="clear: both"></div>
                                </ul>
                                <div style="clear: both"></div>
                                <div class="content_tabs">
                                   
                                    <?php
                                     $first_id_child = 0;
                                    
                                    foreach ($_cates as $cate) {
                                        
                                         ?>
                                          <div class="child_cate_menu  child_cate_<?php echo $cate->term_id; ?> ">
                                         <?php
                                     //    $_child_cates = get_terms('product_cat', 'orderby=term_id&order=ASC&&hide_empty=0&hierarchical=0&parent=' . $cate->term_id);
                                         
                                          $query_args = array('posts_per_page' => -1, 'orderby' => 'ID',
                                            'order'   => 'ASC', 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'product_cat',
                                                    'field' => 'id',
                                                    'terms' => array($cate->term_id)
                                        )));
                                        $_products = new WP_Query($query_args);
                                        
                                          if ($_products->have_posts()) {
                                           echo "<ul>";
                                              $array_ordered = array();
                                            while ($_products->have_posts()) : $_products->the_post();
                                            global $product; 
                                            if($first_id_child==0) $first_id_child =get_the_ID();
                                                if(get_the_ID()==1493){
                                                    $order_n = 0;
                                                }
                                                if(get_the_ID()==1557){
                                                    $order_n = 1;
                                                }
                                                if(get_the_ID()==1504){
                                                    $order_n = 2;
                                                }
                                                if(get_the_ID()==1946){
                                                    $order_n = 3;
                                                }

                                                $array_ordered[$order_n]='<li data-product-id="'.get_the_ID().'">
                                                    <a href="javascript:void(0)" data-product-id="'.get_the_ID().'" >'.get_the_title().'</a>
                                                </li> ';
                                            endwhile;
                                              ksort($array_ordered);
                                              echo implode('',$array_ordered);
                                              echo "</ul>";
                                          }else{
                                            echo "<div class='message-coming-soon'><span>Australian First!</span>
                                            <p class='message-coming-soon-text'>In the very near future TeeM8 will be extending the product range we offer to polo shirts and embroidery. Don't forget to visit back and check out this section when it goes live. For more up to the minute updates, please follow our Facebook Page. </p></div>
                                            ";
                                          }
                                         
                                         
                                         ?>
                                         </div>
                                         <?php
                                    }
                                    wp_reset_query();
                                    ?> 
                                     <div style="clear: both"></div>
                                </div>
                            </div>
                            
                     
                            <div id="category_product_result">
                                
                            </div>
                           
                               <div class="submit_loading ajax_product_loading">
                                    <div class="fpd-loading"></div>
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
                               var pro= jQuery('.child_cate_'+cat_id+ ' ul li').first();
                               var pro_id = jQuery(pro).data('product-id');

                                //  console.log(pro);
                                get_desgin_ajax(pro_id);
                              }else{
                                    jQuery('#category_product_result').html('');
                              }
                              
                            }
                            jQuery('.content_tabs ul li a').click(function(){
                                jQuery('.content_tabs ul li').removeClass('active');
                                jQuery(this).parent().addClass('active');
                                get_desgin_ajax(jQuery(this).data('product-id'));
                                return false;
                            });
                            function get_desgin_ajax(product_id){
                             jQuery('#category_product_result').html('');
                            jQuery(".ajax_product_loading").show();
                            
                            jQuery.ajax({
                                type: "POST",
                                url: ajax_login_object.ajaxurl,
                                data: {
                                    'action': "getProductDetail",
                                    'product_id': product_id
                                },
                                success: function (response) {                                    
                                    if (response.status == 'success') {                          
                                        if(response.html!=''){
                                            jQuery('#category_product_result').html(response.html);
                                        }else{
                                            jQuery('#category_product_result').html('<div class="message_ajax">'+response.message+'<div>');
                                        }
                                    } else {
                                       var html = '<h5 style="color: red;">' + response.message + '</h5>';
                                        jQuery('#category_product_result').html(html);
                                    }
                                     jQuery(".ajax_product_loading").hide();
                                },
                                error: function (data) {
                                    jQuery(".ajax_product_loading").hide();
                                }
                            }
                            );
                            }
                            jQuery(document).ready(function () {
                                jQuery( ".categories_menu li.active a" ).each(function( index ) {
                                     change_to_tab(jQuery(this).data('cate-id'));
                                });


                                jQuery('.content_tabs ul li').each(function(){
                                    if(jQuery(this).data('product-id')==<?php echo $first_id_child; ?>){
                                        jQuery(this).addClass('active');
                                    }
                                });
                                get_desgin_ajax(<?php echo $first_id_child; ?>);
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