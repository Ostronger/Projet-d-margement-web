<?php
// Générer et afficher un mot de passe haché pour les utilisateurs
$password = 'toto'; // Remplacez par le mot de passe réel à hacher
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe haché : $hashedPassword";
?>
