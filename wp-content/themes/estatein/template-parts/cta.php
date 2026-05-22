<?php
$heading     = get_field('cta_heading') ?: 'Start Your Real Estate Journey Today';
$description = get_field('cta_description') ?: 'Your dream property is just a click away. Whether you\'re looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way. Take the first step towards your real estate goals and explore our available properties or get in touch with our team for personalized assistance.';
$btn_text    = get_field('cta_button_text') ?: 'Explore Properties';
$btn_url     = get_field('cta_button_url') ?: '#properties';
?>

<section class="cta" aria-label="<?php esc_attr_e('Call to action', 'estatein'); ?>">
    <div class="container">
        <div class="cta__inner">
            <div class="cta__content">
                <h2 class="cta__heading"><?php echo esc_html($heading); ?></h2>
                <p class="cta__description"><?php echo esc_html($description); ?></p>
            </div>
            <a href="<?php echo esc_url($btn_url); ?>" class="btn btn--primary cta__button"><?php echo esc_html($btn_text); ?></a>
        </div>
    </div>
</section>
