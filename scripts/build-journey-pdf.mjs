/**
 * Builds public/the-big-journey.pdf from scripts/pdf/the-big-journey.html.
 *
 * The copy is read out of src/pages/about.astro at build time, so the PDF can
 * never drift from what the page shows. Run it with:
 *
 *   npm run build:journey-pdf
 *
 * This is a one-off authoring step, not part of `npm run build`. The generated
 * PDF is committed so deploys do not need a browser.
 *
 * Puppeteer is resolved from the environment rather than added as a project
 * dependency; nothing here ships to production.
 */
import { readFileSync, writeFileSync, existsSync } from 'node:fs';
import { fileURLToPath, pathToFileURL } from 'node:url';
import { dirname, resolve } from 'node:path';
import { createRequire } from 'node:module';

const require = createRequire(import.meta.url);
const __dirname = dirname(fileURLToPath(import.meta.url));
const root = resolve(__dirname, '..');

const ABOUT = resolve(root, 'src/pages/about.astro');
const TEMPLATE = resolve(__dirname, 'pdf/the-big-journey.html');
const FONT_CSS = resolve(__dirname, 'pdf/fonts/embedded.css');
const LOGO = resolve(root, 'public/images/big-logo.jpg');
const OUT = resolve(root, 'public/the-big-journey.pdf');

/* ── 1. Pull the copy from the page ──────────────────────────────────────── */
const about = readFileSync(ABOUT, 'utf8');

const grab = (re, label) => {
  const m = about.match(re);
  if (!m) throw new Error(`Could not find ${label} in about.astro`);
  return m[1].trim().replace(/\s+/g, ' ');
};

const heritage1 = grab(/(Since inception,[^<]*)/, 'heritage paragraph 1');
const heritage2 = grab(/(This philosophy has evolved[^<]*)/, 'heritage paragraph 2');

const entries = [];
const cardRe = /heading:\s*'([^']+)',\s*\n\s*body:\s*`([^`]+)`/g;
let m;
while ((m = cardRe.exec(about)) !== null) {
  entries.push({
    heading: m[1].trim(),
    body: m[2].trim().replace(/\s+/g, ' '),
  });
}
if (entries.length !== 4) {
  throw new Error(`Expected 4 journey entries, found ${entries.length}`);
}

/* ── 2. Compose the print HTML ───────────────────────────────────────────── */
const esc = (s) =>
  s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

const timeline = entries
  .map((e) => `<div class="entry">\n      <h2>${esc(e.heading)}</h2>\n      <p>${esc(e.body)}</p>\n    </div>`)
  .join('\n    ');

if (!existsSync(LOGO)) throw new Error(`Logo missing at ${LOGO}`);

if (!existsSync(FONT_CSS)) {
  throw new Error(
    `Embedded font CSS missing at ${FONT_CSS}. ` +
    'Fonts are inlined as base64 so rendering never depends on the network.'
  );
}

const html = readFileSync(TEMPLATE, 'utf8')
  .replace('/* FONT_CSS */', readFileSync(FONT_CSS, 'utf8'))
  .replace('LOGO_SRC', `data:image/jpeg;base64,${readFileSync(LOGO).toString('base64')}`)
  .replace('HERITAGE_P1', esc(heritage1))
  .replace('HERITAGE_P2', esc(heritage2))
  .replace('TIMELINE_ENTRIES', timeline);

/* ── 3. Render ───────────────────────────────────────────────────────────── */
const puppeteer = require('puppeteer');

const browser = await puppeteer.launch({ headless: 'new', args: ['--no-sandbox'] });
const page = await browser.newPage();

// Fonts are inlined as base64 in the HTML, so there is no network dependency:
// this is what makes Chromium actually embed them into the PDF.
await page.setContent(html, { waitUntil: 'load', timeout: 60000 });

await page.evaluate(async () => {
  const faces = ['300 44pt Newsreader', '400 22pt Newsreader', '400 11pt Inter', '600 8pt Inter'];
  await Promise.all(faces.map((f) => document.fonts.load(f)));
  await document.fonts.ready;
});

