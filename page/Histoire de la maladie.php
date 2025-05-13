<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histoire de la Maladie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label, select {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 48%;
        }
        .btn-annuler {
            background-color: red;
            color: white;
        }
        .btn-enregistrer {
            background-color: green;
            color: white;
        }
        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<h2>Histoire de la Maladie</h2>
<div class="form-container">
    <form id="histoireForm">
        <h3>Homme</h3>
        <label for="motif_homme">Motif de la consultation :</label>
        <select name="motif_homme" id="motif_homme" required onchange="toggleFields()">
            <option value="" disabled selected>Choisissez un motif</option>
            <option value="paternite">Désire de paternité</option>
            <option value="dysurie">Dysurie</option>
            <option value="douleur_testiculaire">Douleur testiculaire</option>
            <option value="gene_uretral">Gène urétral</option>
            <option value="amp">AMP</option>
            <option value="anomalie_spermogramme">Anomalie du spermogramme</option>
        </select>

        <h3>Femme</h3>
        <label for="motif_femme">Motif de la consultation :</label>
        <select name="motif_femme" id="motif_femme" required onchange="toggleFields()">
            <option value="" disabled selected>Choisissez un motif</option>
            <option value="gynecologique">Gynécologique</option>
            <option value="consultation_ist">Consultation IST</option>
            <option value="agent_contaminateur">Agent contaminateur</option>
            <option value="desire_grossesse">Désire de grossesse</option>
            <option value="autre">Autre</option>
        </select>

        <label for="signes_fonctionnels">Signes fonctionnels :</label>
        <select name="signes_fonctionnels" id="signes_fonctionnels" required onchange="toggleFields()">
            <option value="" disabled selected>Choisissez un signe</option>
            <option value="leucorrhees">Leucorrhées</option>
            <option value="prurit">Prurit</option>
            <option value="mal_odeur">Mal-odeur</option>
            <option value="douleurs_pelviennes">Douleurs pelviennes</option>
        </select>

        <div class="button-container">
            <button type="button" class="btn-annuler" onclick="resetForm()">Annuler</button>
            <button type="submit" class="btn-enregistrer">Enregistrer</button>
        </div>
    </form>
</div>

<script>
    function toggleFields() {
        const motifHomme = document.getElementById('motif_homme');
        const motifFemme = document.getElementById('motif_femme');
        const signesFonctionnels = document.getElementById('signes_fonctionnels');

        if (motifHomme.value) {
            motifFemme.disabled = true;
            signesFonctionnels.disabled = true;
        } else {
            motifFemme.disabled = false;
            signesFonctionnels.disabled = false;
        }

        if (motifFemme.value || signesFonctionnels.value) {
            motifHomme.disabled = true;
        } else {
            motifHomme.disabled = false;
        }
    }

    function resetForm() {
        document.getElementById('histoireForm').reset();
        document.getElementById('motif_homme').disabled = false;
        document.getElementById('motif_femme').disabled = false;
        document.getElementById('signes_fonctionnels').disabled = false;
    }
</script>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et validation des données
    $motif_homme = htmlspecialchars($_POST['motif_homme']);
    $motif_femme = htmlspecialchars($_POST['motif_femme']);
    $signes_fonctionnels = htmlspecialchars($_POST['signes_fonctionnels']);

    echo "<h2>Données Soumises :</h2>";
    
    // Affichage des données homme
    if (!empty($motif_homme)) {
        echo "<p>Motif de consultation (Homme) : " . $motif_homme . "</p>";
    } else {
        echo "<p>Aucun motif de consultation pour l'homme sélectionné.</p>";
    }

    // Affichage des données femme
    if (!empty($motif_femme)) {
        echo "<p>Motif de consultation (Femme) : " . $motif_femme . "</p>";
    } else {
        echo "<p>Aucun motif de consultation pour la femme sélectionné.</p>";
    }

    // Affichage des signes fonctionnels
    if (!empty($signes_fonctionnels)) {
        echo "<p>Signes fonctionnels : " . $signes_fonctionnels . "</p>";
    } else {
        echo "<p>Aucun signe fonctionnel sélectionné.</p>";
    }
}
?>
</body>
</html>