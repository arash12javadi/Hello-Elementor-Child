<?php
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
get_header(); ?>
<div class="content mt-5">
	<div class="container">
		<!-- Button to toggle the sidebar -->
		<button id="sidebarToggle" class="btn btn-primary d-lg-none">➡️</button>
		<div id="sidebarOverlay" style="display: none;"></div>
		<div class="row">
			<div class="col-lg-9 order-lg-2">
				<section id="post-section" class="post-section">
					<div class="container-fluid">
						<div class="row">
							<?php if (have_posts()): ?>
								<?php while (have_posts()) : the_post();
									get_template_part('theme_addons/content/content', 'page');
								endwhile; ?>
								<div class="d-flex align-items-center justify-content-center">
									<?php
									// Previous/next page navigation.
									the_posts_pagination(array(
										'prev_text'          => '<i class="fa fa-angle-double-left"></i>',
										'next_text'          => '<i class="fa fa-angle-double-right"></i>',
									)); ?>
								</div>
							<?php else: ?>
								<?php get_template_part('theme_addons/content/content', 'none'); ?>
							<?php endif; ?>
						</div>
					</div>
				</section>
			</div>
			<div class="col-lg-3 AJDWP-sidebar AJDWP-page-sidebar rounded shadow py-4 mb-lg-0 order-lg-1">
				<div class="sticky-top">
					<span class="close-sidebar d-lg-none">❎</span>
					<?php dynamic_sidebar('AJDWP-page-sidebar'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>