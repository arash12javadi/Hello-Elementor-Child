<?php
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<?php do_action('ajdwp_theme_before_sidebar'); ?>
<aside>
	<?php dynamic_sidebar('AJDWP-Sidebar-1');  ?>
</aside>
<?php do_action('ajdwp_theme_after_sidebar'); ?>