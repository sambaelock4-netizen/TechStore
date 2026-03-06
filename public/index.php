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
// Pour les pages produit, l'ID est dans $url[1], pour admin c'est dans $url[2-3]
$id = null;
if (in_array($page, ['product'])) {
    $id = isset($url[1]) ? intval($url[1]) : null;
} else {
    $id = isset($url[2]) ? intval($url[2]) : null;
}

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
        // Pass the product ID to the view via $id variable
        // URL format: /product/{id}
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
        // Charger le contrôleur Admin
        require_once APP_PATH . '/Controllers/AdminController.php';
        
        $adminController = new AdminController($pdo);
        
        // Router les actions admin
        $adminAction = isset($url[1]) ? $url[1] : 'index';
        
        switch ($adminAction) {
            case '':
            case 'index':
                $adminController->index();
                break;
            case 'dashboard':
                $adminController->index();
                break;
            case 'products':
                $adminController->products();
                break;
            case 'product':
                $subAction = isset($url[2]) ? $url[2] : 'index';
                $productId = isset($url[3]) ? intval($url[3]) : null;
                if ($subAction === 'add') {
                    $adminController->addProduct();
                } elseif ($subAction === 'edit' && $productId) {
                    $adminController->editProduct($productId);
                } elseif ($subAction === 'delete' && $productId) {
                    $adminController->deleteProduct($productId);
                } else {
                    $adminController->products();
                }
                break;
            case 'orders':
                $subAction = isset($url[2]) ? $url[2] : 'index';
                $orderId = isset($url[3]) ? intval($url[3]) : null;
                if (is_numeric($subAction)) {
                    $adminController->viewOrder(intval($subAction));
                } elseif ($subAction === 'view' && $orderId) {
                    $adminController->viewOrder($orderId);
                } elseif ($subAction === 'update' && $orderId) {
                    $adminController->updateOrderStatus($orderId);
                } else {
                    $adminController->orders();
                }
                break;
            case 'users':
                $subAction = isset($url[2]) ? $url[2] : 'index';
                $userId = isset($url[3]) ? intval($url[3]) : null;
                if ($subAction === 'add') {
                    $adminController->addUser();
                } elseif ($subAction === 'edit' && $userId) {
                    $adminController->editUser($userId);
                } elseif ($subAction === 'delete' && $userId) {
                    $adminController->deleteUser($userId);
                } elseif ($subAction === 'reset' && $userId) {
                    $adminController->resetUserPassword($userId);
                } else {
                    $adminController->users();
                }
                break;
            case 'categories':
                $subAction = isset($url[2]) ? $url[2] : 'index';
                $catId = isset($url[3]) ? intval($url[3]) : null;
                if ($subAction === 'add') {
                    $adminController->addCategory();
                } elseif ($subAction === 'edit' && $catId) {
                    $adminController->editCategory($catId);
                } elseif ($subAction === 'delete' && $catId) {
                    $adminController->deleteCategory($catId);
                } else {
                    $adminController->categories();
                }
                break;
            case 'stock':
                $adminController->stock();
                break;
            case 'promotions':
                $subAction = isset($url[2]) ? $url[2] : 'index';
                $promoId = isset($url[3]) ? intval($url[3]) : null;
                if ($subAction === 'add') {
                    $adminController->addPromotion();
                } elseif ($subAction === 'edit' && $promoId) {
                    $adminController->editPromotion($promoId);
                } elseif ($subAction === 'delete' && $promoId) {
                    $adminController->deletePromotion($promoId);
                } else {
                    $adminController->promotions();
                }
                break;
            case 'statistics':
                $adminController->statistics();
                break;
            case 'profile':
                $adminController->profile();
                break;
            default:
                $adminController->index();
                break;
        }
        exit;
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
