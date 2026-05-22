<?php

function estatein_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('custom-logo', [
        'height'      => 48,
        'width'       => 160,
        'flex-height'  => true,
        'flex-width'   => true,
    ]);

    register_nav_menus([
        'primary'   => __('Primary Navigation', 'estatein'),
        'footer_1'  => __('Footer - Home', 'estatein'),
        'footer_2'  => __('Footer - About Us', 'estatein'),
        'footer_3'  => __('Footer - Properties', 'estatein'),
        'footer_4'  => __('Footer - Services', 'estatein'),
        'footer_5'  => __('Footer - Contact Us', 'estatein'),
    ]);

    add_image_size('property-card', 400, 260, true);
    add_image_size('hero-image', 800, 600, false);
    add_image_size('avatar', 60, 60, true);
}
add_action('after_setup_theme', 'estatein_setup');

function estatein_enqueue_assets() {
    wp_enqueue_style(
        'estatein-fonts',
        'https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'estatein-style',
        get_stylesheet_uri(),
        ['estatein-fonts'],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script(
        'estatein-main',
        get_theme_file_uri('assets/js/main.js'),
        [],
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'estatein_enqueue_assets');

function estatein_add_open_graph_meta() {
    if (is_front_page()) {
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '">' . "\n";
    }
}
add_action('wp_head', 'estatein_add_open_graph_meta');
