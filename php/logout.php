<?php
require_once "session.php"; // Assure que la session est bien gérée

// Vérifier si une session est active
if (session_status() === PHP_SESSION_ACTIVE) {
    // Supprime toutes les variables de session
    $_SESSION = [];

    // Supprime le cookie de session s'il existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, 
            $params["path"], $params["domain"], 
            $params["secure"], $params["httponly"]
        );
    }

    // Détruit la session
    session_destroy();
}

// Redirige vers index.php après la déconnexion
header("Location: ../index.php");
exit();
