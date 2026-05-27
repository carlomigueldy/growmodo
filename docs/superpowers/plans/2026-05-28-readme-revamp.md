# README Revamp Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace `README.md` with a portfolio-grade README (hero screenshot + TOC + install instructions + collage screenshot + preserved engineering prose), as specified in `docs/superpowers/specs/2026-05-28-readme-revamp-design.md`.

**Architecture:** Documentation-only change. Two PNG screenshots captured live from the running Docker stack via Chrome DevTools MCP, then dropped into `docs/screenshots/`. The README is rewritten in place using the section order from the spec, preserving four existing sections verbatim (Architecture Decisions, Theme Structure, Performance bullets, Accessibility bullets). A new Content & Data section is added between Theme Structure and Performance, with truthful ACF Free vs Pro wording confirmed empirically.

**Tech Stack:** Docker Compose (WordPress 6.7 + MySQL 8 + PHP 8.2), Chrome DevTools MCP (screenshot capture), Shields.io (static badges in README), GitHub-flavored Markdown.

---

## File Structure

**Created:**
- `docs/screenshots/hero.png` — 1440 × ~900 viewport screenshot of the homepage hero band
- `docs/screenshots/collage.png` — full-page screenshot of the entire homepage at 1440 px wide

**Modified:**
- `README.md` — complete rewrite using the structure in section 3 of the spec

**Untouched (the engineering substance):**
- `wp-content/themes/estatein/**` — the theme code is correct as-is; do not change
- `docker-compose.yml`, `.env.example`, `.gitignore` — already correct

---

## Team Routing

| Phase | Owner |
|------|-------|
| Tasks 1–6 (Docker, WP install, screenshots) | Probe |
| Task 7 (write README) | Builder |
| Tasks 8–9 (verification) | Builder + Probe |
| Task 10 (review) | Lens |
| Task 11 (final commit) | Builder |

Atlas orchestrates, routes, and reports. Forge stays idle unless a planning question arises.

---

## Task 1: Bring up the Docker stack from a clean state

**Files:**
- Read: `docker-compose.yml`
- Read: `.env.example`

- [ ] **Step 1: Verify Docker is running**

Run:
```bash
docker version
```
Expected: client + server version info, no error. If Docker isn't running, start Docker Desktop.

- [ ] **Step 2: Tear down any existing volumes for a true clean slate**

Run (from `/Users/carlomigueldy/personal/growmodo`):
```bash
docker compose down -v
```
Expected: "Stopping…", "Removing…" — or `no such service` if it never ran. The `-v` flag deletes the `wordpress_data` and `db_data` volumes so the WP installer runs fresh.

- [ ] **Step 3: Copy the env file if missing**

Run:
```bash
test -f .env || cp .env.example .env
```
Expected: no output. The `.env` file now exists with `DB_PASSWORD=wordpress` and `DB_ROOT_PASSWORD=rootpassword`.

- [ ] **Step 4: Start the stack in detached mode**

Run:
```bash
docker compose up -d
```
Expected: `db` healthy, then `wordpress` started. First run pulls images (~600 MB). Wait until `docker compose ps` shows both services healthy / running.

- [ ] **Step 5: Verify WordPress responds on :8080**

Run:
```bash
curl -sI http://localhost:8080 | head -1
```
Expected: `HTTP/1.1 302 Found` (redirect to the WP installer) or `HTTP/1.1 200 OK`. If you get `Connection refused`, wait 10 s and retry — Apache may still be booting.

- [ ] **Step 6: Note any setup gotchas in the team chat**

If Step 4 or 5 produced errors (port conflict, image pull failure, etc.), Probe messages Core with the specific error and Core diagnoses before continuing. Otherwise, proceed to Task 2.

---

## Task 2: Complete the WordPress installer and verify ACF Free behavior

This task uses Chrome DevTools MCP to drive the WP installer browser flow. The goal is twofold: (a) finish WP setup, (b) install ACF **Free** and capture exactly what the admin UI looks like with ACF Free active — because the spec requires the README to truthfully say what ACF Free can and cannot edit.

