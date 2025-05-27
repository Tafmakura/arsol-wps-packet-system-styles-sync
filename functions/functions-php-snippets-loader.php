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
        'file' => __DIR__ . '/../snippets/php/plugin-bricks.php'
    );
    return $php_options;
}

// Add Test1 PHP
//add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_test1_php', 15);
function arsol_wps_packet_system_styles_add_test1_php($php_options) {
    $php_options[] = array(
        'name' => 'Test1 Logger',
        'file' => __DIR__ . '/../snippets/php/test1.php',
        'priority' => 5
    );
    return $php_options;
}

// Add Test2 PHP
//add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_test2_php', 5);
function arsol_wps_packet_system_styles_add_test2_php($php_options) {
    $php_options[] = array(
        'name' => 'Test2 Logger',
        'file' => __DIR__ . '/../snippets/php//test2.php',
        'priority' => 15
    );
    return $php_options;
}

