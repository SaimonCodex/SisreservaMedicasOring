<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$email = 'admin@clinica.com';
$user = App\Models\Usuario::where('correo', $email)->first();

if ($user) {
    echo "Cleaning history for User ID: " . $user->id . "\n";
    
    // Get all history
    $histories = App\Models\HistorialPassword::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    
    // Keep only the latest one as active, verify against current password
    $latest = $histories->first();
    
    // Delete all
    App\Models\HistorialPassword::where('user_id', $user->id)->delete();
    
    // Re-create the correct single entry matching current password
    App\Models\HistorialPassword::create([
        'user_id' => $user->id,
        'password_hash' => $user->password,
        'status' => true,
        'created_at' => $latest ? $latest->created_at : now(),
        'updated_at' => now()
    ]);
    
    echo "Cleaned. Created 1 active entry matching current password.\n";
    
} else {
    echo "User not found\n";
}
