<?php $cart_items=[]; ?>
<div class="ts-cart-wrap">
  <h1 class="ts-page-title"><i class="fas fa-cart-shopping" style="opacity:.8;margin-right:10px;font-size:26px"></i>Mon Panier</h1>
  <div id="cartContent">
    <div style="text-align:center;padding:80px 20px">
      <div style="width:48px;height:48px;border-radius:50%;border:3px solid rgba(255,160,122,.2);border-top-color:#ffa07a;animation:spin .7s linear infinite;margin:0 auto 14px"></div>
      <p style="font-family:'Times New Roman',Times,serif;color:var(--txm);font-size:13px">Chargement du panier…</p>
    </div>
  </div>
</div>
<style>
@keyframes spin{to{transform:rotate(360deg)}}
/* ── Cart layout ── */
.ts-cart-grid{display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start}
/* ── Cart card ── */
.ts-cart-card{background:#fff;border:1px solid rgba(0,123,255,.13);border-radius:20px;overflow:hidden;box-shadow:0 2px 20px rgba(0,123,255,.08)}
.ts-cart-card-head{padding:18px 24px;border-bottom:1px solid rgba(0,123,255,.09);display:flex;align-items:center;justify-content:space-between;gap:10px;background:linear-gradient(135deg,rgba(0,123,255,.03),rgba(255,160,122,.02))}
.ts-cart-card-title{font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:15px;font-weight:700;color:#1a1d2e;display:flex;align-items:center;gap:9px}
/* ── Table ── */
.ts-ctable{width:100%;border-collapse:collapse}
.ts-ctable th{padding:11px 20px;font-family:'Times New Roman',Times,serif;font-size:10px;font-weight:700;color:rgba(0,123,255,.7);text-transform:uppercase;letter-spacing:1.5px;border-bottom:1px solid rgba(0,123,255,.09);background:#fafbff;white-space:nowrap}
.ts-ctable td{padding:16px 20px;border-bottom:1px solid rgba(0,123,255,.06);vertical-align:middle}
.ts-ctable tr:last-child td{border-bottom:none}
.ts-ctable tr:hover td{background:rgba(0,123,255,.02)}
/* ── Product cell ── */
.ts-cart-prod{display:flex;align-items:center;gap:14px}
.ts-cart-img{width:58px;height:58px;border-radius:12px;object-fit:cover;border:1px solid rgba(0,123,255,.12);flex-shrink:0;background:#f0f2f8}
.ts-cart-img-ph{width:58px;height:58px;border-radius:12px;background:linear-gradient(135deg,rgba(0,123,255,.08),rgba(255,160,122,.06));border:1px solid rgba(0,123,255,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:22px;color:rgba(0,123,255,.4)}
.ts-cart-name{font-family:'Times New Roman',Times,serif;font-weight:700;color:#1a1d2e;font-size:14px;line-height:1.3}
.ts-cart-id{font-family:'Times New Roman',Times,serif;font-size:11px;color:rgba(80,90,130,.45);margin-top:2px}
/* ── Price ── */
.ts-cart-price{font-family:'Times New Roman',Times,serif;font-size:13.5px;font-weight:500;color:rgba(40,50,100,.7);white-space:nowrap}
.ts-cart-total{font-family:'Times New Roman',Times,serif;font-size:14px;font-weight:700;color:#1a1d2e;white-space:nowrap}
/* ── Qty control ── */
.ts-qwrap{display:flex;align-items:center;gap:6px}
.ts-qb{width:30px;height:30px;border-radius:8px;border:1px solid rgba(0,123,255,.18);background:#f0f2f8;color:#2a3060;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;transition:all .2s;flex-shrink:0}
.ts-qb:hover{background:rgba(255,160,122,.1);border-color:rgba(255,160,122,.3);color:#ffa07a}
.ts-qi{width:48px;text-align:center;background:#f0f2f8;border:1px solid rgba(0,123,255,.16);border-radius:8px;color:#1a1d2e;font-family:'Times New Roman',Times,serif;font-size:13px;font-weight:600;padding:5px 0}
/* ── Remove btn ── */
.ts-rm{width:32px;height:32px;border-radius:8px;background:rgba(255,160,122,.07);border:1px solid rgba(255,160,122,.18);color:#f87171;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;transition:all .2s}
.ts-rm:hover{background:rgba(255,160,122,.18);color:#ffa07a;transform:scale(1.08)}
/* ── Summary card ── */
.ts-sum-card{background:#fff;border:1px solid rgba(0,123,255,.13);border-radius:20px;overflow:hidden;box-shadow:0 2px 20px rgba(0,123,255,.08);position:sticky;top:80px}
.ts-sum-head{padding:18px 24px;border-bottom:1px solid rgba(0,123,255,.09);font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:15px;font-weight:700;color:#1a1d2e;background:linear-gradient(135deg,rgba(0,123,255,.03),rgba(255,160,122,.02))}
.ts-sum-body{padding:20px 24px}
.ts-sum-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;font-family:'Times New Roman',Times,serif;font-size:13.5px;color:rgba(80,90,130,.65);border-bottom:1px solid rgba(0,123,255,.06)}
.ts-sum-row:last-of-type{border-bottom:none}
.ts-sum-row span:last-child{font-weight:500;color:#2a3060}
.ts-sum-divider{height:1px;background:linear-gradient(90deg,transparent,rgba(0,123,255,.15),transparent);margin:14px 0}
.ts-sum-total-row{display:flex;justify-content:space-between;align-items:center;padding:4px 0}
.ts-sum-total-label{font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:16px;font-weight:700;color:#1a1d2e}
.ts-sum-total-val{font-family:'Times New Roman',Times,serif,'Inter',sans-serif;font-size:22px;font-weight:800;background:linear-gradient(135deg,#ffa07a,#ffa07a,#007bff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.ts-sum-footer{padding:0 24px 20px;display:flex;flex-direction:column;gap:10px}
.ts-sum-secure{text-align:center;font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:rgba(80,90,130,.45);display:flex;align-items:center;justify-content:center;gap:5px}
/* ── Empty ── */
.ts-cart-empty{padding:60px 28px;text-align:center}
.ts-cart-empty i{font-size:56px;display:block;margin-bottom:16px;background:linear-gradient(135deg,rgba(255,160,122,.3),rgba(0,123,255,.3));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
/* ── Buttons ── */
.ts-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:12px;font-family:'Times New Roman',Times,serif;font-size:13.5px;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .24s;white-space:nowrap}
.ts-btn-p{background:linear-gradient(135deg,#e07050,#ffa07a,#ffa07a);color:#fff;box-shadow:0 4px 16px rgba(255,160,122,.3)}
.ts-btn-p:hover{transform:translateY(-2px);box-shadow:0 6px 24px rgba(255,160,122,.4);color:#fff}
.ts-btn-b{background:linear-gradient(135deg,#0056b3,#007bff);color:#fff;box-shadow:0 4px 16px rgba(0,123,255,.25)}
.ts-btn-b:hover{transform:translateY(-2px);box-shadow:0 6px 24px rgba(0,123,255,.38);color:#fff}
.ts-btn-g{background:rgba(0,123,255,.07);border:1px solid rgba(0,123,255,.15);color:rgba(40,50,100,.7)}
.ts-btn-g:hover{background:rgba(0,123,255,.13);color:#007bff}
.ts-btn-sm{padding:7px 14px;font-size:12.5px;border-radius:9px}
.ts-badge{display:inline-flex;align-items:center;justify-content:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700;font-family:'Times New Roman',Times,serif}
.ts-badge-r{background:rgba(255,160,122,.12);color:#ffa07a}
/* ── Responsive ── */
@media(max-width:900px){.ts-cart-grid{grid-template-columns:1fr}}
@media(max-width:600px){
  .ts-cart-wrap{padding:28px 14px 60px}
  .ts-ctable th:nth-child(2),.ts-ctable td:nth-child(2){display:none}
  .ts-ctable th:nth-child(3),.ts-ctable td:nth-child(3){display:none}
  .ts-cart-card-head{padding:14px 16px}
  .ts-ctable th,.ts-ctable td{padding:12px 12px}
}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){ renderCart(); });

function fmt(p){ return Math.round(p*655.957).toString().replace(/\B(?=(\d{3})+(?!\d))/g,' ')+' FC'; }
function gC(){ return JSON.parse(localStorage.getItem('techstore_cart')||'[]'); }
function sC(c){ localStorage.setItem('techstore_cart',JSON.stringify(c)); if(window.updateCartCount) window.updateCartCount(); }

function renderCart(){
  var cart=gC(), el=document.getElementById('cartContent');
  if(!cart.length){
    el.innerHTML='<div class="ts-cart-card"><div class="ts-cart-empty">'
      +'<i class="fas fa-cart-xmark"></i>'
      +'<h3 style="font-family:\'Plus Jakarta Sans\',\'Inter\',sans-serif;font-size:22px;font-weight:700;color:#1a1d2e;margin-bottom:8px">Votre panier est vide</h3>'
      +'<p style="font-family:\'Inter\',sans-serif;color:rgba(80,90,130,.6);margin-bottom:24px;font-size:14px">Découvrez nos produits et ajoutez-les à votre panier.</p>'
      +'<a href="<?=BASE_URL?>/catalogue" class="ts-btn ts-btn-p"><i class="fas fa-shop"></i> Découvrir nos produits</a>'
      +'</div></div>';
    return;
  }

  var sub=0, rows='';
  cart.forEach(function(item){
    var t=item.price*item.quantity; sub+=t;
    var baseUpload='<?=UPLOAD_URL?>/';
    var imgSrc=item.image ? (item.image.startsWith('http') ? item.image : baseUpload+item.image) : '';
    var imgCell = imgSrc
      ? '<img src="'+imgSrc+'" class="ts-cart-img" alt="'+item.name+'" onerror="this.parentNode.innerHTML=\'<div class=ts-cart-img-ph><i class=\\\"fas fa-image\\\"></i></div>\'">'
      : '<div class="ts-cart-img-ph"><i class="fas fa-image"></i></div>';
    rows+='<tr data-id="'+item.id+'">'
      +'<td><div class="ts-cart-prod">'+imgCell+'<div><div class="ts-cart-name">'+item.name+'</div><div class="ts-cart-id">Réf. #'+item.id+'</div></div></div></td>'
      +'<td><div class="ts-cart-price">'+fmt(item.price)+'</div></td>'
      +'<td><div class="ts-qwrap"><button class="ts-qb btn-minus" data-id="'+item.id+'"><i class="fas fa-minus"></i></button><input class="ts-qi cart-qty-input" data-id="'+item.id+'" value="'+item.quantity+'" min="1"><button class="ts-qb btn-plus" data-id="'+item.id+'"><i class="fas fa-plus"></i></button></div></td>'
      +'<td><div class="ts-cart-total">'+fmt(t)+'</div></td>'
      +'<td><button class="ts-rm remove-from-cart" data-id="'+item.id+'" title="Retirer"><i class="fas fa-trash-can"></i></button></td>'
      +'</tr>';
  });

  var loggedIn=<?=isset($_SESSION['user_id'])?'true':'false'?>;
  var chkBtn=loggedIn
    ?'<a href="<?=BASE_URL?>/checkout" class="ts-btn ts-btn-p" style="width:100%;justify-content:center;padding:14px;font-size:14.5px"><i class="fas fa-credit-card"></i> Passer la commande</a>'
    :'<a href="<?=BASE_URL?>/login" class="ts-btn ts-btn-b" style="width:100%;justify-content:center;padding:14px;font-size:14.5px"><i class="fas fa-arrow-right-to-bracket"></i> Se connecter pour commander</a>';

  el.innerHTML='<div class="ts-cart-grid">'
    /* ── Left: items ── */
    +'<div><div class="ts-cart-card">'
      +'<div class="ts-cart-card-head">'
        +'<div class="ts-cart-card-title"><i class="fas fa-basket-shopping" style="color:#ffa07a"></i> Articles <span class="ts-badge ts-badge-r">'+cart.length+'</span></div>'
        +'<button onclick="clrCart()" class="ts-btn ts-btn-g ts-btn-sm"><i class="fas fa-trash"></i> Vider</button>'
      +'</div>'
      +'<div style="overflow-x:auto">'
        +'<table class="ts-ctable">'
          +'<thead><tr><th>Produit</th><th>Prix unitaire</th><th>Quantité</th><th>Total</th><th></th></tr></thead>'
          +'<tbody>'+rows+'</tbody>'
        +'</table>'
      +'</div>'
      +'<div style="padding:14px 20px;border-top:1px solid rgba(0,123,255,.07)">'
        +'<a href="<?=BASE_URL?>/catalogue" class="ts-btn ts-btn-g ts-btn-sm"><i class="fas fa-arrow-left"></i> Continuer mes achats</a>'
      +'</div>'
    +'</div></div>'
    /* ── Right: summary ── */
    +'<div><div class="ts-sum-card">'
      +'<div class="ts-sum-head"><i class="fas fa-receipt" style="color:#ffa07a;margin-right:9px"></i>Résumé de commande</div>'
      +'<div class="ts-sum-body">'
        +'<div class="ts-sum-row"><span>Sous-total ('+cart.length+' article'+(cart.length>1?'s':'')+')</span><span id="cSub">'+fmt(sub)+'</span></div>'
        +'<div class="ts-sum-row"><span>Frais de livraison</span><span style="color:#16a34a;font-weight:600"><i class="fas fa-check-circle" style="font-size:10px;margin-right:3px"></i>Offerts</span></div>'
        +'<div class="ts-sum-divider"></div>'
        +'<div class="ts-sum-total-row"><div class="ts-sum-total-label">Total TTC</div><div class="ts-sum-total-val" id="cTot">'+fmt(sub)+'</div></div>'
      +'</div>'
      +'<div class="ts-sum-footer">'
        +chkBtn
        +'<div class="ts-sum-secure"><i class="fas fa-lock" style="color:rgba(0,123,255,.6)"></i> Paiement sécurisé · Données protégées</div>'
      +'</div>'
    +'</div></div>'
  +'</div>';

  attachEv();
}

function attachEv(){
  document.querySelectorAll('.btn-plus').forEach(function(b){ b.addEventListener('click',function(){ upd(b.dataset.id,1); }); });
  document.querySelectorAll('.btn-minus').forEach(function(b){ b.addEventListener('click',function(){ upd(b.dataset.id,-1); }); });
  document.querySelectorAll('.cart-qty-input').forEach(function(i){ i.addEventListener('change',function(){ setQ(i.dataset.id,parseInt(i.value)||1); }); });
  document.querySelectorAll('.remove-from-cart').forEach(function(b){ b.addEventListener('click',function(){ rmIt(b.dataset.id); }); });
}
function upd(id,d){ var c=gC(),it=c.find(function(x){return x.id==id;}); if(it&&it.quantity+d>=1){it.quantity+=d;sC(c);renderCart();} }
function setQ(id,q){ var c=gC(),it=c.find(function(x){return x.id==id;}); if(it){it.quantity=Math.max(1,q);sC(c);renderCart();} }
function rmIt(id){ sC(gC().filter(function(x){return x.id!=id;})); renderCart(); }
function clrCart(){ if(confirm('Vider le panier ?')){ sC([]); renderCart(); } }
</script>
