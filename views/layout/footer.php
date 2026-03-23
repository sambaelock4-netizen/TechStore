<footer class="ts-footer">
  <div class="ts-footer-grid">
    <div>
      <a href="<?= BASE_URL ?>/home" class="ts-footer-logo">TECHSTORE</a>
      <p class="ts-footer-desc">Votre boutique high-tech de référence au Cameroun. Composants, accessoires et matériel informatique aux meilleurs prix, livrés en 24–48h.</p>
      <div class="ts-footer-socials">
        <a href="#" class="ts-social"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="ts-social"><i class="fab fa-instagram"></i></a>
        <a href="#" class="ts-social"><i class="fab fa-whatsapp"></i></a>
        <a href="#" class="ts-social"><i class="fab fa-x-twitter"></i></a>
      </div>
    </div>
    <div>
      <div class="ts-footer-title">Boutique</div>
      <a href="<?= BASE_URL ?>/catalogue"         class="ts-footer-link">Tout le catalogue</a>
      <a href="<?= BASE_URL ?>/catalogue?promo=1" class="ts-footer-link"><i class="fas fa-fire" style="color:#ffa07a;margin-right:5px;font-size:10px"></i>Promotions</a>
      <a href="<?= BASE_URL ?>/catalogue?new=1"   class="ts-footer-link">Nouveautés</a>
    </div>
    <div>
      <div class="ts-footer-title">Mon Compte</div>
      <?php if(isset($_SESSION['user_id'])): ?>
      <a href="<?= BASE_URL ?>/account" class="ts-footer-link">Mon profil</a>
      <a href="<?= BASE_URL ?>/orders"  class="ts-footer-link">Mes commandes</a>
      <a href="<?= BASE_URL ?>/cart"    class="ts-footer-link">Mon panier</a>
      <a href="<?= BASE_URL ?>/logout"  class="ts-footer-link">Déconnexion</a>
      <?php else: ?>
      <a href="<?= BASE_URL ?>/login"    class="ts-footer-link">Se connecter</a>
      <a href="<?= BASE_URL ?>/register" class="ts-footer-link">Créer un compte</a>
      <a href="<?= BASE_URL ?>/cart"     class="ts-footer-link">Mon panier</a>
      <?php endif; ?>
    </div>
    <div>
      <div class="ts-footer-title">Contact</div>
      <span class="ts-footer-link"><i class="fas fa-location-dot" style="margin-right:6px;opacity:.5"></i>Douala, Cameroun</span>
      <a href="tel:+237652179869"           class="ts-footer-link"><i class="fas fa-phone" style="margin-right:6px;opacity:.5"></i>+237 652 17 98 69</a>
      <a href="mailto:TechStore@gmail.com"  class="ts-footer-link"><i class="fas fa-envelope" style="margin-right:6px;opacity:.5"></i>TechStore@gmail.com</a>
      <span class="ts-footer-link"><i class="fas fa-clock" style="margin-right:6px;opacity:.5"></i>Lun–Sam : 8h–18h</span>
    </div>
  </div>
  <div class="ts-footer-bottom">
    <span>© <?= date('Y') ?> TechStore · Tous droits réservés</span>
    <div style="display:flex;gap:14px">
      <a href="#" class="ts-footer-legal">Mentions légales</a>
      <a href="#" class="ts-footer-legal">CGV</a>
      <a href="#" class="ts-footer-legal">Confidentialité</a>
    </div>
    <div class="ts-pay-chips">
      <span class="ts-pay-chip"><i class="fab fa-cc-visa"></i> Visa</span>
      <span class="ts-pay-chip"><i class="fab fa-cc-mastercard"></i> Mastercard</span>
      <span class="ts-pay-chip"><i class="fas fa-mobile-screen"></i> Mobile Money</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= PUBLIC_URL ?>/js/main.js"></script>
</body>
</html>
