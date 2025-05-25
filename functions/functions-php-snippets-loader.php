<?php
/**
 * PHP Snippets Loader for Arsol WP Snippets
 */

// Add example.php
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_my_example_php');
function arsol_wps_packet_system_styles_add_my_example_php($php_options) {
    $php_options['my-example-php'] = array(
        'name' => 'My Example PHP file',
        'file' => __DIR__ . '/../snippets/php/example.php'
    );
    return $php_options;
}


// Add example.php
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_twooo');
function arsol_wps_packet_system_styles_twooo($php_options) {
    $php_options['my-example-php'] = array(
        'name' => 'My Example PHP file 2',
        'file' => __DIR__ . '/../snippets/php/twooo.php'
    );
    return $php_options;
}