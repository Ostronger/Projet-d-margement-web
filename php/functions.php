<?php

/**
 * Récupérer le nombre total d'utilisateurs par rôle
 */
function getTotalUsersByRole($pdo, $role) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM users WHERE role = :role");
    $stmt->execute(['role' => $role]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

/**
 * Récupérer le nombre total d'émargements (vérifie si la table existe avant)
 */
function getTotalEmargements($pdo) {
    $tableExists = $pdo->query("SHOW TABLES LIKE 'feuilles_emargements'")->rowCount();
    if ($tableExists) {
        $stmt = $pdo->query("SELECT COUNT(*) AS total FROM feuilles_emargements");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }
    return 0;
}

function getFeuillesEmargementsToday($pdo) {
    $tableExists = $pdo->query("SHOW TABLES LIKE 'feuilles_emargements'")->rowCount();
    
    if ($tableExists) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS total 
            FROM feuilles_emargements 
            WHERE DATE(created_at) = CURDATE()
        ");
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    return 0;
}
?>