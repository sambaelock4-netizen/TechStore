<?php
/**
 * TECHSTORE - Admin Stock Management Responsive
 */
?>

<div class="admin-wrapper">
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle d-lg-none" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay d-lg-none" onclick="toggleSidebar()"></div>

    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon"><i class="fas fa-store"></i></span>
            <span class="brand-text">TECHSTORE</span>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/admin" class="nav-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
            <a href="<?= BASE_URL ?>/admin/products" class="nav-item"><i class="fas fa-box"></i><span>Produits</span></a>
            <a href="<?= BASE_URL ?>/admin/orders" class="nav-item"><i class="fas fa-shopping-cart"></i><span>Commandes</span></a>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-item"><i class="fas fa-users"></i><span>Utilisateurs</span></a>
            <a href="<?= BASE_URL ?>/admin/categories" class="nav-item"><i class="fas fa-tags"></i><span>Catégories</span></a>
            <a href="<?= BASE_URL ?>/admin/stock" class="nav-item active"><i class="fas fa-warehouse"></i><span>Stock</span></a>
            <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item"><i class="fas fa-percent"></i><span>Promotions</span></a>
            <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item"><i class="fas fa-chart-bar"></i><span>Statistiques</span></a>
            <a href="<?= BASE_URL ?>/admin/profile" class="nav-item"><i class="fas fa-user-cog"></i><span>Profil</span></a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>/home" class="nav-item"><i class="fas fa-arrow-left"></i><span>Retour au site</span></a>
            <a href="<?= BASE_URL ?>/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-header flex-wrap gap-3">
            <div class="header-title">
                <h1>Gestion des Stocks</h1>
                <p class="text-muted">Surveillez et gérez vos niveaux de stock</p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/admin/stock/movements" class="btn btn-secondary">
                    <i class="fas fa-history"></i> <span class="d-none d-sm-inline">Historique</span>
                </a>
            </div>
        </header>

        <!-- Low Stock Alert -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="alert-box warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Alerte stock bas</strong>
                <p><?= count($lowStockProducts) ?> produit(s) ont un stock faible</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stock Update Form -->
        <div class="content-card mb-4">
            <div class="card-header-custom">
                <h3><i class="fas fa-plus-circle"></i> Mise à jour du stock</h3>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="<?= BASE_URL ?>/admin/stock/update" class="stock-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Produit</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Sélectionner un produit</option>
                                <?php foreach ($products as $p): ?>
                                    <?php $totalStock = ($p['stock'] ?? 0) + ($p['variant_stock'] ?? 0); ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['name']) ?> (Stock: <?= $totalStock ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-select">
                                <option value="entry">Entrée (+)</option>
                                <option value="exit">Sortie (-)</option>
                                <option value="adjustment">Ajustement</option>
                                <option value="return">Retour</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantité</label>
                            <input type="number" name="quantity" class="form-input" required min="1" value="1">
                        </div>
                        <div class="form-group">
                            <label>Raison</label>
                            <input type="text" name="reason" class="form-input" placeholder="Ex: Réapprovisionnement">
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> <span class="d-none d-sm-inline">Mettre à jour</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Stock Table -->
        <div class="content-card">
            <div class="card-header-custom">
                <h3><i class="fas fa-boxes"></i> État des stocks</h3>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th class="d-none d-lg-table-cell">Catégorie</th>
                            <th>Stock</th>
                            <th class="d-none d-md-table-cell">Alerte</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $p): ?>
                            <?php 
                                $variantStock = $p['variant_stock'] ?? 0;
                                $mainStock = $p['stock'] ?? 0;
                                $totalStock = $mainStock + $variantStock;
                                $alertLevel = $p['stock_alert'] ?? 5;
                            ?>
                            <tr class="<?= $totalStock <= $alertLevel ? 'low-stock' : '' ?>">
                                <td>
                                    <strong><?= htmlspecialchars($p['name']) ?></strong>
                                    <div class="d-lg-none small text-muted"><?= htmlspecialchars($p['category_name'] ?? '-') ?></div>
                                </td>
                                <td class="d-none d-lg-table-cell"><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                                <td><strong><?= $totalStock ?></strong></td>
                                <td class="d-none d-md-table-cell"><?= $alertLevel ?></td>
                                <td>
                                    <?php if ($totalStock == 0): ?>
                                        <span class="status-badge danger">Rupture</span>
                                    <?php elseif ($totalStock <= $alertLevel): ?>
                                        <span class="status-badge warning">Bas</span>
                                    <?php else: ?>
                                        <span class="status-badge success">OK</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="empty-cell">Aucun produit</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.querySelector('.sidebar-overlay').classList.toggle('show');
}

