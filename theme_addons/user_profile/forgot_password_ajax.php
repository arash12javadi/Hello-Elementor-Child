<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<p class="AJDWP_up_header mt-4 m-2 fw-bold"><?php esc_html_e('Recover Your Password', 'hello-elementor-child'); ?></p>
<fieldset>
    <div id="reset-password-message"></div>

    <form id="forgot-password-form" method="post" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>" class="AJDWP_form">
        <p>
            <label for="user_login"><?php esc_html_e('Username or Email:', 'hello-elementor-child'); ?></label>
            <input type="text" name="user_login" id="user_login" required />
        </p>
        <p>
            <input type="submit" id="forgot-password-form-submit" value="<?php esc_html_e('Reset Password', 'hello-elementor-child'); ?>" />
        </p>
        <?php wp_nonce_field('ajax-forgot-nonce', 'forgotsecurity'); ?>
    </form>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#forgot-password-form').on('submit', function(e) {
                e.preventDefault();

                var data = {
                    action: 'custom_reset_password',
                    user_login: $(this).find('#user_login').val(),
                    security: $(this).find('#forgotsecurity').val()
                };
                console.log(data);
                $.ajax({
                    type: 'post',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: data,
                    success: function(response) {
                        $('#reset-password-message').html(response);
                        if (response.indexOf('Password reset link sent. Check your email.') !== -1) {
                            // Hide the form on success
                            $('#forgot-password-form').hide();
                        }
                    }
                });
            });
        });
    </script>
</fieldset>