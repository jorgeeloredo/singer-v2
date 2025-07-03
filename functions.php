<?php
/**
 * Singer V2 Theme Functions
 * Adaptations pour WordPress depuis le template personnalisé
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include navigation walkers
require_once get_template_directory() . '/inc/nav-walkers.php';

/**
 * Setup du thème
 */
function singer_theme_setup() {
    // Support des fonctionnalités WordPress
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 64,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Menu principal
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'singer-v2'),
        'footer' => __('Menu Footer', 'singer-v2'),
    ));
    
    // Tailles d'images personnalisées
    add_image_size('product-thumbnail', 140, 140, true);
    add_image_size('product-medium', 400, 400, true);
    add_image_size('product-large', 800, 800, true);
    add_image_size('category-thumb', 300, 300, true);
    
    // Support des extraits pour les pages
    add_post_type_support('page', 'excerpt');
    
    // Longueur des extraits
    add_filter('excerpt_length', function() { return 30; });
}
add_action('after_setup_theme', 'singer_theme_setup');

/**
 * Enqueue des scripts et styles
 */
function singer_theme_scripts() {
    // Tailwind CSS
    wp_enqueue_script('tailwind-js', 'https://cdn.tailwindcss.com', array(), '3.0', false);
    
    // Configuration Tailwind
    wp_add_inline_script('tailwind-js', "
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': 'var(--color-primary)',
                        'primary-hover': 'var(--color-primary-hover)',
                        'secondary': 'var(--color-secondary)',
                        'secondary-light': 'var(--color-secondary-light)',
                        'price': 'var(--color-price)',
                    }
                }
            }
        }
    ");
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
    
    // Style principal du thème
    wp_enqueue_style('singer-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Script principal du thème
    wp_enqueue_script('singer-script', get_template_directory_uri() . '/js/theme.js', array('jquery'), '1.0.0', true);
    
    // Localisation pour les scripts
    wp_localize_script('singer-script', 'singer_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('singer_nonce'),
        'text' => array(
            'loading' => __('Chargement...', 'singer-v2'),
            'error' => __('Une erreur est survenue', 'singer-v2'),
        )
    ));
}
add_action('wp_enqueue_scripts', 'singer_theme_scripts');

/**
 * Fonction de traduction __ adaptée pour WordPress
 */
function singer_translate($key, $replacements = array()) {
    $translation = __($key, 'singer-v2');
    
    // Appliquer les remplacements
    if (!empty($replacements)) {
        foreach ($replacements as $search => $replace) {
            $translation = str_replace(':' . $search, $replace, $translation);
        }
    }
    
    return $translation;
}

/**
 * Configuration des couleurs depuis les options du thème
 */
function singer_get_color($key) {
    $colors = array(
        'primary' => get_theme_mod('primary_color', '#CC0017'),
        'secondary' => get_theme_mod('secondary_color', '#0077BE'),
        'secondary_light' => get_theme_mod('secondary_light_color', '#F5F9FC'),
        'price' => get_theme_mod('price_color', '#CC0017'),
    );
    
    return isset($colors[$key]) ? $colors[$key] : '';
}

/**
 * Customizer pour les couleurs
 */
function singer_customize_register($wp_customize) {
    // Section couleurs
    $wp_customize->add_section('singer_colors', array(
        'title' => __('Couleurs Singer', 'singer-v2'),
        'priority' => 30,
    ));
    
    // Couleur primaire
    $wp_customize->add_setting('primary_color', array(
        'default' => '#CC0017',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label' => __('Couleur Primaire', 'singer-v2'),
        'section' => 'singer_colors',
    )));
    
    // Couleur secondaire
    $wp_customize->add_setting('secondary_color', array(
        'default' => '#0077BE',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'label' => __('Couleur Secondaire', 'singer-v2'),
        'section' => 'singer_colors',
    )));
    
    // Couleur secondaire claire
    $wp_customize->add_setting('secondary_light_color', array(
        'default' => '#F5F9FC',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_light_color', array(
        'label' => __('Couleur Secondaire Claire', 'singer-v2'),
        'section' => 'singer_colors',
    )));
    
    // Couleur prix
    $wp_customize->add_setting('price_color', array(
        'default' => '#CC0017',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'price_color', array(
        'label' => __('Couleur Prix', 'singer-v2'),
        'section' => 'singer_colors',
    )));
}
add_action('customize_register', 'singer_customize_register');

/**
 * Output des variables CSS personnalisées
 */
function singer_custom_css_vars() {
    $primary = singer_get_color('primary');
    $secondary = singer_get_color('secondary');
    $secondary_light = singer_get_color('secondary_light');
    $price = singer_get_color('price');
    
    echo '<style>
        :root {
            --color-primary: ' . $primary . ';
            --color-primary-hover: ' . singer_adjust_brightness($primary, -20) . ';
            --color-secondary: ' . $secondary . ';
            --color-secondary-light: ' . $secondary_light . ';
            --color-price: ' . $price . ';
        }
    </style>';
}
add_action('wp_head', 'singer_custom_css_vars');

/**
 * Ajuster la luminosité d'une couleur hex
 */
function singer_adjust_brightness($hex, $steps) {
    $hex = ltrim($hex, '#');
    
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
}

/**
 * Widget areas
 */
