<?php

namespace utils;

use PHPMailer\PHPMailer\PHPMailer;

class EmailUtils
{
    static function sendEmail($to, $subject, $template, $data): bool
    {
        // Récupération des informations de configuration
        $config = include("configs.php");

        // Construction du message avec la librairie Template
        $message = Template::render("views/emails/$template.php", $data, false);

        // Création de l'instance PHPMailer (librairie doit être installée via Composer)
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $config["MAIL_SERVER"] ?: 'localhost';
        $mail->SMTPAuth = false;
        $mail->Port = 1025;
        $mail->SMTPDebug = 0; // Activer le debug si nécessaire (2 pour debug complet)

        $mail->setFrom($config["FROM_EMAIL"], "Contact CDS49");
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $result = $mail->send();

        // Log l'erreur si l'envoi échoue
        if (!$result && $config["DEBUG"]) {
            error_log("Erreur d'envoi d'email à $to: " . $mail->ErrorInfo);
        }

        return $result;
    }
}
