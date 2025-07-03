<?php
/**
 * Template principal (index.php) - Page d'accueil et blog
 */

get_header(); ?>

<?php if (is_home() || is_front_page()) : ?>
    <!-- Page d'accueil -->
    
    <!-- Navigation par catégories -->
    <section class="py-12 mx-auto bg-secondary-light">
        <div class="px-4 site-container">
            <h2 class="mb-8 text-2xl font-normal text-center text-gray-800"><?php echo __('Nos catégories', 'singer-v2'); ?></h2>

            <div class="grid gap-4 mx-auto grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
                <?php
                // Afficher les catégories WooCommerce si disponibles
                if (function_exists('wc_get_product_categories')) :
                    $categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'number' => 6,
                        'parent' => 0
                    ));
                    
                    foreach ($categories as $category) :
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'category-thumb') : get_template_directory_uri() . '/assets/images/placeholder-category.jpg';
                ?>
                    <a href="<?php echo get_term_link($category); ?>" class="group">
                        <div class="mb-2 overflow-hidden bg-white border border-gray-200 rounded-lg aspect-square">
                            <img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr($category->name); ?>" class="object-cover w-full h-full transition duration-300 group-hover:scale-105">
                        </div>
                        <p class="text-sm font-medium text-center text-gray-800 group-hover:text-primary"><?php echo esc_html($category->name); ?></p>
                    </a>
                <?php 
                    endforeach;
                else :
                    // Catégories statiques de fallback
                    $static_categories = array(
                        array('name' => 'Brodeuses', 'image' => 'category-brodeuses.jpg'),
                        array('name' => 'Électroniques', 'image' => 'category-electroniques.jpg'),
                        array('name' => 'Mécaniques', 'image' => 'category-mecaniques.jpg'),
                        array('name' => 'Surjeteuses', 'image' => 'category-surjeteuses.jpg'),
                        array('name' => 'Accessoires', 'image' => 'category-accessoires.jpg'),
                        array('name' => 'Soin du linge', 'image' => 'category-soin.jpg'),
                    );
                    
                    foreach ($static_categories as $category) :
                ?>
                    <a href="#" class="group">
                        <div class="mb-2 overflow-hidden bg-white border border-gray-200 rounded-lg aspect-square">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/<?php echo $category['image']; ?>" alt="<?php echo esc_attr($category['name']); ?>" class="object-cover w-full h-full transition duration-300 group-hover:scale-105">
                        </div>
                        <p class="text-sm font-medium text-center text-gray-800 group-hover:text-primary"><?php echo esc_html($category['name']); ?></p>
                    </a>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- Produits vedettes -->
    <section class="py-12">
        <div class="px-4 site-container">
            <h2 class="mb-8 text-2xl font-normal text-gray-800"><?php echo __('Nos produits vedettes', 'singer-v2'); ?></h2>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <?php
                // Afficher les produits WooCommerce vedettes si disponibles
                if (function_exists('wc_get_products')) :
                    $featured_products = wc_get_products(array(
                        'featured' => true,
                        'limit' => 8,
                        'status' => 'publish'
                    ));
                    
                    foreach ($featured_products as $product) :
                        $product_image = wp_get_attachment_image_url($product->get_image_id(), 'product-medium');
                        if (!$product_image) {
                            $product_image = get_template_directory_uri() . '/assets/images/placeholder-product.jpg';
                        }
                ?>
                    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                        <a href="<?php echo get_permalink($product->get_id()); ?>" class="block">
                            <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
                                <img src="<?php echo $product_image; ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="absolute inset-0 object-contain w-full h-full p-4">
                                
                                <?php if ($product->is_on_sale()) : ?>
                                    <div class="absolute top-2 left-2">
                                        <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">
                                            <?php echo __('Promo', 'singer-v2'); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="p-4">
                                <h3 class="mb-2 text-sm font-medium text-gray-800 line-clamp-2"><?php echo esc_html($product->get_name()); ?></h3>
                                
                                <div class="flex items-baseline mb-2">
                                    <span class="text-lg font-semibold price-color"><?php echo $product->get_price_html(); ?></span>
                                </div>
                                
                                <?php if ($product->is_in_stock()) : ?>
                                    <span class="text-xs text-green-600"><?php echo __('En stock', 'singer-v2'); ?></span>
                                <?php else : ?>
                                    <span class="text-xs text-red-600"><?php echo __('Rupture de stock', 'singer-v2'); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php 
                    endforeach;
                else :
                    // Produits statiques de fallback
                    for ($i = 1; $i <= 8; $i++) :
                ?>
                    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                        <a href="#" class="block">
                            <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-<?php echo $i; ?>.jpg" alt="Produit <?php echo $i; ?>" class="absolute inset-0 object-contain w-full h-full p-4">
                            </div>
                            
                            <div class="p-4">
                                <h3 class="mb-2 text-sm font-medium text-gray-800">Machine à Coudre Singer <?php echo $i; ?></h3>
                                <div class="flex items-baseline mb-2">
                                    <span class="text-lg font-semibold price-color"><?php echo number_format(299 + ($i * 50), 2, ',', ' '); ?> €</span>
                                </div>
                                <span class="text-xs text-green-600"><?php echo __('En stock', 'singer-v2'); ?></span>
                            </div>
                        </a>
                    </div>
                <?php 
                    endfor;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- Section tutoriels -->
    <section class="py-12 bg-gray-50">
        <div class="px-4 site-container">
            <h2 class="mb-8 text-2xl font-normal text-gray-800"><?php echo __('Tutos & conseils', 'singer-v2'); ?></h2>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php
                // Récupérer les articles de blog récents
                $recent_posts = get_posts(array(
                    'numberposts' => 3,
                    'post_status' => 'publish'
                ));
                
                if ($recent_posts) :
                    foreach ($recent_posts as $post) :
                        setup_postdata($post);
                        $featured_image = get_the_post_thumbnail_url($post->ID, 'medium');
                        if (!$featured_image) {
                            $featured_image = get_template_directory_uri() . '/assets/images/tuto-default.jpg';
                        }
                ?>
                    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                        <img src="<?php echo $featured_image; ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="object-cover w-full h-48" />
                        <div class="p-4">
                            <h3 class="mb-2 text-lg font-medium text-gray-800"><?php echo get_the_title(); ?></h3>
                            <p class="mb-4 text-sm text-gray-700"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            <a href="<?php echo get_permalink(); ?>" class="flex items-center text-sm singer-red-text hover:underline">
                                <?php echo __('Découvrir le tutoriel', 'singer-v2'); ?>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php 
                    endforeach;
                    wp_reset_postdata();
                else :
                    // Tutoriels statiques de fallback
                    for ($i = 1; $i <= 3; $i++) :
                ?>
                    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tuto<?php echo $i; ?>.jpg" alt="Tutoriel <?php echo $i; ?>" class="object-cover w-full h-48" />
                        <div class="p-4">
                            <h3 class="mb-2 text-lg font-medium text-gray-800"><?php echo __('Tutoriel couture', 'singer-v2'); ?> <?php echo $i; ?></h3>
                            <p class="mb-4 text-sm text-gray-700"><?php echo __('Découvrez nos techniques de couture avec ce tutoriel pas à pas.', 'singer-v2'); ?></p>
                            <a href="#" class="flex items-center text-sm singer-red-text hover:underline">
                                <?php echo __('Découvrir le tutoriel', 'singer-v2'); ?>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endfor; endif; ?>
            </div>
        </div>
    </section>

