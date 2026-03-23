<?php
if(!isset($_SESSION['user_id'])){header('Location:'.BASE_URL.'/login');exit;}
$uid     = $_SESSION['user_id'];
$error   = $checkout_error ?? '';
$oid     = $checkout_oid   ?? null;

try{$s=$pdo->prepare("SELECT * FROM addresses WHERE user_id=? ORDER BY is_default DESC,id DESC");$s->execute([$uid]);$addresses=$s->fetchAll();}
catch(PDOException $e){$addresses=[];}
try{$s=$pdo->prepare("SELECT * FROM users WHERE id=? LIMIT 1");$s->execute([$uid]);$user=$s->fetch();}
catch(PDOException $e){$user=[];}
?>
<div class="ts-co-wrap">
<h1 class="ts-page-title"><i class="fas fa-credit-card" style="opacity:.8;margin-right:10px;font-size:24px"></i>Finaliser la commande</h1>

<?php if($error): ?>
<div class="ts-co-alert"><i class="fas fa-exclamation-circle"></i> <?=htmlspecialchars($error)?></div>
<?php endif; ?>

<?php if($oid): ?>
<!-- ── Succès ── -->
<div class="ts-co-success-card">
  <div class="ts-co-success-ico"><i class="fas fa-check"></i></div>
  <h2 class="ts-co-success-title">Commande confirmée !</h2>
  <p class="ts-co-success-sub">
    Votre commande <strong style="color:#4ade80">#<?=$oid?></strong> a bien été enregistrée.<br>
    Notre équipe vous contactera pour la livraison sous 24–48h.
  </p>
  <div style="display:flex;gap:13px;justify-content:center;flex-wrap:wrap">
    <a href="<?=BASE_URL?>/orders" class="ts-co-btn ts-co-btn-b"><i class="fas fa-bag-shopping"></i> Voir mes commandes</a>
    <a href="<?=BASE_URL?>/catalogue" class="ts-co-btn ts-co-btn-o"><i class="fas fa-shop"></i> Continuer mes achats</a>
  </div>
</div>
<script>localStorage.removeItem('techstore_cart');if(window.updateCartCount)window.updateCartCount();</script>

<?php else: ?>

<div class="ts-co-grid">
  <!-- Col gauche -->
  <div>
    <!-- Adresse -->
    <div class="ts-co-card">
      <div class="ts-co-head"><i class="fas fa-location-dot" style="color:#ffa07a"></i> Adresse de livraison</div>
      <div class="ts-co-body">
        <?php if(empty($addresses)): ?>
        <div class="ts-co-info-alert">
          <i class="fas fa-info-circle"></i> Aucune adresse enregistrée.
          <a href="<?=BASE_URL?>/account" style="color:#3d9cff;font-weight:600;margin-left:4px">Ajouter une adresse →</a>
        </div>
        <?php else: ?>
        <div class="ts-addr-list">
          <?php foreach($addresses as $a): ?>
          <div class="ts-addr-opt <?=$a['is_default']?'sel':''?>" onclick="selAddr(this,'<?=$a['id']?>')">
            <div class="ts-addr-radio"></div>
            <div class="ts-addr-info">
              <div class="ts-addr-name">
                <?=htmlspecialchars($a['name']??'Adresse')?>
                <?php if($a['is_default']): ?><span class="ts-addr-badge-def">Défaut</span><?php endif; ?>
              </div>
              <div class="ts-addr-detail">
                <?=htmlspecialchars($a['address'])?>, <?=htmlspecialchars($a['city'])?>
                <?php if(!empty($a['phone'])): ?> · <?=htmlspecialchars($a['phone'])?><?php endif; ?>
              </div>
            </div>
            <div class="ts-addr-check"><i class="fas fa-check"></i></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Notes -->
    <div class="ts-co-card">
      <div class="ts-co-head"><i class="fas fa-comment-alt" style="color:#007bff"></i> Notes <span style="font-size:11px;font-style:italic;color:rgba(80,90,130,.5);font-weight:400">(optionnel)</span></div>
      <div class="ts-co-body">
        <div style="position:relative">
          <i class="fas fa-pen" style="position:absolute;left:14px;top:14px;color:rgba(0,123,255,.4);font-size:13px"></i>
          <textarea id="orderNotes" class="ts-co-textarea" rows="3" placeholder="Instructions de livraison, créneau préféré…"></textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- Col droite : récapitulatif -->
  <div>
    <div class="ts-co-card ts-co-sum-card">
      <div class="ts-co-head"><i class="fas fa-receipt" style="color:#ffa07a"></i> Récapitulatif</div>
      <div class="ts-co-body">
        <div id="sumItems">
          <div style="text-align:center;padding:20px">
            <div style="width:32px;height:32px;border-radius:50%;border:2.5px solid rgba(255,160,122,.2);border-top-color:#ffa07a;animation:spin .7s linear infinite;margin:0 auto"></div>
          </div>
        </div>

        <form method="POST" id="ordForm">
          <input type="hidden" name="place_order" value="1">
          <input type="hidden" name="address_id"  id="fAddr" value="<?=!empty($addresses)?$addresses[0]['id']:''?>">
          <input type="hidden" name="cart_data"   id="fCart" value="">
          <input type="hidden" name="notes"        id="fNotes" value="">
        </form>

        <div class="ts-co-total-row">
          <span class="ts-co-total-lbl">Total TTC</span>
          <span class="ts-co-total-val" id="sumTot">—</span>
        </div>

        <button class="ts-co-btn-submit" id="placeBtn" disabled onclick="submitOrder()">
          <i class="fas fa-shield-check"></i> Confirmer la commande
        </button>
        <div class="ts-co-secure"><i class="fas fa-lock"></i> Paiement sécurisé · Données protégées</div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
