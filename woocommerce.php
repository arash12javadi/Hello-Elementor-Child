<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	} 
?>

<?php get_header(); ?>

<div class="content mt-5">
    <div class="container-fluid px-5">
        <div class="row">

		<div class="col-xl-2 col-lg-3 AJDWP-side-bar rounded shadow pt-4 pb-4 mb-5 mb-lg-0 ">
			<div class="sticky-top">
				<?php dynamic_sidebar('AJDWP-Shop-sidebar'); ; ?>
			</div>
		</div>

		<div class="col-xl-10 col-lg-9">
			<section id="post-section" class="post-section">
				<div class="container-fluid">
					<div class="row">
						<?php woocommerce_content(); ?>
					</div>
				</div>
			</section>
		</div>

		</div>
	</div>
</div>

<?php get_footer(); ?>