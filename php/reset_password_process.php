<?php
require_once "db.php";
require_once "session.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = connection_bdd();
    
        // Récupérer les données du formulaire
        $newPassword = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        $token = trim($_POST['token'] ?? '');

        // Vérification du mot de passe (longueur minimale 8 caractères)
        if (empty($newPassword) || strlen($newPassword) < 8) {
            setSessionMessage('error', "Le mot de passe doit contenir au moins 8 caractères.");
            header("Location: ../auth/reset_password.php?token=$token");
            exit();
        }

        // Vérifier si les deux mots de passe sont identiques
        if ($newPassword !== $confirmPassword) {
            setSessionMessage('error', "Les mots de passe ne correspondent pas.");
            header("Location: ../auth/reset_password.php?token=$token");
            exit();
        }
        
        // Vérifier si le token est valide et n'a pas expiré
        $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = :token AND expires_at > NOW()");
        $stmt->execute(['token' => $token]);
        $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resetData) {
            
            setSessionMessage('error', "Lien de réinitialisation invalide ou expiré.");
            header("Location: ../auth/forgot_password.php");
            exit();
        }

        // Hacher le mot de passe avant de l'enregistrer
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Mettre à jour le mot de passe de l'utilisateur
        $updatePassword = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
        $success = $updatePassword->execute([
            'password' => $hashedPassword, 
            'user_id' => $resetData['user_id']
        ]);

        if (!$success) {
            setSessionMessage('error', "Une erreur est survenue lors de la mise à jour du mot de passe.");
            header("Location: ../auth/reset_password.php?token=$token");
            exit();
        }

        // Supprimer le token après utilisation
        $deleteToken = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
        $deleteToken->execute(['token' => $token]);

        setSessionMessage('success', "Votre mot de passe a été réinitialisé avec succès. Connectez-vous avec votre nouveau mot de passe.");
        header("Location: ../index.php");
        exit();

    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage(), 3, "../logs/sql_errors.log");
        setSessionMessage('error', " Une erreur est survenue, veuillez réessayer plus tard.");
        header("Location: ../auth/reset_password.php?token=$token");
        exit();
    } catch (Exception $e) {
        error_log("Erreur générale : " . $e->getMessage(), 3, "../logs/general_errors.log");
        setSessionMessage('error', "Une erreur est survenue, veuillez réessayer.");
        header("Location: ../auth/reset_password.php?token=$token");
        exit();
    }
}
?>


