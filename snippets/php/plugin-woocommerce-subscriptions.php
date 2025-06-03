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

/**
 * Check if we should show actual billing amounts instead of monthly pricing
 * 
 * @return bool True if actual billing amounts should be shown
 */
function should_show_actual_billing_amounts() {
    return is_admin() || is_product() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Format price without unnecessary .00 decimals
 * 
 * @param float $price The price to format
 * @return string Formatted price HTML
 */
function format_clean_price($price) {
    // Round to 2 decimal places to avoid floating point issues
    $price = round($price, 2);
    
    // If the price has .00 decimals, show without decimals
    if ($price == floor($price)) {
        return wc_price($price, array('decimals' => 0));
    }
    
    // Otherwise show with decimals
    return wc_price($price);
}

// Modify WooCommerce Subscriptions default price string to our custom format
add_filter('woocommerce_subscriptions_product_price_string', 'custom_subscription_price_string', 99, 3);

function custom_subscription_price_string($subscription_string, $product, $include) {
    // Show actual billing amounts on transactional pages, monthly pricing elsewhere
    if (should_show_actual_billing_amounts()) {
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
        $regular_price_html = '<del>' . format_clean_price($regular_price) . '</del> ';
    }

    // Calculate monthly price
    $monthly_price = 0;
    if ($subscription_period === 'month' && $subscription_interval >= 1) {
        $monthly_price = (float)$product->get_price() / $subscription_interval;
        $price = format_clean_price($monthly_price) . '<span class="arsol-saas-for-woo-subscriptions-billing-interval">/mo</span>';
        $billing_description = $subscription_interval == 1 
            ? __('billed every month', 'woocommerce') 
            : sprintf(__('billed every %s months', 'woocommerce'), $subscription_interval);
    } elseif ($subscription_period === 'year' && $subscription_interval >= 1) {
        $monthly_price = (float)$product->get_price() / ($subscription_interval * 12);
        $price = format_clean_price($monthly_price) . '<span class="arsol-saas-for-woo-subscriptions-billing-interval">/mo</span>';
        $billing_description = $subscription_interval == 1 
            ? __('billed every year', 'woocommerce') 
            : sprintf(__('billed every %s years', 'woocommerce'), $subscription_interval);
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
        $final_price .= '<div class="arsol-saas-for-woo-subscriptions-billing-description subscription-details woocommerce-price-suffix">' . $billing_description . '</div>';
    }

    // Add screen reader text
    if ($monthly_price > 0) {
        $final_price .= '<div class="arsol-saas-for-woo-subscriptions-screen-reader-text screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), format_clean_price($monthly_price)) . '</div>';
    }

    // Wrap everything in our container
    return '<div class="arsol-saas-for-woo-subscriptions-price-container price woocommerce-price-amount">' . $final_price . '</div>';
}

// Handle variable subscription products - modify variation price display
add_filter('woocommerce_available_variation', 'custom_variation_subscription_price_display', 99, 3);

function custom_variation_subscription_price_display($variation_data, $product, $variation) {
    // Show actual billing amounts on transactional pages, monthly pricing elsewhere
    if (should_show_actual_billing_amounts()) {
        return $variation_data;
    }

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
        $regular_price_html = '<del>' . format_clean_price($regular_price) . '</del> ';
    }

    // Calculate monthly price
    $monthly_price = 0;
    if ($subscription_period === 'month' && $subscription_interval >= 1) {
        $monthly_price = (float)$variation->get_price() / $subscription_interval;
        $variation_data['price_html'] = format_clean_price($monthly_price) . '<span class="arsol-saas-for-woo-subscriptions-billing-interval">/mo</span>';
        $billing_description = $subscription_interval == 1 
            ? __('billed every month', 'woocommerce') 
            : sprintf(__('billed every %s months', 'woocommerce'), $subscription_interval);
    } elseif ($subscription_period === 'year' && $subscription_interval >= 1) {
        $monthly_price = (float)$variation->get_price() / ($subscription_interval * 12);
        $variation_data['price_html'] = format_clean_price($monthly_price) . '<span class="arsol-saas-for-woo-subscriptions-billing-interval">/mo</span>';
        $billing_description = $subscription_interval == 1 
            ? __('billed every year', 'woocommerce') 
            : sprintf(__('billed every %s years', 'woocommerce'), $subscription_interval);
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
        $final_price .= '<div class="arsol-saas-for-woo-subscriptions-billing-description subscription-details woocommerce-price-suffix">' . $billing_description . '</div>';
    }

    // Add screen reader text
    if ($monthly_price > 0) {
        $final_price .= '<div class="arsol-saas-for-woo-subscriptions-screen-reader-text screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), format_clean_price($monthly_price)) . '</div>';
    }

    // Wrap everything in our container and update the variation data
    $variation_data['price_html'] = '<div class="arsol-saas-for-woo-subscriptions-price-container price woocommerce-price-amount">' . $final_price . '</div>';
    return $variation_data;
}
