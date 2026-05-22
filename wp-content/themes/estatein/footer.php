<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer__top">
            <div class="footer__brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer__logo" aria-label="<?php esc_attr_e('Estatein - Home', 'estatein'); ?>">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <rect width="48" height="48" rx="12" fill="var(--color-accent)"/>
                        <path d="M14 34V18L24 12L34 18V34H28V26H20V34H14Z" fill="white"/>
                    </svg>
                    <span class="footer__logo-text">Estatein</span>
                </a>
                <form class="footer__subscribe" action="#" method="post" aria-label="<?php esc_attr_e('Newsletter subscription', 'estatein'); ?>">
                    <label for="footer-email" class="sr-only"><?php esc_html_e('Enter your email', 'estatein'); ?></label>
                    <input type="email" id="footer-email" name="email" class="footer__input" placeholder="<?php esc_attr_e('Enter Your Email', 'estatein'); ?>" required>
                    <button type="submit" class="footer__submit" aria-label="<?php esc_attr_e('Subscribe', 'estatein'); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
            <div class="footer__social">
                <a href="#" class="footer__social-link" aria-label="Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a href="#" class="footer__social-link" aria-label="LinkedIn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M16 8C17.5913 8 19.1174 8.63214 20.2426 9.75736C21.3679 10.8826 22 12.4087 22 14V21H18V14C18 13.4696 17.7893 12.9609 17.4142 12.5858C17.0391 12.2107 16.5304 12 16 12C15.4696 12 14.9609 12.2107 14.5858 12.5858C14.2107 12.9609 14 13.4696 14 14V21H10V14C10 12.4087 10.6321 10.8826 11.7574 9.75736C12.8826 8.63214 14.4087 8 16 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><rect x="2" y="9" width="4" height="12" stroke="currentColor" stroke-width="1.5"/><circle cx="4" cy="4" r="2" stroke="currentColor" stroke-width="1.5"/></svg>
                </a>
                <a href="#" class="footer__social-link" aria-label="Twitter">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M22 4.01C21 4.5 20.02 4.69 19 5C17.879 3.735 16.217 3.665 14.62 4.263C13.023 4.861 12.004 6.323 12 8.01V9.01C8.755 9.083 5.865 7.605 4 5.01C4 5.01 -0.182 12.94 8 17.01C6.128 18.247 4.261 19.088 2 19.01C5.308 20.687 8.913 21.167 12.034 20.12C15.614 18.906 18.556 15.96 19.685 11.548C20.0218 10.1584 20.189 8.73258 20.183 7.303C20.18 6.92 21.692 5.248 22 4.009V4.01Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a href="#" class="footer__social-link" aria-label="YouTube">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M22.54 6.42C22.4212 5.94541 22.1792 5.51057 21.8386 5.15941C21.498 4.80824 21.0707 4.55318 20.6 4.42C18.88 4 12 4 12 4C12 4 5.12 4 3.4 4.46C2.92925 4.59318 2.50198 4.84824 2.16135 5.19941C1.82072 5.55057 1.57879 5.98541 1.46 6.46C1.14521 8.20556 0.991228 9.97631 1 11.75C0.988741 13.537 1.14277 15.3213 1.46 17.08C1.59096 17.5398 1.83831 17.9581 2.17814 18.2945C2.51797 18.6308 2.93882 18.8738 3.4 19C5.12 19.46 12 19.46 12 19.46C12 19.46 18.88 19.46 20.6 19C21.0707 18.8668 21.498 18.6118 21.8386 18.2606C22.1792 17.9094 22.4212 17.4746 22.54 17C22.8524 15.2676 23.0063 13.5103 23 11.75C23.0113 9.96295 22.8573 8.1787 22.54 6.42Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.75 15.02L15.5 11.75L9.75 8.48V15.02Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>

        <div class="footer__nav">
            <div class="footer__nav-col">
                <h4 class="footer__nav-title">Home</h4>
                <ul class="footer__nav-list">
                    <li><a href="#hero" class="footer__nav-link">Hero Section</a></li>
                    <li><a href="#features" class="footer__nav-link">Features</a></li>
                    <li><a href="#properties" class="footer__nav-link">Properties</a></li>
                    <li><a href="#testimonials" class="footer__nav-link">Testimonials</a></li>
                    <li><a href="#faq" class="footer__nav-link">FAQ's</a></li>
                </ul>
            </div>
            <div class="footer__nav-col">
                <h4 class="footer__nav-title">About Us</h4>
                <ul class="footer__nav-list">
                    <li><a href="#" class="footer__nav-link">Our Story</a></li>
                    <li><a href="#" class="footer__nav-link">Our Works</a></li>
                    <li><a href="#" class="footer__nav-link">How It Works</a></li>
                    <li><a href="#" class="footer__nav-link">Our Team</a></li>
                    <li><a href="#" class="footer__nav-link">Our Clients</a></li>
                </ul>
            </div>
            <div class="footer__nav-col">
                <h4 class="footer__nav-title">Properties</h4>
                <ul class="footer__nav-list">
                    <li><a href="#" class="footer__nav-link">Portfolio</a></li>
                    <li><a href="#" class="footer__nav-link">Categories</a></li>
                </ul>
            </div>
            <div class="footer__nav-col">
                <h4 class="footer__nav-title">Services</h4>
                <ul class="footer__nav-list">
                    <li><a href="#" class="footer__nav-link">Valuation Mastery</a></li>
                    <li><a href="#" class="footer__nav-link">Strategic Marketing</a></li>
                    <li><a href="#" class="footer__nav-link">Negotiation Wizardry</a></li>
                    <li><a href="#" class="footer__nav-link">Closing Success</a></li>
                    <li><a href="#" class="footer__nav-link">Property Management</a></li>
                </ul>
            </div>
            <div class="footer__nav-col">
                <h4 class="footer__nav-title">Contact Us</h4>
                <ul class="footer__nav-list">
                    <li><a href="#" class="footer__nav-link">Contact Form</a></li>
                    <li><a href="#" class="footer__nav-link">Our Offices</a></li>
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <p class="footer__copyright">&copy;<?php echo esc_html(date('Y')); ?> Estatein. All Rights Reserved.</p>
            <a href="#" class="footer__terms">Terms &amp; Conditions</a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
