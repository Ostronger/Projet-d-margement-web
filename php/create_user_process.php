<?php
require_once "session.php";
requireAuth('admin');
require_once "db.php";
require_once "send_email.php";

// Fonction pour générer un token sécurisé
function generateToken($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pdo = connection_bdd();
   

    // Récupération des données envoyées depuis le formulaire
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Générer le username (exemple: prenom.nom)
    $username_base = strtolower($firstname) . '.' . strtolower($lastname);
    $username = $username_base;
   
    // Vérifier l'unicité du username et ajouter un suffixe si nécessaire
    $suffix = 1;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    while ($stmt->rowCount() > 0) {
        $username = $username_base . $suffix;
        $stmt->execute(['username' => $username]);
        $suffix++;
    }

    try {
       
        $pdo->beginTransaction();

        // Hachage temporaire en attendant la réinitialisation
        $hashedPassword = password_hash(generateToken(12), PASSWORD_DEFAULT);

        // Insérer l'utilisateur
        $stmt = $pdo->prepare("
            INSERT INTO users (username, firstname, lastname, email, password, role, must_change_password)
            VALUES (:username, :firstname, :lastname, :email, :password, :role, 1)
        ");

        $stmt->execute([
            'username'  => $username,
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email,
            'password'  => $hashedPassword,
            'role'      => $role
        ]);

       

        $user_id = $pdo->lastInsertId();

        // Générer un token de réinitialisation
        $resetToken = generateToken(64);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+2 hour')); // 1 heure de validité

        // Insérer le token dans password_resets
        $stmt = $pdo->prepare("
            INSERT INTO password_resets (user_id, token, expires_at)
            VALUES (:user_id, :token, :expires_at)
        ");

        $stmt->execute([
            'user_id'    => $user_id,
            'token'      => $resetToken,
            'expires_at' => $expiresAt
        ]);

        // Commit de la transaction
        $pdo->commit();
 
    

        // Envoi de l'email à l'utilisateur
        $resetLink = "http://localhost/Projet-d-margement-web/auth/reset_password.php?token=" . urlencode($resetToken);

        $subject = "Création de votre compte - Projet Emargement";
        $body = "
            <p>Bonjour <strong>$firstname $lastname</strong>,</p>
            <p>Votre compte a été créé sur notre plateforme.</p>
            <p>Votre identifiant est : <strong>$username</strong></p>
            <p>Pour activer votre compte et définir votre mot de passe, cliquez sur le lien suivant :</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p>Ce lien est valable pendant 1 heure.</p>
            <p>Cordialement,<br>L'équipe Projet Emargement</p>
        ";

        $emailSent = sendEmail($email, $subject, $body);

        if ($emailSent) {
            setSessionMessage('success', "Utilisateur créé et email de réinitialisation envoyé !");
        } else {
            setSessionMessage('warning', "Utilisateur créé mais l'email n'a pas pu être envoyé.");
        }

        header("Location: ../php/dashboard_admin_process.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Erreur SQL : " . $e->getMessage());
        setSessionMessage('error', "Une erreur est survenue lors de la création de l'utilisateur.");
        header("Location: ../admin/create_user.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erreur générale : " . $e->getMessage());
        setSessionMessage('error', "Une erreur est survenue, veuillez réessayer.");
        header("Location: ../admin/create_user.php");
        exit();
    }
}
?>