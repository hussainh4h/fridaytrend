<?php

/**
 * Template part for displaying coupons in list view
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClipMyDeals
 */


/*******************************************************************************
 *
 *  Copyrights 2017 to Present - Sellergize Web Technology Services Pvt. Ltd. - ALL RIGHTS RESERVED
 *
 * All information contained herein is, and remains the
 * property of Sellergize Web Technology Services Pvt. Ltd.
 *
 * The intellectual and technical concepts & code contained herein are proprietary
 * to Sellergize Web Technology Services Pvt. Ltd. (India), and are covered and protected
 * by copyright law. Reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Sellergize Web Technology Services Pvt. Ltd.
 *
 * ******************************************************************************/

$id = get_the_ID();
$store_terms = get_the_terms($id, 'stores');
$store_name = $store_terms ? $store_terms[0]->name : '';
$store_slug = $store_terms ? $store_terms[0]->slug : '';
$store_term_id = $store_terms ? $store_terms[0]->term_id : 0;
$store_custom_fields = cmd_get_taxonomy_options($store_term_id, 'stores');
$store_custom_fields['coupon_class'] = 'active-coupon';

$cashback_options = cmd_get_cashback_option();
$cashback_message = $cashback_options['message'][$store_term_id] ?? '';
$cashback_message_color = get_option('cmdcp_message_color', "#4CA14C");
$cashback_details_page = $cashback_options['details'][$store_term_id] ?? '';
$validity_color = 'success';


