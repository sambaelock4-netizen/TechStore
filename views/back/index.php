<?php
/**
 * ==================================================================================
 * TechStore - Tableau de Bord Administration (Dashboard)
 * ==================================================================================
 * Ce fichier est le point d'entrée du back-office. Il présente :
 * 1. Des indicateurs clés de performance (KPIs) : Produits, Commandes, Revenus.
 * 2. Un graphique de tendance des ventes sur les 7 derniers jours.
 * 3. Des alertes sur le stock (produits proches de la rupture).
 * 4. Un aperçu des dernières commandes passées.
 * 5. Des raccourcis vers les actions courantes de gestion.
 * ==================================================================================
 */

// Nom de l'administrateur pour la personnalisation
$adminName = isset($_SESSION['user']['firstname']) ? $_SESSION['user']['firstname'] : 'Admin';

/**
 * 1. RÉCUPÉRATION DES STATISTIQUES (KPIs)
 * Calcul des totaux pour les cartes du haut.
 */
// Nombre total de produits actifs
$s = $pdo->query("SELECT COUNT(*) FROM products WHERE is_active=1");
$stats['total_products'] = $s->fetchColumn();

// Nombre total de commandes non annulées
$s = $pdo->query("SELECT COUNT(*) FROM orders WHERE status != 'annule'");
$stats['total_orders'] = $s->fetchColumn();

// Nombre de clients enregistrés
$s = $pdo->query("SELECT COUNT(*) FROM users WHERE role='client'");
$stats['total_users'] = $s->fetchColumn();

// Chiffre d'affaires total (Somme des montants de commandes validées)
$s = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status != 'annule'");
$stats['total_revenue'] = $s->fetchColumn();

/**
 * 2. COMMANDES RÉCENTES
 * Récupération des 8 dernières commandes avec informations clients.
 */
$s = $pdo->query("SELECT o.*, u.firstname, u.lastname FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 8");
$recentOrders = $s->fetchAll();

/**
 * 3. ALERTES DE STOCK BAS
 * Identifie les produits dont le stock est <= 5.
 */
$s = $pdo->query("SELECT id, name, stock, image FROM products WHERE stock <= 5 AND is_active=1 ORDER BY stock ASC LIMIT 5");
$lowStock = $s->fetchAll();

/**
 * 4. TENDANCE DES VENTES (CHARTS)
 * Prépare les données pour le graphique Chart.js (7 derniers jours).
 */
$trendLabels = []; $trendData = [];
for($i=6; $i>=0; $i--){
  $d = date('Y-m-d', strtotime("-$i days"));
  $trendLabels[] = date('d/m', strtotime($d)); // Label formatté pour l'axe X
  
  // Somme des ventes pour ce jour spécifique
  $s = $pdo->prepare("SELECT SUM(total_amount) FROM orders WHERE DATE(created_at) = ? AND status != 'annule'");
  $s->execute([$d]);
  $trendData[] = (float)($s->fetchColumn() ?? 0);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
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
                        <div class="ts-stat-icon" style="background:var(--primary-bg); color:var(--primary)"><i class="fas fa-boxes"></i></div>
                        <div><div class="ts-stat-label">Produits actifs</div><div class="ts-stat-value"><?= number_format($stats['total_products']) ?></div></div>
                    </div>
                    <div class="ts-stat-card">
                        <div class="ts-stat-icon" style="background:var(--purple-bg); color:var(--purple)"><i class="fas fa-shopping-bag"></i></div>
                        <div><div class="ts-stat-label">Total commandes</div><div class="ts-stat-value"><?= number_format($stats['total_orders']) ?></div></div>
                    </div>
                    <div class="ts-stat-card">
                        <div class="ts-stat-icon" style="background:var(--success-bg); color:var(--success)"><i class="fas fa-users"></i></div>
                        <div><div class="ts-stat-label">Total clients</div><div class="ts-stat-value"><?= number_format($stats['total_users']) ?></div></div>
                    </div>
                    <div class="ts-stat-card">
                        <div class="ts-stat-icon" style="background:var(--warning-bg); color:var(--warning)"><i class="fas fa-coins"></i></div>
                        <div><div class="ts-stat-label">Chiffre d'affaires</div><div class="ts-stat-value" style="font-size:18px"><?= displayPrice($stats['total_revenue']) ?></div></div>
                    </div>
                </div>

                <div class="row g-4" style="margin-top:0">
                    <!-- Tendances (7 jours) -->
                    <div class="col-lg-8">
                        <div class="ts-card" style="height:100%">
                            <div class="ts-card-header">
                                <div class="ts-card-title"><i class="fas fa-chart-line"></i> Tendance des ventes (7 derniers jours)</div>
                            </div>
                            <div class="ts-card-body">
                                <div style="height:250px"><canvas id="trendChart"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <!-- Alertes Stock -->
                    <div class="col-lg-4">
                        <div class="ts-card" style="height:100%">
                            <div class="ts-card-header">
                                <div class="ts-card-title"><i class="fas fa-exclamation-triangle" style="color:var(--danger)"></i> Alertes Stock</div>
                                <a href="<?= BASE_URL ?>/admin/stock" class="ts-btn ts-btn-secondary ts-btn-sm" style="font-size:10px">Gérer</a>
                            </div>
                            <div class="ts-card-body-flush">
                                <?php if(!empty($lowStock)): foreach($lowStock as $ls): ?>
                                <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border-light)">
                                    <div style="width:36px;height:36px;background:#f8f9fa;border-radius:8px;display:flex;align-items:center;justify-content:center;overflow:hidden">
                                        <?php if($ls['image']): ?><img src="<?=UPLOAD_URL.'/'.$ls['image']?>" style="width:100%;height:100%;object-fit:cover"><?php else: ?><i class="fas fa-image" style="color:#dee2e6"></i><?php endif; ?>
                                    </div>
                                    <div style="flex:1">
                                        <div style="font-size:12px;font-weight:600;color:var(--text);line-height:1.2;margin-bottom:2px"><?=htmlspecialchars($ls['name'])?></div>
                                        <div style="font-size:10px;color:var(--text-muted)">ID: #<?=$ls['id']?></div>
                                    </div>
                                    <span class="ts-badge <?=$ls['stock']<=0?'ts-badge-danger':'ts-badge-warning'?>" style="font-size:10px"><?=$ls['stock']?> restants</span>
                                </div>
                                <?php endforeach; else: ?>
                                <div style="padding:40px 20px;text-align:center;color:var(--text-muted);font-size:12px;font-style:italic">Aucune alerte stock</div>
                                <?php endif; ?>
                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    (function(){
        const sidebar = document.querySelector('.ts-sidebar'), overlay = document.querySelector('.ts-overlay'), toggle  = document.querySelector('.ts-mobile-toggle');
        function close(){ sidebar.classList.remove('open'); overlay.classList.remove('open'); }
        toggle.addEventListener('click', function(){ sidebar.classList.contains('open') ? close() : (sidebar.classList.add('open'),overlay.classList.add('open')); });
        overlay.addEventListener('click', close);
        window.addEventListener('resize', function(){ if(window.innerWidth > 991) close(); });

        // Trend Chart
        const ctx = document.getElementById('trendChart')?.getContext('2d');
        if(ctx){
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($trendLabels) ?>,
                    datasets: [{
                        label: 'Ventes',
                        data: <?= json_encode($trendData) ?>,
                        borderColor: '#ffa07a',
                        backgroundColor: 'rgba(255,160,122,0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffa07a',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, grid: { color: 'rgba(200,200,200,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    })();
    </script>
</body>
</html>
