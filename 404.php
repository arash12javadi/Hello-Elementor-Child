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
						<h1 class="text-center ">404</h1>
					</div>
					<div class="contant_box_404">
						<h2 class="h2">
							Look like you're lost
						</h2>
						<p>the page you are looking for not availble!</p>
						<a aria-label="Link to Homepage" href="<?php echo esc_url(home_url('/')); ?>" class="link_404">Go to Home</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>