function singer_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar Principal', 'singer-v2'),
        'id' => 'sidebar-1',
        'description' => __('Widgets qui apparaissent dans la sidebar principale.', 'singer-v2'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 1', 'singer-v2'),
        'id' => 'footer-1',
        'description' => __('Première colonne du footer.', 'singer-v2'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title font-semibold text-gray-800 mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 2', 'singer-v2'),
        'id' => 'footer-2',
        'description' => __('Deuxième colonne du footer.', 'singer-v2'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title font-semibold text-gray-800 mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 3', 'singer-v2'),
        'id' => 'footer-3',
        'description' => __('Troisième colonne du footer.', 'singer-v2'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title font-semibold text-gray-800 mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 4', 'singer-v2'),
        'id' => 'footer-4',
        'description' => __('Quatrième colonne du footer.', 'singer-v2'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title font-semibold text-gray-800 mb-4">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'singer_widgets_init');

/**
 * Récupérer le nombre d'articles dans le panier (pour WooCommerce)
 */
function singer_get_cart_count() {
    if (function_exists('WC') && WC()->cart) {
        return WC()->cart->get_cart_contents_count();
    }
    return 0;
}

/**
 * Support des métas personnalisées pour les produits
 */
function singer_add_product_meta_boxes() {
    if (post_type_exists('product')) {
        add_meta_box(
            'singer_product_details',
            __('Détails Produit Singer', 'singer-v2'),
            'singer_product_details_callback',
            'product',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'singer_add_product_meta_boxes');

function singer_product_details_callback($post) {
    wp_nonce_field('singer_product_details', 'singer_product_details_nonce');
    
    $level = get_post_meta($post->ID, '_singer_level', true);
    $sku = get_post_meta($post->ID, '_singer_sku', true);
    $gtin = get_post_meta($post->ID, '_singer_gtin', true);
    $eco_part = get_post_meta($post->ID, '_singer_eco_part', true);
    
    echo '<table class="form-table">';
    echo '<tr><th><label for="singer_level">' . __('Niveau', 'singer-v2') . '</label></th>';
    echo '<td><select name="singer_level" id="singer_level">';
    echo '<option value="debutant"' . selected($level, 'debutant', false) . '>' . __('Débutant', 'singer-v2') . '</option>';
    echo '<option value="intermediaire"' . selected($level, 'intermediaire', false) . '>' . __('Intermédiaire', 'singer-v2') . '</option>';
    echo '<option value="expert"' . selected($level, 'expert', false) . '>' . __('Expert', 'singer-v2') . '</option>';
    echo '</select></td></tr>';
    
    echo '<tr><th><label for="singer_sku">' . __('Référence SKU', 'singer-v2') . '</label></th>';
    echo '<td><input type="text" name="singer_sku" id="singer_sku" value="' . esc_attr($sku) . '" /></td></tr>';
    
    echo '<tr><th><label for="singer_gtin">' . __('GTIN/EAN', 'singer-v2') . '</label></th>';
    echo '<td><input type="text" name="singer_gtin" id="singer_gtin" value="' . esc_attr($gtin) . '" /></td></tr>';
    
    echo '<tr><th><label for="singer_eco_part">' . __('Éco-participation (€)', 'singer-v2') . '</label></th>';
    echo '<td><input type="number" step="0.01" name="singer_eco_part" id="singer_eco_part" value="' . esc_attr($eco_part) . '" /></td></tr>';
    echo '</table>';
}

function singer_save_product_details($post_id) {
    if (!isset($_POST['singer_product_details_nonce']) || !wp_verify_nonce($_POST['singer_product_details_nonce'], 'singer_product_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['singer_level'])) {
        update_post_meta($post_id, '_singer_level', sanitize_text_field($_POST['singer_level']));
    }
    if (isset($_POST['singer_sku'])) {
        update_post_meta($post_id, '_singer_sku', sanitize_text_field($_POST['singer_sku']));
    }
    if (isset($_POST['singer_gtin'])) {
        update_post_meta($post_id, '_singer_gtin', sanitize_text_field($_POST['singer_gtin']));
    }
    if (isset($_POST['singer_eco_part'])) {
        update_post_meta($post_id, '_singer_eco_part', floatval($_POST['singer_eco_part']));
    }
}
add_action('save_post', 'singer_save_product_details');

/**
 * Chargement du domaine de traduction
 */
function singer_load_textdomain() {
    load_theme_textdomain('singer-v2', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'singer_load_textdomain');

/**
 * Ajouter les sous-menus mobiles au footer
 */
function singer_add_mobile_submenus() {
    if (wp_is_mobile()) {
        singer_mobile_submenus();
    }
}
add_action('wp_footer', 'singer_add_mobile_submenus');

/**
 * Nettoyage du head WordPress
 */
function singer_cleanup_head() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
}
add_action('init', 'singer_cleanup_head');

/**
 * Optimisations des images
 */
function singer_image_optimization() {
    // Lazy loading natif pour les images
    add_filter('wp_img_tag_add_loading_attr', function($value, $image, $context) {
        if ($context === 'the_content') {
            return 'lazy';
        }
        return $value;
    }, 10, 3);
}
add_action('init', 'singer_image_optimization');

/**
 * Remove WooCommerce default wrappers that add containers
 */
function singer_remove_woocommerce_wrappers() {
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}
add_action('after_setup_theme', 'singer_remove_woocommerce_wrappers');

/**
 * Remove WooCommerce page title on shop page (we handle it in our template)
 */
add_filter('woocommerce_show_page_title', '__return_false');

/**
 * Force use of our custom templates for WooCommerce pages
 */
function singer_woocommerce_template_redirect() {
    if (is_shop()) {
        // Force our custom shop template
        include(get_template_directory() . '/page-shop.php');
        exit;
    }
    
    if (is_product_category() || is_product_tag()) {
        // Force our custom category template
        include(get_template_directory() . '/page-category.php');
        exit;
    }
}
add_action('template_redirect', 'singer_woocommerce_template_redirect', 5);