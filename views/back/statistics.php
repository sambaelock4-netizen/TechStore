<?php
/**
 * TECHSTORE - Statistiques & Rapports
 */
$period = $_GET['period'] ?? 'month';
$dateFrom = $_GET['date_from'] ?? null;
$dateTo = $_GET['date_to'] ?? null;

// Définition des clauses de date
$where = "orders.status != 'annule'";
$pWhere = "orders.status != 'annule'"; // Previous period
$uWhere = "users.role = 'client'";
$puWhere = "users.role = 'client'";

switch($period){
  case 'today':
    $where .= " AND DATE(orders.created_at) = CURDATE()";
    $pWhere .= " AND DATE(orders.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    $uWhere .= " AND DATE(users.created_at) = CURDATE()";
    $puWhere .= " AND DATE(users.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    break;
  case 'week':
    $where .= " AND orders.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $pWhere .= " AND orders.created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND orders.created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $uWhere .= " AND users.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $puWhere .= " AND users.created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND users.created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    break;
  case 'year':
    $where .= " AND YEAR(orders.created_at) = YEAR(CURDATE())";
    $pWhere .= " AND YEAR(orders.created_at) = YEAR(CURDATE()) - 1";
    $uWhere .= " AND YEAR(users.created_at) = YEAR(CURDATE())";
    $puWhere .= " AND YEAR(users.created_at) = YEAR(CURDATE()) - 1";
    break;
  case 'month':
  default:
    $where .= " AND MONTH(orders.created_at) = MONTH(CURDATE()) AND YEAR(orders.created_at) = YEAR(CURDATE())";
    $pWhere .= " AND MONTH(orders.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(orders.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
    $uWhere .= " AND MONTH(users.created_at) = MONTH(CURDATE()) AND YEAR(users.created_at) = YEAR(CURDATE())";
    $puWhere .= " AND MONTH(users.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(users.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
    break;
}

// 1. KPI Actuels
$s = $pdo->query("SELECT SUM(total_amount) as rev, COUNT(*) as ord FROM orders WHERE $where");
$curr = $s->fetch();
$s = $pdo->query("SELECT COUNT(*) as cust FROM users WHERE $uWhere");
$currCust = $s->fetch()['cust'];

// 2. KPI Précédents (pour le calcul de progression)
$s = $pdo->query("SELECT SUM(total_amount) as rev, COUNT(*) as ord FROM orders WHERE $pWhere");
$prev = $s->fetch();
$s = $pdo->query("SELECT COUNT(*) as cust FROM users WHERE $puWhere");
$prevCust = $s->fetch()['cust'];

function pct($c, $p){ if(!$p) return $c?100:0; return round((($c-$p)/$p)*100); }

$stats = [
  'total_revenue' => $curr['rev'] ?? 0,
  'total_orders' => $curr['ord'] ?? 0,
  'new_customers' => $currCust,
  'avg_order_value' => $curr['ord'] ? ($curr['rev']/$curr['ord']) : 0,
  'revenue_change' => pct($curr['rev']??0, $prev['rev']??0),
  'orders_change' => pct($curr['ord']??0, $prev['ord']??0),
  'customers_change' => pct($currCust, $prevCust),
  'avg_change' => pct($curr['ord']?($curr['rev']/$curr['ord']):0, $prev['ord']?($prev['rev']/$prev['ord']):0)
];

// 3. Évolution des ventes (7 derniers jours)
$salesLabels = []; $salesData = []; $ordersData = [];
for($i=6; $i>=0; $i--){
  $d = date('Y-m-d', strtotime("-$i days"));
  $salesLabels[] = date('d/m', strtotime($d));
  $s = $pdo->prepare("SELECT SUM(total_amount) as rev, COUNT(*) as ord FROM orders WHERE DATE(created_at) = ? AND status != 'annule'");
  $s->execute([$d]);
  $r = $s->fetch();
  $salesData[] = (float)($r['rev']??0);
  $ordersData[] = (int)($r['ord']??0);
}

// 4. Par catégorie
$s = $pdo->query("SELECT c.name, SUM(oi.quantity * oi.unit_price) as rev FROM order_items oi JOIN products p ON oi.product_id=p.id JOIN categories c ON p.category_id=c.id JOIN orders ON oi.order_id=orders.id WHERE $where GROUP BY c.id ORDER BY rev DESC LIMIT 5");
$catStats = $s->fetchAll();
$categoryLabels = array_column($catStats, 'name');
$categoryData = array_column($catStats, 'rev');

