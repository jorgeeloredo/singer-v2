<?php
/**
 * Header template adapté du template Singer personnalisé
 */

// Récupérer le nombre d'articles dans le panier
$cartCount = singer_get_cart_count();

// Vérifier si l'utilisateur est connecté
$isLoggedIn = is_user_logged_in();
$user = $isLoggedIn ? wp_get_current_user() : null;

// URL actuelle pour le menu actif
$currentUrl = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    
    <?php wp_head(); ?>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-THJLCSNH');</script>
    <!-- End Google Tag Manager -->
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1714961376562218');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1714961376562218&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
</head>

<body <?php body_class('bg-white'); ?>>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-THJLCSNH"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Barre promotionnelle non-fixe en haut -->
    <div class="bg-primary text-white text-center text-sm font-medium py-1.5">
        <div class="site-container"><?php echo singer_translate('Livraison offerte à partir de :amount€ d\'achat !', array('amount' => '300')); ?></div>
    </div>

    <!-- Header sticky/fixe qui s'ajuste au scroll -->
    <header class="sticky top-0 z-40 w-full bg-white shadow-md">
        <div class="site-container flex items-center justify-between px-4 h-[50px] lg:h-[64px]">
            <!-- Logo -->
            <a href="<?php echo home_url(); ?>" class="flex-shrink-0">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?> Logo" class="h-6 lg:h-8" />
                <?php endif; ?>
            </a>

            <!-- Navigation - cachée sur mobile -->
            <nav class="hidden space-x-6 text-sm font-light lg:flex">
                <!-- Nos machines -->
                <div class="desktop-menu-item">
                    <a href="<?php echo (function_exists('wc_get_page_permalink') && wc_get_page_permalink('shop')) ? wc_get_page_permalink('shop') : home_url('/products'); ?>" class="font-normal text-gray-800 hover:text-primary">
                        <?php echo __('Nos machines', 'singer-v2'); ?>
                    </a>
                    <div class="desktop-submenu">
                        <div class="submenu-grid">
                            <?php
                            // Helper function pour les liens de catégories
                            function get_category_link_safe($slug) {
                                if (function_exists('get_term_by') && function_exists('get_term_link')) {
                                    $term = get_term_by('slug', $slug, 'product_cat');
                                    if ($term && !is_wp_error($term)) {
                                        $link = get_term_link($term);
                                        if (!is_wp_error($link)) {
                                            return $link;
                                        }
                                    }
                                }
                                return home_url('/category/' . $slug);
                            }
                            ?>
                            <a href="<?php echo get_category_link_safe('machines-mecaniques'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/machine_mecanique.jpg" alt="<?php echo __('Mécaniques', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Mécaniques', 'singer-v2'); ?></span>
                            </a>
                            <a href="<?php echo get_category_link_safe('machines-electroniques'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/machine_electronique.jpg" alt="<?php echo __('Électroniques', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Électroniques', 'singer-v2'); ?></span>
                            </a>
                            <a href="<?php echo get_category_link_safe('surjeteuses-recouvreuses'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/surjeteuse.jpg" alt="<?php echo __('Surjeteuses & Recouvreuses', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Surjeteuses & Recouvreuses', 'singer-v2'); ?></span>
                            </a>
                            <a href="<?php echo get_category_link_safe('brodeuses'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/brodeuse.jpg" alt="<?php echo __('Brodeuses', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Brodeuses', 'singer-v2'); ?></span>
                            </a>
                            <a href="<?php echo get_category_link_safe('accessoires'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/accessoires.jpg" alt="<?php echo __('Accessoires', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Accessoires', 'singer-v2'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Autres produits -->
                <div class="desktop-menu-item">
                    <a href="#" class="font-normal text-gray-800 hover:text-primary">
                        <?php echo __('Autres produits', 'singer-v2'); ?>
                    </a>
                    <div class="desktop-submenu">
                        <div class="submenu-grid">
                            <a href="<?php echo get_category_link_safe('soin-du-linge'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/fer_repasser.jpg" alt="<?php echo __('Soin du linge', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Soin du linge', 'singer-v2'); ?></span>
                            </a>
                            <a href="<?php echo get_category_link_safe('electromenager'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/electromenager.jpg" alt="<?php echo __('Électroménager', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Électroménager', 'singer-v2'); ?></span>
                            </a>
                            <a href="<?php echo get_category_link_safe('soin-du-sol'); ?>" class="submenu-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/aspirateur.jpg" alt="<?php echo __('Soin du sol', 'singer-v2'); ?>" class="submenu-image">
                                <span class="font-medium"><?php echo __('Soin du sol', 'singer-v2'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tutos & conseils -->
                <div class="desktop-menu-item">
                    <a href="<?php echo get_permalink(get_page_by_path('tutos')) ?: home_url('/tutos'); ?>" class="font-normal text-gray-800 hover:text-primary">
                        <?php echo __('Tutos & conseils', 'singer-v2'); ?>
                    </a>
                </div>

                <!-- La marque -->
                <div class="desktop-menu-item">
                    <a href="<?php echo get_permalink(get_page_by_path('la-marque')) ?: home_url('/la-marque'); ?>" class="font-normal text-gray-800 hover:text-primary">
                        <?php echo __('La marque', 'singer-v2'); ?>
                    </a>
                </div>

                <!-- Actualités -->
                <div class="desktop-menu-item">
                    <a href="<?php echo get_permalink(get_page_by_path('actualites')) ?: home_url('/actualites'); ?>" class="font-normal text-gray-800 hover:text-primary">
                        <?php echo __('Actualités', 'singer-v2'); ?>
                    </a>
                </div>
            </nav>

            <!-- Contrôles côté droit -->
            <div class="flex items-center">
                <!-- Barre de recherche commentée -->
                <!--
                <form action="<?php echo esc_url(home_url('/')); ?>" method="GET" class="flex items-center">
                    <input type="text" name="s" placeholder="<?php echo __('Rechercher', 'singer-v2'); ?>" class="hidden h-8 px-3 py-1 mr-2 text-sm border border-gray-300 rounded md:block custom-input" />
                    <button type="submit" class="flex items-center justify-center w-8 h-8 text-gray-600">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                -->
                
                <a href="<?php echo $isLoggedIn ? get_permalink(get_option('woocommerce_myaccount_page_id')) : wp_login_url(); ?>" class="flex items-center ml-4">
                    <i class="text-gray-600 fas fa-user"></i>
                </a>
                
                <?php if (function_exists('wc_get_cart_url')) : ?>
                <a href="<?php echo wc_get_cart_url(); ?>" class="flex items-center ml-4">
                    <i class="text-gray-600 fas fa-shopping-cart"></i>
                    <span class="ml-1 text-xs text-gray-600">(<?php echo $cartCount; ?>)</span>
                </a>
                <?php endif; ?>

                <!-- Bouton menu mobile -->
                <button class="flex items-center justify-center w-8 h-8 ml-4 text-gray-600 lg:hidden" id="mobile-menu-button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Menu mobile overlay -->
    <div id="mobile-menu" class="fixed inset-0 z-50 hidden bg-white lg:hidden">
        <div class="flex flex-col h-full">
            <!-- Header du menu mobile -->
            <div class="flex items-center justify-between px-4 h-[50px]">
                <a href="<?php echo home_url(); ?>" class="flex-shrink-0">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?> Logo" class="h-6" />
                    <?php endif; ?>
                </a>
                <button id="close-mobile-menu" class="flex items-center justify-center w-8 h-8 text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Corps du menu mobile -->
            <div class="flex-1 px-4 py-6 overflow-y-auto">
                <!-- Barre de recherche mobile -->
                <div class="mb-6">
                    <form action="<?php echo esc_url(home_url('/')); ?>" method="GET" class="flex">
                        <input type="text" name="s" placeholder="<?php echo __('Rechercher', 'singer-v2'); ?>" class="flex-1 h-10 px-3 border border-gray-300 rounded-l custom-input" />
                        <button type="submit" class="flex items-center justify-center w-10 text-white rounded-r bg-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Navigation mobile -->
                <nav>
                    <div id="mobile-main-menu">
                        <!-- Nos machines (avec sous-menu) -->
                        <div class="border-b border-gray-200">
                            <div class="flex items-center justify-between py-4 cursor-pointer" data-submenu="0">
                                <span class="font-medium text-gray-800"><?php echo __('Nos machines', 'singer-v2'); ?></span>
                                <i class="text-gray-500 fas fa-chevron-right"></i>
                            </div>
                        </div>

                        <!-- Autres produits (avec sous-menu) -->
                        <div class="border-b border-gray-200">
                            <div class="flex items-center justify-between py-4 cursor-pointer" data-submenu="1">
                                <span class="font-medium text-gray-800"><?php echo __('Autres produits', 'singer-v2'); ?></span>
                                <i class="text-gray-500 fas fa-chevron-right"></i>
                            </div>
                        </div>

                        <!-- Tutos & conseils (élément simple) -->
                        <div class="border-b border-gray-200">
                            <a href="<?php echo get_permalink(get_page_by_path('tutos')) ?: home_url('/tutos'); ?>" class="flex items-center py-4">
                                <span class="font-medium text-gray-800"><?php echo __('Tutos & conseils', 'singer-v2'); ?></span>
                            </a>
                        </div>

                        <!-- La marque (élément simple) -->
                        <div class="border-b border-gray-200">
                            <a href="<?php echo get_permalink(get_page_by_path('la-marque')) ?: home_url('/la-marque'); ?>" class="flex items-center py-4">
                                <span class="font-medium text-gray-800"><?php echo __('La marque', 'singer-v2'); ?></span>
                            </a>
                        </div>

                        <!-- Actualités (élément simple) -->
                        <div class="border-b border-gray-200">
                            <a href="<?php echo get_permalink(get_page_by_path('actualites')) ?: home_url('/actualites'); ?>" class="flex items-center py-4">
                                <span class="font-medium text-gray-800"><?php echo __('Actualités', 'singer-v2'); ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- Sous-menu: Nos machines -->
                    <div id="submenu-0" class="hidden">
                        <div class="flex items-center py-4 mb-2 border-b border-gray-200">
                            <button class="mr-2 back-to-main">
                                <i class="text-gray-500 fas fa-chevron-left"></i>
                            </button>
                            <span class="font-medium text-gray-800"><?php echo __('Nos machines', 'singer-v2'); ?></span>
                        </div>

                        <a href="<?php echo get_category_link_safe('machines-mecaniques'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/machine_mecanique.jpg" alt="<?php echo __('Mécaniques', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Mécaniques', 'singer-v2'); ?></span>
                        </a>

                        <a href="<?php echo get_category_link_safe('machines-electroniques'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/machine_electronique.jpg" alt="<?php echo __('Électroniques', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Électroniques', 'singer-v2'); ?></span>
                        </a>

                        <a href="<?php echo get_category_link_safe('surjeteuses-recouvreuses'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/surjeteuse.jpg" alt="<?php echo __('Surjeteuses & Recouvreuses', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Surjeteuses & Recouvreuses', 'singer-v2'); ?></span>
                        </a>

                        <a href="<?php echo get_category_link_safe('brodeuses'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/brodeuse.jpg" alt="<?php echo __('Brodeuses', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Brodeuses', 'singer-v2'); ?></span>
                        </a>

                        <a href="<?php echo get_category_link_safe('accessoires'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/accessoires.jpg" alt="<?php echo __('Accessoires', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Accessoires', 'singer-v2'); ?></span>
                        </a>
                    </div>

                    <!-- Sous-menu: Autres produits -->
                    <div id="submenu-1" class="hidden">
                        <div class="flex items-center py-4 mb-2 border-b border-gray-200">
                            <button class="mr-2 back-to-main">
                                <i class="text-gray-500 fas fa-chevron-left"></i>
                            </button>
                            <span class="font-medium text-gray-800"><?php echo __('Autres produits', 'singer-v2'); ?></span>
                        </div>

                        <a href="<?php echo get_category_link_safe('soin-du-linge'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/fer_repasser.jpg" alt="<?php echo __('Soin du linge', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Soin du linge', 'singer-v2'); ?></span>
                        </a>

                        <a href="<?php echo get_category_link_safe('electromenager'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/electromenager.jpg" alt="<?php echo __('Électroménager', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Électroménager', 'singer-v2'); ?></span>
                        </a>

                        <a href="<?php echo get_category_link_safe('soin-du-sol'); ?>" class="flex items-center py-3">
                            <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/products/aspirateur.jpg" alt="<?php echo __('Soin du sol', 'singer-v2'); ?>" class="object-contain w-full h-full bg-gray-50">
                            </div>
                            <span class="text-gray-800"><?php echo __('Soin du sol', 'singer-v2'); ?></span>
                        </a>
                    </div>
                </nav>

                <!-- Liens utilisateur mobile -->
                <div class="mt-8 space-y-4">
                    <a href="<?php echo $isLoggedIn ? get_permalink(get_option('woocommerce_myaccount_page_id')) : wp_login_url(); ?>" class="flex items-center text-gray-800">
                        <i class="w-8 mr-2 text-center fas fa-user"></i>
                        <span><?php echo $isLoggedIn ? __('Mon compte', 'singer-v2') : __('Connexion', 'singer-v2'); ?></span>
                    </a>
                    
                    <?php if (function_exists('wc_get_cart_url')) : ?>
                    <a href="<?php echo wc_get_cart_url(); ?>" class="flex items-center text-gray-800">
                        <i class="w-8 mr-2 text-center fas fa-shopping-cart"></i>
                        <span><?php echo __('Panier', 'singer-v2'); ?> (<?php echo $cartCount; ?>)</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($isLoggedIn) : ?>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="flex items-center text-gray-800">
                        <i class="w-8 mr-2 text-center fas fa-sign-out-alt"></i>
                        <span><?php echo __('Déconnexion', 'singer-v2'); ?></span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <main class="min-h-screen">