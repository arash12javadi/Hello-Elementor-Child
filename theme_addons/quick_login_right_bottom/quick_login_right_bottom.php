<?php
// quick_login_right_bottom.php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * AJAX: Quick login
 */
function ajdwp_custom_user_login()
{

	// Make AJAX responses robust (don’t let notices corrupt JSON)
	if (wp_doing_ajax()) {
		@ini_set('display_errors', '0');
		nocache_headers();
	}

	// Locale switch for translated messages
	$posted_lang = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
	if ($posted_lang) {
		$locale = (strpos($posted_lang, 'fa') === 0) ? 'fa_IR' : $posted_lang;
		switch_to_locale($locale);
	}
	load_child_theme_textdomain('hello-elementor-child', get_stylesheet_directory() . '/languages');

	// Nonce (accept hidden field or localized one)
	$nonce = isset($_POST['user_quick_login_field']) ? $_POST['user_quick_login_field']
		: (isset($_POST['nonce']) ? $_POST['nonce'] : '');

	if (!$nonce || !wp_verify_nonce($nonce, 'custom_user_login_nonce')) {
		if (ob_get_length()) {
			ob_clean();
		}
		wp_send_json_error([
			'code'    => 'bad_nonce',
			'message' => __('Security check failed.', 'hello-elementor-child'),
		], 200);
	}

	// Inputs
	$username = isset($_POST['username']) ? sanitize_user(wp_unslash($_POST['username'])) : '';
	$password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';

	if ($username === '' || $password === '') {
		if (ob_get_length()) {
			ob_clean();
		}
		wp_send_json_error([
			'code'    => 'empty_fields',
			'message' => __('Please enter both username and password.', 'hello-elementor-child'),
		], 200);
	}

	// Sign on
	$user = wp_signon([
		'user_login'    => $username,
		'user_password' => $password,
		'remember'      => true,
	]);

	if (is_wp_error($user)) {
		$codes      = (array) $user->get_error_codes();
		$first_code = $codes ? $codes[0] : 'authentication_failed';

		$reset_url    = wp_lostpassword_url();
		$register_url = home_url('/user-account/');

		switch ($first_code) {
			case 'invalid_username':
				$message = __('We couldn’t find an account with that username or email.', 'hello-elementor-child');
				$help = [
					'type'    => 'invalid_username',
					'actions' => [
						['label' => __('Create an account', 'hello-elementor-child'), 'url' => $register_url],
						['label' => __('Try password reset', 'hello-elementor-child'), 'url' => $reset_url],
					],
				];
				break;

			case 'incorrect_password':
				$message = __('Wrong password.', 'hello-elementor-child');
				$help = [
					'type'    => 'incorrect_password',
					'actions' => [
						['label' => __('Forgot your password?', 'hello-elementor-child'), 'url' => $reset_url],
					],
				];
				break;

			case 'invalid_email':
				$message = __('Invalid email address.', 'hello-elementor-child');
				$help = [
					'type'    => 'invalid_email',
					'actions' => [
						['label' => __('Try password reset', 'hello-elementor-child'), 'url' => $reset_url],
					],
				];
				break;

			case 'empty_username':
			case 'empty_password':
				$message = __('Please enter both username and password.', 'hello-elementor-child');
				$help = null;
				break;

			default:
				$message = $user->get_error_message(); // already translated by core
				$help = null;
		}

		if (ob_get_length()) {
			ob_clean();
		}
		wp_send_json_error([
			'code'    => $first_code,
			'message' => $message,
			'help'    => $help,
			'reset'   => $reset_url,
		], 200);
	}

	// Success redirect
	$redirect_url = get_author_posts_url($user->ID) ?: home_url('/');

	if (ob_get_length()) {
		ob_clean();
	}
	wp_send_json_success([
		'message'      => __('Login successful', 'hello-elementor-child'),
		'redirect_url' => $redirect_url,
	]);
}
add_action('wp_ajax_custom_user_login', 'ajdwp_custom_user_login');
add_action('wp_ajax_nopriv_custom_user_login', 'ajdwp_custom_user_login');


/**
 * Markup via shortcode: [quick_login_right_bottom]
 */
function quick_login_right_bottom_func()
{ ?>
	<button class="open-button" onclick="openForm()">
		<?php esc_html_e('Login 🔐', 'hello-elementor-child'); ?>
	</button>

	<div class="form-popup" id="myForm" style="display:none;">
		<div class="form-container" id="form-container">
			<p class="fw-bold fs-1 text-center">
				<?php esc_html_e('Login', 'hello-elementor-child'); ?>
				<span class="fs-2">🔒</span>
			</p>

			<div id="ql_err_msg" role="alert" aria-live="polite"></div>

			<?php
			// Nonce field used by JS
			wp_nonce_field('custom_user_login_nonce', 'user_quick_login_field');

			echo wp_login_form([
				'id_username'  => 'ql_username',
				'id_password'  => 'ql_password',
				'label_log_in' => esc_html__('Sign in 🔑', 'hello-elementor-child'),
				'id_submit'    => 'ql_form_submit',
				'remember'     => false,
			]);
			?>

			<button type="button" class="btn cancel" onclick="closeForm()">
				<?php esc_html_e('Close ❎', 'hello-elementor-child'); ?>
			</button>
		</div>
	</div>
<?php }
add_shortcode('quick_login_right_bottom', 'quick_login_right_bottom_func');
