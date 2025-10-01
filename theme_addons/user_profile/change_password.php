<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (is_user_logged_in()) {

?>
    <p class="AJDWP_header mt-4 m-2 fw-bold"><?php esc_html_e('Set New Password', 'hello-elementor-child'); ?></p>
    <div id="change-password-form">
        <?php

        if (isset($_POST['chg_psw_submit'])) {
            $nonce_cp = $_POST['my_form_nonce_cp'];
            if (wp_verify_nonce($nonce_cp, 'my_form_action_cp')) {
                $current_password   = sanitize_text_field(trim($_POST['current_password']));
                $new_password_cp    = sanitize_text_field(trim($_POST['new_password_cp']));
                $confirm_password   = sanitize_text_field(trim($_POST['confirm_password']));

                $user = wp_get_current_user();

                // Check if the current password is correct
                if (wp_check_password($current_password, $user->user_pass, $user->ID)) {
                    // Check if new password and confirm password match
                    if ($new_password_cp === $confirm_password) {
                        wp_set_password($new_password_cp, $user->ID);
                        echo '<div class="alert alert-success success_msg"><strong>Success! </strong>Password successfully changed!</div>';
                    } else {
                        echo '<div class="alert alert-danger error_msg"><strong>Error! </strong>New password and confirm password do not match.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger error_msg_2"><strong>Error! </strong>Incorrect current password.</div>';
                }
            }
        }

        ?>
        <fieldset>
            <form method="post" action="" class="change_password_css" autocomplete="off">
                <label for="current_password"><?php esc_html_e('Current Password:', 'hello-elementor-child'); ?></label>
                <input type="password" name="current_password" autocomplete="off" required>

                <label for="new_password_cp"><?php esc_html_e('New Password:', 'hello-elementor-child'); ?></label>
                <input type="password" name="new_password_cp" id="change_psw" autocomplete="off" required>

                <div id="chg_psw_message">
                    <p><?php esc_html_e('Password must contain the following:', 'hello-elementor-child'); ?></p>
                    <p id="Special_char_cp" class="reg_psw_invalid"><?php esc_html_e('A Special Character', 'hello-elementor-child'); ?> [!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]</p>
                    <p id="capital_cp" class="reg_psw_invalid"><?php esc_html_e('A Captal Letter', 'hello-elementor-child'); ?></p>
                    <p id="number_cp" class="reg_psw_invalid"><?php esc_html_e('A Number', 'hello-elementor-child'); ?></p>
                    <p id="length_cp" class="reg_psw_invalid"><?php esc_html_e('Minimum 8 Characters', 'hello-elementor-child'); ?></p>
                </div>

                <label for="confirm_password"><?php esc_html_e('Confirm Password: ', 'hello-elementor-child'); ?></label>
                <input type="password" name="confirm_password" required>

                <?php wp_nonce_field('my_form_action_cp', 'my_form_nonce_cp'); ?>
                <input
                    class="mt-3 chg_psw_submit"
                    type="submit"
                    id="chg_psw_submit"
                    name="chg_psw_submit"
                    value="<?php echo esc_attr__('Change Password', 'hello-elementor-child'); ?>"
                    autocomplete="off"
                    disabled>
            </form>
        </fieldset>
    </div>

<?php

} else {
    return sprintf(
        '<p class="error">%s</p>',
        esc_html__('You must be logged in to change your password.', 'hello-elementor-child')
    );
}

?>