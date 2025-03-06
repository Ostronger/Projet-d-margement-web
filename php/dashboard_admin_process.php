<?php
require_once "session.php";

// Vérifier si l'utilisateur est connecté et a le rôle admin
requireAuth('admin');

// Charger les informations de l'utilisateur
loadUserData();

// Vérifier que les valeurs de session existent avant d'affecter les variables
$firstname = htmlspecialchars($_SESSION['firstname'] ?? 'Admin');
$user_photo = $_SESSION['user_photo'] ?? '../image/default-avatar.png';
$user_initials = $_SESSION['user_initials'] ?? 'A';

// Charger le contenu HTML du tableau de bord
require_once"../dashboard_admin.php";
?>