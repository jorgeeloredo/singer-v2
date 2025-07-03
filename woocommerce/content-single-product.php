<?php
/**
 * Single Product Template - Perfect copy of app/views/product/index.php
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;
$product_id = $product->get_id();
$gallery_images = $product->get_gallery_image_ids();
$all_images = array();

// Add main image first
if ($product->get_image_id()) {
    $all_images[] = $product->get_image_id();
}

// Add gallery images
$all_images = array_merge($all_images, $gallery_images);

$product_level = get_post_meta($product_id, '_singer_level', true);
if (!$product_level) {
    $product_level = 'débutant';
}

// Get product features for characteristics section
$features = get_post_meta($product_id, '_custom_features', true);
$product_features = array();
$featuresCount = 0;
$featuresFirstColumn = array();
$featuresSecondColumn = array();

if (!empty($features)) {
    $features_array = explode("||", $features);
    $features_array = array_filter(array_map('trim', $features_array));
    $featuresCount = count($features_array);
    
    if (!empty($features_array)) {
        $half = ceil(count($features_array) / 2);
        $featuresFirstColumn = array_slice($features_array, 0, $half);
        $featuresSecondColumn = array_slice($features_array, $half);
        $product_features = $features_array; // Pour compatibilité
    }
}

// Get product documents
$product_documents = get_post_meta($product_id, '_singer_documents', true);
if (!$product_documents) {
    $product_documents = array();
}
?>

<!-- Main Product Content - EXACT structure from project template -->
<div class="px-4 pb-12 site-container">
  <!-- Product Display Row -->
  <div class="grid grid-cols-1 gap-8 mb-12 md:grid-cols-2">
    <!-- Left: Product Gallery -->
    <div class="relative p-4 rounded-lg product-image-bg">
      <!-- Navigation Buttons positioned inside the product image -->
      <div class="absolute z-10 top-4 left-4">
        <a href="javascript:history.back()" class="flex items-center text-sm text-gray-600 hover:text-primary">
          <i class="mr-1 text-xs fas fa-chevron-left"></i> <?php echo __('Retour', 'singer-v2'); ?>
        </a>
      </div>
      <div class="absolute z-10 top-4 right-4">
        <span class="px-3 py-1 text-xs text-white rounded green-badge">
          <?php echo __('Niveau', 'singer-v2'); ?> <?php echo esc_html($product_level); ?>
        </span>
      </div>

      <!-- Main Image -->
      <div class="relative pt-8">
        <img
          src="<?php echo !empty($all_images) ? wp_get_attachment_url($all_images[0]) : wc_placeholder_img_src(); ?>"
          alt="<?php echo esc_attr($product->get_name()); ?>"
          class="object-contain w-full h-auto mx-auto"
          id="mainProductImage"
          data-original-src="<?php echo !empty($all_images) ? wp_get_attachment_url($all_images[0]) : wc_placeholder_img_src(); ?>" />

        <?php if (count($all_images) > 1) : ?>
          <!-- Navigation Arrows -->
          <button
            class="absolute left-0 flex items-center justify-center w-8 h-8 transform -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-sm top-1/2"
            id="prev-image">
            <i class="text-gray-600 fas fa-chevron-left"></i>
          </button>
          <button
            class="absolute right-0 flex items-center justify-center w-8 h-8 transform -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-sm top-1/2"
            id="next-image">
            <i class="text-gray-600 fas fa-chevron-right"></i>
          </button>
        <?php endif; ?>
      </div>

      <?php if (count($all_images) > 1) : ?>
        <!-- Thumbnails (use smaller optimized images) -->
        <div class="grid grid-cols-6 gap-2 mt-4">
          <?php foreach ($all_images as $index => $image_id) : ?>
            <div class="border-2 <?php echo $index === 0 ? 'border-primary thumbnail-active' : 'border-gray-200'; ?> rounded cursor-pointer thumbnail" data-index="<?php echo $index; ?>" data-full-url="<?php echo wp_get_attachment_url($image_id); ?>">
              <img src="<?php echo wp_get_attachment_image_url($image_id, 'thumbnail'); ?>" alt="Vue <?php echo $index + 1; ?>" class="object-cover w-full" style="aspect-ratio: 1 / 1;" />
            </div>
          <?php endforeach; ?>
          
          <?php
          // Add empty thumbnails if less than 6 images
          for ($i = count($all_images); $i < 6; $i++):
          ?>
            <div class="bg-gray-100 border border-gray-200 rounded cursor-not-allowed">
              <div style="aspect-ratio: 1 / 1"></div>
            </div>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Right: Product Information - vertically centered with flex -->
    <div class="flex flex-col justify-center">
      <!-- Product Title & Price -->
      <h1 class="mb-4 text-2xl font-normal text-gray-800"><?php echo $product->get_name(); ?></h1>

      <div class="mb-6">
        <div class="flex items-baseline">
          <span class="mr-2 text-xl font-semibold price-color"><?php echo number_format($product->get_price(), 2, ',', ' '); ?> €</span>
          <?php 
          $eco_part = get_post_meta($product_id, '_singer_eco_part', true);
          if ($eco_part && $eco_part > 0) : ?>
            <span class="text-sm text-gray-500"><?php echo sprintf(__('dont éco-participation %.2f€', 'singer-v2'), $eco_part); ?></span>
          <?php endif; ?>
        </div>

        <?php if ($product->is_on_sale() && $product->get_regular_price()) : ?>
          <div class="mt-2">
            <span class="text-sm text-gray-500 line-through"><?php echo number_format($product->get_regular_price(), 2, ',', ' '); ?> €</span>
            <span class="ml-2 text-sm font-medium text-green-600">
              <?php 
              $regular = floatval($product->get_regular_price());
              $sale = floatval($product->get_price());
              if ($regular > 0) {
                  $percentage = round((($regular - $sale) / $regular) * 100);
                  echo '-' . $percentage . '%';
              }
              ?>
            </span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Product Description -->
      <div class="mb-8">
        <div class="text-sm leading-relaxed text-gray-700">
          <?php if ($product->get_short_description()) : ?>
            <?php echo $product->get_short_description(); ?>
          <?php elseif ($product->get_description()) : ?>
            <?php echo wp_trim_words($product->get_description(), 30); ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Product identifiers -->
      <div class="mb-6 text-sm text-gray-600">
        <?php 
        $sku = get_post_meta($product_id, '_singer_sku', true) ?: $product->get_sku();
        if ($sku) : ?>
          <p><?php echo __('Référence', 'singer-v2'); ?> : <?php echo esc_html($sku); ?></p>
        <?php endif; ?>
        <?php 
        $gtin = get_post_meta($product_id, '_singer_gtin', true);
        if ($gtin) : ?>
          <p>GTIN : <?php echo esc_html($gtin); ?></p>
        <?php endif; ?>
      </div>

      <!-- Stock Information -->
      <div class="mb-6">
        <?php if ($product->is_in_stock()) : ?>
          <div class="flex items-center text-green-600">
            <i class="mr-2 fas fa-check-circle"></i>
            <span class="text-sm font-medium"><?php echo __('En stock', 'singer-v2'); ?></span>
            <?php if ($product->get_stock_quantity()) : ?>
              <span class="ml-2 text-sm text-gray-600">
                (<?php echo $product->get_stock_quantity(); ?> disponibles)
              </span>
            <?php endif; ?>
          </div>
        <?php else : ?>
          <div class="flex items-center text-red-600">
            <i class="mr-2 fas fa-times-circle"></i>
            <span class="text-sm font-medium"><?php echo __('Rupture de stock', 'singer-v2'); ?></span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Add to Cart Form - EXACT structure from project template -->
      <form action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="POST" class="singer-custom-form mb-6">
        <input type="hidden" name="product_id" value="<?php echo $product->get_id(); ?>">

        <div class="flex items-center mb-4">
          <label for="quantity" class="mr-4 text-sm font-medium text-gray-700"><?php echo __('Quantité', 'singer-v2'); ?> :</label>
          <div class="flex items-center border border-gray-300 rounded">
            <button type="button" class="px-3 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="decrease">
              <i class="fas fa-minus"></i>
            </button>
            <input
              type="number"
              name="quantity"
              id="quantity"
              value="1"
              min="1"
              max="<?php echo $product->get_max_purchase_quantity() ?: 999; ?>"
              class="w-12 py-1 text-center border-gray-300 border-x">
            <button type="button" class="px-3 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="increase">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>

        <button
          type="submit"
          name="add-to-cart"
          value="<?php echo esc_attr($product->get_id()); ?>"
          class="w-full px-6 py-3 mb-4 text-white transition-colors duration-200 rounded-full singer-red hover:singer-red-hover <?php echo !$product->is_in_stock() ? 'opacity-50 cursor-not-allowed' : ''; ?>"
          <?php echo !$product->is_in_stock() ? 'disabled' : ''; ?>>
          <?php echo $product->is_in_stock() ? __('Acheter cet article', 'singer-v2') : __('Indisponible', 'singer-v2'); ?>
        </button>
      </form>

      <!-- Features Bullets -->
      <div class="space-y-3">
        <div class="flex items-start">
          <i class="mt-1 mr-2 text-green-600 fas fa-check"></i>
          <span class="text-sm font-semibold text-gray-700"><?php echo __('Garantie fabricant', 'singer-v2'); ?></span>
        </div>
        <div class="flex items-start">
          <i class="mt-1 mr-2 text-green-600 fas fa-check"></i>
          <span class="text-sm font-semibold text-gray-700"><?php echo __('Retour gratuit sous 30 jours', 'singer-v2'); ?></span>
        </div>
        <div class="flex items-start">
          <i class="mt-1 mr-2 text-green-600 fas fa-check"></i>
          <span class="text-sm font-semibold text-gray-700"><?php echo __('Paiement en plusieurs fois', 'singer-v2'); ?></span>
        </div>
      </div>
    </div>
  </div>

  <?php if ($featuresCount > 0): ?>
    <!-- Product Characteristics Section - EXACT from project template -->
    <div class="max-w-6xl mx-auto mt-12 mb-16">
      <!-- Section Header -->
      <div class="flex items-center mb-8">
        <h2 class="text-2xl font-normal text-gray-800"><?php echo __('Caractéristiques', 'singer-v2'); ?></h2>
        <a href="#" class="ml-4 text-sm text-primary hover:underline"><?php echo __('Plus d\'infos', 'singer-v2'); ?></a>
        <div class="flex items-center justify-center w-5 h-5 ml-2 border border-gray-300 rounded-full">
          <span class="text-sm text-gray-500">?</span>
        </div>
      </div>

      <!-- Two-column layout with image -->
      <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <!-- Left column features -->
        <div class="md:col-span-1">
          <ul class="space-y-4">
            <?php foreach ($featuresFirstColumn as $feature): ?>
              <li class="flex items-start">
                <span class="flex-shrink-0 w-2 h-2 mt-2 mr-3 bg-gray-300 rounded-full"></span>
                <span class="text-gray-700"><?php echo esc_html($feature); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Middle column features -->
        <div class="md:col-span-1">
          <ul class="space-y-4">
            <?php foreach ($featuresSecondColumn as $feature): ?>
              <li class="flex items-start">
                <span class="flex-shrink-0 w-2 h-2 mt-2 mr-3 bg-gray-300 rounded-full"></span>
                <span class="text-gray-700"><?php echo esc_html($feature); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Right column (Image and download links) -->
        <div class="md:col-span-1">
          <!-- Image -->
          <div class="p-2 mb-4 border border-gray-200 rounded-lg">
            <img
              src="<?php echo isset($all_images[1]) ? wp_get_attachment_url($all_images[1]) : wp_get_attachment_url($all_images[0]); ?>"
              alt="<?php echo esc_attr($product->get_name()); ?> Caractéristiques"
              class="object-contain w-full h-auto rounded" />
          </div>

          <!-- Download links under the image -->
          <div class="space-y-3">
            <?php if (!empty($product_documents)): ?>
              <?php foreach ($product_documents as $document): ?>
                <a href="<?php echo esc_url($document['url']); ?>" class="flex items-center text-sm text-primary hover:underline">
                  <i class="mr-2 fas fa-download"></i>
                  <?php echo esc_html($document['name']); ?>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <!-- Default download links if no custom documents -->
              <a href="#" class="flex items-center text-sm text-primary hover:underline">
                <i class="mr-2 fas fa-download"></i>
                <?php echo __('Notice d\'utilisation', 'singer-v2'); ?>
              </a>
              <a href="#" class="flex items-center text-sm text-primary hover:underline">
                <i class="mr-2 fas fa-download"></i>
                <?php echo __('Guide d\'entretien', 'singer-v2'); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Related Products Section -->
  <?php
  $related_ids = wc_get_related_products($product->get_id(), 4);
  if (!empty($related_ids)) :
  ?>
    <section class="py-12 mt-16 bg-gray-50">
      <div class="px-4 site-container">
        <h2 class="mb-8 text-2xl font-normal text-gray-800"><?php echo __('Produits similaires', 'singer-v2'); ?></h2>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <?php
          foreach ($related_ids as $related_id) :
              $related_product = wc_get_product($related_id);
              if ($related_product) :
          ?>
              <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                  <a href="<?php echo esc_url($related_product->get_permalink()); ?>" class="block">
                      <!-- Updated image container with fixed aspect ratio -->
                      <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
                          <?php if ($related_product->get_image_id()) : ?>
                              <img src="<?php echo wp_get_attachment_url($related_product->get_image_id()); ?>"
                                   alt="<?php echo esc_attr($related_product->get_name()); ?>"
                                   class="absolute inset-0 object-contain w-full h-full p-4">
                          <?php else: ?>
                              <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-gray-400 fas fa-image fa-3x"></span>
                              </div>
                          <?php endif; ?>
                      </div>
                      <div class="p-4">
                          <h3 class="mb-2 text-sm font-medium text-gray-800 line-clamp-2"><?php echo esc_html($related_product->get_name()); ?></h3>
                          <div class="flex items-baseline mb-2">
                              <span class="text-lg font-semibold price-color"><?php echo number_format($related_product->get_price(), 2, ',', ' '); ?> €</span>
                          </div>
                          <?php if ($related_product->is_in_stock()) : ?>
                              <span class="text-xs text-green-600"><?php echo __('En stock', 'singer-v2'); ?></span>
                          <?php else : ?>
                              <span class="text-xs text-red-600"><?php echo __('Rupture de stock', 'singer-v2'); ?></span>
                          <?php endif; ?>
                      </div>
                  </a>
              </div>
          <?php 
              endif;
          endforeach; 
          ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</div>

<script>
// Image gallery functionality - Fixed to use full-size images
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');

    if (!mainImage) return;

    // Get the full-size image URLs from PHP
    const images = [
        <?php foreach ($all_images as $image_id) : ?>
            "<?php echo wp_get_attachment_url($image_id); ?>",
        <?php endforeach; ?>
    ];

    let currentIndex = 0;

    // Function to update main image
    function updateMainImage(index) {
        if (images[index]) {
            // Use full-size image URL
            mainImage.src = images[index];

            // Update active thumbnail
            thumbnails.forEach((thumb, i) => {
                thumb.classList.remove('thumbnail-active', 'border-primary');
                thumb.classList.add('border-gray-200');
                
                if (i === index) {
                    thumb.classList.add('thumbnail-active', 'border-primary');
                    thumb.classList.remove('border-gray-200');
                }
            });

            currentIndex = index;
        }
    }

    // Set up thumbnail click handlers
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            updateMainImage(index);
        });
    });

    // Set up prev/next buttons
    if (prevButton && nextButton && images.length > 1) {
        prevButton.addEventListener('click', function() {
            const newIndex = (currentIndex - 1 + images.length) % images.length;
            updateMainImage(newIndex);
        });

        nextButton.addEventListener('click', function() {
            const newIndex = (currentIndex + 1) % images.length;
            updateMainImage(newIndex);
        });
    }

    // Quantity buttons functionality
    const quantityInput = document.getElementById('quantity');
    const quantityButtons = document.querySelectorAll('.quantity-btn');

    quantityButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.getAttribute('data-action');
            const currentValue = parseInt(quantityInput.value);
            const min = parseInt(quantityInput.getAttribute('min'));
            const max = parseInt(quantityInput.getAttribute('max'));

            if (action === 'increase' && currentValue < max) {
                quantityInput.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > min) {
                quantityInput.value = currentValue - 1;
            }
        });
    });
});
</script>