</div>

<style>
@keyframes spin{to{transform:rotate(360deg)}}
.ts-co-wrap{max-width:1100px;margin:0 auto;padding:38px 28px 80px}
.ts-co-grid{display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;margin-top:20px}
.ts-co-card{background:#fff;border:1px solid rgba(0,123,255,.13);border-radius:20px;overflow:hidden;box-shadow:0 2px 20px rgba(0,123,255,.07);margin-bottom:20px}
.ts-co-head{padding:16px 22px;border-bottom:1px solid rgba(0,123,255,.09);font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:14px;font-weight:700;color:#1a1d2e;display:flex;align-items:center;gap:9px;background:linear-gradient(135deg,rgba(0,123,255,.03),rgba(255,160,122,.02))}
.ts-co-body{padding:20px 22px}
.ts-co-sum-card{position:sticky;top:80px}
.ts-addr-list{display:flex;flex-direction:column;gap:10px}
.ts-addr-opt{display:flex;align-items:center;gap:14px;padding:14px 16px;border:1.5px solid rgba(0,123,255,.14);border-radius:14px;cursor:pointer;transition:all .22s;background:#fff}
.ts-addr-opt:hover{border-color:rgba(0,123,255,.35);background:rgba(0,123,255,.02)}
.ts-addr-opt.sel{border-color:#007bff;background:rgba(0,123,255,.04);box-shadow:0 0 0 3px rgba(0,123,255,.1)}
.ts-addr-radio{width:18px;height:18px;border-radius:50%;border:2px solid rgba(0,123,255,.3);flex-shrink:0;transition:all .22s;position:relative}
.ts-addr-opt.sel .ts-addr-radio{border-color:#007bff;background:#007bff}
.ts-addr-opt.sel .ts-addr-radio::after{content:'';position:absolute;top:3px;left:3px;width:6px;height:6px;border-radius:50%;background:#fff}
.ts-addr-info{flex:1;min-width:0}
.ts-addr-name{font-family:'Times New Roman',Times,serif;font-weight:700;color:#1a1d2e;font-size:13.5px;margin-bottom:3px;display:flex;align-items:center;gap:8px}
.ts-addr-detail{font-family:'Times New Roman',Times,serif;font-size:12.5px;color:rgba(80,90,130,.6);line-height:1.5}
.ts-addr-badge-def{font-size:9px;font-weight:700;background:rgba(0,123,255,.1);color:#007bff;padding:2px 8px;border-radius:20px;text-transform:uppercase;letter-spacing:.5px}
.ts-addr-check{width:24px;height:24px;border-radius:50%;background:rgba(0,123,255,.08);display:flex;align-items:center;justify-content:center;font-size:10px;color:#007bff;flex-shrink:0;opacity:0;transition:all .22s}
.ts-addr-opt.sel .ts-addr-check{opacity:1;background:#007bff;color:#fff}
.ts-co-textarea{width:100%;padding:12px 14px 12px 38px;border:1.5px solid rgba(0,123,255,.18);border-radius:12px;font-family:'Times New Roman',Times,serif;font-size:13.5px;color:#1a1d2e;background:#fff;resize:vertical;min-height:80px;transition:border-color .2s;box-sizing:border-box}
.ts-co-textarea:focus{outline:none;border-color:#007bff;box-shadow:0 0 0 3px rgba(0,123,255,.1)}
.ts-co-line{display:flex;justify-content:space-between;align-items:flex-start;padding:10px 0;border-bottom:1px solid rgba(0,123,255,.07);font-family:'Times New Roman',Times,serif;font-size:13px;gap:12px}
.ts-co-line:last-of-type{border-bottom:none}
.ts-co-line span{color:rgba(80,90,130,.7);line-height:1.4}
.ts-co-line strong{font-weight:700;color:#1a1d2e;white-space:nowrap}
.ts-co-line-ship{color:#16a34a!important;font-weight:600}
.ts-co-total-row{display:flex;justify-content:space-between;align-items:center;padding:16px 0 14px;border-top:1px solid rgba(0,123,255,.12);margin-top:4px}
.ts-co-total-lbl{font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:15px;font-weight:700;color:#1a1d2e}
.ts-co-total-val{font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:22px;font-weight:800;background:linear-gradient(135deg,#ffa07a,#ffa07a,#007bff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.ts-co-btn-submit{width:100%;padding:15px;border-radius:14px;font-family:'Times New Roman',Times,serif;font-size:15px;font-weight:700;border:none;cursor:pointer;background:linear-gradient(135deg,#e07050,#ffa07a,#ffa07a);color:#fff;box-shadow:0 4px 20px rgba(255,160,122,.35);transition:all .24s;display:flex;align-items:center;justify-content:center;gap:10px}
.ts-co-btn-submit:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 6px 28px rgba(255,160,122,.45)}
.ts-co-btn-submit:disabled{opacity:.45;cursor:not-allowed;transform:none}
.ts-co-btn,.ts-co-btn-b,.ts-co-btn-o{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:12px;font-family:'Times New Roman',Times,serif;font-size:13.5px;font-weight:600;text-decoration:none;transition:all .24s}
.ts-co-btn-b{background:linear-gradient(135deg,#0056b3,#007bff);color:#fff;box-shadow:0 4px 14px rgba(0,123,255,.25)}
.ts-co-btn-b:hover{transform:translateY(-2px);color:#fff}
.ts-co-btn-o{background:rgba(0,123,255,.08);border:1px solid rgba(0,123,255,.2);color:#007bff}
.ts-co-btn-o:hover{background:rgba(0,123,255,.15);color:#007bff}
.ts-co-secure{text-align:center;font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:rgba(80,90,130,.45);display:flex;align-items:center;justify-content:center;gap:5px;margin-top:12px}
.ts-co-secure i{color:rgba(0,123,255,.5)}
.ts-co-alert{display:flex;align-items:center;gap:10px;padding:14px 18px;background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.25);border-radius:12px;color:#dc2626;font-family:'Times New Roman',Times,serif;font-size:13.5px;margin-bottom:20px}
.ts-co-info-alert{display:flex;align-items:center;gap:9px;padding:13px 16px;background:rgba(0,123,255,.05);border:1px solid rgba(0,123,255,.16);border-radius:12px;color:rgba(40,50,100,.7);font-family:'Times New Roman',Times,serif;font-size:13px}
.ts-co-success-card{max-width:580px;margin:30px auto;background:#fff;border:1px solid rgba(0,123,255,.13);border-radius:24px;padding:48px 36px;text-align:center;box-shadow:0 8px 40px rgba(0,123,255,.1)}
.ts-co-success-ico{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#4ade80);display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;margin:0 auto 20px;box-shadow:0 8px 24px rgba(74,222,128,.3)}
.ts-co-success-title{font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:26px;font-weight:800;color:#1a1d2e;margin-bottom:10px}
.ts-co-success-sub{font-family:'Times New Roman',Times,serif;font-size:14px;color:rgba(80,90,130,.7);margin-bottom:28px;line-height:1.7}
@media(max-width:860px){.ts-co-grid{grid-template-columns:1fr}}
@media(max-width:600px){.ts-co-wrap{padding:24px 14px 60px}.ts-co-card{border-radius:16px}}
</style>

<script>
function fmt(p){ return Math.round(p*655.957).toString().replace(/\B(?=(\d{3})+(?!\d))/g,' ')+' FC'; }

document.addEventListener('DOMContentLoaded', function(){
  var cart  = JSON.parse(localStorage.getItem('techstore_cart')||'[]');
  var total = 0, html = '';
  if(!cart.length){
    html = '<p style="text-align:center;padding:20px;font-family:\'Inter\',sans-serif;color:rgba(80,90,130,.6);font-size:13px">Panier vide</p>';
    document.getElementById('placeBtn').disabled = true;
  } else {
    cart.forEach(function(it){
      var t = it.price * it.quantity; total += t;
      html += '<div class="ts-co-line"><span>'+it.name+' <span style="font-weight:600;color:#1a1d2e">×'+it.quantity+'</span></span><strong>'+fmt(t)+'</strong></div>';
    });
    html += '<div class="ts-co-line"><span>Livraison</span><span class="ts-co-line-ship"><i class="fas fa-check-circle" style="font-size:10px;margin-right:3px"></i>Offerte</span></div>';
    document.getElementById('sumTot').textContent = fmt(total);
    document.getElementById('fCart').value = JSON.stringify(cart);
    document.getElementById('placeBtn').disabled = false;
  }
  document.getElementById('sumItems').innerHTML = html;
});

function submitOrder(){
  document.getElementById('fNotes').value = document.getElementById('orderNotes').value;
  document.getElementById('ordForm').submit();
}

function selAddr(el, id){
  document.querySelectorAll('.ts-addr-opt').forEach(function(a){ a.classList.remove('sel'); });
  el.classList.add('sel');
  document.getElementById('fAddr').value = id;
}
</script>
