# Estatein WordPress Theme — Design Spec

## Context

Growmodo GmbH WordPress Developer assessment. Convert a Figma design (Real Estate Business Website — Dark Theme) into a pixel-perfect WordPress homepage with a custom theme.

**Figma source:** https://www.figma.com/design/jew3YhbDHZCZ5A2SUsO7VK

**Scope:** Homepage only (pixel-perfect). 4-hour stress test — quality over completion.

**Evaluation criteria:** Design fidelity, functionality, code quality, performance/SEO, UX, creativity.

## Architecture

**Approach:** Classic WordPress theme with template parts. No build tooling. No page builders.

**Stack:**
- WordPress 6.7 (latest)
- PHP 8.2+
- Vanilla CSS with custom properties
- ACF Free plugin
- Docker Compose (WordPress + MySQL 8)
- Google Fonts (Urbanist)

## Project Structure

```
growmodo/
├── docker-compose.yml
├── .env
├── wp-content/
│   └── themes/
│       └── estatein/
│           ├── style.css
│           ├── functions.php
│           ├── front-page.php
│           ├── header.php
│           ├── footer.php
│           ├── screenshot.png
│           ├── template-parts/
│           │   ├── hero.php
│           │   ├── features.php
│           │   ├── featured-properties.php
│           │   ├── testimonials.php
│           │   ├── faq.php
│           │   └── cta.php
│           ├── inc/
│           │   ├── acf-fields.php
│           │   ├── custom-post-types.php
│           │   └── theme-setup.php
│           ├── assets/
│           │   ├── images/
│           │   ├── fonts/
│           │   └── js/
│           │       └── main.js
│           └── 404.php
└── README.md
```

## Design Tokens

```css
:root {
  /* Colors — Dark Theme */
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

  /* Typography */
  --font-family: 'Urbanist', sans-serif;
  --font-size-display: clamp(2.5rem, 4vw, 3.75rem);
  --font-size-h2: clamp(1.75rem, 3vw, 2.5rem);
  --font-size-h3: clamp(1.125rem, 2vw, 1.5rem);
  --font-size-body: 1rem;
  --font-size-small: 0.875rem;

  /* Spacing (8px grid) */
  --space-xs: 0.5rem;
  --space-sm: 1rem;
  --space-md: 1.5rem;
  --space-lg: 2.5rem;
  --space-xl: 5rem;
  --space-2xl: 6.25rem;

  /* Layout */
  --container-max: 1596px;
  --container-padding: 162px;
  --border-radius: 12px;
  --border-radius-sm: 8px;
  --border-radius-pill: 999px;
}
```

## Homepage Sections

### Header (`header.php`)

**Top banner:** Purple-accented promotional strip with text, "Learn More" link, close button. Background: subtle gradient/pattern.

**Navigation bar:** Logo (icon + "Estatein") left-aligned. Centered nav links: Home (active pill state), About Us, Properties, Services. Right-aligned "Contact Us" purple button with rounded corners.

**Mobile:** Hamburger toggle, collapsible nav drawer.

### Hero (`template-parts/hero.php`)

**Layout:** Two-column (left text, right image) on desktop. Stacked on mobile.

**Left column:**
- H1: "Discover Your Dream Property with Estatein"
- Paragraph description
- Two CTAs: "Learn More" (ghost/outline button), "Browse Properties" (purple filled button)
- Stats row: 3 stat boxes with large number + label (200+ Happy Customers, 10k+ Properties For Clients, 16+ Years of Experience). Bordered cards.

**Right column:** Large hero image of modern building with decorative circular badge overlay (arrow icon + text).

**ACF fields:**
- `hero_heading` (text)
- `hero_description` (textarea)
- `hero_primary_cta_text` (text)
- `hero_primary_cta_url` (url)
- `hero_secondary_cta_text` (text)
- `hero_secondary_cta_url` (url)
- `hero_image` (image)
- `hero_stats` (repeater: `stat_number` text, `stat_label` text)

### Features (`template-parts/features.php`)

**Layout:** 4 cards in a row on desktop, 2-col on tablet, 1-col on mobile.

**Each card:** Decorative icon in rounded container with small arrow accent, title, description. Cards have subtle border and dark background.

**Cards:**
1. Find Your Dream Home
2. Unlock Property Value
3. Effortless Property Management
4. Smart Investments, Informed Decisions

**ACF fields:**
- `features` (repeater: `feature_icon` text/select, `feature_title` text, `feature_description` textarea)

### Featured Properties (`template-parts/featured-properties.php`)

**Section header:** Decorative stars icon, H2 "Featured Properties", paragraph, "View All Properties" link aligned right.

**Layout:** 3 property cards on desktop, 2-col on tablet, 1-col on mobile.

**Each card:**
- Property image (top)
- Title + short description + "Read More" link
- Metadata row: bedrooms (icon + count), bathrooms (icon + count), property type (icon + label)
- Bottom row: "Price" label + dollar amount (left), "View Property Details" purple button (right)

**Pagination:** "01 of 60" text + prev/next arrow buttons.

