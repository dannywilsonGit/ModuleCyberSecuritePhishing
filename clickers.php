<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Récupérer la liste des clicks dans phishing_model.php
include( dirname(__FILE__).'/phishing_model.php' );
//include( dirname(__FILE__).'/modules/phishing/create.php' );
$camp_id = (int) $_GET['id'];
$clickers = getAllClickers($camp_id);

?>


<div style="display: flex; align-items: center ; justify-content: space-between">
    <h3>Détails de la Campagne <?= $camp_id ?></h3>
</div>

<table border="1">
    <tr>
        <th>Id du click</th>
        <th>Id de la Campagne</th>
        <th>Nom de la Campagne</th>
        <th>Id du salarié</th>
        <th>Nom du salarié</th>
        <th>Email du salarié</th>
        <th>Moment du click</th>
    </tr>

    <?php foreach ($clickers as $clicker): ?>
        <tr>

            <td><?= $clicker['id'];?></td>
            <td><?= $clicker['campaign_id'];?></td>
            <td><?= getCampaignById($clicker['campaign_id']);?></td>
            <td><?= $clicker['user_id'];?></td>
            <td><?= getUserLogin($clicker['user_id']);?></td>
            <td><?= getUserEmail($clicker['user_id']);?></td>
            <td><?= $clicker['clicked_at'];?></td>
        </tr>
    <?php endforeach; ?>
</table>

