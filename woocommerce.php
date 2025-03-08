<?php
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>

<?php get_header(); ?>

<?php do_action('woocommerce_shop_page_top'); ?>

<div class="content mt-5 woo-page-contents">
	<div class="container-fluid px-lg-5">
		<!-- Button to toggle the sidebar -->
		<button id="sidebarToggle" class="btn btn-primary d-lg-none">➡️</button>
		<div id="sidebarOverlay" style="display: none;"></div>
		<div class="row woo-page-row">

			<div class="col-xl-10 order-lg-2 col-lg-9 woo-page-main-content">
				<section id="post-section" class="post-section">
					<div class="container-fluid">
						<div class="row">
							<?php woocommerce_content(); ?>
						</div>
					</div>
				</section>
			</div>

			<div class="col-xl-2 col-lg-3 order-lg-1 AJDWP-sidebar AJDWP-woo-sidebar rounded shadow py-4 woo-page-sidebar">
				<div class="sticky-top">
					<span class="close-sidebar d-lg-none">❎</span>
					<?php dynamic_sidebar('AJDWP-Shop-sidebar');; ?>
				</div>
			</div>

		</div>
	</div>
</div>

<?php do_action('woocommerce_shop_page_bottom'); ?>

<?php get_footer(); ?>