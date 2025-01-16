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

function clipmydeals_subscriptions_menu()
{
	add_submenu_page('edit.php?post_type=notifications', __('Subscriptions', 'clipmydeals'), __('Subscriptions', 'clipmydeals'), 'edit_others_posts', 'subscriptions', 'clipmydeals_subscriptions_page');
}
add_action('admin_menu', 'clipmydeals_subscriptions_menu');

function clipmydeals_subscriptions_page()
{
	//Bootstrap CSS
	wp_register_style('bootstrap.min', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css');
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('clipmydeals_styles', get_template_directory_uri() . '/inc/assets/css/clipmydeals_styles.css');
	wp_enqueue_style('clipmydeals_styles');

	//Prepare Table of elements
	$cmd_subscription_table = new CMD_Subscription_Table();
	$cmd_subscription_table->prepare_items();

	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}
?>
	<style type="text/css">
		.wp-list-table .column-id {
			width: 5%;
		}

		.wp-list-table .column-subscription_endpoint {
			width: 50%;
		}

		.wp-list-table .column-created_at,
		.wp-list-table .column-actions {
			width: 10%;
		}
	</style>
	<div class="wrap" style="background:#F1F1F1;">
		<h2><?= __('Subscriptions', 'clipmydeals'); ?></h2>
		<?= $message ?? ''; ?>
		<hr />
		<form method="POST">
			<input type="hidden" name="post_type" value="notifications" />
			<input type="hidden" name="page" value="subscriptions" />
			<?php
			$cmd_subscription_table->search_box(__('Search','clipmydeals'), 'subscription_endpoint');
			$cmd_subscription_table->display();
			?>
		</form>
	</div>
	<?php
}

class CMD_Subscription_Table extends WP_List_Table
{

	function __construct()
	{
		global $status, $page;

		parent::__construct(array(
			'singular'  => 'cmd_subscription',
			'plural'    => 'cmd_subscriptions',
			'ajax'      => false
		));
	}

	function column_default($item, $column_name)
	{
		switch ($column_name) {
			case 'id':
			case 'subscription_endpoint':
				return $item[$column_name];
			default:
				return print_r($item, true);
		}
	}
	function column_cb($item)
	{
		return '<input type="checkbox" name="subscriptions[]" value="' . $item['id'] . '" />';
	}
	function column_created_at($item)
	{
		return date('F jS, Y h:i a', strtotime($item['created_at']));
	}
	function column_user_id($item)
	{
		if ($item['user_id']) return get_userdata($item['user_id'])->display_name;
		return "NA";
	}
	function column_actions($item)
	{
		return '<a href="' . wp_nonce_url(admin_url("admin-post.php?action=clipmydeals_delete_subscription&subscription_id={$item['id']}"), 'clipmydeals', 'clipmydeals_delete_subscription_nonce') . '"><span class="dashicons dashicons-trash text-danger"></span></a>';
	}

	function get_columns()
	{
		$columns = array(
			'cb'                    => '<input type="checkbox" />',
			'id'                    =>  __('ID', 'clipmydeals'),
			'subscription_endpoint' =>  __('Subscription', 'clipmydeals'),
			'user_id'               =>  __('User', 'clipmydeals'),
			'created_at'            =>  __('Created On', 'clipmydeals'),
			'actions'               =>  __('Actions', 'clipmydeals'),
		);

		return $columns;
	}

	function get_sortable_columns()
	{
		$sortable_columns = array(
			'id'         => array('id', false),
			'user_id'    => array('user_id', false),
			'created_at' => array('created_at', false),
		);

		return $sortable_columns;
	}
	public static function record_count()
	{
		global $wpdb;
		$sql = "SELECT COUNT(id) FROM {$wpdb->prefix}cmd_subscriptions";
		return $wpdb->get_var($sql);
	}
	function get_bulk_actions()
	{
		$actions = array(
			'delete'    => 'Delete'
		);
		return $actions;
	}
	function prepare_items()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'cmd_subscriptions';
		$per_page   = 25;
		$columns    = $this->get_columns();
		$hidden     = array();
		$sortable   = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->process_bulk_action();

		$total_items = $this->record_count();
		$search = '';
		if (!empty($_REQUEST['s'])) {
			$search = $_REQUEST['s'];
			if (is_numeric($search)) $search = "AND id = $search";
			else $search = "AND subscription_endpoint LIKE '%$search%'";
		}

		$paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
		$orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created_at';
		$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

		$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE 1 $search ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

		$this->set_pagination_args(array(
			'total_items'   => $total_items,
			'per_page'      => $per_page,
			'total_pages'   => ceil($total_items / $per_page),
		));
	}
	function process_bulk_action()
	{
		$action = $this->current_action();
		if (empty($action)) return;
		$subscriptions = $_POST['subscriptions'];
		if (count($subscriptions)) {
			global $wpdb;
			$wpdb->query("DELETE FROM {$wpdb->prefix}cmd_subscriptions WHERE id in (" . implode(",", $subscriptions) . ")");
			echo  "<div class='notice notice-success is-dismissible'><p>" .sprintf(__("%s subscriptions deleted successfully.", 'clipmydeals') ,count($subscriptions)) ."</p></div>";
		}
	}
}

if (!function_exists('clipmydeals_delete_subscription')) {
	function clipmydeals_delete_subscription()
	{
		if (!wp_verify_nonce($_GET['clipmydeals_delete_subscription_nonce'], 'clipmydeals')) {
			$message = '<div class="notice notice-error is-dismissible"><p>'.__('Access Denied. Nonce could not be verified.', 'clipmydeals').'</p></div>';
		} else {
			global $wpdb;
			$wpdb->delete($wpdb->prefix . "cmd_subscriptions", array(
				'id' => $_GET['subscription_id']
			));
			$message = "<div class='notice notice-success is-dismissible'><p>".__('Subscription removed successfully.', 'clipmydeals')."</p></div>";
		}
		setcookie('message', $message);
		wp_redirect("edit.php?post_type=notifications&page=subscriptions");
	}
}
add_action('admin_post_clipmydeals_delete_subscription', 'clipmydeals_delete_subscription');
