<?php
require_once "../php/session.php"; // Gestion des sessions


// Vérifier si un token est présent dans l'URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    setSessionMessage('error', "Accès non autorisé.");
    header("Location: forgot_password.php");
    exit();
}

// Récupération du token
$token = htmlspecialchars($_GET['token']);
?>

<!-- Formulaire HTML pour la réinitialisation -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/forms.css">
</head>
<body>
    <div class="background-image">
        <div class="login-card">
            <h2 class="login-title">RÉINITIALISER LE MOT DE PASSE</h2>

             <!-- Affichage des messages d'erreur/succès -->
             <?php
            if ($error = getSessionMessage('error')) {
                echo "<p class='error-message'>$error</p>";
            }
            if ($success = getSessionMessage('success')) {
                echo "<p class='success-message'>$success</p>";
            }
            ?>


            <form action="../php/reset_password_process.php" method="POST" class="login-form">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                
                <label for="password" class="login-label">NOUVEAU MOT DE PASSE</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Entrer un nouveau mot de passe" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                <label for="confirm_password" class="login-label">CONFIRMER LE MOT DE PASSE</label>
                <div class="input-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                
                <button type="submit" class="button button--primary">Réinitialiser le mot de passe</button>
            </form>
        </div>
    </div>
</body>
</html>