<?php /* Template Name: Blog Posts */ ?>

<?php

$layout_options = clipmydeals_layout_options();
get_header();

if (!get_option('cmd_old_header')) {
?>
	<div class="cmd-page-blog <?= $layout_options['container'] ?> pt-5">
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

						<?php
						$paged = get_query_var('paged') ? get_query_var('paged') : 1;
						$queried_posts = query_posts('post_type=post&post_status=publish&paged=' . $paged);
						if (have_posts()) {
						?>
							<div class="row">
								<?php
								while (have_posts()) : the_post();
									get_template_part('template-parts/post', get_post_format());
								endwhile;
								?>
							</div>
						<?php
						}
						?>
					</div>
					<div class="row mb-2">
						<div class="col-sm-6">
							<?php previous_posts_link('<button class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i>'.__("Previous Page",'clipmydeals').'</button>'); ?>
						</div>
						<div class="col-sm-6">
							<?php next_posts_link('<button class="btn btn-secondary btn-sm float-end">'.__('Next Page','clipmydeals').' <i class="fa fa-arrow-right"></i></button>'); ?>
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
