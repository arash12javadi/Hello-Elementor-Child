<?php 

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function create_user_dash_pages_once() {
    $page_definitions = array(
        'my-comments' => array(
            'title' => 'My Comments',
            'content' => '[vg_display_admin_page page_url="'. home_url("/wp-admin/edit-comments.php") .'"][user_dashboard_float_button]'
        ),
        'my-posts' => array(
            'title' => 'My Posts',
            'content' => '[vg_display_admin_page page_url="'. home_url("/wp-admin/edit.php") .'"][user_dashboard_float_button]'
        ),
        'my-media' => array(
            'title' => 'My Media',
            'content' => '[vg_display_admin_page page_url="'. home_url("/wp-admin/upload.php") .'"][user_dashboard_float_button]'
        ),

    );

    foreach ($page_definitions as $slug => $page) {
        // Check if the page already exists
        $existing_page = get_page_by_path($slug);

        // If the page doesn't exist, create it
        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title' => $page['title'],
                'post_name' => $slug,
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            ));

            // Assign the custom template
            update_post_meta($page_id, '_wp_page_template', 'template-custom.php');
        }
    }
}

add_action('init', 'user_dash_page_creation_trigger');

function user_dash_page_creation_trigger() {
    if (isset($_GET['user_dash_pages']) && $_GET['user_dash_pages'] === 'true') {
        create_user_dash_pages_once();
        wp_safe_redirect(admin_url('edit.php?post_type=page'));
        exit;
    }
}


function exclude_pages_for_subscribers($query) {
    if (!is_admin() && is_user_logged_in() && current_user_can('subscriber')) {
        $excluded_page_slugs = array('my-media', 'my-comments', 'my-posts');

        $excluded_page_ids = array();

        foreach ($excluded_page_slugs as $slug) {
            // Get the ID of the page based on the slug
            $excluded_page = get_page_by_path($slug);

            // If the page ID is found, add it to the exclusion list
            if ($excluded_page) {
                $excluded_page_ids[] = $excluded_page->ID;
            }
        }

        // Exclude the pages from the query
        if (!empty($excluded_page_ids)) {
            $query->set('post__not_in', $excluded_page_ids);
        }
    }
}

add_action('pre_get_posts', 'exclude_pages_for_subscribers');


?>