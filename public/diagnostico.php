<?php
/**
 * Diagnóstico del Sistema
 * Acceder vía: http://localhost/SisReservaMedicas/public/diagnostico.php
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; }
        .test { background: #f8f9fa; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
        .success { border-left-color: #28a745; background: #d4edda; }
        .error { border-left-color: #dc3545; background: #f8d7da; }
        .warning { border-left-color: #ffc107; background: #fff3cd; }
        code { background: #e9ecef; padding: 2px 6px; border-radius: 3px; font-size: 0.9em; }
        pre { background: #343a40; color: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Diagnóstico del Sistema Laravel</h1>
    
    <?php
    $basePath = __DIR__ . '/..';
    $errors = [];
    $warnings = [];
    
    // Test 1: Verificar .htaccess
    echo "<h2>1. Configuración del Servidor</h2>";
    $htaccess = __DIR__ . '/.htaccess';
    if (file_exists($htaccess)) {
        echo "<div class='test success'>✓ Archivo .htaccess existe</div>";
        echo "<pre>" . htmlspecialchars(file_get_contents($htaccess)) . "</pre>";
    } else {
        echo "<div class='test error'>✗ Archivo .htaccess NO EXISTE - Este es el problema principal</div>";
        $errors[] = "Falta .htaccess";
    }
    
    // Test 2: Verificar routes/web.php
    echo "<h2>2. Archivo de Rutas</h2>";
    $webRoutes = $basePath . '/routes/web.php';
    if (file_exists($webRoutes)) {
        echo "<div class='test success'>✓ Archivo routes/web.php existe</div>";
        
        // Buscar ruta de login
        $content = file_get_contents($webRoutes);
        if (strpos($content, "Route::get('/login'") !== false) {
            echo "<div class='test success'>✓ Ruta GET /login está definida</div>";
        } else {
            echo "<div class='test error'>✗ Ruta GET /login NO está definida</div>";
            $errors[] = "Ruta /login no encontrada";
        }
    } else {
        echo "<div class='test error'>✗ Archivo routes/web.php NO existe</div>";
        $errors[] = "Falta routes/web.php";
    }
    
    // Test 3: Verificar AuthController
    echo "<h2>3. Controlador de Autenticación</h2>";
    $authController = $basePath . '/app/Http/Controllers/AuthController.php';
    if (file_exists($authController)) {
        echo "<div class='test success'>✓ AuthController existe</div>";
        
        $content = file_get_contents($authController);
        if (strpos($content, 'function showLogin') !== false) {
            echo "<div class='test success'>✓ Método showLogin() existe</div>";
        } else {
            echo "<div class='test error'>✗ Método showLogin() NO existe</div>";
            $errors[] = "Método showLogin() faltante";
        }
    } else {
        echo "<div class='test error'>✗ AuthController NO existe</div>";
        $errors[] = "Falta AuthController";
    }
    
    // Test 4: Verificar vista de login
    echo "<h2>4. Vista de Login</h2>";
    $loginView = $basePath . '/resources/views/auth/login.blade.php';
    if (file_exists($loginView)) {
        echo "<div class='test success'>✓ Vista auth/login.blade.php existe</div>";
    } else {
        echo "<div class='test error'>✗ Vista auth/login.blade.php NO existe</div>";
        $errors[] = "Vista login.blade.php faltante";
    }
    
    // Test 5: Verificar layout auth
    echo "<h2>5. Layout de Autenticación</h2>";
    $authLayout = $basePath . '/resources/views/layouts/auth.blade.php';
    if (file_exists($authLayout)) {
        echo "<div class='test success'>✓ Layout layouts/auth.blade.php existe</div>";
    } else {
        echo "<div class='test error'>✗ Layout layouts/auth.blade.php NO existe</div>";
        $errors[] = "Layout auth.blade.php faltante";
    }
    
    // Test 6: Verificar layout app
    echo "<h2>6. Layout Principal</h2>";
    $appLayout = $basePath . '/resources/views/layouts/app.blade.php';
    if (file_exists($appLayout)) {
        echo "<div class='test success'>✓ Layout layouts/app.blade.php existe</div>";
    } else {
        echo "<div class='test error'>✗ Layout layouts/app.blade.php NO existe</div>";
        $errors[] = "Layout app.blade.php faltante";
    }
    
    // Test 7: Verificar permisos de storage
    echo "<h2>7. Permisos de Directorios</h2>";
    $storageWritable = is_writable($basePath . '/storage');
    if ($storageWritable) {
        echo "<div class='test success'>✓ Directorio storage/ es escribible</div>";
    } else {
        echo "<div class='test error'>✗ Directorio storage/ NO es escribible</div>";
        $errors[] = "storage/ sin permisos de escritura";
    }
    
    // Test 8: Verificar caché
    echo "<h2>8. Estado de Caché</h2>";
    $configCache = $basePath . '/bootstrap/cache/config.php';
    if (file_exists($configCache)) {
        echo "<div class='test warning'>⚠ Caché de configuración existe (puede causar problemas)</div>";
        $warnings[] = "Existe caché de configuración";
    } else {
        echo "<div class='test success'>✓ No hay caché de configuración</div>";
    }
    
    $routesCache = $basePath . '/bootstrap/cache/routes-v7.php';
    if (file_exists($routesCache)) {
        echo "<div class='test warning'>⚠ Caché de rutas existe (puede causar problemas)</div>";
        $warnings[] = "Existe caché de rutas";
    } else {
        echo "<div class='test success'>✓ No hay caché de rutas</div>";
    }
    
    // Test 9: Variables de entorno
    echo "<h2>9. Variables de Entorno</h2>";
    echo "<div class='test'>";
    echo "URL Base: <code>" . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'N/A') . "</code><br>";
    echo "Request URI: <code>" . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A') . "</code><br>";
    echo "Document Root: <code>" . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'N/A') . "</code><br>";
    echo "</div>";
    
    // Resumen
    echo "<h2>📊 Resumen</h2>";
    if (count($errors) == 0) {
        echo "<div class='test success'><strong>✓ No se encontraron errores críticos</strong></div>";
    } else {
        echo "<div class='test error'><strong>✗ Se encontraron " . count($errors) . " error(es):</strong><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul></div>";
    }
    
    if (count($warnings) > 0) {
        echo "<div class='test warning'><strong>⚠ Se encontraron " . count($warnings) . " advertencia(s):</strong><ul>";
        foreach ($warnings as $warning) {
            echo "<li>$warning</li>";
        }
        echo "</ul></div>";
    }
    
    // Acciones
    echo "<h2>🔧 Acciones Recomendadas</h2>";
    echo "<a href='clear-cache.php' class='btn'>Limpiar Caché</a>";
    echo "<a href='/' class='btn'>Ir al Inicio</a>";
    echo "<a href='/login' class='btn'>Probar Login</a>";
    ?>
    
</div>
</body>
</html>
