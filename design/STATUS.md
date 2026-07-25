# What-We-Do & Login — As-Built Status (photographic sections)

The what-we-do service sections and the login page are **photo-based**, in the Goldman-Sachs
editorial style (the same alternating image/text pattern the Home page uses).

## What shipped
- **`ServiceShowcase.astro`** renders each service as its own `<section id={service.id}>`: a tailored
  photo on one side, heading + copy on the other, **alternating sides down the page** with alternating
  `bg-paper` / `bg-mist` backgrounds. `subServices` render as `border-l-2 border-acid` blocks; an
  optional acid `note` pill is supported (Pensions "In Development").
- **21 curated photos** — one per service across the 4 pillar pages, theme-matched (markets, solar,
  fintech, refinery, real estate, port/commodities, handshake, Manhattan skyline, etc.). Currently
  remote Unsplash URLs (all verified to resolve **and** content-checked via headless-Chrome QA); each
  is a one-line swap for the client's own photo (URL → `/images/<id>.jpg`).
- **Login** is a clean, minimal typographic card — no photo, no SVG.

## Removed (superseded)
- The Islamic-geometric **SVG illustration system** was removed at the user's request: 22 components
  deleted (21 service illustrations + `LoginJali`), and the SVG design docs
  (`ILLUSTRATION-SPEC.md`, `LAYOUT-VARIANTS.md`, `manifest/`) pruned.
- **Kept** (still used by out-of-scope pages): `GeometricPattern.astro`, `AboutConstruction.astro`
  (about), `ContactPortal.astro` (contact), `InsightsTileField.astro` (insights).

## Scope boundary
Changed: the 4 `what-we-do/*` pages + `login.astro`. **Untouched:** Home, About, Contact, Insights —
so About/Contact/Insights **retain their SVG accents** (per the user's instruction). Heroes,
`AboutBIG`, `ContactCTA`, and the contact form are unchanged.

## Verified
- `npm run build` passes — 13 pages.
- All 21 mega-menu anchor ids present exactly once in `dist/`.
- Service copy (title/body/subServices) byte-identical to `design/baseline/*`.
- Zero illustration imports remain in the 5 reworked pages.
- Layout + every photo confirmed via headless-Chrome screenshots (4 initial mismatches swapped:
  am-equity, pakistan-energy, alternatives, ipos).

## Notes / easy tweaks
- `family-office` and `ipos` both use dusk city skylines (different cities, opposite sides, separated) —
  fine as-is, trivially swappable if more contrast is wanted.
- Client photos: replace any `image:` URL in the 4 `what-we-do/*.astro` service arrays.
