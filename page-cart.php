<?php
/**
 * Cart Page Template - No borders, direct styling like repository
 */

defined('ABSPATH') || exit;

get_header(); ?>

<main class="min-h-screen">
    <?php
    // Use WooCommerce cart template if available
    if (function_exists('WC') && WC()->cart) {
        // Use our custom cart template that matches repository styling
        wc_get_template('cart/cart.php');
    } else {
        // Simple fallback content without borders - matches repository style
        ?>
        <div class="px-4 py-8 site-container">
            <div class="mb-6">
                <h1 class="text-2xl font-normal text-gray-800"><?php echo __('Votre panier', 'singer-v2'); ?></h1>
                <p class="text-sm text-gray-600"><?php echo __('Votre panier est actuellement vide.', 'singer-v2'); ?></p>
            </div>
            
            <!-- Empty cart message -->
            <div class="p-8 text-center bg-white border border-gray-200 rounded-lg">
                <div class="flex justify-center mb-4">
                    <i class="text-5xl text-gray-300 fas fa-shopping-cart"></i>
                </div>
                <h2 class="mb-2 text-xl font-medium text-gray-800"><?php echo __('Votre panier est vide', 'singer-v2'); ?></h2>
                <p class="mb-6 text-gray-600"><?php echo __('Veuillez installer WooCommerce pour utiliser le panier.', 'singer-v2'); ?></p>
                <a href="<?php echo home_url('/shop'); ?>" class="px-6 py-3 text-white transition rounded-full bg-primary hover:bg-primary-hover">
                    <?php echo __('Découvrir nos produits', 'singer-v2'); ?>
                </a>
            </div>
        </div>
        <?php
    }
    ?>
</main>

<?php get_footer(); ?>