<?php

// exit if file is called directly
if (! defined('ABSPATH')) {
    exit;
}
?>

<h5 class="AJDWP_header mt-4 m-2 fw-bold"><?php esc_html_e('Delete Your Account', 'hello-elementor-child'); ?></h5>

<div id="primary" class="content-area mx-3">
    <main id="main" class="site-main">
        <div id="user-deletion-form">
            <p>
                <?php esc_html_e(
                    'Are you sure you want to delete your account? This action is irreversible.',
                    'hello-elementor-child'
                ); ?>
            </p>

            <?php
            // Create a nonce field
            $delete_user_nonce = wp_create_nonce('delete_user_nonce');
            ?>

            <button id="confirm-delete" data-nonce="<?php echo esc_attr($delete_user_nonce); ?>">
                <?php esc_html_e('Yes, Delete My Account', 'hello-elementor-child'); ?>
            </button>
        </div>
    </main>
</div>

<div id="overlay"></div>
<div id="customDialog" style="display: none;">
    <p>
        <?php
        printf(
            /* translators: warning text about account deletion */
            esc_html__(
                'By deleting your account, all your %s on this website will be permanently erased.',
                'hello-elementor-child'
            ),
            '<span class="text-danger">' . esc_html__('posts, comments, media, likes, followers, etc.', 'hello-elementor-child') . '</span>'
        );
        ?>
    </p>

    <p>
        <?php esc_html_e(
            'If you agree to these conditions, please check the checkbox below and click on "Continue."',
            'hello-elementor-child'
        ); ?>
    </p>

    <input type="checkbox" id="myCheckbox">
    <span class="text-danger">
        <?php esc_html_e('Check and continue to DELETE', 'hello-elementor-child'); ?>
    </span>
    <br /><br />

    <button id="btnContinue">
        <?php esc_html_e('Continue', 'hello-elementor-child'); ?>
    </button>

    <button id="btnCancel">
        <?php esc_html_e('Cancel', 'hello-elementor-child'); ?>
    </button>
</div>


<style>
    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    #customDialog {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    #customDialog button {
        margin: 0 10px;
    }

    div#customDialog {
        z-index: 999999;
    }
</style>
<?php
$current_user = wp_get_current_user();
if (!in_array('administrator', $current_user->roles)) { ?>
    <script>
        jQuery(document).ready(function($) {
            $('#confirm-delete').on('click', function() {
                // Get the nonce value
                var nonce = $(this).data('nonce');

                // Display confirmation popup
                if (confirm('Are you sure you want to delete your account? This action is irreversible.')) {
                    // AJAX request to delete user
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            'action': 'delete_user_account',
                            'nonce': nonce
                        },
                        success: function(response) {
                            window.location.href = '<?php echo esc_url(home_url('/')); ?>';
                        }

                    });
                }
            });

            $('#delete_account-tab').on('click', function() {
                // Show the overlay and custom dialog
                $("#overlay, #customDialog").show();

                // Continue button click event
                $("#btnContinue").click(function() {
                    if ($("#myCheckbox").is(":checked")) {
                        $("#overlay, #customDialog").hide();
                    }
                });

                // Cancel button click event
                $("#btnCancel").click(function() {
                    location.reload();
                });
            });

        });
    </script>
<?php } else {
?>
    <script>
        jQuery(document).ready(function($) {
            $('#delete_account-tab').on('click', function() {
                alert(" Are you kidding me??? \n You are an ADMIN...!!!");
                location.reload();
            });
        });
    </script>
<?php } ?>