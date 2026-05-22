# Estatein WordPress Theme Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a pixel-perfect WordPress homepage for the Estatein real estate dark-theme Figma design as a custom classic theme, running in Docker.

**Architecture:** Classic WordPress theme with PHP template parts for each homepage section. Vanilla CSS with custom properties for the dark theme token system. ACF Free plugin for content management fields registered via PHP. Docker Compose (WordPress 6.7 + MySQL 8) for local dev.

**Tech Stack:** WordPress 6.7, PHP 8.2, Vanilla CSS (custom properties), ACF Free, Docker Compose, Google Fonts (Urbanist)

**Spec:** `docs/superpowers/specs/2026-05-22-estatein-wordpress-theme-design.md`

**Figma:** https://www.figma.com/design/jew3YhbDHZCZ5A2SUsO7VK — use Figma MCP `get_design_context` on specific node IDs to extract exact colors, spacing, font sizes, and assets for pixel-perfect implementation. Key frames:
- Home Page Desktop: `46:304`
- Home Page Laptop: `139:6238`
- Home Page Mobile: `139:7812`

---

## File Map

| File | Responsibility |
|---|---|
| `docker-compose.yml` | WordPress + MySQL containers |
| `.env.example` | Environment variable template |
| `.gitignore` | Ignore WP core, node_modules, .env |
| `wp-content/themes/estatein/style.css` | Theme header metadata + all CSS (tokens, reset, layout, components, responsive) |
| `wp-content/themes/estatein/functions.php` | Theme bootstrap — enqueues, includes |
| `wp-content/themes/estatein/inc/theme-setup.php` | add_theme_support, menus, image sizes |
| `wp-content/themes/estatein/inc/custom-post-types.php` | Property CPT registration |
| `wp-content/themes/estatein/inc/acf-fields.php` | All ACF field group registrations |
| `wp-content/themes/estatein/front-page.php` | Homepage template — includes all template parts |
| `wp-content/themes/estatein/header.php` | Banner + navigation bar |
| `wp-content/themes/estatein/footer.php` | Footer columns + bottom bar |
| `wp-content/themes/estatein/template-parts/hero.php` | Hero section (heading, CTAs, stats, image) |
| `wp-content/themes/estatein/template-parts/features.php` | 4 feature icon cards |
| `wp-content/themes/estatein/template-parts/featured-properties.php` | Property cards from CPT query |
| `wp-content/themes/estatein/template-parts/testimonials.php` | Testimonial cards from ACF repeater |
| `wp-content/themes/estatein/template-parts/faq.php` | FAQ cards from ACF repeater |
| `wp-content/themes/estatein/template-parts/cta.php` | Call-to-action banner |
| `wp-content/themes/estatein/assets/js/main.js` | Nav toggle, banner dismiss, smooth scroll |
| `wp-content/themes/estatein/assets/images/` | Logo SVG, icons, hero image, decorative elements |
| `wp-content/themes/estatein/404.php` | Basic 404 page |
| `README.md` | Setup instructions, dev process docs, plugin/tool choices |

---

## Task 1: Docker Compose + Project Scaffolding

**Files:**
- Create: `docker-compose.yml`
- Create: `.env.example`
- Create: `.gitignore`

- [ ] **Step 1: Create `.gitignore`**

```gitignore
# WordPress core (only theme is tracked)
wp-content/plugins/
wp-content/uploads/
wp-content/upgrade/

# Environment
.env

# OS
.DS_Store
Thumbs.db

# IDE
.vscode/
.idea/

# Superpowers
.superpowers/
```

- [ ] **Step 2: Create `.env.example`**

```env
DB_PASSWORD=wordpress
DB_ROOT_PASSWORD=rootpassword
```

- [ ] **Step 3: Create `docker-compose.yml`**

```yaml
services:
  wordpress:
    image: wordpress:6.7-php8.2-apache
    restart: unless-stopped
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD:-wordpress}
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - wordpress_data:/var/www/html
      - ./wp-content/themes/estatein:/var/www/html/wp-content/themes/estatein
    depends_on:
      db:
        condition: service_healthy

  db:
    image: mysql:8.0
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: ${DB_PASSWORD:-wordpress}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-rootpassword}
    volumes:
      - db_data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 5s
      timeout: 5s
      retries: 5

volumes:
  wordpress_data:
  db_data:
```

- [ ] **Step 4: Create the theme directory structure**

```bash
mkdir -p wp-content/themes/estatein/{template-parts,inc,assets/{images,fonts,js}}
```

- [ ] **Step 5: Copy `.env.example` to `.env` and start Docker**

```bash
cp .env.example .env
docker compose up -d
```

Wait for both containers to be healthy:

```bash
docker compose ps
```

Expected: Both `wordpress` and `db` show status `Up` / `healthy`.

- [ ] **Step 6: Verify WordPress is running**

Open `http://localhost:8080` in browser. You should see the WordPress installation wizard. Complete the install with:
- Site Title: Estatein
- Username: admin
- Password: admin
- Email: admin@example.com

- [ ] **Step 7: Commit**

```bash
git add docker-compose.yml .env.example .gitignore
git commit -m "chore: add Docker Compose setup for WordPress 6.7 + MySQL 8"
```

---

## Task 2: Theme Bootstrap — style.css, functions.php, theme-setup.php

**Files:**
- Create: `wp-content/themes/estatein/style.css`
- Create: `wp-content/themes/estatein/functions.php`
- Create: `wp-content/themes/estatein/inc/theme-setup.php`

- [ ] **Step 1: Create `style.css` with theme header and design tokens**

The `style.css` must begin with the WordPress theme header comment block, followed by the CSS reset, design tokens, and base styles. This is the ONLY stylesheet — all CSS lives here.

