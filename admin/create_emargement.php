<?php
require_once "../php/session.php";
requireAuth('admin'); // Vérifie que l'utilisateur est un administrateur
require_once "../php/db.php";

// Connexion à la base de données
$pdo = connection_bdd();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une feuille d'émargement</title>
    <link rel="stylesheet" href="../style/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/forms.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/forms_admin.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="container">
    <?php require_once "sidebar_admin.php";?><!-- Sidebar incluse -->

    <main class="main-content">
        <h1>Créer une feuille d'émargement</h1>
        <form action="../php/create_emargement_process.php" method="POST" >
         <div class="card form-admin"> 
            <label for="nom_feuille">Nom de la feuille :</label>
            <input type="text" id="nom_feuille" name="nom_feuille" required>

            <label for="formation_id">Formation :</label>
            <select id="formation_id" name="formation_id" required>
                <option value="">Sélectionner une formation</option>
                <?php
                $stmt = $pdo->query("SELECT id, nom FROM formations");
                while ($formation = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$formation['id']}'>{$formation['nom']}</option>";
                }
                ?>
            </select>

            <!-- Insérer le champ Formateur ici -->
            <label for="formateur_id">Formateur :</label>
            <select id="formateur_id" name="formateur_id" required>
                <option value="">Sélectionner un formateur</option>
                <?php
                $stmt = $pdo->prepare("SELECT id, firstname, lastname FROM users WHERE role = 'formateur'");
                $stmt->execute();
                while ($formateur = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$formateur['id']}'>{$formateur['firstname']} {$formateur['lastname']}</option>";
                }
                ?>
            </select>

            <!-- Conteneur pour conditionner l'affichage -->
            <div class="toggle-container">

            <!-- Sélection : Groupe ou Apprenants Individuels -->
                <input type="radio" id="choix_groupe" name="choix_apprenants" value="groupe" checked>
                <label for="choix_groupe">Groupe(s)</label>

                <input type="radio" id="choix_individuel" name="choix_apprenants" value="individuel">
                <label for="choix_individuel">Apprenant(s)</label>
                

            <!-- Sélection d'un ou plusieurs groupes -->
            <div class="toggle-section1" id="groupe_section">
                <select id="groupe_id" name="groupe_id[]" multiple>
                    <option value="">Sélectionner un ou plusieurs groupes</option>
                    <?php
                    $stmt = $pdo->query("SELECT id, nom FROM groupes");
                    while ($groupe = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$groupe['id']}'>{$groupe['nom']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Sélection d'un ou plusieurs apprenants -->
            <div class="toggle-section2" id="apprenants_section">
                <select id="apprenants" name="apprenants[]" multiple>
                    <option value="">Sélectionner un ou plusieurs apprenants</option>
                    <?php
                    $stmt = $pdo->query("SELECT id, firstname, lastname FROM users WHERE role = 'apprenant'");
                    while ($apprenant = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$apprenant['id']}'>{$apprenant['firstname']} {$apprenant['lastname']}</option>";
                    }
                    ?>
                </select>
            </div>

        </div>
          
        <label for="salle_nom">Salle :</label>
<input list="salles" name="salle_nom" id="salle_nom" required placeholder="Sélectionner ou entrer une salle">

<datalist id="salles">
    <?php
    $stmt = $pdo->query("SELECT id, nom FROM salles");
    while ($salle = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='{$salle['nom']}'>";  // On utilise le NOM de la salle
    }
    ?>
</datalist>

            <label for="date_debut">Date de début :</label>
            <input type="datetime-local" id="date_debut" name="date_debut" required>

            <label for="date_fin">Date de fin :</label>
            <input type="datetime-local" id="date_fin" name="date_fin" required>
            </div> 
            <button type="submit" class="button button--primary">Créer</button>
        </form>
    </main>
    </div>
    
</body>
</html>