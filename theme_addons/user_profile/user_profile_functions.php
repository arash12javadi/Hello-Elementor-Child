<?php
//_____________________________________ user_profile_functions.php _____________________________________//

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//--------------------------- Helper Functions(Error handlers & input sanitizers) ---------------------------//
//--------------------------- Helper Functions(Error handlers & input sanitizers) ---------------------------//
//--------------------------- Helper Functions(Error handlers & input sanitizers) ---------------------------//

// ---------------- Helper: collect errors ----------------
function AJDWP_errors()
{
    static $wp_error;
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error());
}

// ---------------- Helper: echo errors (translated + escaped) ----------------
function AJDWP_register_messages()
{
    $codes = AJDWP_errors()->get_error_codes();
    if (empty($codes)) {
        return;
    }
    echo '<div class="alert alert-danger">';
    foreach ($codes as $code) {
        $message = AJDWP_errors()->get_error_message($code);
        printf(
            '<span class="error"><strong>%s</strong>: %s</span><br/>',
            esc_html__('Error', 'hello-elementor-child'),
            esc_html($message)
        );
    }
    echo '</div>';
}

// ---------------- Sanitizers (store-safe, not output-escaping) ----------------

// Plain text (names, simple fields)
function AJDWP_clean_text($value)
{
    return sanitize_text_field(wp_unslash($value));
}

// Username (strict)
function AJDWP_clean_username($value)
{
    return sanitize_user(wp_unslash($value), true); // strict
}

// Email
function AJDWP_clean_email($value)
{
    $email = sanitize_email(wp_unslash($value));
    if ($email && is_email($email)) {
        return $email;
    }
    AJDWP_errors()->add(
        'email_invalid_naughty',
        __('Naughty characters not allowed in your email field.', 'hello-elementor-child')
    );
    return false;
}

// Password: DO NOT sanitize beyond unslashing + trim (keep all characters)
function AJDWP_clean_password($value)
{
    return trim(wp_unslash((string) $value));
}

// ================== LOGIN REDIRECT/ERROR HANDLERS (CLASSIC FORM ONLY) ==================

// Failure → back to referer with ?login=failed (DON'T run for AJAX/REST)
add_action('wp_login_failed', function ($username) {
    if (wp_doing_ajax() || (function_exists('wp_doing_rest') && wp_doing_rest())) {
        return;
    }

    $target = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : wp_get_referer();
    if (!$target || strpos($target, 'wp-login.php') !== false) {
        $target = home_url('/');
    }
    wp_safe_redirect(add_query_arg('login', 'failed', $target));
    exit;
});

// Empty creds → back with ?login=empty (classic wp-login.php form only; NOT AJAX)
add_filter('authenticate', function ($user, $username, $password) {
    if (wp_doing_ajax() || (function_exists('wp_doing_rest') && wp_doing_rest())) {
        return $user;
    }

    // Only act when default wp-login.php fields are used
    if (isset($_POST['log'], $_POST['pwd']) && ('' === $username || '' === $password)) {
        $target = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : wp_get_referer();
        if (!$target || strpos($target, 'wp-login.php') !== false) {
            $target = home_url('/');
        }
        wp_safe_redirect(add_query_arg('login', 'empty', $target));
        exit;
    }
    return $user;
}, 30, 3);

// Success → author archive (safe for both classic & AJAX)
add_filter('login_redirect', function ($redirect_to, $requested, $user) {
    if ($user instanceof WP_User) {
        return get_author_posts_url($user->ID);
    }
    return $redirect_to;
}, 10, 3);

// Render notices where your classic form lives (optional)
function ajdwp_render_login_notices(): void
{
    if (empty($_GET['login'])) return;

    $code = sanitize_text_field(wp_unslash($_GET['login']));
    $messages = [
        'failed' => __('Invalid username or password.', 'hello-elementor-child'),
        'empty'  => __('Please enter both username and password.', 'hello-elementor-child'),
    ];
    if (isset($messages[$code])) {
        printf('<div class="alert alert-danger" role="alert" aria-live="polite">%s</div>', esc_html($messages[$code]));
    }
}
add_action('init', function () {
    add_shortcode('ajdwp_login_notices', function () {
        ob_start();
        ajdwp_render_login_notices();
        return ob_get_clean();
    });
});


//--------------------------- Register New User ---------------------------//
//--------------------------- Register New User ---------------------------//
//--------------------------- Register New User ---------------------------//

