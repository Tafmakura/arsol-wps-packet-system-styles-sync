<?php
/**
 * PHP Snippets Loader for Arsol WP Snippets
 */

// Add example.php
add_filter('arsol_wp_snippets_php_addon_files', 'add_my_example_php');
function add_my_example_php($php_options) {
    $php_options['my-example-php'] = array(
        'name' => 'My Example PHP file',
        'file' => __DIR__ . '/../snippets/php/example.php'
    );
    return $php_options;
}