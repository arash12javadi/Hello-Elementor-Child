<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//__________________________________________________________________________//
//__________________________________________________________________________//
//                          register a new user
//__________________________________________________________________________//
//__________________________________________________________________________//


function AJDWP_add_new_user()
{
    check_ajax_referer('ajax_user_register_nonce', 'AJDWP_csrf_nonce');

    $user_login = $_POST["AJDWP_user_login"];
    $cleaned_input_user_login = sanitize_user($user_login);

    $user_email = $_POST["AJDWP_user_email"];
    $cleanedEmail = sanitize_email($user_email);

    $user_first = $_POST["AJDWP_user_first"];
    $cleaned_input_user_first = sanitize_text_field($user_first);

    $user_last = $_POST["AJDWP_user_last"];
    $cleaned_input_user_last = sanitize_text_field($user_last);

    $user_pass = $_POST["AJDWP_user_pass"];
    $cleaned_psw = stripslashes($user_pass);
    $pass_confirm = $_POST["AJDWP_user_pass_confirm"];

    $user_role = sanitize_text_field($_POST["AJDWP_user_role"]);

    // No need to include registration.php, remove this line
    // require_once(ABSPATH . WPINC . '/registration.php');

    if (username_exists($cleaned_input_user_login)) {
        AJDWP_errors()->add('username_unavailable', __('Username already taken', 'hello-elementor-child'));
    }
    if (!validate_username($cleaned_input_user_login)) {
        AJDWP_errors()->add('username_invalid', __('Invalid username', 'hello-elementor-child'));
    }
    if ($cleaned_input_user_login == '') {
        AJDWP_errors()->add('username_empty', __('Please enter a username', 'hello-elementor-child'));
    }
    if (!is_email($cleanedEmail)) {
        AJDWP_errors()->add('email_invalid', __('Invalid email', 'hello-elementor-child'));
    }
    if (email_exists($cleanedEmail)) {
        AJDWP_errors()->add('email_used', __('Email already registered', 'hello-elementor-child'));
    }
    if ($cleaned_psw == '') {
        AJDWP_errors()->add('password_empty', __('Please enter a password', 'hello-elementor-child'));
    }
    if ($user_pass != $pass_confirm) {
        AJDWP_errors()->add('password_mismatch', __('Passwords do not match', 'hello-elementor-child'));
    }

    $errors = AJDWP_errors()->get_error_messages();

    if (!empty($errors)) {
        wp_send_json_error(array('errors' => $errors));
    } else {
        $new_user_id = wp_insert_user(array(
            'user_login'        => $cleaned_input_user_login,
            'user_pass'         => $cleaned_psw,
            'user_email'        => $cleanedEmail,
            'first_name'        => $cleaned_input_user_first,
            'last_name'         => $cleaned_input_user_last,
            'user_registered'   => date('Y-m-d H:i:s'),
            'role'              => $user_role
        ));

        if ($new_user_id) {
            // Send an email to the admin
            // wp_new_user_notification($new_user_id);
            wp_new_user_notification($new_user_id, null, 'user');

            // Authenticate and log the new user in
            wp_set_auth_cookie($new_user_id, true);
            wp_set_current_user($new_user_id, $cleaned_input_user_login);
            do_action('wp_login', $cleaned_input_user_login, get_userdata($new_user_id));

            // Redirect to the home page after logging in
            // wp_redirect(home_url());
            wp_send_json_success(['redirect' => home_url()]);

            exit;
        }
    }
}

add_action('wp_ajax_user_register_ajax', 'AJDWP_add_new_user');
add_action('wp_ajax_nopriv_user_register_ajax', 'AJDWP_add_new_user');


// used for tracking error messages
function AJDWP_errors()
{
    static $wp_error; // global variable handle
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function AJDWP_register_messages()
{
    if ($codes = AJDWP_errors()->get_error_codes()) {
        echo '<div class="alert alert-danger">';
        // Loop error codes and display errors
        foreach ($codes as $code) {
            $message = AJDWP_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
        echo '</div>';
    }
}


function sanitize_and_validate_input($input)
{
    // Ensure magic quotes are off (if applicable)
    $input = stripslashes($input);

    // Remove HTML tags, strip whitespace, and ensure it's safe for storage/display
    $input = sanitize_text_field($input);

    // Escape HTML entities
    $input = esc_html($input);

    // Escape text for use in HTML attributes
    $input = esc_attr($input);

    // Define allowed HTML tags and attributes for wp_kses
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'em' => array(),
    );

    // Sanitize and validate HTML content using wp_kses
    $input = wp_kses($input, $allowed_html);

    // Remove all HTML tags
    $input = wp_strip_all_tags($input);

    // Return the sanitized and validated input
    return $input;
}



function sanitize_and_validate_email($email)
{
    // Ensure magic quotes are off (if applicable)
    $email = stripslashes($email);

    // Remove HTML tags, strip whitespace, and ensure it's safe for storage/display
    $email = sanitize_text_field($email);

    // Escape HTML entities
    $email = esc_html($email);

    // Escape text for use in HTML attributes
    $email = esc_attr($email);

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Return the sanitized and validated email
        return $email;
    } else {
        // Handle invalid email (you may choose to return an error or handle it as needed)
        AJDWP_errors()->add('email_invalid_naughty', __('Naughty characters not allowed in your email field.'), 'hello-elementor-child');
        return false;
    }
}


