<?php
/**
 * TECHSTORE - Admin Dashboard ULTRA PREMIUM
 */

// Set page variables
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';

// Prepare KPI content
ob_start();
?>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon primary">
                <i class="bi bi-box-seam"></i>
            </div>
            <?php if (isset($stats['products_change'])): ?>
            <span class="kpi-trend <?= $stats['products_change'] >= 0 ? 'up' : 'down' ?>">
                <i class="bi bi-arrow-<?= $stats['products_change'] >= 0 ? 'up' : 'down' ?>-short"></i>
                <?= abs($stats['products_change']) ?>%
            </span>
            <?php endif; ?>
        </div>
        <div class="kpi-value"><?= number_format($stats['total_products'] ?? 0) ?></div>
        <div class="kpi-label">Produits</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon success">
                <i class="bi bi-bag"></i>
            </div>
            <?php if (isset($stats['orders_change'])): ?>
            <span class="kpi-trend <?= $stats['orders_change'] >= 0 ? 'up' : 'down' ?>">
                <i class="bi bi-arrow-<?= $stats['orders_change'] >= 0 ? 'up' : 'down' ?>-short"></i>
                <?= abs($stats['orders_change']) ?>%
            </span>
            <?php endif; ?>
        </div>
        <div class="kpi-value"><?= number_format($stats['total_orders'] ?? 0) ?></div>
        <div class="kpi-label">Commandes</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon info">
                <i class="bi bi-people"></i>
            </div>
            <?php if (isset($stats['users_change'])): ?>
            <span class="kpi-trend <?= $stats['users_change'] >= 0 ? 'up' : 'down' ?>">
                <i class="bi bi-arrow-<?= $stats['users_change'] >= 0 ? 'up' : 'down' ?>-short"></i>
                <?= abs($stats['users_change']) ?>%
            </span>
            <?php endif; ?>
        </div>
        <div class="kpi-value"><?= number_format($stats['total_users'] ?? 0) ?></div>
        <div class="kpi-label">Utilisateurs</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon warning">
                <i class="bi bi-currency-exchange"></i>
            </div>
            <?php if (isset($stats['revenue_change'])): ?>
            <span class="kpi-trend <?= $stats['revenue_change'] >= 0 ? 'up' : 'down' ?>">
                <i class="bi bi-arrow-<?= $stats['revenue_change'] >= 0 ? 'up' : 'down' ?>-short"></i>
                <?= abs($stats['revenue_change']) ?>%
            </span>
            <?php endif; ?>
        </div>
        <div class="kpi-value"><?= displayPrice($stats['total_revenue'] ?? 0) ?></div>
        <div class="kpi-label">Revenus Totaux</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="content-card mb-4">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-lightning-charge"></i>
            Actions Rapides
        </h3>
        <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i>
            Nouveau Produit
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="<?= BASE_URL ?>/admin/product/add" class="quick-action-card">
                    <div class="quick-action-icon primary">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <span>Nouveau Produit</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?= BASE_URL ?>/admin/users/add" class="quick-action-card">
                    <div class="quick-action-icon success">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <span>Nouveau Client</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?= BASE_URL ?>/admin/categories/add" class="quick-action-card">
                    <div class="quick-action-icon warning">
                        <i class="bi bi-tag"></i>
                    </div>
                    <span>Nouvelle Catégorie</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?= BASE_URL ?>/admin/statistics" class="quick-action-card">
                    <div class="quick-action-icon info">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <span>Statistiques</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-clock-history"></i>
            Commandes Récentes
        </h3>
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-secondary btn-sm">
            Voir Tout <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($recentOrders)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>
                            <span class="fw-bold">#<?= $order['order_number'] ?? $order['id'] ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-mini">
                                    <?= strtoupper(substr($order['firstname'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-success"><?= displayPrice($order['total_amount']) ?></span>
                        </td>
                        <td>
                            <?php
                            $statusConfig = [
                                'en_attente' => ['class' => 'warning', 'label' => 'En attente'],
                                'confirme' => ['class' => 'info', 'label' => 'Confirmé'],
                                'en_preparation' => ['class' => 'primary', 'label' => 'Préparation'],
                                'expedie' => ['class' => 'secondary', 'label' => 'Expédié'],
                                'livre' => ['class' => 'success', 'label' => 'Livré'],
                                'annule' => ['class' => 'danger', 'label' => 'Annulé']
                            ];
                            $status = $statusConfig[$order['status']] ?? ['class' => 'warning', 'label' => $order['status']];
                            ?>
                            <span class="badge badge-<?= $status['class'] ?>"><?= $status['label'] ?></span>
                        </td>
                        <td>
                            <span class="text-muted"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" class="btn btn-icon btn-secondary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Aucune commande récente</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Quick Action Cards */
.quick-action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 24px 16px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: var(--text-secondary);
    transition: all var(--transition-base);
}

.quick-action-card:hover {
    background: var(--glass-bg-hover);
    border-color: var(--glass-border-hover);
    transform: translateY(-4px);
    color: var(--text-primary);
    box-shadow: var(--shadow-lg);
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.quick-action-icon.primary {
    background: rgba(99, 102, 241, 0.15);
    color: var(--primary-light);
}

.quick-action-icon.success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--success);
}

.quick-action-icon.warning {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning);
}

.quick-action-icon.info {
    background: rgba(14, 165, 233, 0.15);
    color: var(--info);
}

.quick-action-card span {
    font-size: 13px;
    font-weight: 500;
}

/* User Avatar Mini */
.user-avatar-mini {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-tertiary);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state p {
    font-size: 14px;
}

/* MB-4 */
.mb-4 {
    margin-bottom: 24px;
}

/* Table enhancements */
.data-table tbody tr {
    transition: all var(--transition-fast);
}

.data-table tbody tr:hover {
    background: var(--glass-bg-hover);
}

.data-table tbody td {
    padding: 14px 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .quick-action-card {
        padding: 20px 12px;
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .quick-action-card span {
        font-size: 12px;
    }
}
</style>

<?php
$content = ob_get_clean();

// Include the layout
include __DIR__ . '/layout.php';

