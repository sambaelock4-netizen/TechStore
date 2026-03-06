<?php
// Récupérer les produits vedettes et les produits en promotion depuis la base de données
$featured_products = [];
$promotion_products = [];
$categories = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE is_featured = 1 AND is_active = 1 LIMIT 6");
    $stmt->execute();
    $featured_products = $stmt->fetchAll();
    
    $stmt = $pdo->prepare("SELECT * FROM categories LIMIT 6");
    $stmt->execute();
    $categories = $stmt->fetchAll();
    
    // Récupérer les produits en promotion
    $stmt = $pdo->prepare("SELECT * FROM products WHERE is_promotion = 1 AND is_active = 1 ORDER BY discount DESC LIMIT 8");
    $stmt->execute();
    $promotion_products = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log($e->getMessage());
}
?>

<!-- Hero Section with Carousel -->
<div id="techStoreHero" class="carousel slide carousel-fade shadow-lg" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="0" class="active" aria-current="true"></button>
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="2"></button>
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="3"></button>
    </div>

    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center carousel-item-content">
                <div class="col-md-7 text-white">
                    <span class="badge bg-primary mb-3 px-3 py-2 rounded-pill animate__animated animate__fadeInDown"> 🆕 NOUVEAUTÉ</span>
                    <h1 class="display-3 fw-bold mb-3 animate__animated animate__fadeInLeft">L'Excellence <br><span class="text-warning">Informatique</span></h1>
                    <p class="lead fs-4 mb-4 animate__animated animate__fadeInLeft animate__delay-1s">Découvrez notre collection exclusive d'équipements haute performance.</p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="<?= BASE_URL ?>/catalogue" class="btn btn-warning btn-md fw-bold px-3 py-2 shadow btn-hover-effect">
                            <i class="bi bi-grid-3x3-gap me-2"></i> Explorer
                        </a>
                        <a href="<?= BASE_URL ?>/register" class="btn btn-outline-light btn-md px-3 py-2 btn-hover-outline">
                            <i class="bi bi-person-plus me-2"></i> Créer un compte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 - PANIER -->
        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center carousel-item-content">
                <div class="col-md-7 text-white">
                    <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill" id="promoCartBadge">🛒 Panier (0)</span>
                    <h2 class="display-3 fw-bold mb-3">Votre <span class="text-warning">Panier</span></h2>
                    <p class="lead fs-4 mb-4">Consultez vos articles et finalisez vos achats.</p>
                    <div class="d-flex gap-3">
                        <a href="<?= BASE_URL ?>/cart" class="btn btn-warning btn-md fw-bold px-3 py-2 shadow">
                            <i class="bi bi-cart3 me-2"></i> Voir le Panier
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 - SUPPORT -->
        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center carousel-item-content">
                <div class="col-md-7 text-white">
                    <span class="badge bg-success mb-3 px-3 py-2 rounded-pill">🎧 SUPPORT</span>
                    <h2 class="display-3 fw-bold mb-3">Support Client <span class="text-warning">24/7</span></h2>
                    <p class="lead fs-4 mb-4">Notre équipe est disponible pour vous accompagner.</p>
                    <div class="d-flex gap-3">
                        <a href="<?= BASE_URL ?>/contact" class="btn btn-success btn-md fw-bold px-3 py-2 shadow">
                            <i class="bi bi-chat-dots me-2"></i> Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 4 - LIVRAISON -->
        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center carousel-item-content">
                <div class="col-md-7 text-white">
                    <span class="badge bg-info mb-3 px-3 py-2 rounded-pill">🚚 LIVRAISON</span>
                    <h2 class="display-3 fw-bold mb-3">Livraison <span class="text-warning">Gratuite</span></h2>
                    <p class="lead fs-4 mb-4">Livraison gratuite dès 50000 Fcfa d'achat. Recevez vos produits en 24-48h.</p>
                    <div class="d-flex gap-3">
                        <a href="<?= BASE_URL ?>/catalogue" class="btn btn-info btn-md fw-bold px-3 py-2 shadow">
                            <i class="bi bi-truck me-2"></i> Commander maintenant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#techStoreHero" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark bg-opacity-50 p-3 rounded-circle" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#techStoreHero" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark bg-opacity-50 p-3 rounded-circle" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
