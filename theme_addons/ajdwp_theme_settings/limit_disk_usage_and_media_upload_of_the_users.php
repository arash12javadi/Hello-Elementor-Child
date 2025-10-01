<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
//--------------------------- limit media upload for users ---------------------------//
// Limit Disk space, files size, file types, and photo dimensions before upload ----------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['limit_uploads'])) {

    // === Restrict uploads ONLY for non-admins ===
    $user_id  = get_current_user_id();
    $user     = $user_id ? get_userdata($user_id) : null;
    $is_admin = $user && (is_super_admin($user->ID) || user_can($user, 'manage_options'));

    if (! $is_admin) {

        add_filter('wp_handle_upload_prefilter', function ($file) {
            // --- Options & sanity ---
            $options = get_option('AJDWP_theme_options');

            // allowed extensions (match your UI)
            $allowed_exts = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'avif');

            // limits from options (defaults provided)
            $max_upload_size_kb = isset($options['max_upload_size'])  ? (int) $options['max_upload_size']  : 500;   // KB
            $max_image_width    = isset($options['max_image_width'])  ? (int) $options['max_image_width']  : 1980;  // px
            $max_image_height   = isset($options['max_image_height']) ? (int) $options['max_image_height'] : 1440;  // px
            $min_image_width    = isset($options['min_image_width'])  ? (int) $options['min_image_width']  : 300;   // px
            $min_image_height   = isset($options['min_image_height']) ? (int) $options['min_image_height'] : 300;   // px

            // temp file present?
            if (empty($file['tmp_name']) || ! file_exists($file['tmp_name'])) {
                $file['error'] = 'Upload failed: temporary file missing.';
                return $file;
            }

            // --- 1) Real type + extension check ---
            $checked = wp_check_filetype_and_ext($file['tmp_name'], $file['name'], false);
            $type    = $checked['type']; // e.g. image/jpeg
            $ext     = $checked['ext'];  // e.g. jpg

            if (empty($type) || empty($ext) || ! in_array(strtolower($ext), $allowed_exts, true)) {
                $file['error'] = 'Only images (jpeg / png / gif / webp / avif) are allowed.';
                return $file;
            }

            // --- 2) File size limit (KB from options) ---
            $filesize = filesize($file['tmp_name']); // bytes
            if ($filesize === false) {
                $file['error'] = 'Could not determine file size.';
                return $file;
            }
            if ($filesize > $max_upload_size_kb * 1024) {
                $file['error'] =
                    'File uploads exceeding ' . number_format($max_upload_size_kb) . ' KB are prohibited. ' .
                    'This file size is ' . number_format($filesize / 1024, 2) . ' KB.';
                return $file;
            }

            // --- 3) Per-user total disk cap ---
            $user_id         = get_current_user_id();
            $total_disk_used = (int) get_user_meta($user_id, 'total_disk_usage', true); // bytes
            $disk_limit      = get_disk_usage_limit($user_id, $options);                // bytes

            // if unlimited, skip the cap; else enforce
            if ($disk_limit !== PHP_INT_MAX && ($filesize + $total_disk_used) > $disk_limit) {
                $file['error'] =
                    'You are allowed to upload files up to ' . number_format($disk_limit / 1048576, 2) . ' MB total. ' .
                    'You have used ' . number_format($total_disk_used / 1048576, 2) . ' MB. ' .
                    'You have ' . number_format(($disk_limit - $total_disk_used) / 1048576, 2) . ' MB left.';
                return $file;
            }

            // --- 4) Dimensions (skip AVIF due to getimagesize issues) ---
            $is_avif = (strtolower($ext) === 'avif');
            if (!$is_avif) {
                $img = @getimagesize($file['tmp_name']);
                if ($img === false) {
                    $file['error'] = 'Unable to read image dimensions. Please upload a valid image.';
                    return $file;
                }

                $w = (int) $img[0];
                $h = (int) $img[1];

                if ($w > $max_image_width) {
                    $file['error'] = 'Image too wide. Max width: ' . $max_image_width . 'px. Uploaded: ' . $w . 'px.';
                    return $file;
                }
                if ($h > $max_image_height) {
                    $file['error'] = 'Image too tall. Max height: ' . $max_image_height . 'px. Uploaded: ' . $h . 'px.';
                    return $file;
                }
                if ($w < $min_image_width) {
                    $file['error'] = 'Image too narrow. Min width: ' . $min_image_width . 'px. Uploaded: ' . $w . 'px.';
                    return $file;
                }
                if ($h < $min_image_height) {
                    $file['error'] = 'Image too short. Min height: ' . $min_image_height . 'px. Uploaded: ' . $h . 'px.';
                    return $file;
                }
            }

            // All good → allow upload to proceed
            return $file;
        });

        add_filter('upload_size_limit', function ($size) {
            $options = get_option('AJDWP_theme_options');
            $kb      = isset($options['max_upload_size']) ? (int) $options['max_upload_size'] : 500;
            $custom  = $kb * 1024;
            return min($size, $custom); // never above server cap
        });
    }

    // === ALWAYS show the banner (admins included) ===
    add_action('admin_enqueue_scripts', function ($hook) {
        if ($hook === 'upload.php') {
            wp_enqueue_script('wp-util'); // ensure wp.template exists
        }
    });

    add_action('print_media_templates', function () {
        // optional: only on Media Library screen
        if (function_exists('get_current_screen')) {
            $s = get_current_screen();
            if (! $s || $s->base !== 'upload') return;
        }

        $user_id = get_current_user_id();
        $options = get_option('AJDWP_theme_options');

        $disk_usage_limit = get_disk_usage_limit($user_id, $options);
        $total_disk_usage = (int) get_user_meta($user_id, 'total_disk_usage', true);
        $remaining_space  = ($disk_usage_limit === PHP_INT_MAX) ? PHP_INT_MAX : max($disk_usage_limit - $total_disk_usage, 0);

        $disk_usage_limit_formatted = ($disk_usage_limit === PHP_INT_MAX)
            ? 'Unlimited'
            : number_format($disk_usage_limit / 1048576, 2);
        $total_disk_usage_formatted = number_format($total_disk_usage / 1048576, 2);
        $remaining_space_formatted  = ($remaining_space === PHP_INT_MAX)
            ? 'Unlimited'
            : number_format($remaining_space / 1048576, 2);

        $usage_percentage = ($disk_usage_limit > 0 && $disk_usage_limit !== PHP_INT_MAX)
            ? ($total_disk_usage / $disk_usage_limit) * 100
            : 0;

        // NB: width/height defaults (width 1980, height 1440)
        $max_image_width  = isset($options['max_image_width'])  ? (int) $options['max_image_width']  : 1980;
        $max_image_height = isset($options['max_image_height']) ? (int) $options['max_image_height'] : 1440;
        $min_image_width  = isset($options['min_image_width'])  ? (int) $options['min_image_width']  : 300;
        $min_image_height = isset($options['min_image_height']) ? (int) $options['min_image_height'] : 300;

        $user      = get_userdata($user_id);
        $user_role = 'User';
        if ($user && (is_super_admin($user->ID) || user_can($user, 'manage_options'))) {
            $user_role = 'Admin (Unlimited)';
        } elseif ($user && user_can($user, 'edit_others_posts')) {
            $user_role = 'Editor';
        } elseif ($user && user_can($user, 'publish_posts')) {
            $user_role = 'Author';
        } elseif ($user && user_can($user, 'edit_posts')) {
            $user_role = 'Contributor';
        } elseif ($user && user_can($user, 'read')) {
            $user_role = 'Subscriber';
        } else {
            $user_role = 'Guest';
        }

?>
        <script type="text/html" id="tmpl-disk-usage-message">
            <div class="row disk-usage-message">
                <div class="col">
                    <p><strong>Disk Usage Limit:</strong> <?php echo esc_html($disk_usage_limit_formatted); ?><?php echo $disk_usage_limit === PHP_INT_MAX ? '' : ' MB'; ?></p>
                    <p><strong>Current Usage:</strong> <?php echo esc_html($total_disk_usage_formatted); ?> MB</p>
                    <p><strong>Remaining Space:</strong> <?php echo esc_html($remaining_space_formatted); ?><?php echo $remaining_space === PHP_INT_MAX ? '' : ' MB'; ?></p>
                    <p><strong>Usage:</strong> <?php echo number_format($usage_percentage, 2); ?>%</p>
                </div>
                <div class="col">
                    <p><strong>Max Allowed Image size:</strong> <?php echo (int)$max_image_width; ?>px <strong>×</strong> <?php echo (int)$max_image_height; ?>px</p>
                    <p><strong>Min Allowed Image size:</strong> <?php echo (int)$min_image_width; ?>px <strong>×</strong> <?php echo (int)$min_image_height; ?>px</p>
                    <p>Your role in this website is <strong><?php echo esc_html($user_role); ?></strong>.</p>
                </div>
            </div>
        </script>

        <script>
            jQuery(function($) {
                if (typeof wp === 'undefined' || typeof wp.template !== 'function') return;
                var tpl = wp.template('disk-usage-message');
                var html = tpl();
                var $target = $('#wp-media-grid');
                if (!$target.length) {
                    $target = $('.wrap');
                } // list view fallback
                if ($target.length) {
                    $target.prepend(html);
                }
            });
        </script>

        <style>
            .disk-usage-message {
                padding: 15px;
                border: 1px solid #ddd;
                margin-bottom: 15px;
                background: #f9f9f9;
            }

            .disk-usage-message p {
                margin: 0;
                padding: 5px 0;
            }
        </style>
<?php
    });
}

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'upload.php') {
        wp_enqueue_script('wp-util');
    }
});

