<?php
/**
 * Product Category Template - Direct styling like repository
 */

defined('ABSPATH') || exit;

get_header(); 

// Use the same direct content as archive-product.php
include(locate_template('woocommerce/archive-product.php'));

get_footer();