</div>

<!-- Promotions Section -->
<?php if (!empty($promotion_products)): ?>
<section class="promotions-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">🔥 Promotions du Moment</h2>
            <p class="text-muted">Profitez de nos offres exclusives !</p>
        </div>
        <div class="row g-4">
            <?php foreach ($promotion_products as $product): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <?php if ($product['discount'] > 0): ?>
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">-<?= $product['discount'] ?>%</span>
                    <?php endif; ?>
                    <div class="position-relative">
                        <img src="<?= !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x200?text=Produit' ?>" 
                             class="card-img-top p-3" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             style="height: 180px; object-fit: contain;">
                    </div>
                    <div class="card-body pt-0">
                        <h6 class="card-title mb-2" style="font-size: 0.9rem; height: 40px; overflow: hidden;">
                            <?= htmlspecialchars($product['name']) ?>
                        </h6>
                        <div class="mb-2">
                            <?php if ($product['old_price'] > 0): ?>
                            <span class="text-muted text-decoration-line-through small"><?= number_format($product['old_price'], 2, ',', ' ') ?> FC</span>
                            <?php endif; ?>
                            <span class="text-danger fw-bold fs-5"><?= number_format($product['price'], 2, ',', ' ') ?> FC</span>
                        </div>
                        <a href="<?= BASE_URL ?>/product/<?= $product['id'] ?>" class="btn btn-sm btn-primary w-100">
                            Voir
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/catalogue?promo=1" class="btn btn-outline-danger btn-lg">
                Voir toutes les promotions <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Choose Us Section -->
<section class="why-choose-us-section py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Pourquoi choisir TechStore ?</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="feature-box p-4">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h4>Livraison rapide</h4>
                    <p class="text-muted">Livraison gratuite dès 50000 FC d'achat. Livraison en 24-48h.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="feature-box p-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h4>Garantie sécurisée</h4>
                    <p class="text-muted">Tous nos produits sont garantis. Paiement 100% sécurisé.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="feature-box p-4">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h4>Support client</h4>
                    <p class="text-muted">Notre équipe est disponible pour vous aider.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section bg-primary text-white py-5">
    <div class="container text-center">
        <h3 class="mb-3">Inscrivez-vous à notre newsletter</h3>
        <p class="mb-4">Recevez nos offres exclusives et nouveautés directement dans votre boîte mail.</p>
        <form class="row justify-content-center g-3 align-items-center">
            <div class="col-auto">
                <input type="email" class="form-control" placeholder="Votre adresse email" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-warning">S'inscrire</button>
            </div>
        </form>
    </div>
</section>

<style>
/* Mobile Responsive Styles for Home Page */
@media (max-width: 768px) {
    /* Hero Carousel */
    #techStoreHero .carousel-item {
        min-height: 280px !important;
    }
    
    #techStoreHero .carousel-item > div {
        min-height: 280px;
        padding: 1.5rem 1rem;
    }
    
    #techStoreHero .carousel-item h1,
    #techStoreHero .carousel-item h2 {
        font-size: 1.3rem !important;
    }
    
    #techStoreHero .carousel-item .lead {
        font-size: 0.85rem !important;
        display: none;
    }
    
    #techStoreHero .carousel-item .badge {
        font-size: 0.65rem;
        padding: 0.3em 0.6em;
    }
    
    #techStoreHero .carousel-item .btn {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }
    
    #techStoreHero .carousel-item .d-flex {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    /* Why Choose Us Section */
    .why-choose-us-section {
        padding: 2rem 0 !important;
    }
    
    .why-choose-us-section h2 {
        font-size: 1.3rem;
    }
    
    .why-choose-us-section .feature-box {
        padding: 1rem !important;
    }
    
    .why-choose-us-section .feature-box i {
        font-size: 2rem !important;
    }
    
    .why-choose-us-section .feature-box h4 {
        font-size: 1rem;
    }
    
    .why-choose-us-section .feature-box p {
        font-size: 0.85rem;
    }
    
    /* Newsletter Section */
    .newsletter-section {
        padding: 2rem 0 !important;
    }
    
    .newsletter-section h3 {
        font-size: 1.2rem;
    }
    
    .newsletter-section p {
        font-size: 0.85rem;
    }
}
</style>
