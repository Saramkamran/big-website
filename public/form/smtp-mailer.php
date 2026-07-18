<?php
/**
 * Minimal, dependency-free SMTP mailer for BIG.
 * -------------------------------------------------------------------------
 * Sends a UTF-8 plain-text email over an AUTHENTICATED SMTP connection
 * (STARTTLS on 587 or implicit SSL on 465). No third-party library, no
 * database — works on any PHP 7.4+ host with sockets enabled.
 *
 * Prefer PHPMailer instead? On the host run:
 *     composer require phpmailer/phpmailer
 * then replace the call to send_smtp_mail() in contact.php / newsletter.php
 * with a PHPMailer instance configured from mail-config.php. Nothing else
 * in the handlers needs to change.
 * -------------------------------------------------------------------------
 */

/** Read one (possibly multi-line) SMTP reply. */
function _smtp_read($fp): string {
    $data = '';
    while (($line = fgets($fp, 515)) !== false) {
        $data .= $line;
        // In multi-line replies the 4th char is '-'; a space means the final line.
        if (isset($line[3]) && $line[3] === ' ') {
            break;
        }
    }
    return $data;
}

/**
 * Send a command (or read only, when $cmd is null) and assert the reply code.
 * @param int|int[] $expect  Acceptable leading 3-digit reply code(s).
 * @return array{0:bool,1:string}
 */
function _smtp_cmd($fp, ?string $cmd, $expect): array {
    if ($cmd !== null) {
        fwrite($fp, $cmd . "\r\n");
    }
    $resp = _smtp_read($fp);
    $code = (int) substr($resp, 0, 3);
    if (!in_array($code, (array) $expect, true)) {
        $want = implode('/', (array) $expect);
        return [false, "Expected $want, got: " . trim($resp)];
    }
    return [true, $resp];
}

/**
 * @param array  $cfg          Settings from mail-config.php.
 * @param string $subject      Email subject (UTF-8).
 * @param string $body         Email body, plain text (UTF-8).
 * @param string $replyToEmail Optional Reply-To (the lead's email).
 * @param string $replyToName  Optional Reply-To display name.
 * @return array{0:bool,1:string}  [success, errorMessage]
 */
function send_smtp_mail(array $cfg, string $subject, string $body, ?string $replyToEmail = null, ?string $replyToName = null): array {
    // Strip CR/LF from any value that lands in a header (prevents header injection).
    $strip = static fn($s) => str_replace(["\r", "\n"], '', (string) $s);

    $host      = $strip($cfg['host'] ?? '');
    $port      = (int) ($cfg['port'] ?? 587);
    $secure    = strtolower($strip($cfg['secure'] ?? 'tls')); // 'tls' | 'ssl'
    $user      = $strip($cfg['username'] ?? '');
    $pass      = (string) ($cfg['password'] ?? '');            // only sent during AUTH, never in a header
    $fromEmail = $strip($cfg['from_email'] ?? $user);
    $fromName  = $strip($cfg['from_name'] ?? 'Website');
    $toEmail   = $strip($cfg['to_email'] ?? '');
    $toName    = $strip($cfg['to_name'] ?? '');
    $ehlo      = $strip($cfg['ehlo'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost'));

    if ($host === '' || $user === '' || $toEmail === '') {
        return [false, 'Mailer not configured — fill in mail-config.php.'];
    }

    $transport = ($secure === 'ssl') ? "ssl://$host" : "tcp://$host";
    $ctx = stream_context_create(['ssl' => ['verify_peer' => true, 'verify_peer_name' => true]]);
    $fp = @stream_socket_client("$transport:$port", $errno, $errstr, 20, STREAM_CLIENT_CONNECT, $ctx);
    if (!$fp) {
        return [false, "Connection failed: $errstr ($errno)"];
    }
    stream_set_timeout($fp, 20);

    // Greeting → EHLO
    [$ok, $e] = _smtp_cmd($fp, null, 220);            if (!$ok) { fclose($fp); return [false, $e]; }
    [$ok, $e] = _smtp_cmd($fp, "EHLO $ehlo", 250);    if (!$ok) { fclose($fp); return [false, $e]; }

    // STARTTLS upgrade for port 587
    if ($secure === 'tls') {
        [$ok, $e] = _smtp_cmd($fp, 'STARTTLS', 220);  if (!$ok) { fclose($fp); return [false, $e]; }
        $crypto = STREAM_CRYPTO_METHOD_TLS_CLIENT;
        if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
            $crypto |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
        }
        if (!@stream_socket_enable_crypto($fp, true, $crypto)) {
            fclose($fp); return [false, 'TLS negotiation failed'];
        }
        [$ok, $e] = _smtp_cmd($fp, "EHLO $ehlo", 250); if (!$ok) { fclose($fp); return [false, $e]; }
    }

    // AUTH LOGIN
    [$ok, $e] = _smtp_cmd($fp, 'AUTH LOGIN', 334);            if (!$ok) { fclose($fp); return [false, $e]; }
    [$ok, $e] = _smtp_cmd($fp, base64_encode($user), 334);   if (!$ok) { fclose($fp); return [false, $e]; }
    [$ok, $e] = _smtp_cmd($fp, base64_encode($pass), 235);   if (!$ok) { fclose($fp); return [false, 'Authentication failed: ' . $e]; }

    // Envelope
    [$ok, $e] = _smtp_cmd($fp, "MAIL FROM:<$fromEmail>", 250);      if (!$ok) { fclose($fp); return [false, $e]; }
    [$ok, $e] = _smtp_cmd($fp, "RCPT TO:<$toEmail>", [250, 251]);   if (!$ok) { fclose($fp); return [false, $e]; }
    [$ok, $e] = _smtp_cmd($fp, 'DATA', 354);                        if (!$ok) { fclose($fp); return [false, $e]; }

    // Headers (all UTF-8 safe via MIME base64 encoding of names/subject)
    $mime = static fn($s) => '=?UTF-8?B?' . base64_encode($s) . '?=';
    $messageId = '<' . bin2hex(random_bytes(16)) . '@' . $host . '>';

    $headers = [
        'Date: ' . date('r'),
        'Message-ID: ' . $messageId,
        'From: ' . $mime($fromName) . " <$fromEmail>",
        'To: ' . ($toName !== '' ? $mime($toName) . ' ' : '') . "<$toEmail>",
    ];
    if ($replyToEmail) {
        $rt = $strip($replyToEmail);
        $rtName = $replyToName ? $mime($strip($replyToName)) . ' ' : '';
        $headers[] = "Reply-To: {$rtName}<$rt>";
    }
    $headers[] = 'Subject: ' . $mime($subject);
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'Content-Transfer-Encoding: base64';

    // base64 body: no line can start with '.', so dot-stuffing is unnecessary.
    $encodedBody = rtrim(chunk_split(base64_encode($body)));
    $payload = implode("\r\n", $headers) . "\r\n\r\n" . $encodedBody . "\r\n.";

    [$ok, $e] = _smtp_cmd($fp, $payload, 250); if (!$ok) { fclose($fp); return [false, $e]; }

    _smtp_cmd($fp, 'QUIT', 221);
    fclose($fp);
    return [true, ''];
}
