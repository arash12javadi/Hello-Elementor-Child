<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//-------------------------------------------------------//
//------------------------Theme Settings Tab On Admin Side                   
//-------------------------------------------------------//


// Register main AJDWP Theme Settings page + "Create Pages" submenu
add_action('admin_menu', function () {
    // Main tabbed settings page
    add_menu_page(
        'AJDWP Theme Options',             // Page title
        'AJDWP Theme Settings',            // Menu title
        'manage_options',                  // Capability
        'AJDWP_Theme_Options',             // Menu slug
        'AJDWP_render_theme_options_page', // Callback
        'dashicons-admin-generic',         // Icon
        9999999999                         // Position
    );
});


//__________________________________________________________________________//
// Register settings and add settings fields
//__________________________________________________________________________//


// The page renderer with core WP nav-tab markup and 4 tab panes
function AJDWP_render_theme_options_page()
{ ?>
    <div class="wrap">
        <h1>AJDWP Theme Settings</h1>

        <h2 class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active">General</a>
            <a href="#uploads" class="nav-tab">Uploads</a>
            <a href="#seo" class="nav-tab">SEO</a>
            <a href="#roles" class="nav-tab">Roles</a>
            <a href="#pages" class="nav-tab">Pages</a>
            <a href="#theme-info" class="nav-tab">Theme Info</a>
        </h2>

        <?php settings_errors(); ?>

        <form id="theme-settings-form" method="post" action="options.php">
            <?php settings_fields('AJDWP_theme_options_group'); ?>

            <div id="general" class="tab-content active">
                <?php do_settings_sections('AJDWP_Theme_Options_general'); ?>
                <?php submit_button('Save General Settings'); ?>
            </div>

            <div id="uploads" class="tab-content">
                <?php do_settings_sections('AJDWP_Theme_Options_uploads'); ?>
                <?php submit_button('Save Upload Settings'); ?>
            </div>

            <div id="seo" class="tab-content">
                <?php do_settings_sections('AJDWP_Theme_Options_seo'); ?>
                <?php submit_button('Save SEO Settings'); ?>
            </div>

            <div id="roles" class="tab-content">
                <p id="roleDifference" style="color: #0073aa;cursor: pointer;">What is the difference between roles?</p>
                <?php do_settings_sections('AJDWP_Theme_Options_roles'); ?>
                <?php submit_button('Save Role Settings'); ?>
            </div>
        </form>

        <div id="pages" class="tab-content">
            <?php AJDWP_render_pages_tab(); ?>
        </div>

        <div id="theme-info" class="tab-content">
            <?php AJDWP_render_theme_info_tab(); ?>
        </div>

    </div>
<?php }


function AJDWP_render_pages_tab()
{
    echo '<h3>Create Necessary Pages</h3><br>';

    // --- User Profile Pages ---
    $user_profile_page = get_page_by_path('user-account');
    $pass_reset_page   = get_page_by_path('password-reset-page');

    if ($user_profile_page && $pass_reset_page) {
        echo '<p>All needed pages for <b>User profile</b> are created and ready to use :)</p>';
    } else {
        echo '<p>Create <b>User Profile</b> Pages for login, Register and Password Recovery: </p>';
        echo '<a href="' . esc_url(admin_url('?user_profile_pages=true')) . '" class="button button-primary">Create User Profile Pages</a><br>';
    }

    // --- User Dashboard Pages ---
    $my_comments_page = get_page_by_path('my-comments');
    $my_posts_page    = get_page_by_path('my-posts');
    $my_media_page    = get_page_by_path('my-media');

    if ($my_comments_page && $my_posts_page && $my_media_page) {
        echo '<p>All needed pages for <b>User dashboard</b> are created and ready to use :)</p>';
    } else {
        echo '<br><p>Create <b>User Dashboard</b> Pages in frontend:</p>';
        echo '<a href="' . esc_url(admin_url('?user_dash_pages=true')) . '" class="button button-primary">Create Dashboard Pages</a><br><br>';
    }

    // --- Privacy Policy ---
    echo '<h3>Privacy Policy and Cookies</h3><br>';

    $privacy_notice_page = get_page_by_path('privacy-notice');
    if ($privacy_notice_page) {
        echo '<p>The page <b>Privacy Notice</b> is created and policy sample contents are added :)</p>';
    } else {
        echo '<p>Create <b>Privacy Notice</b> Page and add pre-written policies to it: </p>';
        echo '<a href="' . esc_url(admin_url('?privacy_notice_page=true')) . '" class="button button-primary">Create Privacy Notice Page</a><br><br>';
    }

    // Load cookie settings UI
    include get_stylesheet_directory() . "/theme_addons/cookie_policy/cookie_policy_settings.php";
}


