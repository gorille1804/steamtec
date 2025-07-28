# Implémentation SEO - SteamTec

## Vue d'ensemble

Ce document décrit l'implémentation SEO complète pour l'application SteamTec, incluant les meta tags, le sitemap, les données structurées et les optimisations techniques.

## 🎯 Objectifs SEO

### Mots-clés cibles principaux

#### Nettoyage Vapeur
- **Nettoyage vapeur** / **Nettoyage vapeur professionnel**
- **Nettoyage vapeur toiture** / **Nettoyage vapeur basse pression**
- **Nettoyage vapeur façade** / **Nettoyage vapeur terrasse**
- **Nettoyage vapeur bois** / **Nettoyage vapeur monuments**
- **Nettoyage vapeur bâtiment classé** / **Nettoyage vapeur bâtiment commercial**
- **Nettoyage vapeur bâtiment industriel**

#### Nettoyage Écologique
- **Nettoyage sans produit chimique** / **Nettoyage écologique**
- **Nettoyage écologique professionnel** / **Nettoyage écologique toiture**
- **Nettoyage écologique basse pression** / **Nettoyage écologique façade**
- **Nettoyage écologique terrasse** / **Nettoyage écologique bois**
- **Nettoyage écologique monuments** / **Nettoyage écologique bâtiment classé**
- **Nettoyage écologique bâtiment commercial** / **Nettoyage écologique bâtiment industriel**

#### Nettoyage Eau Chaude
- **Nettoyage eau chaude** / **Nettoyage eau chaude professionnel**
- **Nettoyage eau chaude toiture** / **Nettoyage eau chaude basse pression**
- **Nettoyage eau chaude façade** / **Nettoyage eau chaude terrasse**
- **Nettoyage eau chaude bois** / **Nettoyage eau chaude monuments**
- **Nettoyage eau chaude bâtiment classé** / **Nettoyage eau chaude bâtiment commercial**
- **Nettoyage eau chaude bâtiment industriel**

#### Désherbage Écologique
- **Désherbage sans produit chimique** / **Désherbage sans produit phytosanitaire**
- **Désherbage écologique** / **Désherbage alternatif**
- **Désherbage vapeur** / **Désherbage eau chaude**
- **Désherbage naturel** / **Désherbage professionnel**
- **Désherbage collectivité** / **Désherbage thermique**

#### Équipements
- **Nettoyeur vapeur** / **Nettoyeur vapeur professionnel**
- **Désherbeur écologique** / **Désherbeur vapeur**
- **Désherbeur professionnel** / **Désherbeur collectivité**
- **Désherbeuse écologique** / **Désherbeuse vapeur**

#### Marques et Fabrication
- **STEAMTEC** / **STEAM_TEC** / **STEAM TEC**
- **STEAMTECH** / **STEAM_TECH** / **STEAM TECH**
- **ENTECH** / **ENTEC**
- **Fabriqué en France** / **Fabricant français**
- **Machine sur remorque** / **Solution alternative de nettoyage**

### Pages importantes
1. **Page d'accueil** (`/`) - Priorité 1.0
2. **Nettoyage** (`/nettoyage`) - Priorité 0.8
3. **Désherbage** (`/desherbage`) - Priorité 0.8
4. **À propos** (`/societe`) - Priorité 0.8
5. **Configuration** (`/configuration`) - Priorité 0.7
6. **Clients** (`/client`) - Priorité 0.7
7. **Matériel** (`/materiel`) - Priorité 0.7
8. **Contact** (`/contact`) - Priorité 0.6
9. **Accessoires** (`/accessoire`) - Priorité 0.6

## 📋 Implémentation

### 1. Fichiers SEO de Base

#### robots.txt
- **Localisation** : `public/robots.txt`
- **Fonction** : Guide les robots d'indexation
- **Configuration** : Autorise les pages publiques (/societe, /configuration, /contact, /client, /nettoyage, /desherbage, /materiel, /accessoire, /login), bloque les zones privées

#### Sitemap XML
- **Localisation** : `src/Infrastructure/Controller/Seo/SitemapController.php`
- **URL** : `/sitemap.xml`
- **Fonction** : Génère dynamiquement le sitemap
- **Mise à jour** : Automatique selon les routes définies
- **Pages incluses** : 9 pages publiques avec priorités SEO optimisées

### 2. Meta Tags SEO

#### Service SEO
- **Localisation** : `src/Infrastructure/Symfony/Service/SeoService.php`
- **Fonction** : Gestion centralisée des meta tags
- **Configuration** : Meta tags par page avec valeurs par défaut

