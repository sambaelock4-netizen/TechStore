<?php $cart_items=[]; ?>
<div class="ts-cart-wrap">
  <!-- Progress Stepper -->
  <div class="ts-stepper">
    <div class="ts-step active">
      <div class="ts-step-icon"><i class="fas fa-shopping-cart"></i></div>
      <div class="ts-step-label">Panier</div>
      <div class="ts-step-line"></div>
    </div>
    <div class="ts-step">
      <div class="ts-step-icon"><i class="fas fa-truck"></i></div>
      <div class="ts-step-label">Livraison</div>
      <div class="ts-step-line" style="left: 60%"></div>
    </div>
    <div class="ts-step">
      <div class="ts-step-icon"><i class="fas fa-credit-card"></i></div>
      <div class="ts-step-label">Paiement</div>
    </div>
  </div>

  <div id="cartContent">
    <div style="text-align:center;padding:100px 20px">
      <div style="width:54px;height:54px;border-radius:50%;border:4px solid rgba(255,160,122,.15);border-top-color:#ffa07a;animation:spin .8s cubic-bezier(0.4, 0, 0.2, 1) infinite;margin:0 auto 20px"></div>
      <p style="font-family:var(--fn);color:#64748b;font-size:14px;font-weight:500;letter-spacing:0.5px">Chargement de votre sélection…</p>
    </div>
  </div>