add_action('admin_init', 'AJDWP_Theme_settings_init');

function AJDWP_Theme_settings_init()
{
    // Register the option + validator
    register_setting(
        'AJDWP_theme_options_group',
        'AJDWP_theme_options',
        'AJDWP_theme_options_validate'
    );

    // ---------------------------------------------------------------------
    // Sections (1 per tab)
    // ---------------------------------------------------------------------
    add_settings_section(
        'AJDWP_general_section',
        'General Settings',
        null,
        'AJDWP_Theme_Options_general'
    );

    add_settings_section(
        'AJDWP_uploads_section',
        'Upload Restrictions',
        null,
        'AJDWP_Theme_Options_uploads'
    );

    add_settings_section(
        'AJDWP_seo_section',
        'SEO Settings',
        null,
        'AJDWP_Theme_Options_seo'
    );

    add_settings_section(
        'AJDWP_roles_section',
        'Role Settings',
        null,
        'AJDWP_Theme_Options_roles'
    );

    // ---------------------------------------------------------------------
    // Fields: GENERAL tab
    // ---------------------------------------------------------------------
    $general = [
        'show_page_title'                => 'Show Page or Post Title',
        'like_follow_system'             => 'Add Like & Follow to Theme',
        'post_views'                     => 'Post View Counter',
        'page_views'                     => 'Page View Counter',
        'post_publish_date'              => 'Post Publish Date',
        'page_publish_date'              => 'Page Publish Date',
        'theme_sidebars'                 => 'AJDWP Theme Sidebars',
        'hide_all_admin_notices'         => 'Hide All Admin Notices',
        'restrict_wp_admin_access'       => 'Restrict Admin Access',
        'remove_admin_bar'               => 'Hide Admin Bar',
        'set_author_archive_limit'       => 'Set Author Archive Limit',
        'stop_image_sizes'               => 'Stop Extra Image Sizes',
        'enqueue_frontend_media_scripts' => 'Load Media Library on Frontend',
        'custom_menu_link'               => 'Custom Menu Link URL',
        'custom_avatar_url'              => 'Custom Avatar URL',
        'limit_post_access'              => 'Users see only their own posts',
        'limit_media_library_access'     => 'Users see only their own uploaded medias',
        'limit_author_comments'          => 'Users see only their own Comments',
        'woocommerce_theme_support'      => 'Woocommerce Theme Support',
        'woocommerce_mini_cart_on_navbar' => 'Woocommerce mini cart on Navbar',
    ];
    foreach ($general as $key => $label) {
        add_settings_field(
            $key,
            $label,
            'AJDWP_Theme_function_checkbox',
            'AJDWP_Theme_Options_general',
            'AJDWP_general_section',
            ['label_for' => $key]
        );
    }

    // Fields with extra sub-fields (keep in GENERAL tab)
    add_settings_field(
        'custom_excerpt_length',
        'Custom Excerpt Length',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options_general',
        'AJDWP_general_section',
        ['label_for' => 'custom_excerpt_length']
    );

    add_settings_field(
        'redirect_login_page',
        'Redirect Login/logout Page',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options_general',
        'AJDWP_general_section',
        ['label_for' => 'redirect_login_page']
    );

    // ---------------------------------------------------------------------
    // Fields: UPLOADS tab
    // ---------------------------------------------------------------------
    add_settings_field(
        'limit_uploads',
        'Media Upload Settings',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options_uploads',
        'AJDWP_uploads_section',
        ['label_for' => 'limit_uploads']
    );

    // ---------------------------------------------------------------------
    // Fields: SEO tab
    // ---------------------------------------------------------------------
    $seo = [
        'add_meta_keywords'     => 'Add Meta Keywords Field',
        'add_meta_descriptions' => 'Add Meta Descriptions Field',
    ];
    foreach ($seo as $key => $label) {
        add_settings_field(
            $key,
            $label,
            'AJDWP_Theme_function_checkbox',
            'AJDWP_Theme_Options_seo',
            'AJDWP_seo_section',
            ['label_for' => $key]
        );
    }

    add_settings_field(
        'google_tag_manager',
        'Google Tag Manager',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options_seo',
        'AJDWP_seo_section',
        ['label_for' => 'google_tag_manager']
    );

    // ---------------------------------------------------------------------
    // Fields: ROLES tab
    // ---------------------------------------------------------------------
    $roles = [
        'contributor_can_upload' => 'Contributor Upload Capability',
        'contributor_can_post'   => 'Contributor Post Capability',
        'subscriber_can_upload'  => 'Subscriber Upload Capability',
        'subscriber_can_post'    => 'Subscriber Post Capability',
    ];
    foreach ($roles as $key => $label) {
        add_settings_field(
            $key,
            $label,
            'AJDWP_Theme_function_checkbox',
            'AJDWP_Theme_Options_roles',
            'AJDWP_roles_section',
            ['label_for' => $key]
        );
    }


    // ---------------------------------------------------------------------
    // Defaults (unchanged from your original)
    // ---------------------------------------------------------------------
    $options = get_option('AJDWP_theme_options');
    if ($options === false) {
        $default_options = [
            'show_page_title' => 1,
            'like_follow_system' => 1,
            'theme_sidebars' => 1,
            'disable_yoast_metabox' => 1,
            'remove_yoast_seo_columns' => 1,
            'custom_menu_link' => 1,
            'custom_avatar_url' => 1,
            'enqueue_frontend_media_scripts' => 1,
            'hide_all_admin_notices' => 1,
            'restrict_wp_admin_access' => 1,
            'remove_admin_bar' => 1,
            'redirect_login_page' => 1,
            'custom_excerpt_length' => 1,
            'set_author_archive_limit' => 1,
            'post_views' => 1,
            'page_views' => 1,
            'stop_image_sizes' => 1,
            'limit_post_access' => 1,
            'limit_media_library_access' => 1,
            'limit_author_comments' => 1,
            'contributor_can_upload' => '',
            'contributor_can_post' => '',
            'subscriber_can_upload' => 1,
            'subscriber_can_post' => '',
            'limit_uploads' => 1,
            'woocommerce_theme_support' => 1,
            'woocommerce_mini_cart_on_navbar' => 1,
            'add_meta_keywords' => 1,
            'add_meta_descriptions' => 1,
            'editor_disk_usage_limit' => 100,
            'author_disk_usage_limit' => 20,
            'contributor_disk_usage_limit' => 10,
            'subscriber_disk_usage_limit' => 2,
            'entered_email_for_disk_usage_limit' => [],
            'entered_amount_for_disk_usage_limit' => [],
            'max_upload_size' => 500,
            'max_image_height' => 1440,
            'max_image_width' => 1980,
            'min_image_height' => 300,
            'min_image_width' => 300,
            'google_tag_manager' => '',
            'gtm_header_script' => '',
            'gtm_body_script' => '',
            'post_publish_date' => 1,
            'page_publish_date' => 1,
        ];
        update_option('AJDWP_theme_options', $default_options);
    }
}


