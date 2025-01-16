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

// PopularStores Widget
class cmd_PopularStores_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'popularstores_widget',
			'description' => 'Popular Stores',
		);
		parent::__construct('popularstores_widget', 'ClipMyDeals Popular Stores', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		$placeholder = get_theme_mod('default_store_image');

		global $exclude_stores;
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$showcount = !empty($instance['count']) ? '1' : '0';
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		if (array_key_exists('orderby', $instance) and $instance['orderby'] != 'priority') {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'name';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'asc';
		}
		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];

		echo '<div class="row justify-content-center p-1 px-2">';
		$stores = get_terms(array(
			'taxonomy'	=> 'stores',
			'hide_empty' => $inv_empty,
			'orderby'	=> $orderby,
			'order'		=> $ascdsc,
		));

		$store_details = array();
		foreach ($stores as $store) {
			$store_custom_fields = cmd_get_taxonomy_options($store->term_id, 'stores');
			if ($store_custom_fields['status'] == "inactive") {
				$exclude_stores[] = $store->term_id;
				continue;
			}
			if ($store_custom_fields['popular'] == 'yes') {
				$store_details[] =  array(
					'term_id' => $store->term_id,
					'name' => $store->name,
					'slug' => $store->slug,
					'count' => $store->count,
					'term_meta' => $store_custom_fields,
				);
			}
		}

		if (array_key_exists('orderby', $instance) and  $instance['orderby'] == 'priority') {
			$store_details = cmd_store_category_sort($store_details, $instance['orderby'], $ascdsc);
		}

		$odd = true;
		foreach ($store_details as $store) {
			$store = (object)$store;
			$store_custom_fields = $store->term_meta;
?>
			<div class="col-6 <?php if ($odd) {
									echo 'pe-1';
									$odd = false;
								} else {
									echo 'ps-1';
									$odd = true;
								} ?>">
				<a href="<?php echo get_term_link($store->term_id); ?>" style="text-decoration:none;">
					<div class="cmd-taxonomy-card card p-1 rounded-4 mt-2">
						<?php
						if (!empty($store_custom_fields['store_logo'])) {
							$store_image_url = $store_custom_fields['store_logo'];
						} elseif ($placeholder) {
							$store_image_url = $placeholder;
						} else {
							$store_image_url = get_template_directory_uri() . "/inc/assets/images/random-feature.jpg";
						}
						?>
						<img src="<?php echo $store_image_url; ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />
						<?php if ($showcount) { ?>
							<div class="card-footer text-center pt-2 pb-1"><span><?php echo $store->count . ' ' . ($store->count > 1 ? __('Offers', 'clipmydeals') : __('Offer', 'clipmydeals')); ?></span></div>
						<?php } ?>
					</div>
				</a>
			</div>
		<?php

		}
		echo '</div>';

		echo $args['after_widget'];
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'];
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$orderby  = 'priority';
			$ascdsc  = 'desc';
			$showcount = true;
			$empty = false;
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
				<option value="priority" <?php if ($orderby == 'priority') {
												echo 'selected="selected"';
											} ?>>Priority</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}

// All Brands
class cmd_AllBrands_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'allbrands_widget',
			'description' => 'All Brands',
		);
		parent::__construct('allbrands_widget', 'ClipMyDeals All Brands', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		$placeholder = get_theme_mod('default_store_image');

		global $exclude_coupons;
		$stores = get_terms('stores', array(
			'hide_empty' => false,
		));
		foreach ($stores as $store) {
			store_taxonomy_status($store->term_id);
		}
		exclude_coupons();
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$showcount = !empty($instance['count']) ? '1' : '0';
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		if (array_key_exists('orderby', $instance) and $instance['orderby'] != 'priority') {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'name';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'asc';
		}
		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];

		echo '<div class="row justify-content-center p-1 px-2">';
		$brands = get_terms(array(
			'taxonomy'	=> 'brands',
			'hide_empty' => $inv_empty,
			'orderby'	=> $orderby,
			'order'		=> $ascdsc,
		));

		$brand_details = array();
		foreach ($brands as $brand) {
			$brand_custom_fields = cmd_get_taxonomy_options($brand->term_id, '');
			$brand_details[] =  array(
				'term_id' => $brand->term_id,
				'name' => $brand->name,
				'slug' => $brand->slug,
				'count' => count(query_posts(array('brands' => $brand->slug, 'posts_per_page' => -1, 'post__not_in' => $exclude_coupons))),
				'term_meta' => $brand_custom_fields,
			);
		}

		$odd = true;
		foreach ($brand_details as $brand) {
			$brand = (object)$brand;
			$brand_custom_fields = $brand->term_meta;
		?>
			<div class="col-6 col-sm-4 col-md-3 col-lg-2 p-2">
				<a href="<?php echo get_term_link($brand->term_id); ?>">
					<div class="cmd-taxonomy-card card h-100 p-2 rounded-4 shadow-sm">
						<?php if (!empty($brand_custom_fields['brand_image'])) { ?>
							<img src="<?php echo $brand_custom_fields['brand_image']; ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $brand->name; ?> Logo" />
						<?php } elseif ($placeholder) {
							$image_url = $placeholder;
						} else { ?>
							<img src="<?php echo get_template_directory_uri() . "/inc/assets/images/random-feature.jpg" ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />

						<?php } ?>
						<div class="card-footer text-center py-1 px-0">
							<div class="cmd-store-name fw-bold p-2"><?php echo $brand->name ?></div>
							<?php if ($showcount) { ?>
								<small><?php echo $brand->count ?> <?= ($brand->count > 1 ? __('Offers', "clipmydeals") : __('Offer', "clipmydeals")) ?></small>
							<?php } ?>
						</div>
					</div>
				</a>
			</div>
		<?php

		}
		echo '</div>';

		echo $args['after_widget'];

		wp_reset_query();
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'];
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$orderby  = 'name';
			$ascdsc  = 'desc';
			$showcount = true;
			$empty = false;
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}


