<?php
/**
 * TECHSTORE - Base Controller
 * Classe de base pour tous les contrôleurs
 */

class Controller {
    protected $pdo;
    protected $viewPath;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->viewPath = VIEW_PATH;
    }
    
    /**
     * Méthode pour render une vue
     */
    protected function render($view, $data = []) {
        extract($data);
        $viewPath = $this->viewPath . $view;
        
        if (file_exists($viewPath)) {
            // Pour les vues admin, on ne inclut pas le layout
            if (strpos($view, '/back/') === 0) {
                require_once $viewPath;
            } else {
                // Pour les vues normales, on utilise le layout
                require_once VIEW_PATH . '/layout/header.php';
                require_once $viewPath;
                require_once VIEW_PATH . '/layout/footer.php';
            }
        } else {
            $this->notFound();
        }
    }
    
    /**
     * Méthode pour afficher une erreur 404
     */
    protected function notFound() {
        header("HTTP/1.0 404 Not Found");
        require_once VIEW_PATH . '/404.php';
        exit;
    }
    
    /**
     * Méthode pour rediriger
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    /**
     * Méthode pour vérifier si l'utilisateur est admin
     */
    protected function requireAdmin() {
        $userRole = $_SESSION['user']['role'] ?? '';
        if (!isset($_SESSION['user']) || ($userRole !== 'admin' && $userRole !== 'super_admin')) {
            $this->redirect('/home');
        }
    }
    
    /**
     * Méthode pour obtenir une instance PDO
     */
    protected function getPdo() {
        return $this->pdo;
    }
}