function AJDWP_add_new_user()
{
    // Nonce check
    check_ajax_referer('ajax_user_register_nonce', 'AJDWP_csrf_nonce');

    // Optional: honor current language posted by front-end so errors are localized
    if (isset($_POST['lang']) && $_POST['lang'] !== '') {
        $lang   = sanitize_text_field(wp_unslash($_POST['lang']));
        $locale = (strpos($lang, 'fa') === 0) ? 'fa_IR' : $lang;
        switch_to_locale($locale);
    }
    // Make sure translations are loaded in AJAX context
    load_child_theme_textdomain('hello-elementor-child', get_stylesheet_directory() . '/languages');

    // ---------- Gather & clean inputs ----------
    $user_login    = isset($_POST['AJDWP_user_login'])        ? AJDWP_clean_username($_POST['AJDWP_user_login']) : '';
    $user_email    = isset($_POST['AJDWP_user_email'])        ? AJDWP_clean_email($_POST['AJDWP_user_email'])     : '';
    $user_first    = isset($_POST['AJDWP_user_first'])        ? AJDWP_clean_text($_POST['AJDWP_user_first'])      : '';
    $user_last     = isset($_POST['AJDWP_user_last'])         ? AJDWP_clean_text($_POST['AJDWP_user_last'])       : '';
    $user_pass     = isset($_POST['AJDWP_user_pass'])         ? AJDWP_clean_password($_POST['AJDWP_user_pass'])   : '';
    $pass_confirm  = isset($_POST['AJDWP_user_pass_confirm']) ? AJDWP_clean_password($_POST['AJDWP_user_pass_confirm']) : '';

    // Role (never trust raw role from client)
    $requested_role = isset($_POST['AJDWP_user_role']) ? AJDWP_clean_text($_POST['AJDWP_user_role']) : '';
    $allowed_roles  = apply_filters('ajdwp_allowed_registration_roles', ['subscriber', 'customer']);
    $user_role      = in_array($requested_role, $allowed_roles, true) ? $requested_role : 'subscriber';

    // ---------- Validate ----------
    if ($user_login === '') {
        AJDWP_errors()->add('username_empty', __('Please enter a username', 'hello-elementor-child'));
    } elseif (!validate_username($user_login)) {
        AJDWP_errors()->add('username_invalid', __('Invalid username', 'hello-elementor-child'));
    } elseif (username_exists($user_login)) {
        AJDWP_errors()->add('username_unavailable', __('Username already taken', 'hello-elementor-child'));
    }

    if (!$user_email) {
        AJDWP_errors()->add('email_invalid', __('Invalid email', 'hello-elementor-child'));
    } elseif (email_exists($user_email)) {
        AJDWP_errors()->add('email_used', __('Email already registered', 'hello-elementor-child'));
    }

    if ($user_pass === '') {
        AJDWP_errors()->add('password_empty', __('Please enter a password', 'hello-elementor-child'));
    } elseif ($user_pass !== $pass_confirm) {
        AJDWP_errors()->add('password_mismatch', __('Passwords do not match', 'hello-elementor-child'));
    } else {
        // Optional minimal strength check (matches your change password logic)
        $has_upper   = (bool) preg_match('/[A-Z]/', $user_pass);
        $has_number  = (bool) preg_match('/\d/',    $user_pass);
        $has_special = (bool) preg_match('/[^A-Za-z0-9]/', $user_pass);
        $len_ok      = strlen($user_pass) >= 8;

        if (!$has_upper || !$has_number || !$has_special || !$len_ok) {
            AJDWP_errors()->add('password_weak', __('Password does not meet the required strength.', 'hello-elementor-child'));
        }
    }

    // ---------- Bail on errors ----------
    $errors = AJDWP_errors()->get_error_messages();
    if (!empty($errors)) {
        wp_send_json_error([
            'errors' => $errors
        ]);
    }

    // ---------- Create user ----------
    $new_user_id = wp_insert_user([
        'user_login'      => $user_login,
        'user_pass'       => $user_pass,
        'user_email'      => $user_email,
        'first_name'      => $user_first,
        'last_name'       => $user_last,
        'user_registered' => current_time('mysql'),
        'role'            => $user_role,
    ]);

    if (is_wp_error($new_user_id)) {
        AJDWP_errors()->add('register_failed', $new_user_id->get_error_message());
        wp_send_json_error([
            'errors' => AJDWP_errors()->get_error_messages()
        ], 500);
    }

    // Notify (to user only; adjust to 'both' if you want admin too)
    if (function_exists('wp_new_user_notification')) {
        // WP 4.9+ signature: wp_new_user_notification( int $user_id, null, string $notify = 'both|user|admin' )
        wp_new_user_notification($new_user_id, null, 'user');
    }

    // Auto-login newly registered user
    wp_set_current_user($new_user_id);
    wp_set_auth_cookie($new_user_id, true);
    do_action('wp_login', $user_login, get_userdata($new_user_id));

    wp_send_json_success([
        'message'  => __('Registration successful.', 'hello-elementor-child'),
        'redirect' => home_url('/'),
    ]);
}
add_action('wp_ajax_user_register_ajax', 'AJDWP_add_new_user');
add_action('wp_ajax_nopriv_user_register_ajax', 'AJDWP_add_new_user');


