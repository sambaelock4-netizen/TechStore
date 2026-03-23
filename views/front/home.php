<?php
$promo=[]; $featured=[]; $cats=[];
try {
  $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND p.is_promotion=1 ORDER BY p.created_at DESC LIMIT 8"); $s->execute(); $promo=$s->fetchAll();
  $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND p.is_featured=1 ORDER BY p.created_at DESC LIMIT 8"); $s->execute(); $featured=$s->fetchAll();
  if(count($featured)<4){ $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 ORDER BY p.created_at DESC LIMIT 8"); $s->execute(); $featured=$s->fetchAll(); }
  $s=$pdo->prepare("SELECT * FROM categories WHERE is_active=1 LIMIT 8"); $s->execute(); $cats=$s->fetchAll();
} catch(PDOException $e){ error_log($e->getMessage()); }
function imgUrl($img, $cat=''){
  if(empty($img)) return unsplashFallback($cat);
  if(strpos($img,'http')===0) return $img;
  $path = UPLOAD_URL.'/'.$img;
  return $path;
}
function unsplashFallback($cat=''){
  $map = [
    'ordinateur'=>'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
    'composant' =>'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
    'gaming'    =>'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80',
    'périphériq'=>'https://images.unsplash.com/photo-1527443224154-c4a573d5e6d0?auto=format&fit=crop&w=800&q=80',
    'stockage'  =>'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80',
    'audio'     =>'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
    'réseau'    =>'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=800&q=80',
    'accessoire'=>'https://images.unsplash.com/photo-1585771724684-38269d6639fd?auto=format&fit=crop&w=800&q=80',
  ];
  $cat = mb_strtolower($cat);
  foreach($map as $k=>$url) if(stripos($cat,$k)!==false) return $url;
  return 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80';
}
?>

<!-- ── HERO ── -->
<div class="ts-hero">
<div id="tsHero" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
  <div class="carousel-indicators">
    <?php for($i=0;$i<4;$i++): ?><button type="button" data-bs-target="#tsHero" data-bs-slide-to="<?=$i?>" <?=$i===0?'class="active"':''?>></button><?php endfor; ?>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active" style="background-image:url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1920&q=80')">
      <div class="ts-slide-overlay"></div>
      <div class="ts-hero-content">
        <div class="ts-hero-badge"><i class="fas fa-star"></i> Nouveauté 2026</div>
        <h1 class="ts-hero-title">La Technologie<br><span>à votre service</span></h1>
        <p class="ts-hero-desc">Découvrez les derniers composants et accessoires high-tech aux meilleurs prix au Cameroun.</p>
        <div class="ts-hero-btns">
          <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-p ts-btn-lg"><i class="fas fa-bolt"></i> Explorer le catalogue</a>
          <a href="<?= BASE_URL ?>/catalogue?promo=1" class="ts-btn ts-btn-o ts-btn-lg"><i class="fas fa-fire"></i> Promotions</a>
        </div>
      </div>
    </div>
    <div class="carousel-item" style="background-image:url('https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1920&q=80')">
      <div class="ts-slide-overlay"></div>
      <div class="ts-hero-content">
        <div class="ts-hero-badge" style="background:rgba(255,160,122,.16);border-color:rgba(255,160,122,.38);color:#ffa07a"><i class="fas fa-gamepad"></i> Gaming Ultimate</div>
        <h1 class="ts-hero-title">Performance<br><span>sans limites</span></h1>
        <p class="ts-hero-desc">Setup gaming haut de gamme — cartes graphiques, processeurs, périphériques de compétition.</p>
        <div class="ts-hero-btns">
          <a href="<?= BASE_URL ?>/catalogue?category=gaming" class="ts-btn ts-btn-p ts-btn-lg"><i class="fas fa-gamepad"></i> Voir la sélection gaming</a>
          <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-o ts-btn-lg">Tout le catalogue</a>
        </div>
      </div>
    </div>
    <div class="carousel-item" style="background-image:url('https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=1920&q=80')">
      <div class="ts-slide-overlay"></div>
      <div class="ts-hero-content">
        <div class="ts-hero-badge" style="background:rgba(0,123,255,.18);border-color:rgba(0,123,255,.4);color:#3d9cff"><i class="fas fa-truck-fast"></i> Livraison Express</div>
        <h1 class="ts-hero-title">Livraison rapide<br><span>partout au Cameroun</span></h1>
        <p class="ts-hero-desc">Gratuite dès 50 000 FC d'achat. Livraison en 24–48h à Douala, Yaoundé et dans toutes les grandes villes.</p>
        <div class="ts-hero-btns">
          <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-b ts-btn-lg"><i class="fas fa-shopping-cart"></i> Commander maintenant</a>
          <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-o ts-btn-lg"><i class="fas fa-map-location-dot"></i> Zones de livraison</a>
        </div>
      </div>
    </div>
    <div class="carousel-item" style="background-image:url('https://images.unsplash.com/photo-1614624532983-4ce03382d63d?auto=format&fit=crop&w=1920&q=80')">
      <div class="ts-slide-overlay"></div>
      <div class="ts-hero-content">
        <div class="ts-hero-badge" style="background:rgba(255,160,122,.18);border-color:rgba(255,160,122,.42);color:#ffa07a"><i class="fas fa-certificate"></i> Certifié &amp; Garanti</div>
        <h1 class="ts-hero-title">Produits 100% certifiés<br><span>garantis 2 ans</span></h1>
        <p class="ts-hero-desc">Chaque produit est authentique, testé et approuvé par nos experts. SAV réactif disponible 7j/7 pour vous accompagner.</p>
        <div class="ts-hero-btns">
          <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-p ts-btn-lg"><i class="fas fa-shield-check"></i> Découvrir nos produits</a>
          <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-o ts-btn-lg"><i class="fas fa-headset"></i> Contacter le SAV</a>
        </div>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#tsHero" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
  <button class="carousel-control-next" type="button" data-bs-target="#tsHero" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
</div>
</div>

<!-- ── TRUST BAR ── -->
<div class="ts-trust">
  <div class="ts-trust-in">
    <div class="ts-trust-item"><i class="fas fa-truck-fast"></i> Livraison 24–48h</div>
    <div class="ts-trust-item"><i class="fas fa-shield-check"></i> Paiement sécurisé</div>
    <div class="ts-trust-item"><i class="fas fa-rotate-left"></i> Retours 30 jours</div>
    <div class="ts-trust-item"><i class="fas fa-headset"></i> Support 7j/7</div>
    <div class="ts-trust-item"><i class="fas fa-award"></i> Garantie 2 ans</div>
  </div>
</div>

<!-- ── CATÉGORIES ── -->
<?php if(!empty($cats)): ?>
<div class="ts-section" style="padding-bottom:0">
  <div class="ts-wrap">
    <div class="ts-section-tag reveal"><span>Parcourir par catégorie</span></div>
    <div class="ts-cats-strip reveal">
      <?php $icons=['laptop'=>'fas fa-laptop','smartphone'=>'fas fa-mobile-screen','gaming'=>'fas fa-gamepad','audio'=>'fas fa-headphones','photo'=>'fas fa-camera','accessoire'=>'fas fa-keyboard','reseau'=>'fas fa-wifi','stockage'=>'fas fa-hard-drive'];
      foreach($cats as $c): $ico='fas fa-box';
        foreach($icons as $k=>$i) if(stripos($c['name'],$k)!==false){$ico=$i;break;} ?>
      <a href="<?= BASE_URL ?>/catalogue?category=<?= $c['id'] ?>" class="ts-cat-chip">
        <i class="<?= $ico ?>"></i><span><?= htmlspecialchars($c['name']) ?></span>
      </a>
      <?php endforeach; ?>
      <a href="<?= BASE_URL ?>/catalogue" class="ts-cat-chip"><i class="fas fa-th-large"></i><span>Tout voir</span></a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ── PRODUITS VEDETTES ── -->
<?php if(!empty($featured)): ?>
<div class="ts-section">
  <div class="ts-wrap">
    <div class="reveal" style="text-align:center;margin-bottom:38px">
      <div class="ts-section-tag"><span>Sélection premium</span></div>
      <h2 class="ts-section-title">Produits à la une</h2>
      <p class="ts-section-sub">Notre sélection de produits high-tech incontournables</p>
    </div>
    <div class="ts-grid-4">
      <?php foreach(array_slice($featured,0,8) as $p):
        $img=imgUrl($p['image'], $p['cat']??''); if(!$img) $img=unsplashFallback($p['cat']??'');
        if($p['stock']==0){$sc='ts-sout';$sl='Rupture';}elseif($p['stock']<=5){$sc='ts-slow';$sl='Stock limité';}else{$sc='ts-sok';$sl='En stock';}
      ?>
      <div class="ts-pcard reveal">
        <div style="position:absolute;top:11px;left:11px;z-index:5;display:flex;flex-direction:column;gap:5px">
          <?php if($p['is_promotion']&&$p['discount']>0): ?><span class="ts-badge ts-badge-r">-<?=$p['discount']?>%</span><?php endif; ?>
          <?php if($p['is_featured']): ?><span class="ts-badge ts-badge-y" style="font-size:9px"><i class="fas fa-star"></i></span><?php endif; ?>
          <?php if($p['stock']==0): ?><span class="ts-badge ts-badge-m">Rupture</span><?php endif; ?>
        </div>
        <div class="ts-pimg"><a href="<?= BASE_URL ?>/product/<?= $p['id'] ?>"><img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>"></a></div>
        <div class="ts-pbody">
          <span class="ts-pcat"><?= htmlspecialchars($p['cat']??'Général') ?></span>
          <a href="<?= BASE_URL ?>/product/<?= $p['id'] ?>" style="text-decoration:none"><div class="ts-pname"><?= htmlspecialchars($p['name']) ?></div></a>
          <?php if($p['is_promotion']&&$p['old_price']>0): ?><div class="ts-pold"><?= number_format($p['old_price'],0,',',' ') ?> FC</div><?php endif; ?>
          <div class="ts-pprice"><?= displayPrice($p['price']) ?></div>
          <div class="ts-pstock <?= $sc ?>"><div class="ts-pstock-dot"></div><?= $sl ?></div>
          <div class="ts-pact">
            <?php if($p['stock']>0): ?>
            <button class="ts-atc add-to-cart" data-id="<?=$p['id']?>" data-name="<?=htmlspecialchars($p['name'])?>" data-price="<?=$p['price']?>" data-image="<?=htmlspecialchars($img)?>"><i class="fas fa-cart-plus"></i> Ajouter</button>
            <?php else: ?><button class="ts-atc" disabled><i class="fas fa-ban"></i> Indisponible</button><?php endif; ?>
            <a href="<?= BASE_URL ?>/product/<?=$p['id']?>" class="ts-peye"><i class="fas fa-eye"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:34px" class="reveal">
      <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-o ts-btn-lg"><i class="fas fa-grid-2"></i> Voir tout le catalogue</a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ── BANNER GAMING ── -->
<div class="ts-wrap" style="margin-bottom:68px">
  <div class="ts-banner reveal">
    <div class="ts-banner-text">
      <div class="ts-badge ts-badge-b" style="margin-bottom:16px;font-size:11px"><i class="fas fa-gamepad"></i> Collection Gaming 2026</div>
      <h2 class="ts-section-title" style="font-size:clamp(24px,3.5vw,38px);margin-bottom:14px">Performance<br>sans compromis</h2>
      <p style="font-family:'Times New Roman',Times,serif;font-size:15px;color:#ffffff;margin-bottom:26px;line-height:1.85;text-shadow:0 1px 4px rgba(0,0,0,.5)">Cartes graphiques, processeurs et périphériques gaming sélectionnés par nos experts pour un setup digne des pros.</p>
      <div style="display:flex;gap:11px;flex-wrap:wrap">
        <a href="<?= BASE_URL ?>/catalogue?category=gaming" class="ts-btn ts-btn-p"><i class="fas fa-bolt"></i> Voir la collection</a>
        <a href="<?= BASE_URL ?>/catalogue" class="ts-btn ts-btn-g"><i class="fas fa-th"></i> Tout le catalogue</a>
      </div>
    </div>
    <div class="ts-banner-img">
      <img src="https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?auto=format&fit=crop&w=700&q=80" alt="Gaming Setup">
    </div>
  </div>
</div>

<!-- ── PROMOTIONS ── -->
<?php if(!empty($promo)): ?>
<div class="ts-section" style="background:rgba(255,160,122,.04);border-top:1px solid rgba(255,160,122,.12);border-bottom:1px solid rgba(255,160,122,.12)">
  <div class="ts-wrap">
    <div style="text-align:center;margin-bottom:38px" class="reveal">
      <div class="ts-section-tag"><span>Offres limitées</span></div>
      <h2 class="ts-section-title">🔥 Promotions du Moment</h2>
      <p class="ts-section-sub">Des offres exclusives à durée limitée — ne ratez pas ces prix exceptionnels</p>
    </div>
    <div class="ts-grid-4">
      <?php foreach(array_slice($promo,0,4) as $p):
        $img=imgUrl($p['image'], $p['cat']??''); if(!$img) $img=unsplashFallback($p['cat']??'');
      ?>
      <div class="ts-pcard reveal">
        <?php if($p['discount']>0): ?><div style="position:absolute;top:11px;left:11px;z-index:5"><span class="ts-badge ts-badge-r" style="font-size:13px;padding:5px 14px">-<?=$p['discount']?>%</span></div><?php endif; ?>
        <div class="ts-pimg"><a href="<?= BASE_URL ?>/product/<?=$p['id']?>"><img src="<?=htmlspecialchars($img)?>" alt="<?=htmlspecialchars($p['name'])?>"></a></div>
        <div class="ts-pbody">
          <span class="ts-pcat">Promotion</span>
          <a href="<?= BASE_URL ?>/product/<?=$p['id']?>" style="text-decoration:none"><div class="ts-pname"><?=htmlspecialchars($p['name'])?></div></a>
          <?php if($p['old_price']>0): ?><div class="ts-pold"><?=number_format($p['old_price'],0,',',' ')?> FC</div><?php endif; ?>
          <div class="ts-pprice"><?=displayPrice($p['price'])?></div>
          <div class="ts-pact">
            <button class="ts-atc add-to-cart" data-id="<?=$p['id']?>" data-name="<?=htmlspecialchars($p['name'])?>" data-price="<?=$p['price']?>" data-image="<?=htmlspecialchars($img)?>"><i class="fas fa-cart-plus"></i> Ajouter</button>
            <a href="<?= BASE_URL ?>/product/<?=$p['id']?>" class="ts-peye"><i class="fas fa-eye"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:34px" class="reveal">
      <a href="<?= BASE_URL ?>/catalogue?promo=1" class="ts-btn ts-btn-p"><i class="fas fa-fire"></i> Toutes les promotions</a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ── STATS ── -->
<div class="ts-section">
  <div class="ts-wrap">
    <div class="ts-stats reveal">
      <div class="ts-stat"><div class="ts-stat-n">500+</div><div class="ts-stat-l">Produits disponibles</div></div>
      <div class="ts-stat"><div class="ts-stat-n">4 800+</div><div class="ts-stat-l">Clients satisfaits</div></div>
      <div class="ts-stat"><div class="ts-stat-n">24h</div><div class="ts-stat-l">Délai de livraison</div></div>
      <div class="ts-stat"><div class="ts-stat-n">4.9★</div><div class="ts-stat-l">Note moyenne</div></div>
    </div>
  </div>
</div>

<!-- ── POURQUOI ── -->
<div class="ts-section" style="padding-top:0">
  <div class="ts-wrap">
    <div style="text-align:center;margin-bottom:38px" class="reveal">
      <div class="ts-section-tag"><span>Notre engagement</span></div>
      <h2 class="ts-section-title">Pourquoi choisir TechStore ?</h2>
      <p class="ts-section-sub">Nous mettons tout en œuvre pour vous offrir la meilleure expérience d'achat</p>
    </div>
    <div class="ts-why-grid">
      <div class="ts-why-card reveal">
        <div class="ts-why-ico" style="background:rgba(255,160,122,.12);border:1px solid rgba(255,160,122,.22)"><i class="fas fa-truck-fast" style="color:#ffa07a"></i></div>
        <div class="ts-why-title">Livraison Express</div>
        <div class="ts-why-desc">Gratuite dès 50 000 FC. Livraison en 24–48h à Douala, Yaoundé et partout au Cameroun.</div>
      </div>
      <div class="ts-why-card reveal">
        <div class="ts-why-ico" style="background:rgba(0,123,255,.15);border:2px solid rgba(0,123,255,.35)"><i class="fas fa-certificate" style="color:#007bff;font-size:28px"></i></div>
        <div class="ts-why-title">Produits Certifiés</div>
        <div class="ts-why-desc">Tous nos produits sont authentiques et certifiés. Garantie constructeur 2 ans sur toute la gamme.</div>
      </div>
      <div class="ts-why-card reveal">
        <div class="ts-why-ico" style="background:rgba(74,222,128,.10);border:1px solid rgba(74,222,128,.2)"><i class="fas fa-headset" style="color:#4ade80"></i></div>
        <div class="ts-why-title">Support Expert 7j/7</div>
        <div class="ts-why-desc">Notre équipe de techniciens est disponible tous les jours pour vous conseiller et vous accompagner.</div>
      </div>
    </div>
  </div>
</div>

<!-- ── TÉMOIGNAGES ── -->
<div class="ts-section" style="background:rgba(0,123,255,.04);border-top:1px solid var(--bd);border-bottom:1px solid var(--bd)">
  <div class="ts-wrap">
    <div style="text-align:center;margin-bottom:38px" class="reveal">
      <div class="ts-section-tag"><span>Ils nous font confiance</span></div>
      <h2 class="ts-section-title">Ce que disent nos clients</h2>
    </div>
    <div class="ts-testi-grid">
      <div class="ts-testi reveal">
        <div class="ts-testi-stars">★★★★★</div>
        <p class="ts-testi-text">« Commande reçue en moins de 24h ! Le MacBook Pro est exactement comme décrit. Service client exceptionnel, je recommande TechStore sans hésitation. »</p>
        <div class="ts-testi-auth"><div class="ts-testi-av">KA</div><div><div class="ts-testi-name">Kamgaing A.</div><div class="ts-testi-role">Développeur Web · Douala</div></div></div>
      </div>
      <div class="ts-testi reveal">
        <div class="ts-testi-stars">★★★★★</div>
        <p class="ts-testi-text">« J'ai acheté une carte graphique RTX 4080 à un prix défiant toute concurrence. Livraison rapide, produit original. Parfait ! »</p>
        <div class="ts-testi-auth"><div class="ts-testi-av">MT</div><div><div class="ts-testi-name">Mbarga T.</div><div class="ts-testi-role">Gamer Pro · Yaoundé</div></div></div>
      </div>
      <div class="ts-testi reveal">
        <div class="ts-testi-stars">★★★★★</div>
        <p class="ts-testi-text">« Interface moderne, paiement sécurisé et équipe très à l'écoute. J'équipe tout mon bureau informatique chez TechStore depuis 2 ans. »</p>
        <div class="ts-testi-auth"><div class="ts-testi-av">NF</div><div><div class="ts-testi-name">Ngo F.</div><div class="ts-testi-role">Directrice DSI · Bafoussam</div></div></div>
      </div>
    </div>
  </div>
</div>

<!-- ── NEWSLETTER ── -->
<div class="ts-section">
  <div class="ts-wrap">
    <div class="ts-news reveal">
      <div class="ts-news-in">
        <div class="ts-badge ts-badge-r" style="margin-bottom:16px;font-size:11px"><i class="fas fa-envelope"></i> Newsletter</div>
        <h3 class="ts-news-title">Restez informé des meilleures offres</h3>
        <p class="ts-news-sub" style="color:#ffffff;font-size:16px;font-weight:500;text-shadow:0 1px 3px rgba(0,0,0,.4)">Promotions exclusives, nouveautés et conseils tech — directement dans votre boîte mail.</p>
        <form class="ts-news-form" onsubmit="nlSub(event)">
          <input type="email" placeholder="votre@email.com" required>
          <button type="submit"><i class="fas fa-paper-plane" style="margin-right:7px"></i>S'inscrire</button>
        </form>
        <p style="font-family:'Times New Roman',Times,serif;font-size:12px;color:rgba(255,255,255,.65);margin-top:12px;position:relative;z-index:1">Pas de spam · Désabonnement en un clic</p>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  var els=document.querySelectorAll('.reveal');
  if(!('IntersectionObserver' in window)){els.forEach(e=>e.classList.add('on'));return;}
  var obs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('on');obs.unobserve(e.target)}})},{threshold:.1,rootMargin:'0px 0px -40px 0px'});
  els.forEach(e=>obs.observe(e));
})();
function nlSub(e){
  e.preventDefault();
  var btn=e.target.querySelector('button');
  btn.innerHTML='<i class="fas fa-check" style="margin-right:7px"></i>Inscrit !';
  btn.style.background='linear-gradient(135deg,#16a34a,#22c55e)';
  setTimeout(()=>{btn.innerHTML='<i class="fas fa-paper-plane" style="margin-right:7px"></i>S\'inscrire';btn.style.background='';},3000);
}
</script>
