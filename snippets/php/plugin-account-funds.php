<?php 
function ars_get_account_funds_form() {
    // Check if Account Funds plugin class exists
    if (!class_exists('WC_Account_Funds_My_Account')) {
        return ''; // Return empty if the class doesn't exist
    }

    ob_start(); // Start output buffering
    
    $account_funds = new WC_Account_Funds_My_Account();
    
    if ('yes' === get_option('account_funds_enable_topup')) {
        $account_funds->my_account_topup();
    } else {
        $account_funds->my_account_products();
    }

    return ob_get_clean(); // Return buffered content
}
add_shortcode('ars-get-account-funds-form', 'ars_get_account_funds_form');





function ars_limit_account_funds_to_one($cart_item_data, $product_id) {
    if (!function_exists('WC') || !WC()->cart) {
        return $cart_item_data;
    }

    $product = wc_get_product($product_id);
    if (!$product || $product->get_type() !== 'account_funds') {
        return $cart_item_data;
    }

    // Remove any existing 'account_funds' product from the cart
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $existing_product = $cart_item['data'];
        if ($existing_product && $existing_product->get_type() === 'account_funds') {
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }

    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'ars_limit_account_funds_to_one', 10, 2);




