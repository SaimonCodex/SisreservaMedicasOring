<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=mysql', 'root', '');
    
    // Explicitly set the auth plugin to caching_sha2_password for root
    // We do this for localhost.
    $pdo->exec("ALTER USER 'root'@'localhost' IDENTIFIED WITH caching_sha2_password BY ''");
    echo "Updated root@localhost to caching_sha2_password.\n";

    // Flush privileges
    $pdo->exec("FLUSH PRIVILEGES");
    echo "Flushed privileges.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
