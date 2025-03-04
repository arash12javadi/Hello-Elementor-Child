<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$cookie_popup_text = get_option('cookie_popup_text', '');
$cookie_popup_link = get_option('cookie_popup_link', '');

?>


<label for="cookies_popup_enabled">Cookies popup message: </label>
<label class="cookie-policy-switch">
    <input type="checkbox" id="cookies_popup_enabled" name="cookies_popup_enabled" />
    <span class="slider"></span>
</label>
<br><br>

<div class="cookie-popup-settings" id="cookie-popup-settings" style="display:none;">
    <!-- Form for cookie popup text -->
    <form method="post" action="options.php">
        <?php settings_fields('ajdwp_theme_options_group_text'); ?>
        <?php do_settings_sections('ajdwp_theme_options_group_text'); ?>
        
        <label for="cookie_popup_text">Insert the text to be shown in the cookie popup message: </label>
        <br>
        <textarea id="cookie_popup_text" name="cookie_popup_text" rows="5" cols="50" class="cookie_popup_text"><?php echo esc_textarea($cookie_popup_text); ?></textarea>
        <br><br>

        <input type="submit" class="button-primary" value="<?php esc_attr_e('Save Text'); ?>" />
    </form>
    <br><br>

    <!-- Form for cookie popup link -->
    <form method="post" action="options.php">
        <?php settings_fields('ajdwp_theme_options_group_link'); ?>
        <?php do_settings_sections('ajdwp_theme_options_group_link'); ?>
        
        <label for="cookie_popup_link">Insert the link for Read more: </label>
        <br>
        <input type="text" id="cookie_popup_link" name="cookie_popup_link" value="<?php echo esc_attr($cookie_popup_link); ?>" class="regular-text">
        <br><br>

        <input type="submit" class="button-primary" value="<?php esc_attr_e('Save Link'); ?>" />
    </form>
    <br><br>
</div>