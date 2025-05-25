<?php
/**
 * CSS Snippets Loader for Arsol WP Snippets
 */

// Add example.css
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_my_example_css');
function arsol_wps_packet_system_styles_add_my_example_css($css_options) {
    $css_options['my-example-css'] = array(
        'name' => 'My Example CSS file',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/example.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}