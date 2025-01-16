<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

?>

<section class="no-results not-found col-12">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'No Coupons Found', 'clipmydeals' ); ?></h1>
	</header><!-- .page-header -->
	<div>
	<?php
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
	?>
	</div>

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( 
					/* translators: 1: New post*/
					wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'clipmydeals' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) 
				); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'clipmydeals' ); ?></p>
			<?php
				get_search_form();

		else : ?>

			<p><?php esc_html_e( 'It seems we don&rsquo;t have any coupons for this category. Try searching for something else.', 'clipmydeals' ); ?></p>
			<?php
				get_search_form();

		endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
