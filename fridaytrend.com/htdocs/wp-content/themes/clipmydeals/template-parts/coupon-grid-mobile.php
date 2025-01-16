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
<a onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-carousel-<?= $id ?>','<?= $title ?>','show_coupon',null,true);" href="<?= get_bloginfo('url') . '/cpn/' . get_the_ID() . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>">
	<article style="<?= !clipmydeals_isDarkPreset() ? 'background-color:' . clipmydeals_lighter_color($store_custom_fields['store_color']) : '' ?>;" id="coupon-grid-<?= $id ?>" <?php post_class("card grid-layout cmd-grid-mobile-layout h-100 rounded-4 {$store_custom_fields['coupon_class']} " . get_post_meta($id, 'cmd_type', true)); ?>>
		<!-- Added Class cmd-grid-image -->
		<?php
		if (has_post_thumbnail($id)) {
			$coupon_image_url = get_the_post_thumbnail_url($id, 'post-thumbnail');
		} elseif (!empty(get_post_meta($id, 'cmd_image_url', true))) {
			$coupon_image_url = get_post_meta($id, 'cmd_image_url', true);
		}elseif($placeholder){
			$coupon_image_url = $placeholder;
		} else {
			$coupon_image_url = get_template_directory_uri() . '/inc/assets/images/coupon-placeholder.png';
		}
		?>
		<img loading="lazy" src="<?= $coupon_image_url ?>" alt="<?= $store_name ?> logo" class="card-img-top cmd-grid-image rounded-top-4" />
		<!-- Classes for placing badge at top left with clipped sides -->
		<div class="card-img-overlay p-0">
			<span class="badge float-end cmd-ribbon-badge px-3 rounded-0 w-fit mx-auto" style="transform:translate(0.25em,-0.75em); <?php if (!empty($store_custom_fields['store_color'])) {
																																		echo 'background-color:' . $store_custom_fields['store_color'] . ';';
																																	} ?>"><?php echo $badge_text; ?></span>
		</div>

		<!-- Added Store Logo Image -->
		<?php
		if (empty(!$store_custom_fields['store_logo'])) {
			echo '<img loading="lazy" src="' . $store_custom_fields['store_logo'] . '" alt="' . $store_name . ' logo" style="border:2px solid ' . $store_custom_fields['store_color'] . '" class="card-img-top cmd-grid-store-logo mx-auto rounded-pill " />';
		} else {
		?>
			<p class="mx-auto mb-0 cmd-grid-store-name rounded-pill bg-primary px-3 py-1"><?= $store_name ?></p>
		<?php
		}
		?>
		<!-- Removed Border -->
		<div class="card-body pt-0 pb-2" style="z-index:2;">
			<h3 class="card-title cmd-grid-title fs-6 mt-0 pb-0"><?php echo get_the_title($id); ?></h3>
			<div class="card-text cmd-grid-mobile-description">
			<?php
			echo get_the_content() ? wp_strip_all_tags(get_the_content()) : '<p>' . $args['coupon_description'] . '</p>';
			?>
			</div>

		</div>

		<?php
		if (
			!empty(get_post_meta($id, 'cmd_verified_on', true))
			or
			(comments_open($id) and get_theme_mod('coupon_page', 'yes') == 'yes')
		) {
		?>
			<!-- <div class="card-footer small border-0" style="z-index:2">

		</div> -->
		<?php } ?>
	</article><!-- #post-## -->
</a>