<?php else : ?>
    <!-- Page de blog -->
    <div class="px-4 py-8 site-container">
        <div class="max-w-4xl mx-auto">
            
            <?php if (have_posts()) : ?>
                <h1 class="mb-8 text-3xl font-normal text-gray-800"><?php echo __('Blog', 'singer-v2'); ?></h1>
                
                <div class="space-y-8">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="w-full h-64 overflow-hidden">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <header class="mb-4">
                                    <h2 class="mb-2 text-xl font-medium text-gray-800">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="text-sm text-gray-500">
                                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                        <?php if (has_category()) : ?>
                                            <span class="mx-2">•</span>
                                            <?php the_category(', '); ?>
                                        <?php endif; ?>
                                    </div>
                                </header>
                                
                                <div class="mb-4 text-gray-700">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm singer-red-text hover:underline">
                                    <?php echo __('Lire la suite', 'singer-v2'); ?>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => '‹ ' . __('Précédent', 'singer-v2'),
                        'next_text' => __('Suivant', 'singer-v2') . ' ›',
                        'class' => 'pagination flex justify-center space-x-2'
                    ));
                    ?>
                </div>

            <?php else : ?>
                <div class="p-8 text-center bg-white border border-gray-200 rounded-lg">
                    <h1 class="mb-4 text-2xl font-normal text-gray-800"><?php echo __('Aucun article trouvé', 'singer-v2'); ?></h1>
                    <p class="text-gray-600"><?php echo __('Il n\'y a pas encore d\'articles publiés.', 'singer-v2'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php get_footer();