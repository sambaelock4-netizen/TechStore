<?php
/**
 * TECHSTORE - Admin Statistics Responsive
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
            <a href="<?= BASE_URL ?>/admin/stock" class="nav-item"><i class="fas fa-warehouse"></i><span>Stock</span></a>
            <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item"><i class="fas fa-percent"></i><span>Promotions</span></a>
            <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item active"><i class="fas fa-chart-bar"></i><span>Statistiques</span></a>
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
                <h1>Statistiques et Rapports</h1>
                <p class="text-muted">Analysez les performances de votre boutique</p>
            </div>
            <div class="header-actions">
                <div class="export-dropdown">
                    <button class="btn btn-secondary dropdown-toggle">
                        <i class="fas fa-download"></i> <span class="d-none d-sm-inline">Exporter</span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="<?= BASE_URL ?>/admin/export?type=orders&format=csv"><i class="fas fa-file-csv"></i> CSV</a>
                        <a href="<?= BASE_URL ?>/admin/export?type=orders&format=excel"><i class="fas fa-file-excel"></i> Excel</a>
                        <a href="<?= BASE_URL ?>/admin/export?type=products&format=csv"><i class="fas fa-file-csv"></i> Produits CSV</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Date Filter -->
        <div class="filters-bar">
            <form method="GET" action="<?= BASE_URL ?>/admin/statistics" class="filters-form flex-wrap">
                <div class="filter-group">
                    <label>Période</label>
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="today" <?= ($period ?? '') === 'today' ? 'selected' : '' ?>>Aujourd'hui</option>
                        <option value="week" <?= ($period ?? '') === 'week' ? 'selected' : '' ?>>Cette semaine</option>
                        <option value="month" <?= ($period ?? '') === 'month' ? 'selected' : '' ?>>Ce mois</option>
                        <option value="year" <?= ($period ?? '') === 'year' ? 'selected' : '' ?>>Cette année</option>
                        <option value="custom" <?= ($period ?? '') === 'custom' ? 'selected' : '' ?>>Personnalisé</option>
                    </select>
                </div>
                <?php if (($period ?? '') === 'custom'): ?>
                <div class="filter-group">
                    <label>Du</label>
                    <input type="date" name="date_from" value="<?= $dateFrom ?? '' ?>" class="form-input">
                </div>
                <div class="filter-group">
                    <label>Au</label>
                    <input type="date" name="date_to" value="<?= $dateTo ?? '' ?>" class="form-input">
                </div>
                <button type="submit" class="btn btn-secondary">Appliquer</button>
                <?php endif; ?>
            </form>
        </div>

        <!-- Key Metrics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon revenue"><i class="fas fa-coins"></i></div>
                <div class="stat-content">
                    <span class="stat-label">Revenu total</span>
                    <span class="stat-value"><?= displayPrice($stats['total_revenue'] ?? 0) ?></span>
                    <span class="stat-change <?= ($stats['revenue_change'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                        <?= ($stats['revenue_change'] ?? 0) >= 0 ? '+' : '' ?><?= $stats['revenue_change'] ?? 0 ?>%
                    </span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orders"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-content">
                    <span class="stat-label">Commandes</span>
                    <span class="stat-value"><?= $stats['total_orders'] ?? 0 ?></span>
                    <span class="stat-change <?= ($stats['orders_change'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                        <?= ($stats['orders_change'] ?? 0) >= 0 ? '+' : '' ?><?= $stats['orders_change'] ?? 0 ?>%
                    </span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon customers"><i class="fas fa-users"></i></div>
                <div class="stat-content">
                    <span class="stat-label">Nouveaux clients</span>
                    <span class="stat-value"><?= $stats['new_customers'] ?? 0 ?></span>
                    <span class="stat-change <?= ($stats['customers_change'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                        <?= ($stats['customers_change'] ?? 0) >= 0 ? '+' : '' ?><?= $stats['customers_change'] ?? 0 ?>%
                    </span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon avg"><i class="fas fa-chart-line"></i></div>
                <div class="stat-content">
                    <span class="stat-label">Panier moyen</span>
                    <span class="stat-value"><?= displayPrice($stats['avg_order_value'] ?? 0) ?></span>
                    <span class="stat-change <?= ($stats['avg_change'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                        <?= ($stats['avg_change'] ?? 0) >= 0 ? '+' : '' ?><?= $stats['avg_change'] ?? 0 ?>%
                    </span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-grid">
            <div class="content-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-chart-line"></i> Ventes par jour</h3>
                </div>
                <div class="card-body-custom">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
            <div class="content-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-chart-pie"></i> Ventes par catégorie</h3>
                </div>
                <div class="card-body-custom">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="content-card mt-4">
            <div class="card-header-custom">
                <h3><i class="fas fa-trophy"></i> Top produits</h3>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr><th>Rang</th><th>Produit</th><th>Quantité</th><th>Chiffre d'affaires</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topProducts)): ?>
                            <?php foreach ($topProducts as $index => $product): ?>
                            <tr>
                                <td><span class="rank-badge <?= $index < 3 ? 'top' : '' ?>"><?= $index + 1 ?></span></td>
                                <td><strong><?= htmlspecialchars($product['name']) ?></strong></td>
                                <td><?= $product['quantity_sold'] ?></td>
                                <td><strong><?= displayPrice($product['revenue']) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="empty-cell">Aucune donnée</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="content-card mt-4">
            <div class="card-header-custom">
                <h3><i class="fas fa-star"></i> Meilleurs clients</h3>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr><th>Rang</th><th>Client</th><th>Commandes</th><th>Total dépensé</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topCustomers)): ?>
                            <?php foreach ($topCustomers as $index => $customer): ?>
                            <tr>
                                <td><span class="rank-badge <?= $index < 3 ? 'top' : '' ?>"><?= $index + 1 ?></span></td>
                                <td><strong><?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?></strong></td>
                                <td><?= $customer['order_count'] ?></td>
                                <td><strong><?= displayPrice($customer['total_spent']) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="empty-cell">Aucune donnée</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const salesCtx = document.getElementById('salesChart')?.getContext('2d');
if (salesCtx) {
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($salesLabels ?? ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']) ?>,
            datasets: [{
                label: 'Ventes (FC)',
                data: <?= json_encode($salesData ?? [0, 0, 0, 0, 0, 0, 0]) ?>,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

// Category Chart
const categoryCtx = document.getElementById('categoryChart')?.getContext('2d');
if (categoryCtx) {
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($categoryLabels ?? ['Catégorie A', 'Catégorie B']) ?>,
            datasets: [{
                data: <?= json_encode($categoryData ?? [1, 1]) ?>,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

// Mobile sidebar toggle
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

/* Sidebar */
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

