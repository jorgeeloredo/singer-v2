<?php
/**
 * Product Category Template - Uses the same layout as archive-product.php
 * Exact copy of app/views/product/list.php for categories
 */

defined('ABSPATH') || exit;

get_header(); 

// Include the same content as archive-product.php
include(locate_template('woocommerce/archive-product.php'));

get_footer();