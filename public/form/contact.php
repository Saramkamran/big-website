<?php
/**
 * Contact form handler for the BIG website.
 * The contact form (src/pages/contact.astro) POSTs here. On success it
 * redirects to /contact?sent=true; on failure to /contact?error=...
 */

$config = require __DIR__ . '/mail-config.php';
require __DIR__ . '/smtp-mailer.php';

// Accept POST only.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: /contact');
    exit;
}

// Honeypot: real users never see/fill "company". Bots do → pretend success, send nothing.
if (!empty($_POST['company'])) {
    header('Location: /contact?sent=true');
    exit;
}

$field = static fn(string $k): string => trim((string) ($_POST[$k] ?? ''));

$name    = $field('name');
$email   = $field('email');
$phone   = $field('phone');
$subject = $field('subject');
$service = $field('service');
$message = $field('message');

// Server-side validation (mirrors the required fields in the form).
$errors = [];
if ($name === '')    { $errors[] = 'name'; }
if ($subject === '') { $errors[] = 'subject'; }
if ($message === '') { $errors[] = 'message'; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'email'; }

if ($errors) {
    header('Location: /contact?error=validation');
    exit;
}

$body = implode("\n", [
    'New contact enquiry from the BIG website',
    str_repeat('-', 44),
    "Name:    $name",
    "Email:   $email",
    'Phone:   ' . ($phone   !== '' ? $phone   : '—'),
    "Subject: $subject",
    'Service: ' . ($service !== '' ? $service : '—'),
    '',
    'Message:',
    $message,
    '',
    str_repeat('-', 44),
    'Sent ' . date('r') . ' from ' . ($_SERVER['HTTP_HOST'] ?? 'website'),
]);

[$ok, $err] = send_smtp_mail($config, "BIG enquiry: $subject", $body, $email, $name);

if ($ok) {
    header('Location: /contact?sent=true');
} else {
    error_log('[BIG contact form] ' . $err);
    header('Location: /contact?error=send');
}
exit;
