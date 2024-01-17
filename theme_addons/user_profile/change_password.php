<?php  

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if (is_user_logged_in()) {

    ?>
    <h5 class="AJDWP_header mt-4 m-2 fw-bold"><?php _e('Set New Password'); ?></h5>
    <div id="change-password-form">
        <?php
        
            if (isset($_POST['chg_psw_submit']) ) {
                $nonce_cp = $_POST['my_form_nonce_cp'];
                if(wp_verify_nonce($nonce_cp, 'my_form_action_cp')){
                    $current_password   = sanitize_text_field( trim( $_POST['current_password'] ) );
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
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" autocomplete="off" required>

                <label for="new_password_cp">New Password:</label>
                <input type="password" name="new_password_cp" id="change_psw" autocomplete="off" required>

                <div id="chg_psw_message" >
                    <h3>Password must contain the following:</h3>
                    <p id="Special_char_cp" class="reg_psw_invalid">A special character<b> [!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]</b> character</p>
                    <p id="capital_cp" class="reg_psw_invalid">A <b>capital (uppercase)</b> letter</p>
                    <p id="number_cp" class="reg_psw_invalid">A <b>number</b></p>
                    <p id="length_cp" class="reg_psw_invalid">Minimum <b>8 characters</b></p>
                </div>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>

                <?php wp_nonce_field('my_form_action_cp', 'my_form_nonce_cp'); ?>
                <input class="mt-3 chg_psw_submit" type="submit" id="chg_psw_submit" name="chg_psw_submit" value="Change Password" autocomplete="off" disabled>
            </form>
        </fieldset>
    </div>

    <?php
    
} else {
    return '<p class="error">You must be logged in to change your password.</p>';
}

?>