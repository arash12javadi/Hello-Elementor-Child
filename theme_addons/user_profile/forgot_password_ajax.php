<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<h5 class="AJDWP_up_header mt-4 m-2 fw-bold"><?php _e('Recover Your Password'); ?></h5>
<fieldset>
    <div id="reset-password-message"></div>

    <form id="forgot-password-form" method="post" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>" class="AJDWP_form">
        <p>
            <label for="user_login">Username or Email:</label>
            <input type="text" name="user_login" id="user_login" required />
        </p>    
        <p>
            <input type="submit" id="forgot-password-form-submit" value="Reset Password" />
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