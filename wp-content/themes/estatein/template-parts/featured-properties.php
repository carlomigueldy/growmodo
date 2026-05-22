<?php
$properties = new WP_Query([
    'post_type'      => 'property',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
$total = wp_count_posts('property')->publish;
?>

<section class="section properties" id="properties" aria-label="<?php esc_attr_e('Featured Properties', 'estatein'); ?>">
    <div class="container">
        <div class="section__header">
            <div>
                <div class="section__icon" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
                    <svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
                    <svg width="8" height="8" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
                </div>
                <h2 class="section__title">Featured Properties</h2>
                <p class="section__description">Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein. Click "View Details" for more information.</p>
            </div>
            <a href="#" class="btn btn--outline btn--sm">View All Properties</a>
        </div>

        <?php if ($properties->have_posts()) : ?>
            <div class="property-grid">
                <?php while ($properties->have_posts()) : $properties->the_post();
                    $price    = estatein_get_field('property_price');
                    $beds     = estatein_get_field('property_bedrooms');
                    $baths    = estatein_get_field('property_bathrooms');
                    $type     = estatein_get_field('property_type');
                    $short    = estatein_get_field('property_description_short');
                    $type_labels = [
                        'villa'     => 'Villa',
                        'apartment' => 'Apartment',
                        'townhouse' => 'Townhouse',
                        'cottage'   => 'Cottage',
                    ];
                ?>
                    <article class="property-card card">
                        <div class="property-card__image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('property-card', [
                                    'class'   => 'property-card__img',
                                    'loading' => 'lazy',
                                ]); ?>
                            <?php else : ?>
                                <div class="property-card__img property-card__img--placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <div class="property-card__body">
                            <h3 class="property-card__title"><?php the_title(); ?></h3>
                            <?php if ($short) : ?>
                                <p class="property-card__description">
                                    <?php echo esc_html($short); ?>
                                    <a href="<?php the_permalink(); ?>" class="property-card__readmore">Read More</a>
                                </p>
                            <?php endif; ?>

                            <div class="property-card__meta">
                                <?php if ($beds) : ?>
                                    <span class="property-card__meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21V14H21V21M3 14V10C3 8.89543 3.89543 8 5 8H19C20.1046 8 21 8.89543 21 10V14M7 8V6C7 4.89543 7.89543 4 9 4H15C16.1046 4 17 4.89543 17 6V8" stroke="currentColor" stroke-width="1.5"/></svg>
                                        <?php echo esc_html($beds); ?>-Bedroom
                                    </span>
                                <?php endif; ?>
                                <?php if ($baths) : ?>
                                    <span class="property-card__meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12H20V16C20 18.2091 18.2091 20 16 20H8C5.79086 20 4 18.2091 4 16V12ZM4 12V5C4 4.44772 4.44772 4 5 4H7C7.55228 4 8 4.44772 8 5V12" stroke="currentColor" stroke-width="1.5"/></svg>
                                        <?php echo esc_html($baths); ?>-Bathroom
                                    </span>
                                <?php endif; ?>
                                <?php if ($type) : ?>
                                    <span class="property-card__meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21V9L12 3L21 9V21H15V15H9V21H3Z" stroke="currentColor" stroke-width="1.5"/></svg>
                                        <?php echo esc_html($type_labels[$type] ?? ucfirst($type)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="property-card__footer">
                                <div class="property-card__price">
                                    <span class="property-card__price-label">Price</span>
                                    <span class="property-card__price-value">$<?php echo esc_html(number_format($price ?: 0)); ?></span>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="btn btn--primary btn--sm">View Property Details</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <div class="pagination">
                <span class="pagination__text">01 of <?php echo esc_html(str_pad($total, 2, '0', STR_PAD_LEFT)); ?></span>
                <div class="pagination__arrows">
                    <button class="pagination__arrow" aria-label="<?php esc_attr_e('Previous properties', 'estatein'); ?>">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button class="pagination__arrow" aria-label="<?php esc_attr_e('Next properties', 'estatein'); ?>">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>
        <?php else : ?>
            <p class="properties__empty">No properties found. Add some in the WordPress admin.</p>
        <?php endif; ?>
    </div>
</section>
