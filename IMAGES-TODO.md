# Images TODO — Pexels assets to download

The client supplied ~35 Pexels URLs. These are **webpage links, not direct image files**, and the
Pexels licence expects download-and-host rather than hotlinking. The site currently still uses its
existing Unsplash photos; nothing is broken.

## How to use this

1. Open each Pexels URL below, click **Free download**, choose a large size (≥1600px wide).
2. Save into `big-website/public/images/` using the **exact filename** in the Filename column.
3. Tell Claude "images are in" and each page will be repointed from Unsplash to `/images/<file>`.

Only the rows you download will be wired up — partial delivery is fine.

---

## Homepage / brand

| Section | Filename | Pexels URL | Notes |
|---|---|---|---|
| Hero (video) | `hero.mp4` | https://www.pexels.com/video/vibrant-display-of-international-flags-in-qatar-36692221/ | ⚠️ **This is a video, not a photo.** The hero currently uses a static background image. Using it needs a `<video>` element — a small code change, not just a swap. Confirm you want this. |

## PSX & Global Markets — `/what-we-do/psx-global-markets`

| Section id | Filename | Pexels URL |
|---|---|---|
| `shariah-equities` | `psx-shariah-equities.jpg` | https://www.pexels.com/photo/three-men-discussing-a-finance-report-7876672/ |
| `portfolio-consultancy` | `psx-portfolio-consultancy.jpg` | https://www.pexels.com/photo/handshake-while-holding-a-tablet-5833876/ |
| `portfolio-supervision` | `psx-portfolio-supervision.jpg` | https://www.pexels.com/photo/a-man-in-black-suit-presenting-using-a-tablet-device-12885860/ |
| `transaction-services` | `psx-transaction-services.jpg` | https://www.pexels.com/photo/colorful-wool-yarn-textures-in-vibrant-stripes-35977640/ |
| `blackstone-intel` | `psx-blackstone-intel.jpg` | https://www.pexels.com/photo/orange-ball-device-20323501/ **or** https://www.pexels.com/photo/white-and-black-robot-toy-9028868/ — ⚠️ two URLs given for one section; pick one |

> `green-sukuks` (Sukuks and Green Sukuks) — **no image supplied** in the deck. Keeping the current photo.

## Asset Management — `/what-we-do/asset-management`

| Section id | Filename | Pexels URL |
|---|---|---|
| pillar hero | `am-hero.jpg` | https://www.pexels.com/photo/low-angle-shot-of-modern-skyscrapers-under-blue-sky-18837865/ |
| `am-fixed-income` | `am-fixed-income.jpg` | https://www.pexels.com/photo/aerial-view-of-large-group-in-colorful-prayer-38944978/ |
| `am-equity` | `am-equity.jpg` | https://www.pexels.com/photo/stunning-view-of-abraj-al-bait-in-makkah-31105900/ |
| `am-etfs` | `am-etf.jpg` | https://www.pexels.com/photo/stunning-architecture-of-masjid-99-kubah-30466254/ |
| `am-multi-asset` | `am-multi-asset.jpg` | https://www.pexels.com/photo/colorful-mosque-domes-in-banten-indonesia-31530663/ |
| `am-pensions` | `am-pensions.jpg` | https://www.pexels.com/photo/person-putting-golf-ball-on-a-tee-6572963/ |

## Private Wealth — `/what-we-do/private-wealth`

| Section id | Filename | Pexels URL |
|---|---|---|
| `private-equity` | `pw-private-equity.jpg` | https://www.pexels.com/photo/yellow-stunt-biplane-performing-aerobatics-in-sky-34303415/ |
| `pakistan-energy` | `pw-pakistan-energy.jpg` | https://www.pexels.com/photo/photo-of-gas-pumps-11116153/ |
| `real-estate` | `pw-real-estate.jpg` | https://www.pexels.com/photo/colorful-geometric-dome-at-sunset-39045529/ |

> `wealth-planning` — **no image supplied**. Keeping the current photo.

## Specialized Services — `/what-we-do/specialized-services`

| Section id | Filename | Pexels URL |
|---|---|---|
| pillar hero | `ss-hero.jpg` | https://www.pexels.com/photo/ferrari-race-car-pit-stop-in-sao-paulo-31854171/ |
| `family-office` | `ss-basirah-family-office.jpg` | https://www.pexels.com/photo/back-view-shot-of-a-person-painting-autumn-scenery-while-standing-on-the-road-side-6262988/ |
| `lifestyle-management` | `ss-lifestyle-management.jpg` | https://www.pexels.com/photo/artist-painting-floral-design-on-paper-31594871/ |
| `pr-campaign` | `ss-pr-campaign.jpg` | https://www.pexels.com/photo/illuminated-neon-on-air-studio-sign-close-up-34368131/ |
| `philanthropy-zakat` | `ss-philanthropy-zakat.jpg` | https://www.pexels.com/photo/joyful-children-embracing-outdoors-33786883/ |
| `mergers-acquisitions` | `ss-mergers-acquisitions.jpg` | https://www.pexels.com/photo/intricate-butternut-squash-flower-carving-33541996/ |
| `ipos` | `ss-initial-public-offer.jpg` | https://www.pexels.com/photo/seattle-public-market-sign-against-blue-sky-37296453/ |
| `professional-services` | `ss-professional-services.jpg` | https://www.pexels.com/photo/colorful-cricket-balls-on-green-grass-34962064/ |

---

## Open questions

- **Hero video** — the Qatar flags asset is a video. Confirm whether the homepage hero should
  become a video background (code change) or stay a still image.
- **Blackstone Intel** — two Pexels URLs were supplied for one section; pick one.
- **Missing images** — `green-sukuks` and `wealth-planning` have no supplied asset; they keep their
  current photos unless you supply replacements.
- **`og-image.jpg`** — still missing from `public/images/` (pre-existing Phase 4.5 bug). A branded
  1200×630 image is needed for social sharing.
