<?php

/**
 * Template part for displaying coupons in grid view
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

$placeholder = get_theme_mod('default_coupon_image');


$validity_color = 'success';

if (!empty(get_post_meta($id, 'cmd_valid_till', true)) and get_post_meta($id, 'cmd_valid_till', true) < current_time('Y-m-d')) {
	$store_custom_fields['store_color'] = '#999999';
	$store_custom_fields['coupon_class'] = 'expired-coupon';
	$validity_color = 'danger';
}

$badge_text = '';
if (!empty(get_post_meta($id, 'cmd_badge', true))) {
	$badge_text = get_post_meta($id, 'cmd_badge', true);
} else {
	//$random_badges = array('Hot Offer','Super Offer','Best Offer','Best Deal','Hot Deal','Super Deal','Value for Money','Best Value');
	$random_badges = array(__('Hot Offer', 'clipmydeals'), __('Super Offer', 'clipmydeals'), __('Best Offer', 'clipmydeals'), __('Best Deal', 'clipmydeals'), __('Hot Deal', 'clipmydeals'), __('Super Deal', 'clipmydeals'), __('Value for Money', 'clipmydeals'), __('Best Value', 'clipmydeals'));
	$badge_text = $random_badges[array_rand($random_badges)];
}

?>
<!-- Added Custom Shadow in coupon-box-grid and removed shadow from article -->
<article id="coupon-grid-<?= $id ?>" style="<?= !clipmydeals_isDarkPreset() ? 'background-color:' . clipmydeals_lighter_color($store_custom_fields['store_color']) : '' ?>;" <?php post_class("card grid-layout cmd-grid-layout rounded-4 {$store_custom_fields['coupon_class']} " . get_post_meta($id, 'cmd_type', true)); ?>>
	<?php
	if (has_post_thumbnail($id)) {
		$coupon_image_url = get_the_post_thumbnail_url($id, 'post-thumbnail');
	} elseif (!empty(get_post_meta($id, 'cmd_image_url', true))) {
		$coupon_image_url = get_post_meta($id, 'cmd_image_url', true);
	}
	elseif($placeholder){
		$coupon_image_url = $placeholder;
	}  else {
		$coupon_image_url = get_template_directory_uri() . '/inc/assets/images/coupon-placeholder.png';
	}
	?>
	<img loading="lazy" src="<?= $coupon_image_url ?>" alt="<?= $store_name ?> logo" class="card-img-top cmd-grid-image rounded-top-4" />
	<div class="card-img-overlay p-0">
		<span class="badge float-end cmd-ribbon-badge px-3 rounded-0 w-fit mx-auto" style="transform:translate(0.75em,-0.75em); <?php if (!empty($store_custom_fields['store_color'])) {
																																	echo 'background-color:' . $store_custom_fields['store_color'] . ';';
																																} ?>"><?php echo $badge_text; ?></span>
	</div>
	<?php
	if (empty(!$store_custom_fields['store_logo'])) {
		echo '<img loading="lazy" src="' . $store_custom_fields['store_logo'] . '" alt="' . $store_name . ' logo" style="border:2px solid ' . $store_custom_fields['store_color'] . '" class="card-img-top cmd-grid-store-logo mx-auto rounded-pill" />';
	} elseif ($store_name) {
	?>
		<p class="mx-auto mb-0 cmd-grid-store-name rounded-pill bg-primary px-3 py-1"><?= $store_name ?></p>
	<?php
	}
	?>
	<!-- Removed Border -->
	<div class="card-body text-center" style="z-index:2;">
		<?php
		if (is_single($id) or get_theme_mod('coupon_page', 'yes') == 'no') :
		?>
			<h3 class="card-title  mt-0 pb-0"><?php echo	get_the_title($id); ?></h3>
		<?php
		else :
		?>
			<h3 class="card-title  mt-0 pb-0"><a href="<?php echo esc_url(get_permalink($id)); ?>" rel="bookmark"><?php echo get_the_title($id); ?></a></h3>
		<?php
		endif;

		if ($cashback_message) {
			echo '<p class="cashback-msg fw-bold" style="font-size:smaller;color:' . ($cashback_message_color) . ';">' . $cashback_message;
			if ($cashback_details_page) {
				echo ' <a target="_blank" class="small" href="' . get_permalink($cashback_details_page) . '">(' . __('View Details', 'clipmydeals') . ')</a>';
			}
			echo '</p>';
		}
		?>
		<div class="mx-2">
			<?php
			// BUTTON
			get_template_part('template', 'parts/code', array('view' => 'grid'));
			?>
		</div>
		<div class="card-text cmd-grid-coupon-description mt-4">
			<?php
			echo get_the_content() ? the_content() : '<p>' . $args['coupon_description'] . '</p>';
			?>
		</div>
		<?php if (!empty(get_post_meta($id, 'cmd_valid_till', true))) { ?>
			<div class="badge bg-<?php echo $validity_color; ?> mb-3"><?= (current_time('Y-m-d') <= get_post_meta($id, 'cmd_valid_till', true) ? __('Valid Till', 'clipmydeals') : __('Expired On', 'clipmydeals')) . ' ' . date_i18n(get_option('date_format'), strtotime(get_post_meta($id, 'cmd_valid_till', true))); ?></div>
		<?php } ?>

		<div class="cmd-tax-tags cmd-tax-tags-grid small mt-0 mb-1">
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
				<div class="cmd-tax-tags-loc cmd-tax-tags-loc-grid small mt-0 mb-1">
					<i class="fa fa-map-marker"></i><?php echo implode(', ', $location_html); ?>
				</div>
		<?php
			}
		}
		?>

	</div>

	<?php
	if (
		!empty(get_post_meta($id, 'cmd_verified_on', true))
		or
		(comments_open($id) and get_theme_mod('coupon_page', 'yes') == 'yes')
	) {
	?>
		<div class="card-footer small border-0 row m-0"+0 style="z-index:2">

			<?php if (!empty(get_post_meta($id, 'cmd_verified_on', true))) { ?>
				<div class="float-start col-lg-9 pe-1">
					<i class="fa fa-check text-success"></i> <?php echo __('Verified On', 'clipmydeals') . ' ' . date_i18n(get_option('date_format'), strtotime(get_post_meta($id, 'cmd_verified_on', true))); ?>
				</div>
			<?php } ?>

			<?php if (comments_open($id) and get_theme_mod('coupon_page', 'yes') == 'yes') { ?>
				<div class="float-end col-lg-3 text-end ps-1">
					<a class="card-link" href="<?php echo esc_url(get_permalink()) . '#comments'; ?>">
						<i class="fa fa-comment"></i> <?php $comment_count = wp_count_comments($id);
														echo $comment_count->approved; ?>
					</a>
				</div>
			<?php } ?>

		</div>
	<?php } ?>
</article><!-- #post-## -->