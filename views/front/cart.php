<?php
// Récupérer les produits du panier depuis localStorage
$cart_items = [];
?>

<div class="cart-page py-5">
    <div class="container">
        <h1 class="mb-4 fw-bold"><i class="bi bi-cart3 me-2"></i>Mon Panier</h1>
        
        <div id="cart-content">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    
    // Conversion EUR to CFA
    var EUR_TO_CFA = 655.957;
    
    function formatPrice(priceEur) {
        var priceCfa = priceEur * EUR_TO_CFA;
        return priceCfa.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Fcfa';
    }
    
    function loadCart() {
        var cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        var cartContent = document.getElementById('cart-content');
        
        if (cart.length === 0) {
            cartContent.innerHTML = '<div class="card shadow-sm"><div class="card-body text-center py-5"><i class="bi bi-cart-x fs-1 text-muted"></i><h4 class="mt-3">Votre panier est vide</h4><p class="text-muted">Découvrez nos produits et ajoutez-les à votre panier.</p><a href="<?= BASE_URL ?>/catalogue" class="btn btn-primary mt-2"><i class="bi bi-shop me-2"></i>Découvrir nos produits</a></div></div>';
            return;
        }
        
        var subtotal = 0;
        var cartHTML = '<div class="row"><div class="col-lg-8"><div class="card shadow-sm mb-4"><div class="card-body"><h5 class="card-title mb-4">Articles dans votre panier (' + cart.length + ')</h5><div class="table-responsive"><table class="table table-hover"><thead><tr><th style="width: 50%">Produit</th><th style="width: 20%">Prix</th><th style="width: 20%">Quantité</th><th style="width: 10%">Total</th><th style="width: 5%"></th></tr></thead><tbody>';
        
        for (var i = 0; i < cart.length; i++) {
            var item = cart[i];
            var itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            cartHTML += '<tr data-id="' + item.id + '"><td><div class="d-flex align-items-center"><div class="me-3"><i class="bi bi-box-seam fs-4 text-muted"></i></div><div><h6 class="mb-0">' + item.name + '</h6></div></div></td><td class="align-middle">' + formatPrice(item.price) + '</td><td class="align-middle"><div class="d-flex align-items-center"><button class="btn btn-sm btn-outline-secondary btn-minus" data-id="' + item.id + '"><i class="bi bi-dash"></i></button><input type="number" class="form-control text-center mx-2 cart-qty-input" data-id="' + item.id + '" value="' + item.quantity + '" min="1" style="width: 60px;"><button class="btn btn-sm btn-outline-secondary btn-plus" data-id="' + item.id + '"><i class="bi bi-plus"></i></button></div></td><td class="align-middle fw-bold">' + formatPrice(itemTotal) + '</td><td class="align-middle"><button class="btn btn-sm btn-outline-danger remove-from-cart" data-id="' + item.id + '"><i class="bi bi-trash"></i></button></td></tr>';
        }
        
        cartHTML += '</tbody></table></div><div class="d-flex justify-content-between align-items-center mt-4"><a href="<?= BASE_URL ?>/catalogue" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-2"></i>Continuer mes achats</a><button class="btn btn-outline-danger" onclick="clearCart()"><i class="bi bi-trash me-2"></i>Vider le panier</button></div></div></div></div><div class="col-lg-4"><div class="card shadow-sm"><div class="card-body"><h5 class="card-title mb-4">Résumé de la commande</h5><div class="d-flex justify-content-between mb-2"><span>Sous-total</span><span id="cart-subtotal">' + formatPrice(subtotal) + '</span></div><div class="d-flex justify-content-between mb-2"><span>Livraison</span><span class="text-success">Offerte</span></div><hr><div class="d-flex justify-content-between mb-4"><span class="fw-bold">Total</span><span class="fw-bold text-primary fs-5" id="cart-total">' + formatPrice(subtotal) + '</span></div>';
        
        <?php if(isset($_SESSION['user_id'])): ?>
        cartHTML += '<a href="<?= BASE_URL ?>/checkout" class="btn btn-primary w-100 py-2 fw-bold"><i class="bi bi-credit-card me-2"></i>Passer la commande</a>';
        <?php else: ?>
        cartHTML += '<a href="<?= BASE_URL ?>/login" class="btn btn-primary w-100 py-2 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Se connecter pour commander</a><p class="text-center text-muted small mt-2">Vous devez être connecté pour passer commande</p>';
        <?php endif; ?>
        
        cartHTML += '</div></div></div></div>';
        
        cartContent.innerHTML = cartHTML;
        attachCartEvents();
    }
    
    function attachCartEvents() {
        var plusBtns = document.querySelectorAll('.btn-plus');
        for (var i = 0; i < plusBtns.length; i++) {
            plusBtns[i].addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                updateQuantity(id, 1);
            });
        }
        
        var minusBtns = document.querySelectorAll('.btn-minus');
        for (var i = 0; i < minusBtns.length; i++) {
            minusBtns[i].addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var input = document.querySelector('.cart-qty-input[data-id="' + id + '"]');
                if (parseInt(input.value) > 1) {
                    updateQuantity(id, -1);
                }
            });
        }
        
        var qtyInputs = document.querySelectorAll('.cart-qty-input');
        for (var i = 0; i < qtyInputs.length; i++) {
            qtyInputs[i].addEventListener('change', function() {
                var id = this.getAttribute('data-id');
                var quantity = parseInt(this.value);
                
                if (quantity < 1) {
                    this.value = 1;
                    return;
                }
                
                setQuantity(id, quantity);
            });
        }
        
        var removeBtns = document.querySelectorAll('.remove-from-cart');
        for (var i = 0; i < removeBtns.length; i++) {
            removeBtns[i].addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                removeFromCart(id);
            });
        }
    }
    
    function updateQuantity(id, change) {
        var cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id === id) {
                var newQty = cart[i].quantity + change;
                if (newQty < 1) newQty = 1;
                cart[i].quantity = newQty;
                break;
            }
        }
        
        localStorage.setItem('techstore_cart', JSON.stringify(cart));
        loadCart();
        updateCartBadge();
    }
    
    function setQuantity(id, quantity) {
        var cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id === id) {
                cart[i].quantity = quantity;
                break;
            }
        }
        
        localStorage.setItem('techstore_cart', JSON.stringify(cart));
        loadCart();
        updateCartBadge();
    }
    
    function removeFromCart(id) {
        var cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        var newCart = [];
        
        for (var i = 0; i < cart.length; i++) {
            if (cart[i].id !== id) {
                newCart.push(cart[i]);
            }
        }
        
        localStorage.setItem('techstore_cart', JSON.stringify(newCart));
        loadCart();
        updateCartBadge();
        showToast('Produit supprimé du panier', 'success');
    }
    
    function updateCartBadge() {
        var cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        var count = 0;
        for (var i = 0; i < cart.length; i++) {
            count += cart[i].quantity;
        }
        var badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = count;
        }
    }
});

