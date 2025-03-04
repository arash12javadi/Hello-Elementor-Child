<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

function custom_user_login()
{

	// Verify the nonce
	if (!isset($_POST['user_quick_login_field']) || !wp_verify_nonce($_POST['user_quick_login_field'], 'custom_user_login_nonce')) {
		echo json_encode(array('status' => 'error', 'message' => 'Nonce verification failed'));
		wp_die();
	}

	$username = sanitize_user($_POST['username']);
	$username = stripslashes($username);
	$username = sanitize_text_field($username);
	$username = esc_html($username);
	$username = esc_attr($username);
	$password = sanitize_text_field(trim($_POST['password']));

	$creds = array(
		'user_login' => $username,
		'user_password' => $password,
		'remember' => true,
	);

	$options = get_option('AJDWP_theme_options');
	if (!empty($options['secure_login'])) {
		$user = wp_signon($creds, true);
	} else {
		$user = wp_signon($creds, false);
	}

	if (!is_wp_error($user)) {
		// Get the user object
		$user_info = get_userdata($user->ID);

		// Check if the user has an author page
		if ($user_info->user_nicename) {
			// Redirect to the author page
			$redirect_url =  get_author_posts_url($user->ID);
			// $user_id = $user->ID;
		}
	}

	if (is_wp_error($user)) {
		$error_message = $user->get_error_message();
		echo json_encode(array('status' => 'error', 'message' => $error_message));
	} else {
		// Include the user ID in the response
		echo json_encode(array('status' => 'success', 'message' => 'Login successful', 'redirect_url' => $redirect_url));
	}
	wp_die(); // Important to terminate the script
}

add_action('wp_ajax_custom_user_login', 'custom_user_login');
add_action('wp_ajax_nopriv_custom_user_login', 'custom_user_login'); // For non-logged in users


function quick_login_right_bottom_func()
{

?>
	<button class="open-button" onclick="openForm()">LOGIN 🔐</button>
	<div class="form-popup" id="myForm">
		<div class="form-container" id="form-container">
			<p class="fw-bold fs-1 text-center"> LOGIN <span class="fs-2">🔒</span></p>
			<div class="" id="ql_err_msg"></div>
			<?php
			wp_nonce_field('custom_user_login_nonce', 'user_quick_login_field');
			// Display the login form
			echo wp_login_form(array(
				'id_username' => 'ql_username',
				'id_password' => 'ql_password',
				'label_log_in' => 'SIGN IN 🔑',
				'id_submit' => 'ql_form_submit',
				'remember' => false,
			));
			?>
			<button type="button" class="btn cancel" onclick="closeForm()"> CLOSE &nbsp ❎ </button>
		</div>
	</div>

<?php }

add_shortcode('quick_login_right_bottom', 'quick_login_right_bottom_func');


?>