	<?php

	/**
	 * Template part for displaying coupons in a popup
	 *
	 * @link https://codex.wordpress.org/Template_Hierarchy
	 *
	 * @package ClipMyDeals
	 */


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

	if (empty($_GET['showCoupon'])) return;

	$id = $_GET['showCoupon'];
	$post = get_post($id);

	$store_terms = get_the_terms($id, 'stores');
	$store_name = $store_terms ? $store_terms[0]->name : '';
	$store_slug = $store_terms ? $store_terms[0]->slug : '';
	$store_term_id = $store_terms ? $store_terms[0]->term_id : 0;
	$store_custom_fields = cmd_get_taxonomy_options($store_term_id, 'stores');
	$coupon_class = current_time('Y-m-d') > get_post_meta($id, 'cmd_valid_till', true) ? 'expired-coupon' : 'active-coupon';
	$coupon_description = get_theme_mod('coupon_default_description', 'Enjoy big savings with this voucher. Don’t miss out, valid for limited period only.');
	$placeholder = get_theme_mod('default_coupon_image');

	
	?>


	<div class="modal fade p-3" style="z-index:100000" tabindex="-1" role="dialog" id="showCouponModal" aria-hidden="true" data-backdrop="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  m-0 h-100 mx-auto rounded-4" role="document">
			<!-- <div class="mx-auto"> -->
			<!-- Coupon Description -->
			<div class="modal-content rounded-4 h-100 bg-transparent border-0 <?= is_active_sidebar('popup') ? '' : 'cmd-popup-widget' ?> <?= $coupon_class ?>">
				<!-- Coupon Box in mobile -->
				<div id="coupon-modal-<?= $id ?>" <?php post_class("  card cmd-grid-mobile-layout d-md-none w-75 mx-auto rounded-4 overflow-hidden {$coupon_class} " . get_post_meta($id, 'cmd_type', true)); ?>>
					<?php
					if (has_post_thumbnail($id)) {
						$coupon_image_url = get_the_post_thumbnail_url($id, 'post-thumbnail');
					} elseif (!empty(get_post_meta($id, 'cmd_image_url', true))) {
						$coupon_image_url = get_post_meta($id, 'cmd_image_url', true);
					} elseif ($placeholder) {
						$coupon_image_url = $placeholder;
					} else {
						$coupon_image_url = get_template_directory_uri() . '/inc/assets/images/coupon-placeholder.png';
					}
					?>
					<img loading="lazy" src="<?= $coupon_image_url ?>" alt="<?= $store_name ?> logo" class="card-img-top cmd-grid-image cmd-popup-mobile-image" />
					<div class="card-img-overlay p-0">
					</div>
					<?php
					if (empty(!$store_custom_fields['store_logo'])) {
						echo '<img loading="lazy" src="' . $store_custom_fields['store_logo'] . '" alt="' . $store_name . ' logo" style="border:2px solid ' . $store_custom_fields['store_color'] . '" class="card-img-top cmd-grid-store-logo mx-auto rounded-pill" />';
					} else {
					?>
						<p class="mx-auto mb-0 cmd-grid-store-name rounded-pill bg-primary px-3 py-1"><?= $store_name ?></p>
					<?php
					}
					?>
					<!-- Removed Border -->
					<div class="card-body pt-0 pb-4 mt-1">
						<?php
						if (is_single($id) or get_theme_mod('coupon_page', 'yes') == 'no') :
						?>
							<h3 class="card-title cmd-grid-title text-center  mt-0 pb-0 fs-5"><?php echo	get_the_title($id); ?></h3>
						<?php
						else :
						?>
							<h3 class="card-title cmd-grid-title text-center fs-5 mt-0 pb-0"><a href="<?php echo esc_url(get_permalink($id)); ?>" rel="bookmark"><?php echo get_the_title($id); ?></a></h3>
						<?php
						endif;
						?>
						<?php if (get_post_meta($id, 'cmd_type', true) == 'deal') {
						?>

							<a class="coupon-button deal-button text-white btn btn-primary text-center d-block <?= (isset($args['view']) and $args['view'] == 'list') ? 'd-block my-3' : '' ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ';border-color: ' . $store_custom_fields['store_color'] . ';background-image:none;' : '' ?>" href="<?php echo get_bloginfo('url') . '/cpn/' . $id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-<?= $args['view'] ?? '' ?>-<?= $id ?>','<?= $title ?>');" target="_blank" rel="nofollow noindex">
								<span><?php echo __('Activate Deal', 'clipmydeals'); ?></span>
							</a>
						<?php } elseif (get_post_meta($id, 'cmd_type', true) == 'print') { ?>
							<div class="bg-primary d-block mx-auto p-1 <?= (isset($args['view']) and $args['view'] == 'list') ? 'd-block my-3' : '' ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ' !important; background-image:none;' : '' ?>">
								<a class="coupon-button print-button d-block btn btn-primary text-white" style="cursor:pointer; border: 2px dashed #fff; font-weight:bold; <?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . '; background-image:none;' : '' ?>" <?= is_single() ? 'onclick="window.print();"' : 'href="' . esc_url(get_permalink($id)) . '"' ?>>
									<span><?php echo __('Print Coupon', 'clipmydeals'); ?></span>
								</a>
							</div>
						<?php } else { ?>
							<div class="d-block mx-2 mt-1 mb-3 px-3 py-2 clearfix cmd-popup-code">
								<span class="fw-bold" id="code-<?= $id ?>"><?php echo get_post_meta($id, 'cmd_code', true); ?></span>
								<div class="copy-box float-end d-inline mx-auto tooltip-active" title="<?= __("Click to Copy", 'clipmydeals') ?>" data-bs-toggle="tooltip" data-placement="bottom">
									<div id="copy-button-popup-<?= $id ?>" class="btn copy-button float-end" onclick="kCopy('<?= $id ?>',this);">
										<i class="fa fa-copy"></i>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>

					<?php
					if (
						!empty(get_post_meta($id, 'cmd_verified_on', true))
						or
						(comments_open($id) and get_theme_mod('coupon_page', 'yes') == 'yes')
					) {
					?>
					<?php } ?>
				</div><!-- #post-## -->
				<div id="showCouponModalHeader" class="modal-header couponModalHeader border-0 p-0 mt-lg-5 rounded-top-4">
					<!-- Coupon Box for Desktop -->
					<div id="coupon-grid-<?= $id ?>" <?php post_class("card cmd-grid-mobile-layout d-none d-md-block w-100 mx-auto overflow-hidden rounded-4 border-0 {$coupon_class} " . get_post_meta($id, 'cmd_type', true)); ?>>
						<?php
						if (has_post_thumbnail($id)) {
							$coupon_image_url = get_the_post_thumbnail_url($id, 'post-thumbnail');
						} elseif (!empty(get_post_meta($id, 'cmd_image_url', true))) {
							$coupon_image_url = get_post_meta($id, 'cmd_image_url', true);
						} elseif ($placeholder) {
							$coupon_image_url = $placeholder;
						} else {
							$coupon_image_url = get_template_directory_uri() . '/inc/assets/images/coupon-placeholder.png';
						}
						?>
						<img loading="lazy" src="<?= $coupon_image_url ?>" alt="<?= $store_name ?> logo" class="card-img-top cmd-grid-image" />
						<?php
						if (empty(!$store_custom_fields['store_logo'])) {
							echo '<img loading="lazy" src="' . $store_custom_fields['store_logo'] . '" alt="' . $store_name . ' logo" style="border:2px solid ' . $store_custom_fields['store_color'] . '" class="card-img-top cmd-grid-store-logo mx-auto position-relative d-block rounded-pill h-auto" />';
						} else {
						?>
							<p class="mx-auto mb-0 cmd-grid-store-name rounded-pill bg-primary px-3 py-1"><?= $store_name ?></p>
						<?php
						}
						?>
						<!-- Removed Border -->
						<div class="card-body py-0 mt-1 text-center">
							<?php
							if (is_single($id) or get_theme_mod('coupon_page', 'yes') == 'no') :
							?>
								<h3 class="card-title cmd-grid-title  mt-0 pb-0"><?php echo	get_the_title($id); ?></h3>
							<?php
							else :
							?>
								<h3 class="card-title cmd-grid-title mb-3 mt-0 pb-0"><a href="<?php echo esc_url(get_permalink($id)); ?>" rel="bookmark"><?php echo get_the_title($id); ?></a></h3>
							<?php
							endif;
							?>
							<?php if (get_post_meta($id, 'cmd_type', true) == 'deal') {
							?>

								<a class="coupon-button deal-button text-white btn btn-primary btn-lg <?= (isset($args['view']) and $args['view'] == 'list') ? 'd-block my-3' : '' ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ';border-color: ' . $store_custom_fields['store_color'] . ';background-image:none;' : '' ?>" href="<?php echo get_bloginfo('url') . '/cpn/' . $id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-<?= $args['view'] ?? '' ?>-<?= $id ?>','<?= $title ?>');" target="_blank" rel="nofollow noindex">
									<span><?php echo __('Activate Deal', 'clipmydeals'); ?></span>
								</a>
							<?php } elseif (get_post_meta($id, 'cmd_type', true) == 'print') { ?>
								<div class="bg-primary d-inline-block p-1 <?= (isset($args['view']) and $args['view'] == 'list') ? 'd-block my-3' : '' ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ' !important; background-image:none;' : '' ?>">
									<a class="coupon-button print-button d-block btn btn-primary text-white" style="cursor:pointer; border: 2px dashed #fff; font-weight:bold; <?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . '; background-image:none;' : '' ?>" <?= is_single() ? 'onclick="window.print();"' : 'href="' . esc_url(get_permalink($id)) . '"' ?>>
										<span><?php echo __('Print Coupon', 'clipmydeals'); ?></span>
									</a>
								</div>
							<?php } else { ?>
								<div class="d-block mx-2 mt-1 mb-3 px-3 py-2 clearfix cmd-popup-code">
									<span class="fw-bold" id="code-<?= $id ?>"><?php echo get_post_meta($id, 'cmd_code', true); ?></span>
									<div class="copy-box float-end d-inline mx-auto tooltip-active" title="<?= __("Click to Copy", 'clipmydeals') ?>" data-bs-toggle="tooltip" data-placement="bottom">
										<div id="copy-button-popup-<?= $id ?>" class="btn copy-button float-end" onclick="kCopy('<?= $id ?>',this);">
											<i class="fa fa-copy"></i>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>

						<?php
						if (
							!empty(get_post_meta($id, 'cmd_verified_on', true))
							or
							(comments_open($id) and get_theme_mod('coupon_page', 'yes') == 'yes')
						) {
						?>
						<?php } ?>
					</div><!-- #post-## -->
				</div>
				<div id="showCouponModalBody" class="<?= is_active_sidebar('popup') ? 'couponModalBodyPopup' : '' ?> modal-body pt-0 couponModalBody d-flex flex-column justify-content-between text-lg-center rounded-top-4">
					<div>
						<?php
						if ($post->post_content) {
						?>
							<p id="showCouponModalDescription" class="my-4"><?php echo $post->post_content; ?></p>
						<?php
						} else {
						?>
							<p>
								<?=
								$coupon_description;
								?>
							</p>
						<?php
						}
						?>
						<?php
						if (!empty(get_post_meta($id, 'cmd_valid_till', true))) {
							if (current_time('Y-m-d') > get_post_meta($id, 'cmd_valid_till', true)) {
								$validity_color = 'danger';
							} else {
								$validity_color = 'success';
							}
						?>
							<div class="badge bg-<?php echo $validity_color; ?> small mt-3"><?= (current_time('Y-m-d') <= get_post_meta($id, 'cmd_valid_till', true) ? __('Valid Till', 'clipmydeals') : __('Expired On', 'clipmydeals')) . ' ' . date_i18n(get_option('date_format'), strtotime(get_post_meta($id, 'cmd_valid_till', true))); ?></div>
						<?php } ?>
					</div>
				</div>
				<?php if (is_active_sidebar('popup')) { ?>
					<div id="popup-widget" class="overflow-scroll d-md-block rounded-0 border-0 couponModalBody">
						<?php dynamic_sidebar('popup'); ?>
					</div>
				<?php } ?>
				<div class="modal-footer row couponModalFooter justify-content-center border-0 ">
					<button type="button" class="col-3 btn btn-outline-primary" data-bs-dismiss="modal" aria-label="Close">
						<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
							<path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
						</svg>
						<?php echo __('Close', 'clipmydeals'); ?>
					</button>
					<div class="col-8 see-applicable see-applicable-coupon-popup text-center small">
						<a target="_blank" class="btn rounded-3 btn-primary w-100" rel="noindex nofollow" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-popup-<?= $id ?>','<?= get_the_title($id) ?>','popup');" href="<?php echo get_bloginfo('url') . '/cpn/' . $id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>">
							<?= __('See Applicable Products', 'clipmydeals') ?>
						</a>
					</div>
				</div>
			</div>
			<!-- </div> -->
		</div>
	</div>