**Files:**
- Read: `wp-content/themes/estatein/inc/acf-fields.php` — note: this defines four `repeater` fields (lines 65, 104, 151, 218)

- [ ] **Step 1: Open the installer in a Chrome DevTools MCP session**

Navigate to: `http://localhost:8080`. Expected redirect to `http://localhost:8080/wp-admin/install.php` (language picker).

- [ ] **Step 2: Select English (United States) and continue**

Click the "Continue" button after English is selected.

- [ ] **Step 3: Fill in the site setup form**

Fill these exact values:
- Site Title: `Estatein`
- Username: `admin`
- Password: `admin` (dismiss the "weak password" confirmation by ticking the "Confirm use of weak password" checkbox)
- Your Email: `admin@example.com`
- Search Engine Visibility: leave unchecked

Click **Install WordPress**. Expected: success page with a "Log In" button.

- [ ] **Step 4: Log in**

Click "Log In", enter `admin` / `admin`. Expected: WP dashboard at `/wp-admin/`.

- [ ] **Step 5: Activate the Estatein theme**

Navigate to **Appearance → Themes**. Hover the "Estatein" tile → click **Activate**. Expected: "New theme activated" banner; Estatein moves to the top-left as the active theme.

- [ ] **Step 6: Install ACF Free**

Navigate to **Plugins → Add New**. Search for `Advanced Custom Fields`. Find the ACF entry by **WP Engine** (>2M active installs). Click **Install Now**, then **Activate**.

- [ ] **Step 7: Verify the ACF admin UI with Free version**

Navigate to **ACF → Field Groups** (left sidebar). Document what you see:
- Are the field groups registered in `inc/acf-fields.php` listed? (Homepage — Hero Section, etc.)
- Edit one (e.g., "Homepage — Hero Section"). Scroll through the field list. **Look for the repeater field** (`hero_stats`). Does it render?
  - **If ACF Free renders the repeater** as an editable table → state "ACF Free is sufficient" in the README.
  - **If ACF Free shows a "Pro required" notice or hides the field** → state "ACF Pro is required to edit the stats/features/testimonials/FAQ repeaters; without Pro the theme uses hardcoded Figma defaults from the template parts" in the README.

- [ ] **Step 8: Test the front-end fallback**

Visit `http://localhost:8080` (the homepage). The theme should still render even though no front-page is set yet — WordPress will fall back to the latest posts. The key check: does the page load without PHP errors? Open browser devtools → Console for any JS errors, and check `docker compose logs wordpress | tail -50` for any PHP fatals.

- [ ] **Step 9: Record findings**

Probe writes the verified ACF Free behavior in a short note for Builder. Two lines is enough, e.g.:
```
ACF Free behavior verified 2026-05-28:
- Repeater fields in field groups SHOW / DO NOT SHOW as editable in admin
- Front-end hero stats render from: ACF data / hardcoded defaults / placeholder template-part code
```

This note becomes the source of truth for the README's Content & Data section.

- [ ] **Step 10: Commit (none — no file changes yet)**

No commit at this step. Findings live in the team chat / task notes only.

---

## Task 3: Configure the homepage and seed sample properties

**Files:** none modified on disk (all changes are inside the WP volume).

- [ ] **Step 1: Create a "Home" page**

In wp-admin, go to **Pages → Add New**. Title: `Home`. Click **Publish**.

- [ ] **Step 2: Set static front page**

Navigate to **Settings → Reading**.
- "Your homepage displays": select **A static page**.
- "Homepage": select **Home** (the page from Step 1).
- "Posts page": leave blank.
- Click **Save Changes**.

- [ ] **Step 3: Populate the Homepage Hero ACF group (only if ACF Free supports it)**

If Task 2 Step 7 confirmed ACF Free renders the hero field group:
- Edit the "Home" page.
- Scroll to the ACF "Homepage — Hero Section" metabox.
- Leave the default values (the spec already uses sensible defaults like "Discover Your Dream Property with Estatein").
- If a Hero Image upload field is empty, upload any tall dark real-estate / interior photo from Unsplash (e.g., search "modern apartment dark"). Click **Update**.