//__________________________________________________________________________//
//__________________________________________________________________________//
//                          Forgot Password
//__________________________________________________________________________//
//__________________________________________________________________________//

function custom_reset_password()
{
    check_ajax_referer('ajax-forgot-nonce', 'security');

    $user_login = isset($_POST['user_login']) ? sanitize_text_field($_POST['user_login']) : '';

    // ---- Validation ----
    if (empty($user_login)) {
        echo '<div class="alert alert-danger">' . __('Please enter a valid username or email.', 'hello-elementor-child') . '</div>';
        wp_die();
    }

    $user_data = get_user_by('login', $user_login) ?: get_user_by('email', $user_login);

    if (!$user_data) {
        echo '<div class="alert alert-danger">' . __('User not found. Please enter a valid username or email.', 'hello-elementor-child') . '</div>';
        wp_die();
    }

    // ---- Prepare reset link ----
    $user_email = $user_data->user_email;
    $reset_key  = get_password_reset_key($user_data);
    $reset_url  = esc_url(site_url('/password-reset-page/')) . '?key=' . rawurlencode($reset_key) . '&login=' . rawurlencode($user_data->user_login);

    // ---- Email message ----
    $subject = __('Password Reset Request', 'hello-elementor-child');
    $message  = __("Someone has requested a password reset for the following account:", 'hello-elementor-child') . "\r\n\r\n";
    $message .= __("Username:", 'hello-elementor-child') . ' ' . $user_data->user_login . "\r\n\r\n";
    $message .= __("If this was a mistake, just ignore this email and nothing will happen.", 'hello-elementor-child') . "\r\n\r\n";
    $message .= __("To reset your password, visit the following link:", 'hello-elementor-child') . "\r\n\r\n";
    $message .= $reset_url . "\r\n";

    // ---- Send email ----
    if (wp_mail($user_email, $subject, $message)) {
        echo '<div class="alert alert-success">' . __('Password reset link sent. Check your email.', 'hello-elementor-child') . '</div>';
    } else {
        echo '<div class="alert alert-danger">' . __('Something went wrong. Please try again later.', 'hello-elementor-child') . '</div>';
    }

    wp_die();
}


add_action('wp_ajax_custom_reset_password', 'custom_reset_password');
add_action('wp_ajax_nopriv_custom_reset_password', 'custom_reset_password');


//------------------------------------- set new password by emailed reset link shortcode ------------------------------------//


