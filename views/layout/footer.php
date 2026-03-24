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

<!-- ── QUICK VIEW MODAL ── -->
<div class="ts-qv-overlay" id="tsQvOverlay"></div>
<div class="ts-qv-modal" id="tsQvModal">
  <button class="ts-qv-close" id="tsQvClose"><i class="fas fa-times"></i></button>
  <div class="ts-qv-grid">
    <div class="ts-qv-img-wrap">
      <img id="tsQvImg" src="" alt="Aperçu">
      <div class="ts-qv-discount-badge" id="tsQvDiscount" style="display:none"></div>
    </div>
    <div class="ts-qv-info">
      <span class="ts-pcat" id="tsQvCat"></span>
      <h3 class="ts-qv-name" id="tsQvName"></h3>
      <div class="ts-stars-row" style="margin-bottom:10px"><span class="ts-stars">★★★★★</span><span class="ts-stars-count">(Avis vérifiés)</span></div>
      <p class="ts-qv-desc" id="tsQvDesc"></p>
      <div id="tsQvOldPrice" class="ts-pold" style="font-size:14px;display:none"></div>
      <div class="ts-pprice" id="tsQvPrice" style="font-size:26px;margin-bottom:10px"></div>
      <div class="ts-pstock" id="tsQvStock"><div class="ts-pstock-dot"></div><span id="tsQvStockText"></span></div>
      <div class="ts-qv-qty">
        <label class="ts-qty-label">Quantité</label>
        <div class="ts-qty-row">
          <button class="ts-qty-btn" onclick="tsQvQty(-1)"><i class="fas fa-minus"></i></button>
          <input type="number" id="tsQvQtyVal" value="1" min="1" max="99" class="ts-qty-inp">
          <button class="ts-qty-btn" onclick="tsQvQty(1)"><i class="fas fa-plus"></i></button>
        </div>
      </div>
      <div class="ts-qv-actions">
        <button class="ts-atc ts-qv-atc" id="tsQvAtc"><i class="fas fa-cart-plus"></i> Ajouter au panier</button>
        <a href="#" class="ts-btn ts-btn-o" id="tsQvLink" style="padding:12px 20px"><i class="fas fa-eye"></i> Voir détails</a>
      </div>
    </div>
  </div>
</div>

<!-- ── BACK TO TOP ── -->
<button class="ts-btt" id="tsBtt" title="Retour en haut"><i class="fas fa-chevron-up"></i></button>

<!-- ── FLY TO CART GHOST ── -->
<div class="ts-fly-ghost" id="tsFlyGhost"></div>

<!-- ── SEARCH AUTOCOMPLETE DROPDOWN ── -->
<div class="ts-ac-dropdown" id="tsAcDrop" style="display:none"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= PUBLIC_URL ?>/js/main.js"></script>
<script>
var TS_BASE='<?= BASE_URL ?>';
var TS_UPLOAD='<?= UPLOAD_URL ?>';
</script>
<script src="<?= PUBLIC_URL ?>/js/enhancements.js"></script>
</body>
</html>