function clearCart() {
    if (confirm('Êtes-vous sûr de vouloir vider votre panier?')) {
        localStorage.removeItem('techstore_cart');
        showToast('Panier vidé avec succès', 'success');
        setTimeout(function() {
            location.reload();
        }, 1000);
    }
}

function showToast(message, type) {
    var toast = document.createElement('div');
    toast.className = 'toast-notification';
    
    var icon = type === 'success' ? 'bi-check-circle text-success' : 'bi-exclamation-circle text-danger';
    
    toast.innerHTML = '<i class="bi ' + icon + ' me-2"></i>' + message;
    
    if (!document.getElementById('toast-styles')) {
        var styles = document.createElement('style');
        styles.id = 'toast-styles';
        styles.textContent = '.toast-notification{position:fixed;bottom:20px;right:20px;background:#fff;padding:1rem 1.5rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:9999;animation:slideIn .3s ease}@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}';
        document.head.appendChild(styles);
    }
    
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.remove();
    }, 3000);
}
</script>

<style>
.cart-page {
    background-color: #f8f9fa;
    min-height: calc(100vh - 76px);
}

.cart-page .card {
    border-radius: 12px;
    border: none;
}

.cart-page .table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.cart-page .cart-qty-input {
    -moz-appearance: textfield;
    appearance: none;
    -webkit-appearance: none;
}

.cart-page .cart-qty-input::-webkit-inner-spin-button,
.cart-page .cart-qty-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.cart-page .cart-qty-input::-moz-inner-spin-button,
.cart-page .cart-qty-input::-moz-outer-spin-button {
    -moz-appearance: none;
    margin: 0;
}
</style>
