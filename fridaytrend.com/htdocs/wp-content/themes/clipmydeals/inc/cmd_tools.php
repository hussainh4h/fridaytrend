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

function clipmydeals_toolbox() {
	add_submenu_page('edit.php?post_type=coupons', __('Toolbox', 'clipmydeals'), __('Toolbox', 'clipmydeals'), 'edit_other_coupons', 'coupon_tools', 'clipmydeals_toolbox_page');
}
add_action('admin_menu', 'clipmydeals_toolbox');

function clipmydeals_toolbox_page() {
	//Bootstrap CSS
	wp_register_style('bootstrap.min', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css');
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('clipmydeals_styles', get_template_directory_uri() . '/inc/assets/css/clipmydeals_styles.css');
	wp_enqueue_style('clipmydeals_styles');

	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

?>

	<div class="wrap" style="background:#F1F1F1;">

		<h2><?= __('Toolbox', 'clipmydeals'); ?></h2>
		<?= $message ?? ''; // some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much.
		?>

		<script>
			function confirmDelete() {
				var cnf = confirm(<?= __("Are you sure you want to delete all offers from LinkMyDeals?", 'clipmydeals'); ?>);
				if (cnf == true) {
					document.getElementById("deleteOffersForm").submit();
				}
			}

			function confirmSync() {
				var cnf = confirm(<?= __("This will drop current offers and pull everything again. Do you want to proceed?", 'clipmydeals'); ?>);
				if (cnf == true) {
					document.getElementById("syncOffersForm").submit();
				}
			}
		</script>

		<hr />

		<div class="row">

			<div class="col-4">
				<div class="card card-primary p-0">
					<div class="card-header bg-dark text-white">
						<h6 class="mb-0"><?= __('Export', 'clipmydeals'); ?></h6>
					</div>
					<div class="card-body border-top">
						<a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=clipmydeals_export_coupons'), 'clipmydeals', 'clipmydeals_export_coupons_nonce'); ?>" class="btn btn-outline-primary px-3"><?= __('Download All Coupons', 'clipmydeals'); ?></a>
					</div>
				</div>
			</div>

			<div class="col-4">
				<div class="card card-primary p-0">
					<div class="card-header bg-dark text-white">
						<h6 class="mb-0"><?= __('Bulk Import', 'clipmydeals'); ?></h6>
					</div>
					<div class="card-body">
						<form name="bulkUpload" class="" role="form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
							<div class="form-group">
								<input type="file" name="feed" id="feed" accept=".csv" />
							</div>
							<input type="hidden" name="action" value="clipmydeals_bulk_import" />
							<?php wp_nonce_field('clipmydeals', 'clipmydeals_bulk_import_nonce'); ?>
							<button class="btn btn-success px-5" type="submit" name="submit_bulk_import"><?= __('Import File', 'clipmydeals'); ?></button>
						</form>
						<a class="small d-block mt-3" download href="<?= get_template_directory_uri() . '/sample_import.csv' ?>"><?= __('Download Sample File', 'clipmydeals'); ?></a>
					</div>
				</div>
			</div>

			<div class="col-4">
				<div class="card card-primary p-0">
					<div class="card-header bg-dark text-white">
						<h6 class="mb-0"><?= __('Purge', 'clipmydeals'); ?></h6>
					</div>
					<div class="card-body">
						<form name="bulkDelete" class="" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
							<input type="hidden" name="action" value="clipmydeals_delete_expired_coupons" />
							<?php wp_nonce_field('clipmydeals', 'clipmydeals_delete_expired_coupons_nonce'); ?>
							<button class="btn btn-danger" type="submit" name="submit_bulk_delete"><?= __('Delete Expired Coupons', 'clipmydeals'); ?></button>
							<div class="mt-3 ps-0 form-check">
								<input type="checkbox" id="skip_trash" name="skip_trash" value="yes">
								<label class="form-check-label" for="skip_trash">
								<?= __('Skip "Trash" and force delete.', 'clipmydeals'); ?>
								</label>
							</div>
						</form>
						<form name="demoDelete" class="mt-3" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
							<input type="hidden" name="action" value="clipmydeals_delete_demo_import_coupons" />
							<?php wp_nonce_field('clipmydeals', 'clipmydeals_delete_demo_import_coupons_nonce'); ?>
							<button class="btn btn-danger" type="submit" name="submit_demo_delete"><?= __('Delete Demo Import Coupons', 'clipmydeals'); ?></button>
						</form>
					</div>
				</div>
			</div>

		</div>

	<?php

}

if (!function_exists('clipmydeals_bulk_import')) {
	function clipmydeals_bulk_import() {
		if (!wp_verify_nonce($_POST['clipmydeals_bulk_import_nonce'], 'clipmydeals')) {
			$message = '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified.', 'clipmydeals').'</p></div>';
		} else {

			if (!function_exists('wp_handle_upload')) {
				require_once(ABSPATH . 'wp-admin/includes/file.php');
			}

			$uploadedfile = $_FILES['feed'];
			$upload_overrides = array('test_form' => false, 'mimes' => array('csv' => 'text/csv'));
			$movefile = wp_handle_upload($uploadedfile, $upload_overrides);
			if (!$movefile or isset($movefile['error'])) {
				$message .= '<div class="notice notice-error is-dismissible"><p>'.sprintf(__('Error during File Upload : %s', 'clipmydeals') , $movefile['error']) . '</p></div>';
			} else {

				$topheader = NULL;
				$sep = '';
				$delimiter = ',';
				$loop_count = 0;
				$process_count = 0;
				$errors = '';

				if (($handle = fopen($movefile['file'], 'r')) !== FALSE) {

					// SPEED IT UP [ START ]
					wp_defer_term_counting(true);
					global $wpdb;
					$wpdb->query('SET autocommit = 0;');

					while (($row = fgetcsv($handle, 10000, $delimiter)) !== FALSE) {

						if (!$topheader) {
							$topheader = $row;
						} else {

							$loop_count++;

							$coupon = array_combine($topheader, $row);

							$coupon['title'] = wp_strip_all_tags($coupon['title']);
							$coupon['ID'] = intval($coupon['ID']);
							if (empty($coupon['title'])) {
								$errors .= '<br/>'.sprintf(__('Error on Row %s: Empty coupon [title]', 'clipmydeals'),$loop_count);
								continue;
							} else if ($coupon['ID'] != 0 and is_null(get_post($coupon['ID']))) {
								$errors .= '<br/>'.sprintf(__('Error on Row %s: Coupon [ID] does not Exist', 'clipmydeals'),$loop_count);
								continue;
							}

							// INSERT OR UPDATE COUPON
							$post_data = array(
								'ID'             => $coupon['ID'],
								'post_title'     => $coupon['title'],
								'post_content'   => $coupon['description'],
								'post_status'    => 'publish',
								'post_type'      => 'coupons',
								'post_author'    => get_current_user_id()
							);
							$post_id = wp_insert_post($post_data, true);

							if (is_wp_error($post_id)) {
								$errors .= sprintf(__('<br/>Error on Row %s: %s','clipmydeals') ,($loop_count + 1) , $post_id->get_error_message());
							} else {

								$process_count++;

								// ADD/UPDATE TAXONOMIES
								if (!empty($coupon['store'])) {
									wp_set_object_terms($post_id, explode(',', $coupon['store']), 'stores', false);
								}
								if (!empty($coupon['categories'])) {
									wp_set_object_terms($post_id, explode(',', $coupon['categories']), 'offer_categories', false);
								}
								if (!empty($coupon['brands'])) {
									wp_set_object_terms($post_id, explode(',', $coupon['brands']), 'brands', false);
								}
								if (!empty($coupon['locations']) and get_theme_mod('location_taxonomy', false)) {
									wp_set_object_terms($post_id, explode(',', $coupon['locations']), 'locations', false);
								}

								// UPDATE POST META
								update_post_meta($post_id, 'cmd_badge', filter_var($coupon['badge'], FILTER_SANITIZE_STRING));
								update_post_meta($post_id, 'cmd_type', in_array($coupon['type'], array('deal', 'code', 'print')) ? $coupon['type'] : 'deal');
								update_post_meta($post_id, 'cmd_code', $coupon['code']);
								update_post_meta($post_id, 'cmd_url', $coupon['url']);
								update_post_meta($post_id, 'cmd_image_url', $coupon['image_url']);
								update_post_meta($post_id, 'cmd_start_date', !empty($coupon['start_date']) ? date('Y-m-d', strtotime($coupon['start_date'])) : '');
								update_post_meta($post_id, 'cmd_valid_till', !empty($coupon['valid_till']) ? date('Y-m-d', strtotime($coupon['valid_till'])) : '');
								update_post_meta($post_id, 'cmd_verified_on', !empty($coupon['verified_on']) ? date('Y-m-d', strtotime($coupon['verified_on'])) : '');
								update_post_meta($post_id, 'cmd_display_priority', intval($coupon['priority']));
								update_post_meta($post_id, 'cmd_notes', filter_var($coupon['notes'], FILTER_SANITIZE_STRING));
							}
						}
					}

					// SPEED IT UP [ END ]
					wp_defer_term_counting(false);
					$wpdb->query('COMMIT;');
					$wpdb->query('SET autocommit = 1;');
				}

				$message = '<div class="notice notice-success is-dismissible"><p>'.sprintf(__('File uploaded. %s offers processed successfully.','clipmydeals'),$process_count).'</p></div>' .  substr($errors, 0, 2020) . (strlen($errors) > 2020 ? '...' : '');
			}

			wp_delete_file($movefile['file']);
		}

		setcookie('message', $message);
		wp_redirect('edit.php?post_type=coupons&page=coupon_tools');
		exit();
	}
}

