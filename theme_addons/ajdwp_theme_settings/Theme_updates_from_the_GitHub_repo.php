<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// __________________________________________________________________________//
//         Theme Update From GitHub Repo
// __________________________________________________________________________//

// This filter checks GitHub for the latest release and, if a newer version is available,
// informs WordPress of the update details.
add_filter('pre_set_site_transient_update_themes', 'automatic_GitHub_theme_updates', 100, 1);
function automatic_GitHub_theme_updates($data) {
    $theme   = get_stylesheet(); // Folder name of the current theme (expected to be Hello-Elementor-Child-Theme)
    $current = wp_get_theme()->get('Version'); // Current version of the theme
    $user    = 'arash12javadi'; // GitHub username
    $repo    = 'Hello-Elementor-Child'; // Repository name

    // GitHub API URL for the latest release
    $url = 'https://api.github.com/repos/' . $user . '/' . $repo . '/releases/latest';

    // Set up headers with a user agent
    $context = stream_context_create([
        'http' => [
            'header'  => "User-Agent: " . $user . "\r\n",
            'timeout' => 30,
        ],
    ]);

    // Fetch the API response
    $response = @file_get_contents($url, false, $context);
    if ( false === $response ) {
        error_log('GitHub API request failed for URL: ' . $url);
        return $data;
    }

    // Decode the JSON response
    $file = json_decode($response);
    if ( is_null($file) || empty($file->tag_name) ) {
        error_log('Invalid GitHub release data: ' . $response);
        return $data;
    }

    // Extract the version from the release tag (e.g., "1.2.3")
    $update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // If a new version exists, set up the update data with the GitHub zipball URL
    if ( version_compare($update, $current, '>') ) {
        $data->response[$theme] = [
            'theme'       => $theme,
            'new_version' => $update,
            'url'         => 'https://github.com/' . $user . '/' . $repo,
            'package'     => 'https://github.com/arash12javadi/Hello-Elementor-Child/archive/refs/heads/Theme.zip',
        ];
    }
    return $data;
}
