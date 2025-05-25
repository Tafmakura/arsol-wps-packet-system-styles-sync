<?php
/*
Plugin Name: Arsol WP Snippets Packet: Arsol System Style Sync
Requires Plugins: arsol-wp-snippets
Plugin URI: https://example.com/arsol-wp-snippets-packet-example
Description: A collection of useful snippets for WordPress development.
Version: 1.0.3
Author: Taf Makura
Author URI: https://example.com
License: GPL2
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include core necessary files
require_once plugin_dir_path(__FILE__) . 'functions/functions-css-snippets-loader.php';
require_once plugin_dir_path(__FILE__) . 'functions/functions-js-snippets-loader.php';
require_once plugin_dir_path(__FILE__) . 'functions/functions-php-snippets-loader.php';