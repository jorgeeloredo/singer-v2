/**
 * Theme JavaScript for Singer V2
 * Adapté des fonctionnalités du template original
 */

document.addEventListener('DOMContentLoaded', function () {
  // Navigation mobile
  initMobileMenu();

  // Galerie d'images produit
  initProductGallery();

  // Bouton scroll to top
  initScrollToTop();

  // Améliorations générales
  initGeneralEnhancements();
});

/**
 * Initialisation du menu mobile
 */
function initMobileMenu() {
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  const closeMobileMenu = document.getElementById('close-mobile-menu');

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', function () {
      mobileMenu.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    });
  }

  if (closeMobileMenu && mobileMenu) {
    closeMobileMenu.addEventListener('click', function () {
      mobileMenu.classList.add('hidden');
      document.body.style.overflow = '';
    });
  }

  // Fermer le menu en cliquant à l'extérieur
  if (mobileMenu) {
    mobileMenu.addEventListener('click', function (e) {
      if (e.target === mobileMenu) {
        mobileMenu.classList.add('hidden');
        document.body.style.overflow = '';
      }
    });
  }

  // Navigation sous-menu mobile
  const mainMenu = document.getElementById('mobile-main-menu');
  const backButtons = document.querySelectorAll('.back-to-main');
  const submenuTriggers = document.querySelectorAll('[data-submenu]');

  // Gérer l'ouverture des sous-menus
  submenuTriggers.forEach((trigger) => {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      const submenuId = this.dataset.submenu;
      const targetSubmenu = document.getElementById(`submenu-${submenuId}`);

      if (targetSubmenu && mainMenu) {
        // Cacher le menu principal
        mainMenu.classList.add('hidden');
        // Afficher le sous-menu
        targetSubmenu.classList.remove('hidden');
      }
    });
  });

  // Gérer le clic sur le bouton retour
  backButtons.forEach((button) => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      // Trouver le sous-menu parent
      const submenu = this.closest('[id^="submenu-"]');

      if (submenu && mainMenu) {
        // Cacher le sous-menu actuel
        submenu.classList.add('hidden');
        // Afficher le menu principal
        mainMenu.classList.remove('hidden');
      }
    });
  });
}

/**
 * Initialisation du bouton scroll to top
 */
function initScrollToTop() {
  const scrollButton = document.getElementById('scroll-to-top');

  if (!scrollButton) {
    return;
  }

  // Afficher/cacher le bouton selon la position de scroll
  function toggleScrollButton() {
    if (window.scrollY > 300) {
      scrollButton.classList.remove('hidden');
    } else {
      scrollButton.classList.add('hidden');
    }
  }

  // Écouter le scroll
  window.addEventListener('scroll', toggleScrollButton);

  // Action du clic
  scrollButton.addEventListener('click', function () {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  });

  // Vérification initiale
  toggleScrollButton();
}

/**
 * Améliorations générales
 */
function initGeneralEnhancements() {
  // Lazy loading pour les images
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            imageObserver.unobserve(img);
          }
        }
      });
    });

    document.querySelectorAll('img[data-src]').forEach((img) => {
      imageObserver.observe(img);
    });
  }

  // Amélioration des formulaires
  const forms = document.querySelectorAll('form');
  forms.forEach((form) => {
    const inputs = form.querySelectorAll('input, select, textarea');

    inputs.forEach((input) => {
      // Focus/blur effects
      input.addEventListener('focus', function () {
        this.parentElement.classList.add('focused');
      });

      input.addEventListener('blur', function () {
        this.parentElement.classList.remove('focused');
        if (this.value) {
          this.parentElement.classList.add('has-value');
        } else {
          this.parentElement.classList.remove('has-value');
        }
      });

      // Vérification initiale
      if (input.value) {
        input.parentElement.classList.add('has-value');
      }
    });
  });

  // Notifications toast (si utilisées)
  const notifications = document.querySelectorAll('.notification, .notice');
  notifications.forEach((notification) => {
    const closeBtn = notification.querySelector('.close, .notice-dismiss');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        notification.style.opacity = '0';
        setTimeout(() => {
          notification.remove();
        }, 300);
      });
    }

    // Auto-hide après 5 secondes
    setTimeout(() => {
      if (notification && notification.parentElement) {
        notification.style.opacity = '0';
        setTimeout(() => {
          notification.remove();
        }, 300);
      }
    }, 5000);
  });

  // Smooth scrolling pour les liens d'ancrage
  const anchorLinks = document.querySelectorAll('a[href^="#"]');
  anchorLinks.forEach((link) => {
    link.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        const headerHeight = document.querySelector('header').offsetHeight || 0;
        const targetPosition = targetElement.offsetTop - headerHeight - 20;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth',
        });
      }
    });
  });

  // Gestion des boutons de quantité (pour WooCommerce)
  const quantityInputs = document.querySelectorAll('input[type="number"].qty');
  quantityInputs.forEach((input) => {
    const wrapper = input.parentElement;

    // Créer les boutons + et - s'ils n'existent pas
    if (!wrapper.querySelector('.quantity-btn')) {
      const minusBtn = document.createElement('button');
      minusBtn.type = 'button';
      minusBtn.className = 'quantity-btn minus-btn px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-l';
      minusBtn.innerHTML = '-';

      const plusBtn = document.createElement('button');
      plusBtn.type = 'button';
      plusBtn.className = 'quantity-btn plus-btn px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-r';
      plusBtn.innerHTML = '+';

      wrapper.insertBefore(minusBtn, input);
      wrapper.appendChild(plusBtn);

      // Styles pour l'input
      input.className += ' text-center border-l-0 border-r-0 rounded-none';

      // Événements
      minusBtn.addEventListener('click', function () {
        const currentValue = parseInt(input.value) || 1;
        const minValue = parseInt(input.getAttribute('min')) || 1;
        if (currentValue > minValue) {
          input.value = currentValue - 1;
          input.dispatchEvent(new Event('change'));
        }
      });

      plusBtn.addEventListener('click', function () {
        const currentValue = parseInt(input.value) || 1;
        const maxValue = parseInt(input.getAttribute('max')) || 999;
        if (currentValue < maxValue) {
          input.value = currentValue + 1;
          input.dispatchEvent(new Event('change'));
        }
      });
    }
  });
}
