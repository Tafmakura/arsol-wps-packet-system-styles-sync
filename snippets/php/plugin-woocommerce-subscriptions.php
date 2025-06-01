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

// Remove any existing filters that might be causing duplicates
remove_filter('woocommerce_get_price_html', 'custom_subscription_price_display', 100);
remove_filter('woocommerce_available_variation', 'custom_variation_subscription_price_display', 100);
remove_filter('woocommerce_variable_subscription_price_html', 'custom_highest_variation_price_html', 10);
remove_filter('woocommerce_variable_price_html', 'custom_highest_variation_price_html', 10);

// Add our filters with priority 99 to ensure they override most plugins
add_filter('woocommerce_get_price_html', 'custom_subscription_price_display', 99, 2);
add_filter('woocommerce_available_variation', 'custom_variation_subscription_price_display', 99, 3);
add_filter('woocommerce_variable_subscription_price_html', 'custom_highest_variation_price_html', 99, 2);
add_filter('woocommerce_variable_price_html', 'custom_highest_variation_price_html', 99, 2);

// Add additional filters to catch all price display scenarios
add_filter('woocommerce_subscription_price_html', 'custom_subscription_price_display', 99, 2);
add_filter('woocommerce_variable_subscription_price_html', 'custom_subscription_price_display', 99, 2);

// Add a filter to clean up any remaining subscription details that get added
add_filter('woocommerce_get_price_html', 'clean_subscription_price_html', 100, 2);

function clean_subscription_price_html($price, $product) {
    // Don't apply on single product pages, cart, and checkout
    if (is_product() || is_cart() || is_checkout()) {
        return $price;
    }

    // Only clean subscription products that have our custom formatting
    if (class_exists('WC_Subscriptions_Product') && 
        WC_Subscriptions_Product::is_subscription($product) && 
        strpos($price, 'billing-description') !== false) {
        
        // Remove only the subscription-details span if it exists alongside our billing description
        $pattern = '/<span class="subscription-details">[^<]*<\/span>/';
        $price = preg_replace($pattern, '', $price);
    }
    
    return $price;
}

function custom_subscription_price_display($price, $product) {
    // Ensure WooCommerce Subscriptions functions are available
    if (!class_exists('WC_Subscriptions_Product')) {
        return $price;
    }

    // Check if the product is a subscription
    if (!WC_Subscriptions_Product::is_subscription($product)) {
        return $price;
    }

    // Don't apply on single product pages, cart, and checkout
    if (is_product() || is_cart() || is_checkout()) {
        return $price;
    }

    // Check if we've already modified this price or if subscription details exist
    if (strpos($price, 'billing-description') !== false) {
        return $price;
    }

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

    // Build the final price HTML
    $final_price = '';
    
    // Add regular price if on sale
    if ($regular_price_html) {
        $final_price .= $regular_price_html;
    }
    
    // Add the monthly price
    $final_price .= $price;
    
    // Add billing description
    if ($billing_description) {
        $final_price .= '<div class="billing-description">' . $billing_description . '</div>';
    }

    // Add screen reader text
    if ($monthly_price > 0) {
        $final_price .= '<div class="screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), wc_price($monthly_price)) . '</div>';
    }

    return $final_price;
}

function custom_variation_subscription_price_display($variation_data, $product, $variation) {
    // Ensure WooCommerce Subscriptions functions are available
    if (!class_exists('WC_Subscriptions_Product')) {
        return $variation_data;
    }

    // Check if the variation is a subscription
    if (!WC_Subscriptions_Product::is_subscription($variation)) {
        return $variation_data;
    }

    // Check if we've already modified this price or if subscription details exist
    if (isset($variation_data['price_html']) && (strpos($variation_data['price_html'], 'billing-description') !== false || strpos($variation_data['price_html'], 'subscription-details') !== false)) {
        return $variation_data;
    }

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

    // Build the final price HTML
    $final_price = '';
    
    // Add regular price if on sale
    if ($regular_price_html) {
        $final_price .= $regular_price_html;
    }
    
    // Add the monthly price
    $final_price .= $variation_data['price_html'];
    
    // Add billing description
    if ($billing_description) {
        $final_price .= '<div class="billing-description">' . $billing_description . '</div>';
    }

    // Add screen reader text
    if ($monthly_price > 0) {
        $final_price .= '<div class="screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), wc_price($monthly_price)) . '</div>';
    }

    $variation_data['price_html'] = $final_price;
    return $variation_data;
}

function custom_highest_variation_price_html($price, $product) {
    // Get all variation prices
    $variation_prices = $product->get_variation_prices();

    if (empty($variation_prices['price'])) {
        return $price;
    }

    // Find the highest price from variations
    $max_price = max($variation_prices['price']);

    // Format the price in WooCommerce format
    $price = sprintf(__('Highest: %s', 'woocommerce'), wc_price($max_price));

    return $price;
}
