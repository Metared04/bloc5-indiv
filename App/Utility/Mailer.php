<?php
namespace App\Utility;

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    /**
     * Envoie un email de contact au propriétaire d'une annonce.
     *
     * @param string $toEmail    Email du destinataire (propriétaire de l'annonce)
     * @param string $toName     Nom du destinataire
     * @param string $fromEmail  Email de l'expéditeur (visiteur)
     * @param string $articleName Nom de l'article concerné
     * @param string $message    Corps du message
     * @return void
     * @throws Exception
     */
    public static function sendContactMail(string $toEmail, string $toName, string $fromEmail, string $articleName, string $message) : void {
        $mail = new PHPMailer(true);$mail->isSMTP();

        $mail->Host       = Config::MAILTRAP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = Config::MAILTRAP_USER;
        $mail->Password   = Config::MAILTRAP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = Config::MAILTRAP_PORT;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(Config::MAILTRAP_FROM, 'Vide Grenier en Ligne');
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo($fromEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Message concernant votre annonce : ' . $articleName;
        $mail->Body = self::buildHtmlBody($toName, $fromEmail, $articleName, $message);
        $mail->AltBody = self::buildTextBody($toName, $fromEmail, $articleName, $message);

        $mail->send();
    }

    private static function buildHtmlBody(string $toName, string $fromEmail, string $articleName, string $message) : string {
        $safeToName = htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');
        $safeFromEmail = htmlspecialchars($fromEmail, ENT_QUOTES, 'UTF-8');
        $safeArticleName = htmlspecialchars($articleName, ENT_QUOTES, 'UTF-8');
        $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        return "
            <h2>Bonjour {$safeToName},</h2>
            <p>
                Vous avez reçu un message concernant votre annonce
                <strong>{$safeArticleName}</strong>.
            </p>
            <hr>
            <p>{$safeMessage}</p>
            <hr>
            <p>
                Pour répondre à cet utilisateur, contactez-le à l'adresse :
                <a href='mailto:{$safeFromEmail}'>{$safeFromEmail}</a>
            </p>
            <p><small>Vide Grenier en Ligne</small></p>
        ";
    }

    private static function buildTextBody(string $toName, string $fromEmail, string $articleName, string $message) : string {
        return "Bonjour {$toName},\n\n"
             . "Vous avez reçu un message concernant votre annonce \"{$articleName}\".\n\n"
             . "---\n{$message}\n---\n\n"
             . "Pour répondre : {$fromEmail}\n\n"
             . "Vide Grenier en Ligne";
    }
}