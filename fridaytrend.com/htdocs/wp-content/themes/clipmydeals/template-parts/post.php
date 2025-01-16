<?php

/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */
$layout_options = clipmydeals_layout_options();

?>

<article id="post-<?php the_ID(); ?>" <?php post_class((($layout_options['sidebar']) ? '' : 'col-xl-3 ') . ' col-sm-6 col-lg-4  mb-0'); ?>>
    <div class="card p-0 rounded-4 mb-4">
        <?php the_post_thumbnail('full', array('class' => 'card-img-top rounded-top-4')); ?>
        <div class="card-body pt-0">
            <header>
                <?php
                if (is_single()) :
                    the_title('<h1 class="card-title fs-3">', '</h1>');
                else :
                    the_title('<h2 class="card-title fs-3 mt-3"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                endif;
                ?>
            </header>
            <div class="card-text blog-description">
                <?php

                //the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'clipmydeals' ) );
                the_excerpt();

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'clipmydeals'),
                    'after'  => '</div>',
                ));
                ?>
                <div class="d-flex align-items-center cmd-blog-readmore">
                    <a class="fs-6 fw-bold" href="<?= esc_url(get_permalink()) ?>"><?= __("Read More",'clipmydeals') ?>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="ms-2 cmd-blog-readmore-icon" height="1em" viewBox="0 0 512 512">
                        <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z" />
                    </svg>
                </div>
            </div><!-- .entry-content -->
        </div>
    </div>
</article><!-- #post-## -->