// Function to get disk usage limit based on user role
function get_disk_usage_limit($user_id, $options)
{
    $user = get_userdata($user_id);

    if (!$user) {
        return 10 * 1024 * 1024; // Default to 10MB if user does not exist
    }

    // ✅ Admins / Super Admins: unlimited
    if (is_super_admin($user->ID) || user_can($user, 'manage_options')) {
        return PHP_INT_MAX;
    }

    $user_email = $user->user_email;

    // Check if the user's email is in the list of custom email limits
    $emails = isset($options['entered_email_for_disk_usage_limit']) ? $options['entered_email_for_disk_usage_limit'] : [];
    $amounts = isset($options['entered_amount_for_disk_usage_limit']) ? $options['entered_amount_for_disk_usage_limit'] : [];

    $index = array_search($user_email, $emails);

    if ($index !== false && isset($amounts[$index])) {
        // Return custom limit if found
        return (int) $amounts[$index] * 1024 * 1024; // Convert MB to bytes
    }

    if (! (is_super_admin($user->ID) || user_can($user, 'manage_options'))) {
        if (user_can($user, 'edit_others_posts')) {
            return isset($options['editor_disk_usage_limit']) ? (int) $options['editor_disk_usage_limit'] * 1024 * 1024 : 100 * 1024 * 1024;
        } elseif (user_can($user, 'publish_posts')) {
            return isset($options['author_disk_usage_limit']) ? (int) $options['author_disk_usage_limit'] * 1024 * 1024 : 20 * 1024 * 1024;
        } elseif (user_can($user, 'edit_posts')) {
            return isset($options['contributor_disk_usage_limit']) ? (int) $options['contributor_disk_usage_limit'] * 1024 * 1024 : 10 * 1024 * 1024;
        } elseif (user_can($user, 'read')) {
            return isset($options['subscriber_disk_usage_limit']) ? (int) $options['subscriber_disk_usage_limit'] * 1024 * 1024 : 2 * 1024 * 1024;
        }
    }
    return 10 * 1024 * 1024; // Default to 10MB if no specific limit
}

// Function to update user disk usage
function update_user_disk_usage($post_id)
{
    $user_id = get_post_field('post_author', $post_id);
    $file_path = get_attached_file($post_id);

    if (file_exists($file_path)) {
        $file_size = filesize($file_path);
        $current_usage = (int) get_user_meta($user_id, 'total_disk_usage', true);
        $new_usage = $current_usage + $file_size;
        update_user_meta($user_id, 'total_disk_usage', $new_usage);
    }
}

// Function to reduce user disk usage on file deletion
function reduce_user_disk_usage($post_id)
{
    $user_id = get_post_field('post_author', $post_id);
    $file_path = get_attached_file($post_id);

    if (file_exists($file_path)) {
        $file_size = filesize($file_path);
        $current_usage = (int) get_user_meta($user_id, 'total_disk_usage', true);
        $new_usage = $current_usage - $file_size;
        update_user_meta($user_id, 'total_disk_usage', max($new_usage, 0));
    }
}

?>