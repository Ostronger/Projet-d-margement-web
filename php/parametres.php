<?php
session_start();

//​Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

//​Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=emargement;charset=utf8mb4", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

//​Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

//​Définir l'accueil selon le rôle
$home_url = "dashboard.php"; //Valeur par défaut​
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            $home_url = "dashboard_admin.php";
            break;
        case 'apprenant':
            $home_url = "dashboard_apprenant.php";
            break;
        case 'formateur':
            $home_url = "dashboard_formateur.php";
            break;
    }
}

//​Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];
    $classe = $_POST['classe'];
    
    // Gestion de la suppression de la photo
    if (isset($_POST['delete_photo'])) {
        if (!empty($user['profile_picture']) && file_exists(__DIR__ . "/" . $user['profile_picture'])) {
            unlink(__DIR__ . "/" . $user['profile_picture']); // Supprimer l'image du serveur
        }
        $photo = null;
    } else {
        //Gestion de l'upload de la photo
        $photo = $user['profile_picture']; 
        if (!empty($_FILES['photo']['name'])) {
            $upload_dir = __DIR__ . "/uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $photo_path = "uploads/photo_" . $_SESSION['user_id'] . "_" . time() . ".jpg";
            if (move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . "/" . $photo_path)) {
                $photo = $photo_path;
            } else {
                echo "<p style='color:red;'>⚠ Erreur : Impossible de sauvegarder l'image.</p>";
            }
        }
    }

// Gestion de la signature
$signature_filename = $user['signature'];

if (!empty($_POST['signature_data'])) {
    $signature_data = $_POST['signature_data'];
    echo "<p>✅ Données de signature reçues.</p>"; // Vérifie que le formulaire envoie bien la signature

    // Nettoyer le nom de l'utilisateur pour éviter les caractères spéciaux
    $clean_name = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($user['lastname'] . "_" . $user['firstname']));

    // Définir le chemin du dossier et du fichier
    $upload_dir = __DIR__ . "/uploads/";
    $signature_filename = "uploads/signature_" . $clean_name . ".png";

    // Vérifier si le dossier 'uploads/' existe, sinon le créer
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
        echo "<p>📂 Dossier 'uploads/' créé.</p>";
    }

    // Vérifier si la signature est bien reçue sous le bon format
    if (strpos($signature_data, "data:image/png;base64,") === 0) {
        echo "<p>✅ Signature au bon format Base64 détectée.</p>";

        $decoded_data = base64_decode(explode(",", $signature_data)[1]);

        if ($decoded_data) {
            echo "<p>✅ Décodage Base64 réussi.</p>";

            if (file_put_contents(__DIR__ . "/" . $signature_filename, $decoded_data)) {
                echo "<p style='color:green;'>✅ Signature enregistrée avec succès : " . $signature_filename . "</p>";
            } else {
                echo "<p style='color:red;'>⚠ Erreur : Impossible d'enregistrer la signature.</p>";
            }
        } else {
            echo "<p style='color:red;'>⚠ Erreur : Échec du décodage Base64.</p>";
        }
    } else {
        echo "<p style='color:red;'>⚠ Erreur : Données de signature incorrectes.</p>";
    }
} else {
    echo "<p style='color:red;'>⚠ Aucune signature reçue.</p>";
}




    //Mise à jour des informations dans la base
    $update = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, dob = ?, classe = ?, profile_picture = ?, signature = ? WHERE id = ?");
    $update->execute([$firstname, $lastname, $dob, $classe, $photo, $signature_filename, $_SESSION['user_id']]);

    header("Location: parametres.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres</title>
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <div class="main-content">
        <div class="background-image">

            <a href="<?php echo $home_url; ?>" class="home-button">
            <div class="sidebar">
                <div class="profile">
                    <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'default.png'); ?>" alt="Photo de profil">
                </div>
                <div class="logout">
                    <a href="logout.php" class="logout-button">Déconnexion</a>
                </div>
                <div class="logo">
                    <img src="../style/logo.png" alt="Logo du groupe">
                </div>
            </div>

            <h2>Paramètres de votre compte</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="settings-form">
                <label><strong>Nom :</strong></label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>

                <label><strong>Prénom :</strong></label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>

                <label><strong>Date de naissance :</strong></label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>

                <label><strong>Classe :</strong></label>
                <select name="classe" required>
                    <option value="BTS SIO SLAM" <?php echo ($user['classe'] == 'BTS SIO SLAM') ? 'selected' : ''; ?>>BTS SIO SLAM</option>
                    <option value="BTS SIO SISR" <?php echo ($user['classe'] == 'BTS SIO SISR') ? 'selected' : ''; ?>>BTS SIO SISR</option>
                    <option value="Autre BTS" <?php echo ($user['classe'] == 'Autre BTS') ? 'selected' : ''; ?>>Autre BTS</option>
                </select>

                <label><strong>Photo de profil :</strong></label>
                <input type="file" name="photo" accept="image/*">
                <?php if (!empty($user['profile_picture'])) { 
                    echo "<div class='delete-photo-container'><input type='checkbox' name='delete_photo' class='delete-photo'> Supprimer la photo</div>";
                } ?>

                <label><strong>Signature :</strong></label>
                <div class="signature-container">
                    <canvas id="signature-pad" class="signature-pad"></canvas>
                    <input type="hidden" name="signature_data" id="signature_data">
                    <button type="button" class="clear-signature" onclick="clearSignature()">Effacer</button>
                </div>

                <button type="submit" class="save-button">Enregistrer</button>
            </form>
        </div>
    </div>
    <script>
        let canvas = document.getElementById("signature-pad");
        let ctx = canvas.getContext("2d");
        let drawing = false;

        canvas.addEventListener("mousedown", startDrawing);
        canvas.addEventListener("mouseup", stopDrawing);
        canvas.addEventListener("mousemove", draw);
        canvas.addEventListener("touchstart", startDrawing);
        canvas.addEventListener("touchend", stopDrawing);
        canvas.addEventListener("touchmove", draw);

        function startDrawing(e) {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        }

        function stopDrawing() {
            drawing = false;
        }

        function draw(e) {
            if (!drawing) return;
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        }

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    </script>
</body>
</html>
