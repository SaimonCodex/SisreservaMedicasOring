<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://localhost/sisreservamedicasoring/public/recovery/send-email");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['email' => 'test@example.com'])); // Use likely existing email or handle 404
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);

// Get CSRF token? The route might verify it. 
// Ah, the route is web, so it uses VerifyCsrfToken middleware.
// I should probably use the /test-mail route instead which is easier to test SMTP.
// But I want to test the specific controller.
// Let's test /test-mail first to rule out SMTP.

curl_setopt($ch, CURLOPT_URL, "http://localhost/sisreservamedicasoring/public/test-mail");
curl_setopt($ch, CURLOPT_POST, 0); // GET
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo "HTTP Code: $httpcode\n";
    echo "Response: $response\n";
}

curl_close($ch);
