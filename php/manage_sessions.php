<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Formateur') {
    header('Location: ../index.html');
    exit();
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'emargement');
if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

// Traitement du formulaire pour ajouter ou gérer les sessions de formation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session_name = $conn->real_escape_string($_POST['session_name']);
    $session_date = $conn->real_escape_string($_POST['session_date']);

    // Insertion de la nouvelle session dans la base de données
    $query = "INSERT INTO sessions (name, date) VALUES ('$session_name', '$session_date')";
    if ($conn->query($query)) {
        echo 'Session créée avec succès';
    } else {
        echo 'Erreur : ' . $conn->error;
    }
}

// Récupération des sessions existantes
$query = "SELECT * FROM sessions";
$sessions = $conn->query($query);
?>