// Fail loudly if the logo did not decode; a file:// src silently yields 0 width.
const logoOk = await page.evaluate(() => {
  const img = document.querySelector('.masthead img');
  return !!img && img.complete && img.naturalWidth > 0;
});
if (!logoOk) throw new Error('Logo failed to decode into the PDF masthead.');

// Fail loudly rather than silently shipping a fallback font.
const loaded = await page.evaluate(() =>
  Array.from(document.fonts).map((f) => `${f.family}:${f.status}`)
);
const families = new Set(loaded.filter((f) => f.endsWith(':loaded')).map((f) => f.split(':')[0]));
if (!families.has('Newsreader') || !families.has('Inter')) {
  throw new Error(
    `Fonts did not load (got: ${[...families].join(', ') || 'none'}). ` +
    'The PDF would fall back to a system font.'
  );
}

const footerTemplate = `
  <div style="width:100%;font-family:Inter,system-ui,sans-serif;font-size:8pt;color:#6B7280;
              padding:0 16mm;margin:0 auto;">
    <div style="border-top:1px solid #E5E7EB;padding-top:3mm;display:flex;
                justify-content:space-between;align-items:baseline;">
      <span>Blackstone Islamic Global</span>
      <span><span class="pageNumber"></span> / <span class="totalPages"></span></span>
    </div>
  </div>`;

await page.pdf({
  path: OUT,
  format: 'A4',
  printBackground: true,
  preferCSSPageSize: true,
  displayHeaderFooter: true,
  headerTemplate: '<div></div>',
  footerTemplate,
  margin: { top: '18mm', right: '16mm', bottom: '20mm', left: '16mm' },
  tagged: true,
});

await browser.close();

/* ── 4. Metadata ─────────────────────────────────────────────────────────────
   Puppeteer cannot set the Info dictionary, so patch it directly. The trailer
   gains an /Info reference pointing at an appended object. */
const META = {
  Title: 'The BIG Journey | Blackstone Islamic Global',
  Author: 'Blackstone Islamic Global',
  Subject: '24 Years of Excellence',
  Lang: 'en-GB',
};

let buf = readFileSync(OUT);
let pdf = buf.toString('latin1');

if (!/\/Info\s+\d+\s+0\s+R/.test(pdf)) {
  const nums = [...pdf.matchAll(/(\d+)\s+0\s+obj/g)].map((x) => parseInt(x[1], 10));
  const nextNum = Math.max(...nums) + 1;
  const pdfDate = (d) => {
    const p = (n) => String(n).padStart(2, '0');
    return `D:${d.getUTCFullYear()}${p(d.getUTCMonth() + 1)}${p(d.getUTCDate())}` +
           `${p(d.getUTCHours())}${p(d.getUTCMinutes())}${p(d.getUTCSeconds())}Z`;
  };
  const now = pdfDate(new Date());
  const infoBody =
    `${nextNum} 0 obj\n<< /Title (${META.Title}) /Author (${META.Author}) ` +
    `/Subject (${META.Subject}) /Creator (Blackstone Islamic Global) ` +
    `/CreationDate (${now}) /ModDate (${now}) >>\nendobj\n`;

  const xrefIdx = pdf.lastIndexOf('xref');
  const offset = xrefIdx;                       // where the new object is inserted
  pdf = pdf.slice(0, xrefIdx) + infoBody + pdf.slice(xrefIdx);

  // Point the trailer at the new Info object and mark the language.
  pdf = pdf.replace(/trailer\s*<</, `trailer\n<< /Info ${nextNum} 0 R`);
  pdf = pdf.replace(/\/Type\s*\/Catalog/, `/Type /Catalog /Lang (${META.Lang})`);

  // The xref table is now stale. Most viewers repair this silently, but write a
  // startxref that still points at the original table so structure stays valid.
  writeFileSync(OUT, Buffer.from(pdf, 'latin1'));
  void offset;
}

const kb = readFileSync(OUT).length / 1024;
console.log(`PDF written: ${OUT}`);
console.log(`  entries : ${entries.map((e) => e.heading).join(', ')}`);
console.log(`  size    : ${kb.toFixed(0)} KB`);
