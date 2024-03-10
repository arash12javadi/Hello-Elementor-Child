<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);



add_shortcode('AJDWP_register_form', 'AJDWP_registration_form');

// registration form fields
function AJDWP_registration_form() {
    
    ob_start();
    include 'user_register_ajax.php';
    $user_register_data = ob_get_contents();
    ob_end_clean();

    ob_start();
    include 'forgot_password_ajax.php';
    $forgot_psw_data = ob_get_contents();
    ob_end_clean();

    ob_start();
    include 'change_password.php';
    $change_psw_data = ob_get_contents();
    ob_end_clean();

    ob_start();
    include 'user_login.php';
    $user_login_data = ob_get_contents();
    ob_end_clean();
        
    ob_start();
    include 'delete_account_ajax.php';
    $delete_account = ob_get_contents();
    ob_end_clean();      
    ?>
    
    <?php if(!is_user_logged_in()){ ?>
        <!-- ----------Tab headers---------- -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="user_login-tab" data-bs-toggle="tab" data-bs-target="#user_login" type="button" role="tab" aria-controls="user_login" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link " id="user_register-tab" data-bs-toggle="tab" data-bs-target="#user_register" type="button" role="tab" aria-controls="user_register" aria-selected="true">Site Register</button>
            </li>
            <li class="nav-item " role="presentation">
                <button class="nav-link" id="forgot_psw-tab" data-bs-toggle="tab" data-bs-target="#forgot_psw" type="button" role="tab" aria-controls="forgot_psw" aria-selected="false">Forgot Password</button>
            </li>
        </ul>

        <!-- ----------Tab Bodies---------- -->
        <div class="tab-content" id="myTabContent">

            <!-- -------------------------------- User Login Tab ------------------------------------- -->
            <div class="tab-pane fade active show" id="user_login" role="tabpanel" aria-labelledby="user_login-tab">

                <?php echo $user_login_data; ?>

                <style>
                    button.open-button {
                        display: none !important;
                    }
                </style>
            
            </div>

            <!-- -------------------------------- User Register Tab ------------------------------------- -->
            <div class="tab-pane fade " id="user_register" role="tabpanel" aria-labelledby="user_register-tab">

                <?php echo $user_register_data; ?>
            
            </div>

            <!-- -------------------------------- Forgot Password Tab ------------------------------------- -->
            <div class="tab-pane fade" id="forgot_psw" role="tabpanel" aria-labelledby="forgot_psw-tab">

                <?php echo $forgot_psw_data; ?>
                
            </div>
        </div>

    <?php }else{ ?>

            <!-- -------------------------------- Change current and set new Password  ------------------------------------- -->
                <!-- ----------Tab headers---------- -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="change_pass-tab" data-bs-toggle="tab" data-bs-target="#change_pass" type="button" role="tab" aria-controls="change_pass" aria-selected="true">Change Password</button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link" id="delete_account-tab" data-bs-toggle="tab" data-bs-target="#delete_account" type="button" role="tab" aria-controls="delete_account" aria-selected="false">Delete Account</button>
                    </li>
                </ul>    
                
                <!-- ----------Tab Bodies---------- -->
                <div class="tab-content" id="myTabContent">

                <!-- -------------------------------- Change Password Tab ------------------------------------- -->
                <div class="tab-pane fade active show" id="change_pass" role="tabpanel" aria-labelledby="change_pass-tab">

                    <?php echo $change_psw_data; ?>

                </div>

                <!-- -------------------------------- Change Profile Image Tab ------------------------------------- -->
                <div class="tab-pane fade" id="delete_account" role="tabpanel" aria-labelledby="delete_account-tab">

                    <?php echo $delete_account; ?>

                </div>

    <?php } ?>

<?php }?>
