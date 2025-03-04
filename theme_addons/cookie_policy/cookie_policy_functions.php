<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//-------------------- Add JavasCript to Admin Side --------------------//

function enqueue_cookie_policy_toggle_script()
{
    // Enqueue scripts and styles for the admin area
    if (is_admin()) {
        // Enqueue admin script
        wp_enqueue_script('admin-cookie-policy-toggle', get_stylesheet_directory_uri() . '/theme_addons/cookie_policy/cookie_policy_admin_side.js', array('jquery'), null, true);

        // Localize the script with AJAX URL and nonce
        wp_localize_script('admin-cookie-policy-toggle', 'cookiePopupData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cookie_popup_nonce')
        ));

        // Enqueue admin style
        wp_enqueue_style('cookie_policy_admin_side_style', get_stylesheet_directory_uri() . '/theme_addons/cookie_policy/cookie_policy_admin_side.css', array(), null);
    } else {
        // Enqueue Bootstrap CSS and JS only for the front-end
        // wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        // wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);

        // Enqueue front-end script
        wp_enqueue_script('frontend-cookie-policy', get_stylesheet_directory_uri() . '/theme_addons/cookie_policy/cookie_policy_frontend.js', array('jquery'), null, true);

        // Localize the front-end script with AJAX URL
        wp_localize_script('frontend-cookie-policy', 'cookiePolicyData', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_cookie_policy_toggle_script');
add_action('admin_enqueue_scripts', 'enqueue_cookie_policy_toggle_script');



//__________________________________________________________________________//
//			                Create Privacy Notice Page
//__________________________________________________________________________//

function create_privacy_page_once()
{
    $file_path = get_stylesheet_directory() . '/theme_addons/cookie_policy/policy.html';
    $policy_content = file_get_contents($file_path);
    $page_definitions = array(
        'privacy-notice' => array(
            'title' => 'Privacy Notice',
            'content' => $policy_content
        )
    );

    foreach ($page_definitions as $slug => $page) {
        // Check if the page already exists
        $existing_page = get_page_by_path($slug);

        // If the page doesn't exist, create it
        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title' => $page['title'],
                'post_name' => $slug,
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            ));

            // Assign the custom template
            update_post_meta($page_id, '_wp_page_template', 'template-custom.php');
        }
    }
}

add_action('init', 'privacy_page_creation_trigger');

function privacy_page_creation_trigger()
{
    if (isset($_GET['privacy_notice_page']) && $_GET['privacy_notice_page'] === 'true') {
        create_privacy_page_once();
        // Redirect back to the admin dashboard or any other page
        wp_safe_redirect(admin_url('edit.php?post_type=page'));
        exit;
    }
}



//__________________________________________________________________________//
//			                Create Cookies Popup
//__________________________________________________________________________//


function display_cookie_popup()
{
    // Get the status of the cookie popup from the database
    $enabled = get_option('cookies_popup_enabled', 'no'); // Default to 'no'

    // Default cookie popup text if none is set
    $default_cookie_popup_text = 'We use cookies to enhance your experience and ensure that your personal information remains confidential.';

    // Retrieve the cookie popup text from the database
    $cookie_popup_text = get_option('cookie_popup_text', '');
    // Use default text if the retrieved text is empty
    if (empty($cookie_popup_text)) {
        $cookie_popup_text = $default_cookie_popup_text;
    }

    // Default link for 'Read more' if none is set
    $default_cookie_popup_link = home_url('privacy-notice');

    $cookie_popup_link = get_option('cookie_popup_link', '');
    // Use default link if the retrieved link is empty
    if (empty($cookie_popup_link)) {
        $cookie_popup_link = $default_cookie_popup_link;
    }

    // Check if the popup is enabled
    if ($enabled === 'yes') {
?>
        <div class="modal fade" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="cookieModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cookieModalLabel">Privacy Notice</h5>
                    </div>
                    <div class="modal-body">
                        <?php echo esc_html($cookie_popup_text); ?> <a href="<?php echo esc_url($cookie_popup_link); ?>">Read more</a>.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="acceptCookies">ACCEPT &nbsp; <i class="fas fa-cookie-bite"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <style>
            #cookieModal .modal-header {
                border-bottom: none;
            }

            #cookieModal .modal-footer {
                border-top: none;
            }
        </style>

<?php
    }
}
add_action('wp_footer', 'display_cookie_popup');



// Register AJAX action 
add_action('wp_ajax_update_cookie_popup_setting', 'update_cookie_popup_setting');
add_action('wp_ajax_get_cookie_popup_setting', 'get_cookie_popup_setting');

function update_cookie_popup_setting()
{
    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'cookie_popup_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check if user has the right capability
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }

    // Sanitize and update the option
    $status = isset($_POST['enabled']) ? sanitize_text_field($_POST['enabled']) : 'no';
    update_option('cookies_popup_enabled', $status);

    wp_send_json_success('Setting updated');
}

function get_cookie_popup_setting()
{
    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'cookie_popup_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check if user has the right capability
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }

    // Retrieve the current setting
    $status = get_option('cookies_popup_enabled', 'no');

    wp_send_json_success(array('enabled' => $status === 'yes'));
}



function ajdwp_register_theme_settings()
{
    register_setting('ajdwp_theme_options_group_text', 'cookie_popup_text');
    register_setting('ajdwp_theme_options_group_link', 'cookie_popup_link');
}

add_action('admin_init', 'ajdwp_register_theme_settings');
