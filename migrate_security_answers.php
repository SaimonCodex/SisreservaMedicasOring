<?php

/**
 * Script de Migración de Respuestas de Seguridad
 * 
 * Este script actualiza las respuestas de seguridad existentes para usar
 * el nuevo formato case-insensitive (minúsculas).
 * 
 * ADVERTENCIA: Este proceso NO puede restaurarse fácilmente.
 * Se recomienda hacer un backup de la tabla respuestas_seguridad antes de ejecutar.
 * 
 * Uso:
 *   php migrate_security_answers.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RespuestaSeguridad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "=== Migración de Respuestas de Seguridad ===" . PHP_EOL . PHP_EOL;
echo "ADVERTENCIA: Este proceso va a actualizar TODAS las respuestas de seguridad." . PHP_EOL;
echo "Asegúrate de haber hecho un backup de la base de datos antes de continuar." . PHP_EOL . PHP_EOL;
echo "¿Deseas continuar? (escribe 'SI' para continuar): ";

$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if ($line !== 'SI') {
    echo "Operación cancelada." . PHP_EOL;
    exit(0);
}

echo PHP_EOL . "Iniciando migración..." . PHP_EOL . PHP_EOL;

try {
    DB::beginTransaction();
    
    $respuestas = RespuestaSeguridad::all();
    $total = $respuestas->count();
    $updated = 0;
    $skipped = 0;
    
    echo "Total de respuestas encontradas: {$total}" . PHP_EOL . PHP_EOL;
    
    foreach ($respuestas as $respuesta) {
        // El problema es que no sabemos la respuesta original en texto plano
        // Solo tenemos el hash. No podemos convertir hashes viejos a lowercase.
        // 
        // SOLUCIÓN: El código ya tiene backward compatibility.
        // Los usuarios con respuestas antiguas seguirán funcionando porque la verificación
        // ahora comprueba 3 formatos:
        // 1. Lowercase + trimmed (nuevo formato)
        // 2. Trimmed solo (formato anterior)
        // 3. Raw (formato muy antiguo)
        
        $skipped++;
    }
    
    DB::commit();
    
    echo PHP_EOL . "=== Resultado ===" . PHP_EOL;
    echo "Total procesadas: {$total}" . PHP_EOL;
    echo "Actualizadas: {$updated}" . PHP_EOL;
    echo "Sin cambios: {$skipped}" . PHP_EOL . PHP_EOL;
    
    echo "NOTA IMPORTANTE:" . PHP_EOL;
    echo "No se actualizaron registros porque no es posible revertir los hashes MD5." . PHP_EOL;
    echo "Sin embargo, el sistema ahora tiene compatibilidad hacia atrás:" . PHP_EOL;
    echo "- Las respuestas antiguas seguirán funcionando (case-sensitive)" . PHP_EOL;
    echo "- Las nuevas respuestas serán case-insensitive (minúsculas)" . PHP_EOL . PHP_EOL;
    echo "Los usuarios con respuestas antiguas deberán ingresar con el CASE correcto," . PHP_EOL;
    echo "o pueden actualizar sus respuestas de seguridad desde su perfil." . PHP_EOL;
    
    Log::info('Security answers migration completed', [
        'total' => $total,
        'updated' => $updated,
        'skipped' => $skipped
    ]);
    
} catch (\Exception $e) {
    DB::rollBack();
    echo PHP_EOL . "ERROR: " . $e->getMessage() . PHP_EOL;
    Log::error('Security answers migration failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    exit(1);
}

echo PHP_EOL . "Migración completada." . PHP_EOL;
