# BIG Illustration Spec — Islamic Geometric SVG System

Authoritative contract for every illustration in `src/components/illustrations/`.
Builders MUST follow this exactly. Reviewers verify against it.

## 1. Component contract (identical for every illustration)

```astro
---
interface Props { class?: string; title?: string }
const { class: className, title } = Astro.props;
---
<svg
  viewBox="0 0 480 480"            {/* or 0 0 960 360 (wide) / 0 0 360 540 (tall) — per assignment below */}
  fill="none"
  xmlns="http://www.w3.org/2000/svg"
  class={className}
  role={title ? 'img' : undefined}
  aria-hidden={title ? undefined : 'true'}
  focusable="false"
>
  {title && <title>{title}</title>}
  <!-- marks -->
</svg>
```

Rules:
- **Default decorative**: no `title` passed → `aria-hidden="true"`. The adjacent heading carries meaning.
- **Colors**: primary marks = `stroke="currentColor"` / `fill="currentColor"` (wrapper sets `text-ink` or `text-paper`).
  Accents = Tailwind token classes only: `class="fill-acid"`, `class="stroke-acid"`, opacity via `opacity-*` or
  `fill-current opacity-10`. **ZERO raw hex / rgb() / named colors anywhere.** (`fill="none"` on the root svg is the
  one allowed literal.) Every artwork must read correctly on BOTH light (text-ink) and dark (text-paper) wrappers —
  never hardcode a light/dark assumption.
- **Weight**: ≤ 8 KB, ≤ ~80 nodes. Repeats via `<defs>` + `<use>` / `<pattern>`, never hand-copied.
  If a `<defs>` id is needed, prefix it with the component name (e.g. `id="psx-se-star"`) — ids are global in HTML.
- **Absolutely no people, faces, hands, animals.** Architecture, geometry, celestial and botanical-geometric forms only.
- Scale via CSS (`w-full h-auto` from callers) — never fixed width/height attributes on the root.

## 2. Per-pillar visual language

| Pillar / page | Style name | Technique rules (in a 480 viewBox; scale strokes proportionally for wide/tall) | Motif vocabulary | Acid budget (hard cap) |
|---|---|---|---|---|
| PSX & Global Markets (`Psx*`) | **Fine geometric line-art** | stroke-only, widths 1.5–2.5, `stroke-linecap="round"`, NO fills except acid nodes | Rub el Hizb 8-point star lattices, girih strapwork, globe meridian arcs, crescent arcs, abstracted tick/candlestick geometry | ≤ 6 small filled acid circles ("opportunity nodes"), r ≤ 6 |
| Asset Management (`Am*`) | **Filled duotone planes** | fills only, ZERO strokes; layer `fill-current` at opacity 1 / 0.16 / 0.08 | mihrab-arch niches as vessels, stacked dome silhouettes, tessellated star tiling, scalloped arcade steps | exactly ONE thin acid sliver/wedge per artwork |
| Private Wealth (`Pw*`) | **Ornamental arabesque** | stroke 1–1.75 plus sparse delicate fills; vertical symmetry preferred | islimi scroll vines, interlaced knotwork cartouches, multifoil pointed arches, rosettes, lantern/Waqf forms | ONE continuous acid hairline (stroke-width ~1.5) threading the composition |
| Specialized Services (`Ss*`) | **Bold minimal marks** | stroke 8–14 OR solid fills; ≤ 12 elements total; one dominant motif | monumental 8-point star, interlocking heavy crescents, keystone arch, stacked ascent blocks | ONE bold acid counter-shape (fill) |
| About (`AboutConstruction`) | **Compass construction** | construction rules 0.75–1.25 + ONE result stroke at 2.5 | an 8-point star mid-construction: circles, radii, chords visible | none (currentColor only) |
| Contact (`ContactPortal`) | **Open portal** | two stroke weights, generous curves | open mihrab doorway, welcoming threshold | single acid threshold underline |
| Login (`LoginJali`) | **Jali lattice** | fine lattice emblem | mashrabiya screen (privacy/security) | none in the SVG (page button carries acid) |
| Insights (`InsightsTileField`) | **Tile field** | flat tiles at ~10% opacity fill | repeating crescent-and-star tiles, ONE tile acid-filled ("the signal") | that one tile |

## 3. viewBox assignment (matches the layout each artwork lives in)