/* Main */
.admin-main { flex: 1; margin-left: 260px; padding: 30px; transition: margin-left 0.3s ease; }
.admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header-title h1 { font-size: 28px; font-weight: 700; color: #1a1d20; margin: 0; }
.header-title .text-muted { color: #6c757d; margin: 5px 0 0; }

.btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; }
.btn-secondary { background: #6c757d; color: white; border: none; cursor: pointer; }
.export-dropdown { position: relative; }
.dropdown-menu { display: none; position: absolute; right: 0; top: 100%; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); min-width: 180px; z-index: 100; }
.export-dropdown:hover .dropdown-menu { display: block; }
.dropdown-menu a { display: flex; align-items: center; gap: 10px; padding: 12px 15px; color: #1a1d20; text-decoration: none; }
.dropdown-menu a:hover { background: #f8f9fa; }

/* Filters */
.filters-bar { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.filters-form { display: flex; gap: 15px; align-items: flex-end; }
.filter-group label { display: block; font-size: 13px; color: #6c757d; margin-bottom: 5px; }
.form-input, .form-select { padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; border-radius: 16px; padding: 25px; display: flex; gap: 20px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
.stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
.stat-icon.revenue { background: rgba(25, 135, 84, 0.1); color: #198754; }
.stat-icon.orders { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.stat-icon.customers { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }
.stat-icon.avg { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
.stat-content { display: flex; flex-direction: column; }
.stat-label { font-size: 14px; color: #6c757d; }
.stat-value { font-size: 28px; font-weight: 700; color: #1a1d20; margin: 5px 0; }
.stat-change { font-size: 13px; font-weight: 600; }
.stat-change.positive { color: #198754; }
.stat-change.negative { color: #dc3545; }

/* Charts */
.charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.content-card { background: white; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
.mt-4 { margin-top: 20px; }
.card-header-custom { padding: 20px; border-bottom: 1px solid #f0f0f0; }
.card-header-custom h3 { font-size: 16px; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 10px; }
.card-header-custom h3 i { color: #0d6efd; }
.card-body-custom { padding: 20px; height: 350px; }
.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { padding: 15px; text-align: left; font-weight: 600; color: #6c757d; font-size: 13px; background: #f8f9fa; }
.data-table td { padding: 15px; border-top: 1px solid #f0f0f0; }
.rank-badge { width: 30px; height: 30px; border-radius: 50%; background: #f1f3f5; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; }
.rank-badge.top { background: #ffc107; color: white; }
.empty-cell { text-align: center; padding: 40px 20px !important; color: #6c757d; }

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
@media (max-width: 1199px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .stat-card { padding: 20px; }
    .stat-value { font-size: 24px; }
}

@media (max-width: 991px) {
    .mobile-menu-toggle { display: flex; align-items: center; justify-content: center; }
    
    .admin-sidebar { transform: translateX(-100%); }
    .admin-sidebar.show { transform: translateX(0); }
    .sidebar-overlay.show { display: block; }
    
    .admin-main { margin-left: 0; padding: 70px 20px 20px; }
}

@media (max-width: 767px) {
    .admin-main { padding: 70px 10px 15px; }
    .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
    .stat-card { padding: 15px; border-radius: 12px; gap: 12px; }
    .stat-icon { width: 45px; height: 45px; font-size: 18px; }
    .stat-value { font-size: 20px; }
    .stat-label { font-size: 12px; }
    .charts-grid { grid-template-columns: 1fr; gap: 15px; }
    .content-card { border-radius: 12px; margin-bottom: 15px; }
    .card-header-custom { padding: 15px; }
    .card-header-custom h3 { font-size: 14px; }
    .card-body-custom { padding: 15px; height: 280px; }
    .header-title h1 { font-size: 20px; }
    .filters-bar { padding: 15px; }
    .filters-form { gap: 10px; }
    .filter-group { flex: 1; min-width: 45%; }
}

@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
    .stat-card { padding: 12px; gap: 10px; }
    .stat-icon { width: 36px; height: 36px; font-size: 16px; }
    .stat-value { font-size: 16px; }
    .stat-change { font-size: 11px; }
    .data-table th, .data-table td { padding: 10px 8px; font-size: 13px; }
}
</style>