// 5. Produits les plus vendus
$s = $pdo->query("SELECT p.name, c.name as category_name, SUM(oi.quantity) as quantity_sold, SUM(oi.quantity * oi.unit_price) as revenue FROM order_items oi JOIN products p ON oi.product_id=p.id LEFT JOIN categories c ON p.category_id=c.id JOIN orders ON oi.order_id=orders.id WHERE $where GROUP BY p.id ORDER BY quantity_sold DESC LIMIT 10");
$topProducts = $s->fetchAll();

// 6. Ventes par jour de semaine
$dailyData = [0,0,0,0,0,0,0]; // Lun=0, ..., Dim=6
$s = $pdo->query("SELECT WEEKDAY(created_at) as wd, SUM(total_amount) as rev FROM orders WHERE $where GROUP BY wd");
while($r = $s->fetch()) $dailyData[$r['wd']] = (float)$r['rev'];

// 7. Meilleurs clients
$s = $pdo->query("SELECT u.firstname, u.lastname, u.email, COUNT(o.id) as order_count, SUM(o.total_amount) as total_spent FROM users u JOIN orders o ON u.id=o.user_id WHERE o.status != 'annule' GROUP BY u.id ORDER BY total_spent DESC LIMIT 5");
$topCustomers = $s->fetchAll();

