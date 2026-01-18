<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$email = 'admin@clinica.com'; // Using the email found earlier
$user = App\Models\Usuario::where('correo', $email)->first();

if ($user) {
    echo "User ID: " . $user->id . "\n";
    echo "Current Password Hash: " . $user->password . "\n";
    
    $history = App\Models\HistorialPassword::where('user_id', $user->id)->get();
    echo "History Count: " . $history->count() . "\n";
    foreach ($history as $h) {
        echo " - ID: {$h->id}, Hash: {$h->password_hash}, Status: {$h->status}, Created: {$h->created_at}\n";
    }
} else {
    echo "User not found\n";
}