// StoreCategory Widget
class cmd_StoreCategory_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'storecategory_widget',
			'description' => 'Store Category',
		);
		parent::__construct('storecategory_widget', 'ClipMyDeals Store Category', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		$placeholder = get_theme_mod('default_store_image');

		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$showcount = !empty($instance['count']) ? '1' : '0';
		$cashback = !empty($instance['cashback']) ? '1' : '0';
		$cashbackredirect = !empty($instance['link']) ? '1' : '0';
		$popular = !empty($instance['popular']) ? 'yes' : '';
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		if (array_key_exists('orderby', $instance) and $instance['orderby'] != 'priority') {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'name';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'asc';
		}
		$store_category = array_key_exists('store_category', $instance)	? strtolower($instance['store_category']) : '';
		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];

		echo '<div class="row justify-content-center p-1 px-2">';
		$stores = get_terms(array(
			'taxonomy' => 'stores',
			'hide_empty'	=> $inv_empty,
			'orderby'	=> $orderby,
			'order'	=> $ascdsc,
		));

		$store_category = explode(',', $store_category);

		$store_details = array();
		foreach ($store_category as $value) {
			foreach ($stores as $key => $store) {
				$store_custom_fields = cmd_get_taxonomy_options($store->term_id, 'stores');
				if ($store_custom_fields['status'] == 'inactive') continue;
				if ((!$popular or $store_custom_fields['popular'] == 'yes') and !empty($store_custom_fields['store_category'])) {
					$store_custom_fields['store_category'] = str_replace("\'", "'", $store_custom_fields['store_category']);
					$store_custom_fields['store_category'] = strtolower($store_custom_fields['store_category']);
					$store_custom_fields['store_category'] = explode(',', $store_custom_fields['store_category']);
					if (in_array($value, $store_custom_fields['store_category'])) {
						$store_details[] =  array(
							'term_id' => $store->term_id,
							'name' => $store->name,
							'slug' => $store->slug,
							'count' => $store->count,
							'term_meta' => $store_custom_fields,
						);
						unset($stores[$key]);
					}
				}
			}
		}

		$store_details = cmd_store_category_sort($store_details, $instance['orderby'] ?? 'priority', $ascdsc);

		foreach ($store_details as $store) {

			$store = (object)$store;
			$store_custom_fields = $store->term_meta;
			if ($store_custom_fields['status'] == 'inactive') continue;
			$cashback_message_color = get_option('cmdcp_message_color', "#4CA14C");

		?>
			<div class="col-6 col-sm-4 col-md-3 col-lg-2 p-2">
				<a <?php if ($cashbackredirect and (!empty($store_custom_fields['store_url']) or !empty($store_custom_fields['store_aff_url']))) { ?> target='_blank' href="<?= get_bloginfo('url') . '/str/' . $store->term_id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : '') ?>" onclick="cmdShowOffer(event,'<?= $store->slug ?>','<?= '#' . $this->get_field_id('store') . '-' . $store->term_id ?>','<?= $store->name ?>','store');" <?php } else { ?> href="<?= get_term_link($store->term_id) ?>" <?php } ?>>
					<div class="cmd-taxonomy-card card h-100 p-2 rounded-4 shadow-sm">
						<?php if (!empty($store_custom_fields['store_logo'])) { ?>
							<img src="<?php echo $store_custom_fields['store_logo']; ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />
						<?php } elseif($placeholder){
							$image_url = $placeholder;
						} else { ?>
							<img src="<?php echo get_template_directory_uri() . "/inc/assets/images/random-feature.jpg" ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />

						<?php } ?>
						<div class="card-footer text-center py-1 px-0">
							<div class="cmd-store-name fw-bold p-2"><?php echo $store->name ?></div>
							<?php
							$cashback_options = cmd_get_cashback_option();
							if (isset($cashback_options['message'][$store->term_id]) and $cashback_options['message'][$store->term_id] and $cashback == '1') { // cashback?
							?>

								<small style="color: <?= $cashback_message_color ?>"><?php echo stripslashes($cashback_options['message'][$store->term_id]); ?></small>
							<?php } else if ($showcount) { ?>
								<div class="card-footer text-center py-1 px-0"><small><?php echo $store->count ?> <?= ($store->count > 1 ? __('Offers', "clipmydeals") : __('Offer', "clipmydeals")) ?></small></div>
							<?php } ?>
						</div>
					</div>
				</a>
			</div>
		<?php



		}


		echo '</div>';

		echo $args['after_widget'];
		wp_reset_query();
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$store_category = $instance['store_category'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'];
			$link = isset($instance['link']) ? (bool) $instance['link'] : false;
			$cashback = isset($instance['cashback']) ? (bool) $instance['cashback'] : false;
			$popular = isset($instance['popular']) ? (bool) $instance['popular'] : false;
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$store_category = '';
			$orderby  = 'priority';
			$ascdsc  = 'desc';
			$popular = false;
			$link = false;
			$cashback = false;
			$showcount = true;
			$empty = false;
		}
		// The widget form
		?>
		<p>

			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('store_category'); ?>"><?php echo __('Store Category (comma-separated list of store categories):', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('store_category'); ?>" name="<?php echo $this->get_field_name('store_category'); ?>" type="text" value="<?php echo $store_category; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('popular'); ?>" name="<?php echo $this->get_field_name('popular'); ?>" <?php checked($popular); ?> />
			<label for="<?php echo $this->get_field_id('popular'); ?>"><?php _e('Show Only Popular Stores', 'clipmydeals'); ?></label><br />

			<?php if (in_array('clipmydeals-cashback/clipmydeals-cashback.php', get_option('active_plugins'))) { ?>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" <?php checked($link); ?> />
				<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Redirect to Store Website', 'clipmydeals'); ?></label><br />

				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('cashback'); ?>" name="<?php echo $this->get_field_name('cashback'); ?>" <?php checked($cashback); ?> />
				<label for="<?php echo $this->get_field_id('cashback'); ?>"><?php _e('Show Cashback Message', 'clipmydeals'); ?></label><br />
			<?php } ?>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
				<option value="priority" <?php if ($orderby == 'priority') {
												echo 'selected="selected"';
											} ?>>Priority</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['store_category']  = strip_tags($new_instance['store_category']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['popular'] = !empty($new_instance['popular']) ? 1 : 0;
		$instance['link'] = !empty($new_instance['link']) ? 1 : 0;
		$instance['cashback'] = !empty($new_instance['cashback']) ? 1 : 0;
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}

// Storelist Widget
class cmd_Storelist_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'storelist_widget',
			'description' => 'List All Stores',
		);
		parent::__construct('storelist_widget', 'ClipMyDeals Store List', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		global $exclude_stores;
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		$showcount = !empty($instance['count']) ? '1' : '0';
		if (array_key_exists('orderby', $instance) and $instance['orderby'] != 'priority') {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'name';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'asc';
		}
		if (array_key_exists('exclude', $instance)) {
			$exclude = $instance['exclude'];
		} else {
			$exclude = '';
		}
		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];
		$stores = get_terms(array(
			'orderby'            => $orderby,
			'order'              => $ascdsc,
			'hide_empty'         => $inv_empty,
			'exclude'            => $exclude,
			'number'             => null,
			'taxonomy'           => 'stores',
		));
		$store_details = array();
		foreach ($stores as $store) {
			$store_custom_fields = cmd_get_taxonomy_options($store->term_id, 'stores');
			if ($store_custom_fields['status'] == 'inactive') {
				$exclude_stores[] = $store->term_id;
				continue;
			}
			$store_details[] =  array(
				'term_id' => $store->term_id,
				'slug' => $store->slug,
				'name' => $store->name,
				'count' => $store->count,
				'term_meta' => $store_custom_fields,
			);
		}
		if (array_key_exists('orderby', $instance) and $instance['orderby'] == 'priority') {
			$store_details = cmd_store_category_sort($store_details, $instance['orderby'], $ascdsc);
		}
		echo '<div id="cmd-storelist-widget" class="cmd-list-widget list-group">';
		foreach ($store_details as $store) {
			$store = (object)$store;
		?>
			<a href="<?php echo get_term_link($store->term_id); ?>" class="list-group-item d-flex justify-content-between align-items-start"><?php echo $store->name; ?>
				<?php if ($showcount) { ?><span class="badge bg-primary rounded-pill"><?php echo $store->count; ?></span><?php } ?>
			</a>
		<?php
		}
		echo '</div>';
		echo $args['after_widget'];
		wp_reset_query();
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'];
			$exclude = $instance['exclude'];
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$orderby  = 'priority';
			$ascdsc  = 'desc';
			$exclude  = '';
			$showcount = true;
			$empty = false;
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
				<option value="priority" <?php if ($orderby == 'priority') {
												echo 'selected="selected"';
											} ?>>Priority</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>">Exclude (comma-separated list of store IDs to skip)</label><br />
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $exclude; ?>" />
		</p>

	<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['exclude'] = $new_instance['exclude'];
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}


