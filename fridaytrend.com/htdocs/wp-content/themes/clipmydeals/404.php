<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ClipMyDeals
 */
$layout_options = clipmydeals_layout_options();
get_header();
global $store_status;

if (!get_option('cmd_old_header')) {
?>
<div class="cmd-not-found <?= $layout_options['container'] ?> pt-5">
  <div class="row mx-0">
<?php
}
?>

<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
  <main id="main" class="site-main" role="main">

    <section class="error-404 not-found">
      <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Oops! That page can’t be found.', 'clipmydeals'); ?></h1>
      </header><!-- .page-header -->

      <div class="page-content">
        <p><?php esc_html_e('It looks like nothing was found at this location. You can try searching or explore the links below:', 'clipmydeals'); ?></p>

        <!-- Search Form -->
        <?php get_search_form(); ?>

        <!-- Custom Suggestions -->
        <div class="custom-404-content mt-4">
          <h2><?php esc_html_e('Here are some helpful links:', 'clipmydeals'); ?></h2>
          <ul>
            <li><a href="<?php echo home_url(); ?>"><?php esc_html_e('Go to Homepage', 'clipmydeals'); ?></a></li>
            <li><a href="<?php echo site_url('/coupons/'); ?>"><?php esc_html_e('Browse Coupons', 'clipmydeals'); ?></a></li>
            <li><a href="<?php echo site_url('/contact/'); ?>"><?php esc_html_e('Contact Us', 'clipmydeals'); ?></a></li>
          </ul>
        </div>
      </div><!-- .page-content -->
    </section><!-- .error-404 -->

  </main><!-- #main -->
</section><!-- #primary -->

<?php
if ($layout_options['sidebar']) {
  get_sidebar();
}

if (!get_option('cmd_old_header')) {
?>
  </div> <!-- row -->
</div> <!-- container -->
<?php
}

get_footer();
?>
