<?php
$heading     = get_field('hero_heading') ?: 'Discover Your Dream Property with Estatein';
$description = get_field('hero_description') ?: 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.';
$primary_text = get_field('hero_primary_cta_text') ?: 'Browse Properties';
$primary_url  = get_field('hero_primary_cta_url') ?: '#properties';
$secondary_text = get_field('hero_secondary_cta_text') ?: 'Learn More';
$secondary_url  = get_field('hero_secondary_cta_url') ?: '#about';
$hero_image  = get_field('hero_image');
?>

<section class="hero" aria-label="<?php esc_attr_e('Hero', 'estatein'); ?>">
    <div class="hero__inner container">
        <div class="hero__content">
            <h1 class="hero__heading"><?php echo esc_html($heading); ?></h1>
            <p class="hero__description"><?php echo esc_html($description); ?></p>
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
            <?php if ($hero_image) : ?>
                <img
                    src="<?php echo esc_url($hero_image['sizes']['hero-image'] ?? $hero_image['url']); ?>"
                    alt="<?php echo esc_attr($hero_image['alt'] ?: 'Modern luxury property'); ?>"
                    width="800"
                    height="600"
                    class="hero__image"
                >
            <?php else : ?>
                <div class="hero__image hero__image--placeholder" role="img" aria-label="Modern luxury property">
                </div>
            <?php endif; ?>
            <div class="hero__badge" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>
</section>