```css
/*
Theme Name: Estatein
Theme URI: https://github.com/carlomigueldy/growmodo
Author: Carlo Miguel Dy
Author URI: https://carlomigueldy.com
Description: A dark-themed real estate website for the Estatein brand. Custom WordPress theme built for the Growmodo developer assessment.
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.2
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: estatein
*/

/* === RESET === */
*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

img {
  max-width: 100%;
  height: auto;
  display: block;
}

a {
  text-decoration: none;
  color: inherit;
}

ul, ol {
  list-style: none;
}

button {
  cursor: pointer;
  border: none;
  background: none;
  font: inherit;
  color: inherit;
}

input {
  font: inherit;
  color: inherit;
  border: none;
  background: none;
}

/* === DESIGN TOKENS === */
:root {
  --color-bg-primary: #141414;
  --color-bg-secondary: #1A1A1A;
  --color-bg-tertiary: #262626;
  --color-border: #262626;
  --color-border-light: #333333;

  --color-text-primary: #FFFFFF;
  --color-text-secondary: #999999;
  --color-text-muted: #666666;

  --color-accent: #703BF7;
  --color-accent-hover: #8B5CF6;
  --color-accent-subtle: rgba(112, 59, 247, 0.1);

  --color-star: #FFB700;

  --font-family: 'Urbanist', sans-serif;
  --font-size-display: clamp(2.5rem, 4vw, 3.75rem);
  --font-size-h2: clamp(1.75rem, 3vw, 2.5rem);
  --font-size-h3: clamp(1.125rem, 2vw, 1.5rem);
  --font-size-body: 1rem;
  --font-size-small: 0.875rem;
  --font-size-xs: 0.75rem;

  --font-weight-regular: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;

  --space-xs: 0.5rem;
  --space-sm: 1rem;
  --space-md: 1.5rem;
  --space-lg: 2.5rem;
  --space-xl: 5rem;
  --space-2xl: 6.25rem;

  --container-max: 1596px;
  --container-padding: 162px;
  --border-radius: 12px;
  --border-radius-sm: 8px;
  --border-radius-pill: 999px;

  --transition: 0.2s ease;
}

/* === BASE === */
html {
  scroll-behavior: smooth;
}

body {
  font-family: var(--font-family);
  font-size: var(--font-size-body);
  font-weight: var(--font-weight-regular);
  line-height: 1.6;
  color: var(--color-text-secondary);
  background-color: var(--color-bg-primary);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

h1, h2, h3, h4, h5, h6 {
  color: var(--color-text-primary);
  font-weight: var(--font-weight-semibold);
  line-height: 1.2;
}

h1 { font-size: var(--font-size-display); }
h2 { font-size: var(--font-size-h2); }
h3 { font-size: var(--font-size-h3); }

/* === UTILITIES === */
.container {
  max-width: var(--container-max);
  margin-inline: auto;
  padding-inline: var(--space-md);
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

.skip-link {
  position: absolute;
  top: -100%;
  left: var(--space-sm);
  z-index: 9999;
  padding: var(--space-xs) var(--space-sm);
  background: var(--color-accent);
  color: var(--color-text-primary);
  border-radius: var(--border-radius-sm);
  font-weight: var(--font-weight-medium);
}

.skip-link:focus {
  top: var(--space-sm);
}

/* === BUTTONS === */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-xs);
  padding: 0.875rem 1.5rem;
  font-size: var(--font-size-small);
  font-weight: var(--font-weight-medium);
  border-radius: var(--border-radius-sm);
  transition: background-color var(--transition), border-color var(--transition);
  white-space: nowrap;
}

.btn--primary {
  background-color: var(--color-accent);
  color: var(--color-text-primary);
}

.btn--primary:hover {
  background-color: var(--color-accent-hover);
}

.btn--outline {
  background-color: var(--color-bg-secondary);
  color: var(--color-text-primary);
  border: 1px solid var(--color-border);
}

.btn--outline:hover {
  background-color: var(--color-bg-tertiary);
  border-color: var(--color-border-light);
}

.btn--sm {
  padding: 0.625rem 1.25rem;
  font-size: var(--font-size-small);
}

/* === SECTION HEADER === */
.section {
  padding-block: var(--space-xl);
}

.section__header {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: flex-end;
  gap: var(--space-md);
  margin-bottom: var(--space-lg);
}

.section__icon {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  margin-bottom: var(--space-xs);
}

.section__title {
  margin-bottom: var(--space-xs);
}

.section__description {
  max-width: 800px;
}

/* === CARD BASE === */
.card {
  background-color: var(--color-bg-secondary);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius);
  overflow: hidden;
  transition: border-color var(--transition);
}

.card:hover {
  border-color: var(--color-border-light);
}

/* === PAGINATION === */
.pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: var(--space-lg);
  padding-top: var(--space-md);
  border-top: 1px solid var(--color-border);
}

.pagination__text {
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
}

.pagination__arrows {
  display: flex;
  gap: var(--space-xs);
}

.pagination__arrow {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background-color: var(--color-bg-secondary);
  border: 1px solid var(--color-border);
  color: var(--color-text-primary);
  transition: background-color var(--transition);
}

.pagination__arrow:hover {
  background-color: var(--color-bg-tertiary);
}

.pagination__arrow svg {
  width: 20px;
  height: 20px;
}
```

This is the foundation. Section-specific styles will be appended at the end of `style.css` during later tasks (hero, features, properties, etc.).

- [ ] **Step 2: Create `inc/theme-setup.php`**

```php
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
```

- [ ] **Step 3: Create `functions.php`**

```php
<?php

if (! defined('ABSPATH')) {
    exit;
}

define('ESTATEIN_VERSION', wp_get_theme()->get('Version'));
define('ESTATEIN_DIR', get_template_directory());
define('ESTATEIN_URI', get_template_directory_uri());

require_once ESTATEIN_DIR . '/inc/theme-setup.php';
require_once ESTATEIN_DIR . '/inc/custom-post-types.php';
require_once ESTATEIN_DIR . '/inc/acf-fields.php';
```

- [ ] **Step 4: Verify theme appears in WP admin**

Open `http://localhost:8080/wp-admin/themes.php`. The "Estatein" theme should appear (without a screenshot for now). Activate it.

Expected: Theme activates successfully. The frontend shows a blank page (no templates yet) — that's correct.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/style.css wp-content/themes/estatein/functions.php wp-content/themes/estatein/inc/theme-setup.php
git commit -m "feat: add theme bootstrap with style.css tokens, functions.php, and theme-setup"
```

---

## Task 3: Custom Post Type + ACF Fields

**Files:**
- Create: `wp-content/themes/estatein/inc/custom-post-types.php`
- Create: `wp-content/themes/estatein/inc/acf-fields.php`

- [ ] **Step 1: Create `inc/custom-post-types.php`**

```php
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
```

- [ ] **Step 2: Create `inc/acf-fields.php`**

This registers all ACF field groups if ACF is active. If ACF is not installed, the theme still works — template parts fall back to hardcoded defaults.

```php
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
```

**Important note on ACF Free + Repeaters:** The ACF Free plugin does NOT support the repeater field type — that's ACF Pro only. Since we're using ACF Free, the `hero_stats`, `features`, `testimonials`, and `faqs` repeater fields will not work via `acf_add_local_field_group`. For the homepage scope, we'll hardcode the repeating content directly in the template parts with sensible defaults from the Figma. The ACF fields that DO work with ACF Free (text, textarea, image, number, select, url) are used for the non-repeating fields: hero heading/description/CTAs/image, CTA section, and property details. This is the pragmatic approach for a 4-hour assessment — show ACF knowledge where it matters, hardcode where ACF Free can't help.

- [ ] **Step 3: Verify CPT in admin**

Open `http://localhost:8080/wp-admin`. You should see "Properties" in the left sidebar with a building icon. Click it — should show the empty property list.

