<?php
try {
    require __DIR__.'/public/index.php';
} catch (\Throwable $e) {
    echo "ERROR CAPTURADO MANUALMENTE:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
