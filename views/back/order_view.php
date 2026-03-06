<?php
/**
 * TECHSTORE - Admin Order View
 */
?>

<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon"><i class="fas fa-store"></i></span>
            <span class="brand-text">TECHSTORE</span>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/admin" class="nav-item">
                <i class="fas fa-th-large"></i><span>Dashboard</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/products" class="nav-item">
                <i class="fas fa-box"></i><span>Produits</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/orders" class="nav-item active">
                <i class="fas fa-shopping-cart"></i><span>Commandes</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-item">
                <i class="fas fa-users"></i><span>Utilisateurs</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/stock" class="nav-item">
                <i class="fas fa-warehouse"></i><span>Stock</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item">
                <i class="fas fa-percent"></i><span>Promotions</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item">
                <i class="fas fa-chart-bar"></i><span>Statistiques</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/profile" class="nav-item">
                <i class="fas fa-user-cog"></i><span>Profil</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>/logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
            </a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <div class="header-title">
                <a href="<?= BASE_URL ?>/admin/orders" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1>Commande #<?= $order['order_number'] ?? $order['id'] ?></h1>
                    <p class="text-muted">Passée le <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></p>
                </div>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/admin/orders/generateInvoice/<?= $order['id'] ?>" class="btn btn-primary" target="_blank">
                    <i class="fas fa-file-invoice"></i> Facture
                </a>
                <a href="<?= BASE_URL ?>/admin/orders/generateDeliveryNote/<?= $order['id'] ?>" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-truck"></i> Bon de livraison
                </a>
            </div>
        </header>

        <div class="order-details-grid">
            <!-- Order Status -->
            <div class="content-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-tasks"></i> Statut de la commande</h3>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="<?= BASE_URL ?>/admin/orders/update/<?= $order['id'] ?>">
                        <div class="form-group">
                            <label>Statut actuel</label>
                            <select name="status" class="form-select">
                                <option value="en_attente" <?= $order['status'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="confirme" <?= $order['status'] === 'confirme' ? 'selected' : '' ?>>Confirmé</option>
                                <option value="en_preparation" <?= $order['status'] === 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                                <option value="expedie" <?= $order['status'] === 'expedie' ? 'selected' : '' ?>>Expédié</option>
                                <option value="livre" <?= $order['status'] === 'livre' ? 'selected' : '' ?>>Livré</option>
                                <option value="annule" <?= $order['status'] === 'annule' ? 'selected' : '' ?>>Annulé</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-input" rows="3" placeholder="Ajouter une note..."><?= htmlspecialchars($order['notes'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="content-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-user"></i> Informations client</h3>
                </div>
                <div class="card-body-custom">
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-label">Nom</span>
                            <span class="info-value"><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value"><?= htmlspecialchars($order['email']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Téléphone</span>
                            <span class="info-value"><?= htmlspecialchars($order['phone'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="content-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-shipping-fast"></i> Adresse de livraison</h3>
                </div>
                <div class="card-body-custom">
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-value">
                                <?= htmlspecialchars($order['shipping_name'] ?? $order['firstname'] . ' ' . $order['lastname']) ?><br>
                                <?= htmlspecialchars($order['shipping_address'] ?? $order['address'] ?? '-') ?><br>
                                <?= htmlspecialchars(($order['shipping_postal_code'] ?? $order['postal_code']) . ' ' . ($order['shipping_city'] ?? $order['city'] ?? '')) ?><br>
                                <?= htmlspecialchars($order['shipping_phone'] ?? $order['phone'] ?? '-') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="content-card full-width">
                <div class="card-header-custom">
                    <h3><i class="fas fa-boxes"></i> Articles commandés</h3>
                </div>
                <div class="card-body-custom">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orderItems)): ?>
                                <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                            <?php if (!empty($item['variant_name'])): ?>
                                                <span class="variant"><?= htmlspecialchars($item['variant_name']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= displayPrice($item['price']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><strong><?= displayPrice($item['total']) ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">Sous-total</td>
                                <td><?= displayPrice($order['subtotal'] ?? $order['total_amount']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Frais de port</td>
                                <td><?= displayPrice($order['shipping_cost'] ?? 0) ?></td>
                            </tr>
                            <?php if (($order['discount_amount'] ?? 0) > 0): ?>
                            <tr>
                                <td colspan="3" class="text-right">Remise</td>
                                <td class="discount">-<?= displayPrice($order['discount_amount']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="total-row">
                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                <td><strong><?= displayPrice($order['total_amount']) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
.admin-wrapper { display: flex; min-height: calc(100vh - 76px); background: #f5f7fa; }
.admin-sidebar { width: 260px; background: linear-gradient(180deg, #1a1d20 0%, #2d3238 100%); padding: 0; display: flex; flex-direction: column; position: fixed; height: calc(100vh - 76px); z-index: 100; }
.sidebar-brand { padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 12px; }
.brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #0d6efd, #0a58ca); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; }
.brand-text { color: white; font-size: 20px; font-weight: 700; letter-spacing: 1px; }
.sidebar-nav { flex: 1; padding: 20px 0; overflow-y: auto; }
.nav-item { display: flex; align-items: center; gap: 12px; padding: 14px 20px; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.3s ease; border-left: 3px solid transparent; }
.nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
.nav-item.active { background: rgba(13, 110, 253, 0.15); color: #0d6efd; border-left-color: #0d6efd; }
.sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
.logout-btn { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 8px; transition: all 0.3s ease; }
.logout-btn:hover { background: rgba(220, 53, 69, 0.2); color: #dc3545; }
.admin-main { flex: 1; margin-left: 260px; padding: 30px; }
.admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header-title { display: flex; align-items: center; gap: 15px; }
.header-title h1 { font-size: 24px; font-weight: 700; color: #1a1d20; margin: 0; }
.header-title .text-muted { color: #6c757d; margin: 0; }
.back-link { width: 40px; height: 40px; border-radius: 8px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d; text-decoration: none; transition: all 0.3s ease; }
.back-link:hover { background: #0d6efd; color: white; }
.header-actions { display: flex; gap: 10px; }
.btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
.btn-primary { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; border: none; cursor: pointer; }
.btn-secondary { background: #6c757d; color: white; }
.order-details-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.content-card { background: white; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); overflow: hidden; }
.content-card.full-width { grid-column: span 2; }
.card-header-custom { padding: 20px; border-bottom: 1px solid #f0f0f0; }
.card-header-custom h3 { font-size: 16px; font-weight: 600; color: #1a1d20; margin: 0; display: flex; align-items: center; gap: 10px; }
.card-header-custom h3 i { color: #0d6efd; }
.card-body-custom { padding: 20px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-weight: 500; color: #1a1d20; margin-bottom: 8px; }
.form-input, .form-select { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s ease; }
.form-input:focus, .form-select:focus { outline: none; border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1); }
.info-list { display: flex; flex-direction: column; gap: 12px; }
.info-item { display: flex; flex-direction: column; gap: 4px; }
.info-label { font-size: 12px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
.info-value { font-weight: 500; color: #1a1d20; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { padding: 12px; text-align: left; font-weight: 600; color: #6c757d; font-size: 13px; background: #f8f9fa; }
.data-table td { padding: 12px; border-top: 1px solid #f0f0f0; }
.product-info { display: flex; flex-direction: column; }
.product-info .variant { font-size: 13px; color: #6c757d; }
.text-right { text-align: right; }
.discount { color: #198754; font-weight: 600; }
.total-row { background: #f8f9fa; }
.total-row td { padding: 15px 12px; font-size: 16px; }
</style>
