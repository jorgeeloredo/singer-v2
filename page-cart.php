<?php
/**
 * Cart Page Template
 */

defined('ABSPATH') || exit;

get_header(); ?>

<main class="min-h-screen">
    <?php
    // Use WooCommerce cart template if available
    if (function_exists('woocommerce_cart')) {
        echo do_shortcode('[woocommerce_cart]');
    } else {
        // Fallback content
        echo '<div class="px-4 py-8 site-container">';
        echo '<h1 class="text-2xl font-normal text-gray-800 mb-6">Panier</h1>';
        echo '<p>Veuillez installer WooCommerce pour utiliser le panier.</p>';
        echo '</div>';
    }
    ?>
</main>

<?php get_footer(); ?>