<?php
//_____________________________________ forgot_password_ajax.php _____________________________________//

if (!defined('ABSPATH')) {
    exit;
}
?>
<p class="AJDWP_up_header mt-4 m-2 fw-bold">
    <?php esc_html_e('Recover Your Password', 'hello-elementor-child'); ?>
</p>

<fieldset>
    <div id="reset-password-message" class="mb-3" role="alert" aria-live="polite" style="display:none"></div>

    <!-- Keep action as core lostpassword for graceful no-JS fallback -->
    <form id="forgot-password-form"
        method="post"
        action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>"
        class="AJDWP_form"
        autocomplete="off">
        <p>
            <label for="user_login"><?php esc_html_e('Username or Email:', 'hello-elementor-child'); ?></label>
            <input type="text" name="user_login" id="user_login" required />
        </p>
        <p>
            <input type="submit" id="forgot-password-form-submit" value="<?php esc_attr_e('Reset Password', 'hello-elementor-child'); ?>" />
        </p>

        <?php
        // Nonce must match the server handler below (action: ajax-forgot-nonce, field: security)
        wp_nonce_field('ajax-forgot-nonce', 'security');
        ?>
    </form>
</fieldset>