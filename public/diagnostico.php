<?php
// Diagnóstico completo de Laravel
echo "<h2>Diagnóstico del Sistema</h2>";
echo "<hr>";

// 1. Información del servidor
echo "<h3>1. Información del Servidor</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "<hr>";

// 2. Verificar archivos de Laravel
echo "<h3>2. Archivos de Laravel</h3>";
$files = [
    'index.php' => __DIR__ . '/index.php',
    '.htaccess' => __DIR__ . '/.htaccess',
    '../vendor/autoload.php' => __DIR__ . '/../vendor/autoload.php',
    '../bootstrap/app.php' => __DIR__ . '/../bootstrap/app.php',
    '../routes/web.php' => __DIR__ . '/../routes/web.php',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo "$name: " . ($exists ? '✓ Existe' : '✗ No existe') . "<br>";
}
echo "<hr>";

// 3. Verificar permisos
echo "<h3>3. Permisos de Directorios</h3>";
$dirs = [
    'storage' => __DIR__ . '/../storage',
    'bootstrap/cache' => __DIR__ . '/../bootstrap/cache',
];

foreach ($dirs as $name => $path) {
    $writable = is_writable($path);
    echo "$name: " . ($writable ? '✓ Escribible' : '✗ No escribible') . "<br>";
}
echo "<hr>";

// 4. Intentar cargar Laravel
echo "<h3>4. Cargar Laravel</h3>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "✓ Autoload cargado correctamente<br>";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✓ Bootstrap cargado correctamente<br>";
    
    echo "✓ Laravel está funcionando!<br>";
    echo "<a href='login'>Ir al login</a>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
