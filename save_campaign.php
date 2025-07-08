<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include(dirname(__FILE__) . '/phishing_model.php');

//Se connecter à la BDD via les identifiants de mon phpmyadmin
//session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/modules/configurations/configs.inc.php");
include($_SERVER["DOCUMENT_ROOT"] . "/include/functions.inc.inc.php");

if (
    !isset($_POST['nom_expediteur'], $_POST['email_expediteur'], $_POST['title'], $_POST['template'], $_POST['destinataires']) ||
    empty($_POST['nom_expediteur']) || empty($_POST['email_expediteur']) || empty($_POST['title']) || empty($_POST['template']) || empty($_POST['destinataires'])
) {
    die("Champs requis manquants.");
}

global $app; // pour accéder à $app->bdd

$nom_expediteur = addslashes($_POST['nom_expediteur']);
$email_expediteur = addslashes($_POST['email_expediteur']);
$title = addslashes($_POST['title']);
$template = addslashes($_POST['template']);
$destinataires = $_POST['destinataires'];
$send_date = 'NULL'; // champ NULL par defaut
$created_at = date('Y-m-d H:i:s');

// 1. Insertion dans phishing_campaigns
$query = "INSERT INTO phishing_campaigns (title, template_name, send_date, created_at, nom_expediteur, email_expediteur )
          VALUES ('$title', '$template', $send_date, '$created_at', '$nom_expediteur', '$email_expediteur')";

$app->bdd->BddInsert($query, "Erreur lors de l'insertion de la campagne");
$campaign_id = $app->bdd->dernier_id_insere();

// 2. Récupération des emails des destinataires sélectionnés
$allUsers = getAllUsers();
$userMap = [];
foreach ($allUsers as $user) {
    $userMap[$user['iduser']] = $user['email'];
}

// 3. Insertion dans phishing_campaign_recipients
foreach ($destinataires as $user_id) {
    $user_id = intval($user_id);
    if (isset($userMap[$user_id])) {
        $email = addslashes($userMap[$user_id]);
        $query2 = "INSERT INTO phishing_campaign_recipients (campaign_id, user_id, email)
                   VALUES ($campaign_id, $user_id, '$email')";
        $app->bdd->BddInsert($query2, "Erreur destinataire ID $user_id");
    }
}


header("Location: modules/phishing/index.php");
exit;