document.addEventListener('DOMContentLoaded', function () {
    var bannerClose = document.getElementById('banner-close');
    var banner = document.getElementById('site-banner');

    if (bannerClose && banner) {
        bannerClose.addEventListener('click', function () {
            banner.classList.add('banner--hidden');
        });
    }

    var navToggle = document.getElementById('nav-toggle');
    var navMenu = document.getElementById('nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            var isOpen = navMenu.classList.toggle('navbar__menu--open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });

        document.addEventListener('click', function (e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('navbar__menu--open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var targetId = this.getAttribute('href');
            if (targetId === '#') return;

            var target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                if (navMenu) {
                    navMenu.classList.remove('navbar__menu--open');
                    navToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
});