If ACF Free does not render these fields, skip — the template part will use hardcoded defaults.

- [ ] **Step 4: Add three Property CPT entries**

Navigate to **Properties → Add New**. Create three posts:

| # | Title | Featured Image (placeholder) | ACF Price | Beds | Baths | Type |
|---|-------|------------------------------|-----------|------|-------|------|
| 1 | Modern Family House     | any Unsplash exterior photo | $1,250,000 | 4 | 3 | House |
| 2 | Skyper Pool Apartment   | any Unsplash apartment photo | $890,000  | 2 | 2 | Apartment |
| 3 | Luxury Penthouse Suite  | any Unsplash penthouse photo | $3,400,000 | 3 | 3 | Penthouse |

(If ACF Pro repeaters aren't available, the property card may show a subset of these fields — that's fine, the screenshot is what matters.)

For each: enter Title, set Featured Image (Media → upload from your machine or use Add Media → Insert from URL with any CC0 image URL), fill the visible ACF fields, click **Publish**.

- [ ] **Step 5: Reload the homepage and visually confirm**

Open `http://localhost:8080` in the browser. Confirm:
- Banner across the top (the promotional banner)
- Header with logo + nav
- Hero section with heading, CTAs, and stats
- Features section (4 icon cards)
- Featured Properties section (3 cards from the seeded properties)
- Testimonials section
- FAQ section
- CTA section
- Footer

If any section is missing or broken, message Core and pause.

- [ ] **Step 6: Commit (none — no file changes on disk yet)**

---

## Task 4: Capture hero.png

**Files:**
- Create: `docs/screenshots/hero.png`

- [ ] **Step 1: Create the screenshots directory**

Run:
```bash
mkdir -p /Users/carlomigueldy/personal/growmodo/docs/screenshots
```
Expected: directory created (or already exists, no error).

- [ ] **Step 2: Resize the Chrome DevTools MCP viewport to 1440 × 900**

Use the MCP `resize_page` tool: `{ width: 1440, height: 900 }`.

- [ ] **Step 3: Navigate to the homepage**

Use MCP `navigate_page` to `http://localhost:8080`. Wait for `networkidle`.

- [ ] **Step 4: Verify above-the-fold content**

Use MCP `take_snapshot` (or `evaluate_script` with `document.title`) to confirm the homepage rendered. The visible viewport should contain the hero heading and primary CTA.

- [ ] **Step 5: Capture the viewport screenshot**

Use MCP `take_screenshot` with:
- `fullPage: false` (viewport only, no scrolling)
- `path: /Users/carlomigueldy/personal/growmodo/docs/screenshots/hero.png`
- format: `png`

Expected: file appears at the path, ~200–800 KB.

- [ ] **Step 6: Verify the file**

Run:
```bash
ls -lh /Users/carlomigueldy/personal/growmodo/docs/screenshots/hero.png
file /Users/carlomigueldy/personal/growmodo/docs/screenshots/hero.png
```
Expected: size between 100 KB and 1 MB; `file` reports `PNG image data, 1440 x 900`.

- [ ] **Step 7: Compress if oversize**

If `hero.png` is > 500 KB, compress it:
```bash
pngquant --quality=80-95 --skip-if-larger --output /tmp/hero.png /Users/carlomigueldy/personal/growmodo/docs/screenshots/hero.png \
  && mv /tmp/hero.png /Users/carlomigueldy/personal/growmodo/docs/screenshots/hero.png
```
Fallback if `pngquant` isn't available:
```bash
oxipng -o 4 /Users/carlomigueldy/personal/growmodo/docs/screenshots/hero.png
```
Expected: file size ≤ 500 KB.

- [ ] **Step 8: Commit (deferred to Task 6)**

No commit yet — both screenshots are committed together in Task 6.

---

## Task 5: Capture collage.png (full-page screenshot)

**Files:**
- Create: `docs/screenshots/collage.png`

- [ ] **Step 1: Keep the Chrome DevTools MCP viewport at 1440 wide**

Confirm the viewport is still 1440 × 900 from Task 4.

