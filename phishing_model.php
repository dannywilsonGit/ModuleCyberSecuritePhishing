<?php
//Fonctions de connections à la base de donnée BDD
//Se connecter à la BDD via les identifiants de mon phpmyadmin
session_start();
include($_SERVER["DOCUMENT_ROOT"]."/modules/configurations/configs.inc.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/functions.inc.inc.php");

//function connectDb() {
//    try {
//        return new PDO(
//            'mysql:host=000.000.000.000;dbname=fghuruu;charset=utf8',
//            'user_login',
//            'user_password',
//            [
//                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // pour afficher les erreurs SQL
//                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // pour récupérer les résultats en tableau associatif
//            ]
//        );
//    } catch (PDOException $e) {
//        die('Erreur de connexion à la b ase de données : ' . $e->getMessage());
//    }
//}


//Récupérer toutes les campagnes depuis la BDD
function getAllCampaigns()
{
    global $app;
    $stmt = $app->bdd->BddSelect("SELECT * FROM phishing_campaigns ORDER BY created_at DESC", "");
    $results = [];
    while ($row = $app->bdd->lignesuivant($stmt)) {
        $results[] = $row;
    }
    return $results;
    //    $db = connectDb();
//    $stmt = $db->query("SELECT * FROM phishing_campaigns ORDER BY created_at DESC");
}

function getNbMails($campaign_id) {
    global $app;

    $query = "SELECT COUNT(*) as nb FROM phishing_campaign_recipients WHERE campaign_id = $campaign_id";
    $res = $app->bdd->BddSelect($query, "Erreur COUNT destinataires");

    if ($res && $row = $res->fetch(PDO::FETCH_ASSOC)) {
        return (int)$row['nb'];
    }

    return 0;
}


function getNbClicks($campaign_id) {
    global $app;

    $query = "SELECT COUNT(*) as nb FROM phishing_clicks WHERE campaign_id = $campaign_id";
    $res = $app->bdd->BddSelect($query, "Erreur COUNT clicks");

    if ($res && $row = $res->fetch(PDO::FETCH_ASSOC)) {
        return (int)$row['nb'];
    }

    return 0; // Si erreur ou aucun résultat
}

function getAllClickers($campaign_id) {
    global $app;
    $stmt = $app->bdd->BddSelect("SELECT * FROM phishing_clicks WHERE campaign_id = $campaign_id", "");
    $clickers = [];
    while ($row = $app->bdd->lignesuivant($stmt)) {
        $clickers[] = $row;
    }
    return $clickers;
}

//Tableau de mails RS à sélectionner

function getAllUsers() {
    //Si possibilité de récuperer les utilisateurs depuis la BDD
//    $db = connectDb();
//    $stmt = $db->query("SELECT id, nom, prenom, email FROM users WHERE actif = 1");//plutot à faire avec l'email
//    return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $destinataires = [
//        ['id' => 1, 'email' => 'rtrttrt@gmail.com'],
//        ['id' => 2, 'email' => 'rttrt@gmail.com'],
//        ['id' => 3, 'email' => 'rttrtrt@rtrtrtr.com'] ,
//        ['id' => 4, 'email' => 'rtrtrt@rtrtr.com'],
//    ];
    global $app;
    $stmt = $app->bdd->BddSelect("SELECT * FROM `contacts_vw` WHERE actif=1 AND iduser NOT IN (96, 1260)", "");
    $destinataires = [];
    while ($row = $app->bdd->lignesuivant($stmt)) {
        $destinataires[] = $row;
    }
    return $destinataires;
}

// Récupère tous les templates HTML disponibles dans le dossier /templates/
function getAllTemplates() {
    $templates = [];
    $dir = __DIR__ . '/templates';
    if (is_dir($dir)) {
        foreach (scandir($dir) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
                $templates[] = $file;
            }
        }
    }
    return $templates;
}

function getUserLogin($user_id) {
    global $app;
    $stmt = $app->bdd->BddSelect("SELECT identite FROM `contacts_vw` WHERE  iduser = $user_id", "");
    if ($row = $app->bdd->lignesuivant($stmt)) {
        return $row['identite'];
    }
    return null;


}
function getUserEmail($user_id) {
    global $app;
    $stmt = $app->bdd->BddSelect("SELECT email FROM `contacts_vw` WHERE  iduser = $user_id", "");
    if ($row = $app->bdd->lignesuivant($stmt)) {
        return $row['email'];
    }
    return null;
}

function getCampaignById($campaign_id) {
    global $app;
    $stmt = $app->bdd->BddSelect("SELECT title FROM `phishing_campaigns` WHERE  id = $campaign_id", "");
    if ($row = $app->bdd->lignesuivant($stmt)) {
        return $row['title'];
    }
    return null;
}


