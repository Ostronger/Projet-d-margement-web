<?php

// Affichage des erreurs pour le debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Paramètres de connexion à la base de données
define('DB_HOST', 'localhost');  // Adresse du serveur (localhost pour un serveur local)
define('DB_USER', 'root');       // Nom d'utilisateur MySQL
define('DB_PASS', '');       // Mot de passe MySQL (mettre '' si vide)
define('DB_NAME', 'emargement'); // Nom de la base de données
define("BASE_URL","http://localhost");

// Paramètres SMTP pour l'envoi d'email
define('SMTP_HOST', 'smtp.gmail.com'); // Serveur SMTP (ex: smtp.gmail.com)
define('SMTP_PORT', 587); // Port SMTP (587 pour TLS, 465 pour SSL)
define('SMTP_USER', 'monprojetppefinal@gmail.com'); // Ton adresse email
define('SMTP_PASS', 'rsrb opzg bses qjsf'); // Ton mot de passe email
define('SMTP_FROM', 'monprojetppefinal@gmail.com'); // Email de l'expéditeur
define('SMTP_FROM_NAME', 'Support Projet Emargement'); // Nom de l'expéditeur
define('SMTP_SECURE', 'tls'); // Utiliser TLS pour Gmail
?>