</div>
<style>
/* ── Variables & Setup ── */
:root {
  --ts-glass: rgba(255, 255, 255, 0.75);
  --ts-glass-sh: 0 12px 40px rgba(0, 123, 255, 0.08);
  --ts-border: 1px solid rgba(0, 123, 255, 0.12);
  --ts-gradient: linear-gradient(135deg, #ffa07a, #e07050);
}

@keyframes spin { to { transform: rotate(360deg); } }
@keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

/* ── Progress Stepper ── */
.ts-stepper { display: flex; justify-content: center; align-items: center; gap: 40px; margin-bottom: 48px; position: relative; }
.ts-step { display: flex; flex-direction: column; align-items: center; gap: 8px; position: relative; z-index: 2; }
.ts-step-icon { 
  width: 44px; height: 44px; border-radius: 50%; background: #fff; border: 2px solid #e2e8f0; 
  display: flex; align-items: center; justify-content: center; font-size: 16px; color: #94a3b8;
  transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.ts-step.active .ts-step-icon { background: var(--ts-gradient); border-color: transparent; color: #fff; box-shadow: 0 8px 20px rgba(255, 160, 122, 0.35); }
.ts-step-label { font-family: var(--fn); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
.ts-step.active .ts-step-label { color: #1a1d2e; }
.ts-step-line { position: absolute; top: 22px; width: 100px; height: 2px; background: #e2e8f0; z-index: 1; left: 60%; }

/* ── Layout ── */
.ts-cart-grid { display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start; animation: slideUp 0.6s ease-out; }

/* ── Cart Card (Glassmorphism) ── */
.ts-cart-card { 
  background: var(--ts-glass); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
  border: var(--ts-border); border-radius: 24px; overflow: hidden; box-shadow: var(--ts-glass-sh);
  transition: transform 0.3s;
}
.ts-cart-card-head { 
  padding: 24px 32px; border-bottom: 1px solid rgba(0, 123, 255, 0.06); 
  display: flex; align-items: center; justify-content: space-between;
  background: rgba(255,255,255,0.4);
}
.ts-cart-card-title { font-family: var(--fn-title); font-size: 18px; font-weight: 800; color: #1a1d2e; display: flex; align-items: center; gap: 12px; }

/* ── Premium Table ── */
.ts-ctable { width: 100%; border-collapse: separate; border-spacing: 0; }
.ts-ctable th { 
  padding: 16px 32px; font-family: var(--fn); font-size: 11px; font-weight: 700; color: #64748b; 
  text-transform: uppercase; letter-spacing: 1.8px; border-bottom: 1px solid rgba(0, 123, 255, 0.08);
  text-align: left; background: rgba(248, 250, 252, 0.5);
}
.ts-ctable td { padding: 24px 32px; border-bottom: 1px solid rgba(0, 123, 255, 0.05); vertical-align: middle; transition: background 0.2s; }
.ts-ctable tr:last-child td { border-bottom: none; }
.ts-ctable tr:hover td { background: rgba(0, 123, 255, 0.02); }

/* ── Product Info ── */
.ts-cart-prod { display: flex; align-items: center; gap: 20px; }
.ts-cart-img-wrap { 
  width: 80px; height: 80px; border-radius: 16px; overflow: hidden; 
  border: 1px solid rgba(0, 123, 255, 0.1); background: #f8fafc; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; transition: transform 0.3s;
}
.ts-cart-prod:hover .ts-cart-img-wrap { transform: scale(1.05); }
.ts-cart-img { width: 100%; height: 100%; object-fit: contain; padding: 10px; }
.ts-cart-img-ph { font-size: 28px; color: #cbd5e1; }
.ts-cart-name { font-family: var(--fn-title); font-weight: 800; color: #1a1d2e; font-size: 16px; line-height: 1.3; margin-bottom: 4px; }
.ts-cart-id { font-family: var(--fn); font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 5px; }

/* ── Controls ── */
.ts-qwrap { display: flex; align-items: center; background: #f1f5f9; padding: 4px; border-radius: 12px; border: 1px solid #e2e8f0; width: fit-content; }
.ts-qb { 
  width: 32px; height: 32px; border-radius: 8px; border: none; background: transparent; 
  color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; 
  font-size: 12px; transition: all 0.2s;
}
.ts-qb:hover { background: #fff; color: #ffa07a; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
.ts-qi { width: 44px; text-align: center; background: transparent; border: none; font-family: var(--fn); font-size: 14px; font-weight: 700; color: #1a1d2e; outline: none; }

/* ── Summary Card ── */
.ts-sum-card { 
  background: var(--ts-glass); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
  border: var(--ts-border); border-radius: 28px; overflow: hidden; box-shadow: var(--ts-glass-sh);
  position: sticky; top: 90px;
}
.ts-sum-head { 
  padding: 24px 32px; border-bottom: 1px solid rgba(0, 123, 255, 0.07); 
  font-family: var(--fn-title); font-size: 18px; font-weight: 800; color: #1a1d2e;
  background: rgba(255,255,255,0.4);
}
.ts-sum-body { padding: 32px; }
.ts-sum-row { display: flex; justify-content: space-between; padding: 12px 0; font-family: var(--fn); font-size: 14.5px; color: #64748b; }
.ts-sum-total-row { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 2px dashed #e2e8f0; }
.ts-sum-total-label { font-family: var(--fn-title); font-size: 18px; font-weight: 900; color: #1a1d2e; }
.ts-sum-total-val { font-family: var(--fn-title); font-size: 28px; font-weight: 900; background: var(--ts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

/* ── Buttons Premium ── */
.ts-btn { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 16px; }
.ts-btn-p { height: 54px; font-size: 16px; font-weight: 800; letter-spacing: 0.5px; }

/* ── Empty State ── */
.ts-cart-empty { padding: 80px 40px; }
.ts-cart-empty i { font-size: 72px; margin-bottom: 24px; opacity: 0.2; background: var(--ts-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

@media(max-width: 900px) { .ts-cart-grid { grid-template-columns: 1fr; } .ts-stepper { gap: 20px; } .ts-step-line { display: none; } }
@media(max-width: 600px) { 
  .ts-ctable th:nth-child(2), .ts-ctable td:nth-child(2) { display: none; }
  .ts-ctable th:nth-child(4), .ts-ctable td:nth-child(4) { display: none; }
  .ts-ctable td { padding: 16px; }
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
      +'<i class="fas fa-shopping-basket"></i>'
      +'<h3 style="font-family:var(--fn-title);font-size:24px;font-weight:900;color:#1a1d2e;margin-bottom:12px">Votre panier est vide</h3>'
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
    var imgCell = '<div class="ts-cart-img-wrap">' + (imgSrc
      ? '<img src="'+imgSrc+'" class="ts-cart-img" alt="'+item.name+'" onerror="this.parentNode.innerHTML=\'<i class=\\\"fas fa-image ts-cart-img-ph\\\"></i>\'">'
      : '<i class="fas fa-image ts-cart-img-ph"></i>') + '</div>';
    
    rows+='<tr data-id="'+item.id+'">'
      +'<td><div class="ts-cart-prod">'+imgCell+'<div><div class="ts-cart-name">'+item.name+'</div><div class="ts-cart-id"><i class="fas fa-hashtag" style="font-size:10px;opacity:0.6"></i>'+item.id+'</div></div></div></td>'
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
      +'<div class="ts-sum-head"><i class="fas fa-receipt" style="color:#ffa07a;margin-right:12px"></i>Résumé de commande</div>'
      +'<div class="ts-sum-body">'
        +'<div class="ts-sum-row"><span>Sous-total ('+cart.length+' article'+(cart.length>1?'s':'')+')</span><span id="cSub" style="font-weight:700;color:#1a1d2e">'+fmt(sub)+'</span></div>'
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
