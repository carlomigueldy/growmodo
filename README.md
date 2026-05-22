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
