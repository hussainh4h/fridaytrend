<?php

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

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function lmd_display_main() {
	//Bootstrap CSS
	wp_register_style('bootstrap.min', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css');
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('lmd_css', get_template_directory_uri() . '/inc/assets/css/lmd_style.css');
	wp_enqueue_style('lmd_css');

	set_time_limit(0);

	$action = 'install-plugin';
	$slug = 'linkmydeals';
	$plugin_url = wp_nonce_url(
		add_query_arg(
			array(
				'action' => $action,
				'plugin' => $slug
			),
			admin_url('update.php')
		),
		$action . '_' . $slug
	);
?>

	<div class="wrap" style="background:#F1F1F1;">
		<h2></h2>
		<div class="px-5 pt-2 pb-1">
			<h2 class="display-4" style="font-size:1.9rem;font-weight:500"><?= __('Get Coupon Feeds from 4000+ Online Stores around the world', 'clipmydeals'); ?></h2>
			<a class="btn btn-primary btn-lg my-4" href="<?= esc_url($plugin_url) ?>"><?= __('Install LinkMyDeals Plugin', 'clipmydeals'); ?></a>
			<hr>
			<h2 class="display-4 py-2" style="font-size:1.65rem;font-weight:500"><?= __('Why LinkMyDeals?', 'clipmydeals'); ?></h2>
			<ul style="list-style: disc;letter-spacing:1.5; font-size:1.15rem">
				<li class="ms-4 mb-2"><?= __("All Stores available: Even if we don't already collect coupons for a store, we'll add them to our database just for you. No problem at all.", 'clipmydeals'); ?></li>
				<li class="ms-4 mb-2"><?= __("All Sources: We source all offers from Store Website, Email Newsletters, Social Media Communications, Affiliate Network Coupons & all such official communications from each brand.", 'clipmydeals'); ?></li>
				<li class="ms-4 mb-2"><?= __("Unique Data: Offer Titles & Descriptions are unique for each subscriber", 'clipmydeals'); ?></li>
				<li class="ms-4 mb-2"><?= __("No vague & useless sentences: Our Offer Titles & Descriptions have a pretty standard length, unlike all our competitors who give junk lines.", 'clipmydeals'); ?></li>
				<li class="ms-4 mb-2"><?= __("No outdated offers: When a store does not communicate offer validity, We check it as frequently as twice a day. As soon as the offer expires, we suspend it in our database.", 'clipmydeals'); ?></li>
				<li class="ms-4 mb-2"><?= __("100% Commission to you: Just enter your affiliate IDs for each network in your account. All affiliate links contain your ID, so you get 100% commission. We're only interested in the monthly subscription amount, nothing else.", 'clipmydeals'); ?></li>
				<li class="ms-4 mb-2"><?= __("Smartlinks: Just set your Affiliate Networks in the order you like. And we'll make sure all offers use the best available network from your priority list.", 'clipmydeals'); ?></li>
			</ul>
		</div>

	</div>
<?php
}


function lmd_admin_menu() {
	add_menu_page("LinkMyDeals Coupon Feed ", "LinkMyDeals", 'edit_pages', "linkmydeals", "lmd_display_main", "dashicons-randomize", 9);
}
add_action('admin_menu', 'lmd_admin_menu');
