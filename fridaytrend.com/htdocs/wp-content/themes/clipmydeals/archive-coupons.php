<?php

/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

$layout_options = clipmydeals_layout_options();
get_header();
global $store_status;

if (!get_option('cmd_old_header')) { ?>
    <div class="cmd-archive-coupons <?= $layout_options['container'] ?> pt-5">
        <div class="row">
        <?php
} ?>
        <section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
            <main id="main" class="site-main" role="main">
                <?php if (have_posts()) : ?>

                    <header class="page-header">
                        <?php
                            the_archive_title('<h1 class="page-title">', '</h1>');
                            the_archive_description('<div class="archive-description">', '</div>');
                        ?>
                    </header><!-- .page-header -->
                    <div class="row">
                        <?php
                            /* Start the Loop */
                            while (have_posts()) : the_post();
                                /*
                                    * Include the Post-Format-specific template for the content.
                                    * If you want to override this in a child theme, then include a file
                                    * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                */
                                $term_id = get_the_terms(get_the_ID(),'stores')[0]->term_id; 
                                store_taxonomy_status($term_id);
                                if($store_status[$term_id]=='inactive') continue;
                                get_template_part('template-parts/content', 'coupons');
                            endwhile; 
                        ?>
                        <div class="col-md-12">
                            <?=  _navigation_markup( 
                                    '<div class="nav-previous">' . get_previous_posts_link( __( 'Previous Coupons', 'clipmydeals' ) ) . '</div>' .
                                        '<div class="nav-next">' . get_next_posts_link( __( 'Next Coupons', 'clipmydeals' ) ) . '</div>'
                                    , 'posts-navigation', 
                                    __( 'Coupons navigation', 'clipmydeals' ), 
                                    __( 'Coupons', 'clipmydeals' ) 
                            ) ?>
                        </div>
                    </div>
                <?php else :
                    get_template_part('template-parts/content', 'none');
                endif; ?>
            </main><!-- #main -->
        </section><!-- #primary -->

        <?php
            if ($layout_options['sidebar']) {
                get_sidebar();
            }

if (!get_option('cmd_old_header')) { ?>
        </div> <!-- row -->
    </div> <!-- container -->
<?php }

get_footer();