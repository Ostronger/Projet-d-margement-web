<?php


// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "db.php"; // Connexion à la base de données

/**
 * Vérifie si un utilisateur est connecté
 * @return bool true si connecté, false sinon
 */
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Récupère le rôle de l'utilisateur
 * @return string|null Le rôle de l'utilisateur ou null s'il n'est pas connecté
 */
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Vérifie si l'utilisateur est connecté et possède un rôle spécifique
 * Redirige vers la page de connexion ou une page non autorisée si besoin
 * @param string|null $role Rôle requis pour accéder à la page (optionnel)
 */
function requireAuth($role = null) {
    if (!isUserLoggedIn()) {
        header("Location: login_process.php");
        exit();
    }

    if ($role && getUserRole() !== $role) {
        header("Location: unauthorized.php");
        exit();
    }
}

/**
 * Récupère les informations de l'utilisateur connecté et les stocke en session
 * Évite les requêtes SQL inutiles si les données sont déjà stockées en session
 */
function loadUserData() {
    if (!isUserLoggedIn()) {
        return;
    }

 // Vérifier si les données ne sont pas déjà en session
 if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname']) || !isset($_SESSION['user_photo'])) {
    $pdo = connection_bdd();
    $stmt = $pdo->prepare("SELECT firstname, lastname, profile_picture FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];

        // Correction ici : Vérification correcte de `profile_picture`
        $_SESSION['user_photo'] = (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) 
            ? $user['profile_picture'] 
            : null;

        $_SESSION['user_initials'] = strtoupper(substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1));
    }
}
}

/**
 * Ajoute un message de session (type: success, error)
 * @param string $type Type de message (ex: "success", "error")
 * @param string $message Contenu du message
 */
function setSessionMessage($type, $message) {
    $_SESSION[$type] = $message;
}

/**
 * Récupère et supprime un message de session après l'affichage
 * @param string $type Type de message ("success", "error")
 * @return string|null Le message ou null s'il n'y a rien
 */
function getSessionMessage($type) {
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]); // Supprime après récupération
        return $message;
    }
    return null;
}
?>