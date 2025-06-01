<?php
/**
 * Shortcode to display WooCommerce product excerpt
 * Usage: [arsol-product-excerpt id=XXX characters=XXX]
 * 
 * @param array $atts Shortcode attributes
 * @return string Product excerpt
 */
function arsol_product_excerpt_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(
        array(
            'id' => 0,
            'characters' => 0,
        ),
        $atts,
        'arsol-product-excerpt'
    );

    // Get product
    $product = wc_get_product($atts['id']);
    
    if (!$product) {
        return '';
    }

    // If it's a variation, get the parent product
    if ($product->is_type('variation')) {
        $product = wc_get_product($product->get_parent_id());
    }

    // Get excerpt
    $excerpt = $product->get_short_description();
    
    // If no excerpt, try to get description
    if (empty($excerpt)) {
        $excerpt = $product->get_description();
    }

    // If characters limit is set and excerpt is longer than limit
    if (!empty($atts['characters']) && strlen($excerpt) > $atts['characters']) {
        $excerpt = substr($excerpt, 0, $atts['characters']) . '...';
    }

    return $excerpt;
}
add_shortcode('arsol-product-excerpt', 'arsol_product_excerpt_shortcode');

/**
 * Shortcode to display WooCommerce product price
 * Usage: [arsol-product-price id=XXX]
 * 
 * @param array $atts Shortcode attributes
 * @return string Product price HTML
 */
function arsol_product_price_shortcode($atts) {
    $atts = shortcode_atts(
        array('id' => 0),
        $atts,
        'arsol-product-price'
    );
    $product = wc_get_product($atts['id']);
    if (!$product) return '';
    return $product->get_price_html();
}
add_shortcode('arsol-product-price', 'arsol_product_price_shortcode');

/**
 * Shortcode to display WooCommerce product page link
 * Usage: [arsol-product-link id=XXX text="View Product"]
 * 
 * @param array $atts Shortcode attributes
 * @return string Product link HTML
 */
function arsol_product_link_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(
        array(
            'id' => 0,
            'text' => 'View Product',
        ),
        $atts,
        'arsol-product-link'
    );

    // Get product
    $product = wc_get_product($atts['id']);
    
    if (!$product) {
        return '';
    }

    // If it's a variation, get the parent product URL
    if ($product->is_type('variation')) {
        $product_url = get_permalink($product->get_parent_id());
        // Add variation ID as query parameter
        $product_url = add_query_arg('variation_id', $product->get_id(), $product_url);
    } else {
        $product_url = get_permalink($product->get_id());
    }
    
    // Create link HTML
    $link_html = sprintf(
        '<a href="%s" class="arsol-product-link">%s</a>',
        esc_url($product_url),
        esc_html($atts['text'])
    );
    
    return $link_html;
}
add_shortcode('arsol-product-link', 'arsol_product_link_shortcode');

/**
 * Shortcode to display WooCommerce direct checkout link
 * Usage: [arsol-checkout-link id=XXX text="Buy Now"]
 * 
 * @param array $atts Shortcode attributes
 * @return string Checkout link HTML
 */
function arsol_checkout_link_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(
        array(
            'id' => 0,
            'text' => 'Buy Now',
        ),
        $atts,
        'arsol-checkout-link'
    );

    // Get product
    $product = wc_get_product($atts['id']);
    
    if (!$product) {
        return '';
    }

    // Check if it's a subscription product
    $is_subscription = $product->is_type('subscription') || 
                      $product->is_type('variable-subscription') || 
                      ($product->is_type('variation') && $product->get_parent_id() && wc_get_product($product->get_parent_id())->is_type('variable-subscription'));

    // Build checkout URL parameters
    $checkout_params = array(
        'add-to-cart' => $product->is_type('variation') ? $product->get_parent_id() : $product->get_id(),
        'quantity' => 1,
    );

    // Handle different product types
    switch ($product->get_type()) {
        case 'variation':
            // For variations, add variation-specific parameters
            $checkout_params['variation_id'] = $product->get_id();
            $variation_attributes = $product->get_attributes();
            foreach ($variation_attributes as $attribute => $value) {
                $checkout_params['attribute_' . sanitize_title($attribute)] = $value;
            }
            // For subscription variations, ensure proper subscription parameters
            if ($is_subscription) {
                $checkout_url = get_permalink($product->get_parent_id());
            } else {
                $checkout_url = add_query_arg($checkout_params, wc_get_checkout_url());
            }
            break;

        case 'variable':
        case 'variable-subscription':
            // For variable products, link to product page
            $checkout_url = get_permalink($product->get_id());
            break;

        case 'subscription':
            // For subscription products, link to product page
            $checkout_url = get_permalink($product->get_id());
            break;

        case 'grouped':
            // For grouped products, link to product page
            $checkout_url = get_permalink($product->get_id());
            break;

        case 'simple':
        default:
            // For simple products, direct to checkout
            $checkout_url = add_query_arg($checkout_params, wc_get_checkout_url());
            break;
    }
    
    // Create link HTML
    $link_html = sprintf(
        '<a href="%s" class="arsol-checkout-link">%s</a>',
        esc_url($checkout_url),
        esc_html($atts['text'])
    );
    
    return $link_html;
}
add_shortcode('arsol-checkout-link', 'arsol_checkout_link_shortcode');
