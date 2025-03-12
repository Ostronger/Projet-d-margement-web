<?php
require_once "session.php"; // Gestion des sessions
require_once "db.php"; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        setSessionMessage('error', "Veuillez remplir tous les champs.");
        header("Location: ../index.php");
        exit();
    }

    try {
        $conn = connection_bdd(); 

        // Requête pour récupérer l'utilisateur
        $stmt = $conn->prepare("SELECT id, role, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $role = $user['role'];
            $hashedPassword = $user['password'];

            // Vérifier le mot de passe
            if (password_verify($password, $hashedPassword)) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $role;

                // Charger les données utilisateur
                loadUserData();

                // Redirection selon le rôle
                switch ($role) {
                    case 'apprenant':
                        header('Location: dashboard_apprenant.php');
                        break;
                    case 'admin':
                        header('Location: dashboard_admin_process.php');
                        break;
                    case 'formateur':
                        header('Location: dashboard_formateur.php');
                        break;
                    default:
                        setSessionMessage('error', "Rôle non reconnu.");
                        header("Location: ../index.php");
                        exit();
                }
                exit();
            } else {
                setSessionMessage('error', "Mot de passe incorrect.");
                header("Location: ../index.php");
                exit();
            }
        } else {
            setSessionMessage('error', "Utilisateur non trouvé.");
            header("Location: ../index.php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage(), 3, "../logs/sql_errors.log");
        setSessionMessage('error', "Une erreur est survenue.");
        header("Location: ../index.php");
        exit();
    }
}
?>