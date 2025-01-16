<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
<div class="card-body">
    <header>
		<?php the_title( '<h1 class="card-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="card-text">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'clipmydeals' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</div><!-- card-body -->

<?php if ( get_edit_post_link() ) : ?>
	<footer class="card-footer">
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'clipmydeals' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
<?php endif; ?>

</article><!-- #post-## -->
