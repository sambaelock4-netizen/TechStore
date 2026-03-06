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
            // Get stats from existing database
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
            
            // Get recent orders
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
            // If there's an error, show a simple dashboard
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
            
            // Gérer l'upload de l'image
            $image = $this->handleImageUpload($_FILES['image'] ?? null);
            
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
            
            // Gérer l'upload de l'image
            $image = $product['image']; // Garder l'image actuelle par défaut
            
            if (isset($_POST['delete_image']) && $_POST['delete_image'] == 1) {
                // Supprimer l'image
                if (!empty($product['image'])) {
                    $this->deleteImage($product['image']);
                }
                $image = null;
            } elseif (!empty($_FILES['image']['name'])) {
                // Nouvelle image uploadée
                $newImage = $this->handleImageUpload($_FILES['image'] ?? null);
                if ($newImage) {
                    // Supprimer l'ancienne image
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
        // Récupérer l'image avant suppression
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
            SELECT oi.*, p.name as product_name, p.image as product_image
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
        
        // Produits en faible stock
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
        // Récupérer les produits en promotion
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
            // Simple promotion creation - redirects to promotions list
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
    
    // ==================== STOCK ====================
    
    public function stockMovements() {
        $this->render('/back/stock_movements.php', [
            'movements' => []
        ]);
    }
    
    // ==================== EXPORT ====================
    
    public function exportData() {
        // Simple export functionality
        $format = $_GET['format'] ?? 'csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Prix', 'Stock']);
        
        try {
            $stmt = $this->pdo->query("SELECT id, name, price, stock FROM products");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($output, $row);
            }
        } catch (Exception $e) {}
        
        fclose($output);
        exit;
    }
    
    // ==================== STATISTIQUES ====================
    
    public function statistics() {
        $stats = [
            'total_revenue' => 0,
            'total_orders' => 0,
            'new_customers' => 0,
            'avg_order_value' => 0,
            'revenue_change' => 0,
            'orders_change' => 0,
            'customers_change' => 0,
            'avg_change' => 0
        ];
        
        try {
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'annule'");
            $stats['total_revenue'] = $stmt ? $stmt->fetchColumn() : 0;
        } catch (Exception $e) {}
        
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM orders WHERE status != 'annule'");
            $stats['total_orders'] = $stmt ? $stmt->fetchColumn() : 0;
        } catch (Exception $e) {}
        
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'");
            $stats['new_customers'] = $stmt ? $stmt->fetchColumn() : 0;
        } catch (Exception $e) {}
        
        if ($stats['total_orders'] > 0) {
            $stats['avg_order_value'] = $stats['total_revenue'] / $stats['total_orders'];
        }
        
        $this->render('/back/statistics.php', [
            'stats' => $stats,
            'topProducts' => [],
            'topCustomers' => [],
            'salesLabels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            'salesData' => [0, 0, 0, 0, 0, 0, 0],
            'categoryLabels' => ['Catégorie A', 'Catégorie B'],
            'categoryData' => [1, 1],
            'period' => 'month'
        ]);
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
    
    /**
     * Gère l'upload d'une image
     * @param array|null $file Le fichier uploadé ($_FILES['image'])
     * @return string|null Le nom du fichier image ou null si erreur
     */
    private function handleImageUpload($file) {
        if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            error_log("Image upload error: file empty or error code " . ($file['error'] ?? 'no file'));
            return null;
        }
        
        // Vérifications de sécurité
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            error_log("Image upload error: invalid file type " . $fileType);
            return null;
        }
        
        // Taille maximale 2MB
        if ($file['size'] > 2 * 1024 * 1024) {
            error_log("Image upload error: file too large " . $file['size']);
            return null;
        }
        
        // Créer le dossier uploads si nécessaire
        $uploadDir = UPLOAD_PATH;
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                error_log("Image upload error: cannot create upload directory");
                return null;
            }
        }
        
        // Vérifier si le dossier est accessible en écriture
        if (!is_writable($uploadDir)) {
            error_log("Image upload error: upload directory is not writable");
            // Essayer avec des permissions plus larges
            chmod($uploadDir, 0777);
        }
        
        // Générer un nom de fichier unique
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('product_') . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . '/' . $filename;
        
        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            error_log("Image uploaded successfully: " . $filename);
            return $filename;
        }
        
        // Si move_uploaded_file échoue (peut-être pas en environnement HTTP), essayer copy
        if (copy($file['tmp_name'], $targetPath)) {
            error_log("Image copied successfully: " . $filename);
            return $filename;
        }
        
        error_log("Image upload error: cannot move or copy file to " . $targetPath);
        return null;
    }
    
    /**
     * Supprime une image du serveur
     * @param string $filename Le nom du fichier à supprimer
     */
    private function deleteImage($filename) {
        if (empty($filename)) {
            return;
        }
        
        $filePath = UPLOAD_PATH . '/' . $filename;
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
