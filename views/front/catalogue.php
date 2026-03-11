<?php
// Récupérer les produits depuis la base de données
$products = [];
$categories = [];
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    // Récupérer les catégories
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE is_active = 1");
    $stmt->execute();
    $categories = $stmt->fetchAll();
    
    // Récupérer les produits avec filtre de catégorie ou recherche si présent
    $category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    
    if (!empty($search_query)) {
        // Recherche par nom ou description
        $search_term = '%' . $search_query . '%';
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                                LEFT JOIN categories c ON p.category_id = c.id 
                                WHERE p.is_active = 1 AND (p.name LIKE ? OR p.description LIKE ?) 
                                ORDER BY p.created_at DESC");
        $stmt->execute([$search_term, $search_term]);
    } elseif ($category_id > 0) {
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                                LEFT JOIN categories c ON p.category_id = c.id 
                                WHERE p.is_active = 1 AND p.category_id = ? 
                                ORDER BY p.created_at DESC");
        $stmt->execute([$category_id]);
    } else {
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                                LEFT JOIN categories c ON p.category_id = c.id 
                                WHERE p.is_active = 1 
                                ORDER BY p.created_at DESC");
        $stmt->execute();
    }
    
    $products = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log($e->getMessage());
}
?>

<div class="catalogue-page py-5">
    <div class="container">
        <?php if (!empty($search_query)): ?>
            <div class="mb-4">
                <h4 class="fw-bold">Résultats de recherche pour: "<?= htmlspecialchars($search_query) ?>"</h4>
                <a href="<?= BASE_URL ?>/catalogue" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-circle"></i> Effacer la recherche
                </a>
            </div>
        <?php else: ?>
            <h1 class="mb-4 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i>Notre Catalogue</h1>
        <?php endif; ?>
        
        <!-- Filtres par catégorie -->
        <?php if (!empty($categories)): ?>
        <div class="category-filters mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= BASE_URL ?>/catalogue" class="btn <?= !isset($_GET['category']) ? 'btn-primary' : 'btn-outline-primary' ?>">
                    Tous
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= BASE_URL ?>/catalogue?category=<?= $cat['id'] ?>" 
                       class="btn <?= isset($_GET['category']) && $_GET['category'] == $cat['id'] ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Message si aucun produit -->
        <?php if (empty($products)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Aucun produit disponible pour le moment.
            </div>
        <?php else: ?>
            <!-- Grille de produits -->
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100 shadow-sm <?= $product['stock'] == 0 ? 'opacity-75' : '' ?>">
                            <?php if ($product['is_promotion'] && $product['discount'] > 0): ?>
                                <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small">
                                    -<?= $product['discount'] ?>%
                                </div>
                            <?php elseif ($product['stock'] == 0): ?>
                                <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small">
                                    Rupture
                                </div>
                            <?php elseif ($product['is_featured']): ?>
                                <div class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 m-2 rounded small">
                                    Vedette
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-image-wrapper">
                                <?php if (!empty($product['image'])): ?>
                                    <?php 
                                    // Vérifier si c'est une URL externe ou locale
                                    $imgSrc = $product['image'];
                                    if (strpos($imgSrc, 'http') !== 0) {
                                        $imgSrc = UPLOAD_URL . '/' . $imgSrc;
                                    }
                                    ?>
                                    <a href="<?= BASE_URL ?>/product/<?= $product['id'] ?>">
                                        <img src="<?= htmlspecialchars($imgSrc) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                    </a>
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <a href="<?= BASE_URL ?>/product/<?= $product['id'] ?>" class="text-decoration-none">
                                    <span class="text-muted small mb-1 d-block"><?= htmlspecialchars($product['category_name'] ?? 'Non catégorisé') ?></span>
                                    <h5 class="card-title mb-2 text-dark"><?= htmlspecialchars($product['name']) ?></h5>
                                    <p class="card-text text-muted small flex-grow-1"><?= substr(htmlspecialchars($product['description'] ?? ''), 0, 80) ?>...</p>
                                </a>
                                
                                <div class="mb-2">
                                    <?php if ($product['stock'] > 0 && $product['stock'] <= 5): ?>
                                        <span class="text-warning small"><i class="bi bi-exclamation-triangle"></i> Plus que <?= $product['stock'] ?> en stock</span>
                                    <?php elseif ($product['stock'] == 0): ?>
                                        <span class="text-danger small"><i class="bi bi-x-circle"></i> Indisponible</span>
                                    <?php else: ?>
                                        <span class="text-success small"><i class="bi bi-check-circle"></i> En stock</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div>
                                        <?php if ($product['is_promotion'] && $product['old_price'] > 0): ?>
                                            <span class="text-muted text-decoration-line-through small d-block"><?= number_format($product['old_price'], 2, ',', ' ') ?> FC</span>
                                            <span class="h5 text-danger mb-0"><?= displayPrice($product['price']) ?></span>
                                        <?php else: ?>
                                            <span class="h5 text-primary mb-0"><?= displayPrice($product['price']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL ?>/product/<?= $product['id'] ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>
                                        </a>
                                        <?php if ($product['stock'] > 0): ?>
                                            <button class="btn <?= $product['is_promotion'] ? 'btn-danger' : 'btn-primary' ?> btn-sm add-to-cart" 
                                                    data-id="<?= $product['id'] ?>"
                                                    data-name="<?= htmlspecialchars($product['name']) ?>"
                                                    data-price="<?= $product['price'] ?>"
                                                    data-stock="<?= $product['stock'] ?>"
                                                    data-is-promotion="<?= $product['is_promotion'] ?>"
                                                    data-discount="<?= $product['discount'] ?>">
                                                <i class="bi bi-cart-plus me-1"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="bi bi-cart-x me-1"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.catalogue-page {
    background-color: #f8f9fa;
    min-height: calc(100vh - 76px);
    padding: 1rem !important;
}

.product-card {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.product-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0d6efd, #6c757d);
    transform: scaleX(0);
    transition: transform 0.3s ease;
    transform-origin: left;
}

.product-card:hover::before {
    transform: scaleX(1);
}

.product-image-wrapper {
    height: 180px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px 16px 0 0;
    position: relative;
}

.product-image-wrapper::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to top, rgba(255,255,255,0.8), transparent);
    pointer-events: none;
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.5s ease;
}

