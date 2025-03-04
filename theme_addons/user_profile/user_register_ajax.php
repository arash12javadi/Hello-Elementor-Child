<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<h5 class="AJDWP_header mt-4 m-2 fw-bold"><?php _e('Register New Account'); ?></h5>
<fieldset>
    <!-- show any error messages after form submission -->
    <div class="" id="user-register-message"></div>
    <div class="success_register_gif_anim " id="success_register_gif_anim"></div>
    <form id="AJDWP_registration_form" class="AJDWP_form" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="POST">
    
        <p>
            <label for="reg_AJDWP_user_Login"><?php _e('Username'); ?></label>
            <input name="AJDWP_user_login" id="reg_AJDWP_user_Login" class="AJDWP_user_login" type="text"/>
        </p>
        <p>
            <label for="reg_AJDWP_user_email"><?php _e('Email'); ?></label>
            <input name="AJDWP_user_email" id="reg_AJDWP_user_email" class="AJDWP_user_email" type="email"/>
        </p>
        <p>
            <label for="reg_AJDWP_user_first"><?php _e('First Name'); ?></label>
            <input name="AJDWP_user_first" id="reg_AJDWP_user_first" type="text" class="AJDWP_user_first" />
        </p>
        <p>
            <label for="reg_AJDWP_user_last"><?php _e('Last Name'); ?></label>
            <input name="AJDWP_user_last" id="reg_AJDWP_user_last" type="text" class="AJDWP_user_last"/>
        </p>
        <p>
            <label for="register_psw"><?php _e('Password'); ?></label>
            <input name="AJDWP_user_pass" id="register_psw" class="register_psw" type="password"/>

            <!-- Check Password Strength -->
            <div id="reg_psw_message">
                <h3>Password must contain the following:</h3>
                <p id="Special_char" class="reg_psw_invalid">A special character<b> [!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]</b> character</p>
                <p id="capital" class="reg_psw_invalid">A <b>capital (uppercase)</b> letter</p>
                <p id="number" class="reg_psw_invalid">A <b>number</b></p>
                <p id="length" class="reg_psw_invalid">Minimum <b>8 characters</b></p>
            </div>

        </p>
        <p>
            <label for="password_again"><?php _e('Password Again'); ?></label>
            <input name="AJDWP_user_pass_confirm" id="password_again" class="password_again" type="password"/>
        </p>
        <p>Select Your Role: 
            </br>
            <label for="author_role">
                <input type="radio" id="author_role" name="user_role" value="author" checked> Author
            </label>
            </br>
            <label for="subsciber_role">
                <input type="radio" id="subsciber_role" name="user_role" value="subscriber"> Subscriber
            </label>
            </br>
        </p>
        <p>
            <input type="hidden" name="AJDWP_csrf" id="AJDWP_csrf_nounce" value="<?php echo wp_create_nonce('AJDWP-csrf'); ?>"/>
            <input type="submit" id="reg_user_submit" class="reg_user_submit" value="<?php _e('Register Your Account'); ?>" disabled/>
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
                    AJDWP_user_role : $('input[name="user_role"]:checked').val(),
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
                            //var success_gif_anim = '<img src="https://arashjavadi.com/wp-content/uploads/2023/12/arashjavadi.com-camel-gif-animation-success-message-.gif" alt="Success Gif"><br/><a href="<?php //echo get_home_url();?>" class="suc_home_btn">&#8592;Go to Home</a>';
                            var success_gif_anim = '<img src="<?php echo get_stylesheet_directory_uri(); ?>/camel-gif-animation-success-message.gif" alt="Success Gif"><br/><a href="<?php echo get_home_url();?>" class="suc_home_btn">&#8592;Go to Home</a>';
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
