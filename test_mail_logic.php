<?php
$ch = curl_init();

$token = 'dummy_csrf_token_simulation_wont_work_if_middleware_active';
// To bypass CSRF middleware, we might need to modify VerifyCsrfToken.php temporarily or use a test route.
// Or we can rely on catching the exception which happens inside the controller logic, but if middleware blocks it first (419), we won't see the controller error.
// The route /recovery/send-email IS protected by web middleware.

// Better approach: Use a PHP script that bootstraps Laravel and calls the method or Mail directly.
// This avoids middleware issues.

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$user = App\Models\Usuario::where('correo', 'admin@clinica.com')->first();
$token = Illuminate\Support\Str::random(60);

try {
    $resetUrl = route('password.reset', $token);
    
    // Simulate what sendingEmailRecuperacion does
    echo "Attempting to send email to: " . $user->correo . "\n";
    echo "Reset URL: " . $resetUrl . "\n";
    
    Illuminate\Support\Facades\Mail::send('emails.recuperar-password', [
        'usuario' => $user,
        'resetUrl' => $resetUrl
    ], function($message) use ($user) {
        $message->to($user->correo)
                ->subject('Recuperación de Contraseña - Sistema Médico');
    });
    
    echo "Email sent successfully via Mail::send!\n";
} catch (\Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
