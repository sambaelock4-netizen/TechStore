<?php
/**
 * TECHSTORE - Admin Promotions List Responsive
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
            <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item active"><i class="fas fa-percent"></i><span>Promotions</span></a>
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
                <h1>Gestion des Promotions</h1>
                <p class="text-muted">Créez et gérez vos promotions et codes promo</p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/admin/promotions/add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Nouvelle promotion</span>
                </a>
            </div>
        </header>

        <div class="content-card">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th class="d-none d-md-table-cell">Code</th>
                            <th>Valeur</th>
                            <th class="d-none d-lg-table-cell">Période</th>
                            <th class="d-none d-sm-table-cell">Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($promotions)): ?>
                            <?php foreach ($promotions as $promo): ?>
                            <?php 
                                $now = new DateTime();
                                $start = new DateTime($promo['start_date']);
                                $end = new DateTime($promo['end_date']);
                                $isActive = $promo['is_active'] && $now >= $start && $now <= $end;
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($promo['name']) ?></strong></td>
                                <td class="d-none d-md-table-cell"><code><?= htmlspecialchars($promo['code'] ?? '-') ?></code></td>
                                <td>
                                    <strong>
                                        <?= $promo['type'] === 'percentage' ? $promo['value'] . '%' : displayPrice($promo['value']) ?>
                                    </strong>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="period-info">
                                        <span><?= date('d/m/Y', strtotime($promo['start_date'])) ?></span>
                                        <span>- <?= date('d/m/Y', strtotime($promo['end_date'])) ?></span>
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <span class="status-badge <?= $isActive ? 'active' : 'inactive' ?>">
                                        <?= $isActive ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>/admin/promotions/edit/<?= $promo['id'] ?>" class="btn-action btn-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/admin/promotions/delete/<?= $promo['id'] ?>" class="btn-action btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr ?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-cell">
                                    <div class="empty-state">
                                        <i class="fas fa-tags"></i>
                                        <p>Aucune promotion</p>
                                    </div>
                                </td>
                            </tr>
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
.btn-primary { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; }

.content-card { background: white; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); overflow: hidden; }
.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { padding: 15px 20px; text-align: left; font-weight: 600; color: #6c757d; font-size: 13px; background: #f8f9fa; }
.data-table td { padding: 15px 20px; border-top: 1px solid #f0f0f0; }
code { background: #f1f3f5; padding: 4px 8px; border-radius: 4px; font-size: 13px; }
.period-info { display: flex; flex-direction: column; font-size: 13px; color: #6c757d; }
.status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-badge.active { background: #d1e7dd; color: #0f5132; }
.status-badge.inactive { background: #e2e3e5; color: #41464b; }
.action-buttons { display: flex; gap: 8px; }
.btn-action { width: 36px; height: 36px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
.btn-edit { background: #e7f5ff; color: #0d6efd; }
.btn-edit:hover { background: #0d6efd; color: white; }
.btn-delete { background: #ffe7e7; color: #dc3545; }
.btn-delete:hover { background: #dc3545; color: white; }
.empty-cell { text-align: center; padding: 60px 20px !important; }
.empty-state { display: flex; flex-direction: column; align-items: center; gap: 10px; }
.empty-state i { font-size: 48px; color: #dee2e6; }
.empty-state p { color: #6c757d; margin: 0; }

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
    .content-card { border-radius: 12px; }
    .data-table th, .data-table td { padding: 12px 10px; font-size: 13px; }
    .btn-action { width: 32px; height: 32px; }
    .btn-action i { font-size: 14px; }
}

@media (max-width: 480px) {
    .header-title h1 { font-size: 18px; }
    .data-table th, .data-table td { padding: 10px 8px; font-size: 12px; }
    code { padding: 2px 5px; font-size: 11px; }
}
</style>
