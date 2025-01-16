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

$coupon_description = get_theme_mod('coupon_default_description', 'Enjoy big savings with this voucher. Don’t miss out, valid for limited period only.');
$placeholder = get_theme_mod('default_coupon_image');

$validity_color = 'success';

if (!empty(get_post_meta($id, 'cmd_valid_till', true)) and get_post_meta($id, 'cmd_valid_till', true) < current_time('Y-m-d')) {
	$store_custom_fields['store_color'] = '#999999';
	$store_custom_fields['coupon_class'] = 'expired-coupon';
	$validity_color = 'danger';
}
?>
<!-- Added Custom Shadow in coupon-box-grid and removed shadow from article -->
<a class="h-100 cmd-carousel-layout" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-carousel-<?= $id ?>','<?= $title ?>','show_coupon',null,true);" href="<?= get_bloginfo('url') . '/cpn/' . get_the_ID() . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>">
	<article style="<?= !clipmydeals_isDarkPreset() ? 'background-color:' . clipmydeals_lighter_color($store_custom_fields['store_color']) : '' ?>;" <?php post_class("card  h-100 rounded-4 border-0 {$store_custom_fields['coupon_class']} " . get_post_meta($id, 'cmd_type', true)); ?>>
		<!-- Added Class cmd-grid-image -->
		<?php
		if (has_post_thumbnail($id)) {
			$coupon_image_url = get_the_post_thumbnail_url($id, 'post-thumbnail');
		} elseif (!empty(get_post_meta($id, 'cmd_image_url', true))) {
			$coupon_image_url = get_post_meta($id, 'cmd_image_url', true);
		} elseif ($placeholder) {
			$coupon_image_url = $placeholder;
		} else {
			$coupon_image_url = get_template_directory_uri() . '/inc/assets/images/coupon-placeholder.png';
		}
		?>
		<img loading="lazy" src="<?= $coupon_image_url ?>" alt="<?= $store_name ?> logo" class="card-img-top carousel-img cmd-grid-image rounded-top-4" />

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
			<?php
			if (is_single($id) or get_theme_mod('coupon_page', 'yes') == 'no') :
			?>
				<h3 class="card-title cmd-grid-title  mt-0 pb-0 fs-6"><?php echo	get_the_title($id); ?></h3>
			<?php
			else :
			?>
				<h3 class="card-title cmd-grid-title fs-6 mt-0 pb-0">
					<?php echo get_the_title($id); ?>
				</h3>
			<?php
			endif;
			?>
			<div class="card-text cmd-grid-mobile-description">
				<?php

				echo get_the_content() ? wp_strip_all_tags(get_the_content()) : '<p>' . $coupon_description . '</p>'
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