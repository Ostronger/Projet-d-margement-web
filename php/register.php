<?php
session_start();

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'emargement');
if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    // Vérifier si l'utilisateur existe déjà
    $check_user = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($check_user);

    if ($result->num_rows > 0) {
        echo 'Un utilisateur avec ce nom ou cet email existe déjà.';
    } else {
        // Insérer un nouvel utilisateur
        $query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
        if ($conn->query($query)) {
            echo 'Utilisateur ajouté avec succès.';
        } else {
            echo 'Erreur : ' . $conn->error;
        }
    }
}
?>