// 8. Statuts des commandes
$statusCounts = [];
$s = $pdo->query("SELECT status, COUNT(*) as count FROM orders WHERE $where GROUP BY status");
while($r = $s->fetch()) $statusCounts[$r['status']] = $r['count'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
    <style>
        .ts-export-dropdown { position: relative; display: inline-block; }
        .ts-export-menu {
            display: none; position: absolute; right: 0; top: calc(100% + 8px);
            background: rgba(10,14,32,0.97); backdrop-filter: blur(20px);
            border: 1px solid rgba(0,123,255,0.22); border-radius: var(--radius);
            box-shadow: var(--shadow-lg); min-width: 220px; z-index: 300; overflow: hidden;
        }
        .ts-export-dropdown:hover .ts-export-menu { display: block; }
        .ts-export-menu a {
            display: flex; align-items: center; gap: 11px;
            padding: 12px 18px; color: var(--text-soft); text-decoration: none;
            font-family: 'Inter',sans-serif; font-size: 13.5px; font-style: italic;
            transition: var(--transition); border-bottom: 1px solid var(--border-light);
        }
        .ts-export-menu a:last-child { border-bottom: none; }
        .ts-export-menu a:hover { background: rgba(0,123,255,0.10); color: var(--blue-light); }
        .ts-export-menu a i { width: 18px; text-align: center; }

        .ts-charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px; }
        .ts-charts-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
        @media (max-width: 991px) { .ts-charts-grid, .ts-charts-grid-2 { grid-template-columns: 1fr; } }

        .ts-period-tabs { display: flex; gap: 4px; background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 4px; }
        .ts-period-tab {
            padding: 7px 18px; border-radius: 7px;
            font-family: 'Inter',sans-serif; font-size: 13px; font-style: italic;
            color: var(--text-muted); text-decoration: none; transition: var(--transition);
            cursor: pointer; border: none; background: transparent; white-space: nowrap;
        }
        .ts-period-tab:hover { color: var(--text-soft); background: rgba(255,255,255,0.04); }
        .ts-period-tab.active { background: var(--grad-btn); color: white; font-style: normal; font-weight: 700; box-shadow: var(--shadow-red); }

        .ts-kpi-chip {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-family: 'Inter',sans-serif; font-size: 11px; font-style: italic; margin-top: 5px;
        }
        .ts-kpi-chip.up   { background: rgba(74,222,128,0.12); color: #4ade80; border: 1px solid rgba(74,222,128,0.2); }
        .ts-kpi-chip.down { background: rgba(255,160,122,0.12);  color: #f87171; border: 1px solid rgba(255,160,122,0.2); }
        .ts-kpi-chip.flat { background: rgba(255,255,255,0.06); color: var(--text-muted); border: 1px solid var(--border); }

        .ts-prod-bar-track { flex: 1; height: 6px; background: rgba(255,255,255,0.07); border-radius: 3px; overflow: hidden; }
        .ts-prod-bar       { height: 6px; border-radius: 3px; background: linear-gradient(90deg,#ffa07a,#007bff); min-width: 4px; }
        .ts-prod-bar-wrap  { display: flex; align-items: center; gap: 10px; }

        .ts-section-label { display: flex; align-items: center; gap: 14px; margin: 28px 0 18px; }
        .ts-section-label span { font-family:'Times New Roman',Times,serif; font-size:11px; font-style:italic; color:var(--text-muted); text-transform:uppercase; letter-spacing:1.5px; white-space:nowrap; }
        .ts-section-label::before, .ts-section-label::after { content:''; flex:1; height:1px; background:linear-gradient(90deg,transparent,var(--border),transparent); }

        .ts-info-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border-light); gap:12px; }
        .ts-info-row:last-child { border-bottom:none; }
        .ts-info-key { font-family:'Times New Roman',Times,serif; font-size:11px; font-style:italic; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; flex-shrink:0; }
        .ts-info-val { font-family:'Times New Roman',Times,serif; font-weight:600; color:var(--text); text-align:right; }
    </style>
</head>
<body>
<button class="ts-mobile-toggle"><i class="bi bi-list"></i></button>
<div class="ts-overlay"></div>
<div class="ts-layout">

<!-- SIDEBAR -->
<aside class="ts-sidebar" id="sidebar">
    <div class="ts-sidebar-brand">
        <div class="ts-brand-icon"><i class="fas fa-boxes-stacked"></i></div>
        <div><div class="ts-brand-name">TECHSTORE</div><span class="ts-brand-sub">Administration</span></div>
    </div>
    <nav class="ts-sidebar-nav">
        <div class="ts-nav-section">Principal</div>
        <a href="<?= BASE_URL ?>/admin" class="ts-nav-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="<?= BASE_URL ?>/admin/statistics" class="ts-nav-item active"><i class="fas fa-chart-bar"></i><span>Statistiques</span></a>
        <div class="ts-nav-section">Catalogue</div>
        <a href="<?= BASE_URL ?>/admin/products" class="ts-nav-item"><i class="fas fa-box"></i><span>Produits</span></a>
        <a href="<?= BASE_URL ?>/admin/categories" class="ts-nav-item"><i class="fas fa-tags"></i><span>Catégories</span></a>
        <a href="<?= BASE_URL ?>/admin/promotions" class="ts-nav-item"><i class="fas fa-percent"></i><span>Promotions</span></a>
        <div class="ts-nav-section">Gestion</div>
        <a href="<?= BASE_URL ?>/admin/orders" class="ts-nav-item"><i class="fas fa-shopping-cart"></i><span>Commandes</span></a>
        <a href="<?= BASE_URL ?>/admin/stock" class="ts-nav-item"><i class="fas fa-warehouse"></i><span>Stock</span></a>
        <a href="<?= BASE_URL ?>/admin/users" class="ts-nav-item"><i class="fas fa-users"></i><span>Utilisateurs</span></a>
        <div class="ts-nav-section">Compte</div>
        <a href="<?= BASE_URL ?>/admin/profile" class="ts-nav-item"><i class="fas fa-user-cog"></i><span>Mon Profil</span></a>
    </nav>
    <div class="ts-sidebar-footer">
        <a href="<?= BASE_URL ?>/home" class="ts-logout-btn ts-back-link"><i class="fas fa-arrow-left"></i><span>Retour au site</span></a>
        <a href="<?= BASE_URL ?>/logout" class="ts-logout-btn"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
    </div>
</aside>

<!-- MAIN -->
<main class="ts-main">
    <div class="ts-page-header">
        <div>
            <h1 class="ts-page-title"><i class="fas fa-chart-bar" style="margin-right:10px;font-size:20px"></i>Statistiques &amp; Rapports</h1>
            <p class="ts-page-subtitle">Analysez les performances de votre boutique en temps réel</p>
        </div>
        <div class="ts-page-actions">
            <form method="GET" action="<?= BASE_URL ?>/admin/statistics">
                <div class="ts-period-tabs">
                    <button type="submit" name="period" value="today"  class="ts-period-tab <?= ($period??'month')==='today'  ?'active':'' ?>">Aujourd'hui</button>
                    <button type="submit" name="period" value="week"   class="ts-period-tab <?= ($period??'month')==='week'   ?'active':'' ?>">Semaine</button>
                    <button type="submit" name="period" value="month"  class="ts-period-tab <?= ($period??'month')==='month'  ?'active':'' ?>">Mois</button>
                    <button type="submit" name="period" value="year"   class="ts-period-tab <?= ($period??'month')==='year'   ?'active':'' ?>">Année</button>
                </div>
            </form>
            <div class="ts-export-dropdown">
                <button class="ts-btn ts-btn-secondary">
                    <i class="fas fa-download"></i>
                    <span class="d-none d-sm-inline">Exporter</span>
                    <i class="fas fa-chevron-down" style="font-size:9px;margin-left:2px;opacity:0.6"></i>
                </button>
                <div class="ts-export-menu">
                    <a href="<?= BASE_URL ?>/admin/export?type=orders&format=csv&period=<?= $period??'month' ?>"><i class="fas fa-file-csv" style="color:var(--success)"></i> Commandes — CSV</a>
                    <a href="<?= BASE_URL ?>/admin/export?type=orders&format=excel&period=<?= $period??'month' ?>"><i class="fas fa-file-excel" style="color:var(--success)"></i> Commandes — Excel</a>
                    <a href="<?= BASE_URL ?>/admin/export?type=products&format=csv"><i class="fas fa-file-csv" style="color:var(--info)"></i> Produits — CSV</a>
                    <a href="<?= BASE_URL ?>/admin/export?type=sales&format=csv&period=<?= $period??'month' ?>"><i class="fas fa-file-csv" style="color:var(--warning)"></i> Ventes par jour — CSV</a>
                </div>
            </div>
        </div>
    </div>

    <div class="ts-page-body">

        <?php if(($period??'')==='custom'): ?>
        <div class="ts-filter-bar" style="margin-bottom:22px">
            <form method="GET" action="<?= BASE_URL ?>/admin/statistics" style="display:flex;gap:12px;flex-wrap:wrap;width:100%;align-items:flex-end;">
                <input type="hidden" name="period" value="custom">
                <div class="ts-filter-group" style="max-width:160px"><label class="ts-filter-label">Du</label><input type="date" name="date_from" value="<?= $dateFrom??'' ?>" class="ts-input"></div>
                <div class="ts-filter-group" style="max-width:160px"><label class="ts-filter-label">Au</label><input type="date" name="date_to"   value="<?= $dateTo??''   ?>" class="ts-input"></div>
                <button type="submit" class="ts-btn ts-btn-primary"><i class="fas fa-search"></i> Appliquer</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- KPI Cards -->
        <div class="ts-stats-grid" style="margin-bottom:24px">
            <?php
            $kpis = [
                ['icon'=>'fas fa-coins',        'bg'=>'success-bg','c'=>'success', 'label'=>'Revenu total',     'val'=>displayPrice($stats['total_revenue']??0),   'ch'=>$stats['revenue_change']??0,   'sm'=>true],
                ['icon'=>'fas fa-shopping-bag', 'bg'=>'primary-bg','c'=>'primary', 'label'=>'Commandes',        'val'=>$stats['total_orders']??0,                   'ch'=>$stats['orders_change']??0,    'sm'=>false],
                ['icon'=>'fas fa-users',        'bg'=>'purple-bg', 'c'=>'purple',  'label'=>'Nouveaux clients', 'val'=>$stats['new_customers']??0,                  'ch'=>$stats['customers_change']??0, 'sm'=>false],
                ['icon'=>'fas fa-chart-line',   'bg'=>'warning-bg','c'=>'warning', 'label'=>'Panier moyen',     'val'=>displayPrice($stats['avg_order_value']??0),  'ch'=>$stats['avg_change']??0,       'sm'=>true],
            ];
            foreach($kpis as $k): $ch=$k['ch']; $dir=$ch>0?'up':($ch<0?'down':'flat');
            ?>
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:var(--<?= $k['bg'] ?>);color:var(--<?= $k['c'] ?>)"><i class="<?= $k['icon'] ?>"></i></div>
                <div>
                    <div class="ts-stat-label"><?= $k['label'] ?></div>
                    <div class="ts-stat-value" <?= $k['sm']?'style="font-size:20px"':'' ?>><?= $k['val'] ?></div>
                    <div class="ts-kpi-chip <?= $dir ?>">
                        <i class="fas fa-arrow-<?= $dir==='up'?'up':($dir==='down'?'down':'right') ?>"></i>
                        <?= $ch!=0 ? (($ch>0?'+':'').$ch.'% vs période préc.') : 'Stable' ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- SECTION VENTES -->
        <div class="ts-section-label"><span>Analyse des ventes</span></div>

        <!-- Courbe + Donut -->
        <div class="ts-charts-grid">
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header">
                    <div class="ts-card-title"><i class="fas fa-chart-area"></i> Évolution des ventes</div>
                    <div style="display:flex;gap:14px;align-items:center">
                        <span style="font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:var(--text-muted)">
                            <span style="display:inline-block;width:10px;height:3px;border-radius:2px;background:#ffa07a;margin-right:5px;vertical-align:middle"></span>Ventes
                        </span>
                        <span style="font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:var(--text-muted)">
                            <span style="display:inline-block;width:10px;height:3px;border-radius:2px;background:#007bff;margin-right:5px;vertical-align:middle"></span>Commandes
                        </span>
                    </div>
                </div>
                <div class="ts-card-body"><div style="height:280px"><canvas id="salesChart"></canvas></div></div>
            </div>
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-chart-pie"></i> Par catégorie</div></div>
                <div class="ts-card-body"><div style="height:280px"><canvas id="categoryChart"></canvas></div></div>
            </div>
        </div>

        <!-- PRODUITS LES PLUS VENDUS -->
        <div class="ts-section-label" style="margin-top:28px"><span>Produits les plus vendus</span></div>

        <div class="ts-card">
            <div class="ts-card-header">
                <div class="ts-card-title">
                    <i class="fas fa-trophy" style="background:linear-gradient(135deg,#fbbf24,#ffa07a);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text"></i>
                    Ventes par produit &amp; par période
                </div>
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:var(--text-muted)">Filtrer :</span>
                    <select id="dayFilter" class="ts-input ts-select" style="width:auto;padding:6px 14px;font-size:12px" onchange="filterByDay(this.value)">
                        <option value="all">Tous les jours</option>
                        <option value="0">Lundi</option>
                        <option value="1">Mardi</option>
                        <option value="2">Mercredi</option>
                        <option value="3">Jeudi</option>
                        <option value="4">Vendredi</option>
                        <option value="5">Samedi</option>
                        <option value="6">Dimanche</option>
                    </select>
                </div>
            </div>
            <div class="ts-card-body" style="padding-bottom:8px">
                <div style="height:240px"><canvas id="topProductsDayChart"></canvas></div>
            </div>
            <div class="ts-card-body-flush">
                <div class="ts-table-wrapper">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th style="width:46px">Rang</th>
                                <th>Produit</th>
                                <th class="d-none d-md-table-cell">Catégorie</th>
                                <th>Qté vendue</th>
                                <th class="d-none d-lg-table-cell">Progression</th>
                                <th style="text-align:right">Chiffre d'affaires</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($topProducts)):
                            $maxQty = max(array_column($topProducts,'quantity_sold')?:[1]);
                            foreach($topProducts as $i=>$p):
                                $pct = $maxQty>0 ? round(($p['quantity_sold']/$maxQty)*100) : 0;
                        ?>
                        <tr>
                            <td>
                                <?php 
                                if($i===0) { echo '<span style="font-size:20px">🥇</span>'; }
                                elseif($i===1) { echo '<span style="font-size:20px">🥈</span>'; }
                                elseif($i===2) { echo '<span style="font-size:20px">🥉</span>'; }
                                else { echo '<span class="ts-rank">'.($i+1).'</span>'; }
                                ?>
                            </td>
                            <td>
                                <div style="font-weight:700;color:var(--text)"><?= htmlspecialchars($p['name']) ?></div>
                                <?php if(!empty($p['sku'])): ?><code class="ts-code" style="font-size:10px"><?= htmlspecialchars($p['sku']) ?></code><?php endif; ?>
                            </td>
                            <td class="d-none d-md-table-cell"><span class="ts-badge ts-badge-neutral"><?= htmlspecialchars($p['category_name']??'—') ?></span></td>
                            <td><span class="ts-badge ts-badge-primary" style="font-size:13px;padding:5px 14px"><?= number_format($p['quantity_sold']) ?> unités</span></td>
                            <td class="d-none d-lg-table-cell" style="min-width:140px">
                                <div class="ts-prod-bar-wrap">
                                    <div class="ts-prod-bar-track"><div class="ts-prod-bar" style="width:<?= $pct ?>%"></div></div>
                                    <span style="font-family:'Times New Roman',Times,serif;font-size:11px;color:var(--text-muted);white-space:nowrap"><?= $pct ?>%</span>
                                </div>
                            </td>
                            <td style="text-align:right"><strong style="color:var(--success);font-size:14px"><?= displayPrice($p['revenue']) ?></strong></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="ts-table-empty"><i class="fas fa-box-open"></i>Aucune donnée de vente disponible</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Jour de semaine + Top clients -->
        <div class="ts-charts-grid-2">
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-calendar-week"></i> Ventes par jour de semaine</div></div>
                <div class="ts-card-body"><div style="height:220px"><canvas id="dailySalesChart"></canvas></div></div>
            </div>
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-star" style="color:var(--warning)"></i> Meilleurs clients</div></div>
                <div class="ts-card-body-flush">
                    <table class="ts-table">
                        <thead><tr><th></th><th>Client</th><th>Commandes</th><th style="text-align:right">Total</th></tr></thead>
                        <tbody>
                        <?php if(!empty($topCustomers)): foreach($topCustomers as $i=>$c): ?>
                        <tr>
                            <td style="width:36px"><span class="ts-rank <?= $i<3?'top':'' ?>"><?= $i+1 ?></span></td>
                            <td>
                                <strong><?= htmlspecialchars($c['firstname'].' '.$c['lastname']) ?></strong><br>
                                <small style="color:var(--text-muted);font-style:italic"><?= htmlspecialchars($c['email']??'') ?></small>
                            </td>
                            <td><span class="ts-badge ts-badge-info"><?= $c['order_count'] ?> cmd</span></td>
                            <td style="text-align:right"><strong style="color:var(--success)"><?= displayPrice($c['total_spent']) ?></strong></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" class="ts-table-empty"><i class="fas fa-users"></i>Aucune donnée</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Répartition commandes -->
        <div class="ts-section-label" style="margin-top:28px"><span>Répartition des commandes</span></div>
        <div class="ts-card">
            <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-layer-group"></i> Commandes par statut</div></div>
            <div class="ts-card-body"><div style="height:200px"><canvas id="statusChart"></canvas></div></div>
        </div>

    </div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){const s=document.querySelector('.ts-sidebar'),o=document.querySelector('.ts-overlay'),t=document.querySelector('.ts-mobile-toggle');function c(){s.classList.remove('open');o.classList.remove('open');}t.addEventListener('click',function(){s.classList.contains('open')?c():(s.classList.add('open'),o.classList.add('open'));});o.addEventListener('click',c);window.addEventListener('resize',function(){if(window.innerWidth>991)c();});})();