// Categorylist Widget
class cmd_Categorylist_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'categorylist_widget',
			'description' => 'List all Offer Categories',
		);
		parent::__construct('categorylist_widget', 'ClipMyDeals Offer Category List', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		global $exclude_coupons;
		$stores = get_terms('stores', array(
			'hide_empty' => false,
		));
		foreach ($stores as $store) {
			store_taxonomy_status($store->term_id);
		}
		exclude_coupons();
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$show_children = !empty($instance['show_children']) ? '1' : '0';
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		$showcount = !empty($instance['count']) ? '1' : '0';
		if (array_key_exists('orderby', $instance)) {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'count';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'desc';
		}
		if (array_key_exists('exclude', $instance)) {
			$exclude = $instance['exclude'];
		} else {
			$exclude = '';
		}
		if (array_key_exists('childof', $instance)) {
			$childof = $instance['childof'];
		} else {
			$childof = '';
		}
		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];
		$cat_args = array(
			'orderby'            => $orderby,
			'order'              => $ascdsc,
			'hide_empty'         => $inv_empty,
			'child_of'           => $childof,
			'exclude'            => $exclude,
			'number'             => null,
			'taxonomy'           => 'offer_categories',
		);
		if (!$show_children) {
			$cat_args['parent'] = 0;
		}
		$cats = get_terms($cat_args);
		echo '<div class="cmd-list-widget cmd-categorylist-widget list-group">';
		foreach ($cats as $cat) {
			echo '<a href="' . get_term_link($cat) . '" class="list-group-item d-flex justify-content-between align-items-start">' . $cat->name;
			if ($showcount) {
				echo '<span class="badge bg-primary rounded-pill">' . count(query_posts(array('offer_categories' => $cat->slug, 'posts_per_page' => -1, 'post__not_in' => $exclude_coupons))) . '</span>';
			}
			echo '</a>';
		}
		echo '</div>';
		echo $args['after_widget'];
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'];
			$exclude = $instance['exclude'];
			$childof = $instance['childof'];
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$show_children = isset($instance['show_children']) ? (bool) $instance['show_children'] : true;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$orderby  = 'count';
			$ascdsc  = 'desc';
			$exclude  = '';
			$childof  = '';
			$show_children = true;
			$showcount = true;
			$empty = false;
		}
		// The widget form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_children'); ?>" name="<?php echo $this->get_field_name('show_children'); ?>" <?php checked($show_children); ?> />
			<label for="<?php echo $this->get_field_id('show_children'); ?>"><?php _e('Show Sub-Categories', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>">Exclude (comma-separated list of Category IDs to skip)</label><br />
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $exclude; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>">Only Show Children of (Category ID)</label><br />
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('childof'); ?>" value="<?php echo $childof; ?>" />
		</p>

	<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['exclude'] = $new_instance['exclude'];
		$instance['childof'] = $new_instance['childof'];
		$instance['show_children'] = !empty($new_instance['show_children']) ? 1 : 0;
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}

//Brandlist Widget
class cmd_Brandlist_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'brandlist_widget',
			'description' => 'List all Brands',
		);
		parent::__construct('brandlist_widget', 'ClipMyDeals Brand List', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		global $exclude_coupons;
		$stores = get_terms('stores', array(
			'hide_empty' => false,
		));
		foreach ($stores as $store) {
			store_taxonomy_status($store->term_id);
		}
		exclude_coupons();
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		$showcount = !empty($instance['count']) ? '1' : '0';
		if (array_key_exists('orderby', $instance)) {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'count';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'desc';
		}

		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];
		$brand_args = array(
			'orderby'            => $orderby,
			'order'              => $ascdsc,
			'hide_empty'         => $inv_empty,
			'number'             => null,
			'taxonomy'           => 'brands',
		);
		$brands = get_terms($brand_args);
		echo '<div class="cmd-list-widget cmd-brandlist-widget list-group">';
		foreach ($brands as $brand) {
			echo '<a href="' . get_term_link($brand) . '" class="list-group-item d-flex justify-content-between align-items-start">' . $brand->name;
			if ($showcount) {
				echo '<span class="badge bg-primary rounded-pill">' . count(query_posts(array('brands' => $brand->slug, 'posts_per_page' => -1, 'post__not_in' => $exclude_coupons))) . '</span>';
			}
			echo '</a>';
		}
		echo '</div>';
		echo $args['after_widget'];
		wp_reset_query();
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'] ?? 'desc';
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$orderby  = 'count';
			$ascdsc  = 'desc';
			$showcount = true;
			$empty = false;
		}
		// The widget form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

	<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}

