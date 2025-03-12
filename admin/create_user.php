<?php
require_once "../php/session.php";
requireAuth('admin'); // Vérifie que l'utilisateur est un administrateur
require_once "../php/db.php";

// Connexion à la base de données
$pdo = connection_bdd();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un nouvel utilisateur</title>
    <link rel="stylesheet" href="../style/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/forms.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/forms_admin.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <?php require_once "sidebar_admin.php";?><!-- Sidebar incluse -->
        <main class="main-content">
        <?php
if ($error = getSessionMessage('error')) {
    echo "<div class='error-message'>$error</div>";
}

if ($success = getSessionMessage('success')) {
    echo "<div class='success-message'>$success</div>";
}
?>
            <h1>Ajouter un nouvel utilisateur</h1>
    
            <form action="../php/create_user_process.php" method="POST" class="form-admin card">

    <label for="firstname">Prénom :</label>
    <input type="text" name="firstname" id="firstname" required>

    <label for="lastname">Nom :</label>
    <input type="text" name="lastname" id="lastname" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>

    <label for="role">Rôle :</label>
    <select name="role" id="role" required>
        <option value="">-- Sélectionner un rôle --</option>
        <option value="apprenant">Apprenant</option>
        <option value="formateur">Formateur</option>
    </select>

    <button type="submit" class="button button--primary">Créer l'utilisateur</button>
</form>
</main>
    </div>
</body>
</html>