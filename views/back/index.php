<?php
/**
 * TECHSTORE - Admin Dashboard
 */
$adminName = isset($_SESSION['user']['firstname']) ? $_SESSION['user']['firstname'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
</head>
<body>
    <button class="ts-mobile-toggle"><i class="bi bi-list"></i></button>
    <div class="ts-overlay"></div>

    <div class="ts-layout">
        <!-- Sidebar -->
        <aside class="ts-sidebar" id="sidebar">
            <div class="ts-sidebar-brand">
                <div class="ts-brand-icon"><i class="fas fa-boxes-stacked"></i></div>
                <div>
                    <div class="ts-brand-name">TECHSTORE</div>
                    <span class="ts-brand-sub">Administration</span>
                </div>
            </div>
            <nav class="ts-sidebar-nav">
                <div class="ts-nav-section">Principal</div>
                <a href="<?= BASE_URL ?>/admin" class="ts-nav-item active">
                    <i class="fas fa-th-large"></i><span>Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/statistics" class="ts-nav-item">
                    <i class="fas fa-chart-bar"></i><span>Statistiques</span>
                </a>
                <div class="ts-nav-section">Catalogue</div>
                <a href="<?= BASE_URL ?>/admin/products" class="ts-nav-item">
                    <i class="fas fa-box"></i><span>Produits</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/categories" class="ts-nav-item">
                    <i class="fas fa-tags"></i><span>Catégories</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/promotions" class="ts-nav-item">
                    <i class="fas fa-percent"></i><span>Promotions</span>
                </a>
                <div class="ts-nav-section">Gestion</div>
                <a href="<?= BASE_URL ?>/admin/orders" class="ts-nav-item">
                    <i class="fas fa-shopping-cart"></i><span>Commandes</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/stock" class="ts-nav-item">
                    <i class="fas fa-warehouse"></i><span>Stock</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/users" class="ts-nav-item">
                    <i class="fas fa-users"></i><span>Utilisateurs</span>
                </a>
                <div class="ts-nav-section">Compte</div>
                <a href="<?= BASE_URL ?>/admin/profile" class="ts-nav-item">
                    <i class="fas fa-user-cog"></i><span>Mon Profil</span>
                </a>
            </nav>
            <div class="ts-sidebar-footer">
                <a href="<?= BASE_URL ?>/home" class="ts-logout-btn ts-back-link">
                    <i class="fas fa-arrow-left"></i><span>Retour au site</span>
                </a>
                <a href="<?= BASE_URL ?>/logout" class="ts-logout-btn">
                    <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main -->
        <main class="ts-main">
            <div class="ts-page-header">
                <div>
                    <h1 class="ts-page-title">Dashboard</h1>
                    <p class="ts-page-subtitle">Bienvenue, <strong><?= htmlspecialchars($adminName) ?></strong> — <?= date('l d/m/Y') ?></p>
                </div>
                <div class="ts-page-actions">
                    <span class="ts-badge ts-badge-success"><i class="fas fa-circle" style="font-size:7px"></i> En ligne</span>
                    <a href="<?= BASE_URL ?>/home" class="ts-btn ts-btn-secondary ts-btn-sm">
                        <i class="fas fa-external-link-alt"></i>
                        <span class="d-none d-md-inline">Voir le site</span>
                    </a>
                </div>
            </div>

            <div class="ts-page-body">

                <!-- Stats Cards -->
                <div class="ts-stats-grid">
                    <div class="ts-stat-card">
                        <div class="ts-stat-icon" style="background:var(--primary-bg); color:var(--primary)">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="ts-stat-label">Produits</div>
                            <div class="ts-stat-value"><?= $stats['total_products'] ?? 0 ?></div>
                        </div>
                    </div>
                    <div class="ts-stat-card">
                        <div class="ts-stat-icon" style="background:var(--purple-bg); color:var(--purple)">
                            <i class="bi bi-bag-check"></i>
                        </div>
                        <div>
                            <div class="ts-stat-label">Commandes</div>
                            <div class="ts-stat-value"><?= $stats['total_orders'] ?? 0 ?></div>
                        </div>
                    </div>
                    <div class="ts-stat-card">
                        <div class="ts-stat-icon" style="background:var(--success-bg); color:var(--success)">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <div class="ts-stat-label">Clients</div>
                            <div class="ts-stat-value"><?= $stats['total_users'] ?? 0 ?></div>
                        </div>
                    </div>
                    <div class="ts-stat-card">
                        <div class="ts-stat-icon" style="background:var(--warning-bg); color:var(--warning)">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <div class="ts-stat-label">Revenus</div>
                            <div class="ts-stat-value" style="font-size:18px"><?= displayPrice($stats['total_revenue'] ?? 0) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="ts-card">
                    <div class="ts-card-header">
                        <div class="ts-card-title">
                            <i class="fas fa-clock-rotate-left"></i>
                            Commandes récentes
                        </div>
                        <a href="<?= BASE_URL ?>/admin/orders" class="ts-btn ts-btn-secondary ts-btn-sm">
                            Voir tout <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="ts-card-body-flush">
                        <?php if (!empty($recentOrders)): ?>
                        <div class="ts-table-wrapper">
                            <table class="ts-table">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th class="d-none d-md-table-cell">Client</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th class="d-none d-lg-table-cell">Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($recentOrders as $order):
                                    $statusMap = [
                                        'en_attente'    => ['warning', 'En attente'],
                                        'confirme'      => ['info',    'Confirmé'],
                                        'en_preparation'=> ['primary', 'Préparation'],
                                        'expedie'       => ['purple',  'Expédié'],
                                        'livre'         => ['success', 'Livré'],
                                        'annule'        => ['danger',  'Annulé'],
                                    ];
                                    $s = $statusMap[$order['status']] ?? ['neutral', $order['status']];
                                ?>
                                <tr>
                                    <td><span class="fw-bold">#<?= $order['order_number'] ?? $order['id'] ?></span></td>
                                    <td class="d-none d-md-table-cell">
                                        <strong><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></strong>
                                    </td>
                                    <td><span class="fw-bold" style="color:var(--success)"><?= displayPrice($order['total_amount']) ?></span></td>
                                    <td><span class="ts-badge ts-badge-<?= $s[0] ?>"><?= $s[1] ?></span></td>
                                    <td class="d-none d-lg-table-cell" style="color:var(--text-muted)"><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" class="ts-action-btn ts-action-btn-view" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="ts-table-empty">
                            <i class="bi bi-bag"></i>
                            Aucune commande pour le moment
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="ts-card">
                    <div class="ts-card-header">
                        <div class="ts-card-title">
                            <i class="fas fa-bolt"></i>
                            Actions rapides
                        </div>
                    </div>
                    <div class="ts-card-body">
                        <div class="ts-quick-grid">
                            <a href="<?= BASE_URL ?>/admin/product/add" class="ts-quick-item">
                                <i class="bi bi-plus-circle" style="color:var(--primary)"></i>
                                <span>Nouveau produit</span>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/users/add" class="ts-quick-item">
                                <i class="bi bi-person-plus" style="color:var(--success)"></i>
                                <span>Nouveau client</span>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/categories/add" class="ts-quick-item">
                                <i class="bi bi-tag" style="color:var(--warning)"></i>
                                <span>Nouvelle catégorie</span>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/stock" class="ts-quick-item">
                                <i class="bi bi-graph-up-arrow" style="color:var(--info)"></i>
                                <span>Gérer le stock</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div><!-- /ts-page-body -->
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function(){
        const sidebar = document.querySelector('.ts-sidebar');
        const overlay = document.querySelector('.ts-overlay');
        const toggle  = document.querySelector('.ts-mobile-toggle');
        function open(){ sidebar.classList.add('open'); overlay.classList.add('open'); }
        function close(){ sidebar.classList.remove('open'); overlay.classList.remove('open'); }
        toggle.addEventListener('click', function(){ sidebar.classList.contains('open') ? close() : open(); });
        overlay.addEventListener('click', close);
        window.addEventListener('resize', function(){ if(window.innerWidth > 991) close(); });
    })();
    </script>
</body>
</html>
