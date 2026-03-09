/**
 * TECHSTORE - JavaScript principal (Corrigé)
 */

// Conversion EUR to CFA (si nécessaire pour tes calculs)
const EUR_TO_CFA = 655.957;

// Fonction pour afficher le prix formaté
function displayPriceCFA(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' FCFA';
}

document.addEventListener('DOMContentLoaded', function() {
    
    // ===========================================
    // 1. GESTION DU PANIER (CORE)
    // ===========================================
    
    // Initialisation du badge au chargement
    updateCartDisplay();

    // Ecouteur pour les boutons "Ajouter au panier" - UN SEUL écouteur pour toute la page
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.add-to-cart');
        if (btn) {
            e.preventDefault();
            e.stopPropagation(); // Empêcher la propagation
            
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const price = parseFloat(btn.dataset.price);
            
            // Récupérer la quantité - d'abord depuis l'input #quantity (page produit), sinon 1
            const qtyInput = document.getElementById('quantity');
            let quantity = 1;
            if (qtyInput) {
                quantity = parseInt(qtyInput.value) || 1;
            }
            
            addToCart(id, name, price, quantity);
            return false; // Empêcher tout comportement par défaut
        }
    });

    function addToCart(id, name, price, quantity) {
        let cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        const existingItem = cart.find(item => item.id === id);

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({ id, name, price, quantity });
        }

        localStorage.setItem('techstore_cart', JSON.stringify(cart));
        updateCartDisplay();
        showToast(`${name} ajouté au panier !`);
    }

    // MISE À JOUR DU BADGE (CORRIGÉ POUR ÉVITER LE 0 ROUGE)
    function updateCartDisplay() {
        const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        
        // On cible UNIQUEMENT l'ID spécifique de la navbar
        const headerCartBadge = document.getElementById('headerCartCount');
        
        if (headerCartBadge) {
            headerCartBadge.textContent = cartCount;
            
            // On cache le badge s'il est à 0 pour un design plus propre
            if (cartCount === 0) {
                headerCartBadge.style.display = 'none';
            } else {
                headerCartBadge.style.display = 'inline-block';
            }
        }
    }

    // ===========================================
    // 2. NOTIFICATIONS (TOAST)
    // ===========================================
    
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-body">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                ${message}
            </div>
        `;

        if (!document.getElementById('toast-styles')) {
            const styles = document.createElement('style');
            styles.id = 'toast-styles';
            styles.textContent = `
                .toast-notification {
                    position: fixed; bottom: 20px; right: 20px;
                    background: #fff; padding: 1rem 1.5rem;
                    border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                    z-index: 10000; animation: slideIn 0.3s ease forwards;
                    border-left: 4px solid #28a745;
                }
                @keyframes slideIn {
                    from { transform: translateX(120%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(styles);
        }

        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'slideIn 0.3s ease reverse forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ===========================================
    // 3. PAGE PANIER (QUANTITÉS & CALCULS)
    // ===========================================
    
    if (document.getElementById('cart-subtotal')) {
        updateCartTotal();
    }

    // Gestion des boutons + et - dans le panier
    document.addEventListener('click', function(e) {
        const btnPlus = e.target.closest('.btn-plus');
        const btnMinus = e.target.closest('.btn-minus');
        const btnRemove = e.target.closest('.remove-from-cart');

        if (btnPlus || btnMinus) {
            const input = (btnPlus || btnMinus).parentElement.querySelector('input');
            let val = parseInt(input.value);
            if (btnPlus) val++;
            if (btnMinus && val > 1) val--;
            
            input.value = val;
            updateCartQuantity(input.dataset.id, val);
        }

        if (btnRemove) {
            removeFromCart(btnRemove.dataset.id);
        }
    });

    function updateCartQuantity(id, quantity) {
        let cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        const item = cart.find(item => item.id === id);
        if (item) {
            item.quantity = parseInt(quantity);
            localStorage.setItem('techstore_cart', JSON.stringify(cart));
            updateCartTotal();
            updateCartDisplay();
        }
    }

    function updateCartTotal() {
        const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        
        if (subtotalEl) subtotalEl.textContent = displayPriceCFA(total);
        if (totalEl) totalEl.textContent = displayPriceCFA(total);
    }

    function removeFromCart(id) {
        let cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        cart = cart.filter(item => item.id !== id);
        localStorage.setItem('techstore_cart', JSON.stringify(cart));
        location.reload(); // Recharger pour mettre à jour la liste
    }

    // ===========================================
    // 4. RECHERCHE & VALIDATION
    // ===========================================
    
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query.length > 1) {
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });
    }

    // Validation Bootstrap
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
});

