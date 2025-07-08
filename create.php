<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Formulaire de création d'une campagne de phishing RS
include( dirname(__FILE__).'/phishing_model.php' );
$destinataires = getAllUsers(); // récupère les destinataires possibles
$templates = getAllTemplates(); // récupère les fichiers .html du dossier templates
?>

<h2>Créer une nouvelle campagne de phishing</h2>
<form id="campaignForm" action="modules/phishing/save_campaign.php" method="post">

    <label>Nom de l'expéditeur :</label><br>
    <input type="text" name="nom_expediteur" required><br><br>

    <label>Email de l'expéditeur :</label><br>
    <input type="email" name="email_expediteur" required><br><br>

    <label>Titre de la campagne :</label><br>
    <input type="text" name="title" required><br><br>

    <label>Template email :</label><br>
    <select name="template" required>
        <?php foreach ($templates as $tpl): ?>
            <option value="<?= $tpl ?>"><?= $tpl ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Destinataires :</label><br>
    <select name="destinataires[]" multiple required>
        <?php foreach ($destinataires as $dest): ?>
            <option value="<?= $dest['iduser'] ?>">
                <?= $dest['email'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <button type="submit">Créer la campagne</button>
</form>

<script>
    document.getElementById('campaignForm').addEventListener('submit', function(e) {
        e.preventDefault(); // stop soumission classique

        const form = e.target;
        const formData = new FormData(form);

        fetch('modules/phishing/save_campaign.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                aff_onglet_content('tab_liste_phishings','Liste%20des%20campagnes','modules/phishing/index.php');
            })
            .catch(error => {
                alert("Erreur lors de la sauvegarde : " + error);
            });
    });
</script>
