# TechStore - Boutique en Ligne High-Tech

## Description

TechStore est une boutique en ligne de produits high-tech et composants informatiques, développée en PHP avec une architecture MVC.

## Fonctionnalités

### Front-office
- **Carrousel animated** : Hero slider avec animations et effets visuels
- **Catalogue produits** : Navigation par catégories avec filtres
- **Promotions** : Section dédiée aux offres spéciales
- **Panier** : Gestion du panier d'achats
- **Compte client** : Inscription, connexion et historique de commandes

### Back-office (Admin)
- **Gestion des produits** : CRUD complet avec images
- **Gestion des catégories** : Organisation du catalogue
- **Gestion des utilisateurs** : Administration des clients
- **Gestion des commandes** : Suivi et traitement des commandes
- **Tableau de bord** : Statistiques et overview

## Structure du Projet

```
TechStore/
├── app/
│   ├── Controllers/     # Contrôleurs MVC
│   └── Core/           # Classes core (Controller)
├── config/             # Configuration (BDD, constantes)
├── public/
│   ├── css/           # Styles CSS
│   ├── js/            # JavaScript
│   └── uploads/       # Images uploadées
├── views/
│   ├── back/          # Templates admin
│   ├── front/         # Templates client
│   └── layout/        # Templates communs
├── index.php          # Point d'entrée
└── router.php        # Routing
```

## Installation

1. **Configuration serveur** :
   - XAMPP (PHP 8+, MySQL)
   - Apache avec mod_rewrite

2. **Base de données** :
   - Importer `config/database.sql`
   - Importer `config/categories_products.sql`

3. **Configuration** :
   - Modifier `config/db.php` avec vos identifiants BDD
   - Vérifier `config/constants.php`

4. **Lancer l'application** :
   - Accéder à `http://localhost/TechStore`

## Comptes de test

### Administrateur
- Email : `admin@techstore.com`
- Mot de passe : `admin123`

### Client
- Email : `client@techstore.com`
- Mot de passe : `client123`

## Technologies utilisées

- **Frontend** : Bootstrap 5, CSS3 animations, JavaScript
- **Backend** : PHP 8, PDO, MySQL
- **Architecture** : MVC

## Captures d'écran

Le site dispose de :
- Page d'accueil avec carrousel animée
- Catalogue produits avec filtres
- Pages produit détaillées
- Panier interactif
- Interface d'administration complète

## Licence

Tous droits réservés - TechStore 2026

