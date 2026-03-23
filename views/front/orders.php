<?php
if(!isset($_SESSION['user_id'])){header('Location:'.BASE_URL.'/login');exit;}
$uid=$_SESSION['user_id'];
try{$s=$pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");$s->execute([$uid]);$orders=$s->fetchAll();}catch(PDOException $e){$orders=[];}
function ordItems($pdo,$oid){try{$s=$pdo->prepare("SELECT oi.*,p.name pname FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");$s->execute([$oid]);return $s->fetchAll();}catch(PDOException $e){return[];}}
$statMap=['en_attente'=>['#fbbf24','En attente','fas fa-clock'],'confirme'=>['#3d9cff','Confirmé','fas fa-check'],'en_preparation'=>['#ffa07a','Préparation','fas fa-cog'],'expedie'=>['#a07aff','Expédié','fas fa-truck'],'livre'=>['#4ade80','Livré','fas fa-check-double'],'annule'=>['#f87171','Annulé','fas fa-ban']];
?>
<div class="ts-ord-wrap">
  <h1 class="ts-page-title"><i class="fas fa-bag-shopping" style="opacity:.8;margin-right:10px;font-size:24px"></i>Mes Commandes</h1>
  <?php if(empty($orders)): ?>
  <div class="ts-ord-card">
    <div style="text-align:center;padding:68px 20px">
      <i class="fas fa-bag" style="font-size:50px;display:block;margin-bottom:15px;opacity:.16;background:var(--g-main);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text"></i>
      <h3 style="font-family:'Times New Roman',Times,serif;font-size:22px;font-style:italic;color:var(--txs);margin-bottom:9px">Aucune commande</h3>
      <p style="font-family:'Times New Roman',Times,serif;font-style:italic;color:var(--txm);margin-bottom:24px">Vous n'avez pas encore passé de commande.</p>
      <a href="<?=BASE_URL?>/catalogue" class="ts-btn ts-btn-p"><i class="fas fa-shop"></i> Découvrir nos produits</a>
    </div>
  </div>
  <?php else: foreach($orders as $o):
    $sm=$statMap[$o['status']]??['#888','Inconnu','fas fa-circle'];
    $items=ordItems($pdo,$o['id']);
  ?>
  <div class="ts-ord-card">
    <div class="ts-ord-hd" onclick="togOrd(<?=$o['id']?>)">
      <div>
        <div class="ts-ord-num">Commande #<?=$o['order_number']??$o['id']?></div>
        <div class="ts-ord-date"><i class="fas fa-calendar" style="margin-right:4px;opacity:.5"></i><?=date('d/m/Y à H:i',strtotime($o['created_at']))?></div>
      </div>
      <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
        <span style="background:rgba(0,0,0,.2);border:1px solid <?=$sm[0]?>33;color:<?=$sm[0]?>;padding:5px 15px;border-radius:18px;font-family:'Times New Roman',Times,serif;font-size:12px;font-style:italic">
          <i class="<?=$sm[2]?>" style="margin-right:4px"></i><?=$sm[1]?>
        </span>
        <span class="ts-ord-total"><?=displayPrice($o['total_amount'])?></span>
        <i class="fas fa-chevron-down" id="chv-<?=$o['id']?>" style="color:var(--txm);transition:transform .3s;font-size:12px"></i>
      </div>
    </div>
    <div class="ts-ord-body" id="ord-<?=$o['id']?>">
      <?php if(!empty($items)): ?>
      <table style="width:100%;border-collapse:collapse">
        <thead><tr>
          <th style="padding:9px 0;font-family:'Times New Roman',Times,serif;font-size:10px;font-style:italic;color:var(--blue-l);text-transform:uppercase;letter-spacing:1.2px;border-bottom:1px solid var(--bd);text-align:left">Produit</th>
          <th style="padding:9px;font-family:'Times New Roman',Times,serif;font-size:10px;font-style:italic;color:var(--blue-l);text-transform:uppercase;letter-spacing:1.2px;border-bottom:1px solid var(--bd);text-align:center">Qté</th>
          <th style="padding:9px;font-family:'Times New Roman',Times,serif;font-size:10px;font-style:italic;color:var(--blue-l);text-transform:uppercase;letter-spacing:1.2px;border-bottom:1px solid var(--bd);text-align:right">Total</th>
        </tr></thead>
        <tbody>
        <?php foreach($items as $it): ?>
        <tr>
          <td style="padding:11px 0;font-family:'Times New Roman',Times,serif;font-weight:700;color:#fff;border-bottom:1px solid rgba(100,140,255,.06)"><?=htmlspecialchars($it['pname'])?></td>
          <td style="padding:11px;border-bottom:1px solid rgba(100,140,255,.06);text-align:center"><span class="ts-badge ts-badge-b"><?=$it['quantity']?>×</span></td>
          <td style="padding:11px;border-bottom:1px solid rgba(100,140,255,.06);text-align:right;font-family:'Times New Roman',Times,serif;font-weight:700;color:#fff"><?=displayPrice($it['unit_price']*$it['quantity'])?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <div style="display:flex;justify-content:flex-end;padding-top:14px;border-top:1px solid var(--bd);margin-top:9px">
        <span style="font-family:'Times New Roman',Times,serif;font-size:17px;font-weight:700;color:#fff">Total : <span style="background:var(--g-main);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text"><?=displayPrice($o['total_amount'])?></span></span>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>
<script>
function togOrd(id){var b=document.getElementById('ord-'+id),c=document.getElementById('chv-'+id),o=b.classList.contains('open');b.classList.toggle('open');if(c)c.style.transform=o?'':'rotate(180deg)';}
</script>
