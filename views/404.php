<?php
// Page 404 - Page introuvable
// Note: Le code de réponse 404 est défini dans index.php
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-404 py-5">
                <h1 class="display-1 fw-bold text-muted">404</h1>
                <h2 class="mb-4">Page introuvable</h2>
                <p class="text-muted mb-4">Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?= BASE_URL ?>/home" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i> Retour à l'accueil
                    </a>
                    <a href="<?= BASE_URL ?>/catalogue" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i> Voir le catalogue
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
