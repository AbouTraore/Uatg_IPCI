# Système de Remplissage Automatique des Informations Patient

## Vue d'ensemble

Ce système permet de remplir automatiquement les informations socio-démographiques du patient dans les différents formulaires d'examens après avoir créé un nouveau dossier patient.

## Fonctionnalités

### 1. Récupération des Informations Patient

- **Fichier :** `get_patient_info.php`
- **Fonction :** Récupère les informations complètes d'un patient par son numéro URAP
- **Retour :** JSON avec toutes les données du patient

### 2. Pages Modifiées

#### Page Nouveau Dossier (`nouveau_dossier.php`)

- Interface simplifiée sans boutons d'examens
- Focus sur la création et sélection des patients
- Navigation vers les examens via la page visite

#### Pages d'Examens Modifiées

- **`ecs.php`** - Examen Cytobactériologique du Sperme
- **`ecsu.php`** - Examen Cytobactériologique des Sécrétions Urétrales
- **`EXA_CYTO_SEC_VAG.php`** - Examen Cytobactériologique des Sécrétions Cervico-Vaginales

### 3. Fonctionnalités Ajoutées

#### Recherche Automatique

- Recherche automatique pendant la saisie du numéro URAP
- Déclenchement après 3 caractères minimum
- Délai de 500ms après arrêt de la saisie pour éviter les requêtes multiples
- Remplissage automatique des champs :
  - Âge
  - Nom
  - Prénom

#### Champs en Lecture Seule

- Les champs remplis automatiquement sont en lecture seule
- Évite les erreurs de saisie
- Garantit la cohérence des données

#### Messages de Feedback

- Messages de succès quand le patient est trouvé
- Messages d'erreur si le patient n'existe pas
- Animations pour une meilleure expérience utilisateur

#### Auto-remplissage par URL

- Si un numéro URAP est passé en paramètre URL (`?urap=12345`)
- Remplissage automatique au chargement de la page

## Utilisation

### 1. Depuis la Page Nouveau Dossier

1. Créer ou sélectionner un patient
2. Les liens vers les examens apparaissent automatiquement
3. Cliquer sur le type d'examen souhaité
4. Les informations du patient sont automatiquement remplies

### 2. Accès Direct aux Examens

1. Aller directement sur une page d'examen
2. Entrer le numéro URAP dans le champ N° Identification
3. Les informations sont automatiquement remplies pendant la saisie

### 3. Par URL

1. Ajouter `?urap=12345` à l'URL de la page d'examen
2. Les informations sont automatiquement remplies au chargement

## Structure Technique

### API de Récupération

```php
// get_patient_info.php
GET /get_patient_info.php?urap=12345
Response: {
  "success": true,
  "data": {
    "numero_urap": "12345",
    "nom": "Doe",
    "prenom": "John",
    "age": "30",
    "sexe": "Masculin",
    // ... autres informations
  }
}
```

### JavaScript

- Requêtes AJAX avec `fetch()`
- Gestion des erreurs et messages
- Animations CSS pour les transitions
- Auto-remplissage au chargement de page

## Avantages

1. **Gain de Temps** : Plus besoin de ressaisir les informations
2. **Réduction d'Erreurs** : Élimine les erreurs de saisie
3. **Cohérence** : Garantit que les mêmes informations sont utilisées partout
4. **Expérience Utilisateur** : Interface intuitive et responsive
5. **Flexibilité** : Plusieurs façons d'accéder aux examens

## Sécurité

- Validation des données côté serveur
- Échappement des caractères spéciaux
- Vérification de l'existence du patient
- Messages d'erreur informatifs

## Maintenance

- Le système est modulaire et facilement extensible
- Ajout de nouveaux types d'examens possible
- Code JavaScript réutilisable
- API centralisée pour la récupération des données
