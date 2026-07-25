# BIG Layout Variants — Section System

`src/components/ServiceShowcase.astro` renders every what-we-do service as its own `<section id={service.id}>`
in one of 7 named variants. **Site-wide cap: no variant used more than 4 times** (one use = one rendered
service/page-section instance). Heroes, AboutBIG, ContactCTA, the contact form, and page chrome are exempt.

## The 7 variants

| # | `layout` | Structure |
|---|---|---|
| 1 | `split-left` | square art in a sticky 5-col left rail, title+copy in 7-col right |
| 2 | `split-right` | mirror of split-left |
| 3 | `band` | full-width `mist`/`ink` band; wide (960×360) SVG bleeds off the right edge at ~40% opacity; copy in a ~3xl measure |
| 4 | `offset-card` | copy inside a bordered paper card sitting on a `GeometricPattern` strip; tall (360×540) SVG overlaps the card's top-right corner (desktop) |
| 5 | `emblem` | centered square emblem above a centered title; copy flows in two symmetric columns |
| 6 | `mosaic` | gapless framed tile grid: art tile / title+copy tile / subService tiles / acid accent strip |
| 7 | `ledger` | compact ruled row: small emblem rail + oversized serif index numeral + title + copy |

## Separator styles (per-page rhythm, `separator` prop)

`hairline` single 1px line · `double-rule` two 1px lines 4px apart · `ornament` centered 8-point star glyph between
flanking hairlines · `heavy` 4px ink rule · `none`. Separators render only between two consecutive **paper**-tone
sections (tone changes act as separators themselves).

## Site-wide allocation (pre-solved; auditor enforces ≤4)

| Variant | Uses |
|---|---|
| split-left | AmFixedIncome · PwPrivateEquity · SsAlternatives (3) |
| split-right | PsxGreenSukuks · PwDedicatedInvestments · SsIpos (3) |
| band | PsxTransactionServices(mist) · AmEquity(ink) · PwPakistanEnergy(mist) · SsFamilyOffice(ink) (4) |
| offset-card | AmEtfs(mist, scallop) · PwRealEstate(paper, star8) · SsMergersAcquisitions(paper, girih) (3) |
| emblem | AmPensions(mist) · PwWealthPlanning · PwPhilanthropyZakat · About "Our Approach" header (4) |
| mosaic | PsxShariahEquities (subServices as tiles) · AmMultiAsset (2) |
| ledger | PsxBlackstoneIntel(ink) · PsxBlackstoneInvestments(ink) · PwProfessionalServices (3) |

## Page order & feel

| Page | Feel | separator | Section order (layout / tone) |
|---|---|---|---|
| psx-global-markets | "precision observatory" | `double-rule` | shariah-equities mosaic/paper → green-sukuks split-right/paper → transaction-services band/mist → blackstone-intel ledger/ink → blackstone-investments ledger/ink |
| asset-management | "layered duotone planes" | `none` | am-fixed-income split-left/paper → am-equity band/ink → am-etfs offset-card/mist → am-multi-asset mosaic/paper → am-pensions emblem/mist |
| private-wealth | "ornamental salon" | `ornament` | wealth-planning emblem/paper → dedicated-investments split-right/paper → private-equity split-left/paper → real-estate offset-card/paper → pakistan-energy band/mist → philanthropy-zakat emblem/paper → professional-services ledger/paper |
| specialized-services | "bold monolith gallery" | `heavy` | family-office band/ink → alternatives split-left/paper → mergers-acquisitions offset-card/paper → ipos split-right/paper |
| about | "drafting-table geometry" | — | bespoke: AboutConstruction behind statement · star accreditation bullets · arch-clipped leadership photos · emblem-header on Our Approach |
| contact | "open door" | — | bespoke: ContactPortal over intro · girih map tile with acid star · **form subtree byte-identical** |
| login | "private gate" | — | bespoke: ink viewport + jali pattern bg + LoginJali card emblem |
| insights | "signal in the field" | — | bespoke: InsightsTileField behind header · grid/chips untouched |

Distinctness rule (auditor): no two pages may share the same **separator + tone-rhythm + art-style** triple.

## Swap table (applied on a >4 violation, in this order)

- `band` over cap → demote PwPakistanEnergy to `split-left`
- `emblem` over cap → demote the About "Our Approach" header to a plain centered heading
- `split-left`/`split-right` over cap → swap the offending section to the mirror variant
- `offset-card` over cap → SsMergersAcquisitions to `band` if band has headroom, else `split-left`

## Hard invariants (every agent, every round)

1. All 21 anchor ids exist exactly once on their page: `shariah-equities, green-sukuks, transaction-services,
   blackstone-intel, blackstone-investments, am-fixed-income, am-equity, am-etfs, am-multi-asset, am-pensions,
   wealth-planning, dedicated-investments, private-equity, real-estate, pakistan-energy, philanthropy-zakat,
   professional-services, family-office, alternatives, mergers-acquisitions, ipos`.
2. Service copy (title / body / subServices strings) is byte-identical to `design/baseline/<page>.astro`.
3. Acid is fills/hairlines/nodes only — never body text, never text on white.
4. Heading order h1 (hero) → h2 (pillar) → h3 (service) → h4 (subService) preserved.
5. Generous whitespace: section vertical padding never below `py-12`; most sections `py-20`+.
