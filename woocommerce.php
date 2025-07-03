<?php
/**
 * WooCommerce Template - Final Clean Version
 * Direct output without wrapper containers to match repository
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); 

// For shop/category pages, redirect to our clean templates
if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
    if (is_shop()) {
        include(locate_template('page-shop.php'));
    } else {
        include(locate_template('woocommerce/archive-product.php'));
    }
} elseif (is_product()) {
    // Single product uses our custom template
    while (have_posts()) :
        the_post();
        wc_get_template_part('content', 'single-product');
    endwhile;
} elseif (is_cart()) {
    // Cart page uses our custom template
    wc_get_template('cart/cart.php');
} else {
    // Other WooCommerce pages - minimal wrapper
    echo '<div class="px-4 py-8 site-container">';
    woocommerce_content();
    echo '</div>';
}

get_footer(); ?>