function AJDWP_Theme_function_checkbox($args)
{
    $options = get_option('AJDWP_theme_options');
    $checked = isset($options[$args['label_for']]) ? (bool) $options[$args['label_for']] : false;
?>
    <input type="checkbox"
        id="<?php echo esc_attr($args['label_for']); ?>"
        name="AJDWP_theme_options[<?php echo esc_attr($args['label_for']); ?>]"
        value="1"
        <?php checked($checked, true); ?>
        onchange="toggleFields('<?php echo esc_attr($args['label_for']); ?>')">
    <?php

    if ($args['label_for'] === 'custom_excerpt_length') {
    ?>
        <div id="excerpt_fields" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
            <br>
            <label for="excerpt_author_length">Author Excerpt Length:</label>
            <input type="number" id="excerpt_author_length" name="AJDWP_theme_options[excerpt_author_length]" value="<?php echo isset($options['excerpt_author_length']) ? esc_attr($options['excerpt_author_length']) : 50; ?>">
            <hr style="width:50%;text-align:left;margin-left:0">
            <label for="excerpt_general_length">General Excerpt Length:</label>
            <input type="number" id="excerpt_general_length" name="AJDWP_theme_options[excerpt_general_length]" value="<?php echo isset($options['excerpt_general_length']) ? esc_attr($options['excerpt_general_length']) : 100; ?>">
            <hr style="width:50%;text-align:left;margin-left:0">
        </div>
    <?php
    }

    if ($args['label_for'] === 'redirect_login_page') {
    ?>
        <div id="login_page_field" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
            <br>
            <label for="login_page_url">Custom Login Page URL:</label>
            <input type="text" id="login_page_url" name="AJDWP_theme_options[login_page_url]" value="<?php echo isset($options['login_page_url']) ? esc_attr($options['login_page_url']) : home_url(); ?>">
        </div>
    <?php
    }



    if ($args['label_for'] === 'limit_uploads') {
    ?>
        <div id="user_upload_settings">

            <div id="disk_usage_limit_field" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
                <label for="editor_disk_usage_limit"><i><b>Editors</b> Allocated Disk Space <strong>(MB)</strong>:</i></label>
                <input type="number" id="editor_disk_usage_limit" name="AJDWP_theme_options[editor_disk_usage_limit]" value="<?php echo isset($options['editor_disk_usage_limit']) ? esc_attr($options['editor_disk_usage_limit']) : 100; ?>">
                <br>
                <label for="author_disk_usage_limit"><i><b>Authors</b> Allocated Disk Space <strong>(MB)</strong>:</i></label>
                <input type="number" id="author_disk_usage_limit" name="AJDWP_theme_options[author_disk_usage_limit]" value="<?php echo isset($options['author_disk_usage_limit']) ? esc_attr($options['author_disk_usage_limit']) : 20; ?>">
                <br>
                <label for="contributor_disk_usage_limit"><i><b>Contributors</b> Allocated Disk Space <strong>(MB)</strong>:</i></label>
                <input type="number" id="contributor_disk_usage_limit" name="AJDWP_theme_options[contributor_disk_usage_limit]" value="<?php echo isset($options['contributor_disk_usage_limit']) ? esc_attr($options['contributor_disk_usage_limit']) : 10; ?>">
                <br>
                <label for="subscriber_disk_usage_limit"><i><b>Subscribers</b> Allocated Disk Space <strong>(MB)</strong>:</i></label>
                <input type="number" id="subscriber_disk_usage_limit" name="AJDWP_theme_options[subscriber_disk_usage_limit]" value="<?php echo isset($options['subscriber_disk_usage_limit']) ? esc_attr($options['subscriber_disk_usage_limit']) : 2; ?>">
            </div>

            <hr style="width:50%;text-align:left;margin-left:0">

            <div id="max_upload_size_field" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
                <label for="max_upload_size">Enter <strong>Max Upload Size (kb)</strong> :</label>
                <input type="number" id="max_upload_size" name="AJDWP_theme_options[max_upload_size]" value="<?php echo isset($options['max_upload_size']) ? esc_attr($options['max_upload_size']) : 500; ?>">
            </div>

            <hr style="width:50%;text-align:left;margin-left:0">

            <div id="max_image_size_field" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
                <label for="max_image_width">Enter Max Image Size Allowed (px):</label><br>
                <strong>Width : </strong>
                <input type="number" id="max_image_width" name="AJDWP_theme_options[max_image_width]"
                    value="<?php echo isset($options['max_image_width']) ? esc_attr($options['max_image_width']) : 1980; ?>">
                <label for="max_image_height"></label>
                <strong>Height : </strong>
                <input type="number" id="max_image_height" name="AJDWP_theme_options[max_image_height]"
                    value="<?php echo isset($options['max_image_height']) ? esc_attr($options['max_image_height']) : 1440; ?>">
            </div>

            <hr style="width:50%;text-align:left;margin-left:0">

            <div id="min_image_size_field" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
                <label for="min_image_width">Enter Min Image Size Allowed (px):</label><br>
                <strong>Width : </strong>
                <input type="number" id="min_image_width" name="AJDWP_theme_options[min_image_width]"
                    value="<?php echo isset($options['min_image_width']) ? esc_attr($options['min_image_width']) : 300; ?>">
                <label for="min_image_height"></label>
                <strong>Height : </strong>
                <input type="number" id="min_image_height" name="AJDWP_theme_options[min_image_height]"
                    value="<?php echo isset($options['min_image_height']) ? esc_attr($options['min_image_height']) : 300; ?>">
            </div>

            <hr style="width:50%;text-align:left;margin-left:0">

            <h3>Allocate Disk Space to Users</h3>
            <div id="user_disk_space_container">
                <?php
                $emails = isset($options['entered_email_for_disk_usage_limit']) ? $options['entered_email_for_disk_usage_limit'] : [];
                $disk_spaces = isset($options['entered_amount_for_disk_usage_limit']) ? $options['entered_amount_for_disk_usage_limit'] : [];
                foreach ($emails as $index => $email) {
                ?>
                    <div class="user-disk-space-row">
                        <label for="user_email_<?php echo $index; ?>">User Email:</label>
                        <input type="email" id="user_email_<?php echo $index; ?>" name="AJDWP_theme_options[entered_email_for_disk_usage_limit][]" value="<?php echo esc_attr($email); ?>" required>
                        <label for="disk_space_<?php echo $index; ?>">Disk Space (MB):</label>
                        <input type="number" id="disk_space_<?php echo $index; ?>" name="AJDWP_theme_options[entered_amount_for_disk_usage_limit][]" value="<?php echo esc_attr($disk_spaces[$index]); ?>" min="0" required>
                        <button type="button" class="remove-row">Remove</button>
                    </div>
                <?php
                }
                ?>
            </div>
            <button type="button" id="add_user_disk_space">Add User</button>
            <hr style="width:50%;text-align:left;margin-left:0">

        </div>
    <?php

    }

    if ($args['label_for'] === 'google_tag_manager') {
    ?>
        <div id="google_tag_manager_field" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
            <br>
            <label for="gtm_header_script">Insert Google Tag Manager header script here: </label>
            <br>
            <textarea id="gtm_header_script" name="AJDWP_theme_options[gtm_header_script]" rows="5" cols="50" class="gtm_header_script"><?php echo isset($options['gtm_header_script']) ? esc_textarea($options['gtm_header_script']) : ''; ?></textarea>
            <br><br>
            <label for="gtm_body_script">Insert Google Tag Manager body script here: </label>
            <br>
            <textarea id="gtm_body_script" name="AJDWP_theme_options[gtm_body_script]" rows="5" cols="50" class="gtm_body_script"><?php echo isset($options['gtm_body_script']) ? esc_textarea($options['gtm_body_script']) : ''; ?></textarea>
            <br><br>
        </div>
<?php
    }
}


