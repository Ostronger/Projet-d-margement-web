<?php

// Affichage des erreurs pour le debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Paramètres de connexion à la base de données
define('DB_HOST', 'localhost');  // Adresse du serveur (localhost pour un serveur local)
define('DB_USER', 'root');       // Nom d'utilisateur MySQL
define('DB_PASS', '');       // Mot de passe MySQL (mettre '' si vide)
define('DB_NAME', 'emargement'); // Nom de la base de données
define("BASE_URL","http://localhost:8000/myproject");

?>
