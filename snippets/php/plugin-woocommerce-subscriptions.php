<?php
/**
 * WooCommerce Subscriptions Plugin Snippets
 * 
 * This file contains custom functions and modifications for WooCommerce Subscriptions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if WooCommerce Subscriptions is active
if (!function_exists('wcs_is_subscription')) {
    return;
}

// Modify the product price display for subscriptions
add_filter('woocommerce_get_price_html', 'custom_subscription_price_display', 100, 2);

function custom_subscription_price_display($price, $product) {
    // Debug log
    error_log('Custom subscription price display called for product: ' . $product->get_id());
    
    // Ensure WooCommerce Subscriptions functions are available
    if (!class_exists('WC_Subscriptions_Product')) {
        error_log('WC_Subscriptions_Product class not found');
        return $price;
    }

    // Check if the product is a subscription
    if (!WC_Subscriptions_Product::is_subscription($product)) {
        return $price;
    }

    error_log('Product is a subscription');

    // Get subscription details
    $subscription_period = WC_Subscriptions_Product::get_period($product);
    $subscription_interval = (float) WC_Subscriptions_Product::get_interval($product);
    $billing_description = '';
    $regular_price_html = '';

    // Handle sale prices
    if ($product->is_on_sale()) {
        $regular_price = (float)$product->get_regular_price();
        if ($subscription_period === 'year') {
            $regular_price /= ($subscription_interval * 12);
        } elseif ($subscription_period === 'month') {
            $regular_price /= $subscription_interval;
        }
        $regular_price_html = '<del>' . wc_price($regular_price) . '</del> ';
    }

    // Calculate monthly price
    $monthly_price = 0;
    if ($subscription_period === 'month' && $subscription_interval >= 1) {
        $monthly_price = (float)$product->get_price() / $subscription_interval;
        $price = sprintf(__('%s /mo', 'woocommerce'), wc_price($monthly_price));
        $billing_description = $subscription_interval == 1 
            ? __('Billed every month', 'woocommerce') 
            : sprintf(__('Billed every %s months', 'woocommerce'), $subscription_interval);
    } elseif ($subscription_period === 'year' && $subscription_interval >= 1) {
        $monthly_price = (float)$product->get_price() / ($subscription_interval * 12);
        $price = sprintf(__('%s /mo', 'woocommerce'), wc_price($monthly_price));
        $billing_description = $subscription_interval == 1 
            ? __('Billed every year', 'woocommerce') 
            : sprintf(__('Billed every %s years', 'woocommerce'), $subscription_interval);
    }

    // Add billing description
    if ($billing_description) {
        $price .= '<div class="billing-description">' . $billing_description . '</div>';
    }

    // Add screen reader text
    if ($monthly_price > 0) {
        $price .= '<div class="screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), wc_price($monthly_price)) . '</div>';
    }

    // Add regular price if on sale
    if ($regular_price_html) {
        $price = $regular_price_html . $price;
    }

    error_log('Final price display: ' . $price);
    return $price;
}

// Modify the variation price display for subscriptions
add_filter('woocommerce_available_variation', 'custom_variation_subscription_price_display', 100, 3);

function custom_variation_subscription_price_display($variation_data, $product, $variation) {
    // Debug log
    error_log('Custom variation subscription price display called for variation: ' . $variation->get_id());
    
    // Ensure WooCommerce Subscriptions functions are available
    if (!class_exists('WC_Subscriptions_Product')) {
        return $variation_data;
    }

    // Check if the variation is a subscription
    if (!WC_Subscriptions_Product::is_subscription($variation)) {
        return $variation_data;
    }

    error_log('Variation is a subscription');

    // Get subscription details
    $subscription_period = WC_Subscriptions_Product::get_period($variation);
    $subscription_interval = (float) WC_Subscriptions_Product::get_interval($variation);
    $billing_description = '';
    $regular_price_html = '';

    // Handle sale prices
    if ($variation->is_on_sale()) {
        $regular_price = (float)$variation->get_regular_price();
        if ($subscription_period === 'year') {
            $regular_price /= ($subscription_interval * 12);
        } elseif ($subscription_period === 'month') {
            $regular_price /= $subscription_interval;
        }
        $regular_price_html = '<del>' . wc_price($regular_price) . '</del> ';
    }

    // Calculate monthly price
    $monthly_price = 0;
    if ($subscription_period === 'month' && $subscription_interval >= 1) {
        $monthly_price = (float)$variation->get_price() / $subscription_interval;
        $variation_data['price_html'] = sprintf(__('%s /mo', 'woocommerce'), wc_price($monthly_price));
        $billing_description = $subscription_interval == 1 
            ? __('Billed every month', 'woocommerce') 
            : sprintf(__('Billed every %s months', 'woocommerce'), $subscription_interval);
    } elseif ($subscription_period === 'year' && $subscription_interval >= 1) {
        $monthly_price = (float)$variation->get_price() / ($subscription_interval * 12);
        $variation_data['price_html'] = sprintf(__('%s /mo', 'woocommerce'), wc_price($monthly_price));
        $billing_description = $subscription_interval == 1 
            ? __('Billed every year', 'woocommerce') 
            : sprintf(__('Billed every %s years', 'woocommerce'), $subscription_interval);
    }

    // Add billing description
    if ($billing_description) {
        $variation_data['price_html'] .= '<div class="billing-description">' . $billing_description . '</div>';
    }

    // Add screen reader text
    if ($monthly_price > 0) {
        $variation_data['price_html'] .= '<div class="screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), wc_price($monthly_price)) . '</div>';
    }

    // Add regular price if on sale
    if ($regular_price_html) {
        $variation_data['price_html'] = $regular_price_html . $variation_data['price_html'];
    }

    error_log('Final variation price display: ' . $variation_data['price_html']);
    return $variation_data;
}

// Modify the variable product price display for subscriptions
add_filter('woocommerce_variable_subscription_price_html', 'custom_highest_variation_price_html', 10, 2);
add_filter('woocommerce_variable_price_html', 'custom_highest_variation_price_html', 10, 2);

function custom_highest_variation_price_html($price, $product) {
    // Debug log
    error_log('Custom highest variation price display called for product: ' . $product->get_id());
    
    // Get all variation prices
    $variation_prices = $product->get_variation_prices();

    if (empty($variation_prices['price'])) {
        return $price;
    }

    // Find the highest price from variations
    $max_price = max($variation_prices['price']);

    // Format the price in WooCommerce format
    $price = sprintf(__('Highest: %s', 'woocommerce'), wc_price($max_price));

    error_log('Final highest price display: ' . $price);
    return $price;
}
