<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=SisReservaMedicas', 'root', '');
    
    $tables = ['estados', 'ciudades', 'municipios', 'parroquias'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        echo "$table: " . $stmt->fetchColumn() . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
