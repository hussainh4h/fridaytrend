<?php

/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

global $exclude_coupons;
$stores = get_terms('stores', array(
	'hide_empty' => false,
));
foreach ($stores as $store) {
	store_taxonomy_status($store->term_id);
}
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
$offer_category_custom_fields = cmd_get_taxonomy_options($term->term_id, 'offer_categories');

$layout_options = clipmydeals_layout_options();
get_header();

exclude_coupons();

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-taxonomy-offer_categories <?= $layout_options['container'] ?> pt-5">
		<div class="row mx-0">
		<?php
	}

		?>

		<section id="primary" class="offer_category-page taxonomy-page content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
			<main id="main" class="site-main" role="main">

				<header class="row page-header mb-2">
					<?php if (!empty($offer_category_custom_fields['category_image'])) { ?>
						<div class="col-sm-3 my-1">
							<img class="img-thumbnail img-fluid" src="<?php echo $offer_category_custom_fields['category_image']; ?>" />
						</div>
					<?php } ?>
					<div class="col my-1">
						<div class="alert alert-secondary" id="offer_categories-archive-alert">
							<?php
							$term_id = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'))->term_id;
							$custom_title = $offer_category_custom_fields['page_title'];
							$title = !empty($custom_title) ? stripslashes($custom_title) : $term->name;
							?>
							<h1 class="page-title my-0"><?php echo $title ?></h1>
							<?php
							if (!empty(get_the_archive_description())) {
								echo '<hr/>';
								the_archive_description('<div class="archive-description">', '</div>');
							}
							?>
						</div>
					</div>
				</header><!-- .page-header -->

				<?php
				if (get_theme_mod('article_placement', 'bottom') == 'top' and !empty($offer_category_custom_fields['category_intro']))
					echo '<div class="card"><article class="card-body">' . do_shortcode(stripslashes($offer_category_custom_fields['category_intro'])) . '</article></div>';
				?>

				<div class="row py-5">
					<?php

					$paged = get_query_var('paged') ? get_query_var('paged') : 1;

					global $wp_query;

					$args_active_coupons = array(
						'meta_key' => 'cmd_display_priority',
						'orderby' => 'meta_value_num date',
						'order' => 'DESC',
						'posts_per_page' => get_theme_mod('active_coupon_count', 50),
						'post__not_in' => $exclude_coupons,
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

					$active_query = array_merge($args_active_coupons, $wp_query->query);
					$queried_posts = query_posts($active_query);

					if (have_posts()  and get_theme_mod('active_coupon_count', 50) != 0) :

						while (have_posts()) : the_post();
							get_template_part('template-parts/content', get_post_type());
						endwhile;

					else :
						get_template_part('template-parts/content', 'nocoupons');

					endif;
					?>
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
				</div>
				<?php
				wp_reset_query(); // reset query for next use

				$args_expired_coupons = array(
					'meta_key' => 'cmd_display_priority',
					'orderby' => 'meta_value_num date',
					'order' => 'DESC',
					'posts_per_page' => get_theme_mod('expired_coupon_count', 10),
					'post__not_in' => $exclude_coupons,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'cmd_valid_till',
							'value' => current_time('Y-m-d'),
							'compare' => '<'
						),
						array(
							'key' => 'cmd_valid_till',
							'value' => '',
							'compare' => '!='
						),
					),
				);
				$expired_query = array_merge($args_expired_coupons, $wp_query->query);
				query_posts($expired_query);

				if (have_posts() and $paged == 1 and get_theme_mod('expired_coupon_count', 10) != 0) {
				?>
					<h2><?= __('Recently Expired Offers', 'clipmydeals') ?></h2>
					<hr />
					<div class="row">
						<?php
						while (have_posts()) : the_post();
							get_template_part('template-parts/content', get_post_type());
						endwhile;
						?>
					</div>
				<?php
				}
				?>

				<?php
				if (get_theme_mod('article_placement', 'bottom') == 'bottom' and !empty($offer_category_custom_fields['category_intro']))
					echo '<div class="card"><article class="card-body">' . do_shortcode(stripslashes($offer_category_custom_fields['category_intro'])) . '</article></div>';
				?>

				<footer>
				</footer>

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
