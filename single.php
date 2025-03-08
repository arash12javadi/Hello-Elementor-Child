<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
get_header(); ?>
<div class="content mt-5">
    <div class="container ">
        <!-- Button to toggle the sidebar -->
        <button id="sidebarToggle" class="btn btn-primary d-lg-none">➡️</button>
        <div id="sidebarOverlay" style="display: none;"></div>
        <div class="row">
            <div class="col-lg-9 order-lg-2">
                <section id="post-section" class="post-section">
                    <div class="container">
                        <div class="row">
                            <?php if (have_posts()): ?>
                                <?php while (have_posts()) : the_post();
                                    get_template_part('theme_addons/content/content', 'page');
                                endwhile; ?>
                                <div class="tag_class my-5">
                                    <?php the_tags(); ?>
                                </div>
                            <?php endif; ?>
                            <?php comments_template('', true); // show comments  
                            ?>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-3 order-lg-1 AJDWP-sidebar AJDWP-single-sidebar rounded shadow py-4">
                <div class="sticky-top">
                    <span class="close-sidebar d-lg-none">❎</span>
                    <?php dynamic_sidebar('AJDWP-blog-Sidebar'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php get_footer(); ?>