// Locationlist Widget
class cmd_Locationlist_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'locationlist_widget',
			'description' => 'List all Locations',
		);
		parent::__construct('locationlist_widget', 'ClipMyDeals Location List', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		global $exclude_coupons;
		$stores = get_terms('stores', array(
			'hide_empty' => false,
		));
		foreach ($stores as $store) {
			store_taxonomy_status($store->term_id);
		}
		exclude_coupons();
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$show_children = !empty($instance['show_children']) ? '1' : '0';
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		$showcount = !empty($instance['count']) ? '1' : '0';
		if (array_key_exists('orderby', $instance)) {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'count';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'desc';
		}
		if (array_key_exists('exclude', $instance)) {
			$exclude = $instance['exclude'];
		} else {
			$exclude = '';
		}
		if (array_key_exists('childof', $instance)) {
			$childof = $instance['childof'];
		} else {
			$childof = '';
		}
		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];
		$cat_args = array(
			'orderby'            => $orderby,
			'order'              => $ascdsc,
			'hide_empty'         => $inv_empty,
			'child_of'           => $childof,
			'exclude'            => $exclude,
			'number'             => null,
			'taxonomy'           => 'locations',
		);
		if (!$show_children) {
			$cat_args['parent'] = 0;
		}
		$cats = get_terms($cat_args);
		echo '<div class="cmd-list-widget cmd-locationlist-widget list-group">';
		foreach ($cats as $cat) {
			echo '<a href="' . get_term_link($cat) . '" class="list-group-item d-flex justify-content-between align-items-start">' . $cat->name;
			if ($showcount) {
				echo '<span class="badge bg-primary rounded-pill">' . count(query_posts(array('locations' => $cat->slug, 'posts_per_page' => -1, 'post__not_in' => $exclude_coupons))) . '</span>';
			}
			echo '</a>';
		}
		echo '</div>';
		echo $args['after_widget'];
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'];
			$exclude = $instance['exclude'];
			$childof = $instance['childof'];
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$show_children = isset($instance['show_children']) ? (bool) $instance['show_children'] : true;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$orderby  = 'count';
			$ascdsc  = 'desc';
			$exclude  = '';
			$childof  = '';
			$show_children = true;
			$showcount = true;
			$empty = false;
		}
		// The widget form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_children'); ?>" name="<?php echo $this->get_field_name('show_children'); ?>" <?php checked($show_children); ?> />
			<label for="<?php echo $this->get_field_id('show_children'); ?>"><?php _e('Show Sub-Locations', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>">Exclude (comma-separated list of Location IDs to skip)</label><br />
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $exclude; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>">Only Show Children of (Location ID)</label><br />
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('childof'); ?>" value="<?php echo $childof; ?>" />
		</p>

	<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['exclude'] = $new_instance['exclude'];
		$instance['childof'] = $new_instance['childof'];
		$instance['show_children'] = !empty($new_instance['show_children']) ? 1 : 0;
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}


// Advance Search Widget
class cmd_AdvanceSearch_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'advancesearch_widget',
			'description' => 'Search Coupons and Deals',
		);
		parent::__construct('advancesearch_widget', 'ClipMyDeals Adv. Search', $widget_options);
	}

	public function widget($args, $instance)
	{
		global $exclude_stores;
		echo $args['before_widget'];

		$parameters = array_merge(array('s' => '', 'store' => 0, 'offer-category' => 0, 'brand' => 0, 'location' => 0), $_GET);
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		if ($title) echo $args['before_title'] . $title . $args['after_title'];

	?>
		<form role="search" method="get" class="search-form form-inline" action="<?php echo esc_url(home_url('/')); ?>">
			<div class="row w-100 mx-0 my-3">

				<div class="col-sm-12 my-1">
					<input class="form-control w-100" <?= get_theme_mod('ajax_search', 'no') == 'yes' ? 'onkeyup="cmdAjaxSearch(this);"' : '' ?> autocomplete="off" type="text" name="s" id="v-search" placeholder="<?= __("Search", 'clipmydeals') ?>..." value="<?php echo $parameters['s']; ?>" />
				</div>

				<div class="col-sm-12 my-1">
					<?php $terms = get_terms('stores', array('hide_empty' => false,));
					foreach ($terms as $term) {
						store_taxonomy_status($term->term_id);
					}

					wp_dropdown_categories(array(
						'name'               => 'store',
						'id'                 => 'store',
						'class'              => 'form-control w-100',
						'taxonomy'           => 'stores',
						'exclude'			 => $exclude_stores,
						'orderby'            => 'name',
						'order'              => 'ASC',
						'hide_if_empty'      => true,
						'value_field'	     => 'slug',
						'selected'	=>	$parameters['store'],
						'show_option_all'    => __('Any Store', 'clipmydeals'),
						'hide_empty'         => 1,
						'hierarchical'       => 1,
						'depth'              => 2,
					));
					?>
				</div>

				<div class="col-sm-12 my-1">
					<?php
					wp_dropdown_categories(array(
						'name'               => 'offer-category',
						'id'                 => 'offer-category',
						'class'              => 'form-control w-100',
						'taxonomy'           => 'offer_categories',
						'orderby'            => 'name',
						'order'              => 'ASC',
						'hide_if_empty'      => true,
						'value_field'	     => 'slug',
						'selected'	=> $parameters['offer-category'],
						'show_option_all'    => __('Any Category', 'clipmydeals'),
						'hide_empty'         => 1,
						'hierarchical'       => 1,
						'depth'              => 2,
					));
					?>
				</div>

				<?php if (get_theme_mod('location_taxonomy', false)) { ?>
					<div class="col-sm-12 my-1">
						<?php
						wp_dropdown_categories(array(
							'name'               => 'location',
							'id'                 => 'location',
							'class'              => 'form-control w-100',
							'taxonomy'           => 'locations',
							'orderby'            => 'name',
							'order'              => 'ASC',
							'hide_if_empty'      => true,
							'value_field'	     => 'slug',
							'selected'	=> $parameters['location'],
							'show_option_all'    => __('Any Location', 'clipmydeals'),
							'hide_empty'         => 1,
							'hierarchical'       => 1,
							'depth'              => 2,
						));
						?>
					</div>
				<?php } ?>

				<div class="col-sm-12 my-1">
					<button type="submit" class="btn btn-primary btn-block"><?= __("Search", 'clipmydeals') ?> <i class="fa fa-search"></i></button>
				</div>

			</div>
		</form>
	<?php

		echo $args['after_widget'];
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
		} else {
			$title  = '';
		}
		// The widget form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']  = strip_tags($new_instance['title']);
		return $instance;
	}
}


// Store Location Widget
class cmd_StoreLocation_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'storelocation_widget',
			'description' => 'Display Store on Map',
		);
		parent::__construct('storelocation_widget', 'ClipMyDeals Map Location', $widget_options);
	}

	public function widget($args, $instance)
	{
		global $exclude_stores;
		if (is_tax("stores") or is_tax("locations")) {
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$store_custom_fields = cmd_get_taxonomy_options($term->term_id, get_query_var('taxonomy'));
			$status = isset($store_custom_fields['status']) ? ($store_custom_fields['status'] != "inactive" ? true : false) : true;
			if (!empty($store_custom_fields['map']) and $status) {
				echo $args['before_widget'];
				if (array_key_exists('title', $instance)) {
					$title = apply_filters('widget_title', $instance['title']);
				} else {
					$title = '';
				}
				if ($title) echo $args['before_title'] . $title . $args['after_title'];
		?>
				<div class="embed-responsive embed-responsive-4by3 w-100 p-0">
					<?php echo stripslashes($store_custom_fields['map']); ?>
				</div>
		<?php
				echo $args['after_widget'];
			} else {
				$exclude_stores[] = $term->term_id;
			}
		}
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
		} else {
			$title  = '';
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']  = strip_tags($new_instance['title']);
		return $instance;
	}
}


