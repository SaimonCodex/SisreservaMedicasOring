<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$email = 'admin@clinica.com';
$user = App\Models\Usuario::where('correo', $email)->first();

echo "Verifying validation for User: " . $user->correo . "\n";
echo "Current Hash: " . $user->password . "\n";

$exists = App\Models\HistorialPassword::where('user_id', $user->id)
    ->where('password_hash', $user->password)
    ->exists();

if ($exists) {
    echo "SUCCESS: Current password found in history. Resetting to SAME password will be BLOCKED.\n";
} else {
    echo "ERROR: Current password NOT found in history. Validation will FAIL (allow reuse).\n";
}

$activeCount = App\Models\HistorialPassword::where('user_id', $user->id)->where('status', true)->count();
echo "Active History Entries: " . $activeCount . " (Should be 1)\n";
