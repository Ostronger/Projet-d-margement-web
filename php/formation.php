<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . url("php/login.php"));
    exit();
}

// Connexion à la base de données
require_once "fonction.php";
$pdo = connection_bdd();


// Récupérer les formations
$stmt = $pdo->prepare("SELECT * FROM formations");
$stmt->execute();
$formations = $stmt->fetchAll(PDO::FETCH_ASSOC);

//​Définir l'accueil selon le rôle
$home_url = "dashboard.php"; //Valeur par défaut​
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            $home_url = "dashboard_admin.php";
            break;
        case 'apprenant':
            $home_url = "dashboard_apprenant.php";
            break;
        case 'formateur':
            $home_url = "dashboard_formateur.php";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <nav class="sidebar">
        <div class="user-profile">
            <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['firstname'], 0, 1) . substr($_SESSION['lastname'], 0, 1)); ?></div>
        </div>
        <ul>
            <li> <a href="<?php echo $home_url; ?>" class="home-button">Tableau de bord</a></li>
            <li class="active"><a href="formation.php">Formations</a></li>
            <li><a href="#">Contacts</a></li>
        </ul>
        <div class="settings">
            <a href="../php/parametres.php">Paramètres</a>
        </div>
        <div class="logo">
            <img src="../image/gefor.jpg" alt="Logo Gefor">
        </div>
    </nav>
    
    <main class="content">
        <header class="page-header">
            <h1>FORMATIONS</h1>
            <button class="add-button">Ajouter une formation</button>
        </header>
        
        <div class="filter-section">
            <button class="filter-button active">Toutes</button>
            <button class="filter-button">Cette semaine</button>
            <input type="text" placeholder="Recherche">
            <input type="date" placeholder="Période">
        </div>
        
        <table class="formation-table">
            <thead>
                <tr>
                    <th>NOM</th>
                    <th>Dates de la formation</th>
                    <th>Apprenants</th>
                    <th>Feuilles de présence</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($formations)): ?>
                    <?php foreach ($formations as $formation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($formation['nom']); ?></td>
                            <td>
                                <span>du <?php echo date('d/m/Y', strtotime($formation['date_debut'])); ?></span><br>
                                <span>au <?php echo date('d/m/Y', strtotime($formation['date_fin'])); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($formation['apprenants']); ?></td>
                            <td><?php echo htmlspecialchars($formation['feuilles_presence']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune formation trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="pagination">
            <button class="page-button">&lt;</button>
            <button class="page-button active">1</button>
            <button class="page-button">&gt;</button>
        </div>
    </main>
</body>
</html>
