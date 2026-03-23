<?php
/**
 * TECHSTORE - Point d'entrée principal
 * Ce fichier est le seul accessible depuis le navigateur
 */

// Démarrer le buffer de sortie AVANT tout output
ob_start();

// Démarrer la session
session_start();

// Charger les fichiers de configuration
require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/config/db.php';

// ── TEST NOTCHPAY — accéder via ?test_payment=1 ──
if (isset($_GET['test_payment'])) {
    $payload = [
        'amount'      => 500,
        'currency'    => 'XAF',
        'email'       => 'test@techstore.cm',
        'name'        => 'Test User',
        'reference'   => 'TEST-' . time(),
        'callback'    => BASE_URL . '/payment/callback',
        'description' => 'Test paiement TechStore',
    ];

    $ch = curl_init('https://api.notchpay.co/payments/initialize');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST,           true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER,     [
        'Authorization: ' . NOTCHPAY_PUBLIC_KEY,
        'Content-Type: application/json',
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE,      CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_TIMEOUT,        30);

    $result  = curl_exec($ch);
    $error   = curl_error($ch);
    $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $decoded = json_decode($result, true);

    echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
    <style>body{font-family:monospace;background:#0d1117;color:#e6edf3;padding:30px}
    pre{background:#161b22;border:1px solid #30363d;padding:20px;border-radius:8px;overflow:auto}
    .ok{color:#3fb950}.err{color:#f85149}.info{color:#58a6ff}h2{color:#fff}
    a{color:#58a6ff}</style></head><body>';
    echo '<h2>🔍 Test NotchPay API</h2><pre>';
    echo '<span class="info">Clé publique :</span> ' . substr(NOTCHPAY_PUBLIC_KEY, 0, 25) . '...' . "\n\n";
    echo '<span class="info">HTTP Code    :</span> ' . ($code == 201 || $code == 200
        ? '<span class="ok">' . $code . ' ✅</span>'
        : '<span class="err">' . $code . ' ❌</span>') . "\n";
    echo '<span class="info">Erreur cURL  :</span> ' . ($error
        ? '<span class="err">' . $error . '</span>'
        : '<span class="ok">aucune ✅</span>') . "\n\n";
    echo '<span class="info">Réponse JSON :</span>' . "\n";
    echo htmlspecialchars(json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    // authorization_url est à la RACINE de la réponse
    $authUrl = $decoded['authorization_url'] ?? $decoded['transaction']['authorization_url'] ?? null;
    if ($authUrl) {
        echo "\n\n" . '<span class="ok">✅ URL paiement : </span>';
        echo '<a href="' . $authUrl . '">' . $authUrl . '</a>';
    }
    echo '</pre></body></html>';
    exit;
}
// ── FIN TEST ──

// Récupérer l'URL demandée
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Routing - Mapper les URLs vers les contrôleurs
$page = isset($url[0]) ? $url[0] : 'home';
$action = isset($url[1]) ? $url[1] : 'index';
$id = null;
if (in_array($page, ['product'])) {
    $id = isset($url[1]) ? intval($url[1]) : null;
} else {
    $id = isset($url[2]) ? intval($url[2]) : null;
}

// Tableau des pages valides
$valid_pages = ['home', 'catalogue', 'product', 'cart', 'login', 'register', 'account', 'orders', 'search', 'admin', 'logout', 'checkout', 'payment'];

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
        
    case 'payment':
        $title = 'Paiement - TechStore';
        $view = VIEW_PATH . '/front/payment_callback.php';
        break;

    case 'checkout':
        $title = 'Commande - TechStore';
        $view  = VIEW_PATH . '/front/checkout.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
            $uid   = $_SESSION['user_id'];
            $aid   = trim($_POST['address_id'] ?? '');
            $notes = trim($_POST['notes']      ?? '');
            $cart  = json_decode($_POST['cart_data'] ?? '[]', true);

            $checkout_error = '';
            $checkout_oid   = null;

            if (empty($cart)) {
                $checkout_error = 'Votre panier est vide.';
            } elseif (empty($aid)) {
                $checkout_error = 'Veuillez sélectionner une adresse de livraison.';
            } else {
                try {
                    $s = $pdo->prepare("SELECT * FROM addresses WHERE id=? AND user_id=?");
                    $s->execute([$aid, $uid]);
                    $addr = $s->fetch();

                    if (!$addr) {
                        $checkout_error = 'Adresse introuvable.';
                    } else {
                        // Calcul total
                        $total = 0;
                        foreach ($cart as $it) $total += floatval($it['price']) * intval($it['quantity']);

                        // Créer la commande
                        $s = $pdo->prepare("INSERT INTO orders (user_id,total_amount,status,payment_status,shipping_address,shipping_city,shipping_postal_code,notes,created_at) VALUES (?,?,'confirmé','payé',?,?,?,?,NOW())");
                        $s->execute([$uid, $total, $addr['address'], $addr['city'], $addr['postal_code'] ?? '', $notes]);
                        $checkout_oid = $pdo->lastInsertId();

                        // Insérer les articles
                        foreach ($cart as $it) {
                            $s = $pdo->prepare("INSERT INTO order_items (order_id,product_id,quantity,unit_price) VALUES (?,?,?,?)");
                            $s->execute([$checkout_oid, intval($it['id']), intval($it['quantity']), floatval($it['price'])]);
                        }
                    }
                } catch (PDOException $e) {
                    $checkout_error = 'Erreur : ' . $e->getMessage();
                    error_log($e->getMessage());
                }
            }
        }
        break;
        
    case 'admin':
        require_once APP_PATH . '/Controllers/AdminController.php';
        
        $adminController = new AdminController($pdo);
        
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
        session_destroy();
        header('Location: ' . BASE_URL . '/home');
        exit;
        break;
        
    default:
        $title = 'Page introuvable - TechStore';
        $view = VIEW_PATH . '/404.php';
        break;
}

if (!file_exists($view)) {
    $view = VIEW_PATH . '/404.php';
    $title = 'Page introuvable - TechStore';
}

require_once VIEW_PATH . '/layout/header.php';
require_once $view;
require_once VIEW_PATH . '/layout/footer.php';

// Envoyer le buffer au navigateur
ob_end_flush();
