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
$coupon_class = current_time('Y-m-d') > get_post_meta($id, 'cmd_valid_till', true) ? 'expired-coupon' : 'active-coupon';
?>

<article id="coupon-print-<?= $id ?>" <?php post_class("card print-layout $coupon_class " . get_post_meta($id, 'cmd_type', true)); ?>>
	<div class="card-header" style="background:unset;">
		<div class="row">
			<div class="col">
				<h3 class="card-title mt-0 pb-0"><?php echo	get_the_title($id); ?></h3>
			</div>
			<div class="col-2">
				<img class="img-responsive" src="<?php echo $store_custom_fields['store_logo']; ?>" />
			</div>
		</div>
	</div>

	<div class="card-body" style="z-index:2;">
		<div class="text-center">
			<?php if (!empty(get_post_meta($id, 'cmd_code', true))) { ?>
				<div class="barcode pb-2 text-center" style="width:30%; margin:auto;">
					<img class="img-fluid w-100" src="<?php echo get_template_directory_uri() . '/inc/assets/barcode.php?text=' . urlencode(get_post_meta($id, 'cmd_code', true)); ?>" />
					<div><?php echo get_post_meta($id, 'cmd_code', true); ?></div>
				</div>
			<?php } ?>
		</div>

		<div class="card-text mt-4"><?php the_content(); ?></div>
		<?php if (!empty(get_post_meta($id, 'cmd_valid_till', true))) { ?>
			<div class="badge bg-default mb-3"><?= (current_time('Y-m-d') <= get_post_meta($id, 'cmd_valid_till', true) ? __('Valid Till', 'clipmydeals') : __('Expired On', 'clipmydeals')) . ' ' . date_i18n(get_option('date_format'), strtotime(get_post_meta($id, 'cmd_valid_till', true))); ?></div>
		<?php } ?>

	</div>

</article><!-- #post-## -->