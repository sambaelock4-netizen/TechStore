<?php
$product=null;$similar_products=[];$category=null;
if(isset($id)&&$id>0){
  try{
    $s=$pdo->prepare("SELECT p.*,c.name cat,c.id cid FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.id=? AND p.is_active=1");
    $s->execute([$id]);$product=$s->fetch();
    if($product){
      $title=$product['name'].' - TechStore';
      $s=$pdo->prepare("SELECT p.*,c.name cat FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.category_id=? AND p.id!=? AND p.is_active=1 LIMIT 4");
      $s->execute([$product['cid'],$product['id']]);$similar_products=$s->fetchAll();
      if($product['cid']){$s=$pdo->prepare("SELECT * FROM categories WHERE id=?");$s->execute([$product['cid']]);$category=$s->fetch();}
    }
  }catch(PDOException $e){error_log($e->getMessage());}
}
if(!$product){header('Location:'.BASE_URL.'/404');exit;}
$imgs=[];
if(!empty($product['images']))$imgs=json_decode($product['images'],true)?:[];
if(!empty($product['image'])){$m=$product['image'];if(strpos($m,'http')!==0)$m=UPLOAD_URL.'/'.$m;array_unshift($imgs,$m);}
if(empty($imgs))$imgs=['https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80'];
function gUrl($p){if(empty($p))return $p;return strpos($p,'http')===0?$p:UPLOAD_URL.'/'.$p;}
?>

