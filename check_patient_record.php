<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=SisReservaMedicas', 'root', '');
    
    $userId = 9; // Based on the error screenshot in Step 262
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pacientes WHERE user_id = ?");
    $stmt->execute([$userId]);
    $count = $stmt->fetchColumn();
    
    echo "Paciente count for user_id $userId: $count\n";
    
    if ($count == 0) {
        $stmt = $pdo->query("SELECT id, correo, rol_id FROM usuarios WHERE id = $userId");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "User Info: " . json_encode($user) . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
