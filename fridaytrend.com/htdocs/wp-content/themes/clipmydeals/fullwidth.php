<?php
/**
* Template Name: Full Width
 */

get_header();

if(!get_option('cmd_old_header')) {
?>
<div class="cmd-fullwidth <?= (isset($layout_options['container']) ? $layout_options['container']:'' ) ?> pt-5">
  <div class="row mx-0">
<?php
}

?>

	<section id="primary" class="content-area col-sm-12">
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
