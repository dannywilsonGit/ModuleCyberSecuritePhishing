<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Récupérer la liste des campagnes dans phishing_model.php
include( dirname(__FILE__).'/phishing_model.php' );
//include( dirname(__FILE__).'/modules/phishing/create.php' );
$campaigns = getAllCampaigns()

?>


<div style="display: flex; align-items: center ; justify-content: space-between">
    <h3>RS Campagnes Phishing</h3>
    <div><a style="cursor: pointer" onclick="aff_onglet_content('tab_phishing','Nouvelle%20campagne','modules/phishing/create.php');" class = "new-campaign"> + Nouvelle Campagne</a></div>
</div>

<table border="1">
    <tr>
        <th>id</th>
        <th>Nom de la campagne</th>
        <th>Nom expéditeur</th>
        <th>Email expéditeur</th>
        <th>création (Date et heure)</th>
        <th>envoi (Date et heure)</th>
        <th>Nb mails envoyés</th>
        <th>Nombre de clicks</th>
        <th>Template associé </th>
        <th>Statut</th>
        <th>Details</th>
    </tr>
    <?php foreach ($campaigns as $camp): ?>
        <tr>
            <td><?= $camp['id']; ?></td>
            <td><?= $camp['title'] ?></td>
            <td><?= $camp['nom_expediteur'] ?></td>
            <td><?= $camp['email_expediteur'] ?></td>
            <td><?= $camp['created_at'] ?></td>
            <td><?= $camp['send_date'] ?: 'Non envoyé' ?></td>
            <td><?= getNbMails($camp['id']) ?></td>
            <td><?= getNbClicks($camp['id']) ?></td>
            <td><?= $camp['template_name'] ?></td>
            <td>
                <?php if (is_null($camp['send_date'])): ?>
                    <a href="/modules/phishing/send_campaign.php?id=<?= $camp['id'] ?>" onclick="return confirm('Envoyer cette campagne maintenant ?');" style="text-decoration:none; border: 1px solid darkcyan ; border-radius: 10px; background-color: aqua">
                        📤 Envoyer
                    </a>
                <?php else: ?>
                    ✅ Envoyée
                <?php endif; ?>
            </td>
            <td><a style="cursor: pointer; color: #5E5DF0" onclick="aff_onglet_content('tab_details','Détails%20des%20clicks','modules/phishing/clickers.php?id=<?= $camp['id'] ?>');" class = "new-campaign">Aperçu</a></td>

        </tr>
    <?php endforeach; ?>
</table>

