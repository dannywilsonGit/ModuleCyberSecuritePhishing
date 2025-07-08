<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/modules/configurations/configs.inc.php");
include($_SERVER["DOCUMENT_ROOT"] . "/include/functions.inc.inc.php");

include( dirname(__FILE__).'/phishing_model.php' );


if (!isset($_GET['cid'], $_GET['uid'])) {
    die("Lien invalide.");
}

$campaign_id = (int) $_GET['cid'];
$user_id = (int) $_GET['uid'];
$clicked_at = date('Y-m-d H:i:s') ;

global $app ;
//$bdd->BddSelect("SELECT * FROM phishing_clicks WHERE campaign_id = $campaign_id AND user_id = $user_id" , "");
var_dump($campaign_id);
var_dump($user_id);
var_dump($clicked_at);
$app->bdd->BddInsert("INSERT INTO phishing_clicks (campaign_id, user_id, clicked_at) VALUES ('$campaign_id', '$user_id' ,'$clicked_at')" , "Erreur lors de l'enregistrement du click");


header("Location: merci.php");
exit;
