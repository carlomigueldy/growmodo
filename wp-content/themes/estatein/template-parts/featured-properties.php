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
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                </div>
                <h2 class="section__title">Featured Properties</h2>
                <p class="section__description">Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein. Click "View Details" for more information.</p>
            </div>
            <a href="#" class="btn btn--outline btn--sm">View All Properties</a>
        </div>

        <?php if ($properties->have_posts()) : ?>
            <div class="property-grid">
                <?php $card_index = 0; while ($properties->have_posts()) : $properties->the_post();
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
                            <?php else :
                                $slug = sanitize_title(get_the_title());
                                $image_map = [
                                    'seaside-serenity-villa' => 'property-seaside.png',
                                    'metropolitan-haven'     => 'property-metropolitan.png',
                                    'rustic-retreat-cottage'  => 'property-rustic.png',
                                ];
                                $fallback_images = ['property-seaside.png', 'property-metropolitan.png', 'property-rustic.png'];
                                $fallback = $image_map[$slug] ?? $fallback_images[$card_index % 3];
                            ?>
                                <img src="<?php echo esc_url(get_theme_file_uri('assets/images/' . $fallback)); ?>"
                                     alt="<?php the_title_attribute(); ?>"
                                     class="property-card__img"
                                     loading="lazy"
                                     width="1060" height="706">
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
                                        <img src="<?php echo esc_url(get_theme_file_uri('assets/images/icon-bed.png')); ?>" alt="" aria-hidden="true" width="24" height="24">
                                        <?php echo esc_html($beds); ?>-Bedroom
                                    </span>
                                <?php endif; ?>
                                <?php if ($baths) : ?>
                                    <span class="property-card__meta-item">
                                        <img src="<?php echo esc_url(get_theme_file_uri('assets/images/icon-bath.png')); ?>" alt="" aria-hidden="true" width="24" height="24">
                                        <?php echo esc_html($baths); ?>-Bathroom
                                    </span>
                                <?php endif; ?>
                                <?php if ($type) : ?>
                                    <span class="property-card__meta-item">
                                        <img src="<?php echo esc_url(get_theme_file_uri('assets/images/icon-villa.png')); ?>" alt="" aria-hidden="true" width="24" height="24">
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
                    <?php $card_index++; ?>
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
