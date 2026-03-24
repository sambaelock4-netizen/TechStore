<?php
/**
 * ==================================================================================
 * TechStore - Router Principal
 * ==================================================================================
 * Ce fichier est le point d'entrée unique (Front Controller) de l'application.
 * Il est responsable de :
 * 1. Initialiser la session et les configurations
 * 2. Analyser l'URL demandée (via .htaccess et $_GET['url'])
 * 3. Mapper les URLs vers les vues ou les contrôleurs appropriés
 * 4. Gérer l'affichage final (Layout Header + Vue + Footer)
 * ==================================================================================
 */

// Démarrer la session utilisateur pour gérer le panier, l'authentification, etc.
session_start();

// Chargement des dépendances critiques
// - constants.php : Définit les chemins (ROOT, BASE_URL, VIEW_PATH, etc.)
// - db.php : Initialise la connexion PDO à la base de données
require_once dirname(__FILE__) . '/config/constants.php';
require_once dirname(__FILE__) . '/config/db.php';

/**
 * ANALYSE DE L'URL
 * L'URL est récupérée via le paramètre 'url' passé par la réécriture d'URL Apache (.htaccess).
 * Exemple : 'catalogue/view/1' devient ['catalogue', 'view', '1']
 */
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/'); // Supprime le slash final
$url = filter_var($url, FILTER_SANITIZE_URL); // Nettoie l'URL pour la sécurité
$url = explode('/', $url); // Découpe l'URL en segments

/**
 * DETERMINATION DE LA PAGE ET DE L'ACTION
 * $page : Le premier segment (ex: 'home', 'admin', 'product')
 * $action : Le second segment (souvent une méthode ou une sous-page)
 * $id : Identifiant numérique éventuel pour les produits, commandes, etc.
 */
$page = isset($url[0]) ? $url[0] : 'home';
$action = isset($url[1]) ? $url[1] : 'index';
$id = null;

// Logique spécifique pour l'extraction de l'ID selon la structure de l'URL
if (in_array($page, ['product'])) {
    $id = isset($url[1]) ? intval($url[1]) : null;
} else {
    $id = isset($url[2]) ? intval($url[2]) : null;
}

/**
 * VALIDATION DE LA PAGE
 * On vérifie si la page demandée existe dans notre liste de pages autorisées.
 * Sinon, redirection vers la page 404.
 */
$valid_pages = [
    'home', 'catalogue', 'product', 'cart', 'login', 
    'register', 'account', 'orders', 'admin', 
    'logout', 'checkout'
];

if (!in_array($page, $valid_pages)) {
    $page = '404';
}

/**
 * ROUTAGE ET PREPARATION DES VARIABLES DE VUE
 * Basé sur la page demandée, on définit le titre et le fichier de vue à inclure.
 */
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
        
    case 'checkout':
        $title = 'Commande - TechStore';
        $view = VIEW_PATH . '/front/checkout.php';
        break;
        
    case 'admin':
        /**
         * SECTION ADMINISTRATION
         * Le routage admin est géré via un contrôleur dédié (AdminController).
         * Il gère les produits, commandes, utilisateurs, catégories, etc.
         */
        require_once APP_PATH . '/Controllers/AdminController.php';
        
        $adminController = new AdminController($pdo);
        $adminAction = isset($url[1]) ? $url[1] : 'index';
        
        // Sous-routage pour l'interface d'administration
        switch ($adminAction) {
            case '':
            case 'index':
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
            case 'statistics':
                $adminController->statistics();
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
            case 'profile':
                $adminController->profile();
                break;
            default:
                $adminController->index();
                break;
        }
        // Pour les pages d'administration, on arrête le script ici car le contrôleur gère déjà l'affichage
        exit;
        
    case 'logout':
        // Deconnexion de l'utilisateur et redirection vers l'accueil
        session_destroy();
        header('Location: ' . BASE_URL . '/home');
        exit;
        
    default:
        $title = 'Page introuvable - TechStore';
        $view = VIEW_PATH . '/404.php';
        break;
}

/**
 * SECURITE ET RENDU FINAL
 * Vérifie si le fichier de vue existe avant de l'inclure.
 * Le rendu est composé du Header, du corps de la page (Vue) et du Footer.
 */
if (!file_exists($view)) {
    $view = VIEW_PATH . '/404.php';
    $title = 'Page introuvable - TechStore';
}

// Composition du layout HTML
require_once VIEW_PATH . '/layout/header.php';
require_once $view;
require_once VIEW_PATH . '/layout/footer.php';

