<?php
/**
 * Template pour l'affichage des produits dans la grille
 * Adapté du design Singer original
 */

defined('ABSPATH') || exit;

global $product;

// Vérifier que le produit est valide
if (empty($product) || !$product->is_visible()) {
    return;
}

$product_id = $product->get_id();
$product_image = wp_get_attachment_image_url($product->get_image_id(), 'product-medium');
if (!$product_image) {
    $product_image = wc_placeholder_img_src('product-medium');
}

// Récupérer le niveau du produit
$level = get_post_meta($product_id, '_singer_level', true);
?>

<div <?php wc_product_class('overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm product-item', $product); ?>>
    <a href="<?php the_permalink(); ?>" class="block group">
        
        <!-- Container d'image avec ratio fixe -->
        <div class="relative overflow-hidden bg-gray-100 product-image-bg" style="padding-bottom: 100%;">
            
            <!-- Image principale -->
            <img 
                src="<?php echo esc_url($product_image); ?>" 
                alt="<?php echo esc_attr($product->get_name()); ?>" 
                class="absolute inset-0 object-contain w-full h-full p-4 transition duration-300 group-hover:scale-105"
                loading="lazy"
            />
            
            <!-- Badge de niveau (coin supérieur gauche) -->
            <?php if ($level) : ?>
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 text-xs text-white rounded green-badge">
                        <?php 
                        echo __('Niveau', 'singer-v2') . ' ';
                        switch ($level) {
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
            
            <!-- Badge de promotion (coin supérieur droit) -->
            <?php if ($product->is_on_sale()) : ?>
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">
                        <?php 
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                        if ($regular_price && $sale_price) {
                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                            echo '-' . $discount . '%';
                        } else {
                            echo __('Promo', 'singer-v2');
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Badge rupture de stock -->
            <?php if (!$product->is_in_stock()) : ?>
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <span class="px-3 py-1 text-sm text-white bg-gray-800 rounded">
                        <?php echo __('Rupture de stock', 'singer-v2'); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Informations produit -->
        <div class="p-4">
            
            <!-- Titre du produit -->
            <h3 class="mb-2 text-sm font-medium text-gray-800 line-clamp-2 group-hover:text-primary transition-colors duration-200">
                <?php echo esc_html($product->get_name()); ?>
            </h3>
            
            <!-- Prix -->
            <div class="flex items-baseline mb-3">
                <?php echo $product->get_price_html(); ?>
            </div>
            
            <!-- Référence SKU si disponible -->
            <?php 
            $sku = get_post_meta($product_id, '_singer_sku', true);
            if ($sku) : 
            ?>
                <div class="mb-2 text-xs text-gray-500">
                    <?php echo __('Réf.', 'singer-v2') . ' ' . esc_html($sku); ?>
                </div>
            <?php endif; ?>
            
            <!-- Statut stock -->
            <div class="mb-3">
                <?php if ($product->is_in_stock()) : ?>
                    <span class="text-xs text-green-600 flex items-center">
                        <i class="mr-1 fas fa-check-circle"></i>
                        <?php echo __('En stock', 'singer-v2'); ?>
                    </span>
                <?php else : ?>
                    <span class="text-xs text-red-600 flex items-center">
                        <i class="mr-1 fas fa-times-circle"></i>
                        <?php echo __('Rupture de stock', 'singer-v2'); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- Note/évaluation si disponible -->
            <?php if (wc_review_ratings_enabled()) : ?>
                <div class="mb-3">
                    <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                </div>
            <?php endif; ?>
            
        </div>
    </a>
    
    <!-- Bouton d'ajout au panier (en dehors du lien) -->
    <div class="px-4 pb-4">
        <?php if ($product->is_in_stock()) : ?>
            <?php
            $add_to_cart_url = $product->add_to_cart_url();
            $add_to_cart_text = $product->is_type('simple') ? __('Acheter', 'singer-v2') : __('Voir options', 'singer-v2');
            ?>
            <a href="<?php echo esc_url($add_to_cart_url); ?>" 
               class="w-full px-4 py-2 text-sm text-center text-white transition-colors duration-200 rounded-full singer-red hover:singer-red-hover block"
               data-product_id="<?php echo esc_attr($product_id); ?>"
               data-product_sku="<?php echo esc_attr($product->get_sku()); ?>">
                <?php echo esc_html($add_to_cart_text); ?>
            </a>
        <?php else : ?>
            <button disabled class="w-full px-4 py-2 text-sm text-white bg-gray-400 rounded-full cursor-not-allowed block">
                <?php echo __('Indisponible', 'singer-v2'); ?>
            </button>
        <?php endif; ?>
    </div>
    
    <!-- Informations supplémentaires (masquées par défaut, visibles au hover) -->
    <div class="absolute inset-x-0 bottom-0 p-4 transition-opacity duration-300 opacity-0 bg-white border-t border-gray-200 group-hover:opacity-100">
        
        <!-- Caractéristiques rapides -->
        <div class="space-y-1 text-xs text-gray-600">
            
            <?php
            // Afficher quelques caractéristiques du produit
            $features = get_post_meta($product_id, '_singer_features', true);
            if ($features && is_array($features)) {
                $quick_features = array_slice($features, 0, 3);
                foreach ($quick_features as $feature) {
                    echo '<div class="flex items-start">';
                    echo '<span class="flex-shrink-0 w-1 h-1 mt-1.5 mr-2 bg-gray-400 rounded-full"></span>';
                    echo '<span>' . esc_html($feature) . '</span>';
                    echo '</div>';
                }
            }
            ?>
            
            <!-- Éco-participation si applicable -->
            <?php 
            $eco_part = get_post_meta($product_id, '_singer_eco_part', true);
            if ($eco_part && $eco_part > 0) : 
            ?>
                <div class="pt-2 mt-2 text-xs text-gray-500 border-t border-gray-100">
                    <?php echo sprintf(__('dont éco-participation %.2f€', 'singer-v2'), $eco_part); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>