// Store Video Widget
class cmd_StoreVideo_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'storevideo_widget',
			'description' => 'Display Store Video',
		);
		parent::__construct('storevideo_widget', 'ClipMyDeals Store Video', $widget_options);
	}

	public function widget($args, $instance)
	{
		global $exclude_stores;
		if (is_tax("stores")) {
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$store_custom_fields = cmd_get_taxonomy_options($term->term_id, get_query_var('taxonomy'));
			if (!empty($store_custom_fields['video']) and $store_custom_fields['status'] != "inactive") {
				echo $args['before_widget'];
				if (array_key_exists('title', $instance)) {
					$title = apply_filters('widget_title', $instance['title']);
				} else {
					$title = '';
				}
				if ($title) echo $args['before_title'] . $title . $args['after_title'];
		?>
				<div class="embed-responsive embed-responsive-16by9 w-100 p-0">
					<?php echo stripslashes($store_custom_fields['video']); ?>
				</div>
		<?php
				echo $args['after_widget'];
			} else {
				$exclude_stores[] = $term->term_id;
			}
		}
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
		} else {
			$title  = '';
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']  = strip_tags($new_instance['title']);
		return $instance;
	}
}

// Brand Location Widget
class cmd_BrandLocation_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'brandlocation_widget',
			'description' => 'Display Brand on Map',
		);
		parent::__construct('brandlocation_widget', 'ClipMyDeals Brand Map Location', $widget_options);
	}

	public function widget($args, $instance)
	{
		if (is_tax("brands")) {
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$brand_custom_fields = cmd_get_taxonomy_options($term->term_id, get_query_var('taxonomy'));
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

			if (!empty($brand_custom_fields['brand_map']) and str_contains($actual_link, $term->slug)) {
				echo $args['before_widget'];
				if (array_key_exists('title', $instance)) {
					$title = apply_filters('widget_title', $instance['title']);
				} else {
					$title = '';
				}
				if ($title) echo $args['before_title'] . $title . $args['after_title'];
		?>
				<div class="embed-responsive embed-responsive-4by3 w-100 p-0">
					<?php echo stripslashes($brand_custom_fields['brand_map']); ?>
				</div>
		<?php
				echo $args['after_widget'];
			}
		}
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
		} else {
			$title  = '';
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']  = strip_tags($new_instance['title']);
		return $instance;
	}
}


// Brand Video Widget
class cmd_BrandVideo_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'brandvideo_widget',
			'description' => 'Display Store Video',
		);
		parent::__construct('brandvideo_widget', 'ClipMyDeals Brand Video', $widget_options);
	}

	public function widget($args, $instance)
	{
		if (is_tax("brands")) {
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$brand_custom_fields = cmd_get_taxonomy_options($term->term_id, get_query_var('taxonomy'));
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

			if (!empty($brand_custom_fields['brand_video']) and str_contains($actual_link, $term->slug)) {
				echo $args['before_widget'];
				if (array_key_exists('title', $instance)) {
					$title = apply_filters('widget_title', $instance['title']);
				} else {
					$title = '';
				}
				if ($title) echo $args['before_title'] . $title . $args['after_title'];
		?>
				<div class="embed-responsive embed-responsive-16by9 w-100 p-0">
					<?php echo stripslashes($brand_custom_fields['brand_video']); ?>
				</div>
		<?php
				echo $args['after_widget'];
			}
		}
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
		} else {
			$title  = '';
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>
	<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']  = strip_tags($new_instance['title']);
		return $instance;
	}
}

// FeaturedOffers Widget
class cmd_FeaturedOffers_Widget extends WP_Widget
{


