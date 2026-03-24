<?php
/**
 * ==================================================================================
 * TechStore - Catalogue des Produits
 * ==================================================================================
 * Cette page affiche la liste complète des produits avec des fonctionnalités de :
 * 1. Filtrage par catégorie, promotion ou recherche textuelle
 * 2. Tri dynamique (Prix, Nom) via JavaScript
 * 3. Commutateur de vue (Grille vs Liste)
 * 4. Gestion responsive de l'affichage
 * ==================================================================================
 */

// Initialisation des listes
$products=[]; $categories=[];

// Récupération des paramètres de filtrage depuis l'URL (GET)
$q     = isset($_GET['q']) ? trim($_GET['q']) : '';
$cid   = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$promo = isset($_GET['promo']) && $_GET['promo'] == '1';

try {
  /**
   * RÉCUPÉRATION DES CATÉGORIES
   * Nécessaire pour afficher les onglets de filtrage en haut de page.
   */
  $s=$pdo->prepare("SELECT * FROM categories WHERE is_active=1 ORDER BY name");
  $s->execute();
  $categories=$s->fetchAll();

  /**
   * RÉCUPÉRATION DES PRODUITS (Triée par nouveauté)
   * On adapte la requête SQL en fonction des filtres actifs (Recherche > Catégorie > Promo > Tout).
   */
  if(!empty($q)){
      // Recherche textuelle (Nom ou Description)
      $t='%'.$q.'%';
      $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND (p.name LIKE ? OR p.description LIKE ?) ORDER BY p.created_at DESC");
      $s->execute([$t,$t]);
  } elseif($cid > 0) {
      // Filtrage par catégorie ID
      $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND p.category_id=? ORDER BY p.created_at DESC");
      $s->execute([$cid]);
  } elseif($promo) {
      // Uniquement les produits en promotion
      $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND p.is_promotion=1 ORDER BY p.created_at DESC");
      $s->execute();
  } else {
      // Affichage par défaut (Tous les produits actifs)
      $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 ORDER BY p.created_at DESC");
      $s->execute();
  }
  
  $products=$s->fetchAll();

} catch(PDOException $e){ 
    error_log("Erreur Catalogue Data : " . $e->getMessage()); 
}

/**
 * GESTIONNAIRE D'IMAGES CATALOGUE
 * Transforme le nom de fichier stocké en URL complète.
 */
function catImg($img){
    if(empty($img)) return '';
    return strpos($img,'http')===0 ? $img : UPLOAD_URL.'/'.$img;
}
?>

