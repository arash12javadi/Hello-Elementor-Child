<?php  

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

//__________________________________________________________________________//
//__________________________________________________________________________//
//                          register a new user
//__________________________________________________________________________//
//__________________________________________________________________________//

function AJDWP_add_new_user() {
    check_ajax_referer('ajax_user_register_nonce', 'AJDWP_csrf_nounce');
    // if (isset( $_POST["AJDWP_csrf"] ) && wp_verify_nonce($_POST['AJDWP_csrf'], 'AJDWP-csrf')) {
        $user_login		            = $_POST["AJDWP_user_login"];
        $cleaned_input_user_login   = sanitize_and_validate_input($user_login);

        $user_email		            = $_POST["AJDWP_user_email"];
        $cleanedEmail               = sanitize_and_validate_email($user_email);

        $user_first 	            = $_POST["AJDWP_user_first"];
        $cleaned_input_user_first   = sanitize_and_validate_input($user_first);

        $user_last	 	            = $_POST["AJDWP_user_last"];
        $cleaned_input_user_last    = sanitize_and_validate_input($user_last);

        $user_pass		            = $_POST["AJDWP_user_pass"];
        $cleaned_psw                = stripslashes($user_pass);
        $pass_confirm 	            = $_POST["AJDWP_user_pass_confirm"];

        $user_role 	                = sanitize_and_validate_input($_POST["AJDWP_user_role"]);
        
        // this is required for username checks
        require_once(ABSPATH . WPINC . '/registration.php');
        
        if(username_exists($cleaned_input_user_login)) {
            // Username already registered
            AJDWP_errors()->add('username_unavailable', __('Username already taken'));
        }
        if(!validate_username($cleaned_input_user_login)) {
            // invalid username
            AJDWP_errors()->add('username_invalid', __('Invalid username'));
        }
        if($cleaned_input_user_login == '') {
            // empty username
            AJDWP_errors()->add('username_empty', __('Please enter a username'));
        }
        if(!is_email($cleanedEmail)) {
            //invalid email
            AJDWP_errors()->add('email_invalid', __('Invalid email'));
        }
        if(email_exists($cleanedEmail)) {
            //Email address already registered
            AJDWP_errors()->add('email_used', __('Email already registered'));
        }
        if($cleaned_psw == '') {
            // passwords do not match
            AJDWP_errors()->add('password_empty', __('Please enter a password'));
        }
        if($user_pass != $pass_confirm) {
            // passwords do not match
            AJDWP_errors()->add('password_mismatch', __('Passwords do not match'));
        }
        
        $errors = AJDWP_errors()->get_error_messages();
        
        // if no errors then cretate user
        if (!empty($errors)) {
            wp_send_json_error(array('errors' => $errors));
        }else{
        // if(empty($errors)) {       
            $new_user_id = wp_insert_user(array(
                    'user_login'		=> $cleaned_input_user_login,
                    'user_pass'	 		=> $cleaned_psw,
                    'user_email'		=> $cleanedEmail,
                    'first_name'		=> $cleaned_input_user_first,
                    'last_name'			=> $cleaned_input_user_last,
                    'user_registered'	=> date('Y-m-d H:i:s'),
                    'role'				=> $user_role
                )
            );
            if($new_user_id) {
                // send an email to the admin
                wp_new_user_notification($new_user_id);
                
                // log the new user in
                wp_setcookie($cleaned_input_user_login, $cleaned_psw, true);
                wp_set_current_user($new_user_id, $cleaned_input_user_login);	
                do_action('wp_login', $cleaned_input_user_login);
                
                // send the newly created user to the home page after logging them in
                wp_redirect(home_url()); exit;
            }

        }


        // Return the errors as part of the response

        
    // } //end if (isset( $_POST["AJDWP_csrf"] ) &&...
}
// add_action('wp', 'AJDWP_add_new_user');

