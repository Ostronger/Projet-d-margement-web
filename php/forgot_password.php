<?php
session_start();

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=emargement;charset=utf8mb4", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération de l'email soumis
$email = $_POST['email'] ?? '';

// Vérification de la validité de l'email
if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Vérifier si l'email existe dans la base
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Stocker le token dans la base
        $insertToken = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $insertToken->execute(['user_id' => $user['id'], 'token' => $token, 'expires_at' => $expiration]);

        // Préparer le lien de réinitialisation
        $resetLink = "http://localhost/ton_projet/reset_password.php?token=$token";

        // Préparer le contenu de l'email
        $subject = "Réinitialisation de votre mot de passe";
        $message = "
            Bonjour {$user['username']},\n\n
            Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour le réinitialiser :\n
            $resetLink\n\n
            Ce lien expirera dans une heure.\n
            Si vous n'avez pas demandé cette action, veuillez ignorer cet email.
        ";

        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Reply-To: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Envoyer l'email
        if (mail($email, $subject, $message, $headers)) {
            echo "Un email de réinitialisation a été envoyé. Vérifiez votre boîte de réception.";
        } else {
            echo "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }
} else {
    echo "Veuillez entrer une adresse email valide.";
}
?>
