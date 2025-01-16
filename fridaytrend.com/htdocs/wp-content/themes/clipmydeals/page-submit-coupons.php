<?php /* Template Name: Submit Coupon */ ?>

<?php

$num1 = mt_rand(2, 10);
$num2 = mt_rand(2, 10);
$num3 = mt_rand(1, 4);
$new_check = hash('md5', (string)($num1 + $num2 - $num3) . wp_salt());

$layout_options = clipmydeals_layout_options();
get_header();
global $store_status;

$coupon = array();

if (isset($_POST['submit'])) {

	$error = null;

	// VALIDATIONS
	if (
		empty($_POST['echeck'])
		or
		hash('md5', filter_var($_POST['echeck'], FILTER_SANITIZE_SPECIAL_CHARS) . wp_salt()) != $_COOKIE['cToken']
		or
		!isset($_POST['submit_coupon_nonce'])
		or
		!wp_verify_nonce($_POST['submit_coupon_nonce'], 'submit_coupon')
	) {
		$error = __('Invalid Captcha', 'clipmydeals');
	} elseif (empty($_POST['submitter_name'])) {
		$error = __('Please enter your name', 'clipmydeals');
	} elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST['submitter_name'])) {
		$error = __('Please enter a valid name', 'clipmydeals');
	} elseif (empty($_POST['submitter_email']) or !filter_var($_POST['submitter_email'], FILTER_VALIDATE_EMAIL)) {
		$error = __('Please enter a valid Email address', 'clipmydeals');
	} elseif (strlen($_POST['title']) < 5) {
		$error = __('Title should be minimum 5 characters', 'clipmydeals');
	} elseif (strlen($_POST['description']) < 15) {
		$error = __('Description should be minimum 5 characters', 'clipmydeals');
	} elseif (!empty($_POST['code']) and strlen($_POST['code']) < 2) {
		$error = __('Code should be minimum 2 characters', 'clipmydeals');
	} elseif (empty($_POST['code']) and $_POST['type'] == 'print') {
		$error = __('Code is required for Printable Vouchers', 'clipmydeals');
	} elseif ($_POST['type'] == 'web' and empty($_POST['url'])) {
		$error = __('Landing Page URL is required for Online Offers', 'clipmydeals');
	} elseif ($_POST['type'] == 'web' and !filter_var($_POST['url'], FILTER_VALIDATE_URL)) {
		$error = __('Invalid Landing Page URL', 'clipmydeals');
	} elseif (!empty($_POST['image_url']) and !filter_var($_POST['image_url'], FILTER_VALIDATE_URL)) {
		$error = __('Invalid Image URL', 'clipmydeals');
	}

	// SANITIZATION
	$coupon['submitter_name'] = filter_var(htmlspecialchars(strip_tags($_POST['submitter_name'])), FILTER_SANITIZE_SPECIAL_CHARS);
	$coupon['submitter_email'] = filter_var($_POST['submitter_email'], FILTER_SANITIZE_EMAIL);
	$coupon['title'] = filter_var(htmlspecialchars(strip_tags($_POST['title'])), FILTER_SANITIZE_SPECIAL_CHARS);
	$coupon['description'] = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
	$coupon['code'] = filter_var(htmlspecialchars(strip_tags($_POST['code'])), FILTER_SANITIZE_SPECIAL_CHARS);
	$coupon['type'] = $_POST['type'] == 'print' ? 'print' : (empty($coupon['code']) ? 'deal' : 'code');
	$coupon['start_date'] = $_POST['start_date'] ? date('Y-m-d', strtotime($_POST['start_date'])) : current_time('Y-m-d');
	$coupon['valid_till'] = $_POST['valid_till'] ? date('Y-m-d', strtotime($_POST['valid_till'])) : '';
	$coupon['url'] = filter_var($_POST['url'], FILTER_SANITIZE_URL);
	$coupon['image_url'] = filter_var($_POST['image_url'], FILTER_SANITIZE_URL);

	// COUPON DATA
	$post_status = 'pending';
	$coupon['notes'] = "SUBMITTED BY:" . PHP_EOL . "Name - " . $coupon['submitter_name'] . PHP_EOL . "Email - " . $coupon['submitter_email'] . PHP_EOL . "IP Address - " . $_SERVER['REMOTE_ADDR'];

	if ($error) {
		$message = '<div class="alert alert-danger"><i class="fas fa-exclamation me-1"></i> ' . $error . '</div>';
	} else {

		// SAVE COUPON
		$coupon_data = array(
			'ID'             => '',
			'post_title'     => $coupon['title'],
			'post_content'   => $coupon['description'],
			'post_status'    => $post_status,
			'post_type'      => 'coupons',
		);
		$coupon_id = wp_insert_post($coupon_data, true);

		// POST META
		update_post_meta($coupon_id, 'cmd_type', $coupon['type']);
		update_post_meta($coupon_id, 'cmd_code', $coupon['code']);
		update_post_meta($coupon_id, 'cmd_url', $coupon['url']);
		if (!empty($coupon['image_url'])) {
			update_post_meta($coupon_id, 'cmd_image_url', $coupon['image_url']);
		}
		update_post_meta($coupon_id, 'cmd_start_date', $coupon['start_date']);
		update_post_meta($coupon_id, 'cmd_valid_till', $coupon['valid_till']);
		update_post_meta($coupon_id, 'cmd_notes', $coupon['notes']);
		update_post_meta($coupon_id, 'cmd_verified_on', current_time('Y-m-d'));
		update_post_meta($coupon_id, 'cmd_display_priority', 0);
		update_post_meta($coupon_id, 'lmd_id', 0);

		wp_set_object_terms($coupon_id, array_map('intval', ($stores ?? array())), 'stores', false);
		wp_set_object_terms($coupon_id, array_map('intval', ($offer_categories ?? array())), 'offer_categories', false);
		wp_set_object_terms($coupon_id, array_map('intval', ($brands ?? array())), 'brands', false);

		if (get_theme_mod('location_taxonomy', false)) {
			wp_set_object_terms($coupon_id, array_map('intval', ($locations ?? array())), 'locations', false);
		}

		// notify admin
		$emailTo = get_option('admin_email');
		$emailHeader[] = "Reply-To: " . $coupon['submitter_name'] . " <" . $coupon['submitter_email'] . ">";
		$emailHeader[] = "Content-type: text/html";
		$emailSubject = sprintf(
			/* translators: 1:Blogname */
			__('[%s] New Coupon Submitted', "clipmydeals"),
			get_bloginfo('name')
		);
		$emailMessage = sprintf(
			/* translators: 1:Name 2:Title 3:Stores */
			__('<p>Hi,</p>
							<p>A new coupon has been submitted on your website by <strong>%1$s</strong>.</p>
							<p>
								<strong>Title:</strong> %%2$s
								<br/>
								<strong>Store:</strong> %3$s
							</p>', 'clipmydeals'),
			$coupon['submitter_name'],
			$coupon['title'],
			implode(', ', wp_get_object_terms($coupon_id, 'stores', array('fields' => 'names')))
		);
		wp_mail($emailTo, $emailSubject, $emailMessage, $emailHeader);

		$message = '<div class="alert alert-success"><i class="fas fa-check me-1"></i> ' . __('Your Coupon has been submitted for approval.', 'clipmydeals') . '</div>';

		// reset form data
		unset($coupon);
		unset($_POST);
	}
	$coupon = array('submitter_name' => '', 'submitter_email' => '', 'title' => '', 'description' => '', 'code' => '', 'type' => '', 'start_date' => '', 'valid_till' => '', 'url' => '', 'image_url' => '', 'notes' => '');
} else {
	$userinfo = wp_get_current_user();
	$coupon = array('submitter_name' => $userinfo->display_name, 'submitter_email' => $userinfo->user_email, 'title' => '', 'description' => '', 'code' => '', 'type' => '', 'start_date' => '', 'valid_till' => '', 'url' => '', 'image_url' => '', 'notes' => '');
}

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-page-submit-options <?= $layout_options['container'] ?> pt-5">
		<div class="row mx-0">
		<?php
	}

		?>

		<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
			<main id="main" class="site-main" role="main">

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
					</header><!-- .entry-header -->
					<?php
					echo $message ?? '';
					?>

					<div class="entry-content">

						<div>
							<?php
							while (have_posts()) : the_post();
								the_content();
							endwhile;
							?>
						</div>

						<form method="POST">

							<div class="card card-primary mt-4">
								<div class="card-header">
									<h4><?= __('Your Details', 'clipmydeals'); ?></h4>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="form-group col-md-6">
											<label for="submitter_name"><?= __("Your Name", 'clipmydeals') ?> <small style="color:red;">*</small></label>
											<input type="text" name="submitter_name" id="submitter_name" class="form-control" value="<?= $coupon['submitter_name'] ?>" required />
										</div>

										<div class="form-group col-md-6">
											<label for="submitter_email"><?= __("Your Email", 'clipmydeals') ?> <small style="color:red;">*</small></label>
											<input type="email" name="submitter_email" id="submitter_email" class="form-control" value="<?= $coupon['submitter_email'] ?>" required />
										</div>
									</div>
								</div>
							</div>

							<div class="card card-primary mt-4">
								<div class="card-header">
									<h4><?= __('Offer Details', 'clipmydeals'); ?></h4>
								</div>
								<div class="card-body">
									<div class="row">

										<div class="my-3 form-group col-12">
											<label for="title"><?= __("Title", 'clipmydeals') ?> <small style="color:red;">*</small></label>
											<input type="text" name="title" id="title" class="form-control" value="<?= $coupon['title'] ?>" minlength="5" required />
										</div>

										<div class="my-3 form-group col-12">
											<label for="description"><?= __("Description", 'clipmydeals') ?> <small style="color:red;">*</small></label>
											<textarea name="description" id="description" class="form-control" minlength="15" required><?= $coupon['description'] ?></textarea>
											<small id="passwordHelpBlock" class="form-text text-muted">
												<?= __("You can use HTML markup here", 'clipmydeals') ?>
											</small>
										</div>

										<div class="my-3 form-group col-md-6 col-lg-3">
											<label for="code"><?= __("Code", 'clipmydeals') ?></label>
											<input type="text" name="code" id="code" class="form-control" value="<?= $coupon['code'] ?>" />
										</div>

										<div class="my-3 form-group col-md-6 col-lg-3">
											<label for="type"><?= __("Offer Type", 'clipmydeals') ?></label>
											<select name="type" class="form-select" onchange="toggleLandingPage(this.value);">
												<option value="web" <?= ($coupon['type'] == 'code' or $coupon['type'] == 'deal') ? 'selected' : '' ?>><?= __("Online", 'clipmydeals') ?></option>
												<option value="print" <?= ($coupon['type'] == 'print') ? 'selected' : '' ?>><?= __("Printable Voucher", 'clipmydeals') ?></option>
											</select>
										</div>

										<div class="my-3 form-group col-md-6 col-lg-3">
											<label for="start_date"><?= __("Start Date", 'clipmydeals') ?></label>
											<input type="date" name="start_date" id="start_date" class="form-control" value="<?= $coupon['start_date'] ?>" />
										</div>

										<div class="my-3 form-group col-md-6 col-lg-3">
											<label for="valid_till"><?= __("Valid Till", 'clipmydeals') ?></label>
											<input type="date" name="valid_till" id="valid_till" class="form-control" value="<?= $coupon['valid_till'] ?>" />
										</div>

										<div class="my-3 form-group col-12" id="landing_page" style="<?= $coupon['type'] == 'print' ? 'display: none;' : 'display: block;'; ?>">
											<label for="url"><?= __("Landing Page URL", 'clipmydeals') ?></label>
											<input type="url" name="url" id="url" class="form-control" value="<?= $coupon['url'] ?>" <?= $coupon['type'] == 'print' ? '' : 'required'; ?> />
										</div>

									</div>

									<div class="row">

										<div class="my-3 form-group col-12 col-md">
											<label for="stores"><?= __("Store", 'clipmydeals') ?> <small style="color:red;">*</small></label>
											<select name="stores[]" class="form-select" size="5" multiple required>
												<?php
												$stores = get_terms(array(
													'taxonomy' => 'stores',
													'hide_empty' => false,
													'orderby' => 'name',
													'order' => 'ASC',
												));
												foreach ($stores as $store) {
													store_taxonomy_status($store->term_id);
													if ($store_status[$store->term_id] == 'inactive') continue;
												?>
													<option value="<?= $store->term_id ?>" <?= (isset($_POST['stores']) and in_array($store->term_id, $_POST['stores'])) ? 'selected' : '' ?>><?= $store->name ?></option>
												<?php
												}
												?>
											</select>
										</div>

										<div class="my-3 form-group col-12 col-md">
											<label for="offer_categories"><?= __("Offer Categories", 'clipmydeals') ?></label>
											<select name="offer_categories[]" class="form-select" size="5" multiple>
												<?php
												$offer_categories = get_terms(array(
													'taxonomy' => 'offer_categories',
													'hide_empty' => false,
													'orderby' => 'name',
													'order' => 'ASC',
												));
												foreach ($offer_categories as $offer_category) {
												?>
													<option value="<?= $offer_category->term_id ?>" <?= (isset($_POST['offer_categories']) and in_array($offer_category->term_id, $_POST['offer_categories'])) ? 'selected' : '' ?>><?= $offer_category->name ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<div class="my-3 form-group col-12 col-md">
											<label for="brand"><?= __("Brands", 'clipmydeals') ?></label>
											<select name="brands[]" class="form-select" size="5" multiple>
												<?php
												$brands = get_terms(array(
													'taxonomy' => 'brands',
													'hide_empty' => false,
													'orderby' => 'name',
													'order' => 'ASC',
												));
												foreach ($brands as $brand) {
												?>
													<option value="<?= $brand->term_id ?>" <?= (isset($_POST['brands']) and in_array($brand->term_id, $_POST['brands'])) ? 'selected' : '' ?>><?= $brand->name ?></option>
												<?php
												}
												?>
											</select>
										</div>

										<?php if (get_theme_mod('location_taxonomy', false)) { ?>
											<div class="my-3 form-group col-12 col-md">
												<label for="locations"><?= __("Locations", 'clipmydeals') ?></label>
												<select name="locations[]" class="form-select" size="5" multiple>
													<?php
													$locations = get_terms(array(
														'taxonomy' => 'locations',
														'hide_empty' => false,
														'orderby' => 'name',
														'order' => 'ASC',
													));
													foreach ($locations as $location) {
													?>
														<option value="<?= $location->term_id ?>" <?= (isset($_POST['locations']) and in_array($location->term_id, $_POST['locations'])) ? 'selected' : '' ?>><?= $location->name ?></option>
													<?php
													}
													?>
												</select>
											</div>
										<?php } ?>

									</div>

									<div class="row">

										<div class="my-3 form-group col-12 col-sm-6">
											<label for="image_url"><?= __("Image/Banner URL", 'clipmydeals') ?></label>
											<input type="url" name="image_url" id="image_url" onchange="showImage(this.value);" class="form-control" value="<?= $coupon['image_url'] ?>" />
										</div>

										<div class="col-12 col-sm-6">
											<img class="img-fluid" id="show_image_url" src="<?= $coupon['image_url'] ?>" />
										</div>

									</div>

									<div class="row">
										<div class="col-12 col-sm-3">
											<label for="image_url"><?= $num1 . ' + ' . $num2 . " - " . $num3 . " = ?" ?></label>
											<input type="number" name="echeck" id="echeck" class="form-control" value="" required />
										</div>

									</div>
								</div>
							</div>

							<?php wp_nonce_field('submit_coupon', 'submit_coupon_nonce'); ?>
							<button type="submit" name="submit" class="btn btn-primary btn-lg my-4 px-5"><?= __("Submit", 'clipmydeals') ?></button>

						</form>

					</div>

					<?php if (get_edit_post_link()) : ?>
						<footer class="entry-footer">
							<?php
							edit_post_link(
								sprintf(
									/* translators: %s: Name of current post */
									esc_html__('Edit %s', 'clipmydeals'),
									the_title('<span class="screen-reader-text">"', '"</span>', false)
								),
								'<span class="edit-link">',
								'</span>'
							);
							?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>

				</article>

			</main><!-- #main -->
		</section><!-- #primary -->

		<?php
		if ($layout_options['sidebar']) {
			get_sidebar();
		}

		if (!get_option('cmd_old_header')) {
		?>
		</div> <!-- row -->
	</div> <!-- container -->

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			setCookie('cToken', '<?= $new_check ?>');
		}, false);

		function showImage(img_url) {
			document.getElementById('show_image_url').src = img_url;
		}

		function toggleLandingPage(offer_type) {
			if (offer_type == 'web') {
				document.getElementById('landing_page').style.display = 'block';
				document.getElementById('url').required = true;
			} else {
				document.getElementById('landing_page').style.display = 'none';
				document.getElementById('url').required = false;
			}
		}
	</script>

<?php
		}

		get_footer();
