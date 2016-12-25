<?php
/* Template Name: Design Now */

get_header();
global $ti_option;
$currency_code = "AUD";
?>

    <section id="content" role="main" class=" woocommerce choose_design_page clearfix animated">

        <?php
        if (have_posts()) : while (have_posts()) : the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="page-content">

                    <div class="wrapper">


                            <?php the_content(); ?>




                    </div>
            </article>

            </div>
        <?php endwhile;
        endif; ?>


    </section><!-- #content -->
<script>
    var success_redirect = function(link){
        window.location.href = link;
    };
</script>
<?php  get_footer(); ?>