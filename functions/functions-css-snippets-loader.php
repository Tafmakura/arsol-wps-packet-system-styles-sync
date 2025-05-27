<?php
/**
 * CSS Snippets Loader for Arsol WP Snippets
 */

// Table System Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_table_styles');
function arsol_wps_packet_system_styles_add_table_styles($css_options) {
    $css_options[] = array(
        'name' => 'System: Table Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/table-system-styles.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// Button System Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_button_styles');
function arsol_wps_packet_system_styles_add_button_styles($css_options) {
    $css_options[] = array(
        'name' => 'System: Button Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/button-system-styles.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// Form System Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_form_styles');
function arsol_wps_packet_system_styles_add_form_styles($css_options) {
    $css_options[] = array(
        'name' => 'System: Form Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/form-system-styles.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}