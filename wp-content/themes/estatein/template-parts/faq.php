<?php
$faqs = [
    [
        'question' => 'How do I search for properties on Estatein?',
        'answer'   => 'Learn how to use our user-friendly search tools to find properties that match your criteria.',
    ],
    [
        'question' => 'What documents do I need to sell my property through Estatein?',
        'answer'   => 'Find out about the necessary documentation for listing your property with us.',
    ],
    [
        'question' => 'How can I contact an Estatein agent?',
        'answer'   => 'Discover the different ways you can get in touch with our experienced agents.',
    ],
];
?>

<section class="section faq" aria-label="<?php esc_attr_e('Frequently Asked Questions', 'estatein'); ?>">
    <div class="container">
        <div class="section__header">
            <div>
                <div class="section__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M12 0C12.5 8 16 11.5 24 12C16 12.5 12.5 16 12 24C11.5 16 8 12.5 0 12C8 11.5 11.5 8 12 0Z" fill="currentColor"/></svg>
                </div>
                <h2 class="section__title">Frequently Asked Questions</h2>
                <p class="section__description">Find answers to common questions about Estatein's services, property listings, and the real estate process. We're here to provide clarity and assist you every step of the way.</p>
            </div>
            <a href="#" class="btn btn--outline btn--sm">View All FAQ's</a>
        </div>

        <div class="faq__grid">
            <?php foreach ($faqs as $faq) : ?>
                <div class="faq-card card">
                    <div class="faq-card__body">
                        <h3 class="faq-card__question"><?php echo esc_html($faq['question']); ?></h3>
                        <p class="faq-card__answer"><?php echo esc_html($faq['answer']); ?></p>
                        <a href="#" class="faq-card__link">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <span class="pagination__text"><span class="pagination__current">01</span> <span class="pagination__total">of 10</span></span>
            <div class="pagination__arrows">
                <button class="pagination__arrow pagination__arrow--prev" aria-label="<?php esc_attr_e('Previous questions', 'estatein'); ?>">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="pagination__arrow pagination__arrow--next" aria-label="<?php esc_attr_e('Next questions', 'estatein'); ?>">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
