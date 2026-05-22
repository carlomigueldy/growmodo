<?php

if (! function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key'      => 'group_hero',
    'title'    => 'Homepage — Hero Section',
    'fields'   => [
        [
            'key'   => 'field_hero_heading',
            'label' => 'Heading',
            'name'  => 'hero_heading',
            'type'  => 'text',
            'default_value' => 'Discover Your Dream Property with Estatein',
        ],
        [
            'key'   => 'field_hero_description',
            'label' => 'Description',
            'name'  => 'hero_description',
            'type'  => 'textarea',
            'rows'  => 3,
            'default_value' => 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.',
        ],
        [
            'key'   => 'field_hero_primary_cta_text',
            'label' => 'Primary CTA Text',
            'name'  => 'hero_primary_cta_text',
            'type'  => 'text',
            'default_value' => 'Browse Properties',
        ],
        [
            'key'   => 'field_hero_primary_cta_url',
            'label' => 'Primary CTA URL',
            'name'  => 'hero_primary_cta_url',
            'type'  => 'url',
            'default_value' => '#properties',
        ],
        [
            'key'   => 'field_hero_secondary_cta_text',
            'label' => 'Secondary CTA Text',
            'name'  => 'hero_secondary_cta_text',
            'type'  => 'text',
            'default_value' => 'Learn More',
        ],
        [
            'key'   => 'field_hero_secondary_cta_url',
            'label' => 'Secondary CTA URL',
            'name'  => 'hero_secondary_cta_url',
            'type'  => 'url',
            'default_value' => '#about',
        ],
        [
            'key'   => 'field_hero_image',
            'label' => 'Hero Image',
            'name'  => 'hero_image',
            'type'  => 'image',
            'return_format' => 'array',
        ],
        [
            'key'        => 'field_hero_stats',
            'label'      => 'Statistics',
            'name'       => 'hero_stats',
            'type'       => 'repeater',
            'layout'     => 'table',
            'min'        => 3,
            'max'        => 3,
            'sub_fields' => [
                [
                    'key'   => 'field_stat_number',
                    'label' => 'Number',
                    'name'  => 'stat_number',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_stat_label',
                    'label' => 'Label',
                    'name'  => 'stat_label',
                    'type'  => 'text',
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_type',
                'operator' => '==',
                'value'    => 'front_page',
            ],
        ],
    ],
]);