<div class="ts-pdwrap">
  <!-- Breadcrumb -->
  <nav class="ts-breadcrumb">
    <a href="<?=BASE_URL?>/home">Accueil</a><span>/</span>
    <a href="<?=BASE_URL?>/catalogue">Catalogue</a>
    <?php if($category): ?><span>/</span><a href="<?=BASE_URL?>/catalogue?category=<?=$category['id']?>"><?=htmlspecialchars($category['name'])?></a><?php endif; ?>
    <span>/</span><span class="cur"><?=htmlspecialchars(mb_strimwidth($product['name'],0,42,'…'))?></span>
  </nav>

  <div class="ts-dg">
    <!-- Galerie -->
    <div>
      <div class="ts-mainimg">
        <img id="mainImg" src="<?=htmlspecialchars($imgs[0])?>" alt="<?=htmlspecialchars($product['name'])?>">
        <div class="ts-imgbadge">
          <?php if($product['is_promotion']&&$product['discount']>0): ?><span class="ts-badge ts-badge-r" style="font-size:13px;padding:5px 15px">-<?=$product['discount']?>%</span>
          <?php elseif($product['stock']>0&&$product['stock']<=5): ?><span class="ts-badge ts-badge-y"><i class="fas fa-exclamation-circle"></i> Plus que <?=$product['stock']?></span>
          <?php elseif($product['stock']==0): ?><span class="ts-badge ts-badge-m">Rupture de stock</span>
          <?php endif; ?>
        </div>
      </div>
      <?php if(count($imgs)>1): ?>
      <div class="ts-thumbs">
        <?php foreach($imgs as $i=>$img): ?>
        <div class="ts-thumb <?=$i===0?'on':''?>" onclick="chImg('<?=htmlspecialchars($img)?>',this)">
          <img src="<?=htmlspecialchars($img)?>" alt="Image <?=$i+1?>">
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Infos -->
    <div>
      <?php if($category): ?><a href="<?=BASE_URL?>/catalogue?category=<?=$category['id']?>" class="ts-pdcat"><i class="fas fa-tag" style="margin-right:5px"></i><?=htmlspecialchars($category['name'])?></a><?php endif; ?>
      <h1 class="ts-pdtitle"><?=htmlspecialchars($product['name'])?></h1>

      <div class="ts-pdprice-wrap">
        <?php if($product['is_promotion']&&$product['old_price']>0): ?><div class="ts-pdold"><?=number_format($product['old_price'],0,',',' ')?> FC</div><?php endif; ?>
        <div class="ts-pdprice"><?=displayPrice($product['price'])?></div>
        <?php if($product['is_promotion']&&$product['discount']>0): ?><div style="margin-top:9px"><span class="ts-badge ts-badge-r" style="font-size:11.5px"><i class="fas fa-fire"></i> Promotion −<?=$product['discount']?>%</span></div><?php endif; ?>
      </div>

      <?php
      if($product['stock']>10){$sc='ok';$sl='<i class="fas fa-check-circle"></i> En stock';}
      elseif($product['stock']>0){$sc='low';$sl='<i class="fas fa-exclamation-circle"></i> Stock limité — '.$product['stock'].' disponible(s)';}
      else{$sc='out';$sl='<i class="fas fa-times-circle"></i> Rupture de stock';}
      ?>
      <div class="ts-pdstock <?=$sc?>"><?=$sl?></div>

      <?php if(!empty($product['short_description'])): ?><p class="ts-pddesc"><?=htmlspecialchars($product['short_description'])?></p><?php endif; ?>

      <?php if($product['stock']>0): ?>
      <div class="ts-qty-label">Quantité</div>
      <div class="ts-qty-row">
        <button class="ts-qty-btn" onclick="chQty(-1)"><i class="fas fa-minus"></i></button>
        <input type="number" id="quantity" value="1" min="1" max="<?=$product['stock']?>" class="ts-qty-inp">
        <button class="ts-qty-btn" onclick="chQty(1)"><i class="fas fa-plus"></i></button>
        <span style="font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:var(--txm);margin-left:5px">/ <?=$product['stock']?> en stock</span>
      </div>
      <div class="ts-pdacts">
        <button class="ts-btn ts-btn-p ts-btn-lg add-to-cart" style="flex:1;justify-content:center;border-radius:13px"
          data-id="<?=$product['id']?>" data-name="<?=htmlspecialchars($product['name'])?>" data-price="<?=$product['price']?>" data-image="<?=htmlspecialchars($imgs[0]??'')?>">
          <i class="fas fa-cart-plus"></i> Ajouter au panier
          <?php if($product['is_promotion']): ?><span style="background:rgba(255,255,255,.2);padding:2px 8px;border-radius:9px;font-size:11px;margin-left:4px">−<?=$product['discount']?>%</span><?php endif; ?>
        </button>
        <a href="<?=BASE_URL?>/cart" class="ts-btn ts-btn-o" style="border-radius:13px"><i class="fas fa-shopping-cart"></i></a>
      </div>
      <?php else: ?>
      <div class="ts-alert ts-alert-err"><i class="fas fa-info-circle"></i> Ce produit est actuellement indisponible. <a href="<?=BASE_URL?>/catalogue" style="color:var(--blue-l);margin-left:5px">Voir d'autres produits →</a></div>
      <?php endif; ?>

      <div class="ts-feat-strip">
        <div class="ts-feat-chip"><i class="fas fa-truck-fast"></i><div><strong>Livraison gratuite</strong><small>dès 50 000 FC</small></div></div>
        <div class="ts-feat-chip"><i class="fas fa-shield-check"></i><div><strong>Garantie 2 ans</strong><small>constructeur</small></div></div>
        <div class="ts-feat-chip"><i class="fas fa-rotate-left"></i><div><strong>Retours 30j</strong><small>satisfait ou échangé</small></div></div>
      </div>
    </div>
  </div>

  <!-- Onglets -->
  <div style="margin-bottom:58px">
    <div class="ts-tabs-nav">
      <button class="ts-tab-btn on" onclick="showTab('desc',this)">Description</button>
      <button class="ts-tab-btn" onclick="showTab('specs',this)">Caractéristiques</button>
    </div>
    <div id="tab-desc" class="ts-tab-panel on">
      <p><?=nl2br(htmlspecialchars($product['description']??'Aucune description disponible.'))?></p>
    </div>
    <div id="tab-specs" class="ts-tab-panel">
      <table class="ts-specs">
        <tr><th>Référence</th><td><?=htmlspecialchars($product['slug']??'—')?></td></tr>
        <tr><th>Catégorie</th><td><?=htmlspecialchars($product['cat']??'—')?></td></tr>
        <tr><th>Prix</th><td><?=displayPrice($product['price'])?></td></tr>
        <tr><th>Disponibilité</th><td><?=$product['stock']>0?'<span style="color:#4ade80">En stock ('.$product['stock'].' unités)</span>':'<span style="color:#f87171">Rupture de stock</span>'?></td></tr>
        <?php if($product['is_promotion']): ?><tr><th>Promotion</th><td><span class="ts-badge ts-badge-r">−<?=$product['discount']?>%</span></td></tr><?php endif; ?>
      </table>
    </div>
  </div>

  <!-- Produits similaires -->
  <?php if(!empty($similar_products)): ?>
  <h3 class="ts-section-title" style="font-size:22px;margin-bottom:20px">Produits similaires</h3>
  <div class="ts-similar-grid">
    <?php foreach($similar_products as $sp):
      $si=!empty($sp['image'])?gUrl($sp['image']):'https://via.placeholder.com/300?text=Produit';
    ?>
    <div class="ts-pcard">
      <div class="ts-pimg"><a href="<?=BASE_URL?>/product/<?=$sp['id']?>"><img src="<?=htmlspecialchars($si)?>" alt="<?=htmlspecialchars($sp['name'])?>"></a></div>
      <div class="ts-pbody">
        <div class="ts-pname"><?=htmlspecialchars($sp['name'])?></div>
        <div class="ts-pprice" style="font-size:16px"><?=displayPrice($sp['price'])?></div>
        <div class="ts-pact" style="margin-top:11px">
          <button class="ts-atc add-to-cart" data-id="<?=$sp['id']?>" data-name="<?=htmlspecialchars($sp['name'])?>" data-price="<?=$sp['price']?>" data-image="<?=htmlspecialchars($si)?>"><i class="fas fa-cart-plus"></i></button>
          <a href="<?=BASE_URL?>/product/<?=$sp['id']?>" class="ts-btn ts-btn-o" style="flex:1;justify-content:center;border-radius:11px;padding:9px"><i class="fas fa-eye"></i> Voir</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<script>
function chImg(src,el){document.getElementById('mainImg').src=src;document.querySelectorAll('.ts-thumb').forEach(t=>t.classList.remove('on'));el.classList.add('on');}
function chQty(d){var i=document.getElementById('quantity');i.value=Math.min(parseInt(i.max),Math.max(1,parseInt(i.value)+d));}
function showTab(id,btn){document.querySelectorAll('.ts-tab-panel').forEach(p=>p.classList.remove('on'));document.querySelectorAll('.ts-tab-btn').forEach(b=>b.classList.remove('on'));document.getElementById('tab-'+id).classList.add('on');btn.classList.add('on');}
</script>