	public function __construct()
	{
		$widget_options = array(
			'classname' => 'featuredoffers_widget',
			'description' => 'List Featured Offers',
		);
		parent::__construct('featuredoffers_widget', 'Homepage: ClipMyDeals Featured Offers List ', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		global $wp_query; // This is the original query that gets POSTS.
		global $store_status;

		$title 	 = array_key_exists('title', $instance)	? apply_filters('widget_title', $instance['title']) : '';
		$count 	 = array_key_exists('count', $instance)	? $instance['count'] : 10;
		$orderkey = array_key_exists('orderkey', $instance)	? $instance['orderkey'] : 'cmd_display_priority';
		$ascdsc	 = array_key_exists('ascdsc', $instance)	? $instance['ascdsc'] : 'desc';
		$include_store = array_key_exists('include_store', $instance) ? $instance['include_store'] : '';
		$include_category = array_key_exists('include_category', $instance) ? $instance['include_category'] : '';
		$include_location = array_key_exists('include_location', $instance) ? $instance['include_location'] : '';
		$include_brand = array_key_exists('include_brand', $instance) ? $instance['include_brand'] : '';
		$include_deals = !empty($instance['include_deals']) ? 1 : 0;
		$include_codes = !empty($instance['include_codes']) ? 1 : 0;
		$include_print = !empty($instance['include_print']) ? 1 : 0;
		$include_products = (in_array('clipmydeals-comparison/clipmydeals-comparison.php', get_option('active_plugins')) and !empty($instance['include_products'])) ? 1 : 0;
		$id = str_replace('REPLACE_TO_ID', uniqid(), $this->id);
		$layout_options = clipmydeals_layout_options();

		$orderby = ($orderkey == 'cmd_display_priority') ? 'meta_value_num' : 'meta_value';

		$offer_args = array(
			'post_type' => $include_products ? array('products', 'coupons') : array('coupons'),
			'orderby' => $orderby,
			'meta_key' => $orderkey,
			'order' => $ascdsc,
			'posts_per_page' => $count,
			'meta_query' => array(
				'relation' => 'AND',
				array('key' => 'cmd_start_date', 'value' => current_time('Y-m-d'), 'compare' => '<='),
				array(
					'relation' => 'OR',
					array('key' => 'cmd_valid_till',	'value' => current_time('Y-m-d'),	'compare' => '>='),
					array('key' => 'cmd_valid_till',	'value' => '', 						'compare' => '='),
				),
			),
		);
		$type_selection = array();
		if ($include_deals)		$type_selection[] = array('key' => 'cmd_type',	'value' => 'deal',	'compare' => '=');
		if ($include_codes)		$type_selection[] = array('key' => 'cmd_type',	'value' => 'code',	'compare' => '=');
		if ($include_print) 		$type_selection[] = array('key' => 'cmd_type',	'value' => 'print',	'compare' => '=');
		if ($include_products)	$type_selection[] = array('key' => 'cmd_original_price',	'value' => '0',	'compare' => '>=');

		// More than one of the above was added. So need to add the relation=>AND
		if (count($type_selection) > 1) $type_selection['relation'] =  'OR';
		$offer_args['meta_query'][] = $type_selection;

		if (!empty($include_store))    $offer_args['tax_query'][] = array('taxonomy' => 'stores',           'field' => 'slug', 'terms' => explode(',', $include_store));
		if (!empty($include_category)) $offer_args['tax_query'][] = array('taxonomy' => 'offer_categories', 'field' => 'slug', 'terms' => explode(',', $include_category));
		if (!empty($include_location)) $offer_args['tax_query'][] = array('taxonomy' => 'locations',        'field' => 'slug', 'terms' => explode(',', $include_location));
		if (!empty($include_brand)) $offer_args['tax_query'][] = array('taxonomy' => 'brands',        'field' => 'slug', 'terms' => explode(',', $include_brand));

		// More than one of the above was added. So need to add the relation=>AND
		if (isset($offer_args['tax_query']) and count($offer_args['tax_query']) > 1) $offer_args['tax_query']['relation'] =  'AND';

		query_posts($offer_args);

		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title']; ?>
		<!--/.Slides  -->
		<div id="<?= $id ?>-carousel" class="cmd-featuredoffers-widget row mx-auto py-4 flex-nowrap overflow-x-scroll cmd-slider-widget position-relative">
			<?php
			$count = 0;
			while (have_posts()) {
				the_post();
				$store_terms = get_the_terms(get_the_ID(), 'stores');
				$price_list = get_post_meta(get_the_ID(), 'cmdcomp_price_list', true);
				if (!$store_terms and get_post_type() == 'products' and empty($price_list) and cmdcomp_get_plugin_mod('show_out_of_stock', 'yes') != "yes") continue;
				store_taxonomy_status($store_terms[0]->term_id);
				if ($store_status[$store_terms[0]->term_id] == 'inactive' and (get_post_type() == 'coupons' or (get_post_type() == 'products' and count(get_post_meta(get_the_ID(), 'cmdcomp_price_list', true)) <= 1))) continue;
			?>
				<div id="<?= substr_replace(get_post_type(), "", -1) ?>-carousel-<?= get_the_ID(); ?>" class="py-2 px-1 px-md-2 rounded-4 <?= ($layout_options['sidebar']) ? ' col-6 col-sm-4 col-md-5 col-lg-4 col-xl-3 small-screen ' : 'col-6 col-sm-4 col-lg-3 latge-screen ' ?> <?= $count == 0 ? 'active' : '' ?>">
					<div class="card h-100 border-0 rounded-4 shadow-sm">
						<?php get_template_part('template-parts/' . substr_replace(get_post_type(), "", -1), 'carousel'); ?>
					</div>
				</div>
			<?php
				$count++;
			}
			?>
		</div>
		<?php
		if ($include_products) {
			query_posts($offer_args);
			while (have_posts()) {
				the_post();
				if (get_post_type() == 'products')
					get_template_part('template-parts/price', 'modal', array('parent' => 'carousel'));
			}
		}
		?>
		<!--/.Slides  -->

	<?php
		echo $args['after_widget'];

		wp_reset_query();
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderkey = $instance['orderkey'];
			$ascdsc = $instance['ascdsc'];
			$include_store = $instance['include_store'];
			$include_category = $instance['include_category'];
			$include_location = $instance['include_location'];
			$include_brand = $instance['include_brand'] ?? '';
			$include_deals = $instance['include_deals'];
			$include_codes = $instance['include_codes'];
			$include_print = $instance['include_print'];
			$include_products = $instance['include_products'] ?? '';
			$count = $instance['count'];
		} else {
			$title  = '';
			$orderkey  = 'cmd_display_priority';
			$ascdsc  = 'desc';
			$include_store  = '';
			$include_category  = '';
			$include_location  = '';
			$include_brand  = '';
			$include_deals = 1;
			$include_codes = 1;
			$include_print = 1;
			$include_products = 0;
			$count = 10;
		}
		// The widget form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p id="<?php echo $this->get_field_id('include_cmd_type'); ?>">
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('include_deals'); ?>" name="<?php echo $this->get_field_name('include_deals'); ?>" <?php checked($include_deals); ?> />
			<label for="<?php echo $this->get_field_id('include_deals'); ?>"><?php _e('Deals', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('include_codes'); ?>" name="<?php echo $this->get_field_name('include_codes'); ?>" <?php checked($include_codes); ?> />
			<label for="<?php echo $this->get_field_id('include_codes'); ?>"><?php _e('Codes', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('include_print'); ?>" name="<?php echo $this->get_field_name('include_print'); ?>" <?php checked($include_print); ?> />
			<label for="<?php echo $this->get_field_id('include_print'); ?>"><?php _e('Printable Vouchers', 'clipmydeals'); ?></label><br />
			<?php if (in_array('clipmydeals-comparison/clipmydeals-comparison.php', get_option('active_plugins'))) { ?>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('include_products'); ?>" name="<?php echo $this->get_field_name('include_products'); ?>" <?php checked($include_products); ?> />
				<label for="<?php echo $this->get_field_id('include_products'); ?>"><?php _e('Products', 'clipmydeals'); ?></label><br />
			<?php } ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php echo __('Count:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" class="widefat" type="number" value="<?php echo $count; ?>" min="0" max="24" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderkey'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderkey'); ?>" id="<?php echo $this->get_field_id('orderkey'); ?>" class="widefat">
				<option value="cmd_start_date" <?php if ($orderkey == 'cmd_start_date') {
													echo 'selected="selected"';
												} ?>>Start Date</option>
				<option value="cmd_valid_till" <?php if ($orderkey == 'cmd_valid_till') {
													echo 'selected="selected"';
												} ?>>End Date</option>
				<option value="cmd_display_priority" <?php if ($orderkey == 'cmd_display_priority') {
															echo 'selected="selected"';
														} ?>>Display Priority</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('include_store'); ?>"><?= __('INCLUDE STORE(comma-separated list of store slug)', 'clipmydeals'); ?></label><br />
			<input id="<?php echo $this->get_field_id('include_store'); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name('include_store'); ?>" value="<?php echo $include_store; ?>" />

			<label for="<?php echo $this->get_field_id('include_category'); ?>"><?= __('INCLUDE CATEGORY (comma-separated list of category slug)', 'clipmydeals'); ?></label><br />
			<input id="<?php echo $this->get_field_id('include_category	'); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name('include_category'); ?>" value="<?php echo $include_category; ?>" />

			<label id="<?php echo $this->get_field_id('include_location'); ?>-label" for="<?php echo $this->get_field_id('include_location'); ?>"><?= __('INCLUDE LOCATION (comma-separated list of location slug)', 'clipmydeals'); ?></label><br />
			<input id="<?php echo $this->get_field_id('include_location'); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name('include_location'); ?>" value="<?php echo $include_location; ?>" />

			<label id="<?php echo $this->get_field_id('include_brand'); ?>-label" for="<?php echo $this->get_field_id('include_brand'); ?>"><?= __('INCLUDE BRAND (comma-separated list of brand slug)', 'clipmydeals'); ?></label><br />
			<input id="<?php echo $this->get_field_id('include_brand'); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name('include_brand'); ?>" value="<?php echo $include_brand; ?>" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderkey'] = $new_instance['orderkey'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['include_store'] = $new_instance['include_store'];
		$instance['include_category'] = $new_instance['include_category'];
		$instance['include_location'] = $new_instance['include_location'];
		$instance['include_brand'] = $new_instance['include_brand'];
		$instance['include_deals'] = !empty($new_instance['include_deals']) ? 1 : 0;;
		$instance['include_codes'] = !empty($new_instance['include_codes']) ? 1 : 0;;
		$instance['include_print'] = !empty($new_instance['include_print']) ? 1 : 0;;
		$instance['include_products'] = !empty($new_instance['include_products']) ? 1 : 0;;
		$instance['count'] = $new_instance['count'];

		return $instance;
	}
}

// Homepage PopularStores Widget
class cmd_Homepage_PopularStores_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'homepage_popularstores_widget',
			'description' => 'Homepage Popular Stores',
		);
		parent::__construct('homepage_popularstores_widget', 'Homepage: ClipMyDeals Popular Stores', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
$placeholder = get_theme_mod('default_store_image');
		
		global $exclude_stores;
		if (array_key_exists('title', $instance)) {
			$title = apply_filters('widget_title', $instance['title']);
		} else {
			$title = '';
		}
		$showcount = !empty($instance['count']) ? '1' : '0';
		$inv_empty = !empty($instance['empty']) ? '0' : '1'; // invert to go from UI's "show empty" to WP's "hide empty"
		$cashbackredirect = !empty($instance['link']) ? '1' : '0';
		if (array_key_exists('orderby', $instance) and  $instance['orderby'] != 'priority') {
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'name';
		}
		if (array_key_exists('ascdsc', $instance)) {
			$ascdsc = $instance['ascdsc'];
		} else {
			$ascdsc = 'asc';
		}
		// Output
		echo $args['before_widget'];
		if ($title) echo $args['before_title'] . $title . $args['after_title'];

		echo '<div class="row justify-content-center p-1 px-2">';
		$stores = get_terms(array(
			'taxonomy' => 'stores',
			'hide_empty'	=> $inv_empty,
			'orderby'	=> $orderby,
			'order'	=> $ascdsc,
		));
		$store_details = array();
		foreach ($stores as $store) {
			$store_custom_fields = cmd_get_taxonomy_options($store->term_id, 'stores');
			if ($store_custom_fields['status'] == "inactive") {
				$exclude_stores[] = $store->term_id;
				continue;
			}
			if ($store_custom_fields['popular'] == 'yes') {
				$store_details[] =  array(
					'term_id' => $store->term_id,
					'name' => $store->name,
					'slug' => $store->slug,
					'count' => $store->count,
					'term_meta' => $store_custom_fields,
				);
			}
		}

		if (array_key_exists('orderby', $instance) and $instance['orderby'] == 'priority') {
			$store_details = cmd_store_category_sort($store_details, $instance['orderby'], $ascdsc);
		}
		foreach ($store_details as $store) {
			$store = (object)$store;
			$store_custom_fields = $store->term_meta;
			$cashback_message_color = get_option('cmdcp_message_color', "#4CA14C");

		?>
			<div id="<?= $this->get_field_id('store') . '-' . $store->term_id ?>" class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 p-2">
				<a <?php if ($cashbackredirect and (!empty($store_custom_fields['store_url']) or !empty($store_custom_fields['store_aff_url']))) { ?> target='_blank' href="<?= get_bloginfo('url') . '/str/' . $store->term_id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : '') ?>" onclick="cmdShowOffer(event,'<?= $store->slug ?>','<?= '#' . $this->get_field_id('store') . '-' . $store->term_id ?>','<?= $store->name ?>','store');" <?php } else { ?> href="<?= get_term_link($store->term_id) ?>" <?php } ?>>
					<div class="cmd-taxonomy-card card h-100 p-2 rounded-4 shadow-sm">
						<?php if (!empty($store_custom_fields['store_logo'])) { ?>
							<img src="<?php echo $store_custom_fields['store_logo']; ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />
						<?php } elseif($placeholder){ ?>
							<img src="<?php echo $placeholder ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />
					<?php	} else { ?>
							<img src="<?php echo get_template_directory_uri() . "/inc/assets/images/random-feature.jpg" ?>" class="card-img-top cmd-store-logo-fix-height rounded" alt="<?php echo $store->name; ?> Logo" />
						<?php } ?>
						<div class="card-footer text-center py-1 px-0">
							<div class="cmd-store-name fw-bold p-2"><?php echo $store->name ?></div>
							<?php if ($showcount) {
								$cashback_options = cmd_get_cashback_option();
								if (isset($cashback_options['message'][$store->term_id]) and $cashback_options['message'][$store->term_id]) { // cashback?
							?>
									<small style="color: <?= $cashback_message_color ?>"><?php echo stripslashes($cashback_options['message'][$store->term_id]); ?></small>
								<?php } else { ?>
									<small><?php echo $store->count ?> <?php _e('Offers', 'clipmydeals'); ?></small>
							<?php }
							} ?>
						</div>
					</div>
				</a>
			</div>
		<?php

		}
		echo '</div>';

		echo $args['after_widget'];
	}

	public function form($instance)
	{
		if ($instance) {
			$title  = $instance['title'];
			$orderby = $instance['orderby'];
			$ascdsc = $instance['ascdsc'];
			$link = isset($instance['link']) ? (bool) $instance['link'] : false;
			$showcount = isset($instance['count']) ? (bool) $instance['count'] : false;
			$empty = isset($instance['empty']) ? (bool) $instance['empty'] : false;
		} else {
			$title  = '';
			$orderby  = 'priority';
			$ascdsc  = 'desc';
			$link = false;
			$showcount = false;
			$empty = false;
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" <?php checked($showcount); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show Offer Counts/ Cashback Message', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" <?php checked($empty); ?> />
			<label for="<?php echo $this->get_field_id('empty'); ?>"><?php _e('Show Empty Terms', 'clipmydeals'); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" <?php checked($link); ?> />
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Redirect to Store Website', 'clipmydeals'); ?></label><br />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __('Order By:', 'clipmydeals'); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="ID" <?php if ($orderby == 'ID') {
										echo 'selected="selected"';
									} ?>>ID</option>
				<option value="name" <?php if ($orderby == 'name') {
											echo 'selected="selected"';
										} ?>>Name</option>
				<option value="slug" <?php if ($orderby == 'slug') {
											echo 'selected="selected"';
										} ?>>Slug</option>
				<option value="count" <?php if ($orderby == 'count') {
											echo 'selected="selected"';
										} ?>>Count</option>
				<option value="priority" <?php if ($orderby == 'priority') {
												echo 'selected="selected"';
											} ?>>Priority</option>
			</select>
		</p>

		<p>
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if ($ascdsc == 'asc') {
																												echo 'checked';
																											} ?> /> Ascending</label><br />
			<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if ($ascdsc == 'desc') {
																												echo 'checked';
																											} ?> /> Descending</label>
		</p>

	<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags($new_instance['title']);
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['link'] = !empty($new_instance['link']) ? 1 : 0;
		$instance['empty'] = !empty($new_instance['empty']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;

		return $instance;
	}
}


// Banner Images Widget
class cmd_BannerImages_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_options = array(
			'classname' => 'bannerimages_widget',
			'description' => 'Banner Images',
		);
		parent::__construct('bannerimages_widget', 'ClipMyDeals Banner Images', $widget_options);
	}

