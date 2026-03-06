</main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row">
                <!-- À propos -->
                <div class="col-md-4 mb-3">
                    <h5><?= SITE_NAME ?></h5>
                    <p class="text-white-50"><?= SITE_DESCRIPTION ?></p>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                
                <!-- Liens rapides -->
                <div class="col-md-4 mb-3">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>/home" class="text-white text-decoration-none">Accueil</a></li>
                        <li><a href="<?= BASE_URL ?>/catalogue" class="text-white text-decoration-none">Catalogue</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div class="col-md-4 mb-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled text-white">
                        <li><i class="fas fa-envelope me-2"></i>TechStore@gmail.com</li>
                        <li><i class="fas fa-phone me-2"></i>+237 652 17 98 69</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Douala, Cameroun</li>
                    </ul>
                </div>
            </div>
            
            <hr class="bg-secondary">
            
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= PUBLIC_URL ?>/js/main.js"></script>
</body>
</html>