If ACF plugin is installed: Go to the front page editor — you should see the Hero, Features, Testimonials, FAQ, and CTA field groups.

- [ ] **Step 4: Add 3 sample properties**

In wp-admin, go to Properties → Add New. Create 3 properties:

1. **Seaside Serenity Villa** — $550,000, 4 bedrooms, 3 bathrooms, Villa. Short desc: "A stunning 4-bedroom, 3-bathroom villa in a peaceful suburban neighborhood..."
2. **Metropolitan Haven** — $550,000, 2 bedrooms, 2 bathrooms, Villa. Short desc: "A chic and fully-furnished 2-bedroom apartment with panoramic city views..."
3. **Rustic Retreat Cottage** — $550,000, 3 bedrooms, 3 bathrooms, Villa. Short desc: "An elegant 3-bedroom, 2-bathroom townhouse in a gated community..."

Add a featured image to each (download any modern building/house photo for now — these will be replaced with Figma assets later).

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/inc/custom-post-types.php wp-content/themes/estatein/inc/acf-fields.php
git commit -m "feat: register Property CPT and ACF field groups for homepage sections"
```

---

## Task 4: Header (banner + navigation)

**Files:**
- Create: `wp-content/themes/estatein/header.php`
- Modify: `wp-content/themes/estatein/style.css` — append header styles

- [ ] **Step 1: Create `header.php`**

Use the Figma MCP to get exact design context for the header:
```
get_design_context(fileKey: "jew3YhbDHZCZ5A2SUsO7VK", nodeId: "60:3125")
```

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e('Skip to content', 'estatein'); ?></a>

<header class="site-header" role="banner">
    <div class="banner" id="site-banner">
        <div class="banner__inner">
            <p class="banner__text">
                <svg class="banner__icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="#FFD700"/>
                </svg>
                Discover Your Dream Property with Estatein.
                <a href="#" class="banner__link">Learn More</a>
            </p>
            <button class="banner__close" id="banner-close" aria-label="<?php esc_attr_e('Close banner', 'estatein'); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>

    <nav class="navbar" role="navigation" aria-label="<?php esc_attr_e('Primary navigation', 'estatein'); ?>">
        <div class="navbar__inner">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar__logo" aria-label="<?php esc_attr_e('Estatein - Home', 'estatein'); ?>">
                <svg class="navbar__logo-icon" width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                    <rect width="48" height="48" rx="12" fill="var(--color-accent)"/>
                    <path d="M14 34V18L24 12L34 18V34H28V26H20V34H14Z" fill="white"/>
                </svg>
                <span class="navbar__logo-text">Estatein</span>
            </a>

            <button class="navbar__toggle" id="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="<?php esc_attr_e('Toggle navigation', 'estatein'); ?>">
                <span class="navbar__toggle-bar"></span>
                <span class="navbar__toggle-bar"></span>
                <span class="navbar__toggle-bar"></span>
            </button>

            <div class="navbar__menu" id="nav-menu">
                <ul class="navbar__links">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="navbar__link navbar__link--active">Home</a></li>
                    <li><a href="#about" class="navbar__link">About Us</a></li>
                    <li><a href="#properties" class="navbar__link">Properties</a></li>
                    <li><a href="#services" class="navbar__link">Services</a></li>
                </ul>
                <a href="#contact" class="btn btn--outline btn--sm navbar__cta">Contact Us</a>
            </div>
        </div>
    </nav>
</header>
```

- [ ] **Step 2: Append header CSS to `style.css`**

Add the following at the end of `style.css`:

```css
/* === BANNER === */
.banner {
  background-color: var(--color-bg-secondary);
  border-bottom: 1px solid var(--color-border);
  padding-block: var(--space-xs);
  position: relative;
  overflow: hidden;
}

.banner__inner {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-sm);
  max-width: var(--container-max);
  margin-inline: auto;
  padding-inline: var(--space-md);
}

.banner__text {
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.banner__icon {
  flex-shrink: 0;
}

.banner__link {
  color: var(--color-text-primary);
  text-decoration: underline;
  text-underline-offset: 2px;
  margin-left: 0.25rem;
}

.banner__link:hover {
  color: var(--color-accent);
}

.banner__close {
  position: absolute;
  right: var(--space-md);
  top: 50%;
  transform: translateY(-50%);
  color: var(--color-text-muted);
  padding: 0.25rem;
}

.banner__close:hover {
  color: var(--color-text-primary);
}

.banner--hidden {
  display: none;
}

/* === NAVBAR === */
.navbar {
  border-bottom: 1px solid var(--color-border);
  padding-block: var(--space-md);
}

.navbar__inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: var(--container-max);
  margin-inline: auto;
  padding-inline: var(--space-md);
}

.navbar__logo {
  display: flex;
  align-items: center;
  gap: var(--space-xs);
}

.navbar__logo-icon {
  width: 48px;
  height: 48px;
}

.navbar__logo-text {
  font-size: 1.25rem;
  font-weight: var(--font-weight-bold);
  color: var(--color-text-primary);
}

.navbar__toggle {
  display: none;
  flex-direction: column;
  gap: 5px;
  padding: 0.5rem;
}

.navbar__toggle-bar {
  width: 24px;
  height: 2px;
  background-color: var(--color-text-primary);
  border-radius: 2px;
  transition: transform var(--transition), opacity var(--transition);
}

.navbar__menu {
  display: flex;
  align-items: center;
  gap: var(--space-lg);
}

.navbar__links {
  display: flex;
  align-items: center;
  gap: var(--space-xs);
}

.navbar__link {
  padding: 0.75rem 1.5rem;
  font-size: var(--font-size-small);
  font-weight: var(--font-weight-medium);
  color: var(--color-text-secondary);
  border-radius: var(--border-radius-sm);
  transition: color var(--transition), background-color var(--transition);
}

.navbar__link:hover {
  color: var(--color-text-primary);
}

.navbar__link--active {
  color: var(--color-text-primary);
  background-color: var(--color-bg-secondary);
}

/* === NAVBAR RESPONSIVE === */
@media (max-width: 767px) {
  .navbar__toggle {
    display: flex;
  }

  .navbar__menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    flex-direction: column;
    background-color: var(--color-bg-primary);
    border-bottom: 1px solid var(--color-border);
    padding: var(--space-md);
    gap: var(--space-sm);
    z-index: 100;
  }

  .navbar__menu--open {
    display: flex;
  }

  .navbar__links {
    flex-direction: column;
    width: 100%;
  }

  .navbar__link {
    width: 100%;
    text-align: center;
  }

  .navbar__cta {
    width: 100%;
    text-align: center;
  }

  .navbar {
    position: relative;
  }
}
```

- [ ] **Step 3: Verify header renders**