//--------------------------- Forgot Password ---------------------------//
//--------------------------- Forgot Password ---------------------------//
//--------------------------- Forgot Password ---------------------------//


add_action('wp_ajax_nopriv_custom_reset_password', 'AJDWP_custom_reset_password');
add_action('wp_ajax_custom_reset_password',        'AJDWP_custom_reset_password');

function AJDWP_custom_reset_password()
{
    check_ajax_referer('ajax-forgot-nonce', 'security');

    // Optional: switch locale for translated messages
    if (isset($_POST['lang']) && $_POST['lang'] !== '') {
        $lang   = sanitize_text_field(wp_unslash($_POST['lang']));
        $locale = (strpos($lang, 'fa') === 0) ? 'fa_IR' : $lang;
        switch_to_locale($locale);
        load_child_theme_textdomain('hello-elementor-child', get_stylesheet_directory() . '/languages');
    }

    $login = isset($_POST['user_login']) ? sanitize_text_field(wp_unslash($_POST['user_login'])) : '';

    if ($login === '') {
        wp_send_json_error(['errors' => [__('Please enter your username or email.', 'hello-elementor-child')]]);
    }

    // Core lost-password
    $_POST['user_login'] = $login;
    $result = retrieve_password();

    if (true === $result) {
        wp_send_json_success([
            'message' => __('Password reset link sent. Check your email.', 'hello-elementor-child'),
        ]);
    }

    $errors = [];
    if (is_wp_error($result)) {
        foreach ((array) $result->errors as $messages) {
            foreach ($messages as $m) {
                $errors[] = $m;
            }
        }
    }
    if (empty($errors)) {
        $errors[] = __('Something went wrong. Please try again.', 'hello-elementor-child');
    }

    wp_send_json_error(['errors' => $errors]);
}


//------------------------------------- set new password by emailed reset link shortcode ------------------------------------//
//------------------------------------- set new password by emailed reset link shortcode ------------------------------------//
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
//------------------------------------- Admin Button to make needed pages ------------------------------------//
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



//--------------------------- DELETE ACCOUNT ---------------------------//
//--------------------------- DELETE ACCOUNT ---------------------------//
//--------------------------- DELETE ACCOUNT ---------------------------//

add_action('wp_ajax_delete_user_account', 'ajdwp_delete_user_account');

function ajdwp_delete_user_account()
{
    check_ajax_referer('delete_user_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('You must be logged in.', 'hello-elementor-child')], 401);
    }

    $current_id = get_current_user_id();
    $user       = get_userdata($current_id);

    // Optional locale switch
    if (!empty($_POST['lang'])) {
        $lang   = sanitize_text_field(wp_unslash($_POST['lang']));
        $locale = (strpos($lang, 'fa') === 0) ? 'fa_IR' : $lang;
        switch_to_locale($locale);
        load_child_theme_textdomain('hello-elementor-child', get_stylesheet_directory() . '/languages');
    }

    if (user_can($user, 'administrator')) {
        wp_send_json_error(['message' => __('Admins cannot delete their account from the front-end.', 'hello-elementor-child')], 403);
    }

    if (empty($_POST['confirmed']) || $_POST['confirmed'] !== '1') {
        wp_send_json_error(['message' => __('Please confirm you understand the consequences.', 'hello-elementor-child')]);
    }

    // Allow self-delete without delete_users cap
    $allow_self_delete = function ($caps, $cap, $user_id, $args) use ($current_id) {
        if ($cap === 'delete_user' && !empty($args[0]) && intval($args[0]) === $current_id && $user_id === $current_id) {
            return ['exist'];
        }
        return $caps;
    };
    add_filter('map_meta_cap', $allow_self_delete, 10, 4);

    require_once ABSPATH . 'wp-admin/includes/user.php';

    $reassign_to = null; // set to a user ID to keep content, or null to delete all content

    $deleted = wp_delete_user($current_id, $reassign_to);

    remove_filter('map_meta_cap', $allow_self_delete, 10);

    if (!$deleted) {
        wp_send_json_error(['message' => __('Could not delete account.', 'hello-elementor-child')], 500);
    }

    wp_send_json_success([
        'message'  => __('Your account has been deleted.', 'hello-elementor-child'),
        'redirect' => home_url('/'),
    ]);
}
