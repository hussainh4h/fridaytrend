<?php
/**
 * The template for displaying Woocommerce Product
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

if(!get_option('cmd_old_header')) {
?>
<div class="cmd-woocommerce <?= $layout_options['container'] ?> pt-5">
  <div class="row mx-0">
<?php
}

?>

    <section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar'])? 'col-sm-12 col-lg-8 col-lg-9':'col'?>">
        <main id="main" class="site-main" role="main">

			<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
				<div class="card-body">

					<div class="card-text">

						<?php woocommerce_content(); ?>

					</div>

				</div>
			</article>

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