Create a minimal `front-page.php` to test:

```php
<?php get_header(); ?>
<main id="main-content">
    <p style="color: white; padding: 2rem;">Homepage content goes here</p>
</main>
<?php get_footer(); ?>
```

Create a minimal `footer.php`:

```php
    <?php wp_footer(); ?>
</body>
</html>
```

Open `http://localhost:8080`. You should see the purple banner at top, then the navbar with logo, nav links, and Contact Us button. Check mobile by resizing — hamburger should appear below 768px.

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/estatein/header.php wp-content/themes/estatein/footer.php wp-content/themes/estatein/front-page.php wp-content/themes/estatein/style.css
git commit -m "feat: add header with promotional banner and responsive navigation"
```

---

## Task 5: Hero Section

**Files:**
- Create: `wp-content/themes/estatein/template-parts/hero.php`
- Modify: `wp-content/themes/estatein/front-page.php`
- Modify: `wp-content/themes/estatein/style.css` — append hero styles

- [ ] **Step 1: Create `template-parts/hero.php`**

Use Figma MCP for pixel-perfect details:
```
get_design_context(fileKey: "jew3YhbDHZCZ5A2SUsO7VK", nodeId: "46:304")
```
Focus on the hero area — first ~400px height of the homepage frame.

```php
<?php
$heading     = get_field('hero_heading') ?: 'Discover Your Dream Property with Estatein';
$description = get_field('hero_description') ?: 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.';
$primary_text = get_field('hero_primary_cta_text') ?: 'Browse Properties';
$primary_url  = get_field('hero_primary_cta_url') ?: '#properties';
$secondary_text = get_field('hero_secondary_cta_text') ?: 'Learn More';
$secondary_url  = get_field('hero_secondary_cta_url') ?: '#about';
$hero_image  = get_field('hero_image');
?>

<section class="hero" aria-label="<?php esc_attr_e('Hero', 'estatein'); ?>">
    <div class="hero__inner container">
        <div class="hero__content">
            <h1 class="hero__heading"><?php echo esc_html($heading); ?></h1>
            <p class="hero__description"><?php echo esc_html($description); ?></p>
            <div class="hero__actions">
                <a href="<?php echo esc_url($secondary_url); ?>" class="btn btn--outline"><?php echo esc_html($secondary_text); ?></a>
                <a href="<?php echo esc_url($primary_url); ?>" class="btn btn--primary"><?php echo esc_html($primary_text); ?></a>
            </div>
            <div class="hero__stats">
                <div class="hero__stat">
                    <span class="hero__stat-number">200+</span>
                    <span class="hero__stat-label">Happy Customers</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">10k+</span>
                    <span class="hero__stat-label">Properties For Clients</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">16+</span>
                    <span class="hero__stat-label">Years of Experience</span>
                </div>
            </div>
        </div>
        <div class="hero__image-wrapper">
            <?php if ($hero_image) : ?>
                <img
                    src="<?php echo esc_url($hero_image['sizes']['hero-image'] ?? $hero_image['url']); ?>"
                    alt="<?php echo esc_attr($hero_image['alt'] ?: 'Modern luxury property'); ?>"
                    width="800"
                    height="600"
                    class="hero__image"
                >
            <?php else : ?>
                <div class="hero__image hero__image--placeholder" role="img" aria-label="Modern luxury property">
                </div>
            <?php endif; ?>
            <div class="hero__badge" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>
</section>
```

- [ ] **Step 2: Update `front-page.php` to include the hero**

```php
<?php get_header(); ?>
<main id="main-content">
    <?php get_template_part('template-parts/hero'); ?>
</main>
<?php get_footer(); ?>
```

- [ ] **Step 3: Append hero CSS to `style.css`**

```css
/* === HERO === */
.hero {
  padding-block: var(--space-xl) var(--space-lg);
  border-bottom: 1px solid var(--color-border);
}

.hero__inner {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-lg);
  align-items: center;
}

.hero__heading {
  margin-bottom: var(--space-md);
}

.hero__description {
  margin-bottom: var(--space-lg);
  max-width: 540px;
}

.hero__actions {
  display: flex;
  gap: var(--space-sm);
  margin-bottom: var(--space-lg);
}

.hero__stats {
  display: flex;
  gap: var(--space-sm);
}

.hero__stat {
  flex: 1;
  padding: var(--space-md);
  background-color: var(--color-bg-secondary);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius);
  text-align: center;
}

.hero__stat-number {
  display: block;
  font-size: var(--font-size-h2);
  font-weight: var(--font-weight-bold);
  color: var(--color-text-primary);
}

.hero__stat-label {
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
}

.hero__image-wrapper {
  position: relative;
}

.hero__image {
  width: 100%;
  height: auto;
  border-radius: var(--border-radius);
  object-fit: cover;
  aspect-ratio: 4 / 3;
}

.hero__image--placeholder {
  width: 100%;
  aspect-ratio: 4 / 3;
  background: linear-gradient(135deg, var(--color-bg-tertiary) 0%, var(--color-accent-subtle) 100%);
  border-radius: var(--border-radius);
}

.hero__badge {
  position: absolute;
  top: var(--space-md);
  left: calc(var(--space-md) * -1);
  width: 60px;
  height: 60px;
  background-color: var(--color-bg-secondary);
  border: 1px solid var(--color-border);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-text-primary);
}

/* === HERO RESPONSIVE === */
@media (max-width: 1023px) {
  .hero__inner {
    grid-template-columns: 1fr;
  }

  .hero__stats {
    flex-wrap: wrap;
  }

  .hero__stat {
    flex: 1 1 calc(50% - var(--space-xs));
    min-width: 0;
  }
}

