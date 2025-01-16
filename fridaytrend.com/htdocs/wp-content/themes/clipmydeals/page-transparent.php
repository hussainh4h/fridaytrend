<?php

/**
 * Template Name: Blank with Transparent Container
 */
?>
<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

$layout_options = clipmydeals_layout_options();
get_header();

if (!get_option('cmd_old_header')) {
?>
    <div class="cmd-page <?= $layout_options['container'] ?> pt-5">
        <div class="row mx-0">
        <?php
    }

        ?>

        <section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
            <main id="main" class="card bg-transparent border-0 site-main" role="main">

                <?php
                while (have_posts()) : the_post();
                ?>

                    <article id="post-<?php the_ID(); ?>">
                        <div>
                            <header>
                                <?php the_title('<h1 class="card-title mb-3">', '</h1>'); ?>
                            </header><!-- .entry-header -->

                            <div class="card-text">
                                <?php
                                the_content();

                                wp_link_pages(array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'clipmydeals'),
                                    'after'  => '</div>',
                                ));
                                ?>
                            </div><!-- .entry-content -->

                        </div><!-- card-body -->

                        <?php if (get_edit_post_link()) : ?>
                            <footer>
                                <?php
                                edit_post_link(
                                    sprintf(
                                        /* translators: %s: Name of current post */
                                        esc_html__('Edit %s', 'clipmydeals'),
                                        the_title('<span class="screen-reader-text">"', '"</span>', false)
                                    ),
                                    '<span class="edit-link">',
                                    '</span>'
                                );
                                ?>
                            </footer><!-- .entry-footer -->
                        <?php endif; ?>

                    </article><!-- #post-## -->
                <?php

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </section><!-- #primary -->

        <?php
        if ($layout_options['sidebar']) {
            get_sidebar();
        }

        if (!get_option('cmd_old_header')) {
        ?>
        </div> <!-- row -->
    </div> <!-- container -->
<?php
        }

        get_footer();