- [ ] **Step 2: Navigate to the homepage**

If the page already shows the homepage from Task 4 Step 3, skip. Otherwise navigate to `http://localhost:8080`.

- [ ] **Step 3: Wait for all sections to render**

Use MCP `evaluate_script` to confirm `document.readyState === 'complete'` AND that the property cards have loaded their featured images. A quick check:
```javascript
Array.from(document.querySelectorAll('.property-card img')).every(img => img.complete && img.naturalHeight > 0);
```
Run via MCP `evaluate_script`. Expected: `true`. If `false`, wait 2 s and re-run.

- [ ] **Step 4: Capture the full-page screenshot**

Use MCP `take_screenshot` with:
- `fullPage: true` (capture the entire scrollable page)
- `path: /Users/carlomigueldy/personal/growmodo/docs/screenshots/collage.png`
- format: `png`

Expected: file appears at the path, likely large (1–4 MB before compression).

- [ ] **Step 5: Verify the file**

Run:
```bash
ls -lh /Users/carlomigueldy/personal/growmodo/docs/screenshots/collage.png
file /Users/carlomigueldy/personal/growmodo/docs/screenshots/collage.png
```
Expected: width 1440 px; height > 3000 px (the full page is tall); size 1–4 MB.

- [ ] **Step 6: Compress if oversize**

If `collage.png` is > 500 KB (it almost certainly will be), compress aggressively:
```bash
pngquant --quality=70-90 --speed 1 --output /tmp/collage.png /Users/carlomigueldy/personal/growmodo/docs/screenshots/collage.png \
  && mv /tmp/collage.png /Users/carlomigueldy/personal/growmodo/docs/screenshots/collage.png
```
If still over 500 KB after that, accept up to 800 KB for the collage (it's the larger of the two visuals and visual fidelity matters more than the strict 500 KB target). Otherwise, run a second pass with `--quality=60-80`.

- [ ] **Step 7: Verify final size**

Run:
```bash
ls -lh /Users/carlomigueldy/personal/growmodo/docs/screenshots/collage.png
```
Expected: size ≤ 800 KB.

- [ ] **Step 8: Commit (deferred to Task 6)**

---

## Task 6: Commit the screenshots

**Files:**
- Stage: `docs/screenshots/hero.png`, `docs/screenshots/collage.png`

- [ ] **Step 1: Stage both PNGs**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo add docs/screenshots/hero.png docs/screenshots/collage.png
```

- [ ] **Step 2: Verify the staged files**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo status --short
```
Expected:
```
A  docs/screenshots/hero.png
A  docs/screenshots/collage.png
```

- [ ] **Step 3: Commit**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo commit -m "$(cat <<'EOF'
docs: add hero and collage screenshots of running theme

Captured live from the running Docker stack at viewport 1440 wide
via Chrome DevTools MCP. hero.png is above-the-fold (1440 x 900);
collage.png is the full-page composite.

Co-Authored-By: Claude Opus 4.7 <noreply@anthropic.com>
EOF
)"
```
Expected: one new commit on `main` with both PNGs.

- [ ] **Step 4: Confirm on the log**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo log --oneline -1
```
Expected: latest commit subject `docs: add hero and collage screenshots of running theme`.

---

## Task 7: Write the new README.md

This is the biggest single edit. Replace the entire contents of `README.md` with the structure from the spec. Use the verified ACF Free behavior note from Task 2 Step 9 to write the Content & Data section truthfully.

**Files:**
- Modify: `README.md` (full rewrite)
- Read for reference: `docs/superpowers/specs/2026-05-28-readme-revamp-design.md` (spec)
- Read for reference: existing `README.md` (preserve specific prose verbatim — see steps below)
- Read for reference: `wp-content/themes/estatein/inc/acf-fields.php` (field group titles, accurate)
- Read for reference: `wp-content/themes/estatein/inc/custom-post-types.php` (Property CPT settings)

- [ ] **Step 1: Read the existing README to extract the verbatim sections**

The following blocks must be carried over verbatim into the new README:

