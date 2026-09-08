# BIG — Design Manifest

The **homepage (`src/pages/index.astro`) is the single source of truth** for this site's
design language. Every value below was extracted from the actual code, not from a spec.
If this document and the code ever disagree, **the code wins** — update this file.

Stack: **Astro 4** (static SSG) + **Tailwind CSS 3**. Tokens live in
`tailwind.config.mjs` and `src/styles/global.css`.

---

## 1. Color tokens

Defined twice, kept in sync: as CSS variables in `src/styles/global.css` (`:root`) and as
Tailwind colors in `tailwind.config.mjs`.

| Token | Hex | Tailwind class | Role |
|---|---|---|---|
| `acid` | `#CCFF00` | `bg-acid` / `text-acid` / `border-acid` | Primary accent — navbar bar, CTA fills, rules, bullets, newsletter band |
| `acid-600` | `#A6D400` | `hover:bg-acid-600` | Hover/active state on acid fills |
| `ink` | `#0B0B0B` | `bg-ink` / `text-ink` | Near-black — body text, footer, dark sections, filled buttons |
| `paper` | `#FFFFFF` | `bg-paper` | Default page background, white cards |
| `mist` | `#F3F5EE` | `bg-mist` | Alternate section background (warm off-white) |
| `stone` | `#6B7280` | `text-stone` | Muted gray — body copy, eyebrows, captions, meta |
| `line` | `#E5E7EB` | `border-line` | Hairline dividers and card borders |

Additional CSS variable: `--header-h: 64px` (drives the sticky navbar height and the
global `scroll-margin-top` on every `[id]`).

**Rules**
- `acid` is for **fills only** — bars, buttons, rules, bullets. Never acid text on white.
- Dark sections use `bg-ink` with an `rgba(11,11,11,…)` gradient overlay on photography
  and a `w-1 bg-acid` left rail (see `Hero.astro` and the four what-we-do heroes).
- Sections alternate `bg-paper` / `bg-mist`, separated by `border-t border-line`.

---

## 2. Typography

Loaded via Google Fonts in `src/layouts/BaseLayout.astro`: **Newsreader** + **Inter**.

| Role | Classes | Family |
|---|---|---|
| **All headings** (h1–h4) | `font-serif font-normal` | Newsreader 400 |
| **Body / UI** | `font-sans` | Microsoft YaHei UI → Inter |
| **Eyebrow label** | `.label` | Inter 600, 13px, uppercase, `0.12em` tracking, `text-stone` |
| **Section number** | `font-sans text-xs font-bold tracking-[0.2em]` | Inter 700 (e.g. `01`, `02`) |
| **Stat figure** | `font-serif font-normal`, `clamp(48px, 6vw, 72px)` | Newsreader 400 |
| **Stat label** | `font-sans font-bold text-sm uppercase tracking-wide` | Inter 700 |

> **Headings are serif, not sans.** All 10 homepage headlines, the `Hero` `<h1>`, and
> `StatBand` figures use `font-serif font-normal`. This is the Goldman-Sachs editorial
> look the brand is built on. Do not substitute a bold sans-serif.

### Body font caveat

```js
sans: ['"Microsoft YaHei UI"', '"Microsoft YaHei"', 'Inter', 'system-ui', 'sans-serif']
```

`Microsoft YaHei UI` is a **Windows-only system font**. It is not available on Google
Fonts and is not licensed for webfont self-hosting. Windows visitors get YaHei UI;
macOS, iOS, Android, and Linux visitors fall back to **Inter**, which is loaded and
renders identically everywhere. This is a deliberate, accepted tradeoff — body text is
*not* pixel-identical across platforms.

### Type scale (`tailwind.config.mjs` → `fontSize`)

| Token | Size / line-height |
|---|---|
| `display-xl` | 80px / 1.05 |
| `display-lg` | 64px / 1.05 |
| `h2` | 48px / 1.1 |
| `h2-sm` | 36px / 1.1 |
| `stat` | 72px / 1 |
| `h3` | 22px / 1.3 |
| `body` | 17px / 1.6 |
| `label` | 13px / 1, `0.12em` tracking |
| `meta` | 14px / 1.4 |

Large headings mostly use inline `clamp()` for fluid sizing rather than these tokens —
e.g. hero `clamp(40px, 6vw, 78px)`, section H2 `clamp(32px, 4vw, 52px)`, card H3
`clamp(18px, 1.8vw, 22px)`. Match the neighbouring section's clamp when adding a heading.

---

## 3. Spacing & layout

