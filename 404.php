<?php

/**
 * The template for displaying 404 pages (not found)
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header(); ?>

<section class="page_404 ">
	<div class="container">
		<div class="row d-flex justify-content-center ">
			<div class="col-sm-9 d-flex justify-content-center">
				<div class="col-sm-10 col-sm-offset-1 text-center">
					<div class="d-flex justify-content-center">
						<?php get_search_form(); ?>
					</div>
					<div class="four_zero_four_bg">
						<h1 class="text-center ">
							<?php esc_html_e('404', 'hello-elementor-child'); ?>
						</h1>
					</div>
					<div class="contant_box_404">
						<h2 class="h2">
							<?php esc_html_e("Looks like you're lost", 'hello-elementor-child'); ?>
						</h2>
						<p>
							<?php esc_html_e('The page you are looking for is not available!', 'hello-elementor-child'); ?>
						</p>
						<a aria-label="<?php esc_attr_e('Link to Homepage', 'hello-elementor-child'); ?>"
							href="<?php echo esc_url(home_url('/')); ?>"
							class="link_404">
							<?php esc_html_e('Go to Home', 'hello-elementor-child'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>