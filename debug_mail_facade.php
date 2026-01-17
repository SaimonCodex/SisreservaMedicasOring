<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

echo "LARAVEL MAIL TEST START\n";
echo "Transport: " . config('mail.mailers.smtp.transport') . "\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Username: " . config('mail.mailers.smtp.username') . "\n";
echo "Encryption: " . config('mail.mailers.smtp.encryption') . "\n";

try {
    Mail::raw('This is a test email sent via Laravel Mail Facade.', function ($message) {
        $message->to('test@example.com')
                ->subject('Laravel Facade Test - ' . now());
    });
    echo "MAIL_RAW_SENT_SUCCESSFULLY\n";
} catch (\Exception $e) {
    echo "MAIL_RAW_ERROR: " . $e->getMessage() . "\n";
}
