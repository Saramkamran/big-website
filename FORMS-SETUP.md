# Contact & Newsletter Forms — Setup (PHP + SMTP)

The site uses **PHP to email you form submissions directly** — no third-party service, no
database. Two handlers live in `public/form/` (they build into `dist/form/`):

| File | What it does |
|------|--------------|
| `contact.php` | Receives the contact form, emails you the lead, redirects to `/contact?sent=true`. |
| `newsletter.php` | Receives newsletter signups, emails you, redirects to `/?newsletter=sent#newsletter`. |
| `smtp-mailer.php` | Dependency-free authenticated-SMTP sender (shared by both). |
| `mail-config.php` | **Your SMTP credentials + where leads are delivered.** Edit this. |

## Requirements

- **PHP 7.4 or newer** on the host (any standard cPanel / shared host / VPS — NOT Netlify/Vercel).
- An **email mailbox with SMTP access** — your own domain mailbox (recommended), or Zoho / Outlook /
  Gmail (Gmail needs an "App Password", not your normal password).

## Setup steps

1. **Build the site:** `cd big-website && npm run build` → deploy the contents of `dist/` to your
   PHP host's web root (e.g. `public_html/`). Make sure the `dist/form/` folder is included.
2. **Open `form/mail-config.php`** on the server (cPanel → File Manager, or edit before upload) and
   fill in the placeholder values:
   - `host`, `port`, `secure` — your mail provider's SMTP server (587/`tls` is typical).
   - `username`, `password` — the mailbox SMTP login.
   - `from_email` — the address the email is sent *from* (often the same as `username`).
   - `to_email` — **the inbox where you want to receive leads.**
3. **Test:** submit the contact form on the live site. You should get an email and land on the
   "Thank you" screen. If not, see Troubleshooting.

## How it behaves

- **Success:** contact → `/contact?sent=true` (thank-you panel); newsletter → thank-you inline.
- **Bad input:** redirects back with `?error=validation` / `?newsletter=error` and shows a message.
- **Send failure:** redirects with `?error=send`; the technical reason is written to the PHP error
  log (cPanel → Errors, or `error_log`).
- **Spam:** each form has a hidden "honeypot" field; bots that fill it are silently dropped.
- Reply-To is set to the lead's email, so hitting "Reply" in your inbox replies to them.

## Security notes

- `mail-config.php` holds your password. **Only deploy `dist/` to the PHP host** — if the files are
  ever served by a non-PHP host, the config could be exposed as plain text.
- Best practice: move `mail-config.php` *above* the web root and update the `require` path at the top
  of `contact.php` / `newsletter.php` (e.g. `require __DIR__ . '/../../mail-config.php';`).
- Avoid committing real credentials to git. Consider adding `public/form/mail-config.php` to
  `.gitignore` once you've put real values in it on the server.

## Troubleshooting

- **500 error on submit:** PHP version too old, or `mail-config.php` missing → check host PHP is 7.4+.
- **No email arrives / `?error=send`:** wrong SMTP host/port/credentials, or the host blocks outbound
  SMTP. Confirm the mailbox SMTP settings; try port 465 with `secure => 'ssl'` if 587 fails.
- **Gmail auth fails:** you must use an **App Password** (Google Account → Security → App Passwords),
  not your login password.

## Prefer PHPMailer instead of the built-in sender?

On the host run `composer require phpmailer/phpmailer`, then swap the `send_smtp_mail(...)` call in
the two handlers for a PHPMailer instance configured from `mail-config.php`. Nothing else changes.
