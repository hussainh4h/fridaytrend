<?php
/**
 * Template Name: Blank with Container
 */

get_header();
?>
    <section id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="cmd-blank-page-with-container <?= $layout_options['container'] ?> pt-5">
                <?php
                while ( have_posts() ) : the_post();
                    get_template_part( 'template-parts/content', 'notitle' );
                endwhile; // End of the loop.
                ?>
            </div>
        </main><!-- #main -->
    </section><!-- #primary -->

<?php
get_footer();
