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


function clipmydeals_demo_import_page()
{
	//Bootstrap JS
	wp_register_script('bootstrap.min', get_template_directory_uri() . '/inc/assets/js/bootstrap.min.js', array('jquery'));
	wp_enqueue_script('bootstrap.min');
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

	if (in_array('clipmydeals-cashback/clipmydeals-cashback.php', get_option('active_plugins'))) $cashback = " + Cashback";
	if (in_array('clipmydeals-comparison/clipmydeals-comparison.php', get_option('active_plugins'))) $comparison = " + Comparison";
	$demo_arr = array(
		array('value' => 'demo1',  'label' => 'LearnToEarn',          'demo_url' => 'https://demo.clipmydeals.com/1/'),
		array('value' => 'demo2',  'label' => 'Coupon Mash',          'demo_url' => 'https://demo.clipmydeals.com/2/'),
		array('value' => 'demo3',  'label' => 'Coin It',              'demo_url' => 'https://demo.clipmydeals.com/3/'),
		array('value' => 'demo4',  'label' => 'Cherry blossom',       'demo_url' => 'https://demo.clipmydeals.com/4/'),
		array('value' => 'demo5',  'label' => 'ClipKaro',             'demo_url' => 'https://demo.clipmydeals.com/5/'),
		array('value' => 'demo6',  'label' => 'Grey Onyx',            'demo_url' => 'https://demo.clipmydeals.com/6/'),
		array('value' => 'demo7',  'label' => 'Blue Lagoon',          'demo_url' => 'https://demo.clipmydeals.com/7/'),
		array('value' => 'demo8',  'label' => 'Cashback Koala',       'demo_url' => 'https://demo.clipmydeals.com/8/'),
		array('value' => 'demo9',  'label' => 'Tangy Tomato',         'demo_url' => 'https://demo.clipmydeals.com/9/'),
		array('value' => 'demo10', 'label' => 'Coupon Panda',         'demo_url' => 'https://demo.clipmydeals.com/10/'),
		array('value' => 'demo11', 'label' => 'Le tango français',    'demo_url' => 'https://demo.clipmydeals.com/11/'),
		array('value' => 'demo12', 'label' => 'Gulf Gambit',          'demo_url' => 'https://demo.clipmydeals.com/12/'),
		array('value' => 'demo13', 'label' => 'The German giant',     'demo_url' => 'https://demo.clipmydeals.com/13/'),
		array('value' => 'demo14', 'label' => 'Maravilha brasileira', 'demo_url' => 'https://demo.clipmydeals.com/14/'),
		array('value' => 'demo15', 'label' => 'Toro español',         'demo_url' => 'https://demo.clipmydeals.com/15/'),
	);
?>

	<div class="wrap" style="background:#F1F1F1;">

		<h1><?= __('ClipMyDeals - Demo Import', 'clipmydeals'); ?></h1>

		<?= $message ?? ''; ?>
		<hr />
		<?php if (wp_get_theme() != 'ClipMyDeals') { ?>
			<div class="notice notice-warning">
				<p><?= __('Demo Import is not available in Child Theme.', 'clipmydeals'); ?></p>
			</div>
		<?php } else { ?>
			<p><?= __('<div class="alert alert-danger">Clicking "<strong>Import Demo</strong>" will reset your website as per selected demo. Third-party plugins & related data will NOT be imported. All your existing content & settings will be permanently deleted. Please take a backup of your content if you need to add it again later.</div>', "clipmydeals") ?></p>
			<div class="row px-3">
				<form onsubmit="cmdConfirmDemoImport()" class="form-row" id="demoImport" name="demoImport" role="form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
					<div class="col-md-12 d-flex justify-content-center align-items-stretch flex-wrap">
						<?php foreach ($demo_arr as $i => $demo) { ?>
							<div class="card p-0 m-2" style="width:15rem;">
								<div class="card-header text-center bg-white">
									<?= $demo['label'] ?>
								</div>
								<label class="card-body row align-content-between flex-wrap p-0" for="<?= $demo['value'] ?>">
									<img loading="lazy" class="col-10 col-md-9 mx-auto my-3" style="aspect-ratio: 1/1;" src="<?php echo get_template_directory_uri() . "/inc/assets/images/demo/import-" . $demo['value'] . ".png"; ?>" alt="Card image cap">
								</label>
								<div class="card-footer border-0 bg-white">
								<!-- <button class="btn btn-success btn-block btn-sm demoImport-submit-btn w-100" onclick="cmdConfirmDemoImport('<?= $demo['label'] ?>','<?=$demo['demo_url']?>')" target="_blank" href="<?= $demo['demo_url'] ?>">Import Demo <span class="dashicons dashicons-download"></span></button> -->
									<button class="btn btn-success btn-block btn-sm demoImport-submit-btn w-100" onclick="cmdConfirmDemoImport('<?= $demo['value'] ?>','<?= $demo['label'] ?>')" target="_blank" href="<?= $demo['demo_url'] ?>"><?= __('Import Demo', 'clipmydeals'); ?> <span class="dashicons dashicons-download"></span></button>
									<a class="btn btn-outline-dark btn-block btn-sm w-100 mt-2" target="_blank" href="<?= $demo['demo_url'] ?>"><?= __('View Demo', 'clipmydeals'); ?> <span class="dashicons dashicons-arrow-right"></span></a>
								</div>
							</div>
						<?php } ?>
					</div>
					<input hidden type="text" name="action" value="cmd_import_content" />
					<input hidden type="text" name="redirect_to" value="clipmydeals-demo-import" />
					<input hidden type="text" id="demo_to_import" name="demo_to_import" />
					<input hidden type="text" name="import" value="all" />
					<?php wp_nonce_field('demo_import', 'demo_import_nonce'); ?>
				</form>
			</div>
			<p><?= __('<div class="alert alert-danger">Clicking "<strong>Import Demo</strong>" will reset your website as per selected demo. Third-party plugins & related data will NOT be imported. All your existing content & settings will be permanently deleted. Please take a backup of your content if you need to add it again later.</div>', "clipmydeals") ?></p>
		<?php } ?>
	</div>

	<!-- Modal -->
	<div class="modal" id="demoImport-modal" tabindex="20" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="demoImport-modalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div id="confirm-modal-body" class="demoImportModalBody modal-body p-0 border-0 px-4 py-5">

					<div class="jumbotron jumbotron-fluid mb-0">
						<div class="container">
							<h1 class="display-4 text-center fw-bold" style="font-size:2rem;"><?= __('Are you sure you want to reset your website?', 'clipmydeals'); ?></h1>
							<p class="lead text-center"><?= __('Clicking "<strong>Import</strong>" will reset your website as per "<strong id="demoImport-name">[DEMO]</strong>", and all your content & settings (Coupons, Products, Menu, Widgets, etc.) will be permanently lost. Please take a backup of your content if you need to add it again later.', 'clipmydeals'); ?></p>
							<p class="lead text-center"><?= __('You cannot undo this operation after this step. Click "<strong class="">Cancel</strong>" to abort.', 'clipmydeals'); ?></p>
							<p id="demo-message" class="lead text-center d-none font-italic"><?= __('Your access to admin demo is a view only access to help you experience our array of functionalities. This functionality is restricted for you to safeguard the working of our admin demo. This restriction will be removed when you', 'clipmydeals'); ?> <a href="https://clipmydeals.com/order/cart.php" target="_blank"><?= __('purchase the license.','clipmydeals') ?></a></p>
							<div id="demoImport-buttons" class="row justify-content-around">
								<button id="demoImport-confirm" class="mt-2 btn col-md-4 btn-danger" onclick="submitDemoImportForm();"><?= __('Import', 'clipmydeals') ?></button>
								<button id="demoImport-cancel" class="mt-2 btn col-md-4 btn-secondary" data-bs-dismiss="modal" aria-label="Close"><?= __('Cancel', 'clipmydeals') ?></button>
							</div>
						</div>
					</div>
				</div>
				<div id="alert-modal-body" class=' modal-body px-5 pt-4 row d-none'>
					<div class='col-12 alert alert-info text-center'>
						<p><strong><?= __('Please Wait. This may take several minutes.</strong>', 'clipmydeals'); ?></p>
						<p><?= __('Please do not click back or refresh button till the page is loading.', 'clipmydeals'); ?></p>
					</div>
					<img src="<?php echo get_template_directory_uri() . "/inc/assets/images/loading.gif" ?>" alt="Loading" class="col-12">
				</div>
			</div>
		</div>
	</div>

	<script>
		function submitDemoImportForm() {
			document.getElementById("confirm-modal-body").classList.add("d-none");
			document.getElementById("alert-modal-body").classList.remove("d-none");
			document.querySelector("#demoImport").submit();
		}

		function cmdConfirmDemoImport(demo,demoName) {
			event.preventDefault();
			console.log(demo)
			document.getElementById("demo_to_import").value = demo;
			
			document.getElementById("demoImport-name").innerText = demoName;

			document.querySelectorAll(".demoImport-submit-btn").forEach(btn => {
				btn.innerHTML = `<?= sprintf(__("Please Wait %s", 'clipmydeals'),"<span class='dashicons dashicons-clock'></span>") ?>`;
				btn.disabled = true;
			});
			jQuery('#demoImport-modal').modal('show');
			document.querySelectorAll("[name='demo_to_import']").forEach(e => {
				if (e.checked === true) {
					document.getElementById("demoImport-name").innerHTML = e.getAttribute('data-import-demo-name')
					return;
				}
			})
		}

		jQuery('#demoImport-modal').on('hidden.bs.modal', function() {
			document.querySelectorAll(".demoImport-submit-btn").forEach(btn => {
				btn.innerHTML = '<?= __("Import", "clipmydeals"); ?> <span class="dashicons dashicons-editor-break" style="margin-top:2px;"></span>';
				btn.disabled = false;
			});
		});
	</script>

<?php
}

function cmd_replaced_with_url_slides($slides)
{
	$replaced_slides = array();
	if ($slides and is_array($slides)) {
		foreach ($slides as $key => $value) {
			if (count($value) == 2)
				$replaced_slides[$key] = array(wp_get_attachment_url(intval($value[0])), $value[1]);
		}
	}
	return $replaced_slides;
}
add_action('admin_post_cmd_import_demo_content', 'cmd_import_demo_content');

?>