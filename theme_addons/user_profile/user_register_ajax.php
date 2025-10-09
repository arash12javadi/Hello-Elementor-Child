<?php
//_____________________________________ user_register_ajax.php _____________________________________//
if (!defined('ABSPATH')) {
    exit;
}
?>

<p class="AJDWP_header mt-4 m-2 fw-bold">
    <?php esc_html_e('Register New Account', 'hello-elementor-child'); ?>
</p>

<fieldset>
    <!-- messages -->
    <div id="user-register-message" class="mb-3" style="display:none"></div>
    <div id="success_register_gif_anim" class="success_register_gif_anim"></div>

    <form id="AJDWP_registration_form" class="AJDWP_form" action="" method="POST" autocomplete="off">
        <p>
            <label for="reg_AJDWP_user_Login"><?php esc_html_e('Username', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_login" id="reg_AJDWP_user_Login" class="AJDWP_user_login" type="text" required />
        </p>

        <p>
            <label for="reg_AJDWP_user_email"><?php esc_html_e('Email', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_email" id="reg_AJDWP_user_email" class="AJDWP_user_email" type="email" required />
        </p>

        <p>
            <label for="reg_AJDWP_user_first"><?php esc_html_e('First Name', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_first" id="reg_AJDWP_user_first" class="AJDWP_user_first" type="text" />
        </p>

        <p>
            <label for="reg_AJDWP_user_last"><?php esc_html_e('Last Name', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_last" id="reg_AJDWP_user_last" class="AJDWP_user_last" type="text" />
        </p>

        <div>
            <label for="register_psw"><?php esc_html_e('Password', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_pass" id="register_psw" class="register_psw" type="password" required />

            <!-- Strength hints -->
            <div id="reg_psw_message" style="display:none">
                <b><?php esc_html_e('Password must contain the following:', 'hello-elementor-child'); ?></b>
                <p id="Special_char" class="reg_psw_invalid">
                    <?php esc_html_e('A Special Character', 'hello-elementor-child'); ?>
                    <b>[!@#$%^&*()_+{}[]:;<>,.?~\/-]</b>
                </p>
                <p id="capital" class="reg_psw_invalid"><?php esc_html_e('A Capital Letter', 'hello-elementor-child'); ?></p>
                <p id="number" class="reg_psw_invalid"><?php esc_html_e('A Number', 'hello-elementor-child'); ?></p>
                <p id="length" class="reg_psw_invalid"><?php esc_html_e('Minimum 8 Characters', 'hello-elementor-child'); ?></p>
            </div>
        </div>

        <p>
            <label for="password_again"><?php esc_html_e('Password Again', 'hello-elementor-child'); ?></label>
            <input name="AJDWP_user_pass_confirm" id="password_again" class="password_again" type="password" required />
        </p>

        <p>
            <?php esc_html_e('Select Your Role:', 'hello-elementor-child'); ?><br><br>
            <label for="author_role">
                <input type="radio" id="author_role" name="user_role" value="author" checked>
                <?php esc_html_e('Author', 'hello-elementor-child'); ?>
            </label><br>
            <label for="subscriber_role">
                <input type="radio" id="subscriber_role" name="user_role" value="subscriber">
                <?php esc_html_e('Subscriber', 'hello-elementor-child'); ?>
            </label>
        </p>

        <p>
            <!-- This nonce name must match your PHP: check_ajax_referer('ajax_user_register_nonce','AJDWP_csrf_nonce') -->
            <?php wp_nonce_field('ajax_user_register_nonce', 'AJDWP_csrf_nonce'); ?>
            <input type="submit" id="reg_user_submit" class="reg_user_submit" value="<?php esc_attr_e('Register', 'hello-elementor-child'); ?>" disabled />
        </p>
    </form>

</fieldset>