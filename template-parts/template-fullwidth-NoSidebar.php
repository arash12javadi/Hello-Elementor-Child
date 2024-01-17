<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/*
Template Name: FullWith No Sidebar
*/

?>

<?php get_header(); ?>


<div class="content mt-5">

    <div class="container">

        <div class="row">


			<div class="col-lg-12">

				<section id="post-section" class="post-section">
					<div class="container-fluid">
						<div class="row">

							<?php if( have_posts() ): ?>
								<?php while( have_posts() ) : the_post(); 
										get_template_part('theme_addons/content/content','page'); 
								endwhile; ?>			
							<div class="d-flex align-items-center justify-content-center">
								<!-- Pagination -->
									<?php								
									// Previous/next page navigation.
									the_posts_pagination( array(
									'prev_text'          => '<i class="fa fa-angle-double-left"></i>',
									'next_text'          => '<i class="fa fa-angle-double-right"></i>',
									) ); ?>
								<!-- Pagination -->	
							</div>
							<?php else: ?>
								<?php get_template_part('theme_addons/content/content','none'); ?>
							<?php endif; ?>
							

						</div>
					</div>
				</section>

			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>