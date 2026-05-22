<?php

function estatein_register_post_types() {
    register_post_type('property', [
        'labels' => [
            'name'               => __('Properties', 'estatein'),
            'singular_name'      => __('Property', 'estatein'),
            'add_new_item'       => __('Add New Property', 'estatein'),
            'edit_item'          => __('Edit Property', 'estatein'),
            'view_item'          => __('View Property', 'estatein'),
            'search_items'       => __('Search Properties', 'estatein'),
            'not_found'          => __('No properties found', 'estatein'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'properties'],
        'supports'     => ['title', 'editor', 'thumbnail'],
        'menu_icon'    => 'dashicons-building',
        'show_in_rest' => true,
    ]);
}
add_action('init', 'estatein_register_post_types');
