<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


function custom_user_login() {

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

	$user = wp_signon($creds, false);

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


function quick_login_right_bottom_func(){

?>
	<button class="open-button" onclick="openForm()">LOGIN</button>
	<div class="form-popup" id="myForm">
		<div class="form-container" id="form-container">
			<h1 class="fw-bold ">LOGIN</h1>
			<div class="" id="ql_err_msg"></div>
			<?php 
				wp_nonce_field('custom_user_login_nonce', 'user_quick_login_field');
				// Display the login form
				echo wp_login_form(array(
							'id_username' => 'ql_username',
							'id_password' => 'ql_password',
							'label_log_in' => 'SIGN IN',
							'id_submit' => 'ql_form_submit',
							'remember' => false,
						));
			?>
			<button type="button" class="btn cancel" onclick="closeForm()">CLOSE</button>
		</div>
	</div>

	<script>

		function openForm() {
			document.getElementById("myForm").style.display = "block";
		}

		function closeForm() {
			document.getElementById("myForm").style.display = "none";
		}

		jQuery(document).ready(function($) {
			// Attach a click event to your login button
			$('#ql_form_submit').on('click', function() {
				// Get the values from the login form
				var username = $('#ql_username').val();
				var password = $('#ql_password').val();
				var nonce = $('#user_quick_login_field').val();
				event.preventDefault();
				// console.log('11111'+username+' // '+password);
				// Make an Ajax request to the server
				$.ajax({
					type: 'POST',
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					data: {
						action: 'custom_user_login', // The action to be performed on the server
						username: username,
						password: password,
						user_quick_login_field: nonce, // Pass the nonce value
					},
					success: function(response) {
						// Handle the response from the server
						// alert(response);
						var jsonResponse = JSON.parse(response);

						if (jsonResponse.status == 'error') {
							$('#ql_err_msg').html(jsonResponse.message).addClass('alert alert-danger');   
						} else {
							$('#ql_err_msg').html(jsonResponse.message).addClass('alert alert-success');

							// Check if the user ID is available in the response
							var redirect_url = jsonResponse.redirect_url;
							console.log(redirect_url);
							// Redirect to the author page if the user ID is available
							if (redirect_url) {
								setTimeout(function() {
									window.location.href = redirect_url;
								}, 1000); // 1000 milliseconds (1 second) for demonstration purposes
							}
						}
					}

				});
			});
		});

	</script>

	<style>
		/* Button used to open the contact form - fixed at the bottom of the page */
		.login-submit input[type="submit"] {
			height: 55px !important;
			border-radius: 5px !important;
			border: 2px solid #d10d00;
			color: #fff;
			width: 100%;
			box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
		}
		.login-submit input[type="submit"]:hover {
			background-color: green;
			border: none;
		}
		.login-wrap {
			background-color: #333;
			border: none;
			padding: 0;
		}
		.open-button {
			background-color: #333;
			color: white;
			padding: 16px 20px;
			border: none;
			cursor: pointer;
			opacity: 0.8;
			position: fixed;
			bottom: 23px;
			right: 28px;
			width: 280px;
			z-index: 9 !important;
		}

		/* The popup form - hidden by default */
		.form-popup {
			display: none;
			position: fixed;
			bottom: 0;
			right: 15px;
			/* border: 3px solid #f1f1f1; */
			z-index: 9;
		}

		/* Add styles to the form container */
		.form-container {
			max-width: 300px;
			padding: 20px;
			padding-bottom: 10px;
			background-color: #333;
			text-align: left;
			color: #fff;
			border-radius: 5px;
		}

		/* Full-width input fields */

		/* When the inputs get focus, do something */
		.form-container input[type=text]:focus, .form-container input[type=password]:focus {
			background-color: #ddd;
			outline: none;
		}

		/* Set a style for the submit/login button */
		.form-container .btn {
			background-color: #04AA6D;
			color: white;
			padding: 16px 20px;
			border: none;
			cursor: pointer;
			width: 100%;
			margin-bottom:10px;
			opacity: 0.8;
		}

		/* Add a #d10d00 background color to the cancel button */
		.form-container .cancel {
			background-color: #333;
			border: 2px solid #d10d00;
			border-radius: 5px;
			box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
		}


		/* Add some hover effects to buttons */
		.form-container , .open-button:hover {
			opacity: 1;
			box-shadow: rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset, rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset, rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
		}
		.form-container {
			box-shadow: rgba(0, 0, 0, 0.56) 0px 22px 70px 4px;
		}

		.form-container h1 {
			text-align: center;
			text-shadow: 0px 3px 0px #b2a98f,
			0px 7px 5px rgba(0,0,0,0.15),
			0px 12px 1px rgba(0,0,0,0.1),
			0px 17px 15px rgba(0,0,0,0.1);
		}

		.form-container .btn:hover {
			background-color: #d10d00;
			color: #fff;
			border: none;
		}

		input#ql_username, 
		input#ql_password{
			box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
			font-weight: bold;
			color: #333;
		}
	</style>

<?php }

add_shortcode( 'quick_login_right_bottom', 'quick_login_right_bottom_func' );


?>