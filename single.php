<?php
/**
 * Template pour les articles individuels (single.php)
 */

get_header(); ?>

<div class="px-4 py-8 site-container">
    <div class="max-w-4xl mx-auto">
        
        <?php while (have_posts()) : the_post(); ?>
            <article class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                
                <!-- Navigation de retour -->
                <div class="mb-6">
                    <a href="javascript:history.back()" class="flex items-center text-sm text-gray-600 hover:text-primary">
                        <i class="mr-1 text-xs fas fa-chevron-left"></i> <?php echo __('Retour', 'singer-v2'); ?>
                    </a>
                </div>

                <!-- Image mise en avant -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-8 overflow-hidden rounded-lg">
                        <?php the_post_thumbnail('large', array('class' => 'w-full h-auto object-cover')); ?>
                    </div>
                <?php endif; ?>

                <!-- En-tête de l'article -->
                <header class="mb-8">
                    <h1 class="mb-4 text-3xl font-normal text-gray-800"><?php the_title(); ?></h1>
                    
                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                        <time datetime="<?php echo get_the_date('c'); ?>" class="flex items-center">
                            <i class="mr-1 fas fa-calendar"></i>
                            <?php echo get_the_date(); ?>
                        </time>
                        
                        <?php if (has_category()) : ?>
                            <div class="flex items-center">
                                <i class="mr-1 fas fa-folder"></i>
                                <?php the_category(', '); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (has_tag()) : ?>
                            <div class="flex items-center">
                                <i class="mr-1 fas fa-tags"></i>
                                <?php the_tags('', ', '); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- Contenu de l'article -->
                <div class="prose prose-lg max-w-none text-sm font-extralight pagecontent">
                    <?php the_content(); ?>
                </div>

                <!-- Pagination des pages multiples -->
                <?php
                wp_link_pages(array(
                    'before' => '<div class="page-links mt-8 pt-4 border-t border-gray-200"><span class="page-links-title text-sm font-medium text-gray-700">' . __('Pages:', 'singer-v2') . '</span>',
                    'after'  => '</div>',
                    'link_before' => '<span class="page-number">',
                    'link_after'  => '</span>',
                ));
                ?>

                <!-- Tags -->
                <?php if (has_tag()) : ?>
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="mb-3 text-sm font-medium text-gray-700"><?php echo __('Tags:', 'singer-v2'); ?></h4>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $tags = get_the_tags();
                            if ($tags) {
                                foreach ($tags as $tag) {
                                    echo '<a href="' . get_tag_link($tag->term_id) . '" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-primary hover:text-white transition">' . esc_html($tag->name) . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Navigation entre articles -->
                <nav class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <?php
                            $prev_post = get_previous_post();
                            if ($prev_post) :
                            ?>
                                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="flex items-center text-sm text-gray-600 hover:text-primary group">
                                    <i class="mr-2 fas fa-chevron-left group-hover:-translate-x-1 transition-transform"></i>
                                    <div>
                                        <div class="text-xs text-gray-500"><?php echo __('Article précédent', 'singer-v2'); ?></div>
                                        <div class="font-medium"><?php echo wp_trim_words($prev_post->post_title, 6); ?></div>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex-1 text-right">
                            <?php
                            $next_post = get_next_post();
                            if ($next_post) :
                            ?>
                                <a href="<?php echo get_permalink($next_post->ID); ?>" class="flex items-center justify-end text-sm text-gray-600 hover:text-primary group">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500"><?php echo __('Article suivant', 'singer-v2'); ?></div>
                                        <div class="font-medium"><?php echo wp_trim_words($next_post->post_title, 6); ?></div>
                                    </div>
                                    <i class="ml-2 fas fa-chevron-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>

                <!-- Partage social -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="mb-4 text-sm font-medium text-gray-700"><?php echo __('Partager cet article:', 'singer-v2'); ?></h4>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="flex items-center justify-center w-10 h-10 bg-blue-400 text-white rounded-full hover:bg-blue-500 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="flex items-center justify-center w-10 h-10 bg-red-600 text-white rounded-full hover:bg-red-700 transition">
                            <i class="fab fa-pinterest"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" class="flex items-center justify-center w-10 h-10 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>

            </article>

            <!-- Articles liés -->
            <?php
            $related_posts = get_posts(array(
                'category__in' => wp_get_post_categories($post->ID),
                'numberposts' => 3,
                'post__not_in' => array($post->ID),
                'orderby' => 'rand'
            ));

            if ($related_posts) :
            ?>
                <section class="mt-12">
                    <h3 class="mb-6 text-2xl font-normal text-gray-800"><?php echo __('Articles similaires', 'singer-v2'); ?></h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <?php foreach ($related_posts as $related_post) : 
                            setup_postdata($related_post);
                            $featured_image = get_the_post_thumbnail_url($related_post->ID, 'medium');
                            if (!$featured_image) {
                                $featured_image = get_template_directory_uri() . '/assets/images/tuto-default.jpg';
                            }
                        ?>
                            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
                                <a href="<?php echo get_permalink($related_post->ID); ?>">
                                    <img src="<?php echo $featured_image; ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="object-cover w-full h-48" />
                                </a>
                                <div class="p-4">
                                    <h4 class="mb-2 text-sm font-medium text-gray-800">
                                        <a href="<?php echo get_permalink($related_post->ID); ?>" class="hover:text-primary">
                                            <?php echo get_the_title(); ?>
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-600"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                    <div class="mt-3">
                                        <a href="<?php echo get_permalink($related_post->ID); ?>" class="text-xs singer-red-text hover:underline">
                                            <?php echo __('Lire la suite', 'singer-v2'); ?> →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

            <!-- Commentaires -->
            <?php
            if (comments_open() || get_comments_number()) :
                echo '<div class="mt-12">';
                comments_template();
                echo '</div>';
            endif;
            ?>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer();