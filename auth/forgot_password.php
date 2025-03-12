
<?php
require_once "../php/session.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/forms.css">
</head>
<body>
    <div class="background-image">
        <div class="login-card">
            <h2 class="login-title">MOT DE PASSE OUBLIÉ ?</h2>

            <!-- Affichage des messages d'erreur/succès -->
            <?php
            if ($error = getSessionMessage('error')) {
                echo "<p class='error-message'>$error</p>";
            }
            if ($success = getSessionMessage('success')) {
                echo "<p class='success-message'>$success</p>";
            }
            ?>

            <form action="../php/forgot_password_process.php" method="POST" class="login-form">
                <label for="email" class="login-label">EMAIL</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" placeholder="Entrer votre email" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                <button type="submit" class="button button--primary">Envoyez un email de réinitialisation</button>
            </form>
            <a href="../index.php">Se connecter</a>
            <img src="../assets/images/gefor.jpg" alt="Groupe Gefor" class="login-logo">
        </div>
    </div>
</body>
</html>
