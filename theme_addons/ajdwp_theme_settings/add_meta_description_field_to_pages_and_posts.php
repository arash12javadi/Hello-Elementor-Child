<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- add_meta_descriptions ---------------------------//
// Add Meta Description from Custom Fields in WordPress with Default Description

$options = get_option('AJDWP_theme_options');
if (!empty($options['add_meta_descriptions'])) {
    add_action('wp_head', function () {
        if (is_single() || is_page()) {
            $post_id = get_the_ID();
            $meta_description_raw = get_post_meta($post_id, 'description', true);

            if ($meta_description_raw) {
                // Sanitize and format the description
                $meta_description = esc_attr($meta_description_raw);
                ?>
                <meta name="description" content="<?php echo $meta_description; ?>">
                <?php
            }

        }
    });

    // Add a meta box for the description field
    function add_description_meta_box_theme() {
        add_meta_box(
            'description_meta_box',      // ID of the meta box
            'Description',               // Title of the meta box
            'display_description_meta_box_theme', // Callback function
            ['post', 'page'],            // Post types where the meta box should appear
            'normal',                    // Context (normal, side, advanced)
            'high'                       // Priority
        );
    }
    add_action('add_meta_boxes', 'add_description_meta_box_theme');

    // Display the meta box
    function display_description_meta_box_theme($post) {
        $description = get_post_meta($post->ID, 'description', true);
        wp_nonce_field(basename(__FILE__), 'description_meta_box_nonce');
        ?>
        <label for="description">Enter Meta Description:</label>
        <textarea name="description" id="description" style="width:100%;"><?php echo esc_attr($description); ?></textarea>
        <?php
    }

    // Save the meta box data
    function save_description_meta_box_theme($post_id) {
        // Check if nonce is set and valid
        if (!isset($_POST['description_meta_box_nonce']) || !wp_verify_nonce($_POST['description_meta_box_nonce'], basename(__FILE__))) {
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

        // Sanitize and save the description
        $new_description = (isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '');
        update_post_meta($post_id, 'description', $new_description);
    }
    add_action('save_post', 'save_description_meta_box_theme');
}

?>