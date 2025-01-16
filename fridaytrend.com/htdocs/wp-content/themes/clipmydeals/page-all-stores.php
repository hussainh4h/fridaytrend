<?php /* Template Name: All Stores */ ?>

<?php
$placeholder = get_theme_mod('default_store_image');
$layout_options = clipmydeals_layout_options();
get_header();
global $exclude_stores;

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-page-all-stores <?= $layout_options['container'] ?> pt-5">
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
							$stores = get_terms(array(
								'taxonomy' => 'stores',
								'hide_empty' => false,
								'orderby' => 'name',
								'order' => 'ASC',
							));
							$store_details = array();
							foreach ($stores as $store) {
								$store_custom_fields = cmd_get_taxonomy_options($store->term_id, 'stores');
								if ($store_custom_fields['status'] == 'inactive') {
									$exclude_stores[] = $store->term_id;
									continue;
								}

								$store_details[] =  array(
									'term_id' => $store->term_id,
									'name' => $store->name,
									'slug' => $store->slug,
									'count' => $store->count,
									'term_meta' => $store_custom_fields,
								);
							}
							if (get_theme_mod('orderby_priority', 'yes') == 'yes') {
								$store_details = cmd_store_category_sort($store_details);
							}
							foreach ($store_details as $store) {
								$store = (object)$store;
								$store_custom_fields = $store->term_meta;
								if ($store_custom_fields['status'] == "inactive") continue;
							?>
								<div class="col-6 col-sm-4 <?= ($layout_options['sidebar']) ? 'col-xl-3' : 'col-lg-3 col-xl-2' ?> p-2">
									<a href="<?php echo get_term_link($store->term_id); ?>" style="text-decoration:none;">
										<div class="cmd-taxonomy-card card h-100 p-2 rounded-4 shadow-sm">
											<?php
											if (!empty($store_custom_fields['store_logo'])) {
												$store_image_url = $store_custom_fields['store_logo'];
											} elseif ($placeholder) {
												$store_image_url = $placeholder;
											} else {
												$store_image_url = get_template_directory_uri() . "/inc/assets/images/random-feature.jpg";
											}
											?>
											<img src="<?= $store_image_url ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?= $store->name ?> Logo" />
											<div class="card-footer text-center">
												<div class="cmd-store-name fw-bold p-2"><?= $store->name ?></div>
												<div><?php echo $store->count . ' ' . __('Offers', "clipmydeals"); ?></div>
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
