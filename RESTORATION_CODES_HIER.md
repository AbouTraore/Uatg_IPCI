# RESTAURATION DES CODES D'HIER

## 📋 Résumé des modifications apportées hier

### 🔄 Modifications de la base de données

**Avant (état d'origine) :**

- Table `patient` sans champ `Adresse`
- Table `patient` sans champ `date_creation`
- Pas de clé primaire sur `Numero_urap`

**Après (modifications d'hier) :**

- Ajout du champ `Adresse` varchar(100) dans la table `patient`
- Ajout du champ `date_creation` TIMESTAMP dans la table `patient`
- Ajout d'une clé primaire sur `Numero_urap`

### 📁 Fichiers modifiés hier

#### 1. **Insert_patient.php**

**Version actuelle :**

```php
// Inclut les champs Adresse et date_creation
$req = "INSERT INTO patient (
    Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
    Date_naissance, Contact_patient, Adresse, Situation_matrimoniale,
    Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession, date_creation
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
```

**Version d'hier (backup) :**

```php
// Sans les champs Adresse et date_creation
$req = "INSERT INTO patient (
    Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
    Date_naissance, Contact_patient, Situation_matrimoniale,
    Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
```

#### 2. **ajouter_patient.php**

**Version actuelle :**

- Inclut le champ "Adresse" dans le formulaire
- Section "Contact et adresse"

**Version d'hier (backup) :**

- Pas de champ "Adresse"
- Section "Contact et résidence"

## 🛠️ Comment restaurer les codes d'hier

### Option 1 : Restaurer complètement (recommandé)

1. **Exécuter le script de restauration de la base de données :**

   ```sql
   -- Exécuter le fichier : BD/restore_patient_table_original.sql
   ```

2. **Remplacer les fichiers actuels par les backups :**

   ```bash
   # Remplacer Insert_patient.php par la version backup
   cp page/Insert_patient_backup.php page/Insert_patient.php

   # Remplacer ajouter_patient.php par la version backup
   cp page/ajouter_patient_backup.php page/ajouter_patient.php
   ```

### Option 2 : Utiliser les fichiers de backup directement

1. **Modifier le formulaire pour pointer vers le backup :**

   ```html
   <!-- Dans ajouter_patient.php, changer l'action du formulaire -->
   <form
     method="POST"
     action="Insert_patient_backup.php"
     id="ajoutPatientForm"
   ></form>
   ```

2. **Exécuter le script SQL de restauration de la base de données**

## 📊 Comparaison des structures

### Table patient - État d'origine

```sql
CREATE TABLE `patient` (
  `Numero_urap` varchar(15) NOT NULL,
  `Nom_patient` varchar(20) NOT NULL,
  `Prenom_patient` varchar(30) NOT NULL,
  `Age` int NOT NULL,
  `Sexe_patient` varchar(10) NOT NULL,
  `Date_naissance` date NOT NULL,
  `Contact_patient` varchar(15) NOT NULL,
  `Situation_matrimoniale` varchar(25) NOT NULL,
  `Lieu_résidence` varchar(30) NOT NULL,
  `Precise` varchar(35) NOT NULL,
  `Type_logement` varchar(25) NOT NULL,
  `Niveau_etude` varchar(15) NOT NULL,
  `Profession` varchar(25) NOT NULL
);
```

### Table patient - État actuel

```sql
CREATE TABLE `patient` (
  `Numero_urap` varchar(15) NOT NULL,
  `Nom_patient` varchar(20) NOT NULL,
  `Prenom_patient` varchar(30) NOT NULL,
  `Age` int NOT NULL,
  `Sexe_patient` varchar(10) NOT NULL,
  `Date_naissance` date NOT NULL,
  `Contact_patient` varchar(15) NOT NULL,
  `Adresse` varchar(100) NOT NULL DEFAULT '',
  `Situation_matrimoniale` varchar(25) NOT NULL,
  `Lieu_résidence` varchar(30) NOT NULL,
  `Precise` varchar(35) NOT NULL,
  `Type_logement` varchar(25) NOT NULL,
  `Niveau_etude` varchar(15) NOT NULL,
  `Profession` varchar(25) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Numero_urap`)
);
```

## ⚠️ Points importants

1. **Données existantes :** Si vous restaurez complètement, les données existantes avec les nouveaux champs pourraient être perdues.

2. **Compatibilité :** Les formulaires actuels ne fonctionneront plus si vous supprimez les champs de la base de données.

3. **Recommandation :** Testez d'abord sur un environnement de développement avant de restaurer en production.

## 🔧 Fichiers de backup créés

- `page/Insert_patient_backup.php` - Version d'hier du traitement
- `page/ajouter_patient_backup.php` - Version d'hier du formulaire
- `BD/restore_patient_table_original.sql` - Script de restauration de la base de données

## 📝 Notes

Les modifications d'hier ont apporté des améliorations importantes :

- Meilleure traçabilité avec le champ `date_creation`
- Plus d'informations avec le champ `Adresse`
- Intégrité des données avec la clé primaire

Si vous souhaitez revenir à l'état d'origine, utilisez les fichiers de backup fournis.
