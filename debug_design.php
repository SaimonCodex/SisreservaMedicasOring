<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\Usuario::where('correo', 'admin@clinica.com')->first();
if ($user) {
    echo "Sending design test to: " . $user->correo . "\n";
    $user->notify(new App\Notifications\AlertaInicioSesion('127.0.0.1', 'PRUEBA DISEÑO NUEVO', now()));
    echo "SENT\n";
} else {
    echo "User not found\n";
}
