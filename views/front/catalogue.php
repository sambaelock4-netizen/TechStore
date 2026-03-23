<?php
$products=[];$categories=[];
$q=isset($_GET['q'])?trim($_GET['q']):'';
$cid=isset($_GET['category'])?(int)$_GET['category']:0;
$promo=isset($_GET['promo'])&&$_GET['promo']=='1';
try{
  $s=$pdo->prepare("SELECT * FROM categories WHERE is_active=1 ORDER BY name");$s->execute();$categories=$s->fetchAll();
  if(!empty($q)){$t='%'.$q.'%';$s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND (p.name LIKE ? OR p.description LIKE ?) ORDER BY p.created_at DESC");$s->execute([$t,$t]);}
  elseif($cid>0){$s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND p.category_id=? ORDER BY p.created_at DESC");$s->execute([$cid]);}
  elseif($promo){$s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND p.is_promotion=1 ORDER BY p.created_at DESC");$s->execute();}
  else{$s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 ORDER BY p.created_at DESC");$s->execute();}
  $products=$s->fetchAll();
}catch(PDOException $e){error_log($e->getMessage());}
function catImg($img){if(empty($img))return'';return strpos($img,'http')===0?$img:UPLOAD_URL.'/'.$img;}
?>

<div class="ts-cat-wrap">
  <div class="ts-cat-head">
    <?php if(!empty($q)): ?>
    <div class="ts-sqres">
      <span>Résultats pour : <strong>"<?=htmlspecialchars($q)?>"</strong> — <?=count($products)?> produit(s)</span>
      <a href="<?=BASE_URL?>/catalogue" class="ts-btn ts-btn-g ts-btn-sm"><i class="fas fa-times"></i> Effacer</a>
    </div>
    <?php elseif($promo): ?>
    <h1 class="ts-cat-htitle"><i class="fas fa-fire" style="margin-right:9px;font-size:22px;opacity:.8"></i>Promotions en cours</h1>
    <p class="ts-cat-hsub"><?=count($products)?> offre(s) disponible(s)</p>
    <?php else: ?>
    <h1 class="ts-cat-htitle"><i class="fas fa-grid-2" style="margin-right:9px;font-size:22px;opacity:.8"></i>Notre Catalogue</h1>
    <p class="ts-cat-hsub"><?=count($products)?> produit(s) disponible(s)</p>
    <?php endif; ?>
  </div>

  <?php if(!empty($categories)): ?>
  <div class="ts-filters">
    <a href="<?=BASE_URL?>/catalogue" class="ts-fpill <?=!$cid&&!$promo&&!$q?'on':''?>"><i class="fas fa-th" style="margin-right:5px"></i>Tous</a>
    <a href="<?=BASE_URL?>/catalogue?promo=1" class="ts-fpill <?=$promo?'on':''?>"><i class="fas fa-fire" style="margin-right:5px"></i>Promotions</a>
    <?php foreach($categories as $c): ?><a href="<?=BASE_URL?>/catalogue?category=<?=$c['id']?>" class="ts-fpill <?=$cid==$c['id']?'on':''?>"><?=htmlspecialchars($c['name'])?></a><?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="ts-sortbar">
    <div style="font-family:'Times New Roman',Times,serif;font-size:12px;font-style:italic;color:var(--txm)"><i class="fas fa-box" style="margin-right:5px;color:var(--blue-l)"></i><?=count($products)?> produit(s)</div>
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

<script>
function setView(v){var g=document.getElementById('prodGrid');g.classList.toggle('list',v==='list');document.getElementById('vGrid').classList.toggle('on',v==='grid');document.getElementById('vList').classList.toggle('on',v==='list');}
function sortProd(v){var g=document.getElementById('prodGrid'),cards=[...g.querySelectorAll('.ts-pcard')];
  if(v==='pa') cards.sort((a,b)=>+a.dataset.price-+b.dataset.price);
  else if(v==='pd') cards.sort((a,b)=>+b.dataset.price-+a.dataset.price);
  else if(v==='na') cards.sort((a,b)=>a.dataset.name.localeCompare(b.dataset.name,'fr'));
  cards.forEach(c=>g.appendChild(c));
}
</script>
