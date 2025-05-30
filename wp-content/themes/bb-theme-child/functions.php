<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

add_action( 'template_redirect', 'redirect_shop_to_custom_page' );
function redirect_shop_to_custom_page() {
    if ( is_shop() ) {
        wp_safe_redirect( home_url( '/catalog' ) ); // Replace with your custom page slug
        exit;
    }
}

add_action('woocommerce_add_to_cart', 'auto_add_onboarding_fee', 20, 6);
add_action('woocommerce_cart_item_set_quantity', 'auto_add_onboarding_fee', 20, 3);
add_action('wc_ajax_add_to_cart', 'auto_add_onboarding_fee', 20);
function auto_add_onboarding_fee($cart_item_key = null, $product_id = null, $quantity = null, $variation_id = null, $variation = null, $cart_item_data = null) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    $onboarding_product_id = 8205; // Setup & Onboarding Call product ID
    $cart = WC()->cart;
    if (!$cart) {
        wc_get_logger()->error('Onboarding Fee: Cart not initialized', ['source' => 'onboarding-fee']);
        return;
    }

    // Validate product
    $product = wc_get_product($onboarding_product_id);
    if (!$product || !$product->is_purchasable() || !$product->is_in_stock()) {
        wc_get_logger()->error('Onboarding Fee: Invalid or unpurchasable product ID ' . $onboarding_product_id, ['source' => 'onboarding-fee']);
        return;
    }

    // Check session to prevent duplicate addition
    $session_key = 'onboarding_fee_added';
    $fee_added = WC()->session->get($session_key, false);

    // Check if onboarding fee is already in cart
    $onboarding_in_cart = false;
    foreach ($cart->get_cart() as $item) {
        if ($item['product_id'] == $onboarding_product_id) {
            $onboarding_in_cart = true;
            break;
        }
    }

    // Add onboarding fee if not in cart and not added this session
    if (!$onboarding_in_cart && !$fee_added) {
        wc_get_logger()->debug('Onboarding Fee: Adding product ID ' . $onboarding_product_id . ', Hook: ' . current_action(), ['source' => 'onboarding-fee']);
        $added = $cart->add_to_cart($onboarding_product_id, 1);
        if ($added) {
            WC()->session->set($session_key, true);
            wc_add_notice('Setup & Onboarding Call ($500) has been added to your cart.', 'success');
            wc_get_logger()->debug('Onboarding Fee: Successfully added product ID ' . $onboarding_product_id, ['source' => 'onboarding-fee']);
        } else {
            wc_get_logger()->error('Onboarding Fee: Failed to add product ID ' . $onboarding_product_id, ['source' => 'onboarding-fee']);
        }
    } elseif ($onboarding_in_cart) {
        wc_get_logger()->debug('Onboarding Fee: Already in cart, skipping', ['source' => 'onboarding-fee']);
    } elseif ($fee_added) {
        wc_get_logger()->debug('Onboarding Fee: Already added this session, skipping', ['source' => 'onboarding-fee']);
    }
}

// Clear session flag when cart is emptied
add_action('woocommerce_cart_emptied', 'clear_onboarding_fee_session');
function clear_onboarding_fee_session() {
    WC()->session->set('onboarding_fee_added', false);
    wc_get_logger()->debug('Onboarding Fee: Session flag cleared', ['source' => 'onboarding-fee']);
}

// Log cart contents after updates
add_action('woocommerce_cart_updated', 'log_cart_contents', 20);
function log_cart_contents() {
    $cart = WC()->cart;
    if ($cart) {
        $items = array_map(function($item) {
            return 'Product ID: ' . $item['product_id'] . ', Qty: ' . $item['quantity'];
        }, $cart->get_cart());
        wc_get_logger()->debug('Cart Contents: ' . (empty($items) ? 'Empty' : implode('; ', $items)), ['source' => 'onboarding-fee']);
    }
}

// Modify cart item name to add "Required" label and class
add_filter('woocommerce_cart_item_name', 'style_onboarding_cart_item_name', 10, 3);
function style_onboarding_cart_item_name($name, $cart_item, $cart_item_key) {
    $onboarding_product_id = 8205; // Setup & Onboarding Call product ID
    if ($cart_item['product_id'] == $onboarding_product_id) {
        $name = '<span class="onboarding-fee-item">' . $name . ' <span class="required-label" style="color: #d32f2f; font-size: 0.9em;">(Required)</span></span>';
        wc_get_logger()->debug('Onboarding Fee: Modified cart item name for product ID ' . $onboarding_product_id, ['source' => 'onboarding-fee']);
    }
    return $name;
}

// Add CSS to style onboarding fee and hide remove link
add_action('wp_footer', 'style_onboarding_fee_cart');
function style_onboarding_fee_cart() {
    if (!is_cart() && !is_checkout()) return; // Apply only on cart and checkout pages
    ?>
    <style>
        /* Style onboarding fee cart item */
        .onboarding-fee-item {
            font-weight: 600 !important; /* Bold text */
            background-color: #f8f1e9 !important; /* Light background */
            padding: 5px !important;
            display: inline-block !important;
        }
        /* Hide Remove Item link for onboarding fee */
        .woocommerce-cart-form__cart-item .onboarding-fee-item ~ .product-remove,
        .wc-block-cart__cart-item .onboarding-fee-item ~ .wc-block-components-product-remove-link {
            display: none !important;
        }
        /* Ensure other items have remove links */
        .woocommerce-cart-form__cart-item:not(:has(.onboarding-fee-item)) .product-remove,
        .wc-block-cart__cart-item:not(:has(.onboarding-fee-item)) .wc-block-components-product-remove-link {
            display: inline-block !important;
        }
    </style>
    <script>
        // Log cart HTML for debugging
        document.addEventListener('DOMContentLoaded', function() {
            const cartContainer = document.querySelector('.woocommerce-cart-form, .wc-block-cart');
            console.log('Cart HTML:', cartContainer ? cartContainer.outerHTML : 'No cart container found');
        });
    </script>
    <?php
    wc_get_logger()->debug('Onboarding Fee: Applied CSS for product ID 8205', ['source' => 'onboarding-fee']);
}