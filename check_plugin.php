<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=mysql', 'root', '');
    $stmt = $pdo->query("SELECT user, host, plugin FROM user WHERE user='root'");
    foreach ($stmt as $row) {
        echo "User: {$row['user']}@{$row['host']} - Plugin: {$row['plugin']}\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
