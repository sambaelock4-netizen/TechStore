<?php
// Récupérer le produit par ID
$product = null;
$similar_products = [];
$category = null;

if (isset($id) && $id > 0) {
    try {
        // Récupérer le produit
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name, c.id as category_id 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ? AND p.is_active = 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        
        if ($product) {
            $title = $product['name'] . ' - TechStore';
            
            // Récupérer les produits similaires (même catégorie)
            $stmt = $pdo->prepare("SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1 
                LIMIT 4");
            $stmt->execute([$product['category_id'], $product['id']]);
            $similar_products = $stmt->fetchAll();
            
            // Récupérer la catégorie
            if ($product['category_id']) {
                $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
                $stmt->execute([$product['category_id']]);
                $category = $stmt->fetch();
            }
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

// Si pas de produit, rediriger vers 404
if (!$product) {
    header('Location: ' . BASE_URL . '/404');
    exit;
}

// Parser les images JSON
$product_images = [];
if (!empty($product['images'])) {
    $product_images = json_decode($product['images'], true);
}
// Ajouter l'image principale avec gestion URL locale/externe
if (!empty($product['image'])) {
    $mainImg = $product['image'];
    if (strpos($mainImg, 'http') !== 0) {
        $mainImg = UPLOAD_URL . '/' . $mainImg;
    }
    array_unshift($product_images, $mainImg);
}
// Images par défaut si aucune image
if (empty($product_images)) {
    $product_images = [
        'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1593062096033-9a26b09da705?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=800&q=80'
    ];
}

// Fonction helper pour convertir les URLs d'images locales en URLs complètes
function getImageUrl($imgPath) {
    if (empty($imgPath)) return $imgPath;
    if (strpos($imgPath, 'http') === 0) return $imgPath;
    return UPLOAD_URL . '/' . $imgPath;
}
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/home">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/catalogue">Catalogue</a></li>
            <?php if ($category): ?>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/catalogue?category=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </div>
</nav>

<!-- Product Detail Section -->
<section class="product-detail-section py-5">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <!-- Main Image -->
                    <div class="main-image-container mb-3">
                        <img id="mainImage" src="<?= htmlspecialchars($product_images[0]) ?>" 
                            alt="<?= htmlspecialchars($product['name']) ?>" 
                            class="img-fluid main-image"
                            data-zoom-image="<?= htmlspecialchars($product_images[0]) ?>">
                        <?php if ($product['stock'] > 0 && $product['stock'] <= 5): ?>
                        <span class="stock-badge low-stock">Plus que <?= $product['stock'] ?> en stock!</span>
                        <?php elseif ($product['stock'] == 0): ?>
                        <span class="stock-badge out-of-stock">Rupture de stock</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="thumbnail-grid">
                        <?php foreach ($product_images as $index => $img): ?>
                        <div class="thumbnail-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars($img) ?>" 
                                alt="<?= htmlspecialchars($product['name']) ?> - Image <?= $index + 1 ?>"
                                onclick="changeImage('<?= htmlspecialchars($img) ?>', this)">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Category -->
                    <?php if ($category): ?>
                    <a href="<?= BASE_URL ?>/catalogue?category=<?= $category['id'] ?>" class="product-category">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                    <?php endif; ?>
                    
                    <!-- Product Name -->
                    <h1 class="product-title mt-2"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <!-- Price -->
                    <div class="product-price mb-4">
                        <span class="current-price"><?= displayPrice($product['price']) ?></span>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="stock-status mb-4">
                        <?php if ($product['stock'] > 0): ?>
                            <?php if ($product['stock'] > 10): ?>
                            <span class="in-stock"><i class="bi bi-check-circle-fill me-2"></i>En stock</span>
                            <?php elseif ($product['stock'] > 5): ?>
                            <span class="low-stock-text"><i class="bi bi-exclamation-circle-fill me-2"></i>Stock limité (<?= $product['stock'] ?> disponibles)</span>
                            <?php else: ?>
                            <span class="very-low-stock-text"><i class="bi bi-exclamation-triangle-fill me-2"></i>Dernières pièces (<?= $product['stock'] ?> disponibles)</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="out-of-stock-text"><i class="bi bi-x-circle-fill me-2"></i>Rupture de stock</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Short Description -->
                    <p class="product-short-description mb-4">
                        <?= htmlspecialchars($product['short_description'] ?? '') ?>
                    </p>
                    
                    <!-- Add to Cart Form -->
                    <?php if ($product['stock'] > 0): ?>
                    <div class="add-to-cart-section mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="quantity" class="form-label">Quantité:</label>
                                <div class="quantity-selector">
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity(-1)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" 
                                        value="1" min="1" max="<?= $product['stock'] ?>" 
                                        class="form-control text-center">
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity(1)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <button class="btn btn-primary btn-lg w-100 add-to-cart" 
                                    data-id="<?= $product['id'] ?>"
                                    data-name="<?= htmlspecialchars($product['name']) ?>"
                                    data-price="<?= $product['price'] ?>">
                                    <i class="bi bi-cart-plus me-2"></i>Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>Ce produit est actuellement indisponible. 
                        <a href="<?= BASE_URL ?>/catalogue">Découvrez nos autres produits!</a>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Product Features -->
                    <div class="product-features mt-4">
                        <div class="feature-item">
                            <i class="bi bi-truck feature-icon"></i>
                            <div>
                                <strong>Livraison gratuite</strong>
                                <small class="d-block text-muted"> dès 50000 FC d'achat</small>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <div>
                                <strong>Garantie</strong>
                                <small class="d-block text-muted"> 2 ans minimale</small>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-arrow-return-left feature-icon"></i>
                            <div>
                                <strong>Retours</strong>
                                <small class="d-block text-muted"> 30 jours pour changer d'avis</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button" role="tab">
                            Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specs-tab" data-bs-toggle="tab" 
                            data-bs-target="#specs" type="button" role="tab">
                            Caractéristiques
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content p-4 border border-top-0 bg-white" id="productTabsContent">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <h3>Description du produit</h3>
                        <div class="product-description mt-3">
                            <?= nl2br(htmlspecialchars($product['description'] ?? 'Aucune description disponible.')) ?>
                        </div>
                    </div>
                    
                    <!-- Specs Tab -->
                    <div class="tab-pane fade" id="specs" role="tabpanel">
                        <h3>Caractéristiques techniques</h3>
                        <div class="specs-table mt-3">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Référence</th>
                                        <td><?= htmlspecialchars($product['slug']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Catégorie</th>
                                        <td><?= htmlspecialchars($product['category_name'] ?? 'Non catégorisé') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Prix</th>
                                        <td><?= number_format($product['price'], 2, ',', ' ') ?> FC</td>
                                    </tr>
                                    <tr>
                                        <th>Disponibilité</th>
                                        <td>
                                            <?php if ($product['stock'] > 0): ?>
                                                <span class="text-success">En stock (<?= $product['stock'] ?> unités)</span>
                                            <?php else: ?>
                                                <span class="text-danger">Rupture de stock</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Similar Products -->
        <?php if (!empty($similar_products)): ?>
        <div class="similar-products-section mt-5">
            <h3 class="mb-4">Produits similaires</h3>
            <div class="row g-4">
                <?php foreach ($similar_products as $simProduct): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100">
                        <img src="<?= htmlspecialchars(!empty($simProduct['image']) ? getImageUrl($simProduct['image']) : 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=400&q=80') ?>"
                            class="card-img-top" 
                            alt="<?= htmlspecialchars($simProduct['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($simProduct['name']) ?></h5>
                            <p class="card-text text-muted small">
                                <?= htmlspecialchars($simProduct['short_description'] ?? '') ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price"><?= displayPrice($simProduct['price']) ?></span>
                                <a href="<?= BASE_URL ?>/product/<?= $simProduct['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.product-detail-section {
    background-color: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.product-gallery .main-image-container {
    position: relative;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-gallery .main-image {
    width: 100%;
    height: auto;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-gallery .main-image:hover {
    transform: scale(1.05);
    cursor: zoom-in;
}

.stock-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.stock-badge.low-stock {
    background: #ffc107;
    color: #000;
}

.stock-badge.out-of-stock {
    background: #dc3545;
    color: #fff;
}

.thumbnail-grid {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 10px 0;
}

.thumbnail-item {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.thumbnail-item:hover,
.thumbnail-item.active {
    border-color: var(--primary-color);
}

.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-category {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
}

.product-price .current-price {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.stock-status {
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stock-status .in-stock {
    color: #198754;
    font-weight: 500;
}

.stock-status .low-stock-text {
    color: #ffc107;
    font-weight: 500;
}

.stock-status .very-low-stock-text {
    color: #fd7e14;
    font-weight: 500;
}

.stock-status .out-of-stock-text {
    color: #dc3545;
    font-weight: 500;
}

.product-short-description {
    font-size: 1.1rem;
    color: #6c757d;
    line-height: 1.6;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 5px;
}

.quantity-selector .form-control {
    width: 60px;
    text-align: center;
}

.quantity-selector button {
    padding: 0.375rem 0.75rem;
}

.add-to-cart {
    transition: all 0.3s;
}

.add-to-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.product-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.product-features .feature-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: white;
    border-radius: 8px;
}

.product-features .feature-icon {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.nav-tabs .nav-link {
    font-weight: 600;
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 15px 25px;
}

.nav-tabs .nav-link.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
    background: transparent;
}

.product-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.product-card .card-img-top {
    height: 200px;
    object-fit: cover;
}

.product-card .price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-color);
}

@media (max-width: 768px) {
    .product-title {
        font-size: 1.5rem;
    }
    
    .product-price .current-price {
        font-size: 1.5rem;
    }
    
    .thumbnail-item {
        width: 60px;
        height: 60px;
    }
}
</style>

<script>
function changeImage(src, element) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    element.parentElement.classList.add('active');
}

function changeQuantity(change) {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    let value = parseInt(input.value) + change;
    
    if (value < 1) value = 1;
    if (value > max) value = max;
    
    input.value = value;
}

// Add to cart functionality
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const price = parseFloat(this.dataset.price);
        const quantity = parseInt(document.getElementById('quantity').value);
        
        addToCart(id, name, price, quantity);
    });
});

function addToCart(id, name, price, quantity) {
    let cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
    
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: quantity
        });
    }
    
    localStorage.setItem('techstore_cart', JSON.stringify(cart));
    
    // Update badge
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = cartCount;
    }
    
    // Show toast
    showToast(name + ' a été ajouté au panier (' + quantity + ')', 'success');
}
</script>
