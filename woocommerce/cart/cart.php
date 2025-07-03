<?php
/**
 * Cart Page Template - Exact copy of app/views/cart/index.php
 */

defined('ABSPATH') || exit;

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

// Available shipping methods (hardcoded like in original)
$shipping_methods = array(
    'standard' => array('name' => 'Livraison standard', 'cost' => 10.00),
    'express' => array('name' => 'Livraison express', 'cost' => 15.00),
    'free' => array('name' => 'Livraison gratuite', 'cost' => 0.00)
);

$current_shipping_method = 'standard';
if ($total_price >= 300) {
    $current_shipping_method = 'free';
    $shipping_cost_float = 0;
}

$final_total_float = $total_price + $shipping_cost_float;
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
      <!-- Cart items (left side) -->
      <div class="lg:col-span-2">
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
          <!-- Table header -->
          <div class="hidden grid-cols-12 gap-4 p-4 border-b border-gray-200 sm:grid">
            <div class="col-span-7">
              <span class="text-sm font-medium text-gray-700"><?php echo __('Produit', 'singer-v2'); ?></span>
            </div>
            <div class="col-span-2 text-center">
              <span class="text-sm font-medium text-gray-700"><?php echo __('Quantité', 'singer-v2'); ?></span>
            </div>
            <div class="col-span-3 text-right">
              <span class="text-sm font-medium text-gray-700"><?php echo __('Prix', 'singer-v2'); ?></span>
            </div>
          </div>

          <!-- Cart items -->
          <?php foreach ($cart_items as $cart_item_key => $cart_item) : 
            $product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
            
            if ($product && $product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
              $product_permalink = apply_filters('woocommerce_cart_item_permalink', $product->is_visible() ? $product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
              $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($product), $cart_item, $cart_item_key);
          ?>
            <div class="grid grid-cols-1 gap-4 p-4 border-b border-gray-200 sm:grid-cols-12 last:border-0" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
              <!-- Mobile: Product image + info in a row -->
              <div class="flex items-start col-span-1 sm:hidden">
                <div class="w-20 h-20 mr-4 overflow-hidden bg-gray-100 rounded">
                  <img src="<?php echo esc_url(get_cart_image_url($product, $cart_item)); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="object-contain w-full h-full">
                </div>
                <div class="flex-1">
                  <h3 class="mb-1 text-sm font-medium text-gray-800">
                    <?php if ($product_permalink) : ?>
                      <a href="<?php echo esc_url($product_permalink); ?>" class="hover:text-primary">
                        <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key)); ?>
                      </a>
                    <?php else : ?>
                      <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key)); ?>
                    <?php endif; ?>
                  </h3>
                  <div class="flex items-center mt-2">
                    <div class="flex items-center mr-4">
                      <div class="flex items-center border border-gray-300 rounded">
                        <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="decrease" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                          <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="w-12 py-1 text-center border-gray-300 border-x quantity-input" value="<?php echo esc_attr($cart_item['quantity']); ?>" min="1" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                        <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="increase" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                          <i class="fas fa-plus"></i>
                        </button>
                      </div>
                    </div>
                    <span class="text-sm font-medium text-gray-800"><?php echo $product_price; ?></span>
                  </div>
                  <div class="mt-2">
                    <button type="button" class="text-xs text-gray-500 hover:text-primary remove-item" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                      <i class="mr-1 fas fa-trash-alt"></i> <?php echo __('Supprimer', 'singer-v2'); ?>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Desktop: Product info -->
              <div class="items-center hidden col-span-7 sm:flex">
                <div class="flex-shrink-0 w-20 h-20 mr-4 overflow-hidden bg-gray-100 rounded">
                  <img src="<?php echo esc_url(get_cart_image_url($product, $cart_item)); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="object-contain w-full h-full">
                </div>
                <div>
                  <h3 class="mb-1 text-sm font-medium text-gray-800">
                    <?php if ($product_permalink) : ?>
                      <a href="<?php echo esc_url($product_permalink); ?>" class="hover:text-primary">
                        <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key)); ?>
                      </a>
                    <?php else : ?>
                      <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key)); ?>
                    <?php endif; ?>
                  </h3>
                  <button type="button" class="text-xs text-gray-500 hover:text-primary remove-item" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                    <i class="mr-1 fas fa-trash-alt"></i> <?php echo __('Supprimer', 'singer-v2'); ?>
                  </button>
                </div>
              </div>

              <!-- Desktop: Quantity selector -->
              <div class="items-center justify-center hidden col-span-2 sm:flex">
                <div class="flex items-center border border-gray-300 rounded">
                  <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="decrease" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                    <i class="fas fa-minus"></i>
                  </button>
                  <input type="number" class="w-12 py-1 text-center border-gray-300 border-x quantity-input" value="<?php echo esc_attr($cart_item['quantity']); ?>" min="1" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                  <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="increase" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>

              <!-- Desktop: Price -->
              <div class="items-center justify-end hidden col-span-3 sm:flex">
                <span class="text-sm font-medium text-gray-800"><?php echo $product_price; ?></span>
              </div>
            </div>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <!-- Continue shopping -->
        <div class="mt-4">
          <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="flex items-center text-sm text-gray-600 hover:text-primary">
            <i class="mr-2 fas fa-arrow-left"></i>
            <?php echo __('Continuer mes achats', 'singer-v2'); ?>
          </a>
        </div>
      </div>

      <!-- Order summary (right side) -->
      <div class="lg:col-span-1">
        <div class="p-6 bg-white border border-gray-200 rounded-lg sticky top-4">
          <h2 class="mb-4 text-lg font-medium text-gray-800"><?php echo __('Récapitulatif', 'singer-v2'); ?></h2>

          <!-- Shipping methods -->
          <div class="mb-4">
            <h3 class="mb-3 text-sm font-medium text-gray-700"><?php echo __('Mode de livraison', 'singer-v2'); ?></h3>
            <div class="space-y-2">
              <?php foreach ($shipping_methods as $method_id => $method) : 
                $method_cost = ($total_price >= 300 && $method_id === 'free') ? 0 : $method['cost'];
                $is_available = ($method_id === 'free') ? ($total_price >= 300) : true;
                $is_checked = ($current_shipping_method === $method_id);
              ?>
                <div class="flex items-center">
                  <input type="radio" id="shipping-<?php echo $method_id; ?>" name="shipping_method" value="<?php echo $method_id; ?>" 
                         class="shipping-method-radio text-primary" <?php echo $is_checked ? 'checked' : ''; ?> <?php echo !$is_available ? 'disabled' : ''; ?>>
                  <label for="shipping-<?php echo $method_id; ?>" class="ml-2 text-sm <?php echo !$is_available ? 'text-gray-400' : 'text-gray-700'; ?>">
                    <?php echo esc_html($method['name']); ?>
                  </label>
                  <span class="ml-auto text-sm <?php echo !$is_available ? 'text-gray-400' : 'text-gray-600'; ?>">
                    <?php echo $method_cost > 0 ? number_format($method_cost, 2, ',', ' ') . ' €' : __('Gratuit', 'singer-v2'); ?>
                  </span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="mb-4 space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600"><?php echo __('Sous-total', 'singer-v2'); ?></span>
              <span class="font-medium text-gray-800"><?php echo number_format($total_price, 2, ',', ' '); ?> €</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600"><?php echo __('Frais de livraison', 'singer-v2'); ?></span>
              <span class="font-medium text-gray-800" id="shipping-cost-display">
                <?php echo $shipping_cost_float > 0 ? number_format($shipping_cost_float, 2, ',', ' ') . ' €' : __('Gratuit', 'singer-v2'); ?>
              </span>
            </div>
          </div>

          <div class="pt-4 mb-6 border-t border-gray-200">
            <div class="flex justify-between">
              <span class="text-base font-medium text-gray-800"><?php echo __('Total', 'singer-v2'); ?></span>
              <span class="text-base font-bold text-gray-800" id="final-total-display">
                <?php echo number_format($final_total_float, 2, ',', ' '); ?> €
              </span>
            </div>
            <?php 
            $remaining_for_free_shipping = max(0, 300 - $total_price);
            if ($remaining_for_free_shipping > 0) : 
            ?>
              <p class="mt-2 text-xs text-gray-500" id="free-shipping-message">
                <?php echo sprintf(__('Plus que %.2f€ pour la livraison gratuite !', 'singer-v2'), $remaining_for_free_shipping); ?>
              </p>
            <?php else : ?>
              <p class="mt-2 text-xs text-green-600" id="free-shipping-message"><?php echo __('Livraison gratuite !', 'singer-v2'); ?></p>
            <?php endif; ?>
          </div>

          <a href="<?php echo wc_get_checkout_url(); ?>"
            class="block w-full py-3 text-base font-medium text-center text-white transition rounded-full bg-primary hover:bg-primary-hover">
            <?php echo __('Passer commande', 'singer-v2'); ?>
          </a>

          <div class="flex flex-col mt-4 space-y-2">
            <div class="flex items-center">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/visa.png" alt="Visa" class="h-6 mr-2">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mastercard.png" alt="Mastercard" class="h-6 mr-2">
              <span class="text-xs text-gray-500">et plus...</span>
            </div>
            <div class="flex items-center">
              <i class="mr-2 text-green-600 fas fa-lock"></i>
              <span class="text-xs text-gray-500"><?php echo __('Paiement sécurisé', 'singer-v2'); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php else : ?>
    <!-- Empty cart message -->
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const quantityInputs = document.querySelectorAll('.quantity-input');

    // Remove buttons
    const removeButtons = document.querySelectorAll('.remove-item');

    // Shipping method radios
    const shippingRadios = document.querySelectorAll('.shipping-method-radio');

    // Quantity button handlers
    quantityBtns.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const cartItemKey = this.getAttribute('data-cart-item');
            const input = document.querySelector(`.quantity-input[data-cart-item="${cartItemKey}"]`);
            
            if (input) {
                let currentValue = parseInt(input.value);
                let newValue = action === 'increase' ? currentValue + 1 : Math.max(1, currentValue - 1);
                
                input.value = newValue;
                updateCartItem(cartItemKey, newValue);
            }
        });
    });

    // Quantity input change handlers
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const cartItemKey = this.getAttribute('data-cart-item');
            const newValue = Math.max(1, parseInt(this.value) || 1);
            this.value = newValue;
            updateCartItem(cartItemKey, newValue);
        });
    });

    // Remove item handlers
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cartItemKey = this.getAttribute('data-cart-item');
            removeCartItem(cartItemKey);
        });
    });

    // Shipping method handlers
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateShippingMethod(this.value);
        });
    });

    function updateCartItem(cartItemKey, quantity) {
        // Use WordPress AJAX for cart updates
        const formData = new FormData();
        formData.append('action', 'update_cart_item');
        formData.append('cart_item_key', cartItemKey);
        formData.append('quantity', quantity);
        formData.append('nonce', wc_add_to_cart_params.wc_ajax_url);

        fetch(wc_add_to_cart_params.wc_ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Reload page to update cart
            window.location.reload();
        })
        .catch(error => {
            console.error('Error updating cart:', error);
            window.location.reload();
        });
    }

    function removeCartItem(cartItemKey) {
        if (confirm('<?php echo esc_js(__("Êtes-vous sûr de vouloir supprimer cet article ?", "singer-v2")); ?>')) {
            // Use WooCommerce remove from cart URL
            window.location.href = wc_add_to_cart_params.cart_url + '?remove_item=' + cartItemKey + '&_wpnonce=' + wc_add_to_cart_params.cart_redirect_after_add;
        }
    }

    function updateShippingMethod(method) {
        // For now, just update the display
        // In a real implementation, this would update the cart via AJAX
        console.log('Shipping method changed to:', method);
    }
});
</script>