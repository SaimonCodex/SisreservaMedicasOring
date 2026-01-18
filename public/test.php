<?php
echo "PHP está funcionando correctamente!";
echo "<br>";
echo "Directorio actual: " . __DIR__;
echo "<br>";
echo "Laravel instalado: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'SÍ' : 'NO');
