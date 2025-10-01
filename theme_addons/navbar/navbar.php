<?php
if (!defined('ABSPATH')) exit; // Prevent direct access

include dirname(__FILE__) . "/class-wp-bootstrap-navwalker.php";

// Register Navigation Menu
register_nav_menus(['AJDWPMenu1' => 'AJDWP Menu']);

if (!function_exists('AJDWP_primary_navigation_func')) :
    function AJDWP_primary_navigation_func()
    {
        wp_nav_menu([
            'theme_location' => 'AJDWPMenu1',
            'container'      => '',
            'menu_class'     => 'menu-wrap navbar-nav me-auto',
            'fallback_cb'    => 'WP_Bootstrap_Navwalker::fallback',
            'walker'         => new WP_Bootstrap_Navwalker(),
            // Add this line:
            'items_wrap'     => '<ul id="%1$s" class="%2$s" aria-label="Main Navigation">%3$s</ul>',
        ]);
    }
endif;
add_action('AJDWP_primary_navigation', 'AJDWP_primary_navigation_func');


// Avatar Function (Handles both small and normal sizes)
function AJDWP_avatar_func($size = 'normal')
{
    if (!is_user_logged_in()) return;

    $user = wp_get_current_user();
    $custom_avatar = get_user_meta(get_current_user_id(), 'custom_avatar_url', true);
    $avatar_img = (!empty($custom_avatar))
        ? '<img id="selected-image" src="' . esc_url($custom_avatar) . '" alt="User Avatar" loading="lazy" style="width:50px; height:50px;">'
        : get_avatar($user, 50); // Default WordPress Avatar with size 50px

    $class = ($size === 'small') ? "AJDWP_avatar_sm" : "AJDWP_avatar";
?>
    <div class="<?php echo esc_attr($class); ?>">
        <a href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>">
            <?php echo $avatar_img; ?>
        </a>
        <br><?php echo esc_html($user->display_name); ?><br>
        <a href="<?php echo esc_url(wp_logout_url()); ?>" title="Logout"><?php esc_html_e('Logout', 'hello-elementor-child'); ?></a>
    </div>
<?php
}
add_action('AJDWP_avatar_sm', function () {
    AJDWP_avatar_func('small');
});
add_action('AJDWP_avatar', 'AJDWP_avatar_func');


// Search Modal Function
function AJDWP_search_modal_func()
{
?>
    <div class="float-lg-end AJDWP_search_modal">
        <span class="fa fa-search fa-2x text-primary mx-4" data-bs-toggle="modal" data-bs-target="#myModal" style="cursor:pointer;"></span>
    </div>

    <!-- Search Modal -->
    <div class="search_modal modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <div class="modal-title h4"><?php esc_html_e('Search', 'hello-elementor-child'); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body my-3 d-flex justify-content-center align-items-center text-center">
                    <div class="AJDWP_search">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
add_action('AJDWP_search_modal', 'AJDWP_search_modal_func');


// Search Form
function AJDWP_search_form_func()
{
?>
    <div class="AJDWP_search_form">
        <?php get_search_form(); ?>
    </div>
<?php
}
add_action('AJDWP_search_form', 'AJDWP_search_form_func');
