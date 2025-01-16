<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ClipMyDeals
 */

?>
</div><!-- #page -->

<?php if (!is_page_template('blank-page.php') && !is_page_template('blank-page-with-container.php')) : ?>

	<?php if (get_option('cmd_old_header')) { ?>
		</div><!-- .row -->
		</div><!-- .container -->
	<?php } ?>

	</div><!-- #content -->
	<?php get_template_part('footer-widget'); ?>
	<?php if ((get_theme_mod('display_get_android', 'on') && get_theme_mod('android_bundle_name')) || (get_theme_mod('display_get_ios', 'on') && get_theme_mod('ios_bundle_name'))) { ?>
		<div id="footer-get-now" class="row p-0 m-0 d-print-none">
			<div class="offset-md-6 col-md-6 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3 d-flex justify-content-end my-2 me-0 pe-0">
				<?php if (get_theme_mod('display_get_android', 'on') && get_theme_mod('android_bundle_name')) { ?>
					<a class="col-6" target="_blank" href="https://play.google.com/store/apps/details?id=<?= get_theme_mod('android_bundle_name') ?>"><img class="" src="<?= get_template_directory_uri() ?>/inc/assets/images/GooglePlay.png"></a>
				<?php } ?>
				<?php if (get_theme_mod('display_get_ios', 'on') && get_theme_mod('ios_bundle_name')) { ?>
					<a class="col-6" target="_blank" href="https://apps.apple.com/app/<?= get_theme_mod('ios_bundle_name') ?>"><img class="" src="<?= get_template_directory_uri() ?>/inc/assets/images/AppStore.png"></a>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	<footer id="colophon" class="site-footer bg-dark text-light d-print-none" role="contentinfo">
		<div class="container-fluid pb-4 my-4">
			<div class="site-info row">
				<div class="col-12 col-md-6 text-center footer-text-left"><?= get_theme_mod('footer_text_left', '&copy ' . current_time('Y') . " " . '<a href="' . home_url() . '">' . get_bloginfo('name') . '</a>');  ?></div>
				<div class="col-12 col-md-6 text-center footer-text-right"><?= get_theme_mod('footer_text_right', 'ClipMyDeals - WordPress Affiliate Theme'); ?></div>
			</div><!-- close .site-info -->
		</div>
	</footer><!-- #colophon -->
<?php endif; ?>

<?php wp_footer(); ?>
</body>

</html>