if (!function_exists('clipmydeals_export_coupons')) {
	function clipmydeals_export_coupons() {
		if (!wp_verify_nonce($_GET['clipmydeals_export_coupons_nonce'], 'clipmydeals')) {
			$message = '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified.', 'clipmydeals').'</p></div>';
		} else {

			$filename = "coupons_" . current_time("YmdHis") . ".csv";
			$seperator = ",";

			header("Content-Type: text/csv");
			header("Content-Disposition: attachment; filename=" . $filename);
			header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
			header("Pragma: no-cache"); // HTTP 1.0
			header("Expires: 0"); // Proxies
			header("Content-Transfer-Encoding: UTF-8");

			$fp = fopen("php://output", "w");
			fputcsv($fp, array('ID', 'title', 'description', 'badge', 'type', 'code', 'store', 'categories', 'brands', 'locations', 'url', 'image_url', 'start_date', 'valid_till', 'verified_on', 'priority', 'notes'), $seperator);


			$paged = 0;
			while (true) :
				$coupon_args = array(
					'post_type' => 'coupons',
					'orderby' => 'ID',
					'order' => 'ASC',
					'paged' => ++$paged,
					'posts_per_page' => 1000,
				);
				query_posts($coupon_args);
				if (!have_posts()) :
					break;
				endif;
				while (have_posts()) : the_post();
					$id = get_the_ID();
					fputcsv(
						$fp,
						array(
							$id,
							get_the_title(),
							get_the_content(),
							get_post_meta($id, 'cmd_badge', true),
							get_post_meta($id, 'cmd_type', true),
							get_post_meta($id, 'cmd_code', true),
							implode(',', wp_get_object_terms($id, 'stores', array('fields' => 'names'))),
							implode(',', wp_get_object_terms($id, 'offer_categories', array('fields' => 'names'))),
							implode(',', wp_get_object_terms($id, 'brands', array('fields' => 'names'))),
							implode(',', get_theme_mod('location_taxonomy', false) ? wp_get_object_terms($id, 'locations', array('fields' => 'names')) : array()),
							get_post_meta($id, 'cmd_url', true),
							get_post_meta($id, 'cmd_image_url', true),
							get_post_meta($id, 'cmd_start_date', true),
							get_post_meta($id, 'cmd_valid_till', true),
							get_post_meta($id, 'cmd_verified_on', true),
							get_post_meta($id, 'cmd_display_priority', true),
							get_post_meta($id, 'cmd_notes', true)
						),
						$seperator
					);
				endwhile;
				wp_reset_query();
			endwhile;


			fclose($fp);
		}
	}
}

