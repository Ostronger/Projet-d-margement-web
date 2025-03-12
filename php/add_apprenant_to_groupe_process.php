<?php
require_once "session.php";
requireAuth('admin'); // Vérifie que l'utilisateur est un administrateur
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connection_bdd();

    $groupe_id = $_POST['groupe_id']; // ID du groupe sélectionné
    $user_id = $_POST['user_id']; // ID de l'utilisateur sélectionné

    try {
        // Vérifier que l'utilisateur a bien le rôle "apprenant"
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :user_id AND role = 'apprenant'");
        $stmt->execute(['user_id' => $user_id]);
        $apprenant = $stmt->fetch();

        if ($apprenant) {
            // Insérer l'utilisateur dans le groupe seulement s'il est "apprenant"
            $stmt = $pdo->prepare("INSERT INTO groupes_apprenants (groupe_id, apprenant_id) VALUES (:groupe_id, :user_id)");
            $stmt->execute(['groupe_id' => $groupe_id, 'user_id' => $user_id]);

            setSessionMessage('success', "Apprenant ajouté au groupe avec succès.");
        } else {
            setSessionMessage('error', "Erreur : Cet utilisateur n'est pas un apprenant.");
        }
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage());
        setSessionMessage('error', "Une erreur est survenue.");
    }

    header("Location: ../admin/manage_groupes.php"); // Redirection vers la gestion des groupes
    exit();
}