<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ClipMyDeals
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php wp_head(); ?>
	<?php clipmydeals_metatags(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'clipmydeals'); ?></a>
		<?php if (!is_page_template('blank-page.php') && !is_page_template('blank-page-with-container.php')) {
			$ajax_search = get_theme_mod('ajax_search', 'no');
		?>
			<header id="masthead" class="site-header d-print-none p-0" role="banner">
				<nav class="navbar navbar-expand-lg  <?= get_theme_mod('navbar_type', 'fixed-top') ?>">
					<div class="<?= get_theme_mod('header_container', 'container-xl') ?>">
						<div class="navbar-brand">
							<?php
							if (get_theme_mod('custom_logo')) {
								$custom_logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
							?>
								<a href="<?php echo esc_url(home_url('/')); ?>">
									<img id="site-logo" src="<?php echo ($custom_logo ? $custom_logo[0]:''); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
								</a>
							<?php } elseif (get_theme_mod('clipmydeals_logo')) { ?>
								<a href="<?php echo esc_url(home_url('/')); ?>">
									<img id="site-logo" src="<?php echo esc_attr(get_theme_mod('clipmydeals_logo')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
								</a>
							<?php } else { ?>
								<a class="site-title" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_attr(bloginfo('name')); ?></a>
							<?php } ?>

						</div>
						<div class="d-lg-none" style="max-width:40%;">
							<form class="my-3" action="<?= esc_url(home_url('/')) ?>" method="GET">
								<div id="mobile-search" class="input-group">
									<input type="text" name="s" id="mobile-search-text" value="<?= $_GET['s']??'' ?>" class="form-control" <?= $ajax_search == 'yes' ? 'onkeyup="cmdAjaxSearch(document.getElementById(\'masthead\'), this.value);"' : '' ?> autocomplete="off" placeholder="<?= __("Search", "clipmydeals") ?>" />
									<button id="navbar-search-button-mobile" class="input-group-text btn btn-warning" type="submit"><i class="fa fa-search"></i></button>
								</div>
							</form>
						</div>
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
							<svg type="button" class="d-xl-none" xmlns="http://www.w3.org/2000/svg" width="25" height="17" viewBox="0 0 25 17">
								<g transform="translate(-318 -20)">
									<rect width="25" height="3" rx="1.5" transform="translate(318 34)"></rect>
									<rect width="16" height="3" rx="1.5" transform="translate(327 27)"></rect>
									<rect width="25" height="3" rx="1.5" transform="translate(318 20)"></rect>
								</g>
							</svg>
						</button>

						<?php
						wp_nav_menu(array(
							'theme_location'    => 'primary',
							'container'       => 'div',
							'container_id'    => 'main-nav',
							'container_class' => 'collapse navbar-collapse justify-content-center mt-3 mt-lg-0 pb-4 pb-lg-0',
							'menu_id'         => false,
							'menu_class'      => 'navbar-nav flex-wrap justify-content-center',
							'depth'           => 3,
							'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
							'walker'          => new wp_bootstrap_navwalker(),
						));
						?>

						<form class="mx-2 d-none d-lg-block d-xl-block" method="GET" action="<?php echo esc_url(home_url('/')); ?>">
							<div id="desktop-search" class="input-group">
								<input type="text" id="nav-search-box" name="s" <?= $ajax_search == 'yes' ? 'onkeyup="cmdAjaxSearch(document.getElementById(\'desktop-search\'),this.value);"' : '' ?> autocomplete="off" class="form-control" placeholder="<?php _e('Search', 'clipmydeals'); ?>" aria-label="<?php _e('Search', 'clipmydeals'); ?>">
								<button id="nav-search-button" class="btn btn-warning my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</div>
	</nav>
	</header><!-- #masthead -->

	<?php
			$welcome_type = get_theme_mod('welcome_type', 'video');
			$hp_video_search = get_theme_mod('hp_video_search', true);
			$hp_video_title = get_theme_mod('hp_video_title', 'ClipMyDeals');
			$hp_video_title_color = get_theme_mod('hp_video_title_color', '#ffffff');
			$hp_video_tagline = get_theme_mod('hp_video_tagline', 'To customize the contents on this video and other elements of your site go to Dashboard -> Appearance -> Customize');
			$hp_video_tagline_color = get_theme_mod('hp_video_tagline_color', '#ffffff');
			$hp_video_search = get_theme_mod('hp_video_search', true);
			$hp_video_url = get_theme_mod('hp_video_url', 'http://demo.clipmydeals.com/1/wp-content/uploads/2024/02/pexels-ivan-samkov-7567365-1080p.mp4');
	?>

	<div id="content" class="site-content mt-1 mt-lg-0 mb-5">

		<?php if ((is_front_page() && $welcome_type == 'video')) { ?>
			<div class="v-container">
				<?php if ($hp_video_search or $hp_video_title) { ?>
					<div class="v-overlay text-center py-5 rounded-4">

						<?php if ($hp_video_title) { ?>
							<h1 class="py-0" <?php if ($hp_video_title_color) {
													echo 'style="color:' . $hp_video_title_color . '"';
												} ?>> <?php echo stripslashes($hp_video_title); ?> </h1>
						<?php } ?>

						<?php if ($hp_video_tagline) { ?>
							<p <?php if ($hp_video_tagline_color) {
									echo 'style="color:' . $hp_video_tagline_color . '"';
								} ?> class="mb-3"> <?php echo stripslashes($hp_video_tagline); ?> </p>
						<?php } ?>

						<?php
						if ($hp_video_search) {
							get_search_form();
						}
						?>

					</div>
				<?php } ?>
				<video id="hp-video" playsinline autoplay muted loop src="<?php echo $hp_video_url; ?>" />
			</div>
		<?php } ?>

		<?php if ((is_front_page() && $welcome_type == 'slides')) {
				$slide_data = get_theme_mod('slide');
				$slides = null;
				$indicators = null;
				$active = 'active';
				for ($i = 1; $i <= 10; $i++) {
					if (!empty($slide_data[$i][0])) {
						$indicators .= '<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . ($i - 1) . '" class="' . $active . '"></li>';
						$slides .= 	'<div class="carousel-item ' . $active . '">' .
							'<a class="col-12 p-0" href="' . $slide_data[$i][1] . '" target="_blank">' .
							wp_get_attachment_image($slide_data[$i][0], 'full', false, array("class" => "d-block w-100")) .
							'</a>' .
							'</div>';
						$active = '';
					}
				} ?>

			<?php if (!empty($slides)) { ?>
				<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
					<ol class="d-none d-lg-flex carousel-indicators">
						<?= $indicators ?>
					</ol>
					<div class="carousel-inner">
						<?= $slides ?>
					</div>
					<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			<?php } else { ?>
				<p class="alert alert-danger">To add slides, please go to Dashboard > Appearance > Customize > Welcome Slides</p>
			<?php } ?>
		<?php } ?>

		<?php if ((is_front_page() && $welcome_type == 'multi_slides')) {
				$slide_data = get_theme_mod('multi_slide');
				$slides = null;
				$indicators = null;
				$active = 'active';
				$multi_images_per_slide = get_theme_mod('multi_images_per_slide', 3);
				for ($i = 1; $i <= 12; $i += $multi_images_per_slide) {
					if (!empty($slide_data[$i][0])) {
						$indicators .= '<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . (($i - 1) / $multi_images_per_slide) . '" class="' . $active . '"></li>';
						$slides .= 	'<div class="carousel-item ' . $active . '">' .
							'<div class="row p-0 m-0">' .
							'<a class="my-auto col-' . (12 / $multi_images_per_slide) . ' p-0" href="' . (!empty($slide_data[$i][1]) ? $slide_data[$i][1] : '') . '" target="_blank">' .
							wp_get_attachment_image($slide_data[$i][0], 'full', false, array("class" => "d-block w-100")) .
							'</a>' .
							'<a class="my-auto col-' . (12 / $multi_images_per_slide) . ' p-0" href="' . (!empty($slide_data[$i + 1][1]) ? $slide_data[$i + 1][1] : '') . '" target="_blank">' .
							wp_get_attachment_image($slide_data[$i + 1][0], 'full', false, array("class" => "d-block w-100")) .
							'</a>' . ($multi_images_per_slide == 3 ?
								'<a class="my-auto col-4 p-0" href="' . (!empty($slide_data[$i + 2][1]) ? $slide_data[$i + 2][1] : '') . '" target="_blank">' .
								wp_get_attachment_image($slide_data[$i + 2][0], 'full', false, array("class" => "d-block w-100")) .
								'</a>' : '') .
							'</div>' .
							'</div>';
						$active = '';
					}
				} ?>

			<?php if (!empty($slides)) { ?>
				<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
					<ol class="d-none d-lg-flex carousel-indicators">
						<?= $indicators ?>
					</ol>
					<div class="carousel-inner">
						<?= $slides ?>
					</div>
					<a style="width:10%;" class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a style="width:10%;" class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			<?php } else { ?>
				<p class="alert alert-danger">To add slides, please go to Dashboard > Appearance > Customize > Welcome Multi Slides</p>
			<?php } ?>
		<?php } ?>

		<?php if ((is_front_page() && $welcome_type == 'banner')) {
				$header_banner_search = get_theme_mod('header_banner_search', true);
				$header_banner_title_setting = get_theme_mod('header_banner_title_setting', 'ClipMyDeals');
				$header_banner_title_color = get_theme_mod('header_banner_title_color', '#fff');
				$header_banner_tagline_setting = get_theme_mod('header_banner_tagline_setting', 'To customize the contents of this header banner and other elements of your site go to Dashboard > Appearance > Customize', 'clipmydeals');
				$header_banner_tagline_color = get_theme_mod('header_banner_tagline_color', '#fff');
				$header_background_style = get_header_image() ? 'background-image: url(\'' . get_header_image() . '\'); background-position: center;' : 'background-image: linear-gradient(to right, #2a3354 0%, #4e95c7 100%);'; ?>

			<div class="d-flex align-items-center" id="page-sub-header" style="min-height:min(50vh,50vw);<?php echo $header_background_style; ?>">
				<div class="container">
					<?php if ($header_banner_title_setting or $header_banner_search) { ?>
						<div class="banner-content py-5 rounded-4">
							<h1 <?php if ($header_banner_title_color) {
									echo 'style="color:' . $header_banner_title_color . '"';
								} ?>> <?php echo stripslashes($header_banner_title_setting); ?> </h1>
							<p <?php if ($header_banner_tagline_color) {
									echo 'style="color:' . $header_banner_tagline_color . '"';
								} ?> class="mb-3"> <?php echo stripslashes($header_banner_tagline_setting); ?> </p>
							<?php if ($header_banner_search) {
								get_search_form();
							} ?>
						</div>
						<a href="#content" class="page-scroller"><i class="fa fa-fw fa-angle-down"></i></a>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<?php
			if (get_option('cmd_old_header')) {

				if (get_query_var('taxonomy') == 'stores') {
					$term = get_term_by('slug', get_query_var('term'), 'stores');
					$store_custom_fields = cmd_get_taxonomy_options($term->term_id, 'stores');
					if (!empty($store_custom_fields['store_banner'])) {
		?>
					<div class="row bg-light mx-0" style="border-bottom: 1px solid #ababab;">
						<div class="visit-website visit-website-oh col-md-4 col-lg-3 text-center">
							<img class="img-fluid img-thumbnail mt-3 mb-1" alt="<?php echo $term->name; ?> Logo" src="<?php echo $store_custom_fields['store_logo']; ?>" />
							<?php the_archive_description('<div class="archive-description my-1">', '</div>'); ?>
							<?php if (!empty($store_custom_fields['store_url']) or !empty($store_custom_fields['store_aff_url'])) { ?>
								<a target="_blank" class="btn btn-secondary my-1" onclick="cmdShowOffer(event,' <?= $term->slug ?>','#store-<?= $term->term_id ?>','<?= $term->name ?>','store');" href="<?php echo get_bloginfo('url') . '/str/' . $term->term_id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); //$store_custom_fields['store_url'];
																																																			?>">
									<?php _e('Visit Website', 'clipmydeals'); ?>
									<i class="fa fa-angle-right"></i>
								</a>
								<?php
								$cashback_options = cmd_get_cashback_option();
								if (isset($cashback_options['message'][$term->term_id]) and $cashback_options['message'][$term->term_id]) { // cashback?
									$color = get_option('cmdcp_message_color', "#4CA14C");
									echo '<p style="font-size:smaller; font-weight:normal; color:' . ($color) . ';">' . stripslashes($cashback_options['message'][$term->term_id]) . '</p>';
								}
								?>
							<?php } ?>
						</div>
						<div class="d-none d-md-block col-md-8 col-lg-9 px-0">
							<img class="img-fluid" style="width:100%;" alt="<?php echo $term->name; ?> Banner" src="<?php echo $store_custom_fields['store_banner']; ?>" />
						</div>
					</div>
			<?php
					}
				}
				$layout_options = clipmydeals_layout_options();
			?>
			<div class="cmd-header <?= $layout_options['container'] ?> pt-5">
				<div class="row">

				<?php
			}
				?>

			<?php } ?>