.product-card:hover .product-image-wrapper img {
    transform: scale(1.15);
}

.product-image-wrapper a {
    display: block;
    width: 100%;
    height: 100%;
}

/* Badges premium */
.product-card .badge {
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 25px;
    font-size: 11px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

.bg-danger {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800) !important;
}

.bg-success {
    background: linear-gradient(135deg, #28a745, #218838) !important;
}

/* Card body premium */
.product-card .card-body {
    padding: 1.25rem;
    background: #fff;
    border-radius: 0 0 20px 20px;
}

.product-card .category-tag {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #6c757d;
    margin-bottom: 8px;
    display: block;
}

.product-card .card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1a1d20;
    margin-bottom: 10px;
    line-height: 1.4;
    transition: color 0.3s ease;
}

.product-card:hover .card-title {
    color: #0d6efd;
}

.product-card .description {
    font-size: 13px;
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 15px;
}

/* Stock status premium */
.stock-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 20px;
    background: #f8f9fa;
}

.stock-status.stock-low {
    background: #fff3cd;
    color: #856404;
}

.stock-status.stock-out {
    background: #f8d7da;
    color: #721c24;
}

.stock-status.stock-available {
    background: #d4edda;
    color: #155724;
}

/* Price premium */
.product-card .price {
    font-size: 1.25rem;
    font-weight: 800;
    background: linear-gradient(135deg, #0d6efd, #6c757d);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.product-card .price.sale {
    background: linear-gradient(135deg, #dc3545, #c82333);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.product-card .old-price {
    font-size: 0.85rem;
    color: #adb5bd;
    text-decoration: line-through;
}

/* Buttons premium */
.product-card .btn {
    border-radius: 12px;
    padding: 10px 16px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.product-card .btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.product-card .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
}

.product-card .btn-outline-primary {
    border: 2px solid #0d6efd;
    color: #0d6efd;
    background: transparent;
}

.product-card .btn-outline-primary:hover {
    background: #0d6efd;
    color: #fff;
    transform: translateY(-2px);
}

.product-card .btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.product-card .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .catalogue-page {
        padding: 0.5rem !important;
    }
    
    .catalogue-page h1 {
        font-size: 1.5rem;
    }
    
    .category-filters .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .product-image-wrapper {
        height: 140px;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .card-title {
        font-size: 0.9rem !important;
    }
    
    .product-card .card-body .text-muted {
        display: none;
    }
    
    .h5, h5 {
        font-size: 1rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