function custom_password_reset_form()
{
    if (isset($_GET['key']) && isset($_GET['login'])) { ?>

        <h5 class="AJDWP_up_header mt-4 m-2 fw-bold"><?php _e('Set a new Password For Your Account', 'hello-elementor-child'); ?></h5>

        <?php
        // show any error messages after form submission
        AJDWP_register_messages();
        ?>
        <div class="alert alert-success" role="alert" id="seccessfully_password_set" style="display: none;"> <?php _e('Password set successfully!', 'hello-elementor-child'); ?></div>
        <fieldset id="fieldset_mprf" style="display: block;">

            <form id="member-password-reset-form" class="AJDWP_form" method="post" action="" autocomplete="off">

                <p><?php _e('This form is specifically designed for the exclusive use of this user:', 'hello-elementor-child'); ?>
                    <span class="text-danger font-weight-bold"><?php echo (!empty($_GET['login'])) ? esc_html($_GET['login']) : ''; ?></span>
                </p>

                <p><?php _e('If the provided information does not match your username or email address, kindly exit the page.', 'hello-elementor-child'); ?>
                    <a href="<?php echo home_url(); ?>">&#8592; <?php _e('Go Home', 'hello-elementor-child'); ?></a>
                </p>

                <p>
                    <label for="new_password_reset"><?php _e('New Password:', 'hello-elementor-child'); ?></label>
                    <input type="password" name="new_password_reset" id="new_password_reset" autocomplete="off" required />
                </p>

                <div id="rpf_psw_message">
                    <h3><?php _e('Password must contain the following:', 'hello-elementor-child'); ?></h3>
                    <p id="Special_char_rpf" class="reg_psw_invalid"><?php esc_html_e('A Special Character', 'hello-elementor-child'); ?> [!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]</p>
                    <p id="capital_rpf" class="reg_psw_invalid"><?php esc_html_e('A Captal Letter', 'hello-elementor-child'); ?></p>
                    <p id="number_rpf" class="reg_psw_invalid"><?php esc_html_e('A Number', 'hello-elementor-child'); ?></p>
                    <p id="length_rpf" class="reg_psw_invalid"><?php esc_html_e('Minimum 8 Characters', 'hello-elementor-child'); ?></p>
                </div>

                <p>
                    <label for="repeat_new_password_reset"><?php _e('Repeat Password: ', 'hello-elementor-child'); ?></label>
                    <input type="password" name="repeat_new_password_reset" id="repeat_new_password_reset" autocomplete="off" required />
                </p>

                <?php wp_nonce_field('resetPassEmailedLink_form_nonce', 'resetPassEmailedLink_form_nonce_field'); ?>

                <p>
                    <input type="submit" id="new_password_reset_submit" class="new_password_reset_submit" name="new_password_reset_submit" value="Set New Password" />
                </p>

            </form>

        </fieldset>

        <?php } else {
        echo '<p>Invalid reset link.</p>';
    }
}

add_shortcode('password_reset_form', 'custom_password_reset_form');



function handle_password_reset()
{
    if (isset($_POST['new_password_reset_submit'])) {
        if (isset($_POST['resetPassEmailedLink_form_nonce_field']) && wp_verify_nonce($_POST['resetPassEmailedLink_form_nonce_field'], 'resetPassEmailedLink_form_nonce')) {
            $new_password_reset = sanitize_text_field($_POST['new_password_reset']);
            $repeat_new_password_reset = sanitize_text_field($_POST['repeat_new_password_reset']);
            if ($new_password_reset == '' || $repeat_new_password_reset == '') {
                // empty Fields
                AJDWP_errors()->add('prf_empty', __('Fields can’t be empty.', 'hello-elementor-child'));
            }
            if ($new_password_reset != $repeat_new_password_reset) {
                // passwords do not match
                AJDWP_errors()->add('prf_password_mismatch', __('Passwords do not match', 'hello-elementor-child'));
            }

            $prf_errors = AJDWP_errors()->get_error_messages();

            if (empty($prf_errors)) {

                $user_login = isset($_GET['login']) ? sanitize_text_field($_GET['login']) : '';
                $reset_key = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';

                $user_data = get_user_by('login', $user_login);

                if ($user_data && check_password_reset_key($reset_key, $user_login)) {
                    // Reset the user's password
                    wp_set_password($new_password_reset, $user_data->ID);
        ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var passwordResetForm = document.getElementById("member-password-reset-form");
                            var successMessage = document.getElementById("seccessfully_password_set");

                            // Check if the elements exist before manipulating them
                            if (passwordResetForm && successMessage) {
                                // Toggle visibility
                                passwordResetForm.style.display = (passwordResetForm.style.display === 'none') ? 'block' : 'none';
                                successMessage.style.display = 'block';
                            }
                        });
                    </script>
<?php

                } else {
                    AJDWP_errors()->add('prf_invalidLink', __('Invalid reset link or expired key', 'hello-elementor-child'));
                }
            }
        }
    }
}

// add_action('init', 'handle_password_reset');
add_action('template_redirect', 'handle_password_reset');


//------------------------------------- Admin Button to make needed pages ------------------------------------//

function create_custom_pages_once()
{
    if (!current_user_can('manage_options')) return;

    $page_definitions = array(
        'user-account' => array(
            'title' => 'User Account',
            'content' => '[AJDWP_register_form]'
        ),
        'password-reset-page' => array(
            'title' => 'Password Reset Page',
            'content' => '[password_reset_form]'
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

add_action('init', 'custom_page_creation_trigger');

function custom_page_creation_trigger()
{
    if (isset($_GET['user_profile_pages']) && $_GET['user_profile_pages'] === 'true') {
        create_custom_pages_once();
        // Redirect back to the admin dashboard or any other page
        wp_safe_redirect(admin_url('edit.php?post_type=page'));
        exit;
    }
}



?>