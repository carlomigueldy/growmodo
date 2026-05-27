# README Revamp — Estatein WordPress Theme

**Status:** Approved design, ready for implementation plan.
**Date:** 2026-05-28
**Owner:** Carlo (with Forge Ops team: Atlas, Forge, Builder, Core, Lens, Probe — all Sonnet 4.6, max effort)

## Goal

Replace the current `README.md` with a portfolio-grade README that:

1. Opens with a hero screenshot of the running theme (above the fold).
2. Carries a GitHub-friendly Table of Contents.
3. Documents installation in a single, scannable, copy-paste-friendly flow with download links for the only two pieces of software a user needs (Docker, Git).
4. Includes a full-page collage screenshot as the "Demo" visual proof.
5. Preserves the existing architecture / theme structure / performance / accessibility prose, which is doing real portfolio work and should not be lost.

## Audience

Dual: **Growmodo reviewers** evaluating the assessment, and **developers visiting the public repo** as a portfolio piece. The README should read like a polished marketing document on the top half and like a thorough engineering document on the bottom half.

## Non-goals

- Not adding a Roadmap / Credits / Changelog section.
- Not adding per-OS install nuances (no WSL2 notes, no per-OS Docker links). Carlo wants a single, clean install path.
- Not introducing build tooling, asset pipelines, or new dependencies — the theme remains vanilla CSS + PHP.
- Not relocating documentation to a separate `ARCHITECTURE.md` (Approach C was rejected).
- Not capturing screenshots from Figma. The screenshots must be from the live running site.

## Final README structure

The README is restructured into the following ordered sections. Each level-2 heading below corresponds to a level-2 (`##`) heading in the final file. Headings marked **(new)** are introduced by this revamp; headings marked **(kept)** preserve content already in the current README.

| # | Section                  | Origin   | Notes                                                                                  |
|---|--------------------------|----------|----------------------------------------------------------------------------------------|
| 0 | Hero screenshot          | new      | Full-bleed PNG embedded at the top of the file, *before* the H1.                       |
| 1 | H1 + tagline             | rewrite  | Title + 2-line blockquote tagline.                                                     |
| 2 | Badges                   | new      | Shields.io badges: WordPress 6.7, PHP 8.2, Docker, License GPL-2.0.                    |
| 3 | Table of Contents        | new      | Anchor list of all level-2 sections below.                                              |
| 4 | Overview                 | rewrite  | 3-line description + Figma source link, tighter than current intro.                     |
| 5 | Demo                     | new      | Holds the collage screenshot — every section stitched into one tall PNG.                |
| 6 | Features                 | new      | 5 bullets summarising the headline engineering work.                                    |
| 7 | Tech Stack               | new      | Markdown table: CMS / Runtime / DB / Styling / Content / Local env.                    |
| 8 | Prerequisites            | rewrite  | Docker Desktop + Git, each with a markdown download link.                              |
| 9 | Installation             | rewrite  | Single numbered list (1–9), copy-paste-friendly, includes stop command.                |
|10 | Architecture Decisions   | kept     | Verbatim from existing README, no edits.                                                |
|11 | Theme Structure          | kept     | Verbatim — the existing file/purpose table.                                             |
|12 | Content & Data           | new      | CPT + ACF field groups + accurate note on ACF Free vs Pro behavior. See section below. |
|13 | Performance              | kept     | Verbatim — renamed from "Performance Optimizations" to "Performance" for TOC tidiness. |
|14 | Accessibility            | kept     | Verbatim from existing README.                                                          |
|15 | Browser Support          | kept     | Compressed to a single line.                                                            |
|16 | Author                   | new      | Carlo Miguel Dy + carlomigueldy.com + 1-line assessment framing.                       |
|17 | License                  | kept     | One line: GPL v2 or later.                                                              |

## Detailed section content

### 0. Hero screenshot

- Format: PNG, captured live from `http://localhost:8080` after the theme is activated and sample data is loaded.
- Capture method: Chrome DevTools MCP, viewport set to a desktop width (1440 × ~900).
- Scope: above-the-fold only (the hero band with heading, CTAs, stats).
- File path: `docs/screenshots/hero.png`.
- Markdown: `![Estatein homepage hero](docs/screenshots/hero.png)`.
- Sized to fit GitHub's content width naturally — no explicit width attribute.

### 1. Title + tagline

```markdown
# Estatein — Real Estate WordPress Theme

> Pixel-perfect dark-themed WordPress theme matching the Estatein Figma design.
> Built for the Growmodo developer assessment.
```

