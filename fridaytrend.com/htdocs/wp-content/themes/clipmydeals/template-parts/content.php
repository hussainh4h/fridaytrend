<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card p-0 rounded-4 mb-4'); ?>>
<?php the_post_thumbnail('full',array('class' => 'card-img-top')); ?>
<div class="card-body pt-0">
	<header>
	<?php
	if ( is_single() ) :
		the_title( '<h1 class="card-title">', '</h1>' );
	else :
		the_title( '<h2 class="card-title mt-3"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	endif;

	if ( 'post' === get_post_type() ) : ?>
	<h6 class="card-subtitle mb-3 text-muted">
		<?php clipmydeals_posted_on(); ?>
	</h6><!-- .entry-meta -->
	<?php
	endif; ?>
	</header>
	<div class="card-text">
		<?php
        if ( is_single() ) :
			the_content();
        else :
            //the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'clipmydeals' ) );
            the_excerpt();
        endif;

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'clipmydeals' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</div>
<footer class="card-footer">
		<?php clipmydeals_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
