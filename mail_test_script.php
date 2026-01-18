<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Starting Mail Test...\n";

try {
    Illuminate\Support\Facades\Mail::raw('Test Email Content', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Subject');
    });
    echo "SUCCESS: Mail sent successfully via Mailtrap.\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
