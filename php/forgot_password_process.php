<?php
require_once "session.php";
require_once "db.php"; 
require_once "send_email.php"; // Inclut la fonction sendEmail()

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    

    // Vérification de la validité de l'email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setSessionMessage('error', "Veuillez entrer une adresse email valide.");
        header("Location: ../auth/forgot_password.php");
        exit();
    }

    try {
        $pdo = connection_bdd();

        // Vérifier si l'email existe dans la base
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

 

        if ($user) {
            // Génération d'un token unique et expiration dans 1 heure
            $token = bin2hex(random_bytes(32));
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Vérifier si un token existe déjà
            $checkToken = $pdo->prepare("SELECT id FROM password_resets WHERE user_id = :user_id");
            $checkToken->execute(['user_id' => $user['id']]);
            $existingToken = $checkToken->fetch(PDO::FETCH_ASSOC);

            if ($existingToken) {
                // Mise à jour du token existant
                $updateToken = $pdo->prepare("UPDATE password_resets SET token = :token, expires_at = :expires_at WHERE user_id = :user_id");
                $updateToken->execute([
                    'token' => $token,
                    'expires_at' => $expiration,
                    'user_id' => $user['id']
                ]);
            } else {
                // Insertion d'un nouveau token
                $insertToken = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
                $insertToken->execute([
                    'user_id' => $user['id'],
                    'token' => $token,
                    'expires_at' => $expiration
                ]);
                
exit();
            }

            // Génération du lien de réinitialisation
            $resetLink = "http://localhost/Projet-d-margement-web/auth/reset_password.php?token=$token";

            // Contenu de l'email
            $subject = "Réinitialisation de votre mot de passe";
            $message = "
                Bonjour {$user['username']},\n\n
                Vous avez demandé la réinitialisation de votre mot de passe.\n
                Cliquez sur le lien ci-dessous pour le réinitialiser :\n
                $resetLink\n\n
                Ce lien expirera dans une heure.\n
                Si vous n'avez pas demandé cette action, veuillez ignorer cet email.
            ";

            $headers = "From: no-reply@example.com\r\n";
            $headers .= "Reply-To: no-reply@example.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            // Envoyer l'email
            $emailSent = sendEmail($email, "Réinitialisation de votre mot de passe", $message);

            if ($emailSent) {
                setSessionMessage('success', "Un email de réinitialisation a été envoyé.");
            } else {
                setSessionMessage('error', "Échec de l'envoi de l'email.");
            }

        } else {
            setSessionMessage('error', "Aucun utilisateur trouvé avec cet email.");
        }
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage(), 3, "../logs/sql_errors.log");
        setSessionMessage('error', "Une erreur est survenue, veuillez réessayer plus tard.");
    } catch (Exception $e) {
        error_log("Erreur générale : " . $e->getMessage(), 3, "../logs/general_errors.log");
        setSessionMessage('error', "Une erreur est survenue.");
    }


    // Redirection vers la page de récupération avec un message
    header("Location: ../auth/forgot_password.php");
    exit();
}
?>
