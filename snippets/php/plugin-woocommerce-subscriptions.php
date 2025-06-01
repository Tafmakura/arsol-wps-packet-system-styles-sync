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

// Modify WooCommerce Subscriptions default price string to our custom format
add_filter('woocommerce_subscriptions_product_price_string', 'custom_subscription_price_string', 99, 3);

function custom_subscription_price_string($subscription_string, $product, $include) {
    // Frontend only - exclude admin and show actual billing amounts on transactional pages
    if (is_admin() || is_product() || is_cart() || is_checkout() || is_account_page()) {
        return $subscription_string;
    }

    // Ensure we have a valid product object
    if (!is_object($product) || !class_exists('WC_Subscriptions_Product')) {
        return $subscription_string;
    }

    // Check if the product is a subscription
    if (!WC_Subscriptions_Product::is_subscription($product)) {
        return $subscription_string;
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
    } else {
        // Fallback to original string for unsupported periods
        return $subscription_string;
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

// Handle variable subscription products - modify variation price display
add_filter('woocommerce_available_variation', 'custom_variation_subscription_price_display', 99, 3);

function custom_variation_subscription_price_display($variation_data, $product, $variation) {
    // Ensure WooCommerce Subscriptions functions are available
    if (!class_exists('WC_Subscriptions_Product')) {
        return $variation_data;
    }

    // Check if the variation is a subscription
    if (!WC_Subscriptions_Product::is_subscription($variation)) {
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
