</main>

    <!-- Footer (arrière-plans pleine largeur avec contenu contenu) -->
    <footer class="border-t border-gray-300">
        <!-- Section avantages du haut -->
        <div class="w-full py-8 border-b border-gray-200">
            <div class="grid grid-cols-1 gap-6 px-4 site-container md:grid-cols-2 lg:grid-cols-4">
                <div class="flex flex-col items-center text-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/footer1.png" alt="<?php echo __('Livraison offerte', 'singer-v2'); ?>" />
                    <p class="font-medium text-gray-800"><?php echo __('Livraison offerte', 'singer-v2'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo singer_translate('Dès :amount€ d\'achat', array('amount' => '300')); ?></p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/footer2.png" alt="<?php echo __('Paiement en plusieurs fois', 'singer-v2'); ?>" />
                    <p class="font-medium text-gray-800"><?php echo __('Paiement en 2, 3 ou 4x', 'singer-v2'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo __('Sans frais', 'singer-v2'); ?></p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/footer3.png" alt="<?php echo __('Retour gratuit', 'singer-v2'); ?>" />
                    <p class="font-medium text-gray-800"><?php echo __('Retour gratuit', 'singer-v2'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo singer_translate('Sous :days jours', array('days' => '30')); ?></p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/footer4.png" alt="<?php echo __('Besoin d\'aide ?', 'singer-v2'); ?>" />
                    <p class="font-medium text-gray-800"><?php echo __('Besoin d\'aide ?', 'singer-v2'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo __('Contactez-nous', 'singer-v2'); ?> <a href="<?php echo get_permalink(get_page_by_path('nous-contacter')); ?>" class="text-primary hover:underline"><?php echo __('ici', 'singer-v2'); ?></a></p>
                </div>
            </div>
        </div>

        <!-- Contenu principal du footer -->
        <div class="bg-[#fdf7f1] w-full py-8">
            <div class="grid grid-cols-1 gap-8 px-4 site-container md:grid-cols-4">
                <!-- Informations marque Singer -->
                <div class="mb-6 md:mb-0">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?> Logo" class="h-6 mb-4" />
                    <?php endif; ?>
                    
                    <p class="mb-4 text-sm italic text-gray-600">
                        <?php echo __('Singer, la marque de référence en matière de couture : 170 ans de savoir-faire et de notoriété', 'singer-v2'); ?>
                    </p>

                    <div class="flex mt-4 space-x-4">
                        <a href="#" class="text-gray-600 hover:text-primary">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-primary">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-primary">
                            <i class="fab fa-pinterest-p"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-primary">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Widgets Footer -->
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div><?php dynamic_sidebar('footer-1'); ?></div>
                <?php else : ?>
                    <!-- Liens généraux par défaut -->
                    <div>
                        <h3 class="mb-4 font-semibold text-gray-800"><?php echo __('Général', 'singer-v2'); ?></h3>
                        <ul class="space-y-2">
                            <li><a href="<?php echo get_permalink(get_page_by_path('politique-de-confidentialite')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Politique de confidentialité', 'singer-v2'); ?></a></li>
                            <li><a href="<?php echo get_permalink(get_page_by_path('conditions-generales-de-vente')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Conditions générales de vente', 'singer-v2'); ?></a></li>
                            <li><a href="<?php echo get_permalink(get_page_by_path('mentions-legales')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Mentions légales', 'singer-v2'); ?></a></li>
                            <li><a href="<?php echo get_permalink(get_page_by_path('la-marque')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('La marque', 'singer-v2'); ?></a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div><?php dynamic_sidebar('footer-2'); ?></div>
                <?php else : ?>
                    <!-- Liens machines par défaut -->
                    <div>
                        <h3 class="mb-4 font-semibold text-gray-800"><?php echo __('Machines', 'singer-v2'); ?></h3>
                        <ul class="space-y-2">
                            <?php if (function_exists('wc_get_product_category_link')) : ?>
                                <li><a href="<?php echo wc_get_product_category_link(get_term_by('slug', 'brodeuses', 'product_cat')); ?>" class="text-sm text-gray-600 hover:text-primary">Brodeuses</a></li>
                                <li><a href="<?php echo wc_get_product_category_link(get_term_by('slug', 'machines-electroniques', 'product_cat')); ?>" class="text-sm text-gray-600 hover:text-primary">Électroniques</a></li>
                                <li><a href="<?php echo wc_get_product_category_link(get_term_by('slug', 'machines-mecaniques', 'product_cat')); ?>" class="text-sm text-gray-600 hover:text-primary">Mécaniques</a></li>
                                <li><a href="<?php echo wc_get_product_category_link(get_term_by('slug', 'soin-du-linge', 'product_cat')); ?>" class="text-sm text-gray-600 hover:text-primary">Soin du linge</a></li>
                                <li><a href="<?php echo wc_get_product_category_link(get_term_by('slug', 'surjeteuses-recouvreuses', 'product_cat')); ?>" class="text-sm text-gray-600 hover:text-primary">Surjeteuses & Recouvreuses</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div><?php dynamic_sidebar('footer-3'); ?></div>
                <?php else : ?>
                    <!-- Aide & Newsletter par défaut -->
                    <div>
                        <h3 class="mb-4 font-semibold text-gray-800"><?php echo __('Aide à l\'achat', 'singer-v2'); ?></h3>
                        <ul class="mb-6 space-y-2">
                            <li><a href="<?php echo get_permalink(get_page_by_path('questions-frequentes')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Questions fréquentes', 'singer-v2'); ?></a></li>
                            <li><a href="<?php echo get_permalink(get_page_by_path('vos-avantages')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Vos avantages', 'singer-v2'); ?></a></li>
                            <li><a href="<?php echo get_permalink(get_page_by_path('tutos')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Tutos & conseils', 'singer-v2'); ?></a></li>
                            <li><a href="<?php echo get_permalink(get_page_by_path('actualites')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Nos actualités', 'singer-v2'); ?></a></li>
                            <li><a href="<?php echo get_permalink(get_page_by_path('nous-contacter')); ?>" class="text-sm text-gray-600 hover:text-primary"><?php echo __('Nous contacter', 'singer-v2'); ?></a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bouton scroll to top -->
        <div class="fixed z-30 bottom-6 right-6">
            <button id="scroll-to-top" class="flex items-center justify-center w-10 h-10 text-white shadow-lg bg-primary hidden">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>

        <!-- Footer copyright -->
        <div class="py-2 text-sm font-medium text-center text-white bg-primary">
            <div class="site-container"><?php echo singer_translate('© :year :brand · Mentions légales', array('year' => date('Y'), 'brand' => get_bloginfo('name'))); ?></div>
        </div>
    </footer>

    <?php wp_footer(); ?>

    <script>
        // Toggle menu mobile
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.remove('hidden');
        });

        document.getElementById('close-mobile-menu')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.add('hidden');
        });

        // Navigation sous-menu mobile
        const mainMenu = document.getElementById('mobile-main-menu');
        const backButtons = document.querySelectorAll('.back-to-main');
        const submenuTriggers = document.querySelectorAll('[data-submenu]');

        // Gérer l'ouverture des sous-menus
        submenuTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const submenuId = this.dataset.submenu;
                const targetSubmenu = document.getElementById(`submenu-${submenuId}`);

                if (targetSubmenu) {
                    // Cacher le menu principal
                    mainMenu.classList.add('hidden');
                    // Afficher le sous-menu
                    targetSubmenu.classList.remove('hidden');
                }
            });
        });

        // Gérer le clic sur le bouton retour
        backButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Trouver le sous-menu parent
                const submenu = this.closest('[id^="submenu-"]');

                if (submenu) {
                    // Cacher le sous-menu actuel
                    submenu.classList.add('hidden');
                    // Afficher le menu principal
                    mainMenu.classList.remove('hidden');
                }
            });
        });

        // Bouton scroll to top
        document.getElementById('scroll-to-top')?.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Afficher/cacher le bouton scroll to top selon la position de scroll
        window.addEventListener('scroll', function() {
            const scrollButton = document.getElementById('scroll-to-top');
            if (scrollButton) {
                if (window.scrollY > 300) {
                    scrollButton.classList.remove('hidden');
                } else {
                    scrollButton.classList.add('hidden');
                }
            }
        });
    </script>

    <script type="text/javascript">
        window._mfq = window._mfq || [];
        (function() {
            var mf = document.createElement("script");
            mf.type = "text/javascript"; mf.defer = true;
            mf.src = "//cdn.mouseflow.com/projects/439b3f19-b164-437e-af31-65bf791dd888.js";
            document.getElementsByTagName("head")[0].appendChild(mf);
        })();
    </script>
</body>

</html>