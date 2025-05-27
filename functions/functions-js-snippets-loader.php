<?php
/**
 * JS Snippets Loader for Arsol WP Snippets
 */

// Add example.js
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_wps_packet_system_styles_add_my_example_js');
function arsol_wps_packet_system_styles_add_my_example_js($js_options) {
    $js_options[] = array(
        'name' => 'My Example JS file',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/example.js',
        'context' => 'frontend',
        'position' => 'footer',
        'priority' =>989898 
    );
    return $js_options;
}