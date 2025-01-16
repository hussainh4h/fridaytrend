<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ClipMyDeals
 */

$layout_options = clipmydeals_layout_options();
get_header();

if(!get_option('cmd_old_header')) {
?>
<div class="cmd-single-attachment <?= $layout_options['container'] ?> pt-5">
  <div class="row mx-0">
<?php
}

?>

	<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar'])? 'col-sm-12 col-lg-8 col-lg-9':'col'?>">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
if($layout_options['sidebar']){
    get_sidebar();
}

if(!get_option('cmd_old_header')) {
?>
  </div> <!-- row -->
</div> <!-- container -->
<?php
}

get_footer();