if (!empty(get_post_meta($id, 'cmd_valid_till', true)) and current_time('Y-m-d') > get_post_meta($id, 'cmd_valid_till', true)) {
	$store_custom_fields['store_color'] = '#777777';
	$store_custom_fields['coupon_class'] = 'expired-coupon';
	$validity_color = 'danger';
}
?>
<article id="coupon-list-<?= $id ?>" <?php post_class("card cmd-list-layout py-2 rounded-4 {$store_custom_fields['coupon_class']} " . get_post_meta($id, 'cmd_type', true)); ?>>
	<div class="row mx-0">

		<div class="coupon-badge col-md-3 px-2 text-center">
			<div class="cmd-badge-text d-flex align-items-center text-center ps-4 h-100" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color: ' . $store_custom_fields['store_color'] . ' !important;' : '' ?>">
				<?php
				$badge_text = '';
				if (!empty(get_post_meta($id, 'cmd_badge', true))) {
					$badge_text = get_post_meta($id, 'cmd_badge', true);
				} else {
					//$random_badges = array('Hot Offer','Super Offer','Best Offer','Best Deal','Hot Deal','Super Deal','Value for Money','Best Value');
					$random_badges = array(__('Hot Offer', 'clipmydeals'), __('Super Offer', 'clipmydeals'), __('Best Offer', 'clipmydeals'), __('Best Deal', 'clipmydeals'), __('Hot Deal', 'clipmydeals'), __('Super Deal', 'clipmydeals'), __('Value for Money', 'clipmydeals'), __('Best Value', 'clipmydeals'));
					$badge_text = $random_badges[array_rand($random_badges)];
				}
				$badge_font_size = '1.5em';
				if (strlen(html_entity_decode($badge_text)) <= 5) {
					$badge_font_size = '3em';
				} elseif (strlen(html_entity_decode($badge_text)) <= 10) {
					$badge_font_size = '2em';
				}
				?>
				<h3 class="text-white w-100" style="font-size:<?php echo $badge_font_size; ?>; line-height: unset;"><?php echo $badge_text; ?></h3>
			</div>
		</div>


		<div class="coupon-content col-md-9 px-2">

			<div class="rounded-4 p-2 pe-5" style="background-color: <?= !clipmydeals_isDarkPreset() ? clipmydeals_lighter_color($store_custom_fields['store_color']) : '#0000004d' ?>;">

				<div class="row">

					<div class="card-body col-md-8">
						<?php
						if (get_theme_mod('coupon_page', 'yes') == 'no') :
						?>
							<h3 class="card-title mt-0 pb-0"><?php echo get_the_title($id); ?></h3>
						<?php
						else :
						?>
							<h3 class="card-title mt-0 pb-0">
								<a class="coupon-title" href="<?php echo esc_url(get_permalink($id)); ?>" rel="bookmark"><?php echo get_the_title($id); ?> </a>
							</h3>
						<?php
						endif;

						if ($cashback_message) {
							echo '<p class="fw-bold" style="font-size:smaller;color:' . ($cashback_message_color) . ';">' . $cashback_message;
							if ($cashback_details_page) {
								echo ' <a target="_blank" class="small" href="' . get_permalink($cashback_details_page) . '">(' . __('View Details', 'clipmydeals') . ')</a>';
							}
							echo '</p>';
						}
						?>
						<div class="card-text mt-4">
							<?php
							echo get_the_content() ? the_content() : '<p>' . $args['coupon_description'] . '</p>';
							?>
						</div>

					</div>

					<div class="col-md-4 mt-3">

						<div class="row">

							<?php
							if (!empty($store_custom_fields['store_logo'])) {
								if (!is_tax('stores')) {
									echo '<a class="col-12" href="' . get_term_link($store_slug, "stores") . '"><img loading="lazy" src="' . $store_custom_fields['store_logo'] . '" alt="' . $store_name . '" class=" float-end" /></a>';
								} else {
									echo '<img loading="lazy" class="col  float-end" src="' . $store_custom_fields['store_logo'] . '" alt="' . $store_name . '" />';
								}
							}
							?>

							<div class="col-12">
								<?php
								get_template_part('template', 'parts/code', array('view' => 'list')); // BUTTON
								?>
							</div>
						</div>
					</div>

				</div>

				<div class="row">
					<div class="cmd-tax-tags cmd-tax-tags-list col mb-3 small">
						<?php
						$sep = '';
						$term_list = '';
						if (!is_tax('stores')) {
							$term_list = get_the_term_list($id, 'stores', '', ', ', '');
						} // stores
						if ($term_list) $sep = ', ';

						if (get_theme_mod('show_coupon_categories', 'all') != 'no') {
							$terms = get_the_terms($id, 'offer_categories'); // categories
							if ($terms and !is_wp_error($terms)) {
								foreach ($terms as $term) {
									if (get_theme_mod('show_coupon_categories', 'all') == 'all' or $term->parent == 0) {
										$term_list .= $sep . ' <a href="' . get_term_link($term) . '">' . $term->name . '</a>';
										$sep = ',';
									}
								}
							}
						}
						if (get_theme_mod('show_coupon_brands', 'yes') != 'no') {
							$brand_list = get_the_term_list($id, 'brands', '', ', ', '');
							if ($brand_list) {
								$term_list .= $sep . $brand_list; //brands
							}
						}
						if ($term_list) echo '<i class="fa fa-tags"></i> ' . $term_list;

						?>
					</div>

					<?php
					if (get_theme_mod('location_taxonomy', false) and get_theme_mod('show_coupon_locations', 'all') != 'no') {
						$location_html = array();
						$terms = get_the_terms($id, 'locations'); // locations
						if ($terms and !is_wp_error($terms)) {
							foreach ($terms as $term) {
								if (get_theme_mod('location_taxonomy', false) and get_theme_mod('show_coupon_locations', 'all') == 'all' or $term->parent == 0) {
									$location_html[] = ' <a href="' . get_term_link($term) . '">' . $term->name . '</a>';
								}
							}
						}
						if (!empty($location_html)) {
					?>
							<div class="cmd-tax-tags-loc cmd-tax-tags-loc-list col mb-3 small">
								<i class="fa fa-map-marker"></i><?php echo implode(', ', $location_html); ?>
							</div>
					<?php
						}
					}
					?>
				</div>

				<?php if (!empty(get_post_meta($id, 'cmd_valid_till', true))) { ?>
					<div class="badge bg-<?php echo $validity_color; ?> mb-3"><?= (current_time('Y-m-d') <= get_post_meta($id, 'cmd_valid_till', true) ? __('Valid Till', 'clipmydeals') : __('Expired On', 'clipmydeals')) . ' ' . date_i18n(get_option('date_format'), strtotime(get_post_meta($id, 'cmd_valid_till', true))); ?></div>
				<?php } ?>
				<div class="row">
					<?php
					if (
						!empty(get_post_meta($id, 'cmd_verified_on', true))
						or
						(comments_open($id) and get_theme_mod('coupon_page', 'yes') == 'yes')
					) {
					?>
						<div class="card-footer col-md-12 small" style="z-index:2">

							<?php if (!empty(get_post_meta($id, 'cmd_verified_on', true))) { ?>
								<div class="float-start">
									<i class="fa fa-check text-success"></i> <?php echo __('Verified On', 'clipmydeals') . ' ' . date_i18n(get_option('date_format'), strtotime(get_post_meta($id, 'cmd_verified_on', true))); ?>
								</div>
							<?php } ?>

							<?php if (comments_open($id) and get_theme_mod('coupon_page', 'yes') == 'yes') { ?>
								<div class="float-end">
									<a class="card-link" href="<?php echo esc_url(get_permalink($id)) . '#comments'; ?>">
										<i class="fa fa-comment"></i> <?php $comment_count = wp_count_comments($id);
																		echo $comment_count->approved; ?>
									</a>
								</div>
							<?php } ?>

						</div>
					<?php
					}
					?>
				</div>

			</div>
		</div>

	</div> <!-- row ends -->
</article><!-- #post-## -->