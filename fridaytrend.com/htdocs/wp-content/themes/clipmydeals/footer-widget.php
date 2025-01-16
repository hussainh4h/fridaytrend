<?php
if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) { ?>
    <div id="footer-widget" class="p-5 d-print-none">
        <!--  -->
        <div class=" row">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="col-12 col-md-4"><?php dynamic_sidebar('footer-1'); ?></div>
            <?php endif; ?>
            <?php if (is_active_sidebar('footer-2')) : ?>
                <div class="col-12 col-md-4"><?php dynamic_sidebar('footer-2'); ?></div>
            <?php endif; ?>
            <?php if (is_active_sidebar('footer-3')) : ?>
                <div class="col-12 col-md-4"><?php dynamic_sidebar('footer-3'); ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php
}

if (is_active_sidebar('footer-4')) {
?>
    <div id="footer-widget-wide" class="shadow-lg shadow-inset d-print-none">
        <div class="container-fluid row">
            <div class="col-12">
                <div class="p-2 px-md-5 py-md-3">
                    <?php dynamic_sidebar('footer-4'); ?>
                </div>
            </div>
        </div>
    </div>
<?php
}

?>