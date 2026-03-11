<?php
// Fin de la logique PHP précédente
?>

<div id="techStoreHero" class="carousel slide carousel-fade shadow-lg" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="0" class="active" aria-current="true"></button>
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="2"></button>
        <button type="button" data-bs-target="#techStoreHero" data-bs-slide-to="3"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center">
                <div class="col-md-7 text-white py-5">
                    <span class="badge bg-primary mb-3 px-3 py-2 rounded-pill">NOUVEAUTÉ 2026</span>
                    <h1 class="display-3 fw-bold mb-3">La Technologie <span class="text-warning">à votre service</span></h1>
                    <p class="lead fs-4 mb-4">Découvrez les derniers composants et accessoires high-tech aux meilleurs prix.</p>
                    <div class="d-flex gap-3">
                        <a href="<?= BASE_URL ?>/catalogue" class="btn btn-warning btn-lg fw-bold px-4 shadow">Explorer</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center">
                <div class="col-md-7 text-white py-5">
                    <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill animate__animated animate__pulse animate__infinite">🔥 PRODUIT PHARE</span>
                    <h2 class="display-3 fw-bold mb-3 animate__animated animate__fadeInLeft">Le gaming ultime <span class="text-warning">à portée de main</span></h2>
                    <p class="lead fs-4 mb-4 animate__animated animate__fadeInLeft animate__delay-1s">Performance extrême pour les vrais joueurs ! Découvrez notre sélection gaming.</p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="<?= BASE_URL ?>/catalogue?category=gaming" class="btn btn-danger btn-lg fw-bold px-4 shadow">Acheter maintenant</a>
                        <a href="<?= BASE_URL ?>/catalogue?category=gaming" class="btn btn-outline-light btn-lg px-4">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1587620962725-abab7fe55159?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center">
                <div class="col-md-7 text-white py-5">
                    <span class="badge bg-success mb-3 px-3 py-2 rounded-pill">🛠 SERVICE CLIENT</span>
                    <h2 class="display-3 fw-bold mb-3">Besoin d'aide <span class="text-warning">Technique ?</span></h2>
                    <p class="lead fs-4 mb-4">Notre équipe d'experts vous accompagne dans le montage et le SAV.</p>
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-success btn-lg fw-bold px-4">Nous contacter</a>
                </div>
            </div>
        </div>

        <div class="carousel-item" style="background: linear-gradient(to right, rgba(0,0,0,0.85) 50%, transparent), url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1920&q=80') center/cover; min-height: 500px;">
            <div class="container h-100 d-flex align-items-center">
                <div class="col-md-7 text-white py-5">
                    <span class="badge bg-info mb-3 px-3 py-2 rounded-pill">🚚 LIVRAISON</span>
                    <h2 class="display-3 fw-bold mb-3">Livraison <span class="text-warning">Gratuite</span></h2>
                    <p class="lead fs-4 mb-4">Livraison offerte dès 50 000 Fcfa d'achat. Recevez vos produits en 24-48h.</p>
                    <a href="<?= BASE_URL ?>/catalogue" class="btn btn-info text-white btn-lg fw-bold px-4 shadow">Commander maintenant</a>
                </div>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#techStoreHero" data-bs-slide="prev">
        <span class="carousel-control-prev-icon animated-arrow" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#techStoreHero" data-bs-slide="next">
        <span class="carousel-control-next-icon animated-arrow" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
</div>

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
                    <div class="card-body pt-0 text-center">
                        <h6 class="card-title mb-2" style="font-size: 0.9rem; height: 40px; overflow: hidden;">
                            <?= htmlspecialchars($product['name']) ?>
                        </h6>
                        <div class="mb-2">
                            <?php if ($product['old_price'] > 0): ?>
                            <span class="text-muted text-decoration-line-through small d-block"><?= number_format($product['old_price'], 0, ',', ' ') ?> Fcfa</span>
                            <?php endif; ?>
                            <span class="text-danger fw-bold fs-5"><?= number_format($product['price'], 0, ',', ' ') ?> Fcfa</span>
                        </div>
                        <a href="<?= BASE_URL ?>/product/<?= $product['id'] ?>" class="btn btn-sm btn-primary w-100">Voir le produit</a>
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

<section class="why-choose-us-section py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Pourquoi choisir TechStore ?</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="feature-box p-4">
                    <i class="bi bi-truck display-4 text-primary mb-3"></i>
                    <h4>Livraison rapide</h4>
                    <p class="text-muted">Gratuite dès 50 000 Fcfa d'achat. Livraison en 24-48h.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4">
                    <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                    <h4>Garantie sécurisée</h4>
                    <p class="text-muted">Tous nos produits sont certifiés. Paiement 100% sécurisé.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4">
                    <i class="bi bi-headset display-4 text-primary mb-3"></i>
                    <h4>Support client</h4>
                    <p class="text-muted">Une équipe dédiée pour répondre à toutes vos questions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="newsletter-section bg-primary text-white py-5">
    <div class="container text-center">
        <h3 class="mb-3">Inscrivez-vous à notre newsletter</h3>
        <p class="mb-4">Recevez nos meilleures offres et actualités directement dans votre boîte mail.</p>
        <form class="row g-2 justify-content-center">
            <div class="col-md-4">
                <input type="email" class="form-control form-control-lg" placeholder="Votre adresse email" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-warning btn-lg fw-bold">S'inscrire</button>
            </div>
        </form>
    </div>
</section>

<style>
/* Animations et effets de survol */
.product-card { transition: all 0.3s ease; }
.product-card:hover { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
.btn-hover-effect:hover { transform: scale(1.05); }

/* Styles Responsifs */
@media (max-width: 768px) {
    #techStoreHero .carousel-item { min-height: 350px !important; }
    #techStoreHero h1, #techStoreHero h2 { font-size: 1.8rem !important; }
    #techStoreHero .lead { display: none; }
    .why-choose-us-section h2 { font-size: 1.5rem; }
    .feature-box i { font-size: 2.5rem !important; }
}
</style>