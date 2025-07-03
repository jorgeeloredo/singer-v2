<?php
/**
 * Single Product Template - Perfect copy of app/views/product/index.php
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main class="min-h-screen">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        
        <?php
        // Include our custom single product content
        wc_get_template_part('content', 'single-product');
        ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>