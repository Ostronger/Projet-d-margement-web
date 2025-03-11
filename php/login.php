<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Connexion à la base de données
    require_once "fonction.php";
    $pdo= connection_bdd();

    // Requête pour récupérer l'utilisateur et vérifier le mot de passe
    $stmt = $pdo->prepare("SELECT id, role, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Rediriger selon le rôle
            switch ($user['role']) {
                case 'apprenant':
                    header('Location: php/dashboard_apprenant.php');
                    break;
                case 'admin':
                    header('Location: php/dashboard_admin.php');
                    break;
                case 'formateur':
                    header('Location: php/dashboard_formateur.php');
                    break;
                default:
                    echo "Rôle non reconnu.";
                    exit();
            }
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>
    <main>
        <form method="POST" action="">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Se connecter</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Plateforme d'Émargement</p>
    </footer>
</body>
</html>
