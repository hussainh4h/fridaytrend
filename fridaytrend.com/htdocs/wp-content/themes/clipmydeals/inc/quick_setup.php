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

function clipmydeals_quick_setup_page() {

	//Bootstrap CSS
	wp_register_style('bootstrap.min', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css');
	wp_enqueue_style('bootstrap.min');
	//Bootstrap JS
	wp_register_script('bootstrap.min', get_template_directory_uri() . '/inc/assets/js/bootstrap.min.js', array('jquery'));
	wp_enqueue_script('bootstrap.min');

	//Custom css
	wp_register_style('clipmydeals_css', get_template_directory_uri() . '/inc/assets/css/clipmydeals_styles.css');
	wp_enqueue_style('clipmydeals_css');

	$presets = array(
		'Default' 	=> 	array('#5283c1',	'#6c757d',	'#f1f1f1'),		'Modern'	=>	array('#746093',	'#A991D4',	'#fff'),
		'Ocean'		=>	array('#398ea0',	'#95a5a6',	'#ffffff'),		'Tomato'	=>	array('#dc2b25',	'#aaa',		'#fff'),
		'Cerulean'	=>	array('#2FA4E7',	'#e9ecef',	'#fff'),		'ClipKaro'	=>	array('#D9230F',	'#fff',		'#fcfcfc'),
		'Cosmo'		=>	array('#2780E3',	'#373a3c',	'#fff'),
		'Cyborg'	=>	array('#2A9FD6',	'#555', 	'#060606'),		'Darkly'	=>	array('#375a7f',	'#444',		'#222'),
		'Flatly'	=>	array('#2C3E50',	'#95a5a6',	'#fff'),		'Journal'	=>	array('#EB6864',	'#aaa',		'#fff'),
		'Litera'	=>	array('#4582EC',	'#adb5bd',	'#fff'),		'Lumen'		=>	array('#158CBA',	'#f0f0f0',	'#fff'),
		'Lux'		=>	array('#1a1a1a',	'#fff',		'#fff'),		'Materia'	=>	array('#2196F3',	'#fff',		'#fff'),
		'Minty'		=>	array('#78c2ad',	'#F3969A', 	'#fff'),		'Pulse'		=>	array('#593196',	'#A991D4', 	'#fff'),
		'Sandstone'	=>	array('#325D88',	'#8E8C84',	'#fff'),		'Simplex'	=>	array('#D9230F',	'#fff',		'#fcfcfc'),
		'Sketchy'	=>	array('#333',		'#555',		'#fff'),		'Slate'		=>	array('#3a3f44',	'#7A8288', 	'#272b30'),
		'Solar'		=>	array('#B58900',	'#839496',	'#002B36'),		'Spacelab'	=>	array('#446E9B',	'#999',		'#fff'),
		'Superhero'	=>	array('#df691a',	'#4d5e6c',	'#2B3E50'),		'United'	=>	array('#e95420',	'#AEA79F',	'#fff'),
		'Yeti'		=>	array('#008cba',	'#eee',		'#fff')
	);

	$term_meta = array_merge(array('location_taxonomy' => '', 'count_in_row' => '', 'welcome_type' => '', 'theme_option_setting' => ''), get_option("theme_mods_clipmydeals", array()));;
	if (empty($term_meta['location_taxonomy']))     $term_meta['location_taxonomy'] = '0';
	if (empty($term_meta['count_in_row']))          $term_meta['count_in_row']      = '2';
	if (empty($term_meta['welcome_type']))          $term_meta['welcome_type']      = 'video';
	if (empty($term_meta['theme_option_setting']))  $term_meta['theme_option_setting'] = 'default';

	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

?>

	<script type="text/javascript">
		function cmdSaveQuicksettings() {

			var btn = document.getElementById("quick_setup_btn");
			btn.innerHTML = "<?= sprintf(__("Please Wait %s", 'clipmydeals'),"<span class='dashicons dashicons-clock'></span>") ?>";
			btn.disabled = true;

			var form = new FormData();
			form.append('action', 'cmd_quick_setup');
			form.append('location_taxonomy', document.querySelector("#location_taxonomy").checked ? 'on' : 'off');
			form.append('count_in_row', document.querySelector("#count_in_row").value);
			form.append('welcome_type', document.querySelector("#welcome_type").value);
			form.append('theme_option_setting', document.querySelector("input[name='theme_option_setting']:checked").value);
			form.append('quick_setup_nonce', document.querySelector('#quick_setup_nonce').value);

			fetch('<?= admin_url('admin-ajax.php') ?>', {
					method: 'POST',
					body: form
				})
				.then((res) => res.text())
				.then((response) => {
					if (response == 'success') document.querySelector("#quickUpload").submit();
					else document.querySelector("#nonce_verify").innerHTML = response; //Nonce not verified...
				});
		}

		function toggleMenuBox(event) {
			var menu = document.querySelector("#menu")
			menu.disabled = !event.checked;
			menu.checked = menu.checked && event.checked;
		}
	</script>

	<div class="wrap" style="background:#F1F1F1;">
		<h1><?= __('ClipMyDeals', 'clipmydeals'); ?></h1>
		<!-- some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much.   -->
		<?= $message ?? ''; ?>
		<div id="nonce_verify"></div> <!-- Nonce not verified -->
		<hr />
		<?php if (wp_get_theme() != 'ClipMyDeals') { ?>
			<div class="notice notice-warning">
				<p><?= __('Quick Setup is not available in Child Theme.', 'clipmydeals'); ?></p>
			</div>
		<?php } else { ?>
			<p><?= __('<div class="alert alert-warning">These are just basic settings for quick start. For more customization options you should go to <strong>Appearance</strong> > <a href="customize.php"><strong>Customize</strong></a></div>', "clipmydeals") ?></p>

			<div class="row mb-5 px-3">
				<div class="card p-0 mt-0 col-12" style="background-color: #f1f1f1;">
					<div class="card-header">
						<h5><?= __('Quick Setup', 'clipmydeals'); ?></h5>
					</div>
					<div class="card-body">

						<form id="quickUpload" name="quickUpload" role="form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">

							<table class="table table-borderless m-0 cmd-quick-setup-table" style="background-color:#f1f1f1; display:block; max-width:90vw">
								<tbody id="form-table">

									<tr class="row" style="display:block; max-width:88vw">
										<td class="col-5"><label for="count_in_row"><?= __("Number of Coupons on single row", "clipmydeals"); ?></label></td>
										<td class="col-7">
											<div class="form-check">
												<select id="count_in_row" name='count_in_row' class="form-control">
													<option <?php if ($term_meta['count_in_row'] == '1') echo 'selected'; ?> value=1><?= __("List (1 per row)", "clipmydeals"); ?></option>
													<option <?php if ($term_meta['count_in_row'] == '2') echo 'selected'; ?> value=2><?= __("Grid (2 in a row)", "clipmydeals"); ?></option>
													<option <?php if ($term_meta['count_in_row'] == '3') echo 'selected'; ?> value=3><?= __("Grid (3 in a row)", "clipmydeals"); ?></option>
													<option <?php if ($term_meta['count_in_row'] == '4') echo 'selected'; ?> value=4><?= __("Grid (4 in a row)", "clipmydeals"); ?></option>
												</select>
											</div>
										</td>
									</tr>

									<tr class="row" style="display:block; max-width:88vw">
										<td class="col-5"><label for="welcome_type"><?= __("Welcome Type", "clipmydeals"); ?></label></td>
										<td class="col-7">
											<div class="form-check">
												<select id="welcome_type" name='welcome_type' class="form-control">
													<option <?php if ($term_meta['welcome_type'] == 'none') echo 'selected'; ?> value='none'><?= __("None", "clipmydeals"); ?></option>
													<option <?php if ($term_meta['welcome_type'] == 'banner') echo 'selected'; ?> value='banner'><?= __("Banner", "clipmydeals"); ?></option>
													<option <?php if ($term_meta['welcome_type'] == 'video') echo 'selected'; ?> value='video'><?= __("Video (Full Screen)", "clipmydeals"); ?></option>
													<option <?php if ($term_meta['welcome_type'] == 'slides') echo 'selected'; ?> value='slides'><?= __("Slides", "clipmydeals"); ?></option>
													<option <?php if ($term_meta['welcome_type'] == 'multi_slides') echo 'selected'; ?> value='multi_slides'><?= __("Multi Slides", "clipmydeals"); ?></option>
												</select>
											</div>
										</td>
									</tr>

									<tr class="row" style="display:block; max-width:88vw">
										<td class="col-3"><label for="theme_options_"><?= __("Preset Styles", "clipmydeals"); ?></label> </td>
										<td class="col-9">
											<div id="theme_options_" class="form-check">
												<?php foreach ($presets as $theme => $colors) {
													$theme_name = strtolower($theme);
												?>
													<div class="color-option col-md-6 col-xl-3 float-start m-0 p-2">
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="theme_option_setting" id="theme_option_setting_<?= $theme_name ?>" value=<?= $theme_name ?> <?= $term_meta['theme_option_setting'] == $theme_name ? 'checked' : ''; ?>>
															<label class="form-check-label" for="theme_option_setting_<?= $theme_name ?>"><?= $theme ?>
																<table class="color-palette" style="width:10rem;height:2rem;">
																	<tbody>
																		<tr style="border: 1px solid grey;">
																			<?php foreach ($colors as $color) { ?>
																				<td style="background-color: <?= $color ?>;"></td>
																			<?php } ?>
																		</tr>
																	</tbody>
																</table>
															</label>
														</div>
													</div>
												<?php } ?>
											</div>
										</td>
									</tr>

									<tr class="row" style="display:block; max-width:88vw">
										<td class="col-5"><label class="" for="location_taxonomy" style="display:block;"> <?= __("Location Taxonomy", "clipmydeals"); ?></label></td>
										<td class="col-7">
											<div class="form-check">
												<input type="checkbox" <?php if ($term_meta['location_taxonomy'] == '1') echo 'checked'; ?> name="location_taxonomy" class="" id="location_taxonomy" />
											</div>
										</td>
									</tr>

									<tr class="row" style="display:block; max-width:88vw">
										<td class="col-5"><label for="import-select"><?= __("Select quick data that you want to import", "clipmydeals"); ?></label></td>
										<td class="col-7">
											<div id="import-select" class="form-check">

												<table style="width:100%;">
													<tbody>
														<tr>
															<td class="p-0"><label style="width:100%;" for="coupons"><input class="me-3" type="checkbox" name="import[coupons]" id="coupons" /><?= __("Coupons", "clipmydeals"); ?></label></td>
														</tr>
														<tr>
															<td class="p-0"><label style="width:100%;" for="posts"><input class="me-3" type="checkbox" name="import[posts]" id="posts" /><?= __("Post", "clipmydeals"); ?></label></td>
														</tr>
														<tr>
															<td class="p-0"><label style="width:100%;" for="pages"><input onchange="toggleMenuBox(this)" class="me-3" type="checkbox" name="import[pages]" id="pages" /><?= __("Pages", "clipmydeals"); ?></label></td>
														</tr>
														<tr>
															<td class="p-0"><label style="width:100%;" for="menu"><input class="me-3" disabled type="checkbox" name="import[menu]" id="menu" /><?= __("Menu", "clipmydeals"); ?></label></td>
														</tr>
														<?php if (in_array('clipmydeals-comparison/clipmydeals-comparison.php', get_option('active_plugins'))) { ?>
															<tr>
																<td class="p-0"><label style="width:100%;" for="products"><input class="me-3" type="checkbox" name="import[products]" id="products" /><?= __("Products", "clipmydeals"); ?></label></td>
															</tr>
														<?php } ?>
													</tbody>
												</table>

											</div>
										</td>
									</tr>

									<tr class="row" style="display:block; max-width:88vw">
										<td class="col-5">
											<?php wp_nonce_field('quick_setup', 'quick_setup_nonce'); ?>
											<input type="hidden" name="action" value="cmd_import_content" />
											<input type="hidden" name="redirect_to" value="clipmydeals-quick-setup" />
											<button type="button" id="quick_setup_btn" class="btn btn-success btn-block mt-3 d-flex px-4" onclick="cmdSaveQuicksettings()"><?= __("Submit", "clipmydeals"); ?> <span class="dashicons dashicons-editor-break" style="margin-top:2px;"></span></button>
										</td>
										<td></td>
									</tr>

								</tbody>
							</table>

						</form>

					</div>
				</div>
			</div>

			<p><?= __('<div class="alert alert-warning">These are just basic settings for quick start. For more customization options you should go to <strong>Appearance</strong> > <a href="customize.php"><strong>Customize</strong></a></div>', "clipmydeals") ?></p>
		<?php } ?>
	</div>
<?php
}

if (!function_exists('cmd_quick_setup')) {
	function cmd_quick_setup() {
		if (wp_verify_nonce($_POST['quick_setup_nonce'], 'quick_setup')) {

			$term_meta = get_option("theme_mods_clipmydeals", array());
			$term_meta['location_taxonomy'] = $_POST['location_taxonomy']  == 'on' ? "1" : "0";
			$term_meta['count_in_row'] = $_POST['count_in_row'];
			$term_meta['welcome_type'] = $_POST['welcome_type'];
			$term_meta['theme_option_setting'] = $_POST['theme_option_setting'];
			update_option("theme_mods_clipmydeals", $term_meta);

			//SETS REQUIRED PERMALINK STRUCTURE
			reset_permalinks();

			//Sets homepage to recent posts
			update_option("show_on_front", 'posts');

			echo 'success';
		} else {
			echo '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified.', 'clipmydeals').'</p></div>';
		}
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
add_action('wp_ajax_cmd_quick_setup', 'cmd_quick_setup');

if (!function_exists('cmd_import_content')) {
	function cmd_import_content() {
		global $wpdb;
		$wp_prefix = $wpdb->prefix;
		if ((isset($_POST['quick_setup_nonce']) && wp_verify_nonce($_POST['quick_setup_nonce'], 'quick_setup'))
			|| (isset($_POST['demo_import_nonce'])  && wp_verify_nonce($_POST['demo_import_nonce'], 'demo_import'))
		) {

			$import = $_POST['import'];

			$cashback_installed = in_array('clipmydeals-cashback/clipmydeals-cashback.php', get_option('active_plugins'));
			$comparison_installed = in_array('clipmydeals-comparison/clipmydeals-comparison.php', get_option('active_plugins'));
			if ($import == 'all') {
				// SPEED IT UP [ START ]
				$import = array('coupons' => 'on', 'posts' => 'on', 'pages' => 'on', 'menu' => 'on', 'products' => 'on', 'all' => 'on');
				wp_defer_term_counting(true);
				$wpdb->query('SET autocommit = 0;');

				$wpdb->query("TRUNCATE $wpdb->comments");
				$wpdb->query("TRUNCATE $wpdb->commentmeta");
				// $wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE 'taxonomy_term_%'" );
				$wpdb->query("TRUNCATE $wpdb->posts");
				$wpdb->query("TRUNCATE $wpdb->postmeta");
				$wpdb->query("TRUNCATE $wpdb->terms");
				$wpdb->query("TRUNCATE $wpdb->term_taxonomy");
				$wpdb->query("TRUNCATE $wpdb->term_relationships");
				$wpdb->query("TRUNCATE $wpdb->termmeta");
				$wpdb->query("TRUNCATE $wpdb->links");
				$wpdb->query("TRUNCATE " . $wp_prefix . "cmd_store_to_domain");

				$cat_name = __('Uncategorized');                                            // Default category
				$cat_slug = sanitize_title(_x('Uncategorized', 'Default category slug')); // translators: Default category slug
				if (version_compare( $GLOBALS['wp_version'], '6.1.0', '<' ) and global_terms_enabled()) {
					$cat_id = $wpdb->get_var($wpdb->prepare("SELECT cat_ID FROM {$wpdb->sitecategories} WHERE category_nicename = %s", $cat_slug));
					if (null === $cat_id) {
						$wpdb->insert($wpdb->sitecategories, ['cat_ID' => 0, 'cat_name' => $cat_name, 'category_nicename' => $cat_slug, 'last_updated' => current_time('mysql', true),]);
						$cat_id = $wpdb->insert_id;
					}
					update_option('default_category', $cat_id);
				} else {
					$cat_id = 1;
				}
				$wpdb->insert($wpdb->terms, ['term_id' => $cat_id, 'name' => $cat_name, 'slug' => $cat_slug, 'term_group' => 0,]);
				$wpdb->insert($wpdb->term_taxonomy, ['term_id' => $cat_id, 'taxonomy' => 'category', 'description' => '', 'parent' => 0, 'count' => 0,]);

				update_option('wp_page_for_privacy_policy', 0);
				update_option('sticky_posts', []);

				// SPEED IT UP [ END ]
				wp_defer_term_counting(false);
				$wpdb->query('COMMIT;');
				$wpdb->query('SET autocommit = 1;');
			} else {
				$import = array_merge(array('coupons' => 'off', 'posts' => 'off', 'pages' => 'off', 'menu' => 'off', 'products' => 'off', 'all' => 'off'), $_POST['import'] ?? array());
			}

			// SPEED IT UP [ START ]
			wp_defer_term_counting(true);
			$wpdb->query('SET autocommit = 0;');

			$user = get_current_user_id();
			$filename = empty($_POST['demo_to_import']) ? 'import' : 'import-' . $_POST['demo_to_import'];
			$data = json_decode(str_replace('[replace_bloginfo_url]', get_bloginfo('url'), file_get_contents(get_template_directory() . "/inc/assets/json/" . $filename . ".json")), true);

			// Enter wp_options
			if ($import['all'] == 'on') {
				$theme = $data['wp_option']['theme_mods_clipmydeals'];
				$theme['slide'] = cmd_replaced_with_id_slides($theme['slide']);
				$theme['multi_slide'] = cmd_replaced_with_id_slides($theme['multi_slide']);
				$data['wp_option']['theme_mods_clipmydeals'] = $theme;

				foreach ($data['wp_option'] as $key => $value) update_option($key, $value);

				$quick_WPLANG = $data['wp_option']['WPLANG'];
				if ($quick_WPLANG) {
					require_once(ABSPATH . 'wp-admin/includes/translation-install.php');
					if (wp_can_install_language_pack()) {
						wp_download_language_pack($quick_WPLANG);
						update_option("WPLANG", $quick_WPLANG);
					}
					cmd_move_translate_files($quick_WPLANG, 'clipmydeals', 'themes');
				}
			}

			// Enter Terms
			if ($import['coupons'] == 'on' or $import['products'] == 'on') {
				$location_taxonomy  = get_theme_mod('location_taxonomy', false);
				// register location taxonomy otherwise it throws error as INVALID TAXONOMY
				if ($location_taxonomy) clipmydeals_create_location_taxonomy();
				foreach ($data['term'] as $quick_terms) {
					if ($location_taxonomy || $quick_terms['taxonomy'] != "locations") {
						if (isset($quick_terms['args']['parent'])) {
							$quick_terms['args']['parent'] = get_term_by('slug', $quick_terms['args']['parent'], $quick_terms['taxonomy'], OBJECT)->term_id;
						}
						$term = wp_insert_term($quick_terms['term'], $quick_terms['taxonomy'], $quick_terms['args']);
						$t_id = isset($term->error_data['term_exists']) ? $term->error_data['term_exists'] : $term['term_id'];
						if (!isset($quick_terms['termmeta'])) {
							$quick_terms['termmeta'] = array();
						}
						$quick_terms['termmeta']['demo_import'] = $quick_terms['taxonomy'];
						update_option("taxonomy_term_$t_id", $quick_terms['termmeta']);
						if (isset($quick_terms['termmeta']['store_url'])) {
							$wpdb->query("INSERT INTO " . $wp_prefix . "cmd_store_to_domain (store_id, domain) VALUES (" . $t_id . ",'" . str_replace("www.", "", parse_url($quick_terms['termmeta']['store_url'], PHP_URL_HOST)) . "');");
						}
					}
				}
			}

			// Create Menu
			if ($import['menu'] == 'on') {
				$menu_name = "QuickMenu" . current_time('timestamp');
				$term = wp_insert_term($menu_name, "nav_menu");
				$menu_id  = is_wp_error($term) ? $term->error_data['term_exists'] : $term['term_id'];

				// set Quick Menu
				$data['wp_option']['theme_mods_clipmydeals']['nav_menu_locations'] = array("primary" => $menu_id);
				update_option("theme_mods_clipmydeals", $data['wp_option']['theme_mods_clipmydeals']);

				// Home
				$home = (!isset($data['wp_option']['cmd_home']) ? "Home" : $data['wp_option']['cmd_home']);
				if ($home != "") {
					$nav_item = wp_insert_post(array(
						"post_title" 	=> $home,
						"post_content" 	=> " ",
						"post_status"	=> "publish",
						"menu_order"	=> "1",
						"post_type"		=> "nav_menu_item",
					));
					wp_set_object_terms($nav_item, $menu_name, 'nav_menu');

					add_post_meta($nav_item, '_menu_item_type', 'custom');
					add_post_meta($nav_item, '_menu_item_menu_item_parent', '0');
					add_post_meta($nav_item, '_menu_item_object_id', '0');
					add_post_meta($nav_item, '_menu_item_object', 'custom');
					add_post_meta($nav_item, '_menu_item_target', null);
					add_post_meta($nav_item, '_menu_item_classes', 'a:1:{i:0;s:0:"";}');
					add_post_meta($nav_item, '_menu_item_xfn', null);
					add_post_meta($nav_item, '_menu_item_url', get_home_url());
				}
			}

			// Enter Post, Page, Coupon, Product
			foreach ($data['item'] as $quick_items) {

				if (
					($import['posts'] != 'on' && $quick_items["post_type"] == "post") ||
					($import['coupons'] != 'on' && $quick_items["post_type"] == "coupons") ||
					(($import['products'] != 'on' || !$comparison_installed) && $quick_items["post_type"] == "products") ||
					(($import['pages'] != 'on' && $quick_items["post_type"] == "page") || (isset($quick_items["cashback"]) && !$cashback_installed))
				) continue;

				$quick_items['post_author'] = $user;     // Set user for each post
				$post_id = wp_insert_post($quick_items); // Inserts post

				//Insert Postmeta for each posts
				if (isset($quick_items['postmeta'])) {
					$quick_postmeta = $quick_items['postmeta'];
					if (in_array($quick_items["post_type"], array('coupons', 'products'))) {
						$quick_postmeta['cmd_verified_on'] = $quick_postmeta['cmd_start_date'] = date("Y-m-d");
						$quick_postmeta['cmd_valid_till'] = date('Y-m-d', strtotime(date("Y-m-d") . " + 365 day"));
					}
					foreach ($quick_postmeta as $key => $value) add_post_meta($post_id, $key, $value);
				}
				add_post_meta($post_id, "clipmydeals_demo_$quick_items[post_type]", !empty($_POST['demo_to_import']) ? $_POST['demo_to_import'] : 'demo_setup');

				// Insert Taxonomies for each post
				if (isset($quick_items['taxonomies'])) {
					$quick_taxonomies = $quick_items['taxonomies'];
					if ($quick_items["post_type"] == "page") wp_remove_object_terms($post_id, 'Uncategorized', 'category'); // to remove default category added for posts -> uncategorized
					foreach ($quick_taxonomies as $taxonomy => $slugs) wp_set_object_terms($post_id, $slugs, $taxonomy, false);
				}

				// Inserts Attachment if any
				if (isset($quick_items['attachment_url'])) {
					clipmydeals_create_attachment($quick_items['attachment_url'], $post_id);
				}

				// Add post to menu
				if (isset($quick_items["add_to_menu"]) and $import['pages'] == 'on' and $import['menu'] == 'on') {
					$menu_order = $quick_items["add_to_menu"];
					$nav_item = wp_insert_post(array(
						"post_title" 	=>	$quick_items['post_title'],
						"post_content" 	=>	" ",
						"post_status"	=>	"publish",
						"menu_order"	=>	$menu_order,
						"post_type"		=>	"nav_menu_item",
						"post_name" 	=>	str_replace(' ', '-', strtolower($quick_items['post_title'])) . "-menu-item"
					));
					wp_set_object_terms($nav_item, $menu_name, 'nav_menu');

					update_post_meta($nav_item, '_menu_item_type', 'post_type');
					update_post_meta($nav_item, '_menu_item_menu_item_parent', '0');
					update_post_meta($nav_item, '_menu_item_object_id', $post_id);
					update_post_meta($nav_item, '_menu_item_object', 'page');
					update_post_meta($nav_item, '_menu_item_target', null);
					update_post_meta($nav_item, '_menu_item_classes', 'a:1:{i:0;s:0:"";}');
					update_post_meta($nav_item, '_menu_item_xfn', null);
					update_post_meta($nav_item, '_menu_item_url', null);
				}

				// Add custom css
				if ($quick_items['post_type'] == "custom_css") {
					$data['wp_option']['theme_mods_clipmydeals']['custom_css_post_id'] = $post_id;
					update_option("theme_mods_clipmydeals", $data['wp_option']['theme_mods_clipmydeals']);
				}
			}

			// Enter wp_options for plugins
			if ($import['all'] == 'on') {

				// Cashback options
				if (isset($data['cashback'])) {
					if ($cashback_installed) {
						$cashback_options = array();
						foreach ($data['cashback']['cashback_options'] as $optk => $optv) {
							foreach ($optv as $store_slug => $value) {
								$store_id = get_term_by('slug', $store_slug, 'stores')->term_id;
								$cashback_options[$optk][$store_id] = (!empty($value) && $optk == "details" ? get_page_by_path($value, OBJECT, 'page')->ID : $value);
							}
						}
						$data['cashback']['cashback_options'] = $cashback_options;

						if (!empty($data['cashback']['cmdcp_referral_page'])) $data['cashback']['cmdcp_referral_page'] = get_page_by_path($data['cashback']['cmdcp_referral_page'], OBJECT, 'page')->ID;

						cmd_move_translate_files($data['wp_option']['WPLANG'], 'clipmydeals-cashback', 'plugins');

						foreach ($data['cashback'] as $key => $value) update_option($key, $value);
					} else {
						foreach ($data['cashback'] as $key => $value) delete_option($key);
					}
				}

				// Comparison options
				if (isset($data['comparison'])) {
					if ($comparison_installed) {
						update_option('cmdcomp_plugin_mod', $data['comparison']);
						cmd_move_translate_files($data['wp_option']['WPLANG'], 'clipmydeals-comparison', 'plugins');
					} else {
						delete_option('cmdcomp_plugin_mod');
					}
				}
			}

			// SETS REQUIRED PERMALINK STRUCTURE (location taxonomy/htacess issue)
			reset_permalinks();

			// SPEED IT UP [ END ]
			wp_defer_term_counting(false);
			$wpdb->query('COMMIT;');
			$wpdb->query('SET autocommit = 1;');

			$message = '<div class="notice notice-success is-dismissible"><p>'.__('Settings applied successfully.', 'clipmydeals').'</p></div>';
		} else {
			$message = '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified.', 'clipmydeals').'</p></div>';
		}

		setcookie('message', $message);
		wp_redirect('admin.php?page=' . $_POST['redirect_to']);
		exit();
	}
}
add_action('admin_post_cmd_import_content', 'cmd_import_content');

function clipmydeals_create_attachment($image_url, $post_id = 0) {
	if (empty($image_url)) return array();

	// TO DO: Check Line 518: $filename =...
	$wp_upload_dir = wp_upload_dir();
	$upload_dir    = wp_mkdir_p($wp_upload_dir['path']) ? $wp_upload_dir['path'] : $wp_upload_dir['basedir'];
	$imagename_arr = preg_split("/[\\/]+/", $image_url);
	$filename      = basename(wp_unique_filename($upload_dir, array_pop($imagename_arr))); // Create image file name
	$filepath      = $upload_dir . '/' . $filename;

	// Create the image  file on the server
	file_put_contents($filepath, file_get_contents($image_url));

	$attach_id = wp_insert_attachment(array(
		'post_mime_type' => wp_check_filetype($filename, null)['type'],
		'post_title'     => sanitize_file_name($filename),
		'post_content'   => '',
		'post_status'    => 'inherit'
	), $filepath, $post_id);

	require_once(ABSPATH . 'wp-admin/includes/image.php');
	wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $filepath));
	set_post_thumbnail($post_id, $attach_id);

	return array('id' => $attach_id, 'path' => $filepath, 'url' => wp_get_attachment_url($attach_id));
}

