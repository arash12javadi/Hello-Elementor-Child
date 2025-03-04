<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- add_meta_keywords ---------------------------//
// Add Meta Keywords from Custom Fields in WordPress with Default Keywords
$options = get_option('AJDWP_theme_options');
if (!empty($options['add_meta_keywords'])) {
    add_action('wp_head', function () {
        if (is_single() || is_page()) {
            $post_id = get_the_ID();
            $meta_keywords_raw = get_post_meta($post_id, 'keywords', true);
            
            if ($meta_keywords_raw) {
                // Sanitize and format the keywords
                $meta_keywords = esc_attr($meta_keywords_raw);
                $meta_keywords = preg_replace('/\s*,\s*/', ', ', $meta_keywords);
                ?>
                <meta name="keywords" content="<?php echo $meta_keywords; ?>">
                <?php
            }

        }
    });

    // Add a meta box for the keywords field
    function add_keywords_meta_box_theme() {
        add_meta_box(
            'keywords_meta_box',       // ID of the meta box
            'Keywords',                // Title of the meta box
            'display_keywords_meta_box_theme', // Callback function
            ['post', 'page'],          // Post types where the meta box should appear
            'normal',                  // Context (normal, side, advanced)
            'high'                     // Priority
        );
    }
    add_action('add_meta_boxes', 'add_keywords_meta_box_theme');

    // Display the meta box
    function display_keywords_meta_box_theme($post) {
        $keywords = get_post_meta($post->ID, 'keywords', true);
        wp_nonce_field(basename(__FILE__), 'keywords_meta_box_nonce');
        ?>
        <label for="keywords">Enter keywords, separated by commas:</label>
        <input type="text" name="keywords" id="keywords" value="<?php echo esc_attr($keywords); ?>" style="width:100%;" />
        <?php
    }

    // Save the meta box data
    function save_keywords_meta_box_theme($post_id) {
        // Check if nonce is set and valid
        if (!isset($_POST['keywords_meta_box_nonce']) || !wp_verify_nonce($_POST['keywords_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        // Check if user has permission to edit post
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // Check if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Sanitize and save the keywords
        $new_keywords = (isset($_POST['keywords']) ? sanitize_text_field($_POST['keywords']) : '');
        update_post_meta($post_id, 'keywords', $new_keywords);
    }
    add_action('save_post', 'save_keywords_meta_box_theme');
}


?>