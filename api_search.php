<?php
/**
 * TechStore — API Recherche Produits (autocomplete)
 * GET ?q=... → JSON [{id, name, price, image, cat, slug}]
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/db.php';

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) { echo json_encode([]); exit; }

try {
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

    // Build image URLs
    foreach ($results as &$r) {
        if (!empty($r['image'])) {
            if (strpos($r['image'], 'http') !== 0) {
                $r['image_url'] = UPLOAD_URL . '/' . $r['image'];
            } else {
                $r['image_url'] = $r['image'];
            }
        } else {
            $r['image_url'] = 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=200&q=60';
        }
        $r['price_display'] = displayPrice($r['price']);
        if ($r['is_promotion'] && $r['old_price'] > 0) {
            $r['old_price_display'] = number_format($r['old_price'] * EUR_TO_CFA, 0, ',', ' ') . ' FC';
        }
    }
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de recherche']);
}