@media (max-width: 767px) {
  .hero {
    padding-block: var(--space-lg);
  }

  .hero__actions {
    flex-direction: column;
  }

  .hero__stats {
    flex-direction: column;
  }

  .hero__stat {
    flex: 1 1 100%;
  }

  .hero__badge {
    display: none;
  }
}
```

- [ ] **Step 4: Verify hero in browser**

Open `http://localhost:8080`. You should see:
- Two-column layout: heading + description + buttons on left, placeholder image on right
- Stats row below the heading with 3 bordered cards
- On mobile (resize to < 768px): stacked layout, buttons and stats go full-width

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/template-parts/hero.php wp-content/themes/estatein/front-page.php wp-content/themes/estatein/style.css
git commit -m "feat: add hero section with heading, CTAs, stats, and responsive layout"
```

---

## Task 6: Features Section

**Files:**
- Create: `wp-content/themes/estatein/template-parts/features.php`
- Modify: `wp-content/themes/estatein/front-page.php`
- Modify: `wp-content/themes/estatein/style.css`

- [ ] **Step 1: Create `template-parts/features.php`**

Use Figma MCP: `get_design_context(fileKey: "jew3YhbDHZCZ5A2SUsO7VK", nodeId: "87:1301")` for the features container.

```php
<section class="features" aria-label="<?php esc_attr_e('Our Features', 'estatein'); ?>">
    <div class="features__grid container">
        <div class="feature-card card">
            <div class="feature-card__icon-wrapper">
                <svg class="feature-card__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 21V9L12 3L21 9V21H15V15H9V21H3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="feature-card__arrow" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            <h3 class="feature-card__title">Find Your Dream Home</h3>
            <p class="feature-card__description">Effortlessly browse through a curated selection of properties that match your unique preferences and lifestyle.</p>
        </div>

        <div class="feature-card card">
            <div class="feature-card__icon-wrapper">
                <svg class="feature-card__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 2L15 8.5L22 9.5L17 14.5L18 21.5L12 18.5L6 21.5L7 14.5L2 9.5L9 8.5L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="feature-card__arrow" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            <h3 class="feature-card__title">Unlock Property Value</h3>
            <p class="feature-card__description">Our expert team provides comprehensive valuations, ensuring you get the most accurate assessment of your property.</p>
        </div>

        <div class="feature-card card">
            <div class="feature-card__icon-wrapper">
                <svg class="feature-card__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M9 12H15M12 9V15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="feature-card__arrow" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            <h3 class="feature-card__title">Effortless Property Management</h3>
            <p class="feature-card__description">Experience hassle-free property management with our team of skilled professionals dedicated to your investment.</p>
        </div>

        <div class="feature-card card">
            <div class="feature-card__icon-wrapper">
                <svg class="feature-card__icon" width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="feature-card__arrow" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            <h3 class="feature-card__title">Smart Investments, Informed Decisions</h3>
            <p class="feature-card__description">Gain insights into the real estate market with our data-driven approach and make smarter investment decisions.</p>
        </div>
    </div>
</section>
```

- [ ] **Step 2: Update `front-page.php`**

```php
<?php get_header(); ?>
<main id="main-content">
    <?php get_template_part('template-parts/hero'); ?>
    <?php get_template_part('template-parts/features'); ?>
</main>
<?php get_footer(); ?>
```

- [ ] **Step 3: Append features CSS to `style.css`**

```css
/* === FEATURES === */
.features {
  padding-block: var(--space-xs);
}

.features__grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;
}

.feature-card {
  padding: var(--space-lg) var(--space-md);
  text-align: center;
  border-radius: 0;
  border-right: 1px solid var(--color-border);
  border-top: 1px solid var(--color-border);
  border-bottom: 1px solid var(--color-border);
  background-color: transparent;
}

.feature-card:first-child {
  border-left: 1px solid var(--color-border);
}

.feature-card__icon-wrapper {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  margin-bottom: var(--space-md);
  position: relative;
}

.feature-card__icon {
  width: 48px;
  height: 48px;
  padding: 12px;
  background-color: var(--color-accent-subtle);
  border-radius: 50%;
  color: var(--color-accent);
}

.feature-card__arrow {
  position: absolute;
  top: -4px;
  right: -12px;
  color: var(--color-text-muted);
}

.feature-card__title {
  margin-bottom: var(--space-xs);
}

.feature-card__description {
  font-size: var(--font-size-small);
}

/* === FEATURES RESPONSIVE === */
@media (max-width: 1023px) {
  .features__grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .feature-card {
    border-left: 1px solid var(--color-border);
  }
}

@media (max-width: 767px) {
  .features__grid {
    grid-template-columns: 1fr;
  }
}
```

- [ ] **Step 4: Verify in browser**

4 feature cards in a row on desktop. 2-col on tablet sizes. 1-col on mobile. Each card has an icon, arrow accent, title, description.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/template-parts/features.php wp-content/themes/estatein/front-page.php wp-content/themes/estatein/style.css
git commit -m "feat: add features section with 4 icon cards and responsive grid"
```

---

## Task 7: Featured Properties Section

**Files:**
- Create: `wp-content/themes/estatein/template-parts/featured-properties.php`
- Modify: `wp-content/themes/estatein/front-page.php`
- Modify: `wp-content/themes/estatein/style.css`

- [ ] **Step 1: Create `template-parts/featured-properties.php`**

Use Figma MCP: `get_design_context(fileKey: "jew3YhbDHZCZ5A2SUsO7VK", nodeId: "87:1301")` — the "Container" below the hero that holds Featured Properties.

```php
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
                    $price    = get_field('property_price');
                    $beds     = get_field('property_bedrooms');
                    $baths    = get_field('property_bathrooms');
                    $type     = get_field('property_type');
                    $short    = get_field('property_description_short');
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
```

- [ ] **Step 2: Update `front-page.php`**

```php
<?php get_header(); ?>
<main id="main-content">
    <?php get_template_part('template-parts/hero'); ?>
    <?php get_template_part('template-parts/features'); ?>
    <?php get_template_part('template-parts/featured-properties'); ?>
</main>
<?php get_footer(); ?>
```

- [ ] **Step 3: Append property card CSS to `style.css`**

```css
/* === PROPERTY GRID === */
.property-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-md);
}

/* === PROPERTY CARD === */
.property-card__image {
  padding: var(--space-sm);
  padding-bottom: 0;
}

.property-card__img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: var(--border-radius-sm);
}

.property-card__img--placeholder {
  background: linear-gradient(135deg, var(--color-bg-tertiary), var(--color-accent-subtle));
}

.property-card__body {
  padding: var(--space-md);
}

.property-card__title {
  margin-bottom: var(--space-xs);
}

.property-card__description {
  font-size: var(--font-size-small);
  margin-bottom: var(--space-md);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.property-card__readmore {
  color: var(--color-text-primary);
  text-decoration: underline;
  text-underline-offset: 2px;
  font-weight: var(--font-weight-medium);
  margin-left: 0.25rem;
}

.property-card__readmore:hover {
  color: var(--color-accent);
}

.property-card__meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-xs);
  margin-bottom: var(--space-md);
}

.property-card__meta-item {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.375rem 0.75rem;
  background-color: var(--color-bg-primary);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius-pill);
  font-size: var(--font-size-xs);
  color: var(--color-text-secondary);
  white-space: nowrap;
}

.property-card__meta-item svg {
  flex-shrink: 0;
  color: var(--color-text-muted);
}

.property-card__footer {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: var(--space-sm);
}

.property-card__price-label {
  display: block;
  font-size: var(--font-size-xs);
  color: var(--color-text-secondary);
}

.property-card__price-value {
  font-size: var(--font-size-h3);
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
}

/* === PROPERTY GRID RESPONSIVE === */
@media (max-width: 1023px) {
  .property-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 767px) {
  .property-grid {
    grid-template-columns: 1fr;
  }
}
```

