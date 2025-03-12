<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Assurez-vous que le chemin est correct
require_once "config.php"; // Pour récupérer les paramètres SMTP

/**
 * Fonction pour envoyer un email avec PHPMailer
 * @param string $to Email du destinataire
 * @param string $subject Sujet de l'email
 * @param string $body Contenu de l'email (HTML possible)
 * @return bool True si l'email est envoyé, False sinon
 */
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE; // TLS recommandé
        $mail->Port = SMTP_PORT;

        // Paramètres de l'expéditeur
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addReplyTo(SMTP_FROM, SMTP_FROM_NAME);

        // Destinataire
        $mail->addAddress($to);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body); // Version texte brut

        // Envoi de l'email
        if ($mail->send()) {
            return true;
        } else {
            error_log("Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo, 3, "../logs/email_errors.log");
            return false;
        }
    } catch (Exception $e) {
        error_log("PHPMailer Exception : " . $e->getMessage(), 3, "../logs/email_errors.log");
        return false;
    }
}
?>