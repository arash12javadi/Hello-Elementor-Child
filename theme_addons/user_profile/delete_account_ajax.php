<?php
//_____________________________________ delete_account_ajax.php _____________________________________//

if (!defined('ABSPATH')) {
    exit;
}

$delete_user_nonce = wp_create_nonce('delete_user_nonce');
$is_admin = current_user_can('administrator');
?>

<h5 class="AJDWP_header mt-4 m-2 fw-bold">
    <?php esc_html_e('Delete Your Account', 'hello-elementor-child'); ?>
</h5>

<div id="primary" class="content-area mx-3">
    <main id="main" class="site-main">
        <div id="user-deletion-form">
            <p><?php esc_html_e('Are you sure you want to delete your account? This action is irreversible.', 'hello-elementor-child'); ?></p>

            <button id="confirm-delete"
                data-nonce="<?php echo esc_attr($delete_user_nonce); ?>"
                <?php echo $is_admin ? 'disabled aria-disabled="true"' : ''; ?>>
                <?php esc_html_e('Yes, Delete My Account', 'hello-elementor-child'); ?>
            </button>

            <?php if ($is_admin): ?>
                <p class="text-danger mt-2">
                    <?php esc_html_e('Admins cannot delete their account from the front-end.', 'hello-elementor-child'); ?>
                </p>
            <?php endif; ?>
        </div>
    </main>
</div>

<div id="overlay"></div>
<div id="customDialog" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="delete-title">
    <p id="delete-title" class="fw-bold">
        <?php esc_html_e('Please confirm account deletion', 'hello-elementor-child'); ?>
    </p>

    <p>
        <?php
        printf(
            esc_html__('By deleting your account, all your %s on this website will be permanently erased.', 'hello-elementor-child'),
            '<span class="text-danger">' . esc_html__('posts, comments, media, likes, followers, etc.', 'hello-elementor-child') . '</span>'
        );
        ?>
    </p>

    <p><?php esc_html_e('If you agree to these conditions, please check the checkbox below and click on "Continue."', 'hello-elementor-child'); ?></p>

    <label for="myCheckbox" class="d-inline-flex align-items-center gap-2">
        <input type="checkbox" id="myCheckbox">
        <span class="text-danger"><?php esc_html_e('Check and continue to DELETE', 'hello-elementor-child'); ?></span>
    </label>

    <div class="mt-3">
        <button id="btnContinue"><?php esc_html_e('Continue', 'hello-elementor-child'); ?></button>
        <button id="btnCancel"><?php esc_html_e('Cancel', 'hello-elementor-child'); ?></button>
    </div>
</div>

<style>
    #overlay {
        display: none;
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999998;
    }

    #customDialog {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, .5);
        z-index: 999999;
        max-width: 520px;
        width: 92%;
    }

    #customDialog button {
        margin: 0 10px;
    }
</style>