### 2. Badges

Use Shields.io static badges (no external API calls beyond the standard `img.shields.io` URL pattern). Four badges, single line:

- WordPress 6.7 (blue)
- PHP 8.2 (purple)
- Docker (informational/blue)
- License GPL-2.0 (green)

### 3. Table of Contents

A bulleted anchor list pointing at every level-2 heading from "Overview" down to "License". GitHub auto-generates the lowercase-hyphenated anchors; no manual id wiring needed. Final order:

- [Overview](#overview)
- [Demo](#demo)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Architecture Decisions](#architecture-decisions)
- [Theme Structure](#theme-structure)
- [Content & Data](#content--data)
- [Performance](#performance)
- [Accessibility](#accessibility)
- [Browser Support](#browser-support)
- [Author](#author)
- [License](#license)

### 4. Overview

Replaces the current opening paragraph. Three lines + a Figma source link.

> A custom dark-themed WordPress theme for the Estatein real estate brand. Single homepage with hero, features, properties, testimonials, FAQ, and CTA sections — all editable via Advanced Custom Fields. Vanilla CSS, no build tooling, runs entirely in Docker.

**Figma source:** [Real Estate Business Website — Dark Theme](https://www.figma.com/design/jew3YhbDHZCZ5A2SUsO7VK)

### 5. Demo

- Format: PNG, tall composite of all homepage sections stitched vertically.
- Capture method: Chrome DevTools MCP full-page screenshot (`fullPage: true` equivalent).
- File path: `docs/screenshots/collage.png`.
- Markdown: `![Estatein full homepage](docs/screenshots/collage.png)`.

### 6. Features

Five bullets, terse:

- Pixel-perfect Figma-to-code with responsive breakpoints (390px / 1440px / desktop)
- Dark theme tokens via CSS custom properties + fluid typography with `clamp()`
- Custom Post Type for property listings with ACF metadata
- Header banner dismiss, mobile nav toggle, newsletter subscribe, smooth scroll
- Vanilla JS only (no jQuery), single CSS file, semantic HTML5

### 7. Tech Stack

```markdown
| Layer    | Tool                              |
|----------|-----------------------------------|
| CMS      | WordPress 6.7                     |
| Runtime  | PHP 8.2                           |
| Database | MySQL 8.0                         |
| Styling  | Vanilla CSS + custom properties   |
| Content  | Advanced Custom Fields (free)     |
| Local    | Docker Compose                    |
```

### 8. Prerequisites

```markdown
- **Docker Desktop** — [Download](https://www.docker.com/products/docker-desktop/)
- **Git** — [Download](https://git-scm.com/downloads)

That's it. Everything else (WordPress, MySQL, PHP) runs in Docker containers.
```

### 9. Installation

A single numbered list. Each step is one action; commands are fenced.

```markdown
1. Clone and enter the repo:
   ```bash
   git clone <repo-url>
   cd growmodo
   ```

2. Copy the env file:
   ```bash
   cp .env.example .env
   ```

3. Start the stack:
   ```bash
   docker compose up -d
   ```
   First run pulls images (~600 MB). WordPress will be on **:8080**, MySQL on **:3306**.

4. Visit <http://localhost:8080> and finish the WordPress installer:
   - Site Title: **Estatein**
   - Username: **admin**
   - Password: **admin** (or anything you'll remember)
   - Email: any address

5. In **wp-admin** (<http://localhost:8080/wp-admin>), go to **Appearance → Themes** and activate **Estatein**.

6. *(Recommended)* **Plugins → Add New** → search for **Advanced Custom Fields**, install + activate. The theme falls back to hardcoded defaults without it.

7. **Settings → Reading** → choose **A static page** → pick any page as the Homepage.

8. **Properties → Add New** → add ~3 sample listings (title, featured image, and ACF fields: price, bedrooms, bathrooms, type).

9. Refresh <http://localhost:8080>. Done.

To stop the stack:
```bash
docker compose down
```
```

### 10. Architecture Decisions (kept)

Verbatim from existing README — no edits to the prose.

### 11. Theme Structure (kept)

Verbatim file/purpose table from existing README.

### 12. Content & Data (new)

A new section sitting between **Theme Structure** and **Performance**. Documents the data model — the Custom Post Type and the ACF field groups — so reviewers can see the WordPress data layer at a glance. Markdown shape:

```markdown
## Content & Data

**Property CPT** — registered as `property`, slug `/properties/`. Supports title, editor, featured image. The homepage queries the 3 most recent published properties.

**ACF field groups:**
- Homepage Hero — heading, description, primary/secondary CTAs, hero image, stats
- Homepage Features — section title, feature cards
- Featured Properties — section title, description
- Testimonials — section title, review cards
- FAQ — section title, questions
- Homepage CTA — heading, description, button text/URL
- Property Details (on the Property CPT) — price, bedrooms, bathrooms, type, short description
```

> ⚠ **Implementation note for Builder (not part of README content):** the theme's ACF field groups in `inc/acf-fields.php` use `repeater` fields, which require **ACF Pro** to render in the admin UI. The current README claims "ACF Free" — Builder must reconcile this before writing the README. Two options: (1) state ACF Pro is required for editable lists and ACF Free still works because the template parts have hardcoded defaults; or (2) verify by running locally with ACF Free and document the actual observed behavior. Pick option (2) — verify, then write the truthful version.

### 13. Performance (kept, heading renamed)

Rename heading from "Performance Optimizations" → "Performance" so the TOC stays tidy. The TOC anchor `#performance` in section 3 reflects this rename. Body content (the five bullet items) is unchanged.

### 14. Accessibility (kept)

Verbatim from existing README.

### 15. Browser Support (kept, compressed)

Replace the existing multi-bullet block with one line: `Tested on Chrome, Firefox, Safari, and Edge (latest).`

### 16. Author (new)

```markdown
## Author

**Carlo Miguel Dy** — [carlomigueldy.com](https://carlomigueldy.com)

Built as a Growmodo developer assessment (May 2026).
```

### 17. License (kept)

One line: `GPL v2 or later.`

## Screenshot capture protocol

Both screenshots are captured live from the running Docker stack. The capture is a one-time task during implementation; the resulting PNGs are committed to the repo.

1. Run `docker compose up -d` and wait for the WP healthcheck.
2. Complete the WP installer (Site Title: Estatein, admin / admin).
3. Activate the Estatein theme.
4. Install + activate ACF (free).
5. Create the static homepage and set it as the front page.
6. Create 3 sample Property posts with realistic-looking data (any short titles like "Modern Family House", "Skyper Pool Apartment", etc.) and featured images. Use any CC0 real-estate photo (Unsplash, Pexels). These uploaded images do **not** need to be committed — they live inside the WP volume and are only used to make the one-time screenshot look real.
7. Capture **hero.png**: Chrome DevTools MCP, viewport 1440 × 900, screenshot of the visible viewport only (no scrolling).
8. Capture **collage.png**: Chrome DevTools MCP full-page screenshot of the entire homepage at viewport 1440 wide.
9. Both PNGs land in `docs/screenshots/`. Create the directory as part of this work.
10. If either PNG exceeds 500 KB, compress with `pngquant --quality=80-95` (preferred) or `oxipng -o 4`. Keep both under 500 KB.

## Asset layout

```
docs/
├── screenshots/
│   ├── hero.png
│   └── collage.png
└── superpowers/
    ├── plans/
    └── specs/
        └── 2026-05-28-readme-revamp-design.md   ← this file
```

## Out-of-scope work the team may notice (do NOT do as part of this spec)

- The README currently has `git clone <repo-url>` with a placeholder URL. If a real public repo URL is available we'd swap it in, but the rest of the README should not be blocked on that. Leave the placeholder if no URL is provided.
- No CI badge yet — there's no CI configured. Don't fabricate one.
- The existing footer copyright / theme metadata in `style.css` is out of scope.

## Success criteria

- `README.md` renders cleanly on GitHub with hero PNG visible above the fold.
- TOC links all resolve to in-document anchors.
- A new developer can go from `git clone` to a working theme at `localhost:8080` using only the README — no external context.
- Both screenshots show the actual running theme (not Figma exports, not stubs).
- The "Architecture Decisions", "Theme Structure", "Performance", "Accessibility" sections preserve the existing prose verbatim.

## Team responsibilities

- **Atlas** — orchestrate; route work; report.
- **Forge** — only if a planning question comes up; otherwise idle.
- **Builder** — write the new `README.md`; embed badges; assemble TOC and prose.
- **Core** — verify the `docker compose up -d` flow is reproducible from a clean state; surface any required env / image / port adjustments.
- **Probe** — bring up the stack, finish WP install, activate theme + ACF, seed sample properties, capture both PNGs via Chrome DevTools MCP, place them under `docs/screenshots/`.
- **Lens** — review the final README diff for accuracy, broken anchors, broken links, dead code references, and consistency with the actual codebase.
