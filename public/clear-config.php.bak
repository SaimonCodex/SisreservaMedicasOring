<?php
// Limpiar caché de configuración específicamente
$basePath = __DIR__ . '/..';

echo "<h2>Limpiando Caché de Configuración</h2>";

$configCache = $basePath . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    unlink($configCache);
    echo "<p style='color: green;'>✓ Caché de configuración eliminada</p>";
} else {
    echo "<p>○ No había caché de configuración</p>";
}

echo "<h3>¡Listo!</h3>";
echo "<p>Ahora intenta hacer login nuevamente.</p>";
echo "<a href='/SisReservaMedicas/public' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>← Volver al Inicio</a>";