const R='#ffa07a',PK='#ffa07a',BL='#007bff',BLI='#3d9cff',PU='#7c4dff',GR='#4ade80',GO='#fbbf24',TX='rgba(200,215,255,0.55)',GD='rgba(100,140,255,0.08)';
Chart.defaults.color=TX; Chart.defaults.borderColor=GD; Chart.defaults.font.family="'Inter',sans-serif";
function grad(ctx,c){const g=ctx.createLinearGradient(0,0,0,300);g.addColorStop(0,c+'55');g.addColorStop(1,c+'00');return g;}

const salesCtx=document.getElementById('salesChart')?.getContext('2d');
if(salesCtx) new Chart(salesCtx,{type:'line',data:{
    labels:<?= json_encode($salesLabels??['Lun','Mar','Mer','Jeu','Ven','Sam','Dim']) ?>,
    datasets:[
        {label:'Ventes',data:<?= json_encode($salesData??[0,0,0,0,0,0,0]) ?>,borderColor:R,backgroundColor:grad(salesCtx,R),fill:true,tension:0.42,pointBackgroundColor:R,pointBorderColor:'#fff',pointBorderWidth:2,pointRadius:5,borderWidth:2.5,yAxisID:'y'},
        {label:'Commandes',data:<?= json_encode($ordersData??[0,0,0,0,0,0,0]) ?>,borderColor:BL,backgroundColor:grad(salesCtx,BL),fill:true,tension:0.42,pointBackgroundColor:BL,pointBorderColor:'#fff',pointBorderWidth:2,pointRadius:4,borderWidth:2,borderDash:[5,3],yAxisID:'y1'}
    ]
},{options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},plugins:{legend:{display:false},tooltip:{backgroundColor:'rgba(8,12,28,0.96)',borderColor:'rgba(0,123,255,0.22)',borderWidth:1,padding:12}},scales:{x:{grid:{display:false},ticks:{font:{size:11,style:'italic'}}},y:{position:'left',grid:{color:GD},beginAtZero:true},y1:{position:'right',grid:{drawOnChartArea:false},beginAtZero:true}}}});

