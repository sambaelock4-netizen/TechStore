<?php
/**
 * TECHSTORE - Point d'entrée principal
 * Ce fichier est le seul accessible depuis le navigateur
 */

// Démarrer la session
session_start();

// Charger les fichiers de configuration
require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/config/db.php';

// Récupérer l'URL demandée
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Routing - Mapper les URLs vers les contrôleurs
$page = isset($url[0]) ? $url[0] : 'home';
$action = isset($url[1]) ? $url[1] : 'index';

// Tableau des pages valides
$valid_pages = ['home', 'catalogue', 'product', 'cart', 'login', 'register', 'account', 'orders', 'search', 'admin', 'logout', 'checkout'];

// Si la page n'est pas valide, afficher 404
if (!in_array($page, $valid_pages)) {
    $page = '404';
}

// Inclusion du fichier de vue
switch ($page) {
    case 'home':
        $title = 'TechStore - Votre boutique d\'équipements informatiques';
        $view = VIEW_PATH . '/front/home.php';
        break;
        
    case 'catalogue':
        $title = 'Catalogue - TechStore';
        $view = VIEW_PATH . '/front/catalogue.php';
        break;
        
    case 'product':
        $title = 'Produit - TechStore';
        $view = VIEW_PATH . '/front/product.php';
        break;
        
    case 'cart':
        $title = 'Panier - TechStore';
        $view = VIEW_PATH . '/front/cart.php';
        break;
        
    case 'login':
        $title = 'Connexion - TechStore';
        $view = VIEW_PATH . '/front/login.php';
        break;
        
    case 'register':
        $title = 'Inscription - TechStore';
        $view = VIEW_PATH . '/front/register.php';
        break;
        
    case 'account':
        $title = 'Mon compte - TechStore';
        $view = VIEW_PATH . '/front/account.php';
        break;
        
    case 'orders':
        $title = 'Mes commandes - TechStore';
        $view = VIEW_PATH . '/front/orders.php';
        break;
        
    case 'search':
        $title = 'Recherche - TechStore';
        $view = VIEW_PATH . '/front/search_results.php';
        break;
        
    case 'checkout':
        $title = 'Commande - TechStore';
        $view = VIEW_PATH . '/front/checkout.php';
        break;
        
    case 'admin':
        $title = 'Administration - TechStore';
        $view = VIEW_PATH . '/back/dashboard.php';
        break;
        
    case 'logout':
        // Déconnexion
        session_destroy();
        header('Location: ' . BASE_URL . '/home');
        exit;
        break;
        
    default:
        $title = 'Page introuvable - TechStore';
        $view = VIEW_PATH . '/404.php';
        break;
}

// Vérifier si le fichier de vue existe
if (!file_exists($view)) {
    $view = VIEW_PATH . '/404.php';
    $title = 'Page introuvable - TechStore';
}

// Inclure le layout header
require_once VIEW_PATH . '/layout/header.php';

// Inclure la vue
require_once $view;

// Inclure le layout footer
require_once VIEW_PATH . '/layout/footer.php';
