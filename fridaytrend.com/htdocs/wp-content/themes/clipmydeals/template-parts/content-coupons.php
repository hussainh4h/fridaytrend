<?php

/**
 * Template part for displaying posts
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

global $wp_responsive;

$coupon_description = get_theme_mod('coupon_default_description', 'Enjoy big savings with this voucher. Don’t miss out, valid for limited period only.');

if (get_option($wp_responsive) && !is_single()) {
	if ((get_theme_mod('count_in_row', 2) == 1)) { ?>
		<!-- LIST VIEW -->
		<div class="coupon-box coupon-box-list py-4 col-12 d-none d-md-block d-print-none">
			<?php get_template_part('template-parts/coupon', 'list', array('coupon_description' => $coupon_description)); ?>
		</div>
			<!-- MOBILE VIEW -->
	<div class="coupon-box coupon-box-grid col-8 mx-auto  pb-4 px-1 px-md-3 d-print-none d-md-none ">
		<?php get_template_part('template-parts/coupon-grid', 'mobile', array('coupon_description' => $coupon_description)); ?>
	</div>
	<?php } else { // GRID VIEW 
		if (get_theme_mod('count_in_row', 2) == 2) {
			$grid_view_class = 'col-6';
			$grid_mobile_class = 'col-6 col-lg-3 col-xl-4';
		} elseif (get_theme_mod('count_in_row', 2) == 3) {
			$grid_view_class = 'col-lg-4 col-md-6';
			$grid_mobile_class = 'col-6 col-md-4 col-lg-3 col-xl-4';
		} else {
			$grid_view_class = 'col-lg-3 col-md-4';
			$grid_mobile_class = 'col-6 col-md-4 col-lg-3 col-xl-4';
		}
	?>
		<div class="coupon-box coupon-box-grid pb-4 <?php echo $grid_view_class; ?> d-none d-lg-block d-print-none">
			<?php get_template_part('template-parts/coupon', 'grid', array('coupon_description' => $coupon_description)); ?>
		</div>
			<!-- MOBILE VIEW -->
	<div class="coupon-box coupon-box-grid <?php echo $grid_mobile_class; ?> pb-4 px-1 px-md-3 d-print-none d-lg-none ">
		<?php get_template_part('template-parts/coupon-grid', 'mobile', array('coupon_description' => $coupon_description)); ?>
	</div>
	<?php } ?>


<?php
} else {
?>
	<div class="d-print-none">
		<?php
		get_template_part('template-parts/single', 'coupon'); ?>
	</div>
<?php

} ?>

<?php if (is_single() and get_post_meta(get_the_ID(), 'cmd_type', true) == 'print') { ?>
	<!-- PRINT VIEW -->
	<div class="print-view d-none d-print-block w-100">

		<?php get_template_part('template-parts/coupon', 'print', array('coupon_description' => $coupon_description)); ?>
	</div>
<?php } ?>