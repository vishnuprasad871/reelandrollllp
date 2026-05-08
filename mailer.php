<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'reelrollphotography26@gmail.com');
define('SMTP_PASS', 'kjpnwpoxxwodvvoa');
define('TO_EMAIL',  'reelrollphotography26@gmail.com');
define('TO_NAME',   'Reel and Roll Photography');

// Sanitize input
function clean($val) {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

$name    = clean($_POST['name']    ?? '');
$email   = clean($_POST['email']   ?? '');
$phone   = clean($_POST['phone']   ?? 'Not provided');
$service = clean($_POST['service'] ?? '');
$date    = clean($_POST['date']    ?? 'Not specified');
$message = clean($_POST['message'] ?? '');

if (!$name || !$email || !$message || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Build email body
$subject  = "New Booking Enquiry from $name";
$body     = "Name:    $name\r\nEmail:   $email\r\nPhone:   $phone\r\nService: $service\r\nDate:    $date\r\n\r\nMessage:\r\n$message";
$boundary = md5(uniqid());

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "From: Reel and Roll <" . SMTP_USER . ">\r\n";
$headers .= "Reply-To: $name <" . $_POST['email'] . ">\r\n";

// Send via SMTP with STARTTLS
function smtp_send($host, $port, $user, $pass, $to_email, $to_name, $subject, $body, $headers) {
    $sock = fsockopen($host, $port, $errno, $errstr, 15);
    if (!$sock) return "Connection failed: $errstr ($errno)";

    function smtp_cmd($sock, $cmd, $expect) {
        if ($cmd) fwrite($sock, $cmd . "\r\n");
        $res = '';
        while ($line = fgets($sock, 512)) {
            $res .= $line;
            if ($line[3] === ' ') break;
        }
        $code = substr($res, 0, 3);
        if ($code != $expect) return "Expected $expect, got: $res";
        return null;
    }

    // Read greeting
    $res = '';
    while ($line = fgets($sock, 512)) { $res .= $line; if ($line[3] === ' ') break; }

    $err = smtp_cmd($sock, "EHLO " . gethostname(), '250');       if ($err) { fclose($sock); return $err; }
    $err = smtp_cmd($sock, "STARTTLS", '220');                     if ($err) { fclose($sock); return $err; }

    // Upgrade to TLS
    stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

    $err = smtp_cmd($sock, "EHLO " . gethostname(), '250');        if ($err) { fclose($sock); return $err; }
    $err = smtp_cmd($sock, "AUTH LOGIN", '334');                   if ($err) { fclose($sock); return $err; }
    $err = smtp_cmd($sock, base64_encode($user), '334');           if ($err) { fclose($sock); return $err; }
    $err = smtp_cmd($sock, base64_encode($pass), '235');           if ($err) { fclose($sock); return $err; }
    $err = smtp_cmd($sock, "MAIL FROM:<$user>", '250');            if ($err) { fclose($sock); return $err; }
    $err = smtp_cmd($sock, "RCPT TO:<$to_email>", '250');          if ($err) { fclose($sock); return $err; }
    $err = smtp_cmd($sock, "DATA", '354');                         if ($err) { fclose($sock); return $err; }

    $msg = "To: $to_name <$to_email>\r\n"
         . "Subject: $subject\r\n"
         . $headers
         . "\r\n"
         . $body
         . "\r\n.";
    $err = smtp_cmd($sock, $msg, '250');                           if ($err) { fclose($sock); return $err; }

    smtp_cmd($sock, "QUIT", '221');
    fclose($sock);
    return null;
}

$error = smtp_send(SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, TO_EMAIL, TO_NAME, $subject, $body, $headers);

if ($error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send email. Please contact us directly.']);
    error_log("Mailer error: $error");
} else {
    echo json_encode(['success' => true, 'message' => "Message sent! We'll get back to you within 24 hours."]);
}
