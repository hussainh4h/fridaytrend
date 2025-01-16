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

/* INSTALLATION */

// ClipMyDeals License Page
function clipmydeals_settings_page(){
	?>
	<div class="wrap">
	    <h1><?= __('ClipMyDeals', 'clipmydeals'); ?></h1>
	    <hr/>
	    <form method="post" action="options.php">
	        <?php
				// need to refresh key on save
				$key = get_option('cmd_key');
				update_option('cmd_key','123');
				update_option('cmd_key',$key);
				// display everything
				settings_fields("section");
	            do_settings_sections("theme-options");
	            submit_button(__('Save', 'clipmydeals'));
	        ?>
	    </form>
	    <hr/>
	    <p><?= __('After you save your key, you can start adding your coupons and customize the look &amp; feel of your website.', 'clipmydeals'); ?></p>
	    <p>
			<a class="button button-warning" href="edit.php?post_type=coupons"><?= __('Add Coupons', 'clipmydeals'); ?></a>
			<a class="button button-success" href="customize.php?return=%2Fclipmydeals%2Fwp-admin%2Fadmin.php%3Fpage%3Dclipmydeals"><?= __('Customize Website', 'clipmydeals'); ?></a>
			<a class="button button-success" href="admin.php?page=clipmydeals-quick-setup"><?= __('Quick Setup', 'clipmydeals'); ?></a>
			<a class="button button-success" href="admin.php?page=clipmydeals-demo-import"><?= __('Demo Import', 'clipmydeals'); ?></a>
		</p>
	</div>
	<?php
}
function clipmydeals_menu_item() {
	add_menu_page('ClipMyDeals License', 'ClipMyDeals', 'edit_pages', 'clipmydeals', 'clipmydeals_settings_page', 'dashicons-tickets-alt', 5);
	add_submenu_page("clipmydeals", __("License Key", 'clipmydeals'), __("License Key", 'clipmydeals'), 	'edit_pages', "clipmydeals", "clipmydeals_settings_page");	
	add_submenu_page("clipmydeals", __("ClipMyDeals Demo Import", 'clipmydeals'),	 __("Demo Import", 'clipmydeals'), 	'manage_categories', "clipmydeals-demo-import", "clipmydeals_demo_import_page");
	add_submenu_page("clipmydeals", __("ClipMyDeals Quick Setup", 'clipmydeals'),	 __("Quick Setup", 'clipmydeals'), 	'manage_categories', "clipmydeals-quick-setup", "clipmydeals_quick_setup_page");
}
add_action('admin_menu', 'clipmydeals_menu_item');

// ClipMyDeals Settings Panel
if(!function_exists('clipmydeals_display_license_key_element')) {
	function clipmydeals_display_license_key_element() {
		?>
			<input type="text" name="cmd_key" id="cmd_key" value="<?php echo get_option('cmd_key'); ?>" />
		<?php
	}
}
function clipmydeals_display_header_format() {
	?>
    	<input type="checkbox" name="cmd_old_header" id="cmd_old_header" value="1" <?= (get_option('cmd_old_header') ? 'checked' : '') ?>>
    <?php
}

function clipmydeals_display_theme_panel_fields() {
	add_settings_section('section', __("License Key", 'clipmydeals'), null, 'theme-options');
	add_settings_field('cmd_key', __('Enter your 16 Digit License Key : ', 'clipmydeals'), 'clipmydeals_display_license_key_element', 'theme-options', 'section');
	//add_settings_field('cmd_old_header', 'Use old Header format : ', 'clipmydeals_display_header_format', 'theme-options', 'section');
    register_setting('section', 'cmd_key');
    register_setting('section', 'cmd_old_header');
}
add_action('admin_init', 'clipmydeals_display_theme_panel_fields');


/* UPDATE */

$update_url = 'https://clipmydeals.com/updates/';

// Get Theme Name & Version
if(function_exists('wp_get_theme')){
    $theme_data = wp_get_theme(get_option('template'));
    $theme_version = $theme_data->Version;  
} else {
    $theme_data = wp_get_theme('clipmydeals');
    $theme_version = $theme_data['Version'];
}    
$theme_base = get_option('template');

// Hook into WordPress' Theme Update checking mechanism
add_filter('pre_set_site_transient_update_themes', 'clipmydeals_check_for_update');
function clipmydeals_check_for_update($checked_data) {
	global $wp_version, $theme_version, $theme_base, $update_url;

	// We only need to take control for clipmydeals. If another theme is active, we will return the checked_data as it is
	if(substr($theme_base,0,11)!='clipmydeals') {
		return $checked_data;
	}
	
	$request = array(
		'slug' => 'clipmydeals',
		'version' => $theme_version 
	);
	// Start checking for an update
	$send_for_check = array(
		'body' => array(
			'action' => 'theme_update', 
			'request' => serialize($request),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);
	$raw_response = wp_remote_post($update_url, $send_for_check);
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);

	// Feed the update data into WP updater
	if (!empty($response)) 
		$checked_data->response[$theme_base] = $response;

	return $checked_data;
}

// Hook into Theme Updates section on WordPress' Updates page
add_filter('themes_api', 'clipmydeals_theme_api', 10, 3);
function clipmydeals_theme_api($def, $action, $args) {
	global $theme_base, $theme_version, $update_url, $wp_version;
	
	if (!isset($args->slug) or $args->slug != $theme_base)
		return false;
	
	// Get the current version

	$args->version = $theme_version;
	$request_string = array(
		'body' => array(
			'action' => 'theme_update', 
			'request' => serialize($args),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);
	$request = wp_remote_post($update_url, $request_string);

	if (is_wp_error($request)) {
		$res = new WP_Error('themes_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>', 'clipmydeals'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		
		if ($res === false)
			$res = new WP_Error('themes_api_failed', __('An unknown error occurred', 'clipmydeals'), $request['body']);
	}
	
	return $res;
}

if (is_admin()) { $current = get_transient('update_themes'); }

// Allow download from non-wordpress repository
add_filter( 'http_request_host_is_external', 'clipmydeals_allow_my_custom_host', 10, 3 );
function clipmydeals_allow_my_custom_host( $allow, $host, $url ) {
  if ( $host == 'localhost' || $host == 'linkmydeals.com' || $host == 'clipmydeals.com') { $allow = true; }
  return $allow;
}

// Copy Customizer Settings to new version
add_action('after_setup_theme', 'clipmydeals_copy_settings');
function clipmydeals_copy_settings() {
	$old_theme_name = get_option( 'cmd_theme_name' );
	$new_theme_name = get_option('template');
	if ($old_theme_name !== $new_theme_name) {
		if(!empty(get_option('theme_mods_'.$old_theme_name))) {
			update_option('theme_mods_'.$new_theme_name,get_option('theme_mods_'.$old_theme_name));
			delete_option('theme_mods_'.$old_theme_name);
		}
		update_option('cmd_theme_name', $new_theme_name);
	}
}

?>