1. **Architecture Decisions** (current README lines 48–54): the five bullet items starting with "Classic theme over Block/FSE theme…" through "Docker Compose…". Copy as-is.

2. **Theme Structure** (current README lines 56–69): the file/purpose table starting at "| File | Purpose |…". Copy as-is.

3. **Performance Optimizations** (current README lines 75–81): the five bullet items starting with "Single CSS file…" through "Semantic HTML5 for SEO". Copy as-is. The heading itself changes from "Performance Optimizations" to "Performance" (rename only).

4. **Accessibility** (current README lines 83–90): the six bullet items starting with "Skip-to-content link" through "Proper heading hierarchy (H1 → H2 → H3)". Copy as-is.

Builder reads the current README first and quotes these blocks into the new file unchanged.

- [ ] **Step 2: Write the new README in one Write call**

Use the Write tool to create the new `README.md` at `/Users/carlomigueldy/personal/growmodo/README.md` with the content below. **The ACF wording in the Content & Data section depends on Task 2 Step 9 findings — Builder substitutes the correct wording before writing.**

```markdown
![Estatein homepage hero](docs/screenshots/hero.png)

# Estatein — Real Estate WordPress Theme

> Pixel-perfect dark-themed WordPress theme matching the Estatein Figma design.
> Built for the Growmodo developer assessment.

![WordPress 6.7](https://img.shields.io/badge/WordPress-6.7-21759B?logo=wordpress&logoColor=white)
![PHP 8.2](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-compose-2496ED?logo=docker&logoColor=white)
![License GPL-2.0](https://img.shields.io/badge/license-GPL--2.0-A42E2B)

## Table of Contents

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

## Overview

A custom dark-themed WordPress theme for the Estatein real estate brand. Single homepage with hero, features, properties, testimonials, FAQ, and CTA sections — all editable via Advanced Custom Fields. Vanilla CSS, no build tooling, runs entirely in Docker.

**Figma source:** [Real Estate Business Website — Dark Theme](https://www.figma.com/design/jew3YhbDHZCZ5A2SUsO7VK)

## Demo

![Estatein full homepage](docs/screenshots/collage.png)

## Features

- Pixel-perfect Figma-to-code with responsive breakpoints (390 px / 1440 px / desktop)
- Dark theme tokens via CSS custom properties + fluid typography with `clamp()`
- Custom Post Type for property listings with ACF metadata
- Header banner dismiss, mobile nav toggle, newsletter subscribe, smooth scroll
- Vanilla JS only (no jQuery), single CSS file, semantic HTML5

## Tech Stack

| Layer    | Tool                              |
|----------|-----------------------------------|
| CMS      | WordPress 6.7                     |
| Runtime  | PHP 8.2                           |
| Database | MySQL 8.0                         |
| Styling  | Vanilla CSS + custom properties   |
| Content  | Advanced Custom Fields (free)     |
| Local    | Docker Compose                    |

## Prerequisites

- **Docker Desktop** — [Download](https://www.docker.com/products/docker-desktop/)
- **Git** — [Download](https://git-scm.com/downloads)

That's it. Everything else (WordPress, MySQL, PHP) runs in Docker containers.

## Installation

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

## Architecture Decisions

- **Classic theme** over Block/FSE theme — gives full control for pixel-perfect design matching and demonstrates core WordPress proficiency (PHP templating, the Loop, template hierarchy).
- **Vanilla CSS with custom properties** — no build tooling overhead. CSS custom properties provide the dark theme token system (colors, spacing, typography) with responsive `clamp()` values for fluid typography.
- **Template parts** per section — clean separation of concerns. Each homepage section (hero, features, properties, testimonials, FAQ, CTA) is isolated in its own PHP file.
- **ACF Free** for content management fields — hero content, CTA content, and property metadata are all editable from the WordPress admin.
- **Docker Compose** — reproducible local environment with WordPress 6.7 + MySQL 8. Theme files are volume-mounted for live editing.

## Theme Structure

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

> **Note on ACF tiers:** ⟪Substitute the verified wording from Task 2 Step 9 here. Two viable options below.⟫
>
> **Option A — ACF Free is sufficient:** *"All field groups including the repeater-based ones (stats, features, testimonials, FAQ) are editable via ACF Free in this build."*
>
> **Option B — ACF Pro is needed for editable lists:** *"The hero stats, features, testimonials, and FAQ field groups use ACF Repeater fields, which require ACF Pro to edit. Without Pro, these sections render from the hardcoded Figma defaults baked into the template parts — the theme still works end-to-end."*
>
> ⟪Builder picks ONE option (delete the other and the surrounding scaffolding) based on Probe's Task 2 finding.⟫

## Performance

- Single CSS file (no render-blocking external stylesheets beyond Google Fonts)
- Lazy loading on below-the-fold images
- Custom image sizes for optimal delivery
- Minimal JavaScript (~60 lines, no jQuery dependency)
- Semantic HTML5 for SEO

## Accessibility

- Skip-to-content link
- ARIA landmarks and labels on all interactive elements
- Keyboard-navigable menu
- Focus-visible styles
- Screen-reader-only utility class
- Proper heading hierarchy (H1 → H2 → H3)

## Browser Support

Tested on Chrome, Firefox, Safari, and Edge (latest).

## Author

**Carlo Miguel Dy** — [carlomigueldy.com](https://carlomigueldy.com)

Built as a Growmodo developer assessment (May 2026).

## License

GPL v2 or later.
```

