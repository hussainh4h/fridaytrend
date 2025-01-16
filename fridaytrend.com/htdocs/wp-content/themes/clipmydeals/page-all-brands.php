<?php /* Template Name: All Brands */ ?>

<?php
$placeholder = get_theme_mod('default_store_image');
$layout_options = clipmydeals_layout_options();
get_header();

global $exclude_coupons;
$stores = get_terms('stores', array(
	'hide_empty' => false,
));
foreach ($stores as $store) {
	store_taxonomy_status($store->term_id);
}
exclude_coupons();

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-page-all-brands <?= $layout_options['container'] ?> pt-5">
		<div class="row mx-0">
		<?php
	}

		?>

		<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
			<main id="main" class="site-main" role="main">

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">

						<div>
							<?php
							while (have_posts()) : the_post();
								the_content();
							endwhile;
							?>
						</div>

						<div class="row">
							<?php
							$brands = get_terms(array(
								'taxonomy' => 'brands',
								'hide_empty' => false,
								'orderby' => 'name',
								'order' => 'ASC',
							));
							$brand_details = array();
							foreach ($brands as $brand) {
								$brand_custom_fields = cmd_get_taxonomy_options($brand->term_id, 'brands');
								$brand_details[] =  array(
									'term_id' => $brand->term_id,
									'name' => $brand->name,
									'slug' => $brand->slug,
									'count' => count(query_posts(array('brands' => $brand->slug, 'posts_per_page' => -1, 'post__not_in' => $exclude_coupons))),
									'term_meta' => $brand_custom_fields,
								);
							}
							foreach ($brand_details as $brand) {
								$brand = (object)$brand;
								$brand_custom_fields = $brand->term_meta;
							?>
								<div class="col-6 col-sm-4 <?= ($layout_options['sidebar']) ? 'col-xl-3' : 'col-lg-3 col-xl-2' ?> p-2">
									<a href="<?php echo get_term_link($brand->term_id); ?>">
										<div class="cmd-taxonomy-card card h-100 p-2 rounded-4 shadow-sm">
											<?php if (!empty($brand_custom_fields['brand_image'])) { ?>
												<img src="<?php echo $brand_custom_fields['brand_image']; ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $brand->name; ?> Logo" />
											<?php } elseif ($placeholder) { ?>
												<img src="<?php echo $placeholder ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />
											<?php } else { ?>
												<img src="<?php echo get_template_directory_uri() . "/inc/assets/images/random-feature.jpg" ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />

											<?php } ?>
											<div class="card-footer text-center py-1 px-0">
												<div class="cmd-store-name fw-bold p-2"><?php echo $brand->name ?></div>
												<small><?php echo $brand->count . ' ' . ($brand->count > 1 ? __('Offers', "clipmydeals") : __('Offer', "clipmydeals")); ?></small>
											</div>
										</div>
									</a>
								</div>
							<?php
							}
							?>
						</div>

					</div>

					<?php if (get_edit_post_link()) : ?>
						<footer class="entry-footer">
							<?php
							edit_post_link(
								sprintf(
									/* translators: %s: Name of current post */
									esc_html__('Edit %s', 'clipmydeals'),
									the_title('<span class="screen-reader-text">"', '"</span>', false)
								),
								'<span class="edit-link">',
								'</span>'
							);
							?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>

				</article>

			</main><!-- #main -->
		</section><!-- #primary -->

		<?php
		if ($layout_options['sidebar']) {
			get_sidebar();
		}

		if (!get_option('cmd_old_header')) {
		?>
		</div> <!-- row -->
	</div> <!-- container -->
<?php
		}

		get_footer();
