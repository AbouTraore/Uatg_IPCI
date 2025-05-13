<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitudes Sexuelles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        .button-container button {
            width: 48%; /* Pour que les boutons prennent une largeur égale */
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Habitudes Sexuelles</h1>
    <form id="habitudesForm" action="process.php" method="post">
        <label for="type_rapport">Quel type de rapport avez-vous ?</label>
        <select name="type_rapport" id="type_rapport" required>
            <option value="" disabled selected>Choisissez un type</option>
            <option value="heterosexuel">Hétérosexuel</option>
            <option value="homosexuel">Homosexuel</option>
            <option value="bisexuel">Bisexuel</option>
        </select>

        <label for="cunnilingus">Pratiquez-vous le cunnilingus (Homme) ?</label>
        <select name="cunnilingus" id="cunnilingus" required onchange="toggleFields()">
            <option value="" disabled selected>Choisissez une option</option>
            <option value="jamais">Jamais</option>
            <option value="rarement">Rarement</option>
            <option value="quelquefois">Quelque fois</option>
            <option value="toujours">Toujours</option>
        </select>

        <label for="fellation">Pratiquez-vous la fellation (Femme) ?</label>
        <select name="fellation" id="fellation" required onchange="toggleFields()" disabled>
            <option value="" disabled selected>Choisissez une option</option>
            <option value="jamais">Jamais</option>
            <option value="rarement">Rarement</option>
            <option value="quelquefois">Quelque fois</option>
            <option value="toujours">Toujours</option>
        </select>

        <label for="preservatif">Utilisez-vous le préservatif ?</label>
        <select name="preservatif" id="preservatif" required>
            <option value="" disabled selected>Choisissez une option</option>
            <option value="jamais">Jamais</option>
            <option value="rarement">Rarement</option>
            <option value="quelquefois">Quelque fois</option>
            <option value="toujours">Toujours</option>
        </select>

        <label for="changement_partenaire">Avez-vous changé de partenaire ces deux derniers mois ?</label>
        <select name="changement_partenaire" id="changement_partenaire" required>
            <option value="" disabled selected>Choisissez une option</option>
            <option value="oui">Oui</option>
            <option value="non">Non</option>
        </select>

        <div class="button-container">
            <button type="submit">Soumettre</button>
            <button type="button" onclick="resetForm()">Effacer</button>
        </div>
    </form>
</div>

<script>
    function toggleFields() {
        const cunnilingusField = document.getElementById('cunnilingus');
        const fellationField = document.getElementById('fellation');

        if (cunnilingusField.value) {
            fellationField.disabled = true;
        } else {
            fellationField.disabled = false;
        }

        if (fellationField.value) {
            cunnilingusField.disabled = true;
        } else {
            cunnilingusField.disabled = false;
        }
    }

    function resetForm() {
        document.getElementById('habitudesForm').reset();
        document.getElementById('fellation').disabled = false;
        document.getElementById('cunnilingus').disabled = false;
    }
</script>

</body>
</html>