acf_add_local_field_group([
    'key'      => 'group_features',
    'title'    => 'Homepage — Features Section',
    'fields'   => [
        [
            'key'        => 'field_features',
            'label'      => 'Features',
            'name'       => 'features',
            'type'       => 'repeater',
            'layout'     => 'block',
            'min'        => 1,
            'max'        => 4,
            'sub_fields' => [
                [
                    'key'   => 'field_feature_icon',
                    'label' => 'Icon Name',
                    'name'  => 'feature_icon',
                    'type'  => 'text',
                    'instructions' => 'Icon identifier: home, value, management, investment',
                ],
                [
                    'key'   => 'field_feature_title',
                    'label' => 'Title',
                    'name'  => 'feature_title',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_feature_description',
                    'label' => 'Description',
                    'name'  => 'feature_description',
                    'type'  => 'textarea',
                    'rows'  => 2,
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_type',
                'operator' => '==',
                'value'    => 'front_page',
            ],
        ],
    ],
]);

acf_add_local_field_group([
    'key'      => 'group_testimonials',
    'title'    => 'Homepage — Testimonials Section',
    'fields'   => [
        [
            'key'        => 'field_testimonials',
            'label'      => 'Testimonials',
            'name'       => 'testimonials',
            'type'       => 'repeater',
            'layout'     => 'block',
            'sub_fields' => [
                [
                    'key'   => 'field_testimonial_rating',
                    'label' => 'Rating (1-5)',
                    'name'  => 'testimonial_rating',
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 5,
                    'default_value' => 5,
                ],
                [
                    'key'   => 'field_testimonial_title',
                    'label' => 'Title',
                    'name'  => 'testimonial_title',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_testimonial_text',
                    'label' => 'Review Text',
                    'name'  => 'testimonial_text',
                    'type'  => 'textarea',
                    'rows'  => 3,
                ],
                [
                    'key'   => 'field_testimonial_avatar',
                    'label' => 'Avatar',
                    'name'  => 'testimonial_avatar',
                    'type'  => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'avatar',
                ],
                [
                    'key'   => 'field_testimonial_name',
                    'label' => 'Name',
                    'name'  => 'testimonial_name',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_testimonial_location',
                    'label' => 'Location',
                    'name'  => 'testimonial_location',
                    'type'  => 'text',
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_type',
                'operator' => '==',
                'value'    => 'front_page',
            ],
        ],
    ],
]);

acf_add_local_field_group([
    'key'      => 'group_faq',
    'title'    => 'Homepage — FAQ Section',
    'fields'   => [
        [
            'key'        => 'field_faqs',
            'label'      => 'FAQs',
            'name'       => 'faqs',
            'type'       => 'repeater',
            'layout'     => 'block',
            'sub_fields' => [
                [
                    'key'   => 'field_faq_question',
                    'label' => 'Question',
                    'name'  => 'faq_question',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_faq_answer',
                    'label' => 'Answer',
                    'name'  => 'faq_answer',
                    'type'  => 'textarea',
                    'rows'  => 3,
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_type',
                'operator' => '==',
                'value'    => 'front_page',
            ],
        ],
    ],
]);

acf_add_local_field_group([
    'key'      => 'group_cta',
    'title'    => 'Homepage — CTA Section',
    'fields'   => [
        [
            'key'   => 'field_cta_heading',
            'label' => 'Heading',
            'name'  => 'cta_heading',
            'type'  => 'text',
            'default_value' => 'Start Your Real Estate Journey Today',
        ],
        [
            'key'   => 'field_cta_description',
            'label' => 'Description',
            'name'  => 'cta_description',
            'type'  => 'textarea',
            'rows'  => 3,
            'default_value' => "Your dream property is just a click away. Whether you're looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way.",
        ],
        [
            'key'   => 'field_cta_button_text',
            'label' => 'Button Text',
            'name'  => 'cta_button_text',
            'type'  => 'text',
            'default_value' => 'Explore Properties',
        ],
        [
            'key'   => 'field_cta_button_url',
            'label' => 'Button URL',
            'name'  => 'cta_button_url',
            'type'  => 'url',
            'default_value' => '#properties',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_type',
                'operator' => '==',
                'value'    => 'front_page',
            ],
        ],
    ],
]);

acf_add_local_field_group([
    'key'      => 'group_property_details',
    'title'    => 'Property Details',
    'fields'   => [
        [
            'key'   => 'field_property_price',
            'label' => 'Price',
            'name'  => 'property_price',
            'type'  => 'number',
            'prepend' => '$',
        ],
        [
            'key'   => 'field_property_bedrooms',
            'label' => 'Bedrooms',
            'name'  => 'property_bedrooms',
            'type'  => 'number',
            'min'   => 0,
        ],
        [
            'key'   => 'field_property_bathrooms',
            'label' => 'Bathrooms',
            'name'  => 'property_bathrooms',
            'type'  => 'number',
            'min'   => 0,
        ],
        [
            'key'     => 'field_property_type',
            'label'   => 'Property Type',
            'name'    => 'property_type',
            'type'    => 'select',
            'choices' => [
                'villa'     => 'Villa',
                'apartment' => 'Apartment',
                'townhouse' => 'Townhouse',
                'cottage'   => 'Cottage',
            ],
        ],
        [
            'key'   => 'field_property_description_short',
            'label' => 'Short Description',
            'name'  => 'property_description_short',
            'type'  => 'textarea',
            'rows'  => 2,
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'property',
            ],
        ],
    ],
]);
