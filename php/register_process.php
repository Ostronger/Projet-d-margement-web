<?php
require_once "session.php"; // Gestion des sessions et connexion à la BDD
require_once "db.php"; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connection_bdd();

    // Récupération et nettoyage des données
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Vérification des champs obligatoires
    if (empty($username) || empty($password) || empty($email) || empty($role)) {
        setSessionMessage('error', "Tous les champs sont requis.");
        header("Location: ../register.php");
        exit();
    }

    // Vérification de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setSessionMessage('error', "Adresse email invalide.");
        header("Location: ../register.php");
        exit();
    }

    // Vérification de la longueur du mot de passe (au moins 8 caractères)
    if (strlen($password) < 8) {
        setSessionMessage('error', "Le mot de passe doit contenir au moins 8 caractères.");
        header("Location: ../register.php");
        exit();
    }

    // Vérification du rôle valide
    $roles_valides = ['admin', 'formateur', 'apprenant'];
    if (!in_array($role, $roles_valides)) {
        setSessionMessage('error', "Rôle invalide.");
        header("Location: ../register.php");
        exit();
    }

    try {
        // Vérifier si l'utilisateur ou l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);

        if ($stmt->fetch()) {
            setSessionMessage('error', "Un utilisateur avec ce nom ou cet email existe déjà.");
            header("Location: ../register.php");
            exit();
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insérer un nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'email' => $email,
            'role' => $role
        ]);

        setSessionMessage('success', "Utilisateur ajouté avec succès.");
        header("Location: ../login.php");
        exit();
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage(), 3, "../logs/sql_errors.log");
        setSessionMessage('error', "Une erreur est survenue. Veuillez réessayer.");
        header("Location: ../register.php");
        exit();
    }
}
?>