if (!function_exists('clipmydeals_delete_expired_coupons')) {
	function clipmydeals_delete_expired_coupons() {
		if (!wp_verify_nonce($_POST['clipmydeals_delete_expired_coupons_nonce'], 'clipmydeals')) {
			$message = '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified.', 'clipmydeals').'</p></div>';
		} else {

			// Query Expired Coupons
			$expired_query = array(
				'post_type' => 'coupons',
				'posts_per_page' => -1,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'cmd_valid_till',
						'value' => current_time('Y-m-d'),
						'compare' => '<'
					),
					array(
						'key' => 'cmd_valid_till',
						'value' => '',
						'compare' => '!='
					),
				),
			);
			$expired_coupons = query_posts($expired_query);

			if (have_posts()) {
				// SPEED IT UP [ START ]
				wp_defer_term_counting(true);
				global $wpdb;
				$wpdb->query('SET autocommit = 0;');
				// DELETE LOOP
				while (have_posts()) : the_post();
					if (isset($_POST['skip_trash'])) {
						wp_delete_post(get_the_ID());
					} else {
						wp_trash_post(get_the_ID());
					}
				endwhile;
				// SPEED IT UP [ END ]
				wp_defer_term_counting(false);
				$wpdb->query('COMMIT;');
				$wpdb->query('SET autocommit = 1;');
			}

			if (isset($_POST['skip_trash'])) {
				$message = '<div class="notice notice-success is-dismissible"><p>'  . sprintf(__('%s Expired Coupons have been permanently deleted.', 'clipmydeals'),count($expired_coupons)).'</p></div>';
			} else {
				$message = '<div class="notice notice-success is-dismissible"><p>'  . sprintf(__('%s Expired Coupons have been moved to trash.', 'clipmydeals'),count($expired_coupons)).'</p></div>';
			}
		}
		setcookie('message', $message);
		wp_redirect('edit.php?post_type=coupons&page=coupon_tools');
		exit();
	}
}

