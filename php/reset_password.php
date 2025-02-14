<?php
session_start();

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=emargement_db;charset=utf8mb4", 'root', '');

// Récupération du token dans l'URL
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $newPassword = $_POST['password'] ?? '';
    $token = $_POST['token'] ?? '';

    if (!empty($newPassword) && !empty($token)) {
        // Vérifier le token dans la base de données
        $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = :token AND expires_at > NOW()");
        $stmt->execute(['token' => $token]);

        $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resetData) {
            // Mettre à jour le mot de passe de l'utilisateur
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updatePassword = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
            $updatePassword->execute(['password' => $hashedPassword, 'user_id' => $resetData['user_id']]);

            // Supprimer le token après utilisation
            $deleteToken = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
            $deleteToken->execute(['token' => $token]);

            echo "Votre mot de passe a été réinitialisé avec succès.";
        } else {
            echo "Lien de réinitialisation invalide ou expiré.";
        }
    } else {
        echo "Veuillez entrer un nouveau mot de passe.";
    }
}
?>

<!-- Formulaire HTML pour la réinitialisation -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link rel="stylesheet" href="style/style_index.css">
</head>
<body>
    <div class="background-image">
        <div class="login-card">
            <h2 class="login-title">RÉINITIALISER LE MOT DE PASSE</h2>
            <form action="reset_password.php" method="POST" class="login-form">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label for="password" class="login-label">NOUVEAU MOT DE PASSE</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Entrer un nouveau mot de passe" required class="login-input">
                    <div class="input-underline"></div>
                </div>
                <button type="submit" class="login-button">Réinitialiser le mot de passe</button>
            </form>
        </div>
    </div>
</body>
</html>
