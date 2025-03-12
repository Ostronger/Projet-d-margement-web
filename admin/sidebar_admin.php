<?php
// Vérification que l'utilisateur est bien un administrateur avant d'afficher la sidebar
require_once "../php/session.php";
requireAuth('admin'); 

// Charger les informations de l'utilisateur
loadUserData();

// Récupération des informations utilisateur
$firstname = htmlspecialchars($_SESSION['firstname'] ?? 'Admin');
$user_photo = $_SESSION['user_photo'] ?? ''; // Ne met pas d'image par défaut
$user_initials = strtoupper($_SESSION['user_initials'] ?? 'A'); // Mise en majuscules des initiales

$current_page = basename($_SERVER['PHP_SELF']); // Récupère le nom du fichier actuel
?>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="user-profile">
        <?php if (!empty($user_photo) && file_exists($user_photo)) : ?>
            <!-- Affichage de la photo si elle existe -->
            <img src="<?php echo htmlspecialchars($user_photo); ?>" alt="Photo de l'utilisateur" class="user-photo">
        <?php else : ?>
            <!-- Affichage des initiales si l'utilisateur n'a pas de photo -->
            <div class="user-initials-placeholder">
                <?php echo $user_initials; ?>
            </div>
        <?php endif; ?>
    </div>

    <a href="../php/logout.php">Déconnexion</a>

    <!-- Ligne de séparation -->
    <hr class="sidebar-divider">
    
    <ul class="menu-top">
        <li><a href="../php/dashboard_admin_process.php" class="<?= ($current_page == 'dashboard_admin_process.php') ? 'active' : '' ?>">
        <img src="../assets/icons/dashboard.svg" alt="Dashboard" class="icon">    
        Tableau de bord</a></li>
        
        <li><a href="../admin/formations_admin.php" class="<?= ($current_page == 'formations_admin.php') ? 'active' : '' ?>">
        <img src="../assets/icons/formations.svg" alt="Formations" class="icon">    
        Formations</a></li>
        
        <li><a href="../admin/contacts_admin.php" class="<?= ($current_page == 'contacts_admin.php') ? 'active' : '' ?>">
        <img src="../assets/icons/contacts.svg" alt="Contacts" class="icon">    
        Contacts</a></li>
    </ul>

    <ul class="menu-bottom">
        <li><a href="../admin/parametres_admin.php" class="<?= ($current_page == 'parametres_admin.php') ? 'active' : '' ?>">
        <img src="../assets/icons/settings.svg" alt="Paramètres" class="icon">
        Paramètres</a></li>
    </ul>

    <!-- Ligne de séparation -->
    <hr class="sidebar-divider">

    <div class="image">
        <img id="gefor" src="../assets/images/gefor.jpg" alt="Logo Gefor">
    </div>
</nav>