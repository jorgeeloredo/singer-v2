<?php
/**
 * Navigation Walkers pour le thème Singer V2
 * Adaptés pour reproduire la structure de navigation du template original
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Walker pour le menu principal desktop
 */
class Singer_Nav_Walker extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class=\"desktop-submenu\">\n$indent\t<div class=\"submenu-grid\">\n";
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent\t</div>\n$indent</div>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        if ($depth == 0) {
            // Menu principal
            $output .= $indent . '<div class="desktop-menu-item">';
            
            $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
            $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
            $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
            $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
            
            // Vérifier si l'URL actuelle correspond à cet élément
            $current_url = $_SERVER['REQUEST_URI'];
            $is_current = (strpos($current_url, $item->url) === 0 && $item->url !== '/') || ($item->url === '/' && $current_url === '/');
            $current_class = $is_current ? ' text-primary' : '';
            
            $item_output = $args->before ?? '';
            $item_output .= '<a' . $attributes . ' class="font-normal text-gray-800 hover:text-primary' . $current_class . '">';
            $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
            $item_output .= '</a>';
            $item_output .= $args->after ?? '';
            
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        } else {
            // Sous-menu
            $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
            $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
            $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
            $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
            
            // Image du sous-menu depuis un champ personnalisé
            $submenu_image = get_post_meta($item->ID, '_menu_item_image', true);
            if (!$submenu_image) {
                $submenu_image = get_template_directory_uri() . '/assets/images/menu-default.jpg';
            }
            
            $item_output = $indent . '<a' . $attributes . ' class="submenu-item">';
            $item_output .= '<img src="' . esc_url($submenu_image) . '" alt="' . esc_attr($item->title) . '" class="submenu-image">';
            $item_output .= '<span class="font-medium">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
            $item_output .= '</a>';
            
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = array()) {
        if ($depth == 0) {
            $output .= "</div>\n";
        }
    }
}

/**
 * Walker pour le menu mobile
 */
class Singer_Mobile_Nav_Walker extends Walker_Nav_Menu {
    
    private $submenu_counter = 0;
    