	public function widget($args, $instance)
	{
		// Widget options
		global $wp_query; // This is the original query that gets POSTS.

		$title = array_key_exists('title', $instance) ? apply_filters('widget_title', $instance['title']) : '';
		$show_devices = array_key_exists('show_devices', $instance) ? $instance['show_devices'] : 'all';
		$image_url = array_key_exists('image_url', $instance) ? $instance['image_url'] : array('', '', '');
		$landing_url = array_key_exists('landing_url', $instance) ? $instance['landing_url'] : array('', '', '');
		$alt_text = array_key_exists('alt_text', $instance) ? $instance['alt_text'] : array('', '', '');
		$view_class = ($show_devices == 'mobile' ? 'd-block d-md-none' : (($show_devices == 'desktop') ? 'd-none d-md-block' : 'd-block'));

		// Output
	?>
		<div class="<?= $view_class ?>">
			<?= $args['before_widget']; ?>
			<?= $title ? $args['before_title'] . $title . $args['after_title'] : ''; ?>
			<div id="cmd-bannerimages-widget" class="list-group">
				<div class="row">
					<?php $count = 0;
					$innerHTML = "";
					for ($i = 0; $i < 3; $i++) {
						if (!empty($image_url[$i])) {
							$innerHTML .= '<a class="col-md-__i__" href="' . $landing_url[$i] . '" target="_blank">' .
								'<img class="card-img" src="' . $image_url[$i] . '" alt="' . $alt_text[$i] . '">' .
								'</a>';
							$count++;
						}
					}
					if ($count > 0) echo str_replace('__i__', (12 / $count), $innerHTML);
					?>
				</div>
			</div>
			<?= $args['after_widget']; ?>
		</div>
	<?php
		wp_reset_query();
	}

