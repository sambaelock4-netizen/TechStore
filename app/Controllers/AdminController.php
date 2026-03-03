<?php
/**
 * TECHSTORE - Contrôleur Admin
 * Gestion du Back Office
 */

class AdminController {
    private $pdo;
    private $currentUser;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkAuth();
    }
    
    /**
     * Vérifier l'authentification admin
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            $this->redirectToLogin();
        }
        
        // Vérifier que l'utilisateur est admin
        if (!in_array($_SESSION['user_role'], ['admin', 'super_admin', 'product_manager', 'order_manager'])) {
            $this->redirectToLogin();
        }
        
        // Récupérer les infos de l'admin
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $this->currentUser = $stmt->fetch();
        
        if (!$this->currentUser) {
            $this->redirectToLogin();
        }
    }
    
    /**
     * Rediriger vers la page de login
     */
    private function redirectToLogin() {
        header('Location: ' . BASE_URL . '/login?redirect=admin');
        exit;
    }
    
    /**
     * Vérifier si l'utilisateur a une permission
     */
    public function hasPermission($permission) {
        // Super admin a tous les droits
        if ($this->currentUser['role'] === 'super_admin') {
            return true;
        }
        
        // Vérifier les permissions dans le JSON
        if ($this->currentUser['permissions']) {
            $permissions = json_decode($this->currentUser['permissions'], true);
            return isset($permissions[$permission]);
        }
        
        return false;
    }
    
    /**
     * Dashboard principal
     */
    public function index() {
        $stats = $this->getDashboardStats();
        
        $data = [
            'title' => 'Dashboard Admin - TechStore',
            'currentUser' => $this->currentUser,
            'stats' => $stats
        ];
        
        return $this->render('dashboard', $data);
    }
    
    /**
     * Obtenir les statistiques du dashboard
     */
    private function getDashboardStats() {
        $stats = [];
        
        // Nombre de produits
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM products");
        $stats['products'] = $stmt->fetch()['count'];
        
        // Nombre de commandes
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM orders");
        $stats['orders'] = $stmt->fetch()['count'];
        
        // Nombre de clients
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'client'");
        $stats['clients'] = $stmt->fetch()['count'];
        
        // Commandes en attente
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'en_attente'");
        $stats['pending_orders'] = $stmt->fetch()['count'];
        
        // Chiffre d'affaires total
        $stmt = $this->pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'annule'");
        $stats['revenue'] = $stmt->fetch()['total'] ?? 0;
        
        // Dernières commandes
        $stmt = $this->pdo->query("SELECT o.*, u.firstname, u.lastname, u.email 
                                     FROM orders o 
                                     LEFT JOIN users u ON o.user_id = u.id 
                                     ORDER BY o.created_at DESC LIMIT 5");
        $stats['recent_orders'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Liste des produits
     */
    public function products() {
        if (!$this->hasPermission('products')) {
            $this->redirectToLogin();
        }
        
        $stmt = $this->pdo->query("SELECT p.*, c.name as category_name 
                                     FROM products p 
                                     LEFT JOIN categories c ON p.category_id = c.id 
                                     ORDER BY p.created_at DESC");
        $products = $stmt->fetchAll();
        
        $data = [
            'title' => 'Gestion des produits - TechStore',
            'products' => $products,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('products', $data);
    }
    
    /**
     * Ajouter un produit
     */
    public function addProduct() {
        if (!$this->hasPermission('products') || !$this->hasPermission('products_write')) {
            $this->redirectToLogin();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $short_description = $_POST['short_description'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? null);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $stmt = $this->pdo->prepare("INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $description, $short_description, $price, $stock, $category_id, $is_featured, $is_active]);
            
            $this->logActivity('add_product', 'product', $this->pdo->lastInsertId(), ['name' => $name]);
            
            header('Location: ' . BASE_URL . '/admin/products?success=added');
            exit;
        }
        
        // Récupérer les catégories
        $stmt = $this->pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name");
        $categories = $stmt->fetchAll();
        
        $data = [
            'title' => 'Ajouter un produit - TechStore',
            'categories' => $categories,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('product_form', $data);
    }
    
    /**
     * Modifier un produit
     */
    public function editProduct($id) {
        if (!$this->hasPermission('products') || !$this->hasPermission('products_write')) {
            $this->redirectToLogin();
        }
        
        // Récupérer le produit
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            header('Location: ' . BASE_URL . '/admin/products?error=not_found');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $short_description = $_POST['short_description'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? null);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $stmt = $this->pdo->prepare("UPDATE products SET name = ?, slug = ?, description = ?, short_description = ?, price = ?, stock = ?, category_id = ?, is_featured = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $description, $short_description, $price, $stock, $category_id, $is_featured, $is_active, $id]);
            
            $this->logActivity('edit_product', 'product', $id, ['name' => $name]);
            
            header('Location: ' . BASE_URL . '/admin/products?success=updated');
            exit;
        }
        
        // Récupérer les catégories
        $stmt = $this->pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name");
        $categories = $stmt->fetchAll();
        
        $data = [
            'title' => 'Modifier le produit - TechStore',
            'product' => $product,
            'categories' => $categories,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('product_form', $data);
    }
    
    /**
     * Supprimer un produit
     */
    public function deleteProduct($id) {
        if (!$this->hasPermission('products') || !$this->hasPermission('products_delete')) {
            $this->redirectToLogin();
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        $this->logActivity('delete_product', 'product', $id);
        
        header('Location: ' . BASE_URL . '/admin/products?success=deleted');
        exit;
    }
    
    /**
     * Liste des commandes
     */
    public function orders() {
        if (!$this->hasPermission('orders')) {
            $this->redirectToLogin();
        }
        
        $stmt = $this->pdo->query("SELECT o.*, u.firstname, u.lastname, u.email 
                                     FROM orders o 
                                     LEFT JOIN users u ON o.user_id = u.id 
                                     ORDER BY o.created_at DESC");
        $orders = $stmt->fetchAll();
        
        $data = [
            'title' => 'Gestion des commandes - TechStore',
            'orders' => $orders,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('orders', $data);
    }
    
    /**
     * Détails d'une commande
     */
    public function viewOrder($id) {
        if (!$this->hasPermission('orders')) {
            $this->redirectToLogin();
        }
        
        // Récupérer la commande
        $stmt = $this->pdo->prepare("SELECT o.*, u.firstname, u.lastname, u.email, u.phone, u.address, u.city, u.postal_code 
                                     FROM orders o 
                                     LEFT JOIN users u ON o.user_id = u.id 
                                     WHERE o.id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        
        if (!$order) {
            header('Location: ' . BASE_URL . '/admin/orders?error=not_found');
            exit;
        }
        
        // Récupérer les articles
        $stmt = $this->pdo->prepare("SELECT oi.*, p.name, p.image 
                                     FROM order_items oi 
                                     LEFT JOIN products p ON oi.product_id = p.id 
                                     WHERE oi.order_id = ?");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();
        
        $data = [
            'title' => 'Commande #' . $id . ' - TechStore',
            'order' => $order,
            'items' => $items,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('order_view', $data);
    }
    
    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateOrderStatus($id) {
        if (!$this->hasPermission('orders') || !$this->hasPermission('orders_write')) {
            $this->redirectToLogin();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            $validStatuses = ['en_attente', 'confirme', 'en_preparation', 'expedie', 'livre', 'annule'];
            
            if (in_array($status, $validStatuses)) {
                $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                $stmt->execute([$status, $id]);
                
                $this->logActivity('update_order_status', 'order', $id, ['status' => $status]);
            }
        }
        
        header('Location: ' . BASE_URL . '/admin/orders/view/' . $id);
        exit;
    }
    
    /**
     * Liste des utilisateurs
     */
    public function users() {
        if (!$this->hasPermission('users')) {
            $this->redirectToLogin();
        }
        
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();
        
        $data = [
            'title' => 'Gestion des utilisateurs - TechStore',
            'users' => $users,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('users', $data);
    }
    
    /**
     * Ajouter un utilisateur
     */
    public function addUser() {
        if (!$this->hasPermission('users') || !$this->hasPermission('users_write')) {
            $this->redirectToLogin();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'client';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $postal_code = $_POST['postal_code'] ?? '';
            
            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $this->pdo->prepare("INSERT INTO users (firstname, lastname, email, password, phone, address, city, postal_code, role) 
                                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$firstname, $lastname, $email, $hashedPassword, $phone, $address, $city, $postal_code, $role]);
                
                $this->logActivity('add_user', 'user', $this->pdo->lastInsertId(), ['email' => $email]);
                
                header('Location: ' . BASE_URL . '/admin/users?success=added');
                exit;
            } catch (PDOException $e) {
                $error = "L'email existe déjà";
            }
        }
        
        $data = [
            'title' => 'Ajouter un utilisateur - TechStore',
            'currentUser' => $this->currentUser,
            'error' => $error ?? null
        ];
        
        return $this->render('user_form', $data);
    }
    
    /**
     * Modifier un utilisateur
     */
    public function editUser($id) {
        if (!$this->hasPermission('users') || !$this->hasPermission('users_write')) {
            $this->redirectToLogin();
        }
        
        // Récupérer l'utilisateur
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            header('Location: ' . BASE_URL . '/admin/users?error=not_found');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'client';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $postal_code = $_POST['postal_code'] ?? '';
            
            // Mettre à jour le mot de passe si fourni
            if (!empty($_POST['password'])) {
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ?, phone = ?, address = ?, city = ?, postal_code = ?, role = ? WHERE id = ?");
                $stmt->execute([$firstname, $lastname, $email, $hashedPassword, $phone, $address, $city, $postal_code, $role, $id]);
            } else {
                $stmt = $this->pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, address = ?, city = ?, postal_code = ?, role = ? WHERE id = ?");
                $stmt->execute([$firstname, $lastname, $email, $phone, $address, $city, $postal_code, $role, $id]);
            }
            
            $this->logActivity('edit_user', 'user', $id, ['email' => $email]);
            
            header('Location: ' . BASE_URL . '/admin/users?success=updated');
            exit;
        }
        
        $data = [
            'title' => 'Modifier l\'utilisateur - TechStore',
            'user' => $user,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('user_form', $data);
    }
    
    /**
     * Supprimer un utilisateur
     */
    public function deleteUser($id) {
        if (!$this->hasPermission('users') || !$this->hasPermission('users_delete')) {
            $this->redirectToLogin();
        }
        
        // Ne pas supprimer son propre compte
        if ($id == $this->currentUser['id']) {
            header('Location: ' . BASE_URL . '/admin/users?error=cannot_delete_self');
            exit;
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        $this->logActivity('delete_user', 'user', $id);
        
        header('Location: ' . BASE_URL . '/admin/users?success=deleted');
        exit;
    }
    
    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetUserPassword($id) {
        if (!$this->hasPermission('users') || !$this->hasPermission('users_write')) {
            $this->redirectToLogin();
        }
        
        // Générer un nouveau mot de passe aléatoire
        $newPassword = bin2hex(random_bytes(8));
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $id]);
        
        // TODO: Envoyer l'email avec le nouveau mot de passe
        
        $this->logActivity('reset_password', 'user', $id);
        
        header('Location: ' . BASE_URL . '/admin/users?success=password_reset&temp_password=' . $newPassword);
        exit;
    }
    
    /**
     * Catégories
     */
    public function categories() {
        if (!$this->hasPermission('categories')) {
            $this->redirectToLogin();
        }
        
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name");
        $categories = $stmt->fetchAll();
        
        $data = [
            'title' => 'Gestion des catégories - TechStore',
            'categories' => $categories,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('categories', $data);
    }
    
    /**
     * Ajouter une catégorie
     */
    public function addCategory() {
        if (!$this->hasPermission('categories') || !$this->hasPermission('categories_write')) {
            $this->redirectToLogin();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $stmt = $this->pdo->prepare("INSERT INTO categories (name, slug, description, is_active) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $description, $is_active]);
            
            $this->logActivity('add_category', 'category', $this->pdo->lastInsertId(), ['name' => $name]);
            
            header('Location: ' . BASE_URL . '/admin/categories?success=added');
            exit;
        }
        
        $data = [
            'title' => 'Ajouter une catégorie - TechStore',
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('category_form', $data);
    }
    
    /**
     * Modifier une catégorie
     */
    public function editCategory($id) {
        if (!$this->hasPermission('categories') || !$this->hasPermission('categories_write')) {
            $this->redirectToLogin();
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch();
        
        if (!$category) {
            header('Location: ' . BASE_URL . '/admin/categories?error=not_found');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = $this->createSlug($name);
            $description = $_POST['description'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $description, $is_active, $id]);
            
            $this->logActivity('edit_category', 'category', $id, ['name' => $name]);
            
            header('Location: ' . BASE_URL . '/admin/categories?success=updated');
            exit;
        }
        
        $data = [
            'title' => 'Modifier la catégorie - TechStore',
            'category' => $category,
            'currentUser' => $this->currentUser
        ];
        
        return $this->render('category_form', $data);
    }
    
    /**
     * Supprimer une catégorie
     */
    public function deleteCategory($id) {
        if (!$this->hasPermission('categories') || !$this->hasPermission('categories_delete')) {
            $this->redirectToLogin();
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        
        $this->logActivity('delete_category', 'category', $id);
        
        header('Location: ' . BASE_URL . '/admin/categories?success=deleted');
        exit;
    }
    
    /**
     * Journal d'activité
     */
    public function logs() {
        if ($this->currentUser['role'] !== 'super_admin') {
            $this->redirectToLogin();
        }
        
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->pdo->prepare("SELECT al.*, u.firstname, u.lastname, u.email 
                                     FROM admin_logs al 
                                     LEFT JOIN users u ON al.user_id = u.id 
                                     ORDER BY al.created_at DESC 
                                     LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        $logs = $stmt->fetchAll();
        
        // Compter le total
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM admin_logs");
        $total = $stmt->fetch()['total'];
        $totalPages = ceil($total / $limit);
        
        $data = [
            'title' => 'Journal d\'activité - TechStore',
            'logs' => $logs,
            'currentUser' => $this->currentUser,
            'page' => $page,
            'totalPages' => $totalPages
        ];
        
        return $this->render('logs', $data);
    }
    
    /**
     * Profil admin
     */
    public function profile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            // Mettre à jour le mot de passe si fourni
            if (!empty($_POST['new_password'])) {
                // Vérifier l'ancien mot de passe
                $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$this->currentUser['id']]);
                $currentPassword = $stmt->fetch()['password'];
                
                if (password_verify($_POST['current_password'], $currentPassword)) {
                    $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashedPassword, $this->currentUser['id']]);
                } else {
                    $error = "Mot de passe actuel incorrect";
                }
            }
            
            if (!isset($error)) {
                $stmt = $this->pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE id = ?");
                $stmt->execute([$firstname, $lastname, $email, $phone, $this->currentUser['id']]);
                
                $this->logActivity('update_profile', 'user', $this->currentUser['id']);
                
                header('Location: ' . BASE_URL . '/admin/profile?success=updated');
                exit;
            }
        }
        
        $data = [
            'title' => 'Mon profil - TechStore',
            'currentUser' => $this->currentUser,
            'error' => $error ?? null
        ];
        
        return $this->render('profile', $data);
    }
    
    /**
     * Créer un slug URL
     */
    private function createSlug($text) {
        // Remplacer les caractères spéciaux
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
    
    /**
     * Logger une activité
     */
    private function logActivity($action, $entityType = null, $entityId = null, $details = null) {
        $stmt = $this->pdo->prepare("INSERT INTO admin_logs (user_id, action, entity_type, entity_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->currentUser['id'],
            $action,
            $entityType,
            $entityId,
            $details ? json_encode($details) : null,
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }
    
    /**
     * Rendre une vue
     */
    private function render($view, $data) {
        extract($data);
        
        $viewFile = VIEW_PATH . '/back/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            die('Vue non trouvée: ' . $view);
        }
        
        require VIEW_PATH . '/back/header.php';
        require $viewFile;
        require VIEW_PATH . '/back/footer.php';
    }
}