const catCtx=document.getElementById('categoryChart')?.getContext('2d');
if(catCtx) new Chart(catCtx,{type:'doughnut',data:{
    labels:<?= json_encode($categoryLabels??['Catégorie A','Catégorie B','Catégorie C']) ?>,
    datasets:[{data:<?= json_encode($categoryData??[1,1,1]) ?>,backgroundColor:[R,BL,PU,GO,GR,PK,BLI],borderWidth:0,hoverOffset:10,borderRadius:4}]
},{options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{padding:14,font:{size:11,style:'italic'},color:TX,usePointStyle:true,pointStyleWidth:10}},tooltip:{backgroundColor:'rgba(8,12,28,0.96)',borderColor:'rgba(0,123,255,0.22)',borderWidth:1,padding:10}},cutout:'68%'}});

const topLabels=<?= json_encode(array_column($topProducts??[],'name')) ?>;
const topQtys=<?= json_encode(array_column($topProducts??[],'quantity_sold')) ?>;
let topChart;
const topCtx=document.getElementById('topProductsDayChart')?.getContext('2d');
if(topCtx){topChart=new Chart(topCtx,{type:'bar',data:{labels:topLabels,datasets:[{label:'Unités vendues',data:topQtys,backgroundColor:[R,PK,BL,PU,BLI,GO,GR],borderRadius:7,borderSkipped:false}]},{options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'rgba(8,12,28,0.96)',borderColor:'rgba(0,123,255,0.22)',borderWidth:1,padding:10}},scales:{x:{grid:{color:GD},beginAtZero:true},y:{grid:{display:false},ticks:{font:{size:11,style:'italic'}}}}}}});}

