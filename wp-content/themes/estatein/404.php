<?php get_header(); ?>
<main id="main-content" class="section">
    <div class="container" style="text-align: center; padding-block: var(--space-2xl);">
        <h1>404</h1>
        <p style="margin-top: var(--space-md); margin-bottom: var(--space-lg);">The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">Back to Home</a>
    </div>
</main>
<?php get_footer(); ?>
