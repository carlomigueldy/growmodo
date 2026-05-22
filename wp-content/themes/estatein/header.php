<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e('Skip to content', 'estatein'); ?></a>

<header class="site-header" role="banner">
    <div class="banner" id="site-banner">
        <div class="banner__inner">
            <p class="banner__text">
                <svg class="banner__icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="#FFD700"/>
                </svg>
                Discover Your Dream Property with Estatein.
                <a href="#" class="banner__link">Learn More</a>
            </p>
            <button class="banner__close" id="banner-close" aria-label="<?php esc_attr_e('Close banner', 'estatein'); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>

    <nav class="navbar" role="navigation" aria-label="<?php esc_attr_e('Primary navigation', 'estatein'); ?>">
        <div class="navbar__inner">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar__logo" aria-label="<?php esc_attr_e('Estatein - Home', 'estatein'); ?>">
                <svg class="navbar__logo-icon" width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                    <rect width="48" height="48" rx="12" fill="var(--color-accent)"/>
                    <path d="M14 34V18L24 12L34 18V34H28V26H20V34H14Z" fill="white"/>
                </svg>
                <span class="navbar__logo-text">Estatein</span>
            </a>

            <button class="navbar__toggle" id="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="<?php esc_attr_e('Toggle navigation', 'estatein'); ?>">
                <span class="navbar__toggle-bar"></span>
                <span class="navbar__toggle-bar"></span>
                <span class="navbar__toggle-bar"></span>
            </button>

            <div class="navbar__menu" id="nav-menu">
                <ul class="navbar__links">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="navbar__link navbar__link--active">Home</a></li>
                    <li><a href="#about" class="navbar__link">About Us</a></li>
                    <li><a href="#properties" class="navbar__link">Properties</a></li>
                    <li><a href="#services" class="navbar__link">Services</a></li>
                </ul>
                <a href="#contact" class="btn btn--outline btn--sm navbar__cta">Contact Us</a>
            </div>
        </div>
    </nav>
</header>
