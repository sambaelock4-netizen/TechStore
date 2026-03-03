<?php
// Récupérer les produits vedettes depuis la base de données (non affiché sur la page d'accueil)
$featured_products = [];
$categories = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE is_featured = 1 AND is_active = 1 LIMIT 6");
    $stmt->execute();
    $featured_products = $stmt->fetchAll();
    
    $stmt = $pdo->prepare("SELECT * FROM categories LIMIT 6");
    $stmt->execute();
    $categories = $stmt->fetchAll();
    
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
                    <span class="badge bg-primary mb-3 px-3 py-2 rounded-pill animate__animated animate__fadeInDown">🚀 NOUVEAUTÉ</span>
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

        <!-- Slide 2 - PROMO -->
        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center carousel-item-content">
                <div class="col-md-7 text-white">
                    <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill animate__animated animate__fadeInDown">🔥 PROMO</span>
                    <h2 class="display-3 fw-bold mb-3 animate__animated animate__fadeInLeft">Hasta la <span class="text-warning">Vista</span></h2>
                    <p class="lead fs-4 mb-4 animate__animated animate__fadeInLeft animate__delay-1s">Profitez de offres exceptionnelles sur toute notre gamme.</p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="<?= BASE_URL ?>/catalogue" class="btn btn-warning btn-md fw-bold px-3 py-2 shadow">
                            <i class="bi bi-tag me-2"></i> Découvrir les Offres
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 - GARANTIE -->
        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center carousel-item-content">
                <div class="col-md-7 text-white">
                    <span class="badge bg-success mb-3 px-3 py-2 rounded-pill animate__animated animate__fadeInDown">🛡️ GARANTIE</span>
                    <h2 class="display-3 fw-bold mb-3 animate__animated animate__fadeInLeft">Qualité <span class="text-warning">Assurée</span></h2>
                    <p class="lead fs-4 mb-4 animate__animated animate__fadeInLeft animate__delay-1s">Tous nos produits sont garantis et certifiés pour votre tranquilité.</p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="<?= BASE_URL ?>/catalogue" class="btn btn-success btn-md fw-bold px-3 py-2 shadow">
                            <i class="bi bi-shield-check me-2"></i> En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 4 - LIVRAISON -->
        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center carousel-item-content">
                <div class="col-md-7 text-white">
                    <span class="badge bg-info mb-3 px-3 py-2 rounded-pill animate__animated animate__fadeInDown">🚚 LIVRAISON</span>
                    <h2 class="display-3 fw-bold mb-3 animate__animated animate__fadeInLeft">Livraison <span class="text-warning">Gratuite</span></h2>
                    <p class="lead fs-4 mb-4 animate__animated animate__fadeInLeft animate__delay-1s">Livraison gratuite dès 50€ d'achat. Recevez vos produits en 24-48h.</p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
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

<!-- Why Choose Us Section -->
<section class="why-choose-us-section py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Pourquoi choisir TechStore ?</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="feature-box p-4">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h4>Livraison rapide</h4>
                    <p class="text-muted">Livraison gratuite dès 50€ d'achat. Livraison en 24-48h.</p>
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
