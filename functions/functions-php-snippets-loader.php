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
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_woo_shortcodes_php');
function arsol_wps_packet_system_styles_add_woo_shortcodes_php($php_options) {
    $php_options[] = array(
        'name' => 'Plugin: Woo Shortcodes',
        'file' => __DIR__ . '/../snippets/php/woo-shortcodes.php',
        'priority' => 99
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

// Add WooCommerce PHP
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_woocommerce_php');
function arsol_wps_packet_system_styles_add_woocommerce_php($php_options) {
    $php_options[] = array(
        'name' => 'Plugin: WooCommerce',
        'file' => __DIR__ . '/../snippets/php/plugin-woocommerce.php',
        'priority' => 99997
    );
    return $php_options;
}

// Add WooCommerce Subscriptions PHP
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_wps_packet_system_styles_add_woocommerce_subscriptions_php');
function arsol_wps_packet_system_styles_add_woocommerce_subscriptions_php($php_options) {
    $php_options[] = array(
        'name' => 'Plugin: WooCommerce Subscriptions',
        'file' => __DIR__ . '/../snippets/php/plugin-woocommerce-subscriptions.php',
        'priority' => 99
    );
    return $php_options;
}



