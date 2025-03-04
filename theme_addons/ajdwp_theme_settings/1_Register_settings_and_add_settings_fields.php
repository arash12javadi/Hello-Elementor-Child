<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//__________________________________________________________________________//
// Register settings and add settings fields
//__________________________________________________________________________//

add_action('admin_init', 'AJDWP_Theme_settings_init');

function AJDWP_Theme_settings_init()
{
    register_setting('AJDWP_theme_options_group', 'AJDWP_theme_options');

    add_settings_section(
        'AJDWP_theme_settings_section',
        'Manage Theme Functions',
        null,
        'AJDWP_Theme_Options'
    );

    $functions = [
        'show_page_title' => 'Show Page or Post Title',
        'like_follow_system' => 'Add Like & Follow to Theme',
        'post_views' => 'Post and Page View Counter',
        'post_publish_date' => 'Post Publish Date',
        'page_publish_date' => 'Page Publish Date',
        'secure_login' => 'Cookie Secure Login',
        'theme_sidebars' => 'AJDWP Theme Sidebars',
        'disable_yoast_metabox' => 'Disable Yoast SEO Metabox',
        'remove_yoast_seo_columns' => 'Remove Yoast SEO Columns',
        'custom_menu_link' => 'Custom Menu Link URL',
        'custom_avatar_url' => 'Custom Avatar URL',
        'enqueue_frontend_media_scripts' => 'Load Media Library on Frontend',
        'hide_all_admin_notices' => 'Hide All Admin Notices',
        'restrict_wp_admin_access' => 'Restrict Admin Access',
        'remove_admin_bar' => 'Hide Admin Bar',
        'redirect_login_page' => 'Redirect Login Page',
        'custom_excerpt_length' => 'Custom Excerpt Length',
        'set_author_archive_limit' => 'Set Author Archive Limit',
        'stop_image_sizes' => 'Stop Extra Image Sizes',
        'limit_post_access' => 'Users see only their own posts',
        'limit_media_library_access' => 'Users see only their own uploaded medias',
        'limit_author_comments' => 'Users see only their own Comments',
        'contributor_can_upload' => 'Contributor Upload Capability',
        'contributor_can_post' => 'Contributor Post Capability',
        'subscriber_can_upload' => 'Subscriber Upload Capability',
        'subscriber_can_post' => 'Subscriber Post Capability',
        'limit_uploads' => 'Limit File Uploads',
        'woocommerce_theme_support' => 'Woocommerce Theme Support',
        'woocommerce_mini_cart_on_navbar' => 'Woocommerce mini cart on Navbar',
        'add_meta_keywords' => 'Add Meta Keywords Field',
        'add_meta_descriptions' => 'Add Meta Descriptions Field',
        'google_tag_manager' => 'Google Tag Manager',
    ];

    foreach ($functions as $key => $label) {
        add_settings_field(
            $key,
            $label,
            'AJDWP_Theme_function_checkbox',
            'AJDWP_Theme_Options',
            'AJDWP_theme_settings_section',
            ['label_for' => $key]
        );
    }

    //--------------------------- setting fields for excerpts ---------------------------//
    add_settings_field(
        'custom_excerpt_length',
        'Custom Excerpt Length',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options',
        'AJDWP_theme_settings_section',
        ['label_for' => 'custom_excerpt_length']
    );

    //--------------------------- setting field for login link ---------------------------//
    add_settings_field(
        'redirect_login_page',
        'Redirect Login/logout Page',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options',
        'AJDWP_theme_settings_section',
        ['label_for' => 'redirect_login_page']
    );

    //--------------------------- setting field for uploads limit  ---------------------------//
    add_settings_field(
        'limit_uploads',
        'Media Upload Settings',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options',
        'AJDWP_theme_settings_section',
        ['label_for' => 'limit_uploads']
    );

    //--------------------------- setting field for uploads limit  ---------------------------//
    add_settings_field(
        'google_tag_manager',
        'Google Tag Manager',
        'AJDWP_Theme_function_checkbox',
        'AJDWP_Theme_Options',
        'AJDWP_theme_settings_section',
        ['label_for' => 'google_tag_manager']
    );

    //--------------------------- setting field for uploads limit  ---------------------------//


    // Set default options if not set
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
            'secure_login' => 1,
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
            <br>
            <h6 id="roleDifference" style="color: #0073aa;cursor: pointer;">What is the difference between roles?</h6>
            <hr style="width:50%;text-align:left;margin-left:0">
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
                <strong>Width : </strong><input type="number" id="max_image_width" name="AJDWP_theme_options[max_image_width]" value="<?php echo isset($options['max_image_width']) ? esc_attr($options['max_image_width']) : 1440; ?>">
                <label for="max_image_height"></label>
                <strong>Height : </strong><input type="number" id="max_image_height" name="AJDWP_theme_options[max_image_height]" value="<?php echo isset($options['max_image_height']) ? esc_attr($options['max_image_height']) : 1980; ?>">
            </div>
            <hr style="width:50%;text-align:left;margin-left:0">
            <div id="min_image_size_field" style="display: <?php echo $checked ? 'block' : 'none'; ?>">
                <label for="min_image_width">Enter Max Image Size Allowed (px):</label><br>
                <strong>Width : </strong><input type="number" id="min_image_width" name="AJDWP_theme_options[min_image_width]" value="<?php echo isset($options['min_image_width']) ? esc_attr($options['min_image_width']) : 1440; ?>">
                <label for="min_image_height"></label>
                <strong>Height : </strong><input type="number" id="min_image_height" name="AJDWP_theme_options[min_image_height]" value="<?php echo isset($options['min_image_height']) ? esc_attr($options['min_image_height']) : 1980; ?>">
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

    // Enqueue Bootstrap CSS from a CDN // Extra bootstrap unneeded for this task removed on 17.02.2025
    // wp_enqueue_style(
    //     'bootstrap-css',
    //     'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css',
    //     array(),
    //     '4.5.2'
    // );

    // Localize script to add Role Comparison Table
    wp_localize_script('ajdwp-admin-scripts', 'RoleComparisonTable', array(
        'htmlFilePath' => get_stylesheet_directory_uri() . '/theme_addons/ajdwp_theme_settings/roles-comparison-table.html'
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');



function AJDWP_theme_options_validate($input)
{
    $emails = array_map('sanitize_email', $input['entered_email_for_disk_usage_limit']);
    $disk_spaces = array_map('intval', $input['entered_amount_for_disk_usage_limit']);

    // Remove duplicates
    $unique_emails = array();
    foreach ($emails as $index => $email) {
        if (!in_array($email, $unique_emails)) {
            $unique_emails[] = $email;
        } else {
            // Remove corresponding disk space entry
            unset($disk_spaces[$index]);
        }
    }

    $input['entered_email_for_disk_usage_limit'] = $unique_emails;
    $input['entered_amount_for_disk_usage_limit'] = array_values($disk_spaces);

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

?>