<div class="ts-cat-wrap">
  <!-- Immersive Header -->
  <header class="ts-cat-hero">
    <div class="ts-cat-hero-in">
      <span class="ts-cat-hero-tag">TechStore Catalogue</span>
      <?php if(!empty($q)): ?>
      <h1 class="ts-cat-hero-title">Résultats pour "<?=htmlspecialchars($q)?>"</h1>
      <p class="ts-cat-hero-sub">Nous avons trouvé <?=count($products)?> produit(s) correspondant à votre recherche.</p>
      <?php elseif($promo): ?>
      <h1 class="ts-cat-hero-title">🔥 Offres & Promotions</h1>
      <p class="ts-cat-hero-sub">Profitez de nos meilleures réductions sur une sélection de produits high-tech.</p>
      <?php else: ?>
      <h1 class="ts-cat-hero-title">Découvrez notre Technologie</h1>
      <p class="ts-cat-hero-sub">Explorez notre large gamme de composants, ordinateurs et accessoires sélectionnés par nos experts.</p>
      <?php endif; ?>
    </div>
  </header>

  <nav class="ts-cat-nav">
    <!-- Category Filters -->
    <?php if(!empty($categories)): ?>
    <div class="ts-filters">
      <a href="<?=BASE_URL?>/catalogue" class="ts-fpill <?=!$cid&&!$promo&&!$q?'on':''?>"><i class="fas fa-th"></i> Tous</a>
      <a href="<?=BASE_URL?>/catalogue?promo=1" class="ts-fpill <?=$promo?'on':''?>"><i class="fas fa-fire"></i> Promos</a>
      <?php foreach($categories as $c): ?>
      <a href="<?=BASE_URL?>/catalogue?category=<?=$c['id']?>" class="ts-fpill <?=$cid==$c['id']?'on':''?>"><?=htmlspecialchars($c['name'])?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Search Clear (if any) -->
    <?php if(!empty($q)): ?>
    <div style="text-align:center;margin-top:-10px">
      <a href="<?=BASE_URL?>/catalogue" class="ts-btn ts-btn-g ts-btn-sm" style="border-radius:20px;padding:6px 16px"><i class="fas fa-times"></i> Réinitialiser la recherche</a>
    </div>
    <?php endif; ?>
  </nav>

  <div class="ts-sortbar">
    <div style="font-family:var(--fn);font-size:13px;font-weight:600;color:#64748b"><i class="fas fa-box" style="margin-right:7px;color:var(--blue-l)"></i><?=count($products)?> produits trouvés</div>
    <div style="display:flex;align-items:center;gap:11px">
      <select class="ts-sortsel" id="sortSel" onchange="sortProd(this.value)">
        <option value="">Trier par défaut</option>
        <option value="pa">Prix croissant</option>
        <option value="pd">Prix décroissant</option>
        <option value="na">Nom A–Z</option>
      </select>
      <div class="ts-vtoggle">
        <button class="ts-vbtn on" id="vGrid" onclick="setView('grid')" title="Grille"><i class="fas fa-th-large"></i></button>
        <button class="ts-vbtn" id="vList" onclick="setView('list')" title="Liste"><i class="fas fa-list"></i></button>
      </div>
    </div>
  </div>

  <div class="ts-pgrid" id="prodGrid">
    <?php if(empty($products)): ?>
    <div class="ts-empty">
      <i class="fas fa-box-open"></i><h3>Aucun produit trouvé</h3>
      <p>Essayez une autre catégorie ou recherche.</p>
      <a href="<?=BASE_URL?>/catalogue" class="ts-btn ts-btn-o"><i class="fas fa-arrow-left"></i> Voir tout le catalogue</a>
    </div>
    <?php else: foreach($products as $p):
      $img=catImg($p['image']); if(!$img) $img='https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=400&q=80';
      if($p['stock']==0){$sc='ts-sout';$sl='Rupture';}elseif($p['stock']<=5){$sc='ts-slow';$sl='Stock limité ('.$p['stock'].')';}else{$sc='ts-sok';$sl='En stock';}
    ?>
    <div class="ts-pcard" data-price="<?=$p['price']?>" data-name="<?=htmlspecialchars($p['name'])?>" data-id="<?=$p['id']?>">
      <div style="position:absolute;top:11px;left:11px;z-index:5;display:flex;flex-direction:column;gap:5px">
        <?php if($p['is_promotion']&&$p['discount']>0): ?><span class="ts-badge ts-badge-r">-<?=$p['discount']?>%</span><?php endif; ?>
        <?php if($p['is_featured']&&!$p['is_promotion']): ?><span class="ts-badge ts-badge-y" style="font-size:9px"><i class="fas fa-star"></i></span><?php endif; ?>
        <?php if($p['stock']==0): ?><span class="ts-badge ts-badge-m" style="font-size:9px">Rupture</span><?php endif; ?>
      </div>
      <div class="ts-pimg"><a href="<?=BASE_URL?>/product/<?=$p['id']?>"><img src="<?=htmlspecialchars($img)?>" alt="<?=htmlspecialchars($p['name'])?>"></a></div>
      <div class="ts-pbody">
        <span class="ts-pcat"><?=htmlspecialchars($p['cat']??'Général')?></span>
        <a href="<?=BASE_URL?>/product/<?=$p['id']?>" style="text-decoration:none"><div class="ts-pname"><?=htmlspecialchars($p['name'])?></div></a>
        <?php if($p['is_promotion']&&$p['old_price']>0): ?><div class="ts-pold"><?=number_format($p['old_price'],0,',',' ')?> FC</div><?php endif; ?>
        <div class="ts-pprice"><?=displayPrice($p['price'])?></div>
        <div class="ts-pstock <?=$sc?>"><div class="ts-pstock-dot"></div><?=$sl?></div>
        <div class="ts-pact">
          <?php if($p['stock']>0): ?>
          <button class="ts-atc add-to-cart" data-id="<?=$p['id']?>" data-name="<?=htmlspecialchars($p['name'])?>" data-price="<?=$p['price']?>" data-image="<?=htmlspecialchars($img)?>"><i class="fas fa-cart-plus"></i> Ajouter</button>
          <?php else: ?><button class="ts-atc" disabled><i class="fas fa-ban"></i> Indisponible</button><?php endif; ?>
          <a href="<?=BASE_URL?>/product/<?=$p['id']?>" class="ts-peye"><i class="fas fa-eye"></i></a>
        </div>
      </div>
    </div>
    <?php endforeach; endif; ?>
  </div>
</div>

