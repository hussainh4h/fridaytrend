<?php
/**
 * Template Name: Left Sidebar
 */

$layout_options = clipmydeals_layout_options();
get_header();

if(!get_option('cmd_old_header')) {
?>
<div class="cmd-left-sidebar <?= $layout_options['container'] ?> pt-5">
  <div class="row mx-0">
<?php
}

if($layout_options['sidebar']){
    get_sidebar();
}
?>
    <section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar'])? 'col-sm-12 col-lg-8 col-lg-9':'col'?>">
        <main id="main" class="site-main" role="main">

            <?php
            while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content', 'page' );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->
    </section><!-- #primary -->

<?php

if(!get_option('cmd_old_header')) {
?>
  </div> <!-- row -->
</div> <!-- container -->
<?php
}

get_footer();