- [ ] **Step 4: Verify in browser**

You should see 3 property cards with images (or placeholders), titles, descriptions, metadata pills, prices, and CTA buttons. Pagination below.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/template-parts/featured-properties.php wp-content/themes/estatein/front-page.php wp-content/themes/estatein/style.css
git commit -m "feat: add featured properties section with CPT query and responsive card grid"
```

---

## Task 8: Testimonials Section

**Files:**
- Create: `wp-content/themes/estatein/template-parts/testimonials.php`
- Modify: `wp-content/themes/estatein/front-page.php`
- Modify: `wp-content/themes/estatein/style.css`

- [ ] **Step 1: Create `template-parts/testimonials.php`**

```php
<?php
$testimonials = [
    [
        'rating'   => 5,
        'title'    => 'Exceptional Service!',
        'text'     => 'Our experience with Estatein was outstanding. Their team\'s dedication and professionalism made finding our dream home a breeze. Highly recommended!',
        'name'     => 'Wade Warren',
        'location' => 'USA, California',
    ],
    [
        'rating'   => 5,
        'title'    => 'Efficient and Reliable',
        'text'     => 'Estatein provided us with top-notch service. They helped us sell our property quickly and at a great price. We couldn\'t be happier with the results.',
        'name'     => 'Emelie Thomson',
        'location' => 'USA, Florida',
    ],
    [
        'rating'   => 5,
        'title'    => 'Trusted Advisors',
        'text'     => 'The Estatein team guided us through the entire buying process. Their knowledge and commitment to our needs were impressive. Thank you for your support!',
        'name'     => 'John Mans',
        'location' => 'USA, Nevada',
    ],
];
?>

<section class="section testimonials" aria-label="<?php esc_attr_e('Client Testimonials', 'estatein'); ?>">
    <div class="container">
        <div class="section__header">
            <div>
                <div class="section__icon" aria-hidden="true">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
                    <svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
                    <svg width="8" height="8" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
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
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 1L12.5 6.5L18.5 7.5L14.25 11.75L15.25 17.75L10 15L4.75 17.75L5.75 11.75L1.5 7.5L7.5 6.5L10 1Z" fill="var(--color-star)"/></svg>
                        <?php endfor; ?>
                    </div>
                    <h3 class="testimonial-card__title"><?php echo esc_html($testimonial['title']); ?></h3>
                    <p class="testimonial-card__text"><?php echo esc_html($testimonial['text']); ?></p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar" aria-hidden="true">
                            <?php echo esc_html(mb_substr($testimonial['name'], 0, 1)); ?>
                        </div>
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
```

- [ ] **Step 2: Update `front-page.php`**

```php
<?php get_header(); ?>
<main id="main-content">
    <?php get_template_part('template-parts/hero'); ?>
    <?php get_template_part('template-parts/features'); ?>
    <?php get_template_part('template-parts/featured-properties'); ?>
    <?php get_template_part('template-parts/testimonials'); ?>
</main>
<?php get_footer(); ?>
```

- [ ] **Step 3: Append testimonial CSS to `style.css`**

```css
/* === TESTIMONIALS === */
.testimonials__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-md);
}

.testimonial-card {
  padding: var(--space-lg) var(--space-md);
  display: flex;
  flex-direction: column;
  gap: var(--space-md);
}

.testimonial-card__stars {
  display: flex;
  gap: 0.25rem;
}

.testimonial-card__title {
  font-size: var(--font-size-h3);
}

.testimonial-card__text {
  font-size: var(--font-size-small);
  flex-grow: 1;
}

.testimonial-card__author {
  display: flex;
  align-items: center;
  gap: var(--space-sm);
  padding-top: var(--space-md);
  border-top: 1px solid var(--color-border);
}

.testimonial-card__avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background-color: var(--color-bg-tertiary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
  flex-shrink: 0;
}

.testimonial-card__name {
  display: block;
  font-weight: var(--font-weight-medium);
  color: var(--color-text-primary);
}

.testimonial-card__location {
  display: block;
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
}

/* === TESTIMONIALS RESPONSIVE === */
@media (max-width: 1023px) {
  .testimonials__grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 767px) {
  .testimonials__grid {
    grid-template-columns: 1fr;
  }
}
```

- [ ] **Step 4: Verify in browser**

3 testimonial cards with star ratings, titles, review text, and author info. Responsive collapse to 1 column on mobile.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/template-parts/testimonials.php wp-content/themes/estatein/front-page.php wp-content/themes/estatein/style.css
git commit -m "feat: add testimonials section with review cards and star ratings"
```

---

## Task 9: FAQ Section

**Files:**
- Create: `wp-content/themes/estatein/template-parts/faq.php`
- Modify: `wp-content/themes/estatein/front-page.php`
- Modify: `wp-content/themes/estatein/style.css`

- [ ] **Step 1: Create `template-parts/faq.php`**

```php
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
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
                    <svg width="10" height="10" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
                    <svg width="8" height="8" viewBox="0 0 16 16" fill="none"><path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/></svg>
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
            <span class="pagination__text">01 of 10</span>
            <div class="pagination__arrows">
                <button class="pagination__arrow" aria-label="<?php esc_attr_e('Previous questions', 'estatein'); ?>">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="pagination__arrow" aria-label="<?php esc_attr_e('Next questions', 'estatein'); ?>">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
```

- [ ] **Step 2: Update `front-page.php`**

```php
<?php get_header(); ?>
<main id="main-content">
    <?php get_template_part('template-parts/hero'); ?>
    <?php get_template_part('template-parts/features'); ?>
    <?php get_template_part('template-parts/featured-properties'); ?>
    <?php get_template_part('template-parts/testimonials'); ?>
    <?php get_template_part('template-parts/faq'); ?>
</main>
<?php get_footer(); ?>
```

- [ ] **Step 3: Append FAQ CSS to `style.css`**

```css
/* === FAQ === */
.faq__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-md);
}

.faq-card {
  padding: 0;
}

.faq-card__body {
  padding: var(--space-lg) var(--space-md);
}

.faq-card__question {
  margin-bottom: var(--space-sm);
}

.faq-card__answer {
  font-size: var(--font-size-small);
  margin-bottom: var(--space-md);
}

.faq-card__link {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: var(--font-size-small);
  font-weight: var(--font-weight-medium);
  color: var(--color-text-primary);
  padding: 0.5rem 1rem;
  background-color: var(--color-bg-primary);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius-sm);
  transition: background-color var(--transition);
}

.faq-card__link:hover {
  background-color: var(--color-bg-tertiary);
}

/* === FAQ RESPONSIVE === */
@media (max-width: 1023px) {
  .faq__grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 767px) {
  .faq__grid {
    grid-template-columns: 1fr;
  }
}
```

