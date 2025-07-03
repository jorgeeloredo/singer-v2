<?php
/**
 * WooCommerce Template - Final Version
 * This template handles all WooCommerce pages and uses our custom Singer templates
 * Exact adaptation of repository templates for WordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main class="min-h-screen">
    <?php if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) : ?>
        
        <!-- Product Listing - Exact copy of app/views/product/list.php -->
        <div class="px-4 py-8 site-container">
          <div class="mb-6">
            <h1 class="text-2xl font-normal text-gray-800">
              <?php
              if (is_product_category()) {
                single_cat_title();
              } elseif (is_product_tag()) {
                single_tag_title(__('Produits étiquetés ', 'singer-v2'));
              } elseif (is_shop()) {
                echo __('Tous nos produits', 'singer-v2');
              } else {
                echo __('Produits', 'singer-v2');
              }
              ?>
            </h1>
            <?php 
            if (is_product_category() || is_product_tag()) {
              $term_description = term_description();
              if (!empty($term_description)) {
                echo '<p class="mt-2 text-sm text-gray-600">' . wp_kses_post($term_description) . '</p>';
              }
            }
            ?>
          </div>

          <?php if (woocommerce_product_loop()) : ?>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
              <?php
              while (have_posts()) {
                the_post();
                global $product;
                
                // Get product data
                $product_id = $product->get_id();
                $product_image = wp_get_attachment_image_url($product->get_image_id(), 'medium');
                if (!$product_image) {
                  $product_image = wc_placeholder_img_src('medium');
                }
                
                // Get product level
                $product_level = get_post_meta($product_id, '_singer_level', true);
                
                // Get average rating (if reviews are enabled)
                $average_rating = 0;
                $review_count = 0;
                if (wc_review_ratings_enabled()) {
                  $average_rating = $product->get_average_rating();
                  $review_count = $product->get_review_count();
                }
              ?>
                <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                  <a href="<?php the_permalink(); ?>" class="block">
                    <!-- Product image with fixed aspect ratio -->
                    <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
                      <img
                        src="<?php echo esc_url($product_image); ?>"
                        alt="<?php echo esc_attr($product->get_name()); ?>"
                        class="absolute top-0 left-0 object-contain w-full h-full p-4">
                      
                      <!-- Sale badge -->
                      <?php if ($product->is_on_sale()) : ?>
                        <div class="absolute top-2 right-2">
                          <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">
                            <?php 
                            if ($product->get_regular_price()) {
                                $regular = floatval($product->get_regular_price());
                                $sale = floatval($product->get_price());
                                if ($regular > 0) {
                                    $percentage = round((($regular - $sale) / $regular) * 100);
                                    echo '-' . $percentage . '%';
                                } else {
                                    echo __('Promo', 'singer-v2');
                                }
                            } else {
                                echo __('Promo', 'singer-v2');
                            }
                            ?>
                          </span>
                        </div>
                      <?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                      <h3 class="mb-2 text-sm font-medium text-gray-800"><?php echo esc_html($product->get_name()); ?></h3>
                      
                      <!-- Star rating -->
                      <?php if (wc_review_ratings_enabled() && $review_count > 0) : ?>
                        <div class="flex items-center mt-1 mb-2">
                          <div class="flex">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                              <span class="<?php echo $i <= round($average_rating) ? 'text-yellow-400' : 'text-gray-300'; ?> text-xs">
                                <i class="fas fa-star"></i>
                              </span>
                            <?php endfor; ?>
                          </div>
                          <span class="ml-1 text-xs text-gray-500">(<?php echo $review_count; ?>)</span>
                        </div>
                      <?php endif; ?>
                      
                      <!-- Price -->
                      <div class="flex items-baseline mb-2">
                        <span class="text-lg font-semibold price-color">
                          <?php echo number_format($product->get_price(), 2, ',', ' '); ?> €
                        </span>
                        <?php if ($product->is_on_sale() && $product->get_regular_price()) : ?>
                          <span class="ml-2 text-sm text-gray-500 line-through">
                            <?php echo number_format($product->get_regular_price(), 2, ',', ' '); ?> €
                          </span>
                        <?php endif; ?>
                      </div>
                      
                      <!-- Stock status and level badge -->
                      <div class="flex items-center justify-between">
                        <?php if ($product->is_in_stock()) : ?>
                          <span class="text-xs text-green-600"><?php echo __('En stock', 'singer-v2'); ?></span>
                        <?php else : ?>
                          <span class="text-xs text-red-600"><?php echo __('Rupture de stock', 'singer-v2'); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($product_level) : ?>
                          <span class="px-2 py-1 text-xs text-white rounded green-badge">
                            <?php echo __('Niveau débutant', 'singer-v2'); ?>
                          </span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </a>
                </div>
              <?php } ?>
            </div>

          <?php else : ?>
            <!-- No products found -->
            <div class="p-8 text-center bg-white border border-gray-200 rounded-lg">
              <div class="flex justify-center mb-4">
                <i class="text-5xl text-gray-300 fas fa-box-open"></i>
              </div>
              <h2 class="mb-2 text-xl font-medium text-gray-800"><?php echo __('Aucun produit trouvé', 'singer-v2'); ?></h2>
              <p class="mb-6 text-gray-600"><?php echo __('Aucun produit n\'est disponible dans cette catégorie pour le moment.', 'singer-v2'); ?></p>
              <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="px-6 py-3 text-white transition rounded-full bg-primary hover:bg-primary-hover">
                <?php echo __('Voir tous les produits', 'singer-v2'); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>

    <?php elseif (is_product()) : ?>
        
        <!-- Single Product - Uses our custom template -->
        <?php
        while (have_posts()) :
            the_post();
            wc_get_template_part('content', 'single-product');
        endwhile;
        ?>

    <?php elseif (is_cart()) : ?>
        
        <!-- Cart Page - Exact copy of app/views/cart/index.php -->
        <?php
        // Get cart data
        $cart = WC()->cart;
        $cart_items = $cart->get_cart();
        $total_price = $cart->get_subtotal();
        $total_quantity = $cart->get_cart_contents_count();
        $shipping_cost = $cart->get_shipping_total();
        $final_total = $cart->get_total('');

        // Convert shipping cost to float
        $shipping_cost_float = floatval($shipping_cost);
        
        // Function to generate image URL
        function get_cart_image_url($product, $cart_item) {
            $image_id = $product->get_image_id();
            if ($image_id) {
                return wp_get_attachment_image_url($image_id, 'thumbnail');
            }
            return wc_placeholder_img_src('thumbnail');
        }
        ?>

        <div class="px-4 py-8 site-container">
          <div class="mb-6">
            <h1 class="text-2xl font-normal text-gray-800"><?php echo __('Votre panier', 'singer-v2'); ?></h1>
            <p class="text-sm text-gray-600">
              <?php echo $total_quantity; ?> <?php echo __('articles dans votre panier', 'singer-v2'); ?>
            </p>
          </div>

          <?php if ($total_quantity > 0) : ?>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
              <!-- Cart items -->
              <div class="lg:col-span-2">
                <div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
                  <?php foreach ($cart_items as $cart_item_key => $cart_item) : 
                    $product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    if ($product && $product->exists() && $cart_item['quantity'] > 0) :
                  ?>
                    <div class="p-4 border-b border-gray-200 last:border-0">
                      <div class="flex items-center">
                        <div class="w-20 h-20 mr-4 overflow-hidden bg-gray-100 rounded">
                          <img src="<?php echo esc_url(get_cart_image_url($product, $cart_item)); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="object-contain w-full h-full">
                        </div>
                        <div class="flex-1">
                          <h3 class="text-sm font-medium text-gray-800"><?php echo esc_html($product->get_name()); ?></h3>
                          <p class="text-sm text-gray-600"><?php echo number_format($product->get_price(), 2, ',', ' '); ?> €</p>
                        </div>
                        <div class="text-right">
                          <p class="text-sm font-medium"><?php echo $cart_item['quantity']; ?> x <?php echo number_format($product->get_price(), 2, ',', ' '); ?> €</p>
                        </div>
                      </div>
                    </div>
                  <?php endif; endforeach; ?>
                </div>
              </div>

              <!-- Order summary -->
              <div class="lg:col-span-1">
                <div class="p-6 bg-white border border-gray-200 rounded-lg">
                  <h2 class="mb-4 text-lg font-medium text-gray-800"><?php echo __('Récapitulatif', 'singer-v2'); ?></h2>
                  <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                      <span><?php echo __('Sous-total', 'singer-v2'); ?></span>
                      <span><?php echo number_format($total_price, 2, ',', ' '); ?> €</span>
                    </div>
                    <div class="flex justify-between">
                      <span><?php echo __('Livraison', 'singer-v2'); ?></span>
                      <span><?php echo $shipping_cost_float > 0 ? number_format($shipping_cost_float, 2, ',', ' ') . ' €' : __('Gratuit', 'singer-v2'); ?></span>
                    </div>
                  </div>
                  <div class="pt-4 border-t">
                    <div class="flex justify-between font-bold">
                      <span><?php echo __('Total', 'singer-v2'); ?></span>
                      <span><?php echo number_format(floatval($final_total), 2, ',', ' '); ?> €</span>
                    </div>
                  </div>
                  <a href="<?php echo wc_get_checkout_url(); ?>" class="block w-full mt-6 py-3 text-center text-white rounded-full bg-primary hover:bg-primary-hover">
                    <?php echo __('Passer commande', 'singer-v2'); ?>
                  </a>
                </div>
              </div>
            </div>
          <?php else : ?>
            <!-- Empty cart -->
            <div class="p-8 text-center bg-white border border-gray-200 rounded-lg">
              <div class="flex justify-center mb-4">
                <i class="text-5xl text-gray-300 fas fa-shopping-cart"></i>
              </div>
              <h2 class="mb-2 text-xl font-medium text-gray-800"><?php echo __('Votre panier est vide', 'singer-v2'); ?></h2>
              <p class="mb-6 text-gray-600"><?php echo __('Ajoutez des produits à votre panier pour continuer.', 'singer-v2'); ?></p>
              <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="px-6 py-3 text-white transition rounded-full bg-primary hover:bg-primary-hover">
                <?php echo __('Découvrir nos produits', 'singer-v2'); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>

    <?php elseif (is_checkout()) : ?>
        
        <!-- Checkout Page -->
        <div class="px-4 py-8 site-container">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <?php woocommerce_content(); ?>
            </div>
        </div>

    <?php elseif (is_account_page()) : ?>
        
        <!-- Account Page -->
        <div class="px-4 py-8 site-container">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <?php woocommerce_content(); ?>
            </div>
        </div>

    <?php else : ?>
        
        <!-- Fallback for other WooCommerce pages -->
        <div class="px-4 py-8 site-container">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <?php woocommerce_content(); ?>
            </div>
        </div>

    <?php endif; ?>
</main>

<?php get_footer(); ?>