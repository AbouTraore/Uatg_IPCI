# 📋 Histoire de la Maladie - Connexion Base de Données

## 🎯 Objectif

Connecter la page "Histoire de la maladie" à la base de données MySQL pour permettre l'enregistrement et la gestion des consultations médicales.

## 🗄️ Structure de la Base de Données

### Table `histoire_maladie`

```sql
CREATE TABLE `histoire_maladie` (
    `id` int NOT NULL AUTO_INCREMENT,
    `numero_urap` varchar(15) NOT NULL,
    `sexe_patient` varchar(10) NOT NULL,
    `motif_homme` varchar(100) DEFAULT NULL,
    `motif_femme` varchar(100) DEFAULT NULL,
    `signes_fonctionnels` varchar(100) DEFAULT NULL,
    `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `numero_urap` (`numero_urap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Champs de la table :

- **`id`** : Identifiant unique auto-incrémenté
- **`numero_urap`** : Numéro URAP du patient (clé unique)
- **`sexe_patient`** : Sexe du patient (masculin/féminin)
- **`motif_homme`** : Motif de consultation pour les hommes
- **`motif_femme`** : Motif de consultation pour les femmes
- **`signes_fonctionnels`** : Signes fonctionnels observés
- **`date_creation`** : Date et heure de création de l'enregistrement

## 📁 Fichiers Créés/Modifiés

### 1. **`page/Histoire de la maladie.php`** (Modifié)

- ✅ Ajout de la création automatique de la table
- ✅ Correction de la logique d'insertion
- ✅ Gestion des erreurs améliorée

### 2. **`BD/create_histoire_maladie_table.sql`** (Nouveau)

- Script SQL pour créer la table manuellement
- Inclut des données de test

### 3. **`page/test_histoire_maladie_connection.php`** (Nouveau)

- Script de test pour vérifier la connexion
- Création automatique de la table
- Test d'insertion et de récupération

### 4. **`page/liste_histoire_maladie.php`** (Nouveau)

- Interface pour visualiser tous les enregistrements
- Statistiques des consultations
- Actions pour voir/modifier les enregistrements

## 🚀 Installation et Configuration

### Étape 1 : Vérifier la connexion

1. Accédez à : `http://localhost/Uatg_IPCI/page/test_histoire_maladie_connection.php`
2. Vérifiez que tous les tests passent ✅

### Étape 2 : Créer la table (si nécessaire)

Si la table n'existe pas, elle sera créée automatiquement lors de la première utilisation.

**Ou manuellement :**

```sql
-- Exécuter le fichier : BD/create_histoire_maladie_table.sql
```

### Étape 3 : Tester la page

1. Accédez à : `http://localhost/Uatg_IPCI/page/Histoire de la maladie.php`
2. Remplissez le formulaire avec un numéro URAP existant
3. Sélectionnez le sexe et le motif de consultation
4. Enregistrez les données

## 🔧 Fonctionnalités

### ✅ Fonctionnalités Implémentées

1. **Création automatique de la table** : La table est créée si elle n'existe pas
2. **Validation des données** : Vérification des champs obligatoires
3. **Gestion des doublons** : Un seul enregistrement par patient
4. **Interface responsive** : Design moderne et adaptatif
5. **Messages d'erreur/succès** : Feedback utilisateur clair
6. **Liste des enregistrements** : Visualisation de tous les enregistrements
7. **Statistiques** : Compteurs des consultations par sexe

### 📊 Données Gérées

#### Motifs de consultation - Hommes :

- Désir de paternité
- Dysurie
- Douleur testiculaire
- Gène urétral
- AMP
- Anomalie du spermogramme

#### Motifs de consultation - Femmes :

- Gynécologique
- Consultation IST
- Agent contaminateur
- Désir de grossesse
- Autre

#### Signes fonctionnels (optionnels) :

- Leucorrhées
- Prurit
- Mauvaise odeur
- Douleurs pelviennes

## 🔗 Liens Utiles

- **Page principale** : `Histoire de la maladie.php`
- **Liste des enregistrements** : `liste_histoire_maladie.php`
- **Test de connexion** : `test_histoire_maladie_connection.php`
- **Script SQL** : `BD/create_histoire_maladie_table.sql`

## 🛠️ Dépannage

### Problème : "Table n'existe pas"

**Solution :** Exécutez le script de test pour créer automatiquement la table.

### Problème : "Erreur de connexion"

**Solution :** Vérifiez les paramètres dans `connexion.php`

### Problème : "Patient non trouvé"

**Solution :** Assurez-vous que le numéro URAP existe dans la table `patient`

## 📈 Améliorations Futures

1. **Modification des enregistrements** : Permettre la modification des données existantes
2. **Suppression** : Ajouter la possibilité de supprimer des enregistrements
3. **Recherche** : Ajouter un système de recherche par patient
4. **Export** : Permettre l'export des données en CSV/PDF
5. **Historique** : Garder un historique des modifications

## 🔐 Sécurité

- ✅ Validation des données côté serveur
- ✅ Protection contre les injections SQL (PDO)
- ✅ Échappement des données affichées
- ✅ Vérification de l'authentification utilisateur

## 📝 Notes Techniques

- **Base de données** : MySQL
- **Moteur de table** : InnoDB (pour les clés étrangères)
- **Encodage** : UTF-8
- **Framework** : PHP natif avec PDO
- **Interface** : HTML5 + CSS3 + JavaScript

---

**Status :** ✅ **CONNECTÉ ET FONCTIONNEL**

La page "Histoire de la maladie" est maintenant entièrement connectée à la base de données et prête à être utilisée.
