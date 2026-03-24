<?php
/**
 * ==================================================================================
 * TechStore — API Recherche Produits (Autocomplete / Temps réel)
 * ==================================================================================
 * Ce script est appelé via AJAX (JavaScript) pour fournir des suggestions 
 * de produits en temps réel au fur et à mesure que l'utilisateur tape.
 * 
 * Paramètre attendu (GET) :
 * - q : La chaîne de recherche (min. 2 caractères)
 * 
 * Retourne :
 * - Un tableau JSON contenant les produits correspondants (id, name, slug, price...).
 * ==================================================================================
 */

// Définition des headers pour le format JSON et les permissions CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Chargement des dépendances
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/db.php';

// Récupération et nettoyage du terme de recherche
$q = trim($_GET['q'] ?? '');

// Sécurité : Ne pas lancer de recherche si moins de 2 caractères
if (strlen($q) < 2) { 
    echo json_encode([]); 
    exit; 
}

try {
    /** 
     * REQUETE DE RECHERCHE
     * On recherche dans le nom du produit OU le nom de la catégorie.
     * On trie en priorité par les noms commençant par le terme (Exact Match first).
     */
    $s = $pdo->prepare("
        SELECT p.id, p.name, p.slug, p.price, p.old_price, p.discount, p.is_promotion, p.image, p.stock,
               c.name AS cat
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1
          AND (p.name LIKE :q1 OR c.name LIKE :q2)
        ORDER BY
          CASE WHEN p.name LIKE :q3 THEN 0 ELSE 1 END,
          p.is_featured DESC, p.created_at DESC
        LIMIT 6
    ");
    
    $like = '%' . $q . '%';
    $s->execute([':q1' => $like, ':q2' => $like, ':q3' => $q . '%']);
    $results = $s->fetchAll(PDO::FETCH_ASSOC);

    /**
     * POST-TRAITEMENT DES RÉSULTATS
     * 1. Génération des URLs d'images complètes via la constante UPLOAD_URL
     * 2. Formatage des prix pour l'affichage (Conversion CFA si nécessaire)
     */
    foreach ($results as &$r) {
        if (!empty($r['image'])) {
            // Si l'image est un lien externe (http), on le garde tel quel
            if (strpos($r['image'], 'http') !== 0) {
                $r['image_url'] = UPLOAD_URL . '/' . $r['image'];
            } else {
                $r['image_url'] = $r['image'];
            }
        } else {
            // Image par défaut si aucune image n'est définie
            $r['image_url'] = 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=200&q=60';
        }
        
        // Formatage du prix pour le composant d'autocomplete
        $r['price_display'] = displayPrice($r['price']);
        
        if ($r['is_promotion'] && $r['old_price'] > 0) {
            $r['old_price_display'] = number_format($r['old_price'] * EUR_TO_CFA, 0, ',', ' ') . ' FC';
        }
    }
    
    // Envoi des résultats au client JavaScript
    echo json_encode($results);
    
} catch (PDOException $e) {
    // Log d'erreur discret pour l'API
    echo json_encode(['error' => 'Erreur de recherche système']);
}
