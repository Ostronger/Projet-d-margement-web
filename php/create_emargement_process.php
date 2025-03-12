<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "session.php";
requireAuth('admin'); 
require_once "db.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connection_bdd(); // Connexion à la base de données

    // Récupération des données
    $nom_feuille = trim($_POST['nom_feuille']);
    $formation_id = intval($_POST['formation_id']);
    $formateur_id = intval($_POST['formateur_id']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $choix_apprenants = $_POST['choix_apprenants'] ?? '';
    $salle_nom = trim($_POST['salle_nom'] ?? '');

    try {
        $pdo->beginTransaction();

        // Gestion de la salle (existante ou nouvelle)
        if (!empty($salle_nom)) {
            $stmt = $pdo->prepare("SELECT id FROM salles WHERE nom = :nom");
            $stmt->execute(['nom' => $salle_nom]);
            $salle_existante = $stmt->fetch();

            if ($salle_existante) {
                $salle_id = $salle_existante['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO salles (nom) VALUES (:nom)");
                $stmt->execute(['nom' => $salle_nom]);
                $salle_id = $pdo->lastInsertId();
            }
        } else {
            throw new Exception("Aucune salle sélectionnée !");
        }

        // Gestion des apprenants/groupe
        if ($choix_apprenants === 'groupe') {
            $groupe_ids = $_POST['groupe_id'] ?? [];

            if (!empty($groupe_ids)) {
                foreach ($groupe_ids as $groupe_id) {
                    $stmt = $pdo->prepare("
                        INSERT INTO feuilles_emargements 
                        (nom, formation_id, groupe_id, salle_id, formateur_id, date_debut, date_fin)
                        VALUES
                        (:nom, :formation_id, :groupe_id, :salle_id, :formateur_id, :date_debut, :date_fin)
                    ");
                    $stmt->execute([
                        'nom' => $nom_feuille,
                        'formation_id' => $formation_id,
                        'groupe_id' => $groupe_id,
                        'salle_id' => $salle_id,
                        'formateur_id' => $formateur_id,
                        'date_debut' => $date_debut,
                        'date_fin' => $date_fin
                    ]);

                    $feuille_id = $pdo->lastInsertId();

                    // Formateur
                    $stmt = $pdo->prepare("
                        INSERT INTO feuilles_emargements_signatures 
                        (feuille_id, user_id, role)
                        VALUES
                        (:feuille_id, :user_id, 'formateur')
                    ");
                    $stmt->execute([
                        'feuille_id' => $feuille_id,
                        'user_id' => $formateur_id
                    ]);

                    // Apprenants du groupe
                    $stmt_apprenants = $pdo->prepare("
                        SELECT user_id 
                        FROM groupes_apprenants 
                        WHERE groupe_id = :groupe_id
                    ");
                    $stmt_apprenants->execute(['groupe_id' => $groupe_id]);

                    while ($apprenant = $stmt_apprenants->fetch(PDO::FETCH_ASSOC)) {
                        $stmt = $pdo->prepare("
                            INSERT INTO feuilles_emargements_signatures 
                            (feuille_id, user_id, role)
                            VALUES
                            (:feuille_id, :user_id, 'apprenant')
                        ");
                        $stmt->execute([
                            'feuille_id' => $feuille_id,
                            'user_id' => $apprenant['user_id']
                        ]);
                    }
                }
            } else {
                throw new Exception("Aucun groupe sélectionné !");
            }

        } elseif ($choix_apprenants === 'individuel') {
            $apprenant_ids = $_POST['apprenants'] ?? [];

            if (!empty($apprenant_ids)) {
                $stmt = $pdo->prepare("
                    INSERT INTO feuilles_emargements 
                    (nom, formation_id, salle_id, formateur_id, date_debut, date_fin)
                    VALUES
                    (:nom, :formation_id, :salle_id, :formateur_id, :date_debut, :date_fin)
                ");
                $stmt->execute([
                    'nom' => $nom_feuille,
                    'formation_id' => $formation_id,
                    'salle_id' => $salle_id,
                    'formateur_id' => $formateur_id,
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin
                ]);

                $feuille_id = $pdo->lastInsertId();

                // Formateur
                $stmt = $pdo->prepare("
                    INSERT INTO feuilles_emargements_signatures 
                    (feuille_id, user_id, role)
                    VALUES
                    (:feuille_id, :user_id, 'formateur')
                ");
                $stmt->execute([
                    'feuille_id' => $feuille_id,
                    'user_id' => $formateur_id
                ]);

                // Apprenants individuels
                foreach ($apprenant_ids as $apprenant_id) {
                    $stmt = $pdo->prepare("
                        INSERT INTO feuilles_emargements_signatures 
                        (feuille_id, user_id, role)
                        VALUES
                        (:feuille_id, :user_id, 'apprenant')
                    ");
                    $stmt->execute([
                        'feuille_id' => $feuille_id,
                        'user_id' => $apprenant_id
                    ]);
                }
            } else {
                throw new Exception("Aucun apprenant sélectionné !");
            }
        }

        $pdo->commit();

        setSessionMessage('success', "Feuille d'émargement créée avec succès.");
        header("Location: ../php/dashboard_admin_process.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erreur : " . $e->getMessage());
        setSessionMessage('error', "Erreur : " . $e->getMessage());
        header("Location: ../admin/create_emargement.php");
        exit();
    }
}
?>