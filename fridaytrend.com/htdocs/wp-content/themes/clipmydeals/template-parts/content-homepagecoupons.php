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

$coupon_description = stripslashes(get_theme_mod('coupon_default_description', 'Enjoy big savings with this voucher. Don’t miss out, valid for limited period only.'));

if (get_option($wp_responsive) and (get_theme_mod('hp_coupon_layout', 'default') == 1
or (get_theme_mod('hp_coupon_layout', 'default') == 'default' and get_theme_mod('count_in_row', 2) == 1)
or !get_option($wp_responsive))) { ?>
	<!-- LIST VIEW -->
	<div class="coupon-box coupon-box-list py-4 col-12 d-none d-lg-block">
		<?php get_template_part('template-parts/coupon', 'list',array('coupon_description' => $coupon_description)); ?>
	</div>
	<?php  } else {
		if (
			get_theme_mod('hp_coupon_layout', 'default') == 2
			or (get_theme_mod('hp_coupon_layout', 'default') == 'default' and get_theme_mod('count_in_row', 2) == 2)
		) {
			$grid_view_class = 'col-6';
		} elseif (
			get_theme_mod('hp_coupon_layout', 'default') == 3
			or (get_theme_mod('hp_coupon_layout', 'default') == 'default' and get_theme_mod('count_in_row', 2) == 3)
		) {
			$grid_view_class = 'col-6 col-lg-4 col-md-6';
		} else {
			$grid_view_class = 'col-6 col-lg-3 col-md-4';
		} ?>
		<!-- GRID VIEW -->
		<div class="coupon-box coupon-box-grid pb-4 d-none d-lg-block <?php echo $grid_view_class; ?>">
			<?php
			get_template_part('template-parts/coupon', 'grid',array('coupon_description' => $coupon_description));
			?>
		</div>
	<?php } ?>
<!-- Mobile View -->

<div class="coupon-box coupon-box-grid col-6 col-md-4 pb-4 px-1 px-md-3 d-print-none d-lg-none ">
	<?php get_template_part('template-parts/coupon-grid', 'mobile',array('coupon_description' => $coupon_description)); ?>
</div>