<?php
require_once "php/session.php"; // Gestion des sessions et authentification
requireAuth('admin'); // Vérifie que l'utilisateur est bien un administrateur
loadUserData(); // Charge les informations de l'utilisateur connecté

// Connexion à la base pour récupérer des statistiques
$pdo = connection_bdd();

// Récupérer le nombre total d'apprenants
$stmt = $pdo->query("SELECT COUNT(*) AS total_apprenants FROM users WHERE role = 'apprenant'");
$apprenants = $stmt->fetch(PDO::FETCH_ASSOC)['total_apprenants'];

// Récupérer le nombre total de formateurs
$stmt = $pdo->query("SELECT COUNT(*) AS total_formateurs FROM users WHERE role = 'formateur'");
$formateurs = $stmt->fetch(PDO::FETCH_ASSOC)['total_formateurs'];

// Récupérer le nombre total d'émargements 
// Vérifier si la table existe avant d'exécuter la requête
$tableExists = $pdo->query("SHOW TABLES LIKE 'emargements'")->rowCount();

if ($tableExists) {
$stmt = $pdo->query("SELECT COUNT(*) AS total_emargements FROM emargements");
$emargements = $stmt->fetch(PDO::FETCH_ASSOC)['total_emargements'];
} else {
    $emargements = 0; // Valeur par défaut si la table n'existe pas encore
}
// Générer la date actuelle en PHP
$current_date = date('d/m/Y');

$firstname = htmlspecialchars($_SESSION['firstname'] ?? 'Admin');
$user_photo = $_SESSION['user_photo'] ?? '../image/default-avatar.png';
$user_initials = $_SESSION['user_initials'] ?? 'A';



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gefor - Tableau de Bord</title>
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/global.css">
</head>
<body>
    <!-- Menu latéral -->
    <nav class="sidebar">
        <div class="user-profile">
            <img src="<?php echo $user_photo; ?>" alt="Photo de l'utilisateur" class="user-photo">
            <h3 class="user-initials"><?php echo $user_initials; ?></h3>
        </div>
        <ul>
            <li><a href="#">Tableau de bord</a></li>
            <li><a href="php/formation.php">Formations</a></li>
            <li><a href="#">Contacts</a></li>
            <li><a href="../php/parametres.php">Paramètres</a></li>
        </ul>
        <a class="logout-link" href="../php/logout.php">Déconnexion</a>
        <div class="image">
            <img id="gefor" src="../image/gefor.jpg" alt="Logo Gefor">
        </div>
    </nav>

    <!-- Contenu principal -->
    <section class="main-content">
        <section class="dashboard-card">
            <div class="dashboard-info">
                <h2>Bienvenue <span class="username"><?php echo $firstname; ?></span></h2>
                <p>Gérez les utilisateurs, les émargements et les paramètres du système depuis cet espace.</p>
                <div class="date-and-stats">
                    <div class="date">Aujourd'hui<br><span><?php echo $current_date; ?></span></div>
                    <div class="stats-summary">
                        <p><?php echo $emargements; ?> feuilles démarrées</p>
                        <button>Créer une feuille</button>
                    </div>
                </div>
            </div>
            <img src="../image/gefor.jpg" alt="Dashboard Illustration">
        </section>

        <!-- Section Statistiques -->
        <section class="stats">
            <div class="stat-item">
                <h2><?php echo $apprenants; ?></h2>
                <p>Apprenants</p>
            </div>
            <div class="stat-item">
                <h2><?php echo $formateurs; ?></h2>
                <p>Formateurs</p>
            </div>
            <div class="stat-item">
                <h2><?php echo $emargements; ?></h2>
                <p>Émargements</p>
            </div>
        </section>

        <!-- Section Options -->
        <section class="options">
            <div class="option">
                <button class="option-icon">+</button>
                <p>Ajouter un nouvel utilisateur</p>
            </div>

            <div class="option">
                <button class="option-icon">+</button>
                <p>Paramètres de l'organisme</p>
            </div>
        </section>
    </section>
</body>
</html>