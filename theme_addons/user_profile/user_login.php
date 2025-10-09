<?php
//_____________________________________ user_login.php _____________________________________//

if (!defined('ABSPATH')) {
	exit;
}
?>

<p class="AJDWP_header mt-4 m-2 fw-bold">
	<?php esc_html_e('Access Your Account', 'hello-elementor-child'); ?>
</p>

<fieldset>
	<div class="theme_form-popup theme_AJDWP_form" id="theme_myForm" role="dialog" aria-labelledby="theme-loginform-title" aria-modal="true">
		<div class="theme_form-container" id="theme_form-container">
			<div id="theme_ql_err_msg" class="" role="alert" aria-live="polite"></div>

			<?php
			// Nonce (hidden input with id="user_quick_login_field")
			wp_nonce_field('custom_user_login_nonce', 'user_quick_login_field');

			if (function_exists('ajdwp_render_login_notices')) {
				ajdwp_render_login_notices();
			}
			// Render the login form with your IDs
			// force redirect back to this page so failures return here
			echo wp_login_form([
				'id_username'  => 'theme_ql_username',
				'id_password'  => 'theme_ql_password',
				'label_log_in' => esc_html__('SIGN IN', 'hello-elementor-child'),
				'id_submit'    => 'theme_ql_form_submit',
				'remember'     => false,
				'form_id'      => 'theme-loginform',
				'redirect'     => get_permalink(), // <— important
			]);
			?>
		</div>
	</div>
</fieldset>