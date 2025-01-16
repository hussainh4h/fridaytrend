<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ClipMyDeals
 */
$layout_options = clipmydeals_layout_options();
get_header();
global $store_status;

if(!get_option('cmd_old_header')) {
?>
<div class="cmd-not-found <?= $layout_options['container'] ?> pt-5">
  <div class="row mx-0">
<?php
}

?>

	<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar'])? 'col-sm-12 col-lg-8 col-lg-9':'col'?>">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'clipmydeals' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'clipmydeals' ); ?></p>

					<?php
						get_search_form();
					?>
					<div>
					<?php
						while ( have_posts() ) : the_post();
						if(is_tax()=="stores"){
							$store_id = get_queried_object_id();
							if($store_status[$store_id]=="inactive") continue;
						}
						if(get_post_type()=="coupons"){
							$store_terms = get_the_terms(get_the_ID(),'stores');
							store_taxonomy_status($store_terms[0]->term_id);
							if($store_status[$store_terms[0]->term_id] == 'inactive') continue;
						}
						the_content();
						endwhile;
					?>
					</div>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

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
