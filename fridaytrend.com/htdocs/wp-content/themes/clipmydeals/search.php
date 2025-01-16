<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package ClipMyDeals
 */

// If Store
if (term_exists($_GET['s'], 'stores')) {
	$term = get_term_by('id', term_exists($_GET['s'], 'stores')['term_id'], 'stores');
	wp_redirect(get_bloginfo('url') . '/store/' . $term->slug . '/');
	die();
}
// If Offer Category
if (term_exists($_GET['s'], 'offer_categories')) {
	$term = get_term_by('id', term_exists($_GET['s'], 'offer_categories')['term_id'], 'offer_categories');
	wp_redirect(get_bloginfo('url') . '/offer-category/' . $term->slug . '/');
	die();
}

// If Brand
if (term_exists($_GET['s'], 'brands')) {
	$term = get_term_by('id', term_exists($_GET['s'], 'brands')['term_id'], 'brands');
	wp_redirect(get_bloginfo('url') . '/brand/' . $term->slug . '/');
	die();
}

// If Location
if (term_exists($_GET['s'], 'locations') and get_theme_mod('location_taxonomy', false)) {
	$term = get_term_by('id', term_exists($_GET['s'], 'locations')['term_id'], 'locations');
	wp_redirect(get_bloginfo('url') . '/location/' . $term->slug . '/');
	die();
}

$layout_options = clipmydeals_layout_options();
get_header();

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-search <?= $layout_options['container'] ?> pt-5">
		<div class="row mx-0">
		<?php
	}

		?>

		<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
			<main id="main" class="site-main" role="main">

				<header class="page-header">
					<?php if (get_search_query()) { ?>
						<h1 class="page-title"><?php printf(
													/* translators: 1:Search query */
													esc_html__('Search Results for: %s', 'clipmydeals'),
													'<span>' . get_search_query() . '</span>'
												); ?></h1>

					<?php } else { ?>
						<h1 class="page-title"><?php printf(
													/* translators: 1:Search query */
													esc_html__('Search Results', 'clipmydeals')
												); ?></h1>
					<?php } ?>
				</header><!-- .page-header -->

				<div class="row">
					<?php

					$paged = get_query_var('paged') ? get_query_var('paged') : 1;

					global $wp_query;

					$args_active_coupons = array(
						'meta_key' => 'cmd_display_priority',
						'orderby' => 'meta_value_num',
						'order' => 'DESC',
						'posts_per_page' => get_theme_mod('active_coupon_count', 50),
						'paged' => $paged,
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
					);

					$args_active_coupons['tax_query']['relation'] = 'AND';
					// Store, Category & Location Filter
					if (!empty($_GET['store'])) {
						$args_active_coupons['tax_query'][] = array(
							'taxonomy' => 'stores',
							'field'    => 'slug',
							'terms'    => array($_GET['store']),
						);
					}
					if (!empty($_GET['offer-category'])) {
						$args_active_coupons['tax_query'][] = array(
							'taxonomy' => 'offer_categories',
							'field'    => 'slug',
							'terms'    => array($_GET['offer-category']),
						);
					}
					if (get_theme_mod('location_taxonomy', false) and !empty($_GET['location'])) {
						$args_active_coupons['tax_query'][] = array(
							'taxonomy' => 'locations',
							'field'    => 'slug',
							'terms'    => array($_GET['location']),
						);
					}
					if (count($args_active_coupons['tax_query']) == 2) {
						// Only one of the above was added. So need to remove the first element ie relation=>AND
						array_shift($args_active_coupons['tax_query']);
					}

					$active_query = array_merge($args_active_coupons, $wp_query->query);
					$queried_posts = query_posts($active_query);

					if (have_posts()  and get_theme_mod('active_coupon_count', 10) != 0) :

						while (have_posts()) : the_post();
							get_template_part('template-parts/content', get_post_type());
						endwhile;

					else :
						get_template_part('template-parts/content', 'nocoupons');

					endif;
					?>

					<!-- Pagination -->
					<div class="col-md-12">
						<div class="row mb-2">
							<div class="col-sm-6">
								<?php previous_posts_link('<button class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i>'.__("Previous Page",'clipmydeals').'</button>'); ?>
							</div>
							<div class="col-sm-6">
								<?php next_posts_link('<button class="btn btn-secondary btn-sm float-end">'.__('Next Page','clipmydeals').' <i class="fa fa-arrow-right"></i></button>'); ?>
							</div>
						</div>
					</div>

				</div><!-- #row -->

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
