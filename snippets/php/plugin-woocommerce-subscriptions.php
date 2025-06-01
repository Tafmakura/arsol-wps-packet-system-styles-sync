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
    // Ensure WooCommerce Subscriptions functions are available
    if (class_exists('WC_Subscriptions_Product') && $product->is_type('variable')) {
        // Check if the product is a subscription and not on the single product page
        if (WC_Subscriptions_Product::is_subscription($product) && !is_product()) {
            // Get all variation prices
            $variation_prices = $product->get_variation_prices();

            if (!empty($variation_prices['price'])) {
                // Find the highest price from variations
                $max_price = max($variation_prices['price']);

                // Assuming monthly billing for display purposes
                $monthly_price = $max_price / 12;
                $price = '<div class="starting-at">Starting at</div> <div class="price">' . sprintf(__('%s /mo', 'woocommerce'), wc_price($monthly_price)) . '</div>';
            }
        } elseif (WC_Subscriptions_Product::is_subscription($product)) {
            // Existing logic for single product page
            $subscription_period = WC_Subscriptions_Product::get_period($product);
            $subscription_interval = (float) WC_Subscriptions_Product::get_interval($product);
            $billing_description = '';
            $regular_price_html = '';

            if ($product->is_on_sale()) {
                $regular_price = (float)$product->get_regular_price();
                if ($subscription_period === 'year') {
                    $regular_price /= ($subscription_interval * 12);
                } elseif ($subscription_period === 'month') {
                    $regular_price /= $subscription_interval;
                }
                $regular_price_html = '<del>' . wc_price($regular_price) . '</del> ';
            }

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

            if ($billing_description) {
                $price .= '<div class="billing-description">' . $billing_description . '</div>';
            }

            $price .= '<div class="screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), wc_price($monthly_price)) . '</div>';

            if ($regular_price_html) {
                $price = $regular_price_html . $price;
            }
        }
    }

    return $price;
}

// Modify the variation price display for subscriptions
add_filter('woocommerce_available_variation', 'custom_variation_subscription_price_display', 100, 3);

function custom_variation_subscription_price_display($variation_data, $product, $variation) {
    // Ensure WooCommerce Subscriptions functions are available
    if (class_exists('WC_Subscriptions_Product') && $variation->is_type('variable')) {
        // Check if the variation is a subscription
        if (WC_Subscriptions_Product::is_subscription($variation)) {
            // Get the subscription period and interval
            $subscription_period = WC_Subscriptions_Product::get_period($variation);
            $subscription_interval = (float) WC_Subscriptions_Product::get_interval($variation);
            $billing_description = '';
            $regular_price_html = '';

            if ($variation->is_on_sale()) {
                $regular_price = (float)$variation->get_regular_price();
                if ($subscription_period === 'year') {
                    $regular_price /= ($subscription_interval * 12);
                } elseif ($subscription_period === 'month') {
                    $regular_price /= $subscription_interval;
                }
                $regular_price_html = '<del>' . wc_price($regular_price) . '</del> ';
            }

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

            if ($billing_description) {
                $variation_data['price_html'] .= '<div class="billing-description">' . $billing_description . '</div>';
            }

            $variation_data['price_html'] .= '<div class="screen-reader-text">' . sprintf(__('Price: %s per month', 'woocommerce'), wc_price($monthly_price)) . '</div>';

            if ($regular_price_html) {
                $variation_data['price_html'] = $regular_price_html . $variation_data['price_html'];
            }
        }
    }

    return $variation_data;
}

// Modify the variable product price display for subscriptions
add_filter('woocommerce_variable_subscription_price_html', 'custom_highest_variation_price_html', 10, 2);
add_filter('woocommerce_variable_price_html', 'custom_highest_variation_price_html', 10, 2);

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
