<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ClipMyDeals
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

// Content order is already set to order-md-1 in all files
// and when sidebar_positon is (0) - order-md-0, sidebar is displayed 1st ie. left
// and when sidebar_positon is (1) - order-md-1, sidebar is displayed 2nd ie. right
?>

<aside id="secondary" class="order-md-<?= get_theme_mod('sidebar_position', '1') ?> d-print-none widget-area col-sm-12 col-lg-3" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
