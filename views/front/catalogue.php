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
                            <?php if ($product['stock'] == 0): ?>
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
                                    <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <span class="text-muted small mb-1"><?= htmlspecialchars($product['category_name'] ?? 'Non catégorisé') ?></span>
                                <h5 class="card-title mb-2"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text text-muted small flex-grow-1"><?= substr(htmlspecialchars($product['description'] ?? ''), 0, 80) ?>...</p>
                                
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
                                    <span class="h5 text-primary mb-0"><?= number_format($product['price'], 2, ',', ' ') ?> €</span>
                                    
                                    <?php if ($product['stock'] > 0): ?>
                                        <button class="btn btn-primary btn-sm add-to-cart" 
                                                data-id="<?= $product['id'] ?>"
                                                data-name="<?= htmlspecialchars($product['name']) ?>"
                                                data-price="<?= $product['price'] ?>"
                                                data-stock="<?= $product['stock'] ?>">
                                            <i class="bi bi-cart-plus me-1"></i> Ajouter
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="bi bi-cart-x me-1"></i> Indisponible
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name');
            var price = parseFloat(this.getAttribute('data-price'));
            var stock = parseInt(this.getAttribute('data-stock'));
            
            addToCartItem(id, name, price, stock);
        });
    });
    
    function addToCartItem(id, name, price, stock) {
        var cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        var existingItem = null;
        
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id === id) {
                existingItem = cart[i];
                break;
            }
        }
        
        var currentQty = existingItem ? existingItem.quantity : 0;
        
        if (currentQty >= stock) {
            alert('Stock insuffisant! Il ne reste que ' + stock + ' exemplaires de ce produit.');
            return;
        }
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                quantity: 1
            });
        }
        
        localStorage.setItem('techstore_cart', JSON.stringify(cart));
        updateCartBadge();
        alert(name + ' a été ajouté au panier!');
    }
    
    function updateCartBadge() {
        var cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        var count = 0;
        for (var i = 0; i < cart.length; i++) {
            count += cart[i].quantity;
        }
        var badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = count;
        }
    }
});
</script>

<style>
.catalogue-page {
    background-color: #f8f9fa;
    min-height: calc(100vh - 76px);
}

.product-card {
    border-radius: 12px;
    border: none;
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
}

.product-image-wrapper {
    height: 200px;
    overflow: hidden;
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
