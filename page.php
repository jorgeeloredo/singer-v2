<?php
/**
 * Template pour les pages statiques (page.php)
 * Adapté du template app/views/page/show.php
 */

get_header(); ?>

<div class="px-4 py-8 site-container">
    <div class="max-w-6xl mx-auto">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Navigation de retour -->
            <div class="mb-6">
                <a href="javascript:history.back()" class="flex items-center text-sm text-gray-600 hover:text-primary">
                    <i class="mr-1 text-xs fas fa-chevron-left"></i> <?php echo __('Retour', 'singer-v2'); ?>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 text-sm font-extralight pagecontent">
                
                <!-- Titre de la page -->
                <?php if (!is_front_page()) : ?>
                    <h1 class="mb-6 text-3xl font-normal text-gray-800"><?php the_title(); ?></h1>
                <?php endif; ?>

                <!-- Image mise en avant si présente -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-8 overflow-hidden rounded-lg">
                        <?php the_post_thumbnail('large', array('class' => 'w-full h-auto object-cover')); ?>
                    </div>
                <?php endif; ?>

                <!-- Contenu de la page -->
                <div class="prose prose-lg max-w-none">
                    <?php
                    the_content();
                    
                    // Pagination pour le contenu multi-page
                    wp_link_pages(array(
                        'before' => '<div class="page-links mt-8 pt-4 border-t border-gray-200"><span class="page-links-title text-sm font-medium text-gray-700">' . __('Pages:', 'singer-v2') . '</span>',
                        'after'  => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    ));
                    ?>
                </div>

                <!-- Métadonnées de la page si nécessaire -->
                <?php if (get_edit_post_link()) : ?>
                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <a href="<?php echo get_edit_post_link(); ?>" class="text-xs text-gray-500 hover:text-primary">
                            <i class="mr-1 fas fa-edit"></i> <?php echo __('Modifier cette page', 'singer-v2'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Affichage des pages enfants si c'est une page parent -->
                <?php
                $child_pages = get_pages(array(
                    'parent' => get_the_ID(),
                    'sort_column' => 'menu_order',
                    'sort_order' => 'ASC'
                ));

                if ($child_pages) :
                ?>
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="mb-6 text-xl font-medium text-gray-800"><?php echo __('Pages liées', 'singer-v2'); ?></h3>
                        
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <?php foreach ($child_pages as $child_page) : 
                                $child_thumbnail = get_the_post_thumbnail_url($child_page->ID, 'medium');
                                if (!$child_thumbnail) {
                                    $child_thumbnail = get_template_directory_uri() . '/assets/images/page-default.jpg';
                                }
                            ?>
                                <div class="overflow-hidden bg-gray-50 border border-gray-200 rounded-lg">
                                    <a href="<?php echo get_permalink($child_page->ID); ?>" class="block group">
                                        <div class="h-32 overflow-hidden">
                                            <img src="<?php echo $child_thumbnail; ?>" alt="<?php echo esc_attr($child_page->post_title); ?>" class="object-cover w-full h-full transition duration-300 group-hover:scale-105">
                                        </div>
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-800 group-hover:text-primary">
                                                <?php echo esc_html($child_page->post_title); ?>
                                            </h4>
                                            <?php if ($child_page->post_excerpt) : ?>
                                                <p class="mt-2 text-xs text-gray-600">
                                                    <?php echo esc_html($child_page->post_excerpt); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Sidebar ou widgets spécifiques à la page -->
                <?php if (is_active_sidebar('sidebar-1') && !is_page_template('page-full-width.php')) : ?>
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <aside class="widget-area">
                            <?php dynamic_sidebar('sidebar-1'); ?>
                        </aside>
                    </div>
                <?php endif; ?>

            </div>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer();