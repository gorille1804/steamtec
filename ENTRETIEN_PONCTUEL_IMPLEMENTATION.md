# Implémentation de l'Entretien Ponctuel

## Vue d'ensemble

L'entretien ponctuel a été implémenté en suivant exactement la même architecture que l'entretien régulier, mais avec des données JSON spécifiques fournies par l'utilisateur.

## Fichiers créés/modifiés

### 1. Template Twig
**Fichier :** `src/Infrastructure/templates/admin/entretien/ponctuel/index.html.twig`

- Interface utilisateur identique à l'entretien régulier
- Sélection de machine avec affichage des heures actuelles
- Saisie des heures pour localiser la ligne correspondante
- Tableau de maintenance avec les tâches spécifiques à l'entretien ponctuel
- Intégration du document PDF d'entretien

### 2. JavaScript
**Fichier :** `public/assets/js/ponctuel-maintenance-table.js`

- Données JSON intégrées selon les spécifications fournies
- 6 périodes d'entretien : 700h (1 an), 1400h (2 ans), 2100h (3 ans), 2800h (4 ans), 3500h (5 ans), 4200h (6 ans)
- 13 tâches d'entretien ponctuel :
  - Changer les buses des accessoires
  - Changer Filtre alimentation en eau
  - Changer la sonde débit*
  - Changer les Clapets de pompe*
  - Changer la vanne de régulation*
  - Changer le clapet du raccord de sortie*
  - Joints de piston*
  - Vérifier les câbles et capuchons d'électrodes
  - Nettoyer le brûleur*
  - Changer le brûleur*
  - Vérifier le filtre à carburant interne de la pompe à fuel
  - Changer la pompe à FUEL*
  - Vérifier l'intégrité du câblage électrique et les serrages sur l'ensemble de la machine*

### 3. Contrôleur
**Fichier :** `src/Infrastructure/Controller/Entretien/EntretienController.php`

- Méthode `ponctuel()` mise à jour pour récupérer les machines du parc utilisateur
- Passage des données `parcMachines` à la vue
- Réutilisation des endpoints existants pour la sauvegarde des logs

## Fonctionnalités

### Sélection de machine
- Dropdown avec toutes les machines du parc utilisateur
- Affichage du nom, numéro d'identification et heures actuelles
- Pré-remplissage automatique du champ heures

### Localisation des heures
- Saisie du nombre d'heures de la machine
- Surlignage automatique de la ligne correspondante
- Affichage d'informations contextuelles (exact, avant, après)
- Défilement automatique vers la ligne

### Tableau de maintenance
- Affichage de toutes les périodes d'entretien ponctuel
- Icônes d'outils pour les tâches requises
- Icônes moins pour les tâches non requises
- Colonnes heures et années pour chaque période

### Enregistrement des entretiens
- Modal de saisie avec date et sélection des tâches
- Validation des données
- Sauvegarde en base de données
- Marquage visuel des cellules complétées
- Messages de succès/erreur

### Persistance des données
- Récupération des logs existants
- Affichage des dates d'entretien effectués
- Sauvegarde locale pour la persistance

## Données JSON intégrées

```json
{
  "task_mapping": {
    "changer_buses_accessoires": "Changer les buses des accessoires",
    "changer_filtre_alimentation_eau": "Changer Filtre alimentation en eau",
    "changer_sonde_debit": "Changer la sonde débit*",
    "changer_clapets_pompe": "Changer les Clapets de pompe*",
    "changer_vanne_regulation": "Changer la vanne de régulation*",
    "changer_clapet_raccord_sortie": "Changer le clapet du raccord de sortie*",
    "joints_piston": "Joints de piston*",
    "verifier_cables_electrodes": "Vérifier les câbles et capuchons d'électrodes",
    "nettoyer_bruleur": "Nettoyer le brûleur*",
    "changer_bruleur": "Changer le brûleur*",
    "verifier_filtre_carburant": "Vérifier le filtre à carburant interne de la pompe à fuel",
    "changer_pompe_fuel": "Changer la pompe à FUEL*",
    "verifier_cablage_electrique": "Vérifier l'intégrité du câblage électrique et les serrages sur l'ensemble de la machine*"
  },
  "maintenance_schedule": [
    {
      "heures": "700",
      "annees": "1",
      // Configuration des tâches pour 700h/1an
    },
    // ... autres périodes
  ]
}
```

## Réutilisation de l'architecture existante

- **CSS :** Utilisation du fichier `maintenance-table.css` existant
- **Endpoints API :** Réutilisation des routes `/maintenance-table/log` et `/maintenance-table/logs`
- **Modèles de données :** Utilisation des mêmes entités `EntretienLog` et `ParcMachine`
- **Sécurité :** Mêmes contrôles d'accès et validation utilisateur

## Routes disponibles

- `GET /dashboard/entretiens/ponctuel` - Page principale
- `POST /dashboard/entretiens/maintenance-table/log` - Sauvegarde des logs
- `GET /dashboard/entretiens/maintenance-table/logs` - Récupération des logs

## Tests

Pour tester l'implémentation :

1. Accéder à `/dashboard/entretiens/ponctuel`
2. Sélectionner une machine du parc
3. Saisir un nombre d'heures (ex: 1250)
4. Cliquer sur une icône d'outil dans le tableau
5. Remplir le modal et enregistrer l'entretien
6. Vérifier que la cellule est marquée comme complétée

## Compatibilité

L'implémentation est entièrement compatible avec :
- L'architecture existante du projet
- Les données utilisateur et machines
- Le système de logs d'entretien
- L'interface utilisateur existante
- Les styles CSS existants 