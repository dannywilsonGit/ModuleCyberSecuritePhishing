<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include( dirname(__FILE__).'/phishing_model.php' );

if (!class_exists('PHPMailer')) {
    require_once(__DIR__ . '/../../mail/class.phpmailer.php');
}

if (!isset($_GET['id'])) {
    die("ID campagne manquant.");
}

global $app; // pour accéder à $app->bdd

$campaign_id = (int)$_GET['id'];

// 1. Récupérer les infos de la campagne
$query = "SELECT * FROM phishing_campaigns WHERE id = $campaign_id";
$stmt = $app->bdd->BddSelect($query, "Erreur récupération campagne");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($result)) {
    die("Campagne introuvable.");
}

$campaign = $result[0];
$template_name = $campaign['template_name'];
$template_path = __DIR__ . "/templates/" . $template_name;

if (!file_exists($template_path)) {
    die("Template introuvable.");
}

$template_content = file_get_contents($template_path);

// 2. Récupérer les destinataires
$query = "SELECT user_id, email FROM phishing_campaign_recipients WHERE campaign_id = $campaign_id";
$destinataires = $app->bdd->BddSelect($query, "Erreur récupération destinataires");

// 3. Préparer l’envoi
$subject = "URGENT";
$from = $campaign['email_expediteur'];
$nom_expediteur = $campaign['nom_expediteur'];

foreach ($destinataires as $dest) {
    $user_id = (int)$dest['user_id'];
    $email = $dest['email'];

    // Générer lien de tracking
    $click_url = "https://test.rsdeveloppement.com/modules/phishing/click.php?cid=$campaign_id&uid=$user_id";


    // Injecter le lien dans le template
    $message = str_replace('{{CLICK_URL}}', $click_url, $template_content);

    // En-têtes mail HTML
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->IsHTML(true); // si tu envoies du HTML
    $mail->AddReplyTo($from, $nom_expediteur);
    $mail->SetFrom($from, $nom_expediteur);
    $mail->AddAddress($email);

    $mail->Subject = $subject;
    $mail->AltBody = "Veuillez utiliser un lecteur d’email HTML pour lire ce message.";
    $mail->MsgHTML($message);

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent to $email<br>";
    }

}

// 4. Mise a jour de la date d’envoi de la campagne
$update = "UPDATE phishing_campaigns SET send_date = NOW() WHERE id = $campaign_id";
$app->bdd->BddUpdate($update, "Erreur mise à jour date d’envoi");

header("Location: modules/phishing/index.php?success=1");
exit;

