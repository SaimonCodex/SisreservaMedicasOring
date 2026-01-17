<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "MAIL CONFIG:\n";
print_r(config('mail.from'));
echo "\nTesting Socket Connectivity...\n";

$fp = @fsockopen('sandbox.smtp.mailtrap.io', 2525, $errno, $errstr, 10);
if (!$fp) {
    echo "ERROR: $errstr ($errno)\n";
} else {
    echo "SUCCESS: Connected to Mailtrap on port 2525\n";
    fclose($fp);
}

echo "\nSending RAW Test Email...\n";
try {
    Mail::raw('Debug Test Content', function ($message) {
        $message->to('test@example.com')
                ->subject('Debug Test - ' . now());
    });
    echo "MAIL_SENT_SUCCESSFULLY\n";
} catch (\Exception $e) {
    echo "MAIL_SEND_ERROR: " . $e->getMessage() . "\n";
}