- [ ] **Step 3: Resolve the ACF placeholder**

Open the new `README.md`, find the `⟪Substitute…⟫` block in the Content & Data section, and replace it with the one option (A or B) that matches Task 2 Step 9 findings. Delete the other option and the surrounding `⟪…⟫` scaffolding so no placeholder text remains.

- [ ] **Step 4: Verify no placeholder markers remain**

Run:
```bash
grep -nE '⟪|TBD|TODO|<repo-url>' /Users/carlomigueldy/personal/growmodo/README.md
```
Expected output: **one line** — the `<repo-url>` placeholder on the `git clone` line in Installation. That one is intentional per the spec's out-of-scope note (it's left as a placeholder if no real public URL is known). Zero `⟪`, `TBD`, or `TODO` markers.

If you see more than the single `<repo-url>` line, fix the README until the grep shows only that one line.

- [ ] **Step 5: Commit (deferred to Task 11)**

The README is committed in Task 11 after all verification passes.

---

## Task 8: Verify TOC anchors and external links

**Files:**
- Read: `README.md`

- [ ] **Step 1: Generate the list of `##` headings from the README**

Run:
```bash
grep -nE '^## ' /Users/carlomigueldy/personal/growmodo/README.md
```
Expected: exactly 14 H2 headings, in this order:
```
Table of Contents
Overview
Demo
Features
Tech Stack
Prerequisites
Installation
Architecture Decisions
Theme Structure
Content & Data
Performance
Accessibility
Browser Support
Author
License
```
(15 H2s total — the first one is "Table of Contents" itself, which doesn't need a TOC link.)

- [ ] **Step 2: Verify each TOC anchor target exists**

For each TOC link in the README, the GitHub anchor is the heading text lowercased with non-alphanumerics replaced by `-` and ampersand stripped (so `Content & Data` → `content--data`, with two dashes).

Run this one-liner sanity check that asserts each TOC anchor maps to a real heading:
```bash
python3 <<'PY'
import re, pathlib
text = pathlib.Path('/Users/carlomigueldy/personal/growmodo/README.md').read_text()
toc_anchors = re.findall(r'\]\(#([a-z0-9-]+)\)', text)
headings = re.findall(r'^## (.+)$', text, flags=re.MULTILINE)
def slugify(h):
    s = h.lower().strip()
    s = re.sub(r'[^\w\s-]', '', s)  # strip punctuation incl. &
    s = re.sub(r'\s+', '-', s)
    return s
heading_slugs = {slugify(h) for h in headings}
missing = [a for a in toc_anchors if a not in heading_slugs]
print('TOC anchors:', toc_anchors)
print('Heading slugs:', sorted(heading_slugs))
if missing:
    print('MISSING anchors:', missing)
    raise SystemExit(1)
print('OK — all TOC anchors resolve to a heading')
PY
```
Expected: prints `OK — all TOC anchors resolve to a heading` and exits 0. Note: GitHub's actual slugifier strips `&` and may collapse to `content--data` (double dash) — if the check above finds `content--data` mismatched, fix the slug in the TOC to `content--data` (it's already that in the spec).

