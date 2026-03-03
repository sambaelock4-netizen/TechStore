/**
 * TECHSTORE - JavaScript principal
 */

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    
    // ===========================================
    // Gestion du panier
    // ===========================================
    
    // Ajouter au panier
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productPrice = parseFloat(this.dataset.price);
            
            addToCart(productId, productName, productPrice);
        });
    });
    
    // Fonction pour ajouter au panier
    function addToCart(id, name, price) {
        // Récupérer le panier depuis le localStorage
        let cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        
        // Vérifier si le produit existe déjà
        const existingItem = cart.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                quantity: 1
            });
        }
        
        // Sauvegarder dans le localStorage
        localStorage.setItem('techstore_cart', JSON.stringify(cart));
        
        // Mettre à jour l'affichage du panier
        updateCartDisplay();
        
        // Afficher un message
        showToast(name + ' a été ajouté au panier');
    }
    
    // Mettre à jour l'affichage du panier
    function updateCartDisplay() {
        const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        
        // Mettre à jour le badge du panier (plusieurs sélecteurs pour compatibilité)
        const cartBadges = document.querySelectorAll('.cart-badge, .nav-item .badge, .badge.bg-danger');
        cartBadges.forEach(badge => {
            badge.textContent = cartCount;
        });
    }
    
    // Afficher un toast (message)
    function showToast(message) {
        // Créer l'élément toast
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-body">
                <i class="fas fa-check-circle text-success me-2"></i>
                ${message}
            </div>
        `;
        
        // Ajouter les styles si pas encore fait
        if (!document.getElementById('toast-styles')) {
            const styles = document.createElement('style');
            styles.id = 'toast-styles';
            styles.textContent = `
                .toast-notification {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: white;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    animation: slideIn 0.3s ease;
                }
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(styles);
        }
        
        document.body.appendChild(toast);
        
        // Supprimer après 3 secondes
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    // ===========================================
    // Gestion des formulaires
    // ===========================================
    
    // Validation des formulaires
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // ===========================================
    // Recherche
    // ===========================================
    
    // Recherche avec auto-complétion
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    if (searchInput && searchResults) {
        let timeout = null;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            // Attendre 300ms après la dernière frappe
            timeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
        
        // Fermer les résultats quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }
    
    // Fonction de recherche
    function performSearch(query) {
        // Ici, vous pouvez appeler une API ou faire une requête AJAX
        // Pour l'instant, on redirige vers la page de recherche
        const searchUrl = '<?= BASE_URL ?>/index.php/search?q=' + encodeURIComponent(query);
        window.location.href = searchUrl;
    }
    
    // ===========================================
    // Quantité du panier
    // ===========================================
    
    // Boutons + et - pour la quantité
    const quantityInputs = document.querySelectorAll('.cart-quantity-input');
    
    quantityInputs.forEach(input => {
        // Bouton +
        const plusBtn = input.parentElement.querySelector('.btn-plus');
        if (plusBtn) {
            plusBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                input.value = value + 1;
                updateCartQuantity(input.dataset.id, input.value);
            });
        }
        
        // Bouton -
        const minusBtn = input.parentElement.querySelector('.btn-minus');
        if (minusBtn) {
            minusBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    updateCartQuantity(input.dataset.id, input.value);
                }
            });
        }
        
        // Changement direct
        input.addEventListener('change', function() {
            updateCartQuantity(this.dataset.id, this.value);
        });
    });
    
    // Mettre à jour la quantité
    function updateCartQuantity(id, quantity) {
        let cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        
        const item = cart.find(item => item.id === id);
        if (item) {
            item.quantity = parseInt(quantity);
            localStorage.setItem('techstore_cart', JSON.stringify(cart));
            
            // Recalculer le total
            updateCartTotal();
        }
    }
    
    // Mettre à jour le total du panier
    function updateCartTotal() {
        const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
        });
        
        const subtotalElement = document.getElementById('cart-subtotal');
        const totalElement = document.getElementById('cart-total');
        
        if (subtotalElement) {
            subtotalElement.textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
        }
        
        if (totalElement) {
            totalElement.textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
        }
    }
    
    // ===========================================
    // Supprimer un article du panier
    // ===========================================
    
    const removeButtons = document.querySelectorAll('.remove-from-cart');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            removeFromCart(productId);
        });
    });
    
    function removeFromCart(id) {
        let cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        cart = cart.filter(item => item.id !== id);
        localStorage.setItem('techstore_cart', JSON.stringify(cart));
        
        // Recharger la page pour mettre à jour l'affichage
        location.reload();
    }
    
    // ===========================================
    // Initialisation
    // ===========================================
    
    // Mettre à jour l'affichage du panier au chargement
    updateCartDisplay();
    
    // Mettre à jour le total du panier si on est sur la page panier
    if (document.getElementById('cart-subtotal')) {
        updateCartTotal();
    }
    
});

// Exécuter au chargement de la page (au cas où DOMContentLoaded ne se déclenche pas)
window.addEventListener('load', function() {
    // Forcer la mise à jour du badge du panier
    const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    
    // Mettre à jour tous les badges possibles
    const selectors = ['.cart-badge', '.nav-item .badge', '.badge.bg-danger', '[class*="cart-badge"]'];
    selectors.forEach(selector => {
        document.querySelectorAll(selector).forEach(badge => {
            badge.textContent = cartCount;
        });
    });
});

function updateCartBadge() {
    const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    
    // Mettre à jour tous les badges possibles
    const selectors = ['.cart-badge', '.nav-item .badge', '.badge.bg-danger', '[class*="cart-badge"]'];
    selectors.forEach(selector => {
        document.querySelectorAll(selector).forEach(badge => {
            badge.textContent = cartCount;
        });
    });
}

/**
 * Fonction globale pour ajouter au panier
 * (peut être appelée depuis les liens HTML)
 */
function addToCartGlobal(id, name, price) {
    // Créer un event click simulé
    const event = new CustomEvent('addToCart', {
        detail: { id, name, price }
    });
    document.dispatchEvent(event);
}
