<?php
// Inclure PHPMailer
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base sur InfinityFree (exemple à adapter avec TES infos)
    $pdo = new PDO('mysql:host=sqlXXX.infinityfree.com;dbname=if0_38901861_inscription;charset=utf8', 'if0_38901861', 'Q2rmvq8lxpHn');

    // Données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $formations = isset($_POST['formations']) ? implode(", ", $_POST['formations']) : '';

    // Insertion en base
    $stmt = $pdo->prepare("INSERT INTO inscriptions (nom, prenom, telephone, formations) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $telephone, $formations]);

    // Envoi de l'e-mail
    $mail = new PHPMailer(true);
    try {
        // Utiliser la fonction mail() de PHP (fonctionne sur InfinityFree)
        $mail->isMail();

        $mail->setFrom('noreply@gc.kesug.com', 'Formulaire G2C'); // Doit être un email de TON domaine
        $mail->addAddress('TON_EMAIL@gmail.com'); // Où tu veux recevoir l'inscription

        $mail->Subject = "Nouvelle inscription";
        $mail->Body = "Nom: $nom\nPrénom: $prenom\nTéléphone: $telephone\nFormations: $formations";

        $mail->send();
        echo "Inscription réussie. Email envoyé.";
    } catch (Exception $e) {
        echo "Inscription OK mais erreur mail : " . $mail->ErrorInfo;
    }
}

?>
