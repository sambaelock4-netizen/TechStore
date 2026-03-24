<?php
/**
 * ==================================================================================
 * TechStore - Contrôleur de Base (Base Controller)
 * ==================================================================================
 * Cette classe sert d'ossature à tous les contrôleurs de l'application.
 * Elle fournit des outils communs pour :
 * 1. La gestion de la connexion à la base de données (PDO)
 * 2. Le rendu des vues (avec ou sans layout)
 * 3. La gestion des erreurs (404)
 * 4. La redirection et la sécurité (vérification des privilèges admin)
 * ==================================================================================
 */

class Controller {
    /** @var PDO Instance de connexion à la base de données */
    protected $pdo;
    
    /** @var string Chemin vers le dossier des vues */
    protected $viewPath;
    
    /**
     * Constructeur du contrôleur
     * 
     * @param PDO $pdo L'instance PDO injectée
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->viewPath = VIEW_PATH;
    }
    
    /**
     * Rendre une vue (View Engine simplifié)
     * Cette méthode extrait les données et inclut le fichier PHP de la vue.
     * 
     * @param string $view Le chemin relatif du fichier (ex: '/front/home.php')
     * @param array $data Tableau associatif de données passées à la vue
     */
    protected function render($view, $data = []) {
        // Injection automatique de l'objet PDO dans les données de vue
        $data['pdo'] = $this->pdo;
        
        // Transforme les clés du tableau en variables (ex: $data['name'] -> $name)
        extract($data);
        
        $viewFullFile = $this->viewPath . $view;
        
        if (file_exists($viewFullFile)) {
            // LOGIQUE DE LAYOUT :
            // Si la vue appartient à l'administration ('/back/'), on l'affiche brute
            // Sinon (front-office), on l'entoure du Header et Footer communs
            if (strpos($view, '/back/') === 0) {
                require_once $viewFullFile;
            } else {
                require_once VIEW_PATH . '/layout/header.php';
                require_once $viewFullFile;
                require_once VIEW_PATH . '/layout/footer.php';
            }
        } else {
            // Si le fichier de vue n'existe pas, on lance une erreur 404
            $this->notFound();
        }
    }
    
    /**
     * Envoie un header HTTP 404 et affiche la page d'erreur
     */
    protected function notFound() {
        header("HTTP/1.0 404 Not Found");
        require_once VIEW_PATH . '/404.php';
        exit;
    }
    
    /**
     * Redirige l'utilisateur vers une URL relative
     * 
     * @param string $url L'URL de destination (ex: '/home')
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    /**
     * Middleware de sécurité simple pour l'accès administrateur.
     * Si l'utilisateur n'est pas admin, il est renvoyé vers l'accueil.
     */
    protected function requireAdmin() {
        $userRole = $_SESSION['user']['role'] ?? '';
        if (!isset($_SESSION['user']) || ($userRole !== 'admin' && $userRole !== 'super_admin')) {
            $this->redirect('/home');
        }
    }
    
    /**
     * Getter pour l'instance PDO
     * 
     * @return PDO
     */
    protected function getPdo() {
        return $this->pdo;
    }
}
