<?php
require_once "session.php";
require_once "db.php";
require_once "functions.php"; // Fichier où on met les fonctions réutilisables

// Vérifier si l'utilisateur est connecté et a le rôle admin
requireAuth('admin');

// Charger les informations de l'utilisateur
loadUserData();

// Vérifier que les valeurs de session existent avant d'affecter les variables
$firstname = htmlspecialchars($_SESSION['firstname'] ?? 'Admin');
$user_photo = !empty($_SESSION['user_photo']) ? $_SESSION['user_photo'] : '';
$user_initials = $_SESSION['user_initials'] ?? 'A';

// Connexion à la base de données
$pdo = connection_bdd();

// Récupérer les statistiques via des fonctions réutilisables
$apprenants = getTotalUsersByRole($pdo, 'apprenant');
$formateurs = getTotalUsersByRole($pdo, 'formateur');
$emargements = getTotalEmargements($pdo);
$emargementsToday = getFeuillesEmargementsToday($pdo);

// Générer la date actuelle en PHP
$current_date = date('d/m/Y');

// Charger le contenu HTML du tableau de bord
require_once "../admin/dashboard_admin.php";
?>