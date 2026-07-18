<?php
/**
 * SMTP + recipient settings for the BIG contact & newsletter forms.
 *
 * ┌─────────────────────────────────────────────────────────────────────────┐
 * │  FILL IN THE PLACEHOLDER VALUES BELOW AFTER UPLOADING TO YOUR HOST.        │
 * │  Use the SMTP credentials from your email provider — your own domain      │
 * │  mailbox (e.g. mail.blackstoneislamicglobal.com), Zoho, Outlook, or       │
 * │  Gmail with an "App Password".                                            │
 * └─────────────────────────────────────────────────────────────────────────┘
 *
 * SECURITY NOTES
 *  - Replace the placeholders on the SERVER. Do NOT commit real credentials to git.
 *  - Ideally move this file ABOVE the public web root and update the include path
 *    in contact.php / newsletter.php (e.g. require '../../mail-config.php').
 *  - PHP source is executed, not served as text, so a direct request to this file
 *    returns nothing — but keep it out of version control once real values are in.
 */

return [
    // ── SMTP server (from your email host) ──────────────────────────────────
    'host'       => 'smtp.example.com',      // e.g. smtp.zoho.com, smtp.gmail.com, mail.yourdomain.com
    'port'       => 587,                       // 587 = STARTTLS (recommended) · 465 = implicit SSL
    'secure'     => 'tls',                     // 'tls' for port 587 · 'ssl' for port 465
    'username'   => 'you@yourdomain.com',      // SMTP login
    'password'   => 'CHANGE_ME',               // SMTP password / app password

    // ── Addresses ───────────────────────────────────────────────────────────
    'from_email' => 'website@yourdomain.com',  // "From" address on the email (often same as username)
    'from_name'  => 'BIG Website',
    'to_email'   => 'leads@yourdomain.com',    // WHERE LEADS ARE DELIVERED — your inbox
    'to_name'    => 'Black Stone Islamic Global',

    // ── Optional ────────────────────────────────────────────────────────────
    'ehlo'       => 'blackstoneislamicglobal.com', // EHLO hostname (safe to leave as-is)
];
