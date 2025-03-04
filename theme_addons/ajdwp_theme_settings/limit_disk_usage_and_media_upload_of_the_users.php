<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- limit media upload for users ---------------------------//
// Limit Disk space, files size, file types, and photo dimensions before upload ----------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['limit_uploads'])) {
    $user_id = get_current_user_id();
    $user = get_userdata($user_id);
    if(!user_can($user, 'administrator')){
        add_filter('wp_handle_upload_prefilter', function($file) {
            // Get options and image info
            $options = get_option('AJDWP_theme_options');
            $mimes = array('image/jpeg', 'image/png', 'image/gif');
            $img = getimagesize($file['tmp_name']);
            
            // Set upload limits
            $max_upload_size = isset($options['max_upload_size']) ? (int) $options['max_upload_size'] : 500;
            $max_image_height = isset($options['max_image_height']) ? (int) $options['max_image_height'] : 1440;
            $max_image_width = isset($options['max_image_width']) ? (int) $options['max_image_width'] : 1980;
            $min_image_height = isset($options['min_image_height']) ? (int) $options['min_image_height'] : 300;
            $min_image_width = isset($options['min_image_width']) ? (int) $options['min_image_width'] : 300;

            // Check if image size is determinable
            if ($img === false) {
                $file['error'] = 'Unable to determine image size. Please check the file.';
                return $file;
            }

            // Check image dimensions
            if ($img[0] > $max_image_width) {
                $file['error'] = 'Image too large. Maximum allowed width is ' . $max_image_width . 'px. Uploaded image width is ' . $img[0] . 'px';
            } elseif ($img[1] > $max_image_height) {
                $file['error'] = 'Image too large. Maximum allowed height is ' . $max_image_height . 'px. Uploaded image height is ' . $img[1] . 'px';
            } elseif ($img[0] < $min_image_width) {
                $file['error'] = 'Image too small. Minimum allowed width is ' . $min_image_width . 'px. Uploaded image width is ' . $img[0] . 'px';
            } elseif ($img[1] < $min_image_height) {
                $file['error'] = 'Image too small. Minimum allowed height is ' . $min_image_height . 'px. Uploaded image height is ' . $img[1] . 'px';
            }

            // Check file size
            $filesize = filesize($file['tmp_name']);
            if ($filesize > $max_upload_size * 1024) {
                $file['error'] = 'File uploads exceeding ' . number_format($max_upload_size) . ' KB are prohibited. This file size is ' . number_format($filesize / 1024, 2) . ' KB.';
            }

            // Check total disk usage
            $user_id = get_current_user_id();
            $total_disk_used = (int) get_user_meta($user_id, 'total_disk_usage', true);
            $disk_usage_limit = get_disk_usage_limit($user_id, $options);
            
            if (($filesize + $total_disk_used) > $disk_usage_limit) {
                $file['error'] = 'You are allowed to upload files up to ' . number_format($disk_usage_limit / 1024 / 1024, 2) . ' MB. You have used ' . number_format($total_disk_used / 1024 / 1024, 2) . ' MB. You have ' . number_format(($disk_usage_limit - $total_disk_used) / 1024 / 1024, 2) . ' MB left.';
            }

            // Check file type
            if (!in_array($file['type'], $mimes)) {
                $file['error'] = 'Only images (jpeg / png / gif) are allowed.';
            }

            return $file;
        });

        // Set custom upload size limit
        add_filter('upload_size_limit', function($size) {
            $options = get_option('AJDWP_theme_options');
            $max_upload_size = isset($options['max_upload_size']) ? (int) $options['max_upload_size'] : 500;
            return $max_upload_size * 1024;
        });

        // Display disk usage message on media upload page
        add_action('print_media_templates', function() {
            $user_id = get_current_user_id();
            $options = get_option('AJDWP_theme_options');
            $disk_usage_limit = get_disk_usage_limit($user_id, $options);
            $disk_usage_limit_formatted = number_format($disk_usage_limit / 1024 / 1024, 2);
            $total_disk_usage = (int) get_user_meta($user_id, 'total_disk_usage', true);
            $total_disk_usage_formatted = number_format($total_disk_usage / 1024 / 1024, 2);
            $remaining_space = $disk_usage_limit - $total_disk_usage;
            $remaining_space_formatted = number_format($remaining_space / 1024 / 1024, 2);
            $usage_percentage = ($total_disk_usage / $disk_usage_limit) * 100;
            // Set upload limits
            $max_image_height = isset($options['max_image_height']) ? (int) $options['max_image_height'] : 1980;
            $max_image_width = isset($options['max_image_width']) ? (int) $options['max_image_width'] : 1440;
            $min_image_height = isset($options['min_image_height']) ? (int) $options['min_image_height'] : 300;
            $min_image_width = isset($options['min_image_width']) ? (int) $options['min_image_width'] : 300;
            
            $user = get_userdata($user_id);
            $user_role = '';
            if (user_can($user, 'edit_others_posts')) {
                $user_role = 'editor';
            } elseif (user_can($user, 'publish_posts')) {
                $user_role = 'author';
            } elseif (user_can($user, 'edit_posts')) {
                $user_role = 'contributor';
            } elseif (user_can($user, 'read')) {
                $user_role = 'subscrber';
            }

            ?>
            <script type="text/html" id="tmpl-disk-usage-message">
            <div class="row disk-usage-message">
                    <div class="col">
                        <p><strong>Disk Usage Limit:</strong> <?php echo $disk_usage_limit_formatted; ?> MB</p>
                        <p><strong>Current Usage:</strong> <?php echo esc_html($total_disk_usage_formatted); ?> MB</p>
                        <p><strong>Remaining Space:</strong> <?php echo esc_html($remaining_space_formatted); ?> MB</p>
                        <p><strong>Usage:</strong> <?php echo number_format($usage_percentage, 2); ?>%</p>
                    </div>
                    <class class="col"> 
                        <p><strong>Max Allowed Image size:</strong> <?php echo $max_image_width .'px <strong>*</strong> '. $max_image_height; ?>px</p> 
                        <p><strong>Min Allowed Image size:</strong> <?php echo $min_image_width .'px <strong>*</strong> '. $min_image_height; ?>px</p> 
                        <p>Your role in this website is <strong><?php echo $user_role;?>.</strong></p> 
                    </class>
                </div>
            </script>
            <style>
                .disk-usage-message {
                    padding: 15px;
                    border: 1px solid #ddd;
                    margin-bottom: 15px;
                    background-color: #f9f9f9;
                }
                .disk-usage-message p {
                    margin: 0;
                    padding: 5px 0;
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var message = wp.template('disk-usage-message');
                    $('#wp-media-grid').prepend(message);
                });
            </script>
        <?php
        
        });

        // Update user disk usage on file upload
        add_action('add_attachment', function($post_id) {
            update_user_disk_usage($post_id);
        });

        // Reduce user disk usage on file deletion
        add_action('delete_attachment', function($post_id) {
            reduce_user_disk_usage($post_id);
        });
    }//end if(!user_can($user, 'administrator')){
} //if (!empty($options['limit_uploads'])) {

// Function to get disk usage limit based on user role
function get_disk_usage_limit($user_id, $options) {
    $user = get_userdata($user_id);

    if (!$user) {
        return 10 * 1024 * 1024; // Default to 10MB if user does not exist
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

    if ($user && !user_can($user, 'administrator')) {
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
function update_user_disk_usage($post_id) {
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
function reduce_user_disk_usage($post_id) {
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