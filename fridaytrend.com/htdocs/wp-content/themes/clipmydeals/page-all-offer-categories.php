<?php /* Template Name: All Offer Categories */ ?>

<?php

$layout_options = clipmydeals_layout_options();
get_header();

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-page-all-offer-categories <?= $layout_options['container'] ?> pt-5">
		<div class="row mx-0">
		<?php
	}

	global $exclude_coupons;
	$stores = get_terms('stores', array(
		'hide_empty' => false,
	));
	foreach ($stores as $store) {
		store_taxonomy_status($store->term_id);
	}
	exclude_coupons();

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

							$all_categories = get_terms(array(
								'taxonomy' => 'offer_categories',
								'hide_empty' => false,
							));

							$parent_categories = get_terms(array(
								'taxonomy' => 'offer_categories',
								'hide_empty' => false,
								'parent' => 0,
								'orderby' => 'count',
								'order' => 'DESC',
							));

							if (count($all_categories) == count($parent_categories)) {
							?>
								<?php foreach ($parent_categories as $parent) { // non-hierarchical
								?>
									<div class="col-6 col-sm-4 <?= ($layout_options['sidebar']) ? 'col-xl-3' : 'col-lg-3 col-xl-2' ?> p-2">
										<a class="text-decoration-none" href="<?php echo get_term_link($parent); ?>">
											<div class="cmd-taxonomy-card card h-100 p-2 rounded-4 shadow-sm">
												<h2 class="fs-5 card-header text-center"><?php echo $parent->name ?></h2>
												<div class="card-footer text-center py-1 px-0"><small><?php echo count(query_posts(array('offer_categories' => $parent->slug, 'posts_per_page' => -1, 'post__not_in' => $exclude_coupons))) . ' ' . __('Offers', "clipmydeals"); ?></small></div>
											</div>
										</a>
									</div>
								<?php } ?>
								<?php

							} else {

								foreach ($parent_categories as $parent) { // hierarchical foreach starts
									$parent_count = count(query_posts(array('offer_categories' => $parent->slug, 'posts_per_page' => -1, 'post__not_in' => $exclude_coupons)));
								?>


									<div class="col-12 col-md-6 col-xl-4 p-2">
										<div class="cmd-taxonomy-card cmd-multilevel-card card my-3 p-2 rounded-4 shadow-sm">
											<div class="card-header text-center border-0">
												<a class="text-decoration-none" href="<?php echo get_term_link($parent); ?>">
													<h2 class="fs-5">
														<?= $parent->name ?>
													</h2>
												</a>
											</div>
											<div class="card-body p-2">
												<div class="cmd-child-taxonomies list-group">
													<?php
													$sub_categories =  get_terms(array(
														'taxonomy' => 'offer_categories',
														'hide_empty' => false,
														'child_of' => $parent->term_id,
													));
													if (!empty($sub_categories)) {
														foreach ($sub_categories as $child) {
													?>
															<a href="<?php echo get_term_link($child); ?>" class="list-group-item border-0 d-flex justify-content-between align-items-center">
																<?php echo $child->name ?>
																<span class="badge bg-primary rounded-pill"><?php echo $child->count ?></span>
															</a>
														<?php
														}
													} else {
														?>
														<a class="text-decoration-none text-center" href="<?php echo get_term_link($parent); ?>">
															<small><?php echo $parent_count . ' ' . ($parent_count > 1 ? __('Offers', "clipmydeals") : __('Offer', "clipmydeals")); ?></small>
														</a>
													<?php
													}
													?>
												</div>
											</div>
										</div>
									</div>
							<?php
								} // hierarchical foreach ends

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
