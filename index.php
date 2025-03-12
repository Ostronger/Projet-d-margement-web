<?php
require_once "php/session.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion émargement</title>
    <!-- Chemin relatif vers le fichier CSS -->
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/forms.css">

</head>

<body>
    <div class="background-image">
        <div class="login-card">
            <h2 class="login-title">CONNEXION</h2>

            <!-- Affichage des messages d'erreur/succès -->
            <?php
                if ($error = getSessionMessage('error')) {
                    echo "<p class='error-message'>$error</p>";
                }
                if ($success = getSessionMessage('success')) {
                    echo "<p class='success-message'>$success</p>";
                }
            ?>

            <form action="php/login_process.php" method="POST" class="login-form">

                <!-- IDENTIFIANT-->
                <label for="username" class="login-label">IDENTIFIANT</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                <!-- MOT DE PASSE-->
                <label for="password" class="login-label">MOT DE PASSE</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Mot de passe" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                <!-- REMEMBER ME -->
                <div class="login-options">
                    <input type="checkbox" id="remember" name="remember" class="checkbox">
                    <label for="remember" class="login-checkbox-label">Se souvenir de moi</label>
                </div>

                <button type="submit" class="button button--primary">Connexion</button>
            </form>
               <a href="auth/forgot_password.php">Mot de passe oublié?</a>

            <img src="assets/images/gefor.jpg" alt="Groupe Gefor" class="login-logo">
        </div>
    </div>

</body>
</html>