- [ ] **Step 4: Verify in browser**

3 FAQ cards showing questions, short answers, and "Read More" links. Responsive.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/template-parts/faq.php wp-content/themes/estatein/front-page.php wp-content/themes/estatein/style.css
git commit -m "feat: add FAQ section with question cards and responsive grid"
```

---

## Task 10: CTA Section

**Files:**
- Create: `wp-content/themes/estatein/template-parts/cta.php`
- Modify: `wp-content/themes/estatein/front-page.php`
- Modify: `wp-content/themes/estatein/style.css`

- [ ] **Step 1: Create `template-parts/cta.php`**

```php
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
```

- [ ] **Step 2: Update `front-page.php`**

```php
<?php get_header(); ?>
<main id="main-content">
    <?php get_template_part('template-parts/hero'); ?>
    <?php get_template_part('template-parts/features'); ?>
    <?php get_template_part('template-parts/featured-properties'); ?>
    <?php get_template_part('template-parts/testimonials'); ?>
    <?php get_template_part('template-parts/faq'); ?>
    <?php get_template_part('template-parts/cta'); ?>
</main>
<?php get_footer(); ?>
```

- [ ] **Step 3: Append CTA CSS to `style.css`**

```css
/* === CTA === */
.cta {
  padding-block: var(--space-xl);
  background-color: var(--color-bg-secondary);
  border-top: 1px solid var(--color-border);
  border-bottom: 1px solid var(--color-border);
}

.cta__inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-lg);
}

.cta__heading {
  margin-bottom: var(--space-sm);
}

.cta__description {
  max-width: 800px;
}

.cta__button {
  flex-shrink: 0;
}

/* === CTA RESPONSIVE === */
@media (max-width: 767px) {
  .cta__inner {
    flex-direction: column;
    text-align: center;
  }

  .cta__button {
    width: 100%;
  }
}
```

- [ ] **Step 4: Verify in browser**

Full-width dark strip with heading, description on left, purple CTA button on right.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/estatein/template-parts/cta.php wp-content/themes/estatein/front-page.php wp-content/themes/estatein/style.css
git commit -m "feat: add CTA section with ACF-powered content"
```

---

## Task 11: Footer

**Files:**
- Modify: `wp-content/themes/estatein/footer.php`
- Modify: `wp-content/themes/estatein/style.css`

- [ ] **Step 1: Replace `footer.php` with full implementation**

```php
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
```

- [ ] **Step 2: Append footer CSS to `style.css`**

```css
/* === FOOTER === */
.site-footer {
  background-color: var(--color-bg-secondary);
  border-top: 1px solid var(--color-border);
  padding-top: var(--space-xl);
}

.footer__top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: var(--space-lg);
  padding-bottom: var(--space-xl);
  border-bottom: 1px solid var(--color-border);
}

.footer__brand {
  display: flex;
  flex-direction: column;
  gap: var(--space-md);
}

.footer__logo {
  display: flex;
  align-items: center;
  gap: var(--space-xs);
}

.footer__logo-text {
  font-size: 1.25rem;
  font-weight: var(--font-weight-bold);
  color: var(--color-text-primary);
}

.footer__subscribe {
  display: flex;
  align-items: center;
  background-color: var(--color-bg-primary);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius-sm);
  overflow: hidden;
}

.footer__input {
  padding: 0.75rem 1rem;
  width: 220px;
  font-size: var(--font-size-small);
  color: var(--color-text-primary);
  background: transparent;
}

.footer__input::placeholder {
  color: var(--color-text-muted);
}

.footer__submit {
  padding: 0.75rem 1rem;
  color: var(--color-text-primary);
  transition: color var(--transition);
}

.footer__submit:hover {
  color: var(--color-accent);
}

.footer__social {
  display: flex;
  gap: var(--space-xs);
}

.footer__social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background-color: var(--color-bg-primary);
  border: 1px solid var(--color-border);
  color: var(--color-text-secondary);
  transition: color var(--transition), background-color var(--transition);
}

.footer__social-link:hover {
  color: var(--color-text-primary);
  background-color: var(--color-bg-tertiary);
}

/* === FOOTER NAV === */
.footer__nav {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: var(--space-lg);
  padding-block: var(--space-xl);
  border-bottom: 1px solid var(--color-border);
}

.footer__nav-title {
  font-size: var(--font-size-body);
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
  margin-bottom: var(--space-md);
}

.footer__nav-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-xs);
}

.footer__nav-link {
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
  transition: color var(--transition);
}

.footer__nav-link:hover {
  color: var(--color-text-primary);
}

/* === FOOTER BOTTOM === */
.footer__bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-block: var(--space-md);
}

.footer__copyright {
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
}

.footer__terms {
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
  transition: color var(--transition);
}

.footer__terms:hover {
  color: var(--color-text-primary);
}

/* === FOOTER RESPONSIVE === */
@media (max-width: 1023px) {
  .footer__nav {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 767px) {
  .footer__top {
    flex-direction: column;
  }

  .footer__nav {
    grid-template-columns: repeat(2, 1fr);
  }

  .footer__bottom {
    flex-direction: column;
    gap: var(--space-sm);
    text-align: center;
  }

  .footer__input {
    width: 160px;
  }
}
```

- [ ] **Step 3: Verify in browser**