- **Wide `0 0 960 360`** (band layouts): `PsxTransactionServices`, `AmEquity`, `PwPakistanEnergy`, `SsFamilyOffice`, `InsightsTileField`
- **Tall `0 0 360 540`** (offset-card layouts): `AmEtfs`, `PwRealEstate`, `SsMergersAcquisitions`
- **Square `0 0 480 480`** (split / emblem / mosaic / ledger / accents): all others

## 4. Motif-to-meaning map (each SVG MUST depict its service; reviewer criterion #1)

| Component | Service / heading | Must depict |
|---|---|---|
| PsxShariahEquities | Shariah Equities | girih lattice with THREE ascending node-paths (Swing / Positional / Long-Term strategies); acid nodes = screened opportunities |
| PsxGreenSukuks | Green Sukuks | crescent cradling a geometric leaf/turbine rosette above ruled coupon rows (green energy + fixed income) |
| PsxTransactionServices | Transaction Services | two facing mihrab arches joined by interlaced routing lines with star checkpoints (RAAST/SCRA/RDA rails → settlement) |
| PsxBlackstoneIntel | Blackstone Intel | minaret beacon with concentric broadcast arcs of tiny crescents (a channel broadcasting knowledge) |
| PsxBlackstoneInvestments | Blackstone Investments | closed octagonal Rub-el-Hizb seal with keyhole negative space (exclusive membership) |
| AmFixedIncome | Shariah Fixed Income | repeating scalloped arcade rows on a solid ground plane (stable coupons, capital preservation) |
| AmEquity | Shariah Equity | three overlapping dome silhouettes at rising scales (PSX / FTSE / NYSE) on stepped planes |
| AmEtfs | Shariah ETFs | tessellation of small star tiles enclosed in ONE circle (many holdings, one traded unit) |
| AmMultiAsset | Shariah Multi-Asset | interlocking 8-point star with differently-filled quadrants balanced on a fulcrum plane (the blend) |
| AmPensions | Shariah Pensions | crescent phases growing across stepped planes toward a horizon acid sliver (long-term accumulation) |
| PwWealthPlanning | Wealth Planning | symmetric arabesque tree, paired branch generations, varied leaf terminals (à la carte, legacy) |
| PwDedicatedInvestments | Dedicated Investments | ornamental key interlaced in a knotwork cartouche (bespoke/exclusive access) |
| PwPrivateEquity | Private Equity | two scroll vines converging into a sprouting bud inside a pointed arch (capital meets growth) |
| PwRealEstate | Real Estate | multifoil-arch skyline with jharoka window forms over scrollwork ground |
| PwPakistanEnergy | Pakistan Energy | radial girih sunburst over an arch-framed derrick abstraction; acid hairline = energy flow |
| PwPhilanthropyZakat | Philanthropy & Zakat | eight-petal rosette dispersing petals outward above a basin form (structured, purposeful giving) |
| PwProfessionalServices | Professional Services | ruled ledger cartouche with interlaced border and a star seal (chartered rigor) |
| SsFamilyOffice | BIG Family Office | ONE monumental 8-point star assembled from four interlocking quadrants (family branches, one mark) |
| SsAlternatives | Alternative Investments | bold rotated octagon breaking out of a square grid; acid counter-form (beyond public markets) |
| SsMergersAcquisitions | M&A and Carve-Out | two heavy interlocking crescents forming one continuous form; offset acid cut line (union + carve-out) |
| SsIpos | IPOs (Pakistan & US) | stacked blocks narrowing upward to an 8-point star at apex over TWO baselines (PSX & US listing ascent) |
| AboutConstruction | About statement | compass-and-straightedge construction of an 8-point star, mid-draw |
| ContactPortal | Contact intro | open mihrab doorway with an acid threshold line (open door, welcome) |
| LoginJali | Client portal | square mashrabiya/jali screen emblem (privacy, secure access) |
| InsightsTileField | Insights header | field of crescent-star tiles, exactly one acid tile ("the signal in the field") |

## 5. Shared-file rule (workflow agents)

Workflow agents may edit ONLY: (a) illustration components belonging to their own page/track, (b) their own page
`.astro` file, (c) their own manifest JSON. `ServiceShowcase.astro`, `GeometricPattern.astro`, `global.css`,
`tailwind.config.mjs`, layouts, and any other shared file are OFF-LIMITS — if a change there seems needed, record it
in the manifest under `escalations` instead of editing.
