<?php
/**
 * Product Category Template - Direct styling like repository
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="px-4 py-8 site-container">
  <div class="mb-6">
    <h1 class="text-2xl font-normal text-gray-800">
      <?php single_cat_title(); ?>
    </h1>
    <?php 
    $term_description = term_description();
    if (!empty($term_description)) {
      echo '<p class="mt-2 text-sm text-gray-600">' . wp_kses_post($term_description) . '</p>';
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
              
              <!-- Product level badge -->
              <?php if ($product_level) : ?>
                <div class="absolute top-2 left-2">
                  <span class="px-2 py-1 text-xs text-white rounded green-badge">
                    <?php 
                    echo __('Niveau', 'singer-v2') . ' ';
                    switch ($product_level) {
                        case 'debutant':
                            echo __('Débutant', 'singer-v2');
                            break;
                        case 'intermediaire':
                            echo __('Intermédiaire', 'singer-v2');
                            break;
                        case 'expert':
                            echo __('Expert', 'singer-v2');
                            break;
                        default:
                            echo __('Débutant', 'singer-v2');
                    }
                    ?>
                  </span>
                </div>
              <?php endif; ?>
              
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
              
              <!-- Stock status -->
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

<?php get_footer(); ?>