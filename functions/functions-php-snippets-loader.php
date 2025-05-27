<?php
/**
 * PHP Snippets Loader for Arsol WP Snippets
 */

// Add Account Funds PHP
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_account_funds_php');
function arsol_wps_packet_system_styles_add_account_funds_php($php_options) {
    $php_options[] = array(
        'name' => 'Plugin: Account Funds',
        'file' => __DIR__ . '/../snippets/php/plugin-account-funds.php'
    );
    return $php_options;
}

// Add Bricks PHP
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_bricks_php');
function arsol_wps_packet_system_styles_add_bricks_php($php_options) {
    $php_options[] = array(
        'name' => 'Plugin: Bricks Builder',
        'file' => __DIR__ . '/../snippets/php/plugin-bricks.php',
        'priority' => 99996
    );
    return $php_options;
}

