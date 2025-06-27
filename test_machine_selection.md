# Modification de la page d'entretien régulier - Sélection de machine

## Résumé des modifications

La page d'entretien régulier a été modifiée pour permettre la sélection d'une machine du parc de l'utilisateur avant de définir le volume d'heures.

## Modifications apportées

### 1. Contrôleur (`src/Infrastructure/Controller/Entretien/EntretienController.php`)

- **Méthode `regulier()`** : Ajout de la récupération des machines du parc de l'utilisateur
- **Méthode `createMaintenanceTableLog()`** : Modification pour utiliser l'ID de la machine sélectionnée au lieu de la première machine de l'utilisateur

### 2. Template (`src/Infrastructure/templates/admin/entretien/regulier/index.html.twig`)

- Ajout d'une zone de sélection de machine avec un dropdown
- Affichage conditionnel des sections (heures et tableau) selon qu'une machine est sélectionnée ou non
- Message d'information si l'utilisateur n'a pas de machines dans son parc
- Les options du select affichent : `Nom (Numéro) - Heures actuelles`

### 3. JavaScript (`public/assets/js/maintenance-table.js`)

- Ajout de la fonction `initializeMachineSelection()` pour gérer la sélection de machine
- Variables globales pour stocker les informations de la machine sélectionnée
- Pré-remplissage automatique du champ des heures avec les heures actuelles de la machine
- Affichage/masquage conditionnel des sections
- Modification de `saveMaintenanceLog()` pour inclure l'ID de la machine dans les données envoyées

### 4. CSS (`public/assets/css/maintenance-table.css`)

- Styles pour la sélection de machine (focus, validation, etc.)
- Animations pour l'apparition des sections
- Styles responsives pour mobile

## Fonctionnalités

1. **Sélection de machine** : L'utilisateur doit d'abord sélectionner une machine de son parc
2. **Pré-remplissage automatique** : Les heures actuelles de la machine sont automatiquement affichées
3. **Validation** : Vérification qu'une machine est sélectionnée avant l'enregistrement
4. **Interface adaptative** : Les sections s'affichent progressivement selon les actions de l'utilisateur
5. **Gestion des erreurs** : Messages appropriés si l'utilisateur n'a pas de machines

## Flux utilisateur

1. L'utilisateur arrive sur la page d'entretien régulier
2. Si il n'a pas de machines dans son parc, un message l'informe et lui propose d'en ajouter
3. Si il a des machines, il voit un dropdown pour sélectionner une machine
4. Une fois une machine sélectionnée :
   - La section des heures apparaît avec les heures actuelles pré-remplies
   - Le tableau de maintenance s'affiche
   - La ligne correspondant aux heures actuelles est automatiquement mise en surbrillance
5. L'utilisateur peut modifier les heures si nécessaire
6. Les logs d'entretien sont créés avec la machine sélectionnée

## Sécurité

- Vérification que la machine sélectionnée appartient bien au parc de l'utilisateur
- Validation côté serveur de l'ID de machine
- Messages d'erreur appropriés en cas de problème 