<?php
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Template part for displaying no-results / no content found.
 */

do_action('AJDWP_content_none_top');
?>

<article <?php post_class('blog-items no-results'); ?>>
	<div class="blog-wrapup">
		<?php if (is_home() && current_user_can('publish_posts')) : ?>
			<p class="pt-5">
				<?php
				printf(
					__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'Hello-Elementor-Child-Theme'),
					esc_url(admin_url('post-new.php'))
				);
				?>
			</p>
		<?php elseif (is_search()) : ?>
			<p class="pt-5">
				<?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'Hello-Elementor-Child-Theme'); ?>
			</p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p class="pt-5">
				<?php _e('It seems we can’t find what you’re looking for. Perhaps searching can help.', 'Hello-Elementor-Child-Theme'); ?>
			</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</article>

<?php do_action('AJDWP_content_none_bottom'); ?>