	public function form($instance)
	{
		if ($instance) {
			$title = $instance['title'];
			$show_devices = isset($instance['show_devices']) ? $instance['show_devices'] : 'all';
			$image_url = $instance['image_url'];
			$landing_url = $instance['landing_url'];
			$alt_text = $instance['alt_text'];
		} else {
			$title = '';
			$show_devices = 'all';
			$image_url = array('', '', '');
			$landing_url = array('', '', '');
			$alt_text = array('', '', '');
		}
		// The widget form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title:', 'clipmydeals'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>

		<label for="<?php echo $this->get_field_id('show_devices'); ?>"><?php echo __('Show Banner on :', 'clipmydeals'); ?></label>
		<select name="<?php echo $this->get_field_name('show_devices'); ?>" id="<?php echo $this->get_field_id('show_devices'); ?>" class="widefat">
			<option value="mobile" <?php if ($show_devices == 'mobile') {
										echo 'selected="selected"';
									} ?>>Mobile</option>
			<option value="desktop" <?php if ($show_devices == 'desktop') {
										echo 'selected="selected"';
									} ?>>Desktop</option>
			<option value="all" <?php if ($show_devices == 'all') {
									echo 'selected="selected"';
								} ?>>All Devices</option>
		</select>

		<?php for ($i = 0; $i < 3; $i++) { ?>
			<p>
				<strong><?php echo __('Image:', 'clipmydeals'); ?> <?= ($i + 1) ?></label></strong></br>

				<label for="<?php echo $this->get_field_id('image_url[' . $i . ']'); ?>"><?= __('Image URL', 'clipmydeals'); ?></label><br />
				<input id="<?php echo $this->get_field_id('image_url[' . $i . ']'); ?>" type="url" class="widefat" name="<?php echo $this->get_field_name('image_url[' . $i . ']'); ?>" value="<?php echo $image_url[$i]; ?>" />

				<label for="<?php echo $this->get_field_id('landing_url[' . $i . ']'); ?>"><?= __('Landing Page URL', 'clipmydeals'); ?></label><br />
				<input id="<?php echo $this->get_field_id('landing_url[' . $i . ']'); ?>" type="url" class="widefat" name="<?php echo $this->get_field_name('landing_url[' . $i . ']'); ?>" value="<?php echo $landing_url[$i]; ?>" />

				<label for="<?php echo $this->get_field_id('alt_text[' . $i . ']'); ?>"><?= __('Alternative Text', 'clipmydeals'); ?></label><br />
				<input id="<?php echo $this->get_field_id('alt_text[' . $i . ']'); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name('alt_text[' . $i . ']'); ?>" value="<?php echo $alt_text[$i]; ?>" />
			</p>
		<?php } ?>

<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_devices'] = $new_instance['show_devices'];
		$instance['image_url'] = $new_instance['image_url'];
		$instance['landing_url'] = $new_instance['landing_url'];
		$instance['alt_text'] = $new_instance['alt_text'];

		return $instance;
	}
}


// Register Widgets
function cmd_register_widgets()
{
	register_widget('cmd_PopularStores_Widget');
	register_widget('cmd_AllBrands_Widget');
	register_widget('cmd_StoreCategory_Widget');
	register_widget('cmd_Storelist_Widget');
	register_widget('cmd_Categorylist_Widget');
	register_widget('cmd_Brandlist_Widget');
	if (get_theme_mod('location_taxonomy', false)) register_widget('cmd_Locationlist_Widget');
	register_widget('cmd_AdvanceSearch_Widget');
	register_widget('cmd_StoreLocation_Widget');
	register_widget('cmd_StoreVideo_Widget');
	register_widget('cmd_BrandLocation_Widget');
	register_widget('cmd_BrandVideo_Widget');
	register_widget('cmd_FeaturedOffers_Widget');
	register_widget('cmd_Homepage_PopularStores_Widget');
	register_widget('cmd_bannerimages_Widget');

	register_sidebar(array(
		'name'          => esc_html__('Footer 4', 'clipmydeals'),
		'id'            => 'footer-4',
		'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Homepage Widget', 'clipmydeals'),
		'id'            => 'homepage-widget',
		'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
		'before_widget' => '<section id="%1$s" class="mb-5 %2$s homepage-before-widget">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title homepage-widget-title text-center text-uppercase">',
		'after_title'   => '</h2>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Coupon Popup', 'clipmydeals'),
		'id'            => 'popup',
		'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
		'before_widget' => '<section id="%1$s" class="w-100 widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Login Popup - Above Form', 'clipmydeals'),
		'id'            => 'login-above',
		'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
		'before_widget' => '<section id="%1$s" class="w-100 m-0 widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Login Popup - Below Form', 'clipmydeals'),
		'id'            => 'login-below',
		'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
		'before_widget' => '<section id="%1$s" class="w-100 m-0 widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
}
add_action('widgets_init', 'cmd_register_widgets');
