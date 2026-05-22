<?php
$heading     = estatein_get_field('hero_heading') ?: 'Discover Your Dream Property with Estatein';
$description = estatein_get_field('hero_description') ?: 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.';
$primary_text = estatein_get_field('hero_primary_cta_text') ?: 'Browse Properties';
$primary_url  = estatein_get_field('hero_primary_cta_url') ?: '#properties';
$secondary_text = estatein_get_field('hero_secondary_cta_text') ?: 'Learn More';
$secondary_url  = estatein_get_field('hero_secondary_cta_url') ?: '#about';
$hero_image  = estatein_get_field('hero_image');
?>

<section class="hero" aria-label="<?php esc_attr_e('Hero', 'estatein'); ?>">
    <div class="hero__inner">
        <div class="hero__content">
            <div class="hero__text">
                <h1 class="hero__heading"><?php echo esc_html($heading); ?></h1>
                <p class="hero__description"><?php echo esc_html($description); ?></p>
                <div class="hero__badge" aria-hidden="true">
                    <svg class="hero__badge-text" width="175" height="175" viewBox="0 0 175 175">
                        <defs>
                            <path id="badge-circle" d="M87.5,87.5 m-62,0 a62,62 0 1,1 124,0 a62,62 0 1,1 -124,0"/>
                        </defs>
                        <text fill="white" font-size="14" font-weight="600" font-family="Urbanist, sans-serif" letter-spacing="4">
                            <textPath href="#badge-circle">Discover Your Dream Property ✨ </textPath>
                        </text>
                    </svg>
                    <div class="hero__badge-ring"></div>
                    <svg class="hero__badge-arrow" width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <div class="hero__actions">
                <a href="<?php echo esc_url($secondary_url); ?>" class="btn btn--outline"><?php echo esc_html($secondary_text); ?></a>
                <a href="<?php echo esc_url($primary_url); ?>" class="btn btn--primary"><?php echo esc_html($primary_text); ?></a>
            </div>
            <div class="hero__stats">
                <div class="hero__stat">
                    <span class="hero__stat-number">200+</span>
                    <span class="hero__stat-label">Happy Customers</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">10k+</span>
                    <span class="hero__stat-label">Properties For Clients</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">16+</span>
                    <span class="hero__stat-label">Years of Experience</span>
                </div>
            </div>
        </div>
        <div class="hero__image-wrapper">
            <div class="hero__image-overlay"></div>
            <?php if ($hero_image) : ?>
                <img
                    src="<?php echo esc_url($hero_image['sizes']['hero-image'] ?? $hero_image['url']); ?>"
                    alt="<?php echo esc_attr($hero_image['alt'] ?: 'Modern luxury property'); ?>"
                    width="920"
                    height="814"
                    class="hero__image"
                >
            <?php else : ?>
                <img src="<?php echo esc_url(get_theme_file_uri('assets/images/hero-building.png')); ?>" alt="Modern luxury property" width="920" height="814" class="hero__image">
            <?php endif; ?>
        </div>
    </div>
</section>