**Data source:** `property` Custom Post Type. Query 3 most recent.

### Testimonials (`template-parts/testimonials.php`)

**Section header:** Decorative icon, H2 "What Our Clients Say", paragraph, "View All Testimonials" link.

**Layout:** 3 review cards on desktop, stacked on mobile.

**Each card:**
- 5-star rating (yellow stars)
- Title (bold)
- Review text
- Bottom: avatar image + name + location

**Pagination:** "01 of 10" + arrows.

**ACF fields:**
- `testimonials` (repeater: `testimonial_rating` number, `testimonial_title` text, `testimonial_text` textarea, `testimonial_avatar` image, `testimonial_name` text, `testimonial_location` text)

### FAQ (`template-parts/faq.php`)

**Section header:** Decorative icon, H2 "Frequently Asked Questions", paragraph, "View All FAQ's" link.

**Layout:** 3 FAQ cards on desktop, stacked on mobile.

**Each card:** Question title, short answer paragraph, "Read More" link.

**Pagination:** "01 of 10" + arrows.

**ACF fields:**
- `faqs` (repeater: `faq_question` text, `faq_answer` textarea)

### CTA (`template-parts/cta.php`)

**Layout:** Full-width dark section. Left-aligned heading + paragraph. Right-aligned "Explore Properties" purple button. Decorative background pattern.

**ACF fields:**
- `cta_heading` (text)
- `cta_description` (textarea)
- `cta_button_text` (text)
- `cta_button_url` (url)

### Footer (`footer.php`)

**Top row:** Logo + email subscribe input (with send icon) + social media icons (Facebook, Instagram, Twitter, YouTube).

**Middle row:** 5 navigation columns — Home (Hero Section, Features, Properties, Testimonials, FAQ's), About Us (Our Story, Our Works, How It Works, Our Team, Our Clients), Properties (Portfolio, Categories), Services (Valuation Mastery, Strategic Marketing, Negotiation Wizardry, Closing Success, Property Management), Contact Us (Contact Form, Our Offices).

**Bottom bar:** Copyright "©2023 Estatein. All Rights Reserved." + "Terms & Conditions" link.

## Custom Post Type

### Property (`property`)

- **Slug:** `/properties/`
- **Supports:** title, editor, thumbnail
- **ACF fields:**
  - `property_price` (number)
  - `property_bedrooms` (number)
  - `property_bathrooms` (number)
  - `property_type` (select: Villa, Apartment, Townhouse, Cottage)
  - `property_description_short` (textarea)

## Responsive Breakpoints

| Breakpoint | Width | Figma Reference |
|---|---|---|
| Desktop | ≥1440px | Home Page - Desktop (1920px) |
| Laptop | 1024px–1439px | Home Page - Laptop (1440px) |
| Tablet | 768px–1023px | Interpolated |
| Mobile | <768px | Home Page - Mobile (390px) |

**Grid behavior:**
- Property cards: 3-col → 2-col → 1-col
- Feature cards: 4-col → 2-col → 1-col
- Testimonial cards: 3-col → 1-col
- FAQ cards: 3-col → 1-col
- Hero: side-by-side → stacked
- Footer columns: 5-col → 2-col → 1-col

## Performance & SEO

- Inline critical CSS for above-the-fold content
- Lazy load images below the fold (`loading="lazy"`)
- Optimized image sizes via `add_image_size()` in theme setup
- Minified CSS/JS in production (manual or via WP plugin)
- Semantic HTML5 elements (`<header>`, `<main>`, `<section>`, `<footer>`, `<nav>`)
- Proper heading hierarchy (single H1, H2 per section, H3 for cards)
- Meta tags: title, description via `wp_head`
- Alt attributes on all images
- Open Graph meta tags for social sharing
- Schema.org structured data for RealEstateListing (bonus)

## Accessibility

- Skip-to-content link
- ARIA landmarks on major sections
- Keyboard-navigable menu and interactive elements
- Focus-visible styles on all interactive elements
- Color contrast ratio ≥4.5:1 for text (verify purple on dark)
- Alt text on all images
- Form labels on email subscribe input

## Docker Compose Setup

```yaml
services:
  wordpress:
    image: wordpress:6.7-php8.2-apache
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - wordpress_data:/var/www/html
      - ./wp-content/themes/estatein:/var/www/html/wp-content/themes/estatein
    depends_on:
      - db

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql

volumes:
  wordpress_data:
  db_data:
```

## Interactions & JavaScript

Minimal JS in `assets/js/main.js`:
- Mobile navigation toggle (hamburger menu open/close)
- Banner close button (dismiss top promotional strip)
- Smooth scroll for anchor links
- Pagination arrows: static display only (no AJAX for homepage scope)

## Deliverables Checklist

1. Custom WordPress theme source code in `wp-content/themes/estatein/`
2. `docker-compose.yml` + `.env.example` for local setup
3. `README.md` with setup instructions, dev process explanation, and tool/plugin choices
4. Screenshot of running site for quick reference
