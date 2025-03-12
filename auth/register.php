<?php
require_once "php/session.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/forms.css">
</head>
<body>
    <div class="background-image">
        <div class="login-card">
            <h2 class="login-title">Inscription</h2>

            <!-- Affichage des messages d'erreur/succès -->
            <?php if ($error = getSessionMessage('error')) : ?>
                <p class="error-message"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if ($success = getSessionMessage('success')) : ?>
                <p class="success-message"><?= htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <form action="php/register_process.php" method="POST" class="login-form">

                <!-- Nom d'utilisateur -->
                <label for="username" class="login-label">Nom d'utilisateur</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                <!-- Email -->
                <label for="email" class="login-label">Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" placeholder="Votre email" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                <!-- Mot de passe -->
                <label for="password" class="login-label">Mot de passe</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required class="login-input">
                    <div class="input-underline"></div>
                </div>

                <!-- Sélection du rôle -->
                <label for="role" class="login-label">Rôle</label>
                <div class="input-wrapper">
                    <select id="role" name="role" class="login-input" required>
                        <option value="admin">Administrateur</option>
                        <option value="formateur">Formateur</option>
                        <option value="apprenant">Apprenant</option>
                    </select>
                    <div class="input-underline"></div>
                </div>

                <button type="submit" class="button button--primary">S'inscrire</button>
            </form>
            <p class="login-label">Déjà un compte ? <a href="index.php">Se connecter</a></p>
        </div>
    </div>
</body>
</html>