function enqueue_admin_scripts()
{
    // Enqueue JavaScript
    wp_enqueue_script(
        'ajdwp-admin-scripts',
        get_stylesheet_directory_uri() . '/theme_addons/ajdwp_theme_settings/2_admin-scripts.js',
        array('jquery'),
        null,
        true
    );

    // Enqueue CSS
    wp_enqueue_style(
        'ajdwp-admin-styles',
        get_stylesheet_directory_uri() . '/theme_addons/ajdwp_theme_settings/3_admin-styles.css',
        array(),
        null
    );

    // Localize script to add Role Comparison Table
    wp_localize_script('ajdwp-admin-scripts', 'RoleComparisonTable', array(
        'htmlFilePath' => get_stylesheet_directory_uri() . '/theme_addons/ajdwp_theme_settings/roles-comparison-table.html'
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');


function AJDWP_theme_options_validate($input)
{
    // Normalise missing checkbox keys to 0
    $checkbox_keys = [
        'show_page_title',
        'like_follow_system',
        'post_views',
        'page_views',
        'post_publish_date',
        'page_publish_date',
        'theme_sidebars',
        'disable_yoast_metabox',
        'remove_yoast_seo_columns',
        'custom_menu_link',
        'custom_avatar_url',
        'enqueue_frontend_media_scripts',
        'hide_all_admin_notices',
        'restrict_wp_admin_access',
        'remove_admin_bar',
        'redirect_login_page',
        'custom_excerpt_length',
        'set_author_archive_limit',
        'stop_image_sizes',
        'limit_post_access',
        'limit_media_library_access',
        'limit_author_comments',
        'contributor_can_upload',
        'contributor_can_post',
        'subscriber_can_upload',
        'subscriber_can_post',
        'limit_uploads',
        'woocommerce_theme_support',
        'woocommerce_mini_cart_on_navbar',
        'add_meta_keywords',
        'add_meta_descriptions',
        'google_tag_manager'
    ];
    foreach ($checkbox_keys as $k) {
        $input[$k] = !empty($input[$k]) ? 1 : 0;
    }

    // Arrays: dedupe/sanitise
    $emails = array_map('sanitize_email', $input['entered_email_for_disk_usage_limit'] ?? []);
    $emails = array_values(array_unique(array_filter($emails)));
    $amounts = array_map('intval', $input['entered_amount_for_disk_usage_limit'] ?? []);
    $input['entered_email_for_disk_usage_limit']  = $emails;
    $input['entered_amount_for_disk_usage_limit'] = array_values($amounts);

    // Integers
    foreach (['editor_disk_usage_limit', 'author_disk_usage_limit', 'contributor_disk_usage_limit', 'subscriber_disk_usage_limit', 'max_upload_size', 'max_image_height', 'max_image_width', 'min_image_height', 'min_image_width'] as $k) {
        if (isset($input[$k])) $input[$k] = (int) $input[$k];
    }
    // Scripts (GTM) — allow the tags we actually need
    $ajdwp_allowed_gtm_tags = [
        'script' => [
            'type'        => true,
            'src'         => true,
            'async'       => true,
            'defer'       => true,
            'nonce'       => true,
            'crossorigin' => true,
        ],
        'noscript' => [],  // for GTM <noscript> fallback
        'iframe' => [
            'src'            => true,
            'height'         => true,
            'width'          => true,
            'style'          => true,
            'frameborder'    => true,
            'scrolling'      => true,
            'referrerpolicy' => true,
            'allow'          => true,
            'sandbox'        => true,
        ],
    ];

    foreach (['gtm_header_script', 'gtm_body_script'] as $k) {
        if (isset($input[$k]) && is_string($input[$k])) {
            // Optional: strip HTML comments if you don’t want to store them
            $clean = preg_replace('/<!--.*?-->/s', '', $input[$k]);
            $input[$k] = wp_kses($clean, $ajdwp_allowed_gtm_tags);
        }
    }

    return $input;
}


function display_saved_disk_usage_limits()
{
    $options = get_option('AJDWP_theme_options');

    if (empty($options['entered_email_for_disk_usage_limit']) || empty($options['entered_amount_for_disk_usage_limit'])) {
        return 'No disk usage limits have been set.';
    }

    $output = '<h3>Saved Disk Usage Limits</h3><ul>';

    foreach ($options['entered_email_for_disk_usage_limit'] as $index => $email) {
        $disk_space = isset($options['entered_amount_for_disk_usage_limit'][$index]) ? $options['entered_amount_for_disk_usage_limit'][$index] : 'N/A';
        $output .= '<li>Email: ' . esc_html($email) . ' - Disk Space: ' . esc_html($disk_space) . ' MB</li>';
    }

    $output .= '</ul>';

    return $output;
}
add_shortcode('show_disk_usage_limits', 'display_saved_disk_usage_limits');


function AJDWP_render_theme_info_tab()
{
    // Look in child theme first, then parent
    $relative = 'theme_addons/ajdwp_theme_settings/theme-info.html';
    $path = locate_template($relative, false, false);

    if ($path && file_exists($path)) {
        include $path; // outputs the HTML directly
        return;
    }

    // Fallback in case file is missing
    echo '<div class="notice notice-error"><p>Theme Info file not found: ' . esc_html($relative) . '</p></div>';
}


?>