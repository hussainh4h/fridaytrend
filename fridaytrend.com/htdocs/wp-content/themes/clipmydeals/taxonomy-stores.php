<?php

/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */

$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
$store_custom_fields = cmd_get_taxonomy_options($term->term_id, 'stores');

$title = !empty($store_custom_fields['page_title']) ? stripslashes($store_custom_fields['page_title']) : $term->name;
$placeholder = get_theme_mod('default_store_image');
$layout_options = clipmydeals_layout_options();
get_header();


if (!empty($store_custom_fields['store_banner'])) {
?>

	<div class="store-banner overflow-hidden" style="width:100%; height:auto;">
		<img class="w-100" alt="<?php echo $term->name; ?> Banner" src="<?php echo $store_custom_fields['store_banner']; ?>" />
	</div>

	<div class="row justify-content-center">
		<div class="col-11 col-lg-8">
			<div class="card rounded-4 shadow-lg" style="margin-top:-70px">
				<div class="card-body mb-0">
					<div class="row align-items-center">
						<div id="store-info" class="visit-website visit-website-si col-12 col-sm-6 col-md-4 text-center my-1 border-end">

							<?php if (!empty($store_custom_fields['store_logo'])) { ?>
								<img src="<?php echo $store_custom_fields['store_logo']; ?>" class="img-fluid rounded" alt="<?php echo $term->name; ?> Logo" />
							<?php } else { ?>
								<p class="page-title fs-3 my-0"><?php echo $term->name; ?></p>
							<?php } ?>

							<?php if (!empty($store_custom_fields['store_url']) or !empty($store_custom_fields['store_aff_url'])) { ?>
								<a target="_blank" class="btn btn-primary mt-3 mb-1" rel="nofollow" onclick="cmdShowOffer(event,'<?= $term->slug ?>','#store-<?= $term->term_id ?>','<?= $term->name ?>','store');" href="<?php echo get_bloginfo('url') . '/str/' . $term->term_id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); //$store_custom_fields['store_url'];
																																																							?>">
									<?php _e('Visit Website', 'clipmydeals'); ?>
									<i class="fa fa-angle-right"></i>
								</a>
								<?php
								$cashback_options = cmd_get_cashback_option();
								if (isset($cashback_options['message'][$term->term_id]) and $cashback_options['message'][$term->term_id]) { // cashback?
									$color = get_option('cmdcp_message_color', "#4CA14C");
									echo '<p style="font-size:smaller; font-weight:normal; color:' . ($color) . ';">' . stripslashes($cashback_options['message'][$term->term_id]) . '</p>';
								}
								?>
							<?php } ?>
						</div>
						<div class="col my-1">
							<?php the_archive_description('<div class="archive-description">', '</div>'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

?>
<div class="cmd-taxonomy-stores <?= $layout_options['container'] ?> pt-5">
	<div class="row mx-0">

		<section id="primary" class="store-page taxonomy-page content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
			<main id="main" class="site-main" role="main">

				<?php

				if (empty($store_custom_fields['store_banner'])) {
				?>
					<header class="row page-header">
						<div id="store-info" class="visit-website visit-website-si col-lg-3 col-md-4 col-sm-6 text-center my-1">
							<?php if (!empty($store_custom_fields['store_logo'])) {
								$store_image_url = $store_custom_fields['store_logo'];
							} elseif ($placeholder) {
								$store_image_url = $placeholder;
							} else {
								$store_image_url = get_template_directory_uri() . '/inc/assets/images/random-feature.jpg';
							} ?>
							<img src="<?php echo $store_image_url ?>" class="img-thumbnail img-fluid rounded" alt="<?php echo $term->name; ?> Logo" />
							<?php if (!empty($store_custom_fields['store_url']) or !empty($store_custom_fields['store_aff_url'])) { ?>
								<a target="_blank" class="btn btn-primary my-3" rel="nofollow" onclick="cmdShowOffer(event,'<?= $term->slug ?>','#store-<?= $term->term_id ?>','<?= $term->name ?>','store');" href="<?php echo get_bloginfo('url') . '/str/' . $term->term_id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); //$store_custom_fields['store_url'];
																																																						?>">
									<?php _e('Visit Website', 'clipmydeals'); ?>
									<i class="fa fa-angle-right"></i>
								</a>
								<?php
								$cashback_options = cmd_get_cashback_option();
								if (isset($cashback_options['message'][$term->term_id]) and $cashback_options['message'][$term->term_id]) { // cashback?
									$color = get_option('cmdcp_message_color', "#4CA14C");
									echo '<p style="font-size:smaller; font-weight:normal; color:' . ($color) . ';">' . stripslashes($cashback_options['message'][$term->term_id]) . '</p>';
								}
								?>
							<?php } ?>
						</div>
						<div class="col my-1">
							<div class="alert alert-secondary" id="stores-archive-alert">
								<h1 class="page-title my-0"><?php echo $title; ?></h1>
								<hr />
								<?php the_archive_description('<div class="archive-description">', '</div>'); ?>
							</div>
						</div>
					</header><!-- .page-header -->
				<?php } else { ?>
					<h1 class="page-title my-0"><?php echo $title; ?></h1>
				<?php } ?>
				<hr />

				<?php
				if (get_theme_mod('article_placement', 'bottom') == 'top' and !empty($store_custom_fields['store_intro']))
					echo '<div class="card"><article class="card-body">' . do_shortcode(stripslashes($store_custom_fields['store_intro'])) . '</article></div>';
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

				if (get_theme_mod('article_placement', 'bottom') == 'bottom' and !empty($store_custom_fields['store_intro']))
					echo '<div class="card"><article class="card-body">' . do_shortcode(stripslashes($store_custom_fields['store_intro'])) . '</article></div>';

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
