/**
 * TECHSTORE — Enhancements JS
 * 1) Countdown Timer  2) Search Autocomplete  3) Quick View Modal
 * 4) Back to Top      5) Fly-to-Cart Animation
 */

(function(){
'use strict';

/* ═══════════════════════════════════════════════════
   1. COUNTDOWN TIMERS
   ═══════════════════════════════════════════════════ */
function initCountdowns(){
  var els=document.querySelectorAll('.ts-countdown[data-end]');
  if(!els.length) return;
  function pad(n){return n<10?'0'+n:''+n;}
  function tick(){
    var now=Math.floor(Date.now()/1000);
    els.forEach(function(el){
      var end=parseInt(el.dataset.end);
      var diff=end-now;
      if(diff<=0){
        el.querySelector('.ts-cd-text').textContent='Offre expirée';
        el.classList.add('ts-cd-expired');
        return;
      }
      var d=Math.floor(diff/86400); diff%=86400;
      var h=Math.floor(diff/3600);  diff%=3600;
      var m=Math.floor(diff/60);
      var s=diff%60;
      var txt='';
      if(d>0) txt+=d+'j ';
      txt+=pad(h)+'h '+pad(m)+'m '+pad(s)+'s';
      el.querySelector('.ts-cd-text').textContent=txt;
    });
  }
  tick();
  setInterval(tick,1000);
}

/* ═══════════════════════════════════════════════════
   2. SEARCH AUTOCOMPLETE
   ═══════════════════════════════════════════════════ */
function initAutocomplete(){
  var forms=document.querySelectorAll('.ts-search');
  var drop=document.getElementById('tsAcDrop');
  if(!drop) return;
  var debounce=null;

  forms.forEach(function(form){
    var inp=form.querySelector('input[name="q"]');
    if(!inp) return;

    // Make form position reference for dropdown
    form.style.position='relative';

    inp.addEventListener('input',function(){
      clearTimeout(debounce);
      var q=inp.value.trim();
      if(q.length<2){drop.style.display='none';return;}
      debounce=setTimeout(function(){
        fetch(TS_BASE+'/api_search.php?q='+encodeURIComponent(q))
          .then(function(r){return r.json();})
          .then(function(data){
            if(!data.length){
              drop.innerHTML='<div class="ts-ac-empty"><i class="fas fa-search"></i> Aucun résultat pour "'+q+'"</div>';
            } else {
              var html='';
              data.forEach(function(p){
                html+='<a href="'+TS_BASE+'/product/'+p.id+'" class="ts-ac-item">';
                html+='<img src="'+p.image_url+'" alt="'+p.name+'" class="ts-ac-img">';
                html+='<div class="ts-ac-info">';
                html+='<div class="ts-ac-name">'+p.name+'</div>';
                html+='<div class="ts-ac-cat">'+((p.cat)||'Général')+'</div>';
                html+='</div>';
                html+='<div class="ts-ac-price">';
                if(p.old_price_display) html+='<span class="ts-ac-old">'+p.old_price_display+'</span>';
                html+='<span>'+p.price_display+'</span>';
                html+='</div>';
                html+='</a>';
              });
              html+='<a href="'+TS_BASE+'/catalogue?q='+encodeURIComponent(q)+'" class="ts-ac-all"><i class="fas fa-arrow-right"></i> Voir tous les résultats</a>';
              drop.innerHTML=html;
            }
            // Position dropdown under the search form
            var rect=form.getBoundingClientRect();
            drop.style.top=(rect.bottom+4)+'px';
            drop.style.left=rect.left+'px';
            drop.style.width=Math.max(rect.width,340)+'px';
            drop.style.display='block';
          })
          .catch(function(){drop.style.display='none';});
      },280);
    });

    inp.addEventListener('focus',function(){
      if(inp.value.trim().length>=2 && drop.innerHTML) drop.style.display='block';
    });
  });

  // Close on click outside
  document.addEventListener('click',function(e){
    if(!e.target.closest('.ts-search') && !e.target.closest('.ts-ac-dropdown')){
      drop.style.display='none';
    }
  });
}

/* ═══════════════════════════════════════════════════
   3. QUICK VIEW MODAL
   ═══════════════════════════════════════════════════ */
function initQuickView(){
  var overlay=document.getElementById('tsQvOverlay');
  var modal=document.getElementById('tsQvModal');
  var closeBtn=document.getElementById('tsQvClose');
  if(!modal||!overlay) return;

  var _qvData={};

  function openQV(data){
    _qvData=data;
    document.getElementById('tsQvImg').src=data.image;
    document.getElementById('tsQvCat').textContent=data.cat;
    document.getElementById('tsQvName').textContent=data.name;
    document.getElementById('tsQvDesc').textContent=data.desc||'Découvrez ce produit dans notre catalogue TechStore.';
    document.getElementById('tsQvLink').href=TS_BASE+'/product/'+data.id;
    document.getElementById('tsQvQtyVal').value=1;

    // Price
    var priceCfa=data.price*655.957;
    document.getElementById('tsQvPrice').textContent=Math.round(priceCfa).toLocaleString('fr-FR')+' FC';

    // Old price
    var oldEl=document.getElementById('tsQvOldPrice');
    if(data.oldPrice && parseFloat(data.oldPrice)>0){
      var oldCfa=parseFloat(data.oldPrice)*655.957;
      oldEl.textContent=Math.round(oldCfa).toLocaleString('fr-FR')+' FC';
      oldEl.style.display='block';
    } else { oldEl.style.display='none'; }

    // Discount
    var discEl=document.getElementById('tsQvDiscount');
    if(data.discount && parseInt(data.discount)>0){
      discEl.textContent='-'+data.discount+'%';
      discEl.style.display='flex';
    } else { discEl.style.display='none'; }

    // Stock
    var stockEl=document.getElementById('tsQvStock');
    var stockTxt=document.getElementById('tsQvStockText');
    var stock=parseInt(data.stock)||0;
    stockEl.className='ts-pstock';
    if(stock===0){stockEl.classList.add('ts-sout');stockTxt.textContent='Rupture de stock';}
    else if(stock<=5){stockEl.classList.add('ts-slow');stockTxt.textContent='Stock limité ('+stock+')';}
    else{stockEl.classList.add('ts-sok');stockTxt.textContent='En stock ('+stock+')';}

    // ATC button
    var atcBtn=document.getElementById('tsQvAtc');
    atcBtn.disabled=stock===0;
    if(stock===0){atcBtn.innerHTML='<i class="fas fa-ban"></i> Indisponible';}
    else{atcBtn.innerHTML='<i class="fas fa-cart-plus"></i> Ajouter au panier';}

    overlay.classList.add('open');
    modal.classList.add('open');
    document.body.style.overflow='hidden';
  }

  function closeQV(){
    overlay.classList.remove('open');
    modal.classList.remove('open');
    document.body.style.overflow='';
  }

  // Listen for quickview buttons
  document.addEventListener('click',function(e){
    var btn=e.target.closest('.ts-quickview-btn');
    if(!btn) return;
    e.preventDefault(); e.stopPropagation();
    openQV({
      id:btn.dataset.id,
      name:btn.dataset.name,
      price:parseFloat(btn.dataset.price),
      oldPrice:btn.dataset.oldPrice||'0',
      image:btn.dataset.image,
      cat:btn.dataset.cat||'Général',
      desc:btn.dataset.desc||'',
      stock:btn.dataset.stock||'0',
      discount:btn.dataset.discount||'0'
    });
  });

  if(closeBtn) closeBtn.addEventListener('click',closeQV);
  if(overlay) overlay.addEventListener('click',closeQV);
  document.addEventListener('keydown',function(e){if(e.key==='Escape')closeQV();});

  // QV add to cart
  var qvAtcBtn=document.getElementById('tsQvAtc');
  if(qvAtcBtn){
    qvAtcBtn.addEventListener('click',function(){
      if(qvAtcBtn.disabled) return;
      var qty=parseInt(document.getElementById('tsQvQtyVal').value)||1;
      var imageRaw=_qvData.image||'';
      var image=imageRaw.indexOf('/uploads/')>-1?imageRaw.split('/uploads/').pop():imageRaw;
      var cart=JSON.parse(localStorage.getItem('techstore_cart')||'[]');
      var idx=cart.findIndex(function(x){return x.id==_qvData.id;});
      if(idx>=0){cart[idx].quantity+=qty;}
      else{cart.push({id:String(_qvData.id),name:_qvData.name,price:_qvData.price,image:image,quantity:qty});}
      localStorage.setItem('techstore_cart',JSON.stringify(cart));
      if(window.updateCartCount) window.updateCartCount();
      if(window.showToast) window.showToast(_qvData.name+' ajouté au panier !');

      // Fly animation from modal
      flyToCart(document.getElementById('tsQvImg'));

      qvAtcBtn.innerHTML='<i class="fas fa-check"></i> Ajouté !';
      qvAtcBtn.style.background='linear-gradient(135deg,#16a34a,#22c55e)';
      setTimeout(function(){
        qvAtcBtn.innerHTML='<i class="fas fa-cart-plus"></i> Ajouter au panier';
        qvAtcBtn.style.background='';
        closeQV();
      },1200);
    });
  }

  // QV quantity
  window.tsQvQty=function(d){
    var inp=document.getElementById('tsQvQtyVal');
    if(!inp) return;
    inp.value=Math.max(1,Math.min(99,parseInt(inp.value)+d));
  };
}

/* ═══════════════════════════════════════════════════
   4. BACK TO TOP
   ═══════════════════════════════════════════════════ */
function initBackToTop(){
  var btn=document.getElementById('tsBtt');
  if(!btn) return;
  window.addEventListener('scroll',function(){
    btn.classList.toggle('visible',window.scrollY>400);
  },{passive:true});
  btn.addEventListener('click',function(){
    window.scrollTo({top:0,behavior:'smooth'});
  });
}

/* ═══════════════════════════════════════════════════
   5. FLY TO CART ANIMATION
   ═══════════════════════════════════════════════════ */
function flyToCart(sourceEl){
  if(!sourceEl) return;
  var ghost=document.getElementById('tsFlyGhost');
  if(!ghost) return;

  var srcRect=sourceEl.getBoundingClientRect();
  // Target: cart icon in navbar
  var cartBtn=document.querySelector('.ts-cart-btn')||document.querySelector('.ts-mob-cart');
  if(!cartBtn) return;
  var tgtRect=cartBtn.getBoundingClientRect();

  // Clone image into ghost
  if(sourceEl.tagName==='IMG'){
    ghost.style.backgroundImage='url('+sourceEl.src+')';
    ghost.style.backgroundSize='contain';
    ghost.style.backgroundRepeat='no-repeat';
    ghost.style.backgroundPosition='center';
  }

  ghost.style.left=srcRect.left+'px';
  ghost.style.top=srcRect.top+'px';
  ghost.style.width=srcRect.width+'px';
  ghost.style.height=srcRect.height+'px';
  ghost.style.opacity='1';
  ghost.style.display='block';
  ghost.style.transform='scale(1)';
  ghost.style.borderRadius='12px';

  // Animate
  requestAnimationFrame(function(){
    ghost.style.transition='all .65s cubic-bezier(.4,0,.2,1)';
    ghost.style.left=tgtRect.left+'px';
    ghost.style.top=tgtRect.top+'px';
    ghost.style.width='30px';
    ghost.style.height='30px';
    ghost.style.opacity='0.3';
    ghost.style.transform='scale(0.2)';
    ghost.style.borderRadius='50%';
  });

  // Pulse cart icon
  setTimeout(function(){
    ghost.style.display='none';
    ghost.style.transition='';
    ghost.style.backgroundImage='';
    cartBtn.classList.add('ts-cart-pulse');
    setTimeout(function(){cartBtn.classList.remove('ts-cart-pulse');},600);
  },700);
}

// Hook fly animation into add-to-cart clicks
function initFlyToCart(){
  document.addEventListener('click',function(e){
    var btn=e.target.closest('.add-to-cart');
    if(!btn||btn.disabled||btn.closest('.ts-qv-modal')) return;
    // Find the product image nearby
    var card=btn.closest('.ts-pcard')||btn.closest('.ts-bs-hero')||btn.closest('.ts-bs-item');
    if(!card) return;
    var img=card.querySelector('.ts-pimg img, .ts-bs-hero-img img, .ts-bs-item-img img');
    if(img) flyToCart(img);
  });
}


/* ═══════════════════════════════════════════════════
   INIT ALL
   ═══════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded',function(){
  initCountdowns();
  initAutocomplete();
  initQuickView();
  initBackToTop();
  initFlyToCart();
});

})();