- [ ] **Step 3: Verify external links return 2xx / 3xx**

Run:
```bash
for url in \
  "https://www.docker.com/products/docker-desktop/" \
  "https://git-scm.com/downloads" \
  "https://www.figma.com/design/jew3YhbDHZCZ5A2SUsO7VK" \
  "https://carlomigueldy.com" \
  "https://img.shields.io/badge/WordPress-6.7-21759B"; do
    echo "$url -> $(curl -sI -o /dev/null -w "%{http_code}" -L --max-time 10 "$url")"
done
```
Expected: every URL prints a status of `200` or `301`/`302`. If any URL returns `4xx` or `5xx`, investigate and either correct the link or note it as a known issue (Figma may require auth — a `200` after redirect is fine; a hard `403` should be raised to Carlo).

- [ ] **Step 4: Verify both screenshot files exist and are referenced correctly**

Run:
```bash
ls /Users/carlomigueldy/personal/growmodo/docs/screenshots/hero.png \
   /Users/carlomigueldy/personal/growmodo/docs/screenshots/collage.png \
&& grep -c "docs/screenshots/hero.png\|docs/screenshots/collage.png" /Users/carlomigueldy/personal/growmodo/README.md
```
Expected: both files listed without error, and `grep -c` prints `2` (each path referenced exactly once in the README).

- [ ] **Step 5: Commit (deferred to Task 11)**

---

## Task 9: End-to-end clean install verification

This is the "the README actually works" test. A simulated new developer follows the README from `git clone` and arrives at a working theme.

**Files:** none modified — this is a pure verification task.

- [ ] **Step 1: Tear down everything**

Run:
```bash
cd /Users/carlomigueldy/personal/growmodo
docker compose down -v
rm -f .env
```
Expected: all containers + volumes removed; `.env` deleted (will be recreated from `.env.example` by the README steps).

- [ ] **Step 2: Walk through the README's Installation section, step by step**

Open `README.md`. Execute each numbered step exactly as written:

1. Skip the `git clone` — we're already in the repo.
2. `cp .env.example .env`
3. `docker compose up -d`
4. Visit `http://localhost:8080` → finish the installer (Site Title `Estatein`, admin / admin, email any).
5. Activate the Estatein theme.
6. Install + activate ACF Free.
7. Set a static homepage.
8. Add 3 sample properties.
9. Refresh `http://localhost:8080`.

- [ ] **Step 3: Verify the homepage renders fully**

Open `http://localhost:8080` and confirm all sections render (hero, features, properties, testimonials, FAQ, CTA, footer). No PHP fatals in `docker compose logs wordpress | tail -50`.

- [ ] **Step 4: Note any drift between the README and reality**

If any step in the README required improvisation or fixing during this walk-through, that's a README bug. Probe messages Builder with the specific line that needs an edit. Builder updates the README and Probe re-runs from Step 1.

- [ ] **Step 5: Tear down again to leave a clean repo**

Run:
```bash
docker compose down
```
Optional: leave volumes in place so the next dev start is fast; or run `docker compose down -v` for a truly clean state. Pick `down` (no `-v`) to keep the seeded data around for any follow-up work.

- [ ] **Step 6: Commit (none — verification only)**

---

## Task 10: Lens review

**Files:**
- Read: `README.md` (current diff against `main` at HEAD)
- Read: `docs/superpowers/specs/2026-05-28-readme-revamp-design.md`

