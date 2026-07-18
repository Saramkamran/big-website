<?php
/**
 * Newsletter signup handler for the BIG website.
 * The newsletter band (src/components/NewsletterBand.astro) POSTs here.
 * On success it redirects to /?newsletter=sent#newsletter.
 */

$config = require __DIR__ . '/mail-config.php';
require __DIR__ . '/smtp-mailer.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: /');
    exit;
}

// Honeypot.
if (!empty($_POST['company'])) {
    header('Location: /?newsletter=sent#newsletter');
    exit;
}

$email = trim((string) ($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /?newsletter=error#newsletter');
    exit;
}

$body = implode("\n", [
    'New newsletter subscription',
    str_repeat('-', 44),
    "Email: $email",
    'Sent ' . date('r') . ' from ' . ($_SERVER['HTTP_HOST'] ?? 'website'),
]);

[$ok, $err] = send_smtp_mail($config, 'BIG newsletter signup', $body, $email);

if ($ok) {
    header('Location: /?newsletter=sent#newsletter');
} else {
    error_log('[BIG newsletter] ' . $err);
    header('Location: /?newsletter=error#newsletter');
}
exit;
