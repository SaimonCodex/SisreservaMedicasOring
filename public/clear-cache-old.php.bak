<?php
/**
 * Script para limpiar la caché de Laravel sin usar artisan
 * Acceder vía: http://localhost/SisReservaMedicas/public/clear-cache.php
 */

echo "<h2>Limpiando caché de Laravel...</h2>";
echo "<hr>";

$basePath = __DIR__ . '/..';
$cleared = 0;

// 1. Limpiar caché de configuración
echo "<h3>1. Caché de Configuración</h3>";
$configCache = $basePath . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    unlink($configCache);
    echo "✓ <span style='color: green;'>Config cache eliminada</span><br>";
    $cleared++;
} else {
    echo "○ No hay caché de configuración<br>";
}

// 2. Limpiar caché de rutas
echo "<h3>2. Caché de Rutas</h3>";
$routesCache = $basePath . '/bootstrap/cache/routes-v7.php';
if (file_exists($routesCache)) {
    unlink($routesCache);
    echo "✓ <span style='color: green;'>Routes cache eliminada</span><br>";
    $cleared++;
} else {
    echo "○ No hay caché de rutas<br>";
}

// 3. Limpiar vistas compiladas
echo "<h3>3. Vistas Compiladas</h3>";
$viewsPath = $basePath . '/storage/framework/views';
if (is_dir($viewsPath)) {
    $files = glob($viewsPath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            unlink($file);
            $count++;
        }
    }
    if ($count > 0) {
        echo "✓ <span style='color: green;'>{$count} vistas compiladas eliminadas</span><br>";
        $cleared++;
    } else {
        echo "○ No hay vistas compiladas<br>";
    }
} else {
    echo "✗ <span style='color: red;'>Directorio de vistas no encontrado</span><br>";
}

// 4. Limpiar caché de aplicación
echo "<h3>4. Caché de Aplicación</h3>";
$cachePath = $basePath . '/storage/framework/cache/data';
if (is_dir($cachePath)) {
    $files = glob($cachePath . '/*/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    if ($count > 0) {
        echo "✓ <span style='color: green;'>{$count} archivos de caché eliminados</span><br>";
        $cleared++;
    } else {
        echo "○ No hay caché de aplicación<br>";
    }
} else {
    echo "○ Directorio de caché no encontrado<br>";
}

// Resumen
echo "<hr>";
if ($cleared > 0) {
    echo "<h2 style='color: green;'>✓ Caché limpiada exitosamente!</h2>";
    echo "<p>{$cleared} tipo(s) de caché fueron eliminados.</p>";
} else {
    echo "<h2 style='color: orange;'>○ No había caché para limpiar</h2>";
}

echo "<hr>";
echo "<a href='/SisReservaMedicas/public' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>← Volver al Inicio</a>";
echo "<br><br>";
echo "<small>Puedes eliminar este archivo (clear-cache.php) cuando ya no lo necesites.</small>";
