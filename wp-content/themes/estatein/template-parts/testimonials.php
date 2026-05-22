<?php
$testimonials = [
    [
        'rating'   => 5,
        'title'    => 'Exceptional Service!',
        'text'     => 'Our experience with Estatein was outstanding. Their team\'s dedication and professionalism made finding our dream home a breeze. Highly recommended!',
        'name'     => 'Wade Warren',
        'location' => 'USA, California',
        'avatar'   => 'avatar-wade.png',
    ],
    [
        'rating'   => 5,
        'title'    => 'Efficient and Reliable',
        'text'     => 'Estatein provided us with top-notch service. They helped us sell our property quickly and at a great price. We couldn\'t be happier with the results.',
        'name'     => 'Emelie Thomson',
        'location' => 'USA, Florida',
        'avatar'   => 'avatar-emelie.png',
    ],
    [
        'rating'   => 5,
        'title'    => 'Trusted Advisors',
        'text'     => 'The Estatein team guided us through the entire buying process. Their knowledge and commitment to our needs were impressive. Thank you for your support!',
        'name'     => 'John Mans',
        'location' => 'USA, Nevada',
        'avatar'   => 'avatar-john.png',
    ],
];
?>

<section class="section testimonials" aria-label="<?php esc_attr_e('Client Testimonials', 'estatein'); ?>">
    <div class="container">
        <div class="section__header">
            <div>
                <div class="section__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                </div>
                <h2 class="section__title">What Our Clients Say</h2>
                <p class="section__description">Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.</p>
            </div>
            <a href="#" class="btn btn--outline btn--sm">View All Testimonials</a>
        </div>

        <div class="testimonials__grid">
            <?php foreach ($testimonials as $testimonial) : ?>
                <div class="testimonial-card card">
                    <div class="testimonial-card__stars" aria-label="<?php echo esc_attr($testimonial['rating']); ?> out of 5 stars">
                        <?php for ($i = 0; $i < $testimonial['rating']; $i++) : ?>
                            <img
                                src="<?php echo esc_url( get_theme_file_uri( 'assets/images/star-rating.svg' ) ); ?>"
                                width="44"
                                height="44"
                                alt=""
                                aria-hidden="true"
                                class="testimonial-card__star"
                            >
                        <?php endfor; ?>
                    </div>
                    <h3 class="testimonial-card__title"><?php echo esc_html($testimonial['title']); ?></h3>
                    <p class="testimonial-card__text"><?php echo esc_html($testimonial['text']); ?></p>
                    <div class="testimonial-card__author">
                        <img
                            src="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $testimonial['avatar'] ) ); ?>"
                            width="60"
                            height="60"
                            alt="<?php echo esc_attr( $testimonial['name'] ); ?>"
                            class="testimonial-card__avatar"
                        >
                        <div>
                            <span class="testimonial-card__name"><?php echo esc_html($testimonial['name']); ?></span>
                            <span class="testimonial-card__location"><?php echo esc_html($testimonial['location']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <span class="pagination__text">01 of 10</span>
            <div class="pagination__arrows">
                <button class="pagination__arrow" aria-label="<?php esc_attr_e('Previous testimonials', 'estatein'); ?>">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="pagination__arrow" aria-label="<?php esc_attr_e('Next testimonials', 'estatein'); ?>">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
