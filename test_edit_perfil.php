<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Paciente;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Find a patient user
$user = Usuario::where('rol_id', 3)->first();

if (!$user) {
    die("No se encontró ningún usuario con rol de paciente.");
}

// Log in the user
Auth::login($user);

// Create a request to the route
$request = Illuminate\Http\Request::create('/paciente/perfil/editar', 'GET');

try {
    $response = $kernel->handle($request);
    echo "Status Code: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() == 302) {
        echo "Redirecting to: " . $response->headers->get('Location') . "\n";
    }
    // Echo the first 200 chars if 200
    if ($response->getStatusCode() == 200) {
        echo "Content preview: " . substr($response->getContent(), 0, 200) . "...\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