    function start_lvl(&$output, $depth = 0, $args = array()) {
        // Les sous-menus mobiles sont gérés différemment
        // Ils ne sont pas imbriqués dans le HTML mais créés séparément
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        // Pas d'action nécessaire
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        if ($depth == 0) {
            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            
            // Vérifier si cet élément a des enfants
            $has_children = in_array('menu-item-has-children', $classes);
            
            if ($has_children) {
                // Élément avec sous-menu
                $output .= '<div class="border-b border-gray-200">';
                $output .= '<div class="flex items-center justify-between py-4 cursor-pointer" data-submenu="' . $this->submenu_counter . '">';
                $output .= '<span class="font-medium text-gray-800">' . esc_html($item->title) . '</span>';
                $output .= '<i class="text-gray-500 fas fa-chevron-right"></i>';
                $output .= '</div>';
                $output .= '</div>';
                
                $this->submenu_counter++;
            } else {
                // Élément simple
                $attributes = ! empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
                
                $output .= '<div class="border-b border-gray-200">';
                $output .= '<a' . $attributes . ' class="flex items-center py-4">';
                $output .= '<span class="font-medium text-gray-800">' . esc_html($item->title) . '</span>';
                $output .= '</a>';
                $output .= '</div>';
            }
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = array()) {
        // Pas d'action nécessaire
    }
}

/**
 * Fonction pour afficher les sous-menus mobiles
 */
function singer_mobile_submenus() {
    $menu_name = 'primary';
    $locations = get_nav_menu_locations();
    
    if (!isset($locations[$menu_name])) {
        return;
    }
    
    $menu = wp_get_nav_menu_object($locations[$menu_name]);
    if (!$menu) {
        return;
    }
    
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    if (!$menu_items) {
        return;
    }
    
    $menu_tree = array();
    $submenu_counter = 0;
    
    // Organiser les éléments de menu en arbre
    foreach ($menu_items as $item) {
        if ($item->menu_item_parent == 0) {
            $menu_tree[$item->ID] = array(
                'item' => $item,
                'children' => array()
            );
        } else {
            if (isset($menu_tree[$item->menu_item_parent])) {
                $menu_tree[$item->menu_item_parent]['children'][] = $item;
            }
        }
    }
    
    // Générer les sous-menus mobiles
    foreach ($menu_tree as $parent_id => $parent_data) {
        if (!empty($parent_data['children'])) {
            echo '<div id="submenu-' . $submenu_counter . '" class="hidden">';
            echo '<div class="flex items-center py-4 mb-2 border-b border-gray-200">';
            echo '<button class="mr-2 back-to-main">';
            echo '<i class="text-gray-500 fas fa-chevron-left"></i>';
            echo '</button>';
            echo '<span class="font-medium text-gray-800">' . esc_html($parent_data['item']->title) . '</span>';
            echo '</div>';
            
            foreach ($parent_data['children'] as $child) {
                $submenu_image = get_post_meta($child->ID, '_menu_item_image', true);
                if (!$submenu_image) {
                    $submenu_image = get_template_directory_uri() . '/assets/images/menu-default.jpg';
                }
                
                echo '<a href="' . esc_url($child->url) . '" class="flex items-center py-3">';
                echo '<div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">';
                echo '<img src="' . esc_url($submenu_image) . '" alt="' . esc_attr($child->title) . '" class="object-contain w-full h-full bg-gray-50">';
                echo '</div>';
                echo '<span class="text-gray-800">' . esc_html($child->title) . '</span>';
                echo '</a>';
            }
            
            echo '</div>';
            $submenu_counter++;
        }
    }
}

/**
 * Menu par défaut si aucun menu n'est défini
 */
function singer_default_menu() {
    echo '<div class="flex space-x-6">';
    echo '<a href="' . home_url() . '" class="font-normal text-gray-800 hover:text-primary">' . __('Accueil', 'singer-v2') . '</a>';
    
    if (function_exists('wc_get_page_permalink')) {
        echo '<a href="' . wc_get_page_permalink('shop') . '" class="font-normal text-gray-800 hover:text-primary">' . __('Boutique', 'singer-v2') . '</a>';
    }
    
    echo '<a href="' . get_permalink(get_page_by_path('blog')) . '" class="font-normal text-gray-800 hover:text-primary">' . __('Blog', 'singer-v2') . '</a>';
    echo '<a href="' . get_permalink(get_page_by_path('nous-contacter')) . '" class="font-normal text-gray-800 hover:text-primary">' . __('Contact', 'singer-v2') . '</a>';
    echo '</div>';
}

/**
 * Menu mobile par défaut
 */
function singer_mobile_default_menu() {
    echo '<div class="border-b border-gray-200">';
    echo '<a href="' . home_url() . '" class="flex items-center py-4">';
    echo '<span class="font-medium text-gray-800">' . __('Accueil', 'singer-v2') . '</span>';
    echo '</a>';
    echo '</div>';
    
    if (function_exists('wc_get_page_permalink')) {
        echo '<div class="border-b border-gray-200">';
        echo '<a href="' . wc_get_page_permalink('shop') . '" class="flex items-center py-4">';
        echo '<span class="font-medium text-gray-800">' . __('Boutique', 'singer-v2') . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '<div class="border-b border-gray-200">';
    echo '<a href="' . get_permalink(get_page_by_path('blog')) . '" class="flex items-center py-4">';
    echo '<span class="font-medium text-gray-800">' . __('Blog', 'singer-v2') . '</span>';
    echo '</a>';
    echo '</div>';
    
    echo '<div class="border-b border-gray-200">';
    echo '<a href="' . get_permalink(get_page_by_path('nous-contacter')) . '" class="flex items-center py-4">';
    echo '<span class="font-medium text-gray-800">' . __('Contact', 'singer-v2') . '</span>';
    echo '</a>';
    echo '</div>';
}