#### Meta Tags implémentés
```html
<!-- Meta tags de base -->
<title>SteamTec - Solutions éco-responsables</title>
<meta name="description" content="ENTECH, fabricant français...">
<meta name="keywords" content="nettoyage vapeur, désherbage écologique...">
<meta name="author" content="SteamTec">
<meta name="robots" content="index, follow">

<!-- Open Graph (Facebook) -->
<meta property="og:type" content="website">
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:image" content="...">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="...">
<meta name="twitter:description" content="...">

<!-- Canonical -->
<link rel="canonical" href="...">
```

### 3. Données Structurées (Schema.org)

#### Types implémentés
- **Organization** : Informations sur l'entreprise
- **ContactPoint** : Coordonnées de contact
- **PostalAddress** : Adresse physique

#### Exemple JSON-LD
```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "SteamTec",
  "url": "https://votre-domaine.com",
  "logo": "https://votre-domaine.com/assets/images/logo.png",
  "description": "Fabricant français de solutions éco-responsables...",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+33-XX-XX-XX-XX",
    "contactType": "customer service",
    "email": "contact@steamtec.fr"
  }
}
```

### 4. Optimisations Techniques

#### Compression Gzip
- **Fichier** : `.htaccess`
- **Types** : CSS, JS, HTML, XML
- **Impact** : Réduction de 60-80% de la taille des fichiers

#### Cache navigateur
- **Images** : 1 mois
- **CSS/JS** : 1 mois
- **Favicon** : 1 an
- **Pages** : 2 jours

#### Redirections SEO
- **Trailing slash** : Redirection automatique vers URLs avec `/`
- **HTTPS** : Recommandé pour la production

### 5. Analytics et Tracking

#### Google Analytics 4
- **Condition** : Uniquement en production
- **Configuration** : Via variable d'environnement `GOOGLE_ANALYTICS_ID`
- **Tracking** : Pages vues, événements personnalisés

#### Google Tag Manager
- **Condition** : Uniquement en production
- **Configuration** : Via variable d'environnement `GTM_ID`
- **Avantages** : Gestion centralisée des tags

## 🔧 Configuration

### Variables d'environnement
```env
# SEO
APP_URL=https://votre-domaine.com
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
GTM_ID=GTM-XXXXXXX

# Contact (pour les données structurées)
COMPANY_PHONE=+33-XX-XX-XX-XX
COMPANY_EMAIL=contact@steamtec.fr
COMPANY_ADDRESS=Votre adresse
COMPANY_CITY=Votre ville
COMPANY_POSTAL_CODE=00000
```

### Images SEO
- **OG Image** : `public/assets/images/og-image.jpg` (1200x630px)
- **Twitter Image** : `public/assets/images/twitter-image.jpg` (1200x600px)
- **Logo** : `public/assets/images/logo.png` (haute résolution)

## 📊 Monitoring

### Outils recommandés
1. **Google Search Console** : Indexation et performance
2. **Google Analytics** : Trafic et comportement utilisateur
3. **PageSpeed Insights** : Performance technique
4. **Schema.org Validator** : Validation des données structurées
5. **Facebook Sharing Debugger** : Test des Open Graph tags

### Métriques à surveiller
- **Positionnement** : Classement des mots-clés cibles
- **Trafic organique** : Visiteurs venant des moteurs de recherche
- **Taux de rebond** : Qualité du trafic
- **Temps de chargement** : Performance technique
- **Taux de conversion** : Efficacité commerciale

## 🚀 Actions à effectuer

### Immédiat (Priorité Haute)
1. ✅ Créer `robots.txt`
2. ✅ Implémenter le sitemap XML
3. ✅ Ajouter les meta tags SEO
4. ✅ Configurer les données structurées
5. ✅ Optimiser le `.htaccess`

### Court terme (Priorité Moyenne)
1. 🔄 Créer les images OG et Twitter
2. 🔄 Configurer Google Analytics
3. 🔄 Tester avec les outils de validation
4. 🔄 Soumettre le sitemap à Google Search Console

### Long terme (Priorité Basse)
1. 📝 Créer du contenu optimisé SEO
2. 📝 Implémenter un blog technique
3. 📝 Optimiser les images existantes
4. 📝 Créer des pages de landing spécifiques

## 📝 Notes importantes

### Sécurité
- Les zones privées (`/dashboard/*`) sont bloquées dans `robots.txt`
- Les fichiers sensibles (PDF) sont protégés
- Analytics uniquement en production

### Performance
- Compression Gzip activée
- Cache navigateur configuré
- Images optimisées et redimensionnées

### Maintenance
- Mettre à jour les meta tags lors de changements de contenu
- Vérifier régulièrement les données structurées
- Surveiller les performances dans Google Search Console 