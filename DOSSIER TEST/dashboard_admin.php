<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location:' . url("php/login.php"));
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=emargement;charset=utf8mb4", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les informations de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT firstname, lastname, profile_picture FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur existe, stocker les données dans la session
if ($user) {
    $_SESSION['firstname'] = $user['firstname'];
    $_SESSION['lastname'] = $user['lastname'];
    $_SESSION['user_photo'] = $user['profile_picture'] ?? '../image/default-avatar.png';

    // Générer les initiales de l'utilisateur
    $_SESSION['user_initials'] = strtoupper(substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1));
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <!-- Menu latéral -->
    <nav class="sidebar">

    <div class="user-profile">
    <img src="<?php echo !empty($_SESSION['user_photo']) ? $_SESSION['user_photo'] : '../image/default-avatar.png'; ?>" 
     alt="Photo de l'utilisateur" class="user-photo">

    <h1 class="user-initials"></h1>
</div>
        <ul>
            <li><a href="#">Tableau de bord</a></li>
            <li><a href="#">Formations</a></li>
            <li><a href="#">Contacts</a></li>
            <li><a href="../php/parametres.php">Paramètres</a></li>
            
        </ul>

        <!-- Déconnexion -->
        <a class="logout-link" href="logout.php">Déconnexion</a>

        <div class="image">
            <img id="gefor" src="../image/gefor.jpg" alt="Logo Gefor">
        </div>

        <br><br>

    </nav>

    <!-- Contenu principal -->
    <section class="main-content">
        <!-- Section Tableau de bord -->
        <section class="dashboard-card">

        <div class="dashboard-info">
        <h2>Bienvenue <span class="username"><?php echo htmlspecialchars($_SESSION['firstname']); ?></span></h2>
        <p>Gérez les utilisateurs, les émargements et les paramètres du système depuis cet espace.</p>
            <div class="date-and-stats">
                <div class="date">Aujourd'hui<br><span id="current-date"></span></div>
                    <div class="stats-summary">
                        <p>0 feuilles démarrées</p>
                        <button>Créer une feuille</button>
                    </div>
            </div>
        </div>
            <img src="../image/gefor.jpg" alt="Dashboard Illustration">
        </section>

        <!-- Section Statistiques -->
        <section class="stats">
            <div class="stat-item">
                <h2>12</h2>
                <p>Apprenants</p>
            </div>
            <div class="stat-item">
                <h2>5</h2>
                <p>Intervenants</p>
            </div>
            <div class="stat-item">
                <h2>3</h2>
                <p>Absences</p>
            </div>
            <div class="stat-item">
                <h2>5</h2>
                <p>Groupes</p>
            </div>
            <div class="stat-item">
                <h2>5</h2>
                <p>Retards</p>
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

    <script>
        // Affichage de la date dynamique
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('fr-FR');
    </script>
</body>
</html>