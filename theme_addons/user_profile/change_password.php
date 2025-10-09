<?php
//_____________________________________ change_password.php _____________________________________//

if (!defined('ABSPATH')) {
    exit;
}

if (is_user_logged_in()) : ?>
    <p class="AJDWP_header mt-4 m-2 fw-bold"><?php esc_html_e('Set New Password', 'hello-elementor-child'); ?></p>
    <div id="change-password-form">
        <?php
        if (isset($_POST['chg_psw_submit'])) {
            // CSRF check
            $nonce_cp = isset($_POST['my_form_nonce_cp']) ? $_POST['my_form_nonce_cp'] : '';
            if (!wp_verify_nonce($nonce_cp, 'my_form_action_cp')) {
                echo '<div class="alert alert-danger error_msg_3">'
                    . esc_html__('Security check failed.', 'hello-elementor-child')
                    . '</div>';
            } else {
                // Do not sanitize passwords; only trim + unslash
                $current_password = isset($_POST['current_password']) ? trim(wp_unslash($_POST['current_password'])) : '';
                $new_password_cp  = isset($_POST['new_password_cp']) ? trim(wp_unslash($_POST['new_password_cp'])) : '';
                $confirm_password = isset($_POST['confirm_password']) ? trim(wp_unslash($_POST['confirm_password'])) : '';

                $user = wp_get_current_user();

                if (!$user || 0 === $user->ID) {
                    echo '<div class="alert alert-danger error_msg_3">'
                        . esc_html__('Error! User not logged in.', 'hello-elementor-child')
                        . '</div>';
                } elseif (!wp_check_password($current_password, $user->user_pass, $user->ID)) {
                    echo '<div class="alert alert-danger error_msg_2">'
                        . '<strong>' . esc_html__('Error!', 'hello-elementor-child') . '</strong> '
                        . esc_html__('Incorrect current password.', 'hello-elementor-child')
                        . '</div>';
                } elseif ($new_password_cp !== $confirm_password) {
                    echo '<div class="alert alert-danger error_msg">'
                        . '<strong>' . esc_html__('Error!', 'hello-elementor-child') . '</strong> '
                        . esc_html__('New password and confirm password do not match.', 'hello-elementor-child')
                        . '</div>';
                } else {
                    // Optional: minimal server-side strength validation to mirror JS
                    $has_upper   = preg_match('/[A-Z]/', $new_password_cp);
                    $has_number  = preg_match('/\d/', $new_password_cp);
                    $has_special = preg_match('/[^A-Za-z0-9]/', $new_password_cp);
                    $len_ok      = strlen($new_password_cp) >= 8;

                    if (!$has_upper || !$has_number || !$has_special || !$len_ok) {
                        echo '<div class="alert alert-danger error_msg">'
                            . '<strong>' . esc_html__('Error!', 'hello-elementor-child') . '</strong> '
                            . esc_html__('Password does not meet the required strength.', 'hello-elementor-child')
                            . '</div>';
                    } else {
                        wp_set_password($new_password_cp, $user->ID);

                        echo '<div class="alert alert-success success_msg">'
                            . '<strong>' . esc_html__('Success!', 'hello-elementor-child') . '</strong> '
                            . esc_html__('Password successfully changed. Please log in again.', 'hello-elementor-child')
                            . '</div>';

                        // Optionally redirect to login page:
                        // echo '<meta http-equiv="refresh" content="1;url=' . esc_url( wp_login_url() ) . '">';
                    }
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
                    <p id="Special_char_cp" class="reg_psw_invalid"><?php esc_html_e('A Special Character', 'hello-elementor-child'); ?> [!@#$%^&*()_+{}[]:;<>,.?~\/-]</p>
                    <p id="capital_cp" class="reg_psw_invalid"><?php esc_html_e('A Capital Letter', 'hello-elementor-child'); ?></p>
                    <p id="number_cp" class="reg_psw_invalid"><?php esc_html_e('A Number', 'hello-elementor-child'); ?></p>
                    <p id="length_cp" class="reg_psw_invalid"><?php esc_html_e('Minimum 8 Characters', 'hello-elementor-child'); ?></p>
                </div>

                <label for="confirm_password"><?php esc_html_e('Confirm Password:', 'hello-elementor-child'); ?></label>
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
else :
    echo '<p class="error">' . esc_html__('You must be logged in to change your password.', 'hello-elementor-child') . '</p>';
endif;
