<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
<div class="card-body pt-0">
	<header>
		<?php the_title( sprintf( '<h2 class="card-title mt-3"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php if ( 'post' === get_post_type() ) : ?>
		<h6 class="card-subtitle mb-3 text-muted">
			<?php clipmydeals_posted_on(); ?>
		</h6><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="card-text">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

</div>

<footer class="card-footer">
	<?php clipmydeals_entry_footer(); ?>
</footer><!-- .entry-footer -->

</article><!-- #post-## -->
