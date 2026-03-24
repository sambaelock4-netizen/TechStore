/**
 * ==================================================================================
 * TECHSTORE — Script Principal (Main JS)
 * ==================================================================================
 * Gère les interactions fondamentales de l'interface :
 * 1. Initialisation des composants UI (Carrousels, Toasts).
 * 2. Gestion du menu mobile et des menus déroulants.
 * 3. Logique de base du panier et des notifications.
 * ==================================================================================
 */

/* ── PANIER ── */
function getCart(){ return JSON.parse(localStorage.getItem('techstore_cart')||'[]'); }
function saveCart(c){ localStorage.setItem('techstore_cart',JSON.stringify(c)); updBadge(); }
window.updateCartCount = updBadge;

function updBadge(){
  var cart=getCart(), n=cart.reduce(function(s,i){return s+i.quantity;},0);
  ['headerCartCount','mobCartCount'].forEach(function(id){
    var el=document.getElementById(id); if(!el) return;
    el.textContent = n>99?'99+':n;
    el.style.display = n>0?'flex':'none';
  });
}

/* ── TOAST ── */
function showToast(msg){
  var t=document.getElementById('tsToast'),m=document.getElementById('tsToastMsg');
  if(t&&m){
    m.textContent=msg; t.style.display='flex';
    t.classList.add('show'); clearTimeout(t._tid);
    t._tid=setTimeout(function(){t.classList.remove('show');setTimeout(function(){t.style.display='none';},380);},2800);
    return;
  }
}
window.showToast = showToast;

/* ── NAV MOBILE ── */
var _navOpen=false;
function toggleNav(){
  _navOpen=!_navOpen;
  var l=document.getElementById('navLinks'), i=document.getElementById('navIcon');
  if(l) l.classList.toggle('open',_navOpen);
  if(i) i.className=_navOpen?'fas fa-times':'fas fa-bars';
}
function toggleMobSearch(){
  var ms=document.getElementById('mobSearch'); if(!ms) return;
  var open=ms.style.display!=='none'&&ms.style.display!=='';
  ms.style.display=open?'none':'block';
  if(!open){ var inp=ms.querySelector('input'); if(inp)inp.focus(); }
}
function toggleDrop(){
  var m=document.getElementById('dropMenu'); if(m) m.classList.toggle('open');
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded',function(){
  updBadge();

  /* Add to cart */
  document.addEventListener('click',function(e){
    var btn=e.target.closest('.add-to-cart');
    if(!btn||btn.disabled) return;
    e.preventDefault(); e.stopPropagation();
    var id=btn.dataset.id, name=btn.dataset.name, price=parseFloat(btn.dataset.price), qty=1;
    /* Stocker seulement le nom de fichier pour éviter les URLs cassées */
    var imageRaw=btn.dataset.image||'';
    var image=imageRaw ? imageRaw.split('/uploads/').pop() : '';
    var qEl=document.getElementById('quantity');
    if(qEl) qty=parseInt(qEl.value)||1;
    var cart=getCart(), idx=cart.findIndex(function(x){return x.id==id;});
    if(idx>=0){ cart[idx].quantity+=qty; if(image&&!cart[idx].image) cart[idx].image=image; }
    else cart.push({id:String(id),name:name,price:price,image:image,quantity:qty});
    saveCart(cart);
    showToast(name+' ajouté au panier !');
    var orig=btn.innerHTML;
    btn.innerHTML='<i class="fas fa-check"></i>';
    btn.style.background='linear-gradient(135deg,#16a34a,#22c55e)';
    setTimeout(function(){btn.innerHTML=orig;btn.style.background='';},1800);
  });

  /* Fermer dropdown/nav au clic extérieur */
  document.addEventListener('click',function(e){
    var dd=document.getElementById('accDrop');
    if(dd&&!dd.contains(e.target)){var m=document.getElementById('dropMenu');if(m)m.classList.remove('open');}
    if(_navOpen){var nav=document.getElementById('tsNav');if(nav&&!nav.contains(e.target))toggleNav();}
  });

  /* Navbar scroll */
  window.addEventListener('scroll',function(){
    var nav=document.getElementById('tsNav');
    if(nav) nav.classList.toggle('scrolled',window.scrollY>20);
  },{passive:true});

  /* Auto-dismiss flash */
  setTimeout(function(){
    var f=document.querySelector('.ts-flash')?.closest('div');
    if(f&&f.style.position==='fixed'){f.style.opacity='0';f.style.transition='opacity .5s';setTimeout(function(){f.remove();},500);}
  },5000);

  /* changeQuantity global */
  window.changeQuantity=function(d){
    var inp=document.getElementById('quantity');
    if(!inp) return;
    inp.value=Math.min(parseInt(inp.max)||999,Math.max(1,parseInt(inp.value)+d));
  };
});
