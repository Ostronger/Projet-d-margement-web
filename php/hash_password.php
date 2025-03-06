<?php
// Vérifier si un mot de passe est envoyé via POST ou GET
if (isset($_POST['password'])) {
    $password = trim($_POST['password']); // Récupération et nettoyage du mot de passe
    
    if (strlen($password) < 8) {
        echo "Le mot de passe doit contenir au moins 8 caractères.";
        exit();
    }

    // Générer le hash du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    echo "✅ Mot de passe haché : <br><strong>$hashedPassword</strong>";
} else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Générer un mot de passe haché</title>
        <link rel="stylesheet" href="style/style.css">
    </head>
    <body>
        <div class="container">
            <h2>Générer un mot de passe haché</h2>
            <form method="POST">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Générer</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