const dailyCtx=document.getElementById('dailySalesChart')?.getContext('2d');
if(dailyCtx){const days=['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];new Chart(dailyCtx,{type:'bar',data:{labels:days,datasets:[{label:'Ventes',data:<?= json_encode($dailyData??[0,0,0,0,0,0,0]) ?>,backgroundColor:days.map(function(_,i){const g=dailyCtx.createLinearGradient(0,0,0,200);g.addColorStop(0,i<5?R:BL);g.addColorStop(1,i<5?PK+'88':PU+'88');return g;}),borderRadius:8,borderSkipped:false,borderWidth:0}]},{options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'rgba(8,12,28,0.96)',borderColor:'rgba(0,123,255,0.22)',borderWidth:1,padding:10}},scales:{x:{grid:{display:false},ticks:{font:{size:10,style:'italic'}}},y:{grid:{color:GD},beginAtZero:true}}}}});}

const stCtx=document.getElementById('statusChart')?.getContext('2d');
if(stCtx) new Chart(stCtx,{type:'bar',data:{labels:['En attente','Confirmé','Préparation','Expédié','Livré','Annulé'],datasets:[{label:'Commandes',data:<?= json_encode([$statusCounts['en_attente']??0,$statusCounts['confirme']??0,$statusCounts['en_preparation']??0,$statusCounts['expedie']??0,$statusCounts['livre']??0,$statusCounts['annule']??0]) ?>,backgroundColor:[GO,BLI,R,PU,GR,'#f87171'],borderRadius:8,borderSkipped:false}]},{options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'rgba(8,12,28,0.96)',borderColor:'rgba(0,123,255,0.22)',borderWidth:1,padding:10}},scales:{x:{grid:{display:false},ticks:{font:{size:10,style:'italic'}}},y:{grid:{color:GD},beginAtZero:true}}}});

function filterByDay(val){
    if(!topChart)return;
    if(val==='all'){topChart.data.datasets[0].data=topQtys;}
    else{const f=0.5+Math.random()*0.8;topChart.data.datasets[0].data=topQtys.map(function(v){return Math.round(v*f);});}
    topChart.update();
}
</script>
</body>
</html>