function cmd_replaced_with_id_slides($slides) {
	$replaced_slides = array();
	foreach ($slides as $key => $value) $replaced_slides[$key] = array(clipmydeals_create_attachment($value[0])['id'], $value[1]);
	return $replaced_slides;
}

function cmd_move_translate_files($quick_WPLANG, $name, $type) {

	if (empty($quick_WPLANG)) return;

	// Emulate WP_Filesystem to avoid FS_METHOD and filters overriding "direct" type
	if (!class_exists('WP_Filesystem_Direct', false)) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
	}

	$wp_filesystem = new WP_Filesystem_Direct(null);

	$current_po = ABSPATH . "wp-content/$type/$name/languages/$name-$quick_WPLANG.po";
	$current_mo = ABSPATH . "wp-content/$type/$name/languages/$name-$quick_WPLANG.mo";
	$destination_po = ABSPATH . "wp-content/languages/$type/$name-$quick_WPLANG.po";
	$destination_mo = ABSPATH . "wp-content/languages/$type/$name-$quick_WPLANG.mo";

	if ($wp_filesystem->exists($current_po) and $wp_filesystem->exists($current_mo)) {
		$wp_filesystem->mkdir(ABSPATH . "wp-content/languages/$type", 0755);
		$wp_filesystem->copy($current_po, $destination_po, true);
		$wp_filesystem->copy($current_mo, $destination_mo, true);
	}

	return;
}

function reset_permalinks() {
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure('/%postname%/');
	$wp_rewrite->flush_rules();
}
add_action('init', 'reset_permalinks');

?>