
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gefor - Tableau de Bord</title>
    <link rel="stylesheet" href="../style/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/dashboard_admin.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">

    <!-- Menu latéral -->
    <?php require_once "sidebar_admin.php";?>
    
    <?php
    if ($error = getSessionMessage('error')) {
        echo "<div class='error-message'>$error</div>";
    }

    if ($success = getSessionMessage('success')) {
        echo "<div class='success-message'>$success</div>";
    }
    ?>

    <!-- Contenu principal -->
    <section class="main-content">
    <?php
    if ($error = getSessionMessage('error')) {
        echo "<div class='error-message'>$error</div>";
    }

    if ($success = getSessionMessage('success')) {
        echo "<div class='success-message'>$success</div>";
    }
    ?>
        <h1 class="page-title">Tableau de Bord</h1>
        <section class="dashboard-card">
            <div class="dashboard-info">
                <P>Bienvenue <span class="username"><?php echo $firstname; ?>.</span></P>
                <p>Gérez les utilisateurs, les émargements et les paramètres du système depuis cet espace.</p>
                <div class="date-and-stats">
                    <div class="date">Aujourd'hui<br>
                    <span><?php echo $current_date; ?></span>
                    </div>
                    <div class="stats-summary">
                        <div class="stats-emargements">
                            <span class="emargement-stat"><?php echo $emargementsToday; ?></span> 
                            <p>feuilles démarrées</p>
                        </div>
                        <!-- Bouton pour créer une feuille d'émargement -->
                        <a href="../admin/create_emargement.php" class="button button--primary">Créer une feuille</a>
                    </div>
                </div>
            </div>
            <img src="../assets/images/dashboard_principal.png" alt="Dashboard Illustration">
        </section>

        <!-- Section Statistiques -->
        <section class="stats">
            <h2>Statistiques</h2>
            <div class="stat-item">
                <h3><?php echo $apprenants; ?></h3>
                <p>Apprenants</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $formateurs; ?></h3>
                <p>Formateurs</p>
            </div>
            <div class="stat-item">
                <h3><?php echo $emargements; ?></h3>
                <p>Émargements</p>
            </div>
        </section>

        <!-- Section Options -->
        <section class="options">
            <div class="option card">
                <a href="../admin/create_user.php">
                <button class="option-icon button button--primary">+</button>
                </a>
                <p>Ajouter un nouvel utilisateur</p>
            </div>

            <div class="option card">
                <button class="option-icon button button--primary"><img src="../assets/icons/settings.svg" alt="Paramètres" class="icon"></button>
                <p>Paramètres de l'organisme</p>
            </div>
        </section>
    </section>
    </div>
</body>
</html>