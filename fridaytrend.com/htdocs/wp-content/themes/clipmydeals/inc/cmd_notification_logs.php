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

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

function clipmydeals_notifications_log_menu() {
	add_submenu_page('edit.php?post_type=notifications', __('Notifications Log', 'clipmydeals'), __('Notifications Log', 'clipmydeals'), 'edit_others_posts', 'notifications_log', 'clipmydeals_notifications_log_page');
}
add_action('admin_menu', 'clipmydeals_notifications_log_menu');

function clipmydeals_notifications_log_page() {
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
	//Prepare Table of elements
	$cmd_notification_log_table = new CMD_Notification_Log_Table();
	$cmd_notification_log_table->prepare_items();
	$no_of_days = get_option('cmd_clear_notifications_log_after', '30');
?>
	<style type="text/css">
		.wp-list-table .column-id {
			width: 5%;
		}

		.wp-list-table .column-message {
			width: 65%;
		}

		.wp-list-table .column-created_at {
			width: 10%;
		}
	</style>
	<div class="wrap" style="background:#F1F1F1;">
		<h2><?= __('Notifications Log', 'clipmydeals'); ?></h2>
		<?= $message ?? ''; ?>
		<hr />
		<?php if ($cmd_notification_log_table->record_count() > 0) { ?>
			<form name="refreshLogs" role="form" class="w-100" method="post" action="<?= admin_url('admin-post.php') ?>">
				<div class="form-row">
					<?php wp_nonce_field('clipmydeals', 'clipmydeals_clear_notification_log_nonce') ?>
					<label for="no_of_days" class="text-center">
					<?= sprintf(__('Delete Notification Logs After %s Day(s)', 'clipmydeals'),'<input id="no_of_days" name="no_of_days" type="number" min="1" step="1" value="'.$no_of_days.'">') ?>	
						
					</label>
					<input type="hidden" name="action" value="clipmydeals_clear_notification_log">
					<div class="form-group">
						<button type="submit" name="cmd_notification_update" class="btn btn-primary ms-md-2"><?= __('Delete', 'clipmydeals'); ?></button>
						<a href="<?= wp_nonce_url(admin_url('admin-post.php?action=clipmydeals_clear_notification_log&cmd_notifications_clear_all=1'), 'clipmydeals', 'clipmydeals_clear_notification_log_nonce') ?>" class="btn btn-outline-danger"><?= __('Clear All', 'clipmydeals'); ?></a>
					</div>
				</div>
			</form>
		<?php } ?>
	</div>
<?php
	$cmd_notification_log_table->display();
}

class CMD_Notification_Log_Table extends WP_List_Table {
	function __construct() {
		global $status, $page;

		parent::__construct(array(
			'singular'  => 'cmd_notification_log',
			'plural'    => 'cmd_notification_logs',
			'ajax'      => false
		));
	}

	function column_default($item, $column_name) {
		switch ($column_name) {
			case 'id':
			case 'subscription_id':
			case 'message':
				return ucfirst($item[$column_name]);
			default:
				return print_r($item, true);
		}
	}
	function column_failed($item) {
		$failed = $item['failed'];
		return '<span class="badge bg-' . ($failed == 'Y' ? 'danger' : 'success') . ' p-2">' . ($failed == 'Y' ? __('Failed', 'clipmydeals') : __('Sent', 'clipmydeals')) . '</span>';
	}
	function column_created_at($item) {
		return date('F jS, Y h:i a', strtotime($item['created_at']));
	}

	function get_columns() {
		$columns = array(
			'id'              =>  __('ID', 'clipmydeals'),
			'subscription_id' =>  __('Subscription', 'clipmydeals'),
			'failed'          => __('Status', 'clipmydeals'),
			'message'         => __('Message', 'clipmydeals'),
			'created_at'      => __('Created On', 'clipmydeals')
		);

		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'         => array('id', false),
			'failed'     => array('failed', false),
			'created_at' => array('created_at', false),
		);

		return $sortable_columns;
	}
	public static function record_count() {
		global $wpdb;
		$sql = "SELECT COUNT(id) FROM {$wpdb->prefix}cmd_notification_logs";
		return $wpdb->get_var($sql);
	}
	function prepare_items() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'cmd_notification_logs';
		$per_page   = 25;
		$columns    = $this->get_columns();
		$hidden     = array();
		$sortable   = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->process_bulk_action();

		$total_items = $this->record_count();
		$paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
		$orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created_at';
		$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

		$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

		$this->set_pagination_args(array(
			'total_items'   => $total_items,
			'per_page'      => $per_page,
			'total_pages'   => ceil($total_items / $per_page),
		));
	}
}

if (!function_exists('clipmydeals_clear_notification_log')) {
	function clipmydeals_clear_notification_log() {
		if (!wp_verify_nonce($_REQUEST['clipmydeals_clear_notification_log_nonce'], 'clipmydeals')) {
			$message = '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified', 'clipmydeals').'</p></div>';
		} else if (isset($_REQUEST['cmd_notifications_clear_all'])) {
			global $wpdb;
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}cmd_notification_logs");
			$message = "<div class='notice notice-success is-dismissible'><p>".__('Notifications log cleared successfully.', 'clipmydeals')."</p></div>";
		} elseif (isset($_POST['cmd_notification_update'])) {
			global $wpdb;
			$no_of_days = $_POST['no_of_days'];
			update_option('cmd_clear_notifications_log_after', $no_of_days);
			$wpdb->query("DELETE FROM `{$wpdb->prefix}cmd_notification_logs` WHERE `created_at` < CURRENT_TIMESTAMP - INTERVAL $no_of_days DAY");
		}
		setcookie('message', $message);
		wp_redirect('edit.php?post_type=notifications&page=notifications_log');
	}
}
add_action('admin_post_clipmydeals_clear_notification_log', 'clipmydeals_clear_notification_log');

if (!wp_next_scheduled('cmd_clear_notifications_log_event')) {
	wp_schedule_event(time(), 'daily', 'cmd_clear_notifications_log_event');
}

add_action('cmd_clear_notifications_log_event', 'cmd_clear_notifications_log');
function cmd_clear_notifications_log() {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	$no_of_days = get_option('cmd_clear_notifications_log_after', '30');
	$wpdb->query("DELETE FROM `{$wp_prefix}cmd_notification_logs` WHERE `created_at` < CURRENT_TIMESTAMP - INTERVAL $no_of_days DAY");
}
