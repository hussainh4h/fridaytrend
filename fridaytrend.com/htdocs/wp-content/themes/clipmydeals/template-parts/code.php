<?php

/**
 * Template part for displaying button on coupons in grid view
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

$id = get_the_ID();
$title = get_the_title($id);
$store_terms = get_the_terms($id, 'stores');
$store_name = $store_terms ? $store_terms[0]->name : '';
$store_slug = $store_terms ? $store_terms[0]->slug : '';
$store_term_id = $store_terms ? $store_terms[0]->term_id : 0;
$store_custom_fields = cmd_get_taxonomy_options($store_term_id, 'stores');

$store_slug = urldecode($store_slug);

if (get_post_meta($id, 'cmd_type', true) == 'print') { // PRINT BUTTON
?>
    <?php if (is_single()) { ?>
        <div class="card-body row d-flex justify-content-center" style="z-index:2;">
            <div class="text-center col-12 col-sm-10">
                <?php if (!empty(get_post_meta($id, 'cmd_code', true))) { ?>
                    <div class="barcode pb-2 text-center" style="margin:auto;">
                        <img class="img-fluid w-100" src="<?php echo get_template_directory_uri() . '/inc/assets/barcode.php?text=' . urlencode(get_post_meta($id, 'cmd_code', true)); ?>" />
                        <div><?php echo get_post_meta($id, 'cmd_code', true); ?></div>
                    </div>
                <?php } ?>
                <p class="lead" style="font-size:0.75rem;"><em>
                        <?= __('Show this barcode at the checkout counter to avail this offer. You can scan it directly from your phone, or from a Printed copy.', 'clipmydeals'); ?>
                    </em></p>
            </div>
        </div>
    <?php } ?>

    <div class="bg-primary d-inline-block p-1 btn-lg print-button <?= $args['view'] == 'list' ? 'd-block my-3' : '' ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ' !important; background-image:none;' : '' ?>">
        <a class="coupon-button print-button d-block btn btn-primary text-white" style="cursor:pointer; border: 2px dashed #fff; font-weight:bold; <?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . '; background-image:none;' : '' ?>" <?= is_single() ? 'onclick="window.print();"' : 'href="' . esc_url(get_permalink($id)) . '"' ?>>
            <span><?php echo __('Print Coupon', 'clipmydeals'); ?></span>
        </a>
    </div>

<?php } elseif (get_post_meta($id, 'cmd_type', true) == 'deal') { // DEAL
?>

    <a class="coupon-button deal-button text-white btn btn-primary btn-lg <?= $args['view'] == 'list' ? 'd-block my-3' : '' ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ';border-color: ' . $store_custom_fields['store_color'] . ';background-image:none;' : '' ?>" href="<?php echo get_bloginfo('url') . '/cpn/' . $id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-<?= $args['view'] ?>-<?= $id ?>','<?= $title ?>');" target="_blank" rel="nofollow noindex">
        <span><?php echo __('Activate Deal', 'clipmydeals'); ?></span>
    </a>

<?php } elseif (!empty($_GET['appView'])) { // APP CODE ONLY FOR GRID
?>

    <div class="app-code-button-parent bg-primary d-inline-block p-1 <?php echo $store_slug . '-app-code-hidden'; ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ' !important; background-image:none;' : '' ?>">
        <a class="coupon-button app-code-button btn btn-primary text-white" style="border: 2px dashed #fff;<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . '; background-image:none;' : '' ?>" href="<?php echo get_bloginfo('url') . '/cpn/' . $id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-<?= $args['view'] ?>-<?= $id ?>','<?= $title ?>','app_code');" target="_blank">
            <?php echo __('Get Code', 'clipmydeals'); ?> <i class="fa fa-cut"></i>
        </a>
    </div>
    <div class="d-none text-center <?php echo $store_slug . '-app-code-revealed'; ?>">
        <div class="d-block mx-2 mt-1 mb-3 px-3 py-2 clearfix" style="position:relative; border: 2px dashed <?= !empty($store_custom_fields['store_color']) ? $store_custom_fields['store_color'] . ';' : '#343434;' ?>">
            <span id="code-<?= $id ?>"><?php echo get_post_meta($id, 'cmd_code', true); ?></span>
            <div class="copy-box float-end d-inline mx-auto tooltip-active" style="position:absolute;top:0.06rem;right:0.1rem;" title="<?= __("Click to Copy", 'clipmydeals') ?>" data-bs-toggle="tooltip" data-placement="bottom">
                <div class="btn copy-button float-end">
                    <i class="fa fa-copy"></i>
                </div>
            </div>
        </div>
        <div class="see-applicable see-applicable-app-view text-center small">
            <a href="<?php echo get_bloginfo('url') . '/cpn/' . $id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-<?= $args['view'] ?>-<?= $id ?>','<?= $title ?>');" target="_blank" rel="noindex nofollow">
                <?= __('See Applicable Products', 'clipmydeals') ?>
            </a>
        </div>
    </div>

<?php } elseif (
    get_theme_mod('reveal_type', 'inline') == 'always' or
    (strpos(($_COOKIE['storesVisited'] ?? ''), $store_slug . '|') !== false)
) { // REVEALED CODE
?>

    <div class="text-center mt-3">
        <div class="revealed-button d-block mx-2 mt-1 mb-1 px-3 py-2 clearfix" style="<?= $args['view'] == 'list' ? '' : 'position:relative;' ?> border: 2px dashed <?= !empty($store_custom_fields['store_color']) ? $store_custom_fields['store_color'] . ';' : '#343434;' ?>">
            <span class="fw-bold" id="code-<?= $id ?>"><?php echo get_post_meta($id, 'cmd_code', true); ?></span>
            <?php if ($args['view'] == 'list') { ?>
                <div class="d-inline mx-auto tooltip-active" style="color:#6a84ab;" title="Click to Copy" data-bs-toggle="tooltip" data-placement="bottom">
                    <span id="copy-button-list-<?= $id ?>" class="copy-button" onclick="kCopy('<?= $id ?>',this);"><i class="fa fa-copy"></i></span>
                </div>
            <?php } else { ?>
                <div class="copy-box float-end d-inline mx-auto tooltip-active" style="position:absolute;top:0.06rem;right:0.1rem;" title="<?= __("Click to Copy", 'clipmydeals') ?>" data-bs-toggle="tooltip" data-placement="bottom">
                    <div id="copy-button-grid-<?= $id ?>" class="btn copy-button float-end" style="color:#8f8f8f;" onclick="kCopy('<?= $id ?>',this);">
                        <i class="fa fa-copy"></i>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="see-applicable see-applicable-inline-reveal text-center small">
        <a onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-<?= $args['view'] ?>-<?= $id ?>','<?= $title ?>');" href="<?php echo get_bloginfo('url') . '/cpn/' . $id . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>" target="_blank" rel="noindex nofollow">
            <?= __('See Applicable Products', 'clipmydeals') ?>
        </a>
    </div>

<?php } else { // HIDDEN CODE
?>

    <div class="coupon-button code-button-parent bg-primary d-inline-block p-1 <?= $args['view'] == 'list' ? 'd-block my-3' : '' ?>" style="<?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . ' !important; background-image:none;' : '' ?>">
        <span id="code-<?= $id ?>" hidden><?php echo get_post_meta($id, 'cmd_code', true); ?></span>
        <a class="code-button d-block btn btn-primary text-white" style="border: 2px dashed #fff; <?= !empty($store_custom_fields['store_color']) ? 'background-color:' . $store_custom_fields['store_color'] . '; background-image:none;' : '' ?>" onclick="cmdShowOffer(event,'<?= $store_slug ?>','#coupon-<?= $args['view'] ?>-<?= $id ?>','<?= $title ?>','show_coupon','<?= $id ?>');" href="<?= get_bloginfo('url') . '/cpn/' . get_the_ID() . '/' . (get_current_user_id() ? get_current_user_id() . '/' : ''); ?>">
            <span><?php echo __('Get Code', 'clipmydeals'); ?></span>
        </a>
    </div>

<?php } ?>