<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<p class="AJDWP_header mt-4 m-2 fw-bold"><?php esc_html_e('Register New Account', 'hello-elementor-child'); ?></p>
<fieldset>
    <!-- show any error messages after form submission -->
    <div class="" id="user-register-message"></div>
    <div class="success_register_gif_anim " id="success_register_gif_anim"></div>
    <form id="AJDWP_registration_form" class="AJDWP_form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="POST">

        <p>
            <label for="reg_AJDWP_user_Login"><?php esc_html_e('Username', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_login" id="reg_AJDWP_user_Login" class="AJDWP_user_login" type="text" />
        </p>
        <p>
            <label for="reg_AJDWP_user_email"><?php esc_html_e('Email', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_email" id="reg_AJDWP_user_email" class="AJDWP_user_email" type="email" />
        </p>
        <p>
            <label for="reg_AJDWP_user_first"><?php esc_html_e('First Name', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_first" id="reg_AJDWP_user_first" type="text" class="AJDWP_user_first" />
        </p>
        <p>
            <label for="reg_AJDWP_user_last"><?php esc_html_e('Last Name', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_last" id="reg_AJDWP_user_last" type="text" class="AJDWP_user_last" />
        </p>
        <div>
            <label for="register_psw"><?php esc_html_e('Password', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_pass" id="register_psw" class="register_psw" type="password" />

            <!-- Check Password Strength -->
            <div id="reg_psw_message">
                <b><?php esc_html_e('Password must contain the following:', 'hello-elementor-child'); ?></b>
                <p id="Special_char" class="reg_psw_invalid">
                    <?php esc_html_e('A Special Character', 'hello-elementor-child'); ?>
                    <b>[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]</b>
                </p>
                <p id="capital" class="reg_psw_invalid">
                    <?php esc_html_e('A Capital Letter', 'hello-elementor-child'); ?>
                </p>
                <p id="number" class="reg_psw_invalid">
                    <?php esc_html_e('A Number', 'hello-elementor-child'); ?>
                </p>
                <p id="length" class="reg_psw_invalid">
                    <?php esc_html_e('Minimum 8 Characters', 'hello-elementor-child'); ?>
                </p>
            </div>
        </div>
        <p>
            <label for="password_again"><?php esc_html_e('Password Again', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_pass_confirm" id="password_again" class="password_again" type="password" />
        </p>
        <p><?php esc_html_e('Select Your Role:', 'hello-elementor-child'); ?>
            </br>
            </br>
            <label for="author_role">
                <input type="radio" id="author_role" name="user_role" value="author" checked> <?php esc_html_e('Author', 'hello-elementor-child'); ?>
            </label>
            </br>
            <label for="subsciber_role">
                <input type="radio" id="subsciber_role" name="user_role" value="subscriber"> <?php esc_html_e('Subscriber', 'hello-elementor-child'); ?>
            </label>
            </br>
        </p>
        <p>
            <input type="hidden" name="AJDWP_csrf" id="AJDWP_csrf_nounce" value="<?php echo wp_create_nonce('AJDWP-csrf'); ?>" />
            <input type="submit" id="reg_user_submit" class="reg_user_submit" value="<?php esc_html_e('Register', 'hello-elementor-child'); ?>" disabled />
        </p>
        <?php wp_nonce_field('ajax_user_register_nonce', 'user_register_security'); ?>
    </form>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#AJDWP_registration_form').on('submit', function(e) {
                e.preventDefault();

                var data = {
                    action: 'user_register_ajax',
                    AJDWP_user_login: $('#reg_AJDWP_user_Login').val(),
                    AJDWP_user_email: $('#reg_AJDWP_user_email').val(),
                    AJDWP_user_first: $('#reg_AJDWP_user_first').val(),
                    AJDWP_user_last: $('#reg_AJDWP_user_last').val(),
                    AJDWP_user_pass: $('#register_psw').val(),
                    AJDWP_user_pass_confirm: $('#password_again').val(),
                    AJDWP_user_role: $('input[name="user_role"]:checked').val(),
                    AJDWP_csrf_nounce: $('#user_register_security').val()
                };

                $.ajax({
                    type: 'post',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: data,
                    success: function(response) {
                        if (response.data && response.data.errors && response.data.errors.length > 0) {
                            var htmlContent = '';
                            for (var i = 0; i < response.data.errors.length; i++) {
                                htmlContent += '* ' + response.data.errors[i] + '<br>';
                            }
                            $('#user-register-message').addClass('alert alert-danger').html(htmlContent);
                        } else {
                            //var success_gif_anim = '<img src="https://arashjavadi.com/wp-content/uploads/2023/12/arashjavadi.com-camel-gif-animation-success-message-.gif" alt="Success Gif"><br/><a href="<?php //echo get_home_url();
                                                                                                                                                                                                                ?>" class="suc_home_btn">&#8592;Go to Home</a>';
                            var success_gif_anim = '<img src="<?php echo get_stylesheet_directory_uri(); ?>/camel-gif-animation-success-message.gif" alt="Success Gif"><br/><a href="<?php echo get_home_url(); ?>" class="suc_home_btn">&#8592;Go to Home</a>';
                            $('#user-register-message').addClass('alert alert-success').html('Account created successfully...!!!');
                            $('#success_register_gif_anim').html(success_gif_anim);
                            $('#AJDWP_registration_form').hide();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>

</fieldset>