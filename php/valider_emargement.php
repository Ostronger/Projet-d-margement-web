<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté.");
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=emargement;charset=utf8mb4", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer le certificat enregistré
$stmt = $pdo->prepare("SELECT certificat_hash FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

// Récupérer la signature envoyée pour l'émargement
if (!empty($_POST['signature_data'])) {
    $signature_data = $_POST['signature_data'];
    $signature_hash = hash('sha256', $signature_data);

    // Vérifier si le certificat numérique est valide
    if ($signature_hash === $user['certificat_hash']) {
        echo "Émargement validé avec succès.";
        // Enregistrement dans la base des émargements validés
        $insert = $pdo->prepare("INSERT INTO emargements (user_id, date_emargement) VALUES (?, NOW())");
        $insert->execute([$_SESSION['user_id']]);
    } else {
        echo "Erreur : La signature ne correspond pas au certificat enregistré.";
    }
} else {
    echo "Aucune signature reçue.";
}
?>
