<?php

/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package ClipMyDeals
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses clipmydeals_header_style()
 */

global $wp_responsive;

function clipmydeals_custom_header_setup() {
	add_theme_support('custom-header', apply_filters('clipmydeals_custom_header_args', array(
		'default-image'          => '',
		'width'                  => 1950,
		'height'                 => 490,
		'flex-height'            => true,
		'wp-head-callback'       => 'clipmydeals_header_style',
	)));
}
add_action('after_setup_theme', 'clipmydeals_custom_header_setup');

if (!function_exists('clipmydeals_header_style')) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see clipmydeals_custom_header_setup().
	 */
	function clipmydeals_header_style() {
		$header_text_color = get_header_textcolor();

		/*
	 * If no custom options for text are set, let's bail.
	 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
	 */
		if (get_theme_support('custom-header', 'default-text-color') === $header_text_color) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
?>
		<style type="text/css">
			<?php
			// Has the text been hidden?
			if ($header_text_color != '') : // Ignore default
			?>:root {
				--cmd-header-logo-color: #<?= esc_attr($header_text_color); ?>
			}

			<?php endif; ?>
		</style>
<?php
	}
endif;
