<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */


global $exclude_coupons;
$stores = get_terms('stores', array(
	'hide_empty' => false,
));
foreach ($stores as $store) {
	store_taxonomy_status($store->term_id);
}
exclude_coupons();
$layout_options = clipmydeals_layout_options();
$placeholder = get_theme_mod('default_store_image');

get_header();
if (!get_option('cmd_old_header')) { ?>
	<div class="cmd-index <?= $layout_options['container'] ?> pt-5">
		<div class="row">
			<?php if (is_home() && is_front_page() && get_theme_mod('hp_popular_stores', false)) {
			?>
				<div id="default-popular-store" class="col-12 text-center">
					<h1 class="mt-0"><?php _e('Popular Stores', 'clipmydeals'); ?></h1>
					<div class="row justify-content-center">
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
							if ($store_custom_fields['status'] == 'inactive') continue;
							if ($store_custom_fields['popular'] == 'yes') {
								$store_details[] =  array(
									'term_id' => $store->term_id,
									'name' => $store->name,
									'slug' => $store->slug,
									'count' => $store->count,
									'term_meta' => $store_custom_fields,
								);
							}
						}
						if (get_theme_mod('orderby_priority', 'yes') == 'yes') {
							$store_details = cmd_store_category_sort($store_details);
						}
						foreach ($store_details as $store) {
							$store = (object)$store;
							$store_custom_fields = $store->term_meta;
						?>
							<div class="col-6 col-sm-4 col-md-3 col-lg-2 p-2">
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
										<img src="<?php echo $store_image_url; ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />
										<div class="card-footer text-center pt-2 pb-1">
											<?php
											$cashback_options = cmd_get_cashback_option();
											if (isset($cashback_options['message'][$store->term_id]) and $cashback_options['message'][$store->term_id]) { // cashback?
											?>
												<small><?php echo stripslashes($cashback_options['message'][$store->term_id]); ?></small>
											<?php } else { ?>
												<small><?php echo $store->count . ' ' . ($store->count > 1 ? __('Offers', 'clipmydeals') : __('Offer', 'clipmydeals')); ?></small>
											<?php } ?>
										</div>
									</div>
								</a>
							</div>
						<?php

						}
						?>
					</div>
				</div>
				<div class="col-12">
					<hr />
				</div>
			<?php
			} ?>
			<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
				<main id="main" class="home site-main" role="main">
				<?php
			}

			if (is_active_sidebar('homepage-widget')) { ?>
					<div id="homepage-widget-full" class="row p-0 m-0 border-0 d-print-none">
						<div class="col-12 p-0">
							<?php dynamic_sidebar('homepage-widget'); ?>
						</div>
					</div><!-- #primary -->
					<?php
				}

				if (is_home() && is_front_page()) {	// Top Offers & Posts

					if (get_theme_mod('hp_latest_coupons', true)) {
						global $wp_query; // This is the original query that gets POSTS.

						$latest_active_coupon_args = array(
							'post_type' => 'coupons',
							'orderby' => 'date meta_value',
							'meta_key' => 'cmd_display_priority',
							'post__not_in' => $exclude_coupons,
							'order' => 'DESC',
							'meta_query' => array(
								'relation' => 'AND',
								array(
									'key' => 'cmd_start_date',
									'value' => current_time('Y-m-d'),
									'compare' => '<='
								),
								array(
									'relation' => 'OR',
									array(
										'key' => 'cmd_valid_till',
										'value' => current_time('Y-m-d'),
										'compare' => '>='
									),
									array(
										'key' => 'cmd_valid_till',
										'value' => '',
										'compare' => '='
									),
								),
							),
							'posts_per_page' => get_theme_mod('hp_coupon_count', 12)
						);
						//$offers_query = array_merge($latest_active_coupon_args, $wp_query->query);
						//query_posts($offers_query);
						query_posts($latest_active_coupon_args);
						if (have_posts()) {
							if (is_active_sidebar('homepage-widget')) { ?>
								<div class="col-12">
									<hr />
								</div>
							<?php
							} ?>
							<h1><?php _e('Latest Offers', 'clipmydeals'); ?></h1>
							<div class="row items-stretch">
								<?php
								while (have_posts()) : the_post();
									get_template_part('template-parts/content', 'homepagecoupons');
								endwhile;
								?>
							</div>
						<?php
						} else { ?>
							<p class="alert alert-danger"><?php _e('You have not added any Coupon so far.', 'clipmydeals'); ?></p>
						<?php
						}

						wp_reset_query();
					}

					query_posts($wp_query->query);
					if (have_posts() && get_theme_mod('hp_latest_posts', true)) { ?>
						<h1><?php _e('Recent Posts', 'clipmydeals'); ?></h1>
						<div id="recent-post" class="row my-2 px-1 justify-content-start <?= ($layout_options['sidebar']) ? 'sidebar-blogs-section' : 'blogs-section' ?> ">
							<?php
							while (have_posts()) : the_post();
								get_template_part('template-parts/post', get_post_format());
							endwhile;
							?>
						</div>
					<?php
					}
				} else { ?>

					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
					<?php
					while (have_posts()) : the_post();
						get_template_part('template-parts/content', get_post_format());
					endwhile;
				}

				if (!get_option('cmd_old_header')) { ?>
				</main><!-- #main -->
			</section><!-- #primary -->
		<?php
				}

				if ($layout_options['sidebar']) {
					get_sidebar();
				}

				if (!get_option('cmd_old_header')) { ?>
		</div> <!-- row -->
	</div> <!-- container -->
<?php
				}

				get_footer();