- [ ] **Step 1: Run a diff of the working tree against the last commit**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo diff --stat HEAD -- README.md
git -C /Users/carlomigueldy/personal/growmodo diff HEAD -- README.md
```
Expected: a single modified file (README.md) with a large diff. Pipe to a file or scroll the full diff.

- [ ] **Step 2: Lens reviews against the spec checklist**

Lens confirms each item below by reading both the new `README.md` and the spec:

- [ ] Hero PNG appears at the very top of the file, *before* the H1.
- [ ] H1 is `# Estatein — Real Estate WordPress Theme`.
- [ ] Tagline blockquote follows, exactly two lines.
- [ ] Four Shields.io badges, single line, in order: WordPress, PHP, Docker, License.
- [ ] TOC has 14 entries in the spec's exact order.
- [ ] Overview has the 3-line description + Figma source link.
- [ ] Demo section embeds `docs/screenshots/collage.png`.
- [ ] Features lists exactly five bullets, matching spec section 6 wording.
- [ ] Tech Stack is a 6-row markdown table.
- [ ] Prerequisites lists Docker Desktop + Git, each with a working download link.
- [ ] Installation is a single numbered list (1–9) with a final `docker compose down` stop block.
- [ ] Architecture Decisions content is verbatim from the original README.
- [ ] Theme Structure table is verbatim from the original README.
- [ ] Content & Data section is present, with truthful ACF wording (no `⟪⟫` markers).
- [ ] Performance heading is "Performance" (renamed from "Performance Optimizations") with original bullets verbatim.
- [ ] Accessibility bullets are verbatim from the original README.
- [ ] Browser Support is one line: `Tested on Chrome, Firefox, Safari, and Edge (latest).`
- [ ] Author section names Carlo + carlomigueldy.com + assessment framing.
- [ ] License section is one line: `GPL v2 or later.`
- [ ] No leftover placeholder text (`TBD`, `TODO`, `⟪`).
- [ ] No broken markdown (mismatched fenced code blocks, unbalanced parentheses in links).

- [ ] **Step 3: Lens posts findings**

If everything passes, Lens sends Atlas / Builder a single message: `LGTM — ship it.` If any issue is found, Lens lists the exact line + the proposed fix. Builder addresses each finding and Lens re-checks just the changed areas.

- [ ] **Step 4: Commit (none — review only)**

---

## Task 11: Commit the README

**Files:**
- Stage: `README.md`

- [ ] **Step 1: Stage the README**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo add README.md
```

- [ ] **Step 2: Verify only README is staged**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo status --short
```
Expected: a single line `M  README.md`. If anything else is staged, unstage it.

- [ ] **Step 3: Commit**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo commit -m "$(cat <<'EOF'
docs: revamp README with hero, TOC, screenshots, and install guide

- Hero screenshot above H1; full-page collage as Demo
- Table of Contents linking 14 sections
- Tech Stack table + 5 Feature bullets
- Single numbered Installation flow with Docker Desktop and Git
  download links
- New Content & Data section documenting the Property CPT and ACF
  field groups with truthful ACF Free/Pro wording
- Preserved Architecture Decisions, Theme Structure, Performance,
  and Accessibility prose verbatim
- New Author section + compressed Browser Support line

Co-Authored-By: Claude Opus 4.7 <noreply@anthropic.com>
EOF
)"
```
Expected: one commit on `main`.

- [ ] **Step 4: Confirm on the log**

Run:
```bash
git -C /Users/carlomigueldy/personal/growmodo log --oneline -3
```
Expected: latest commit subject `docs: revamp README with hero, TOC, screenshots, and install guide`, preceded by the screenshots commit and the spec commit.

- [ ] **Step 5: Tear down the team's stack (optional)**

Run:
```bash
cd /Users/carlomigueldy/personal/growmodo
docker compose down
```
Leaves volumes intact (they have the seeded properties and ACF settings) but stops the containers.

---

## Done — what success looks like

1. `git log --oneline -3` shows: spec commit → screenshots commit → README commit, in that order.
2. `README.md` opens in GitHub with the hero PNG visible above the fold.
3. Every TOC link jumps to its section.
4. A new developer can clone the repo and follow the README from `Prerequisites` through `Installation` and end up at a working `http://localhost:8080`.
5. The ACF wording in Content & Data reflects the actual ACF Free behavior verified in Task 2.