if (!function_exists('clipmydeals_delete_demo_import_coupons')) {
	function clipmydeals_delete_demo_import_coupons() {
		if (!wp_verify_nonce($_POST['clipmydeals_delete_demo_import_coupons_nonce'], 'clipmydeals')) {
			$message = '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified.', 'clipmydeals').'</p></div>';
		} else {
			global $wpdb;
			$wp_prefix = $wpdb->prefix;

			// Query Demo Import Coupons
			$imported_coupons = $wpdb->get_results("SELECT post_id FROM `" . $wp_prefix . "postmeta` WHERE meta_key = 'clipmydeals_demo_coupons'", ARRAY_A);
			if (!empty($imported_coupons)) {
				foreach ($imported_coupons as $coupon) {
					wp_delete_post($coupon['post_id']);
				}
				$options = $wpdb->get_results("SELECT option_name FROM `" . $wp_prefix . "options` WHERE option_value LIKE '%demo_import%'", ARRAY_A);
				foreach ($options as $option) {
					$term_id = trim($option['option_name'], 'taxonomy_term_');
					$tax = get_option($option['option_name']);
					if (is_array($tax)) {
						$tax = $tax['demo_import'];
						$term_data = get_term_by('ID', $term_id, $tax, ARRAY_A);
						if (is_array($term_data) && $term_data['count'] == 0) {
							wp_delete_term($term_id, $tax);
							delete_option($option['option_name']);
						}
					}
				}
			}
			$message = '<div class="notice notice-success is-dismissible"><p>'.__('Demo Imported Content has been permanently deleted.', 'clipmydeals').'</p></div>';
		}
		setcookie('message', $message);
		wp_redirect('edit.php?post_type=coupons&page=coupon_tools');
		exit();
	}
}

add_action('admin_post_clipmydeals_bulk_import', 'clipmydeals_bulk_import');
add_action('admin_post_clipmydeals_export_coupons', 'clipmydeals_export_coupons');
add_action('admin_post_clipmydeals_delete_expired_coupons', 'clipmydeals_delete_expired_coupons');
add_action('admin_post_clipmydeals_delete_demo_import_coupons', 'clipmydeals_delete_demo_import_coupons');

	?>