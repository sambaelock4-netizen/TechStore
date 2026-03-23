<?php
/**
 * TECHSTORE - Admin Controller
 * Contrôleur pour la partie administration
 */

require_once APP_PATH . '/Core/Controller.php';

class AdminController extends Controller {
    
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->requireAdmin();
    }
    
    // ==================== DASHBOARD ====================
    
    public function index() {
        try {
            $totalProducts = 0;
            $totalOrders = 0;
            $totalUsers = 0;
            $totalRevenue = 0;
            
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM products WHERE is_active = 1");
                $totalProducts = $stmt ? $stmt->fetchColumn() : 0;
            } catch (Exception $e) { $totalProducts = 0; }
            
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders");
                $totalOrders = $stmt ? $stmt->fetchColumn() : 0;
            } catch (Exception $e) { $totalOrders = 0; }
            
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'");
                $totalUsers = $stmt ? $stmt->fetchColumn() : 0;
            } catch (Exception $e) { $totalUsers = 0; }
            
            try {
                $stmt = $this->pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'annule'");
                $totalRevenue = $stmt ? $stmt->fetchColumn() : 0;
            } catch (Exception $e) { $totalRevenue = 0; }
            
            $stats = [
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_users' => $totalUsers,
                'total_revenue' => $totalRevenue
            ];
            
            $recentOrders = [];
            try {
                $stmt = $this->pdo->query("
                    SELECT o.*, u.firstname, u.lastname 
                    FROM orders o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC 
                    LIMIT 5
                ");
                $recentOrders = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            } catch (Exception $e) { $recentOrders = []; }
            
            $this->render('/back/index.php', [
                'stats' => $stats,
                'recentOrders' => $recentOrders
            ]);
            
        } catch (Exception $e) {
            $this->render('/back/index.php', [
                'stats' => [
                    'total_products' => 0,
                    'total_orders' => 0,
                    'total_users' => 0,
                    'total_revenue' => 0
                ],
                'recentOrders' => []
            ]);
        }
    }
    
    // ==================== PRODUITS ====================
    
    public function products() {
        try {
            $stmt = $this->pdo->query("
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC
            ");
            $products = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $products = [];
        }
        
        try {
            $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name");
            $categories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $categories = [];
        }
        
        $this->render('/back/products.php', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $short_description = $_POST['short_description'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? 0) ?: null;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 1;
            $is_production = isset($_POST['is_production']) ? 1 : 0;
            
            // Champs promotion
            $is_promotion = isset($_POST['is_promotion']) ? 1 : 0;
            $promotion_price = !empty($_POST['promotion_price']) ? floatval($_POST['promotion_price']) : null;
            $discount = intval($_POST['discount'] ?? 0);
            $promotion_start_date = !empty($_POST['promotion_start_date']) ? $_POST['promotion_start_date'] : null;
            $promotion_end_date = !empty($_POST['promotion_end_date']) ? $_POST['promotion_end_date'] : null;
            
            // Gérer l'image: priorité au champ texte "image_name", sinon upload
            $image = null;
            
            // D'abord vérifier si un nom d'image a été entré manuellement
            if (!empty($_POST['image_name'])) {
                $image = trim($_POST['image_name']);
            }
            // Sinon essayer l'upload de fichier
            elseif (!empty($_FILES['image']['name'])) {
                $image = $this->handleImageUpload($_FILES['image'] ?? null);
            }
            
            $stmt = $this->pdo->prepare("
                INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, is_production, is_promotion, promotion_price, discount, promotion_start_date, promotion_end_date, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([$name, $slug, $description, $short_description, $price, $stock, $category_id, $is_featured, $is_active, $is_production, $is_promotion, $promotion_price, $discount, $promotion_start_date, $promotion_end_date, $image]);
            $this->redirect('/admin/products');
            return;
        }
        
        try {
            $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name");
            $categories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $categories = [];
        }
        
        $this->render('/back/product_form.php', [
            'categories' => $categories,
            'product' => null
        ]);
    }
    
    public function editProduct($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            $this->notFound();
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $short_description = $_POST['short_description'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? 0) ?: null;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 1;
            $is_production = isset($_POST['is_production']) ? 1 : 0;
            
            // Champs promotion
            $is_promotion = isset($_POST['is_promotion']) ? 1 : 0;
            $promotion_price = !empty($_POST['promotion_price']) ? floatval($_POST['promotion_price']) : null;
            $discount = intval($_POST['discount'] ?? 0);
            $promotion_start_date = !empty($_POST['promotion_start_date']) ? $_POST['promotion_start_date'] : null;
            $promotion_end_date = !empty($_POST['promotion_end_date']) ? $_POST['promotion_end_date'] : null;
            
            // Gérer l'image
            $image = $product['image'];
            
            // Supprimer l'image si demandé
            if (isset($_POST['delete_image']) && $_POST['delete_image'] == 1) {
                if (!empty($product['image'])) {
                    $this->deleteImage($product['image']);
                }
                $image = null;
            }
            // Nouveau nom d'image entré manuellement
            elseif (!empty($_POST['image_name'])) {
                $image = trim($_POST['image_name']);
            }
            // Sinon nouvelle image uploadée
            elseif (!empty($_FILES['image']['name'])) {
                $newImage = $this->handleImageUpload($_FILES['image'] ?? null);
                if ($newImage) {
                    if (!empty($product['image'])) {
                        $this->deleteImage($product['image']);
                    }
                    $image = $newImage;
                }
            }
            
            $stmt = $this->pdo->prepare("
                UPDATE products 
                SET name = ?, slug = ?, description = ?, short_description = ?, 
                    price = ?, stock = ?, category_id = ?, is_featured = ?, is_active = ?, is_production = ?,
                    is_promotion = ?, promotion_price = ?, discount = ?, promotion_start_date = ?, promotion_end_date = ?, image = ?
                WHERE id = ?
            ");
            
            $stmt->execute([$name, $slug, $description, $short_description, $price, $stock, $category_id, $is_featured, $is_active, $is_production, $is_promotion, $promotion_price, $discount, $promotion_start_date, $promotion_end_date, $image, $id]);
            $this->redirect('/admin/products');
            return;
        }
        
        try {
            $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name");
            $categories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $categories = [];
        }
        
        $this->render('/back/product_form.php', [
            'categories' => $categories,
            'product' => $product
        ]);
    }
    
    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product && !empty($product['image'])) {
            $this->deleteImage($product['image']);
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('/admin/products');
    }
    
    // ==================== COMMANDES ====================
    
    public function orders() {
        try {
            $stmt = $this->pdo->query("
                SELECT o.*, u.firstname, u.lastname, u.email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC
            ");
            $orders = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $orders = [];
        }
        
        $this->render('/back/orders.php', [
            'orders' => $orders
        ]);
    }
    
    public function viewOrder($id) {
        $stmt = $this->pdo->prepare("
            SELECT o.*, u.firstname, u.lastname, u.email, u.phone, u.address, u.city, u.postal_code
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            $this->notFound();
            return;
        }
        
        $stmt = $this->pdo->prepare("
            SELECT oi.*,
                   oi.unit_price AS price,
                   (oi.unit_price * oi.quantity) AS total,
                   p.name as product_name, p.image as product_image
            FROM order_items oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$id]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->render('/back/order_view.php', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }
    
    public function updateOrderStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
        }
        $this->redirect('/admin/orders/view/' . $id);
    }
    
    // ==================== UTILISATEURS ====================
    
    public function users() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
            $users = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $users = [];
        }
        
        $this->render('/back/users.php', [
            'users' => $users
        ]);
    }
    
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = password_hash($_POST['password'] ?? 'password', PASSWORD_DEFAULT);
            $role = $_POST['role'] ?? 'client';
            
            $stmt = $this->pdo->prepare("
                INSERT INTO users (firstname, lastname, email, password, role)
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([$firstname, $lastname, $email, $password, $role]);
            $this->redirect('/admin/users');
            return;
        }
        
        $this->render('/back/user_form.php', [
            'user' => null
        ]);
    }
    
    public function editUser($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $this->notFound();
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'client';
            
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET firstname = ?, lastname = ?, email = ?, role = ?
                WHERE id = ?
            ");
            
            $stmt->execute([$firstname, $lastname, $email, $role, $id]);
            $this->redirect('/admin/users');
            return;
        }
        
        $this->render('/back/user_form.php', [
            'user' => $user
        ]);
    }
    
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->execute([$id]);
        $this->redirect('/admin/users');
    }
    
    public function resetUserPassword($id) {
        $newPassword = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$newPassword, $id]);
        $this->redirect('/admin/users');
    }
    
    // ==================== CATÉGORIES ====================
    
    public function categories() {
        try {
            $stmt = $this->pdo->query("
                SELECT c.*, 
                       (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count
                FROM categories c
                ORDER BY c.name
            ");
            $categories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $categories = [];
        }
        
        $this->render('/back/categories.php', [
            'categories' => $categories
        ]);
    }
    
    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 1;
            
            $stmt = $this->pdo->prepare("
                INSERT INTO categories (name, slug, description, is_active)
                VALUES (?, ?, ?, ?)
            ");
            
            $stmt->execute([$name, $slug, $description, $is_active]);
            $this->redirect('/admin/categories');
            return;
        }
        
        $this->render('/back/category_form.php', [
            'category' => null
        ]);
    }
    
    public function editCategory($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$category) {
            $this->notFound();
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 1;
            
            $stmt = $this->pdo->prepare("
                UPDATE categories 
                SET name = ?, slug = ?, description = ?, is_active = ?
                WHERE id = ?
            ");
            
            $stmt->execute([$name, $slug, $description, $is_active, $id]);
            $this->redirect('/admin/categories');
            return;
        }
        
        $this->render('/back/category_form.php', [
            'category' => $category
        ]);
    }
    
    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('/admin/categories');
    }
    
    // ==================== STOCK ====================
    
    public function stock() {
        try {
            $stmt = $this->pdo->query("
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.stock ASC
            ");
            $products = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $products = [];
        }
        
        $lowStockProducts = array_filter($products, function($p) {
            return ($p['stock'] ?? 0) <= 5;
        });
        
        $this->render('/back/stock.php', [
            'products' => $products,
            'lowStockProducts' => array_values($lowStockProducts)
        ]);
    }
    
    // ==================== PROMOTIONS ====================
    
    public function promotions() {
        $promotions = [];
        try {
            $stmt = $this->pdo->query("
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_promotion = 1
                ORDER BY p.discount DESC
            ");
            $promotions = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (Exception $e) {
            $promotions = [];
        }
        
        $this->render('/back/promotions.php', [
            'promotions' => $promotions
        ]);
    }
    
    public function addPromotion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->redirect('/admin/promotions');
        }
        $this->render('/back/promotion_form.php', [
            'promotion' => null
        ]);
    }
    
    public function editPromotion($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->redirect('/admin/promotions');
        }
        $this->render('/back/promotion_form.php', [
            'promotion' => ['id' => $id, 'name' => '', 'discount' => 0, 'valid_until' => '']
        ]);
    }
    
    public function deletePromotion($id) {
        $this->redirect('/admin/promotions');
    }
    
    // ==================== PROFIL ====================
    
    public function profile() {
        $user = $_SESSION['user'] ?? ['firstname' => 'Admin', 'lastname' => 'User', 'email' => ''];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET firstname = ?, lastname = ?, email = ?
                WHERE id = ?
            ");
            
            $stmt->execute([$firstname, $lastname, $email, $user['id']]);
            
            $_SESSION['user']['firstname'] = $firstname;
            $_SESSION['user']['lastname'] = $lastname;
            $_SESSION['user']['email'] = $email;
            
            $this->redirect('/admin/profile');
        }
        
        $this->render('/back/profile.php', [
            'user' => $user
        ]);
    }
    
    // ==================== GESTION DES IMAGES ====================
    
    private function handleImageUpload($file)
    {
        if (!isset($file) || $file['error'] != 0) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','gif','webp'];

        if(!in_array($extension,$allowed)){
            return null;
        }

        $filename = time().'_'.uniqid().'.'.$extension;

        $destination = $uploadDir.$filename;

        if(move_uploaded_file($file['tmp_name'],$destination)){
            return $filename;
        }

        return null;
    }
    
    private function deleteImage($filename) {
        if (empty($filename)) {
            return;
        }
        
        $uploadDir = __DIR__ . '/../../public/uploads/';
        $filePath = $uploadDir . $filename;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    // ==================== UTILITAIRE ====================
    
    private function createSlug($str) {
        $slug = strtolower(trim($str));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