<style>
/* ── Variables & Animations ── */
:root {
  --ts-glass-w: rgba(255, 255, 255, 0.84);
  --ts-glass-b: rgba(10, 15, 30, 0.04);
}
@keyframes fadeInScale { from { opacity: 0; transform: scale(0.98) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
@keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

/* ── Immersive Header ── */
.ts-cat-hero {
  background: linear-gradient(135deg, #0a0f1d 0%, #1a2238 100%);
  border-radius: 30px; margin-bottom: 40px; padding: 60px 40px;
  position: relative; overflow: hidden; border: 1px solid rgba(0, 123, 255, 0.2);
  box-shadow: 0 20px 50px rgba(0,0,0,0.15); animation: slideDown 0.6s both;
}
.ts-cat-hero::before { content:''; position:absolute; top:-100px; right:-100px; width:300px; height:300px; border-radius:50%; background:radial-gradient(circle,rgba(255,160,122,.12),transparent 70%); }
.ts-cat-hero-in { position: relative; z-index: 2; text-align: center; }
.ts-cat-hero-tag { font-family: var(--fn); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 3px; color: var(--orange); margin-bottom: 12px; display: block; }
.ts-cat-hero-title { font-family: var(--fn-title); font-size: 42px; font-weight: 900; color: #fff; margin-bottom: 12px; line-height: 1.1; }
.ts-cat-hero-sub { font-family: var(--fn); font-size: 15.5px; color: rgba(220, 230, 255, 0.7); max-width: 600px; margin: 0 auto; line-height: 1.7; }

/* ── Filters & Sort Bar ── */
.ts-cat-nav { display: flex; flex-direction: column; gap: 24px; margin-bottom: 32px; animation: slideDown 0.6s 0.1s both; }
.ts-filters { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
.ts-fpill { 
  padding: 10px 22px; border-radius: 30px; background: #fff; border: 1px solid rgba(0, 123, 255, 0.12);
  font-family: var(--fn); font-size: 13px; font-weight: 700; color: #64748b; transition: all 0.3s;
  box-shadow: 0 2px 8px rgba(0,0,0,0.03); cursor: pointer; display: flex; align-items: center; gap: 8px;
}
.ts-fpill:hover { transform: translateY(-2px); border-color: rgba(255, 160, 122, 0.3); background: rgba(255,160,122,0.04); color: var(--orange-d); }
.ts-fpill.on { background: var(--ts-gradient); border-color: transparent; color: #fff; box-shadow: 0 8px 20px rgba(255, 160, 122, 0.3); }

.ts-sortbar { 
  background: var(--ts-glass-w); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(0, 123, 255, 0.12); border-radius: 18px; padding: 14px 24px;
  display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 18px rgba(0,0,0,0.05);
}

/* ── Grid/List Transitions ── */
.ts-pgrid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; animation: fadeInScale 0.7s 0.2s both; }
.ts-pgrid.list { grid-template-columns: 1fr; }
.ts-pgrid.list .ts-pcard { display: flex; height: 180px; }
.ts-pgrid.list .ts-pimg { width: 220px; height: 100%; }
.ts-pgrid.list .ts-pbody { flex: 1; padding: 24px 32px; display: flex; flex-direction: column; justify-content: center; }
.ts-pgrid.list .ts-pact { margin-top: 15px; }

/* ── Product Alignment ── */
.ts-pcard { transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); }
.ts-pimg img { transition: transform 0.6s ease; }
.ts-pcard:hover .ts-pimg img { transform: scale(1.08); }

@media(max-width: 1100px) { .ts-pgrid { grid-template-columns: repeat(3, 1fr); } }
@media(max-width: 900px) { .ts-pgrid { grid-template-columns: repeat(2, 1fr); } .ts-pgrid.list .ts-pcard { height: auto; flex-direction: column; } .ts-pgrid.list .ts-pimg { width: 100%; height: 200px; } }
@media(max-width: 576px) { .ts-pgrid { grid-template-columns: 1fr; } .ts-cat-hero { padding: 40px 20px; } .ts-cat-hero-title { font-size: 30px; } }
</style>

<script>
function setView(v){
  var g=document.getElementById('prodGrid');
  g.style.opacity = '0';
  setTimeout(function(){
    g.classList.toggle('list',v==='list');
    document.getElementById('vGrid').classList.toggle('on',v==='grid');
    document.getElementById('vList').classList.toggle('on',v==='list');
    g.style.opacity = '1';
  }, 200);
}
function sortProd(v){
  var g=document.getElementById('prodGrid'),cards=[...g.querySelectorAll('.ts-pcard')];
  g.style.opacity = '0.4';
  setTimeout(function(){
    if(v==='pa') cards.sort((a,b)=>+a.dataset.price-+b.dataset.price);
    else if(v==='pd') cards.sort((a,b)=>+b.dataset.price-+a.dataset.price);
    else if(v==='na') cards.sort((a,b)=>a.dataset.name.localeCompare(b.dataset.name,'fr'));
    cards.forEach(c=>g.appendChild(c));
    g.style.opacity = '1';
  }, 300);
}
</script>