Full footer with logo, subscribe form, social icons, 5 nav columns, and copyright bar. Responsive.

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/estatein/footer.php wp-content/themes/estatein/style.css
git commit -m "feat: add footer with subscribe form, nav columns, social links"
```

---

## Task 12: JavaScript Interactions

**Files:**
- Create: `wp-content/themes/estatein/assets/js/main.js`

- [ ] **Step 1: Create `assets/js/main.js`**

```javascript
document.addEventListener('DOMContentLoaded', function () {
    var bannerClose = document.getElementById('banner-close');
    var banner = document.getElementById('site-banner');

    if (bannerClose && banner) {
        bannerClose.addEventListener('click', function () {
            banner.classList.add('banner--hidden');
        });
    }

    var navToggle = document.getElementById('nav-toggle');
    var navMenu = document.getElementById('nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            var isOpen = navMenu.classList.toggle('navbar__menu--open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });

        document.addEventListener('click', function (e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('navbar__menu--open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var targetId = this.getAttribute('href');
            if (targetId === '#') return;

            var target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                if (navMenu) {
                    navMenu.classList.remove('navbar__menu--open');
                    navToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
});
```

- [ ] **Step 2: Verify interactions in browser**

1. Click the X on the banner — it should disappear
2. Resize to mobile — hamburger appears, clicking it opens the nav drawer
3. Click outside the nav drawer — it closes
4. Click an anchor link — smooth scroll to section

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/estatein/assets/js/main.js
git commit -m "feat: add JS for banner dismiss, mobile nav toggle, and smooth scroll"
```

---

## Task 13: 404 Page + Screenshot

**Files:**
- Create: `wp-content/themes/estatein/404.php`

- [ ] **Step 1: Create `404.php`**

```php
<?php get_header(); ?>
<main id="main-content" class="section">
    <div class="container" style="text-align: center; padding-block: var(--space-2xl);">
        <h1>404</h1>
        <p style="margin-top: var(--space-md); margin-bottom: var(--space-lg);">The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">Back to Home</a>
    </div>
</main>
<?php get_footer(); ?>
```

- [ ] **Step 2: Take a screenshot of the running homepage**

Open `http://localhost:8080` and take a full-page screenshot. Save it as `wp-content/themes/estatein/screenshot.png` (must be 1200x900 per WP guidelines for theme thumbnail).

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/estatein/404.php wp-content/themes/estatein/screenshot.png
git commit -m "feat: add 404 page and theme screenshot"
```

---

## Task 14: README Documentation

**Files:**
- Create: `README.md`

- [ ] **Step 1: Create `README.md`**

```markdown
# Estatein — Real Estate WordPress Theme

A custom dark-themed WordPress theme for the Estatein real estate brand, built as a Growmodo developer assessment.

## Quick Start

### Prerequisites

- Docker & Docker Compose
- Git

### Setup

1. Clone the repository:

   ```bash
   git clone <repo-url>
   cd growmodo
   ```

2. Copy environment file:

   ```bash
   cp .env.example .env
   ```

3. Start the containers:

   ```bash
   docker compose up -d
   ```

4. Open `http://localhost:8080` and complete WordPress setup:
   - Site Title: **Estatein**
   - Username: **admin**
   - Password: **admin**

5. Activate the **Estatein** theme in Appearance → Themes.

6. (Optional) Install **Advanced Custom Fields** plugin for content management fields.

7. Set the homepage: Settings → Reading → "A static page" → select any page as Homepage.

8. Add sample properties: Properties → Add New (add 3 with title, featured image, and fill in ACF fields for price, bedrooms, bathrooms, type).

## Development Process

### Architecture Decisions

- **Classic theme** over Block/FSE theme — gives full control for pixel-perfect design matching and demonstrates core WordPress proficiency (PHP templating, the Loop, template hierarchy).
- **Vanilla CSS with custom properties** — no build tooling overhead. CSS custom properties provide the dark theme token system (colors, spacing, typography) with responsive `clamp()` values for fluid typography.
- **Template parts** per section — clean separation of concerns. Each homepage section (hero, features, properties, testimonials, FAQ, CTA) is isolated in its own PHP file.
- **ACF Free** for content management fields — hero content, CTA content, and property metadata are all editable from the WordPress admin.
- **Docker Compose** — reproducible local environment with WordPress 6.7 + MySQL 8. Theme files are volume-mounted for live editing.

### Theme Structure

| File | Purpose |
|---|---|
| `style.css` | Theme metadata + all CSS (tokens, reset, components, responsive) |
| `functions.php` | Theme bootstrap, includes |
| `front-page.php` | Homepage template |
| `header.php` | Banner + responsive navigation |
| `footer.php` | Newsletter subscribe, nav columns, social links |
| `template-parts/*.php` | Individual homepage sections |
| `inc/theme-setup.php` | Theme supports, menus, image sizes, enqueues |
| `inc/custom-post-types.php` | Property CPT registration |
| `inc/acf-fields.php` | ACF field group definitions |
| `assets/js/main.js` | Banner dismiss, nav toggle, smooth scroll |

### Plugins Used

- **Advanced Custom Fields (Free)** — optional, for content management fields. Theme works without it using hardcoded defaults.

### Performance Optimizations

- Single CSS file (no render-blocking external stylesheets beyond Google Fonts)
- Lazy loading on below-the-fold images
- Custom image sizes for optimal delivery
- Minimal JavaScript (~60 lines, no jQuery dependency)
- Semantic HTML5 for SEO

### Accessibility

- Skip-to-content link
- ARIA landmarks and labels on all interactive elements
- Keyboard-navigable menu
- Focus-visible styles
- Screen-reader-only utility class
- Proper heading hierarchy (H1 → H2 → H3)

## Browser Support

Tested on:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

GPL v2 or later
```

- [ ] **Step 2: Commit**

```bash
git add README.md
git commit -m "docs: add README with setup instructions and development process"
```

---

## Task 15: Pixel-Perfect Polish Pass

This is the final quality pass. Use Figma MCP to compare your implementation against the Figma design node by node.

**Files:**
- Modify: `wp-content/themes/estatein/style.css`
- Possibly modify template parts

- [ ] **Step 1: Compare hero section**

Use Figma MCP:
```
get_design_context(fileKey: "jew3YhbDHZCZ5A2SUsO7VK", nodeId: "46:304")
```

Open `http://localhost:8080` side-by-side with the Figma screenshot. Adjust:
- Exact padding/margin values
- Font sizes and weights
- Border radius values
- Color values (use browser DevTools color picker to compare)
- Image aspect ratios

- [ ] **Step 2: Compare mobile layout**

Use Figma MCP:
```
get_screenshot(fileKey: "jew3YhbDHZCZ5A2SUsO7VK", nodeId: "139:7812")
```

Resize browser to 390px width. Compare against the Figma mobile frame. Adjust:
- Mobile navigation layout
- Stacking order
- Card spacing
- Typography scaling

- [ ] **Step 3: Compare laptop layout**

Use Figma MCP:
```
get_screenshot(fileKey: "jew3YhbDHZCZ5A2SUsO7VK", nodeId: "139:6238")
```

Resize browser to 1440px. Adjust any spacing or layout differences.

- [ ] **Step 4: Fix any discovered issues**

Make CSS/PHP adjustments as needed. Each fix should be small and targeted.

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "style: pixel-perfect polish pass — adjust spacing, typography, and responsive layouts"
```

---

## Task 16: Final Verification

- [ ] **Step 1: Full page scroll test at 1920px, 1440px, 768px, 390px**

At each width, scroll the entire page and verify:
- No horizontal overflow
- All sections render correctly
- All interactive elements work (banner close, nav toggle, hover states)
- No broken images
- Typography is readable

- [ ] **Step 2: Accessibility check**

- Tab through the entire page — focus order should be logical
- Skip-to-content link works on first Tab press
- All buttons/links have visible focus indicators
- Screen reader test: heading hierarchy makes sense

- [ ] **Step 3: Performance check**

Open Chrome DevTools → Lighthouse → run Performance audit. Target:
- Performance > 80
- Accessibility > 90
- Best Practices > 90
- SEO > 90

- [ ] **Step 4: Final commit**

```bash
git add -A
git commit -m "chore: final verification and cleanup"
```