document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    if (window.innerWidth <= 991) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('show');
            document.querySelector('.sidebar-overlay').classList.remove('show');
        }
    }
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 991) {
        document.getElementById('sidebar').classList.remove('show');
        document.querySelector('.sidebar-overlay').classList.remove('show');
    }
});
</script>

<style>
.admin-wrapper { display: flex; min-height: calc(100vh - 76px); background: #f5f7fa; }

.admin-sidebar { 
    width: 260px; 
    background: linear-gradient(180deg, #1a1d20 0%, #2d3238 100%); 
    position: fixed; 
    height: calc(100vh - 76px); 
    transition: transform 0.3s ease;
    z-index: 1000;
}

.sidebar-brand { padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 12px; }
.brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #0d6efd, #0a58ca); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
.brand-text { color: white; font-size: 20px; font-weight: 700; }
.sidebar-nav { flex: 1; padding: 20px 0; }
.nav-item { display: flex; align-items: center; gap: 12px; padding: 14px 20px; color: rgba(255,255,255,0.7); text-decoration: none; border-left: 3px solid transparent; }
.nav-item:hover, .nav-item.active { background: rgba(13, 110, 253, 0.15); color: #0d6efd; border-left-color: #0d6efd; }
.sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
.logout-btn { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.7); text-decoration: none; }
.logout-btn:hover { color: #dc3545; }

.admin-main { flex: 1; margin-left: 260px; padding: 30px; transition: margin-left 0.3s ease; }
.admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header-title h1 { font-size: 28px; font-weight: 700; color: #1a1d20; margin: 0; }
.header-title .text-muted { color: #6c757d; margin: 5px 0 0; }

.btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; }
.btn-primary { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; border: none; cursor: pointer; }
.btn-secondary { background: #6c757d; color: white; }

.alert-box { padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
.alert-box.warning { background: #fff3cd; border: 1px solid #ffc107; }
.alert-box i { font-size: 24px; color: #856404; }
.alert-box strong { display: block; color: #856404; }
.alert-box p { margin: 5px 0 0; color: #856404; }

.content-card { background: white; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
.mb-4 { margin-bottom: 20px; }
.card-header-custom { padding: 20px; border-bottom: 1px solid #f0f0f0; }
.card-header-custom h3 { font-size: 16px; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 10px; }
.card-header-custom h3 i { color: #0d6efd; }
.card-body-custom { padding: 20px; }

.stock-form .form-row { display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
.form-group { flex: 1; min-width: 140px; }
.form-group label { display: block; font-weight: 500; margin-bottom: 8px; color: #1a1d20; font-size: 14px; }
.form-input, .form-select { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }

.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { padding: 15px; text-align: left; font-weight: 600; color: #6c757d; font-size: 13px; background: #f8f9fa; }
.data-table td { padding: 15px; border-top: 1px solid #f0f0f0; }
.data-table .low-stock { background: #fff8f8; }

.status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-badge.success { background: #d1e7dd; color: #0f5132; }
.status-badge.warning { background: #fff3cd; color: #856404; }
.status-badge.danger { background: #f8d7da; color: #842029; }
.empty-cell { text-align: center; padding: 60px 20px !important; color: #6c757d; }

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1100;
    background: #0d6efd;
    color: white;
    border: none;
    border-radius: 8px;
    width: 45px;
    height: 45px;
    font-size: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

/* Responsive */
@media (max-width: 991px) {
    .mobile-menu-toggle { display: flex; align-items: center; justify-content: center; }
    .admin-sidebar { transform: translateX(-100%); }
    .admin-sidebar.show { transform: translateX(0); }
    .sidebar-overlay.show { display: block; }
    .admin-main { margin-left: 0; padding: 70px 20px 20px; }
}

@media (max-width: 767px) {
    .admin-main { padding: 70px 10px 15px; }
    .header-title h1 { font-size: 20px; }
    .content-card { border-radius: 12px; margin-bottom: 15px; }
    .card-header-custom { padding: 15px; }
    .card-header-custom h3 { font-size: 14px; }
    .card-body-custom { padding: 15px; }
    .stock-form .form-row { gap: 10px; }
    .form-group { min-width: 100%; }
    .form-group label { font-size: 13px; }
    .form-input, .form-select { padding: 10px 12px; font-size: 13px; }
    .data-table th, .data-table td { padding: 12px 10px; font-size: 13px; }
    .alert-box { padding: 15px; flex-direction: column; text-align: center; }
    .alert-box i { font-size: 20px; }
}

@media (max-width: 480px) {
    .header-title h1 { font-size: 18px; }
    .data-table th, .data-table td { padding: 10px 8px; font-size: 12px; }
    .status-badge { padding: 4px 8px; font-size: 11px; }
}
</style>
