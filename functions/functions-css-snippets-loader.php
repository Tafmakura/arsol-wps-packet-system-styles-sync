<?php
/**
 * CSS Snippets Loader for Arsol WP Snippets
 */

// Table System Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_table_styles');
function arsol_wps_packet_system_styles_add_table_styles($css_options) {
    $css_options[] = array(
        'name' => 'System: Table Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/system-table-styles.css',
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
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/system-button-styles.css',
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
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/system-form-styles.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// Pagination System Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_pagination_styles');
function arsol_wps_packet_system_styles_add_pagination_styles($css_options) {
    $css_options[] = array(
        'name' => 'System: Pagination Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/system-pagination-styles.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// Fluent Booking Plugin Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_fluent_booking_styles');
function arsol_wps_packet_system_styles_add_fluent_booking_styles($css_options) {
    $css_options[] = array(
        'name' => 'Plugin: Fluent Booking',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/plugin-fluent-booking.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// Fluent Support Plugin Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_fluent_support_styles');
function arsol_wps_packet_system_styles_add_fluent_support_styles($css_options) {
    $css_options[] = array(
        'name' => 'Plugin: Fluent Support',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/plugin-fluent-support.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// Formidable Forms Plugin Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_formidable_forms_styles');
function arsol_wps_packet_system_styles_add_formidable_forms_styles($css_options) {
    $css_options[] = array(
        'name' => 'Plugin: Formidable Forms',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/plugin-formidable-forms.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// Themeisle Menu Icon Plugin Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_themeisle_menu_icon_styles');
function arsol_wps_packet_system_styles_add_themeisle_menu_icon_styles($css_options) {
    $css_options[] = array(
        'name' => 'Plugin: Themeisle Menu Icon',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/plugin-themeisle-menu-icon.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// WooCommerce Plugin Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_woocommerce_styles');
function arsol_wps_packet_system_styles_add_woocommerce_styles($css_options) {
    $css_options[] = array(
        'name' => 'Plugin: WooCommerce',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/plugin-woocommerce.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// WooCommerce Subscriptions Plugin Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_woocommerce_subscriptions_styles');
function arsol_wps_packet_system_styles_add_woocommerce_subscriptions_styles($css_options) {
    $css_options[] = array(
        'name' => 'Plugin: WooCommerce Subscriptions',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/plugin-woocommerce-subscriptions.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}

// WooCommerce Product Add-Ons Ultimate Plugin Styles
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_system_styles_add_woocommerce_product_addons_styles');
function arsol_wps_packet_system_styles_add_woocommerce_product_addons_styles($css_options) {
    $css_options[] = array(
        'name' => 'Plugin: WooCommerce Product Add-Ons Ultimate (Product Extras for WooCommerce)',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/plugin-product-extras-for-woocommerce.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}