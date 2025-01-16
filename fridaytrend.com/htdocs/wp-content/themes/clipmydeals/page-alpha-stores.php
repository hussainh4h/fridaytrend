<?php /* Template Name: Alphabetic Stores */ ?>

<?php

$layout_options = clipmydeals_layout_options();
get_header();
global $store_status;

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-page-alpha-stores <?= $layout_options['container'] ?> pt-5">
		<div class="row mx-0">
		<?php
	}

		?>

		<section id="primary" class="content-area order-md-1 <?= ($layout_options['sidebar']) ? 'col-sm-12 col-lg-8 col-lg-9' : 'col' ?>">
			<main id="main" class="site-main" role="main">

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">

						<div>
							<?php
							while (have_posts()) : the_post();
								the_content();
							endwhile;
							?>
						</div>

						<div class="row p-3">
							<div class="col">
								<ul class="nav nav-underline">
									<li class="nav-item"><a class="nav-link <?= (empty($_GET['filter']) ? 'active' : '') ?>" href="<?= get_permalink() ?>"><?= __('All', 'clipmydeals') ?></a></li>
									<?php foreach (range('A', 'Z') as $letter) { ?>
										<li class="nav-item"><a class="nav-link <?= (isset($_GET['filter']) and $_GET['filter'] == $letter) ? 'active' : '' ?>" href="<?= get_permalink() . '?filter=' . $letter ?>"><?= $letter ?></a></li>
									<?php } ?>
									<li class="nav-item"><a class="nav-link <?= (isset($_GET['filter']) and $_GET['filter'] == 'num') ? 'active' : '' ?>" href="<?= get_permalink() . '?filter=num' ?>">#</a></li>
								</ul>
							</div>
						</div>

						<div class="row mt-5">
							<?php
							$stores = get_terms(array(
								'taxonomy' => 'stores',
								'hide_empty' => false,
								'orderby' => 'name',
								'order' => 'ASC',
							));
							$store_found = false;
							foreach ($stores as $store) {
								store_taxonomy_status($store->term_id);
								if ($store_status[$store->term_id] == 'inactive') continue;
								$firstChar = strtoupper(substr($store->name, 0, 1));
								$store_class =  (in_array($firstChar, range('A', 'Z')) ? $firstChar : 'num');
								if (empty($_GET['filter']) or $_GET['filter'] == $store_class) {
									$store_found = true;
							?>
									<div class="col-6 col-sm-4 <?= ($layout_options['sidebar']) ? 'col-xl-3' : 'col-lg-3 col-xl-2' ?> p-2">
										<a class="text-decoration-none" href="<?php echo get_term_link($store); ?>">
											<div class="cmd-taxonomy-card store-<?= $store_class ?> card my-3 p-2 rounded-4 shadow-sm">
												<h2 class="fs-5 card-header text-center cmd-store-logo-fix-height">
													<?php echo $store->name ?>
												</h2>
												<div class="card-footer text-center py-1 px-0"><small><?php echo $store->count . ' ' . ($store->count > 1 ? __('Offers', "clipmydeals") : __('Offer', "clipmydeals")); ?></small></div>
											</div>
										</a>
									</div>
								<?php
								}
							}
							if (!$store_found) {
								?>
								<div class="col-12">
									<p class="alert alert-info text-center">
										<?php
										printf(
											/* translators: %s: Alphabet filter on Store Page */
											__("Oops! We do not have any store starting with %s", 'clipmydeals'),
											($_GET['filter'] == 'num' ? 'special characters' : $_GET['filter'])
										);
										?>
									</p>
								</div>
							<?php
							}
							?>
						</div>

					</div>

					<?php if (get_edit_post_link()) : ?>
						<footer class="entry-footer">
							<?php
							edit_post_link(
								sprintf(
									/* translators: %s: Name of current post */
									esc_html__('Edit %s', 'clipmydeals'),
									the_title('<span class="screen-reader-text">"', '"</span>', false)
								),
								'<span class="edit-link">',
								'</span>'
							);
							?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>

				</article>

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
