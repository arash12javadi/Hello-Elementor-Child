<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php get_header(); ?>


<div class="content mt-5">

    <div class="container ">

        <div class="row">


            <div class="col-lg-3 AJDWP-side-bar rounded shadow pt-4 pb-4 mb-5 mb-lg-0 ">
                <div class="sticky-top" >
                    <?php dynamic_sidebar('AJDWP-blog-Sidebar'); ?>
                </div>
            </div>

        
            <div class="col-lg-9 ">

                <section id="post-section" class="post-section">
                    <div class="container">
                        <div class="row">

                            <?php if( have_posts() ): ?>
                                <?php while( have_posts() ) : the_post(); 
                                        get_template_part('theme_addons/content/content','page'); 
                                endwhile; ?>	
                                
                                    <div class="tag_class my-5">
                                        <?php the_tags(); ?>
                                    </div>
                                    
                                <?php endif; ?>
                                <?php comments_template( '', true ); // show comments  ?>
                            </div>
                        
                        </div>
                    </div>
                </section>

            </div>



        </div>

    </div>

</div>

<?php get_footer(); ?>