| Token / class | Value | Use |
|---|---|---|
| `.container-big` | `max-w-content` (1280px), `mx-auto`, `px-6 lg:px-16` | Standard content wrapper |
| `py-section` | 112px | Standard section vertical padding |
| `py-section-sm` | 64px | Compact section padding |
| `max-w-content` | 1280px | Content max width |

- Split image/text pillar sections use `min-height: 580px` with `px-8 py-16 lg:px-14 xl:px-20`.
- Dark full-bleed photographic bands use `py-20 lg:py-28` (or `py-24 lg:py-32`) rather
  than `py-section` — they carry their own rhythm.
- Card grids: `gap-10`; 1-up mobile → 2-up tablet → 3-up desktop.

---

## 4. Shared components

All live in `src/components/`. **Reuse these — do not build parallel versions.**

### Global chrome
| Component | Role |
|---|---|
| `src/layouts/BaseLayout.astro` | Head/meta/OG, font loading, skip-link. Renders `Navbar` + `Footer`. **Every page uses this.** |
| `Navbar.astro` | Sticky acid bar, 63×26 SVG wordmark, mega-menu + 4 utility dropdowns, search panel, mobile drawer. Collapses below 900px. |
| `Footer.astro` | `bg-ink`, 4 link columns + Connect, copyright/legal bar |

### Page sections
| Component | Role |
|---|---|
| `Hero.astro` | Homepage dark hero — eyebrow + serif headline + body + acid CTA + `BIG` watermark |
| `StatBand.astro` | Three serif figures + bold uppercase labels on `bg-mist` |
| `PillarIntro.astro` | "What We Do" section header — title left, summary right, hairline |
| `ServiceSections.astro` | **Dispatcher.** Maps each service's `layout` key to one of the six section components below |
| `ServiceFeature` · `ServiceBanner` · `ServiceCtaBand` · `ServiceBordered` · `ServiceOverlap` · `ServiceCurtain` | The six service-section layouts |
| `CurtainRevealCard.astro` | Image card with CSS curtain-reveal on hover/focus |
| `AboutBIG.astro` | Shared "About BIG" band on service pages |
| `ContactCTA.astro` | Split mist-panel + image CTA block (`headline`, `subtext`, `image`, `imageAlt` props) |
| `NewsletterBand.astro` | Acid band, `id="newsletter"`, PHP-backed form |

### Primitives
`Button.astro` · `ArrowLink.astro` · `SectionLabel.astro` · `Card.astro`

> `Card.astro`, `SectionLabel.astro`, and `ServiceShowcase.astro` are currently unused.
> Prefer the `.label` utility class over `SectionLabel`.

Decorative SVGs live in `src/components/illustrations/`:
`AboutConstruction` (about) · `ContactPortal` + `GeometricPattern` (contact) ·
`InsightsTileField` (insights).

---

## 5. Buttons

Use `Button.astro`. Base:
`inline-flex items-center justify-center px-6 py-3 font-sans text-sm font-semibold tracking-wide transition-all duration-200 border`

| Variant | Classes | Use |
|---|---|---|
| `ink` (default) | `bg-ink text-white border-ink hover:bg-stone` | Primary |
| `acid` | `bg-acid text-ink border-acid hover:bg-acid-600` | Accent CTA |
| `outline` | `bg-transparent text-ink border-ink hover:bg-ink hover:text-white` | Secondary |

The homepage's four pillar CTAs use a larger inline variant —
`bg-acid text-ink px-8 py-4` with a sliding `→` on `group-hover`. That pattern is
homepage-native; match it when building an equivalent pillar section.

The navbar's `Client Login` button is spec-fixed at 91×32 with a `0.8px` border and is
intentionally distinct from these variants.

---

## 6. Utility classes (`src/styles/global.css`)

| Class | Definition |
|---|---|
| `.label` | `font-sans text-label font-semibold uppercase tracking-widest text-stone` |
| `.container-big` | `max-w-content mx-auto px-6 lg:px-16` |
| `.arrow-link` / `.arrow-icon` | Inline link + arrow that slides `translate-x-1` on hover |
| `.divider` | `border-t border-line` |

---

## 7. Conformance checklist

When adding or editing a page:

- [ ] Uses `BaseLayout` (never a page-local header/footer)
- [ ] Headings are `font-serif font-normal text-ink` (white on dark sections)
- [ ] Eyebrows use `.label`
- [ ] Sections wrap content in `.container-big`
- [ ] Section padding is `py-section` (or the dark-band `py-20 lg:py-28`)
- [ ] Backgrounds alternate `bg-paper` / `bg-mist` with `border-t border-line`
- [ ] Buttons use `Button.astro` variants
- [ ] Colors reference tokens — no raw hex outside SVG/gradient overlays
- [ ] No new font families; no `font-display` (Cormorant was removed)
