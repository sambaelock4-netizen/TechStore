<?php
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Récupérer les adresses de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC");
    $stmt->execute([$user_id]);
    $addresses = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $addresses = [];
}

// Récupérer les informations de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $user = [];
}

// Traitement de la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $address_id = $_POST['address_id'] ?? '';
    $notes = trim($_POST['notes'] ?? '');
    
    // Récupérer le panier depuis le JavaScript via un champ hidden
    $cart_data = $_POST['cart_data'] ?? '[]';
    $cart = json_decode($cart_data, true);
    
    if (empty($cart)) {
        $error = 'Votre panier est vide';
    } elseif (empty($address_id)) {
        $error = 'Veuillez sélectionner une adresse de livraison';
    } else {
        try {
            // Récupérer l'adresse de livraison
            $stmt = $pdo->prepare("SELECT * FROM addresses WHERE id = ? AND user_id = ?");
            $stmt->execute([$address_id, $user_id]);
            $address = $stmt->fetch();
            
            if (!$address) {
                $error = 'Adresse invalide';
            } else {
                // Calculer le total
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                
                // Créer la commande
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, shipping_address, shipping_city, shipping_postal_code, notes) VALUES (?, ?, 'en_attente', ?, ?, ?, ?)");
                $stmt->execute([
                    $user_id,
                    $total,
                    $address['address'],
                    $address['city'],
                    $address['postal_code'],
                    $notes
                ]);
                
                $order_id = $pdo->lastInsertId();
                
                // Ajouter les articles de la commande
                foreach ($cart as $item) {
                    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $order_id,
                        $item['id'],
                        $item['quantity'],
                        $item['price']
                    ]);
                    
                    // Mettre à jour le stock
                    $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                    $stmt->execute([$item['quantity'], $item['id']]);
                }
                
                // Vider le panier
                $success = 'Commande passée avec succès!';
                
                // Rediriger vers la page des commandes
                echo '<script>localStorage.removeItem("techstore_cart"); window.location.href = "' . BASE_URL . '/orders";</script>';
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de la commande. Veuillez réessayer.';
            error_log($e->getMessage());
        }
    }
}
?>

<div class="checkout-page py-5">
    <div class="container">
        <h1 class="mb-4 fw-bold"><i class="bi bi-check2-square me-2"></i>Passer la commande</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger animate__animated animate__shake">
                <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success animate__animated animate__fadeIn">
                <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="checkout-form">
            <input type="hidden" name="place_order" value="1">
            <input type="hidden" name="cart_data" id="cart-data" value="">
            
            <div class="row">
                <div class="col-lg-8">
                    <!-- Adresse de livraison -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4"><i class="bi bi-truck me-2"></i>Adresse de livraison</h5>
                            
                            <?php if (empty($addresses)): ?>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Vous n'avez pas d'adresse enregistrée. 
                                    <a href="<?= BASE_URL ?>/account">Ajoutez une adresse</a>
                                </div>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php foreach ($addresses as $address): ?>
                                        <div class="col-md-6">
                                            <div class="form-check card border <?= $address['is_default'] ? 'border-primary' : '' ?> p-3">
                                                <input class="form-check-input" type="radio" name="address_id" 
                                                       id="address_<?= $address['id'] ?>" 
                                                       value="<?= $address['id'] ?>" 
                                                       <?= $address['is_default'] ? 'checked' : '' ?>>
                                                <label class="form-check-label w-100" for="address_<?= $address['id'] ?>">
                                                    <strong><?= htmlspecialchars($address['name']) ?></strong>
                                                    <p class="mb-0 text-muted small">
                                                        <?= htmlspecialchars($address['address']) ?><br>
                                                        <?= htmlspecialchars($address['postal_code']) ?> <?= htmlspecialchars($address['city']) ?>
                                                    </p>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-chat-left-text me-2"></i>Notes complémentaires</h5>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Instructions de livraison, code porte, etc."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Résumé de la commande -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Résumé de la commande</h5>
                            <div id="checkout-items">
                                <!-- Les articles seront chargés ici via JavaScript -->
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sous-total</span>
                                <span id="checkout-subtotal">0,00 FC</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Livraison</span>
                                <span class="text-success">Offerte</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold text-primary fs-5" id="checkout-total">0,00 FC</span>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" <?= empty($addresses) ? 'disabled' : '' ?>>
                                <i class="bi bi-check2-circle me-2"></i>Confirmer la commande
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCheckoutSummary();
    
    function loadCheckoutSummary() {
        const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        const itemsContainer = document.getElementById('checkout-items');
        const subtotalElement = document.getElementById('checkout-subtotal');
        const totalElement = document.getElementById('checkout-total');
        
        if (cart.length === 0) {
            itemsContainer.innerHTML = '<p class="text-muted">Votre panier est vide</p>';
            return;
        }
        
        let subtotal = 0;
        let html = '';
        
        cart.forEach(function(item) {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            html += '<div class="d-flex justify-content-between mb-2"><span>' + item.name + ' x' + item.quantity + '</span><span>' + itemTotal.toFixed(2).replace('.', ',') + ' FC</span></div>';
        });
        
        html += '<input type="hidden" id="cart-data-input" value=\'' + JSON.stringify(cart) + '\'>';
        
        itemsContainer.innerHTML = html;
        subtotalElement.textContent = subtotal.toFixed(2).replace('.', ',') + ' FC';
        totalElement.textContent = subtotal.toFixed(2).replace('.', ',') + ' FC';
        
        // Mettre à jour le champ hidden
        document.getElementById('cart-data').value = JSON.stringify(cart);
    }
    
    // Soumettre le formulaire
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        
        if (cart.length === 0) {
            e.preventDefault();
            alert('Votre panier est vide');
            return;
        }
        
        const addressSelected = document.querySelector('input[name="address_id"]:checked');
        if (!addressSelected) {
            e.preventDefault();
            alert('Veuillez sélectionner une adresse de livraison');
            return;
        }
    });
});
</script>

<style>
.checkout-page {
    background-color: #f8f9fa;
    min-height: calc(100vh - 76px);
}

.checkout-page .card {
    border-radius: 12px;
    border: none;
}

.checkout-page .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