add_action('wp_ajax_user_register_ajax', 'AJDWP_add_new_user');
add_action('wp_ajax_nopriv_user_register_ajax', 'AJDWP_add_new_user');




// used for tracking error messages
function AJDWP_errors(){
    static $wp_error; // global variable handle
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function AJDWP_register_messages() {
	if($codes = AJDWP_errors()->get_error_codes()) {
		echo '<div class="alert alert-danger">';
        // Loop error codes and display errors
        foreach($codes as $code){
            $message = AJDWP_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
		echo '</div>';
	}	
}


function sanitize_and_validate_input($input) {
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



function sanitize_and_validate_email($email) {
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
        AJDWP_errors()->add('email_invalid_naughty', __('Naughty characters not allowed in your email field.'));
        return false;
    }
}


//__________________________________________________________________________//
//__________________________________________________________________________//
//                          Forgot Password
//__________________________________________________________________________//
//__________________________________________________________________________//

function custom_reset_password() {
    check_ajax_referer('ajax-forgot-nonce', 'security');

    $user_login = isset($_POST['user_login']) ? sanitize_text_field($_POST['user_login']) : '';

    if (empty($user_login)) {
        echo '<div class="alert alert-danger">Please enter a valid username or email.</div>';
        die();
    }

    $user_data = get_user_by('login', $user_login) ?: get_user_by('email', $user_login);

    if (!$user_data) {
        echo '<div class="alert alert-danger">User not found. Please enter a valid username or email.</div>';
        die();
    }

    $user_email = $user_data->user_email;
    $reset_key = get_password_reset_key($user_data);
	$reset_url = esc_url(site_url('/password-reset-page/')) . '?key=' . rawurlencode($reset_key) . '&login=' . rawurlencode($user_data->user_login);



    $message = "Someone has requested a password reset for the following account:\r\n\r\n";
    $message .= "Username: $user_data->user_login\r\n\r\n";
    $message .= "If this was a mistake, just ignore this email and nothing will happen.\r\n\r\n";
    $message .= "To reset your password, visit the following link:\r\n\r\n";
    $message .= "$reset_url\r\n";

    wp_mail($user_email, 'Password Reset Request', $message);

    if(wp_mail($user_email, 'Password Reset Request', $message)){
        echo '<div class="alert alert-success">Password reset link sent. Check your email.</div>';
    }else{
        echo '<div class="alert alert-danger">Something went wrong...!!!</div>';
        // echo json_encode(array('success' => true));
    }
    die();
}

add_action('wp_ajax_custom_reset_password', 'custom_reset_password');
add_action('wp_ajax_nopriv_custom_reset_password', 'custom_reset_password');


//------------------------------------- set new password by emailed reset link shortcode ------------------------------------//


function custom_password_reset_form() {
    if (isset($_GET['key']) && isset($_GET['login'])) { ?>

        <h5 class="AJDWP_up_header mt-4 m-2 fw-bold"><?php _e('Set a new Password For Your Account'); ?></h5>

        <?php 
            // show any error messages after form submission
            AJDWP_register_messages(); 
        ?>
        <div class="alert alert-success" role="alert" id="seccessfully_password_set" style="display: none;"> Password Set Successfully...!!!</div>
        <fieldset id="fieldset_mprf" style="display: block;">

            <form id="member-password-reset-form" class="AJDWP_form" method="post" action="" autocomplete="off">
                
                <p>This form is specifically designed for the exclusive use of this user: 
                    <span class="text-danger font-weight-bold"><?php echo (!empty($_GET['login'])) ? $_GET['login'] : ''; ?></span>
                </p>

                <p>If the provided information does not match your username or email address, kindly exit the page. 
                    <a href="<?php echo home_url();?>">&#8592; Go Home</a>
                </p>

                <p>
                    <label for="new_password_reset">New Password:</label>
                    <input type="password" name="new_password_reset" id="new_password_reset" autocomplete="off" required />
                </p>

                <div id="rpf_psw_message" >
                    <h3>Password must contain the following:</h3>
                    <p id="Special_char_rpf" class="reg_psw_invalid">A special character<b> [!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]</b> character</p>
                    <p id="capital_rpf" class="reg_psw_invalid">A <b>capital (uppercase)</b> letter</p>
                    <p id="number_rpf" class="reg_psw_invalid">A <b>number</b></p>
                    <p id="length_rpf" class="reg_psw_invalid">Minimum <b>8 characters</b></p>
                </div>

                <p>
                    <label for="repeat_new_password_reset">New Password:</label>
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



function handle_password_reset() {
    if (isset($_POST['new_password_reset_submit'])) {
        if (isset($_POST['resetPassEmailedLink_form_nonce_field']) && wp_verify_nonce($_POST['resetPassEmailedLink_form_nonce_field'], 'resetPassEmailedLink_form_nonce')) {
            $new_password_reset = sanitize_text_field($_POST['new_password_reset']);
            $repeat_new_password_reset = sanitize_text_field($_POST['repeat_new_password_reset']);
            if($new_password_reset == '' || $repeat_new_password_reset == '' ) {
                // empty Fields
                AJDWP_errors()->add('prf_empty', __('Fields cann\'t be empty'));
            }
            if($new_password_reset != $repeat_new_password_reset) {
                // passwords do not match
                AJDWP_errors()->add('prf_password_mismatch', __('Passwords do not match'));
            }
            
            $prf_errors = AJDWP_errors()->get_error_messages();

            if(empty($prf_errors)){

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
                    AJDWP_errors()->add('prf_invalidLink', __('Invalid reset link or expired key'));
                }

            }
        }
    }
}

// add_action('init', 'handle_password_reset');
add_action('template_redirect', 'handle_password_reset');

//------------------------------------- Delete Account Ajax ------------------------------------//
// AJAX handler for deleting the user account
function delete_user_account() {
    // Verify the nonce
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'delete_user_nonce')) {
        die('Invalid nonce');
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete_user_account') {
        $current_user = wp_get_current_user();
        if (!in_array('administrator', $current_user->roles)) {
            // Get the current user ID
            $user_id = get_current_user_id();

            // Delete all posts and comments by the user
            $args_posts = array('author' => $user_id, 'posts_per_page' => -1);
            $user_posts = get_posts($args_posts);

            foreach ($user_posts as $post) {
                wp_delete_post($post->ID, true);
            }

            // Remove all comments by the user
            $args_comments = array('user_id' => $user_id);
            $user_comments = get_comments($args_comments);

            foreach ($user_comments as $comment) {
                wp_delete_comment($comment->comment_ID, true);
            }

            // Delete user's files (if applicable)

            // Delete the user
            wp_delete_user($user_id);
            
            global $wpdb;

            // Set the table name
            $table_name = $wpdb->prefix . 'ajdwp_like_follow';
            
            // User ID to delete
            $user_id_to_delete = get_current_user_id();
            
            // Execute the delete query
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $table_name WHERE user_id = %d OR author_id = %d",
                    $user_id_to_delete,
                    $user_id_to_delete
                )
            );

        }
        
    }

    die();
}
add_action('wp_ajax_delete_user_account', 'delete_user_account');
add_action('wp_ajax_nopriv_delete_user_account', 'delete_user_account');


//------------------------------------- Admin Button to make needed pages ------------------------------------//

function create_custom_pages_once() {
    $page_definitions = array(
        'user-profile' => array(
            'title' => 'User Profile',
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

function custom_page_creation_trigger() {
    if (isset($_GET['user_profile_pages']) && $_GET['user_profile_pages'] === 'true') {
        create_custom_pages_once();
        // Redirect back to the admin dashboard or any other page
        wp_safe_redirect(admin_url('edit.php?post_type=page'));
        exit;
    }
}



?>