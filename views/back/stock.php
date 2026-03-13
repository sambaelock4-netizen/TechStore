<?php /** TECHSTORE - Gestion du Stock */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
    <style>
        /* Barre de stock visuelle */
        .ts-stock-bar-track { width: 80px; height: 6px; background: rgba(255,255,255,0.07); border-radius: 3px; overflow: hidden; display: inline-block; vertical-align: middle; }
        .ts-stock-bar       { height: 6px; border-radius: 3px; transition: width 0.5s ease; }
        .ts-stock-bar.ok      { background: linear-gradient(90deg,#4ade80,#22c55e); }
        .ts-stock-bar.low     { background: linear-gradient(90deg,#fbbf24,#f59e0b); }
        .ts-stock-bar.rupture { background: linear-gradient(90deg,#f87171,#e8445a); }

        /* KPI stock mini-cards */
        .ts-stock-kpi-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
        @media(max-width:767px){ .ts-stock-kpi-grid { grid-template-columns: 1fr 1fr; } }

        /* Mouvement badge direction */
        .ts-mvt-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;font-family:'Times New Roman',Times,serif;font-size:11.5px;font-style:italic;border:1px solid transparent; }
        .ts-mvt-entry   { background:rgba(74,222,128,0.10);color:#4ade80;border-color:rgba(74,222,128,0.22); }
        .ts-mvt-exit    { background:rgba(232,68,90,0.10);color:#f87171;border-color:rgba(232,68,90,0.22); }
        .ts-mvt-adjust  { background:rgba(251,191,36,0.10);color:#fbbf24;border-color:rgba(251,191,36,0.22); }
        .ts-mvt-return  { background:rgba(74,108,247,0.10);color:#6b8cff;border-color:rgba(74,108,247,0.22); }

        .ts-section-label { display:flex;align-items:center;gap:14px;margin:28px 0 18px; }
        .ts-section-label span { font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:var(--text-muted);text-transform:uppercase;letter-spacing:1.5px;white-space:nowrap; }
        .ts-section-label::before,.ts-section-label::after { content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent); }

        .ts-seuil-input { width: 70px; padding: 5px 8px; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 7px; color: var(--text); font-size: 12px; text-align: center; font-family:'Times New Roman',Times,serif; }
        .ts-seuil-input:focus { outline: none; border-color: var(--border-accent); }
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
        <a href="<?= BASE_URL ?>/admin/statistics" class="ts-nav-item"><i class="fas fa-chart-bar"></i><span>Statistiques</span></a>
        <div class="ts-nav-section">Catalogue</div>
        <a href="<?= BASE_URL ?>/admin/products" class="ts-nav-item"><i class="fas fa-box"></i><span>Produits</span></a>
        <a href="<?= BASE_URL ?>/admin/categories" class="ts-nav-item"><i class="fas fa-tags"></i><span>Catégories</span></a>
        <a href="<?= BASE_URL ?>/admin/promotions" class="ts-nav-item"><i class="fas fa-percent"></i><span>Promotions</span></a>
        <div class="ts-nav-section">Gestion</div>
        <a href="<?= BASE_URL ?>/admin/orders" class="ts-nav-item"><i class="fas fa-shopping-cart"></i><span>Commandes</span></a>
        <a href="<?= BASE_URL ?>/admin/stock" class="ts-nav-item active"><i class="fas fa-warehouse"></i><span>Stock</span></a>
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
            <h1 class="ts-page-title"><i class="fas fa-warehouse" style="margin-right:10px;font-size:20px"></i>Gestion des Stocks</h1>
            <p class="ts-page-subtitle">Surveillance et mise à jour des niveaux de stock en temps réel</p>
        </div>
        <div class="ts-page-actions">
            <a href="<?= BASE_URL ?>/admin/stock/movements" class="ts-btn ts-btn-secondary">
                <i class="fas fa-history"></i>
                <span class="d-none d-sm-inline">Historique des mouvements</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/export?type=stock&format=csv" class="ts-btn ts-btn-secondary">
                <i class="fas fa-file-csv"></i>
                <span class="d-none d-sm-inline">Exporter CSV</span>
            </a>
        </div>
    </div>

    <div class="ts-page-body">

        <!-- KPI mini-cards stock -->
        <?php
            $totalProds    = count($products ?? []);
            $okCount       = 0; $lowCount = 0; $rupCount = 0; $totalUnits = 0;
            foreach ($products ?? [] as $p) {
                $t = ($p['stock']??0)+($p['variant_stock']??0);
                $a = $p['stock_alert']??5;
                $totalUnits += $t;
                if ($t == 0) $rupCount++;
                elseif ($t <= $a) $lowCount++;
                else $okCount++;
            }
        ?>
        <div class="ts-stock-kpi-grid">
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(74,108,247,0.12);color:#6b8cff"><i class="fas fa-boxes"></i></div>
                <div><div class="ts-stat-label">Produits total</div><div class="ts-stat-value"><?= $totalProds ?></div></div>
            </div>
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(74,222,128,0.12);color:#4ade80"><i class="fas fa-check-circle"></i></div>
                <div><div class="ts-stat-label">En stock</div><div class="ts-stat-value" style="color:#4ade80"><?= $okCount ?></div></div>
            </div>
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(251,191,36,0.12);color:#fbbf24"><i class="fas fa-exclamation-circle"></i></div>
                <div><div class="ts-stat-label">Stock bas</div><div class="ts-stat-value" style="color:#fbbf24"><?= $lowCount ?></div></div>
            </div>
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(232,68,90,0.12);color:#f87171"><i class="fas fa-times-circle"></i></div>
                <div><div class="ts-stat-label">Rupture</div><div class="ts-stat-value" style="color:#f87171"><?= $rupCount ?></div></div>
            </div>
        </div>

        <!-- Alerte stock bas -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="ts-alert ts-alert-warning" style="margin-bottom:22px">
            <div class="ts-alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div class="ts-alert-title">⚠ Alerte stock faible</div>
                <div class="ts-alert-body">
                    <strong><?= count($lowStockProducts) ?></strong> produit(s) nécessitent un réapprovisionnement urgent.
                    <?php $names = array_slice(array_column($lowStockProducts,'name'),0,3); echo implode(', ', array_map('htmlspecialchars', $names)); ?>
                    <?php if (count($lowStockProducts)>3): ?>&hellip; et <?= count($lowStockProducts)-3 ?> autre(s).<?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- SECTION : Mise à jour du stock -->
        <div class="ts-section-label"><span>Mise à jour du stock</span></div>

        <div class="ts-card">
            <div class="ts-card-header">
                <div class="ts-card-title"><i class="fas fa-plus-circle"></i> Enregistrer un mouvement</div>
                <span style="font-family:'Times New Roman',Times,serif;font-size:12px;font-style:italic;color:var(--text-muted)">Entrée · Sortie · Ajustement · Retour</span>
            </div>
            <div class="ts-card-body">
                <form method="POST" action="<?= BASE_URL ?>/admin/stock/update">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="ts-form-group" style="margin-bottom:0">
                                <label class="ts-label">Produit</label>
                                <div class="ts-input-icon">
                                    <i class="fas fa-box"></i>
                                    <select name="product_id" class="ts-input ts-select" required>
                                        <option value="">Sélectionner un produit</option>
                                        <?php foreach ($products??[] as $p):
                                            $t=($p['stock']??0)+($p['variant_stock']??0);
                                        ?>
                                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> (<?= $t ?> en stock)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="ts-form-group" style="margin-bottom:0">
                                <label class="ts-label">Type de mouvement</label>
                                <div class="ts-input-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                    <select name="type" class="ts-input ts-select">
                                        <option value="entry">Entrée (+)</option>
                                        <option value="exit">Sortie (−)</option>
                                        <option value="adjustment">Ajustement</option>
                                        <option value="return">Retour client</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <div class="ts-form-group" style="margin-bottom:0">
                                <label class="ts-label">Quantité</label>
                                <div class="ts-input-icon">
                                    <i class="fas fa-hashtag"></i>
                                    <input type="number" name="quantity" class="ts-input" required min="1" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-8">
                            <div class="ts-form-group" style="margin-bottom:0">
                                <label class="ts-label">Raison / Note</label>
                                <div class="ts-input-icon">
                                    <i class="fas fa-comment-alt"></i>
                                    <input type="text" name="reason" class="ts-input" placeholder="Ex : Réapprovisionnement fournisseur">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12">
                            <div class="ts-form-group" style="margin-bottom:0">
                                <label class="ts-label">&nbsp;</label>
                                <button type="submit" class="ts-btn ts-btn-primary" style="width:100%">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECTION : État des stocks -->
        <div class="ts-section-label"><span>État des stocks en temps réel</span></div>

        <div class="ts-card">
            <div class="ts-card-header">
                <div class="ts-card-title"><i class="fas fa-boxes"></i> Inventaire complet</div>
                <div style="display:flex;align-items:center;gap:10px">
                    <?php if(!empty($products)): ?><span class="ts-badge ts-badge-info"><?= $totalProds ?> produit(s)</span><?php endif; ?>
                    <!-- Filtre rapide statut -->
                    <select onchange="filterStock(this.value)" class="ts-input ts-select" style="width:auto;padding:6px 14px;font-size:12px">
                        <option value="all">Tous les statuts</option>
                        <option value="ok">En stock</option>
                        <option value="low">Stock bas</option>
                        <option value="rupture">Rupture</option>
                    </select>
                </div>
            </div>
            <div class="ts-card-body-flush">
                <?php if(!empty($products)): ?>
                <div class="ts-table-wrapper">
                    <table class="ts-table" id="stockTable">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="d-none d-lg-table-cell">Catégorie</th>
                                <th>Stock actuel</th>
                                <th class="d-none d-md-table-cell">Niveau</th>
                                <th>Seuil d'alerte</th>
                                <th>Statut</th>
                                <th class="d-none d-lg-table-cell">Dernière MAJ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($products as $p):
                            $vs = $p['variant_stock']??0;
                            $ms = $p['stock']??0;
                            $ts = $ms+$vs;
                            $al = $p['stock_alert']??5;
                            $maxStock = max($ts*2, $al*4, 20);
                            $pct = min(100, round(($ts/$maxStock)*100));
                            if($ts==0)       { $statClass='rupture'; $statBadge='ts-badge-danger'; $statLabel='Rupture'; $barClass='rupture'; }
                            elseif($ts<=$al) { $statClass='low';     $statBadge='ts-badge-warning'; $statLabel='Stock bas'; $barClass='low'; }
                            else             { $statClass='ok';      $statBadge='ts-badge-success'; $statLabel='OK'; $barClass='ok'; }
                        ?>
                        <tr data-status="<?= $statClass ?>">
                            <td>
                                <div style="font-weight:700;color:var(--text)"><?= htmlspecialchars($p['name']) ?></div>
                                <?php if($vs>0): ?><small style="color:var(--text-muted);font-style:italic">dont <?= $vs ?> en variantes</small><?php endif; ?>
                            </td>
                            <td class="d-none d-lg-table-cell"><span class="ts-badge ts-badge-neutral"><?= htmlspecialchars($p['category_name']??'—') ?></span></td>
                            <td>
                                <span style="font-size:20px;font-weight:700;color:<?= $ts==0?'var(--danger)':($ts<=$al?'var(--warning)':'var(--success)') ?>">
                                    <?= $ts ?>
                                </span>
                                <span style="color:var(--text-muted);font-size:11px;margin-left:4px">unités</span>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="ts-stock-bar-track"><div class="ts-stock-bar <?= $barClass ?>" style="width:<?= $pct ?>%"></div></div>
                                    <span style="font-size:11px;color:var(--text-muted)"><?= $pct ?>%</span>
                                </div>
                            </td>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>/admin/stock/setAlert" style="display:inline">
                                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                    <input type="number" name="alert_level" value="<?= $al ?>" class="ts-seuil-input" min="0" title="Seuil d'alerte">
                                    <button type="submit" style="background:none;border:none;color:var(--text-muted);cursor:pointer;padding:0 4px;font-size:11px" title="Sauvegarder le seuil">
                                        <i class="fas fa-check" style="color:var(--success)"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <span class="ts-badge <?= $statBadge ?>">
                                    <i class="fas fa-<?= $ts==0?'times-circle':($ts<=$al?'exclamation-circle':'check-circle') ?>"></i>
                                    <?= $statLabel ?>
                                </span>
                            </td>
                            <td class="d-none d-lg-table-cell" style="color:var(--text-muted);font-style:italic;font-size:12px">
                                <?= isset($p['updated_at']) ? date('d/m/Y H:i', strtotime($p['updated_at'])) : '—' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="ts-table-empty"><i class="fas fa-warehouse"></i>Aucun produit en stock</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- SECTION : Historique des mouvements récents -->
        <?php if(!empty($recentMovements)): ?>
        <div class="ts-section-label"><span>Mouvements récents</span></div>
        <div class="ts-card">
            <div class="ts-card-header">
                <div class="ts-card-title"><i class="fas fa-history"></i> Derniers mouvements de stock</div>
                <a href="<?= BASE_URL ?>/admin/stock/movements" class="ts-btn ts-btn-secondary ts-btn-sm">
                    Voir tout <i class="fas fa-arrow-right" style="margin-left:4px"></i>
                </a>
            </div>
            <div class="ts-card-body-flush">
                <div class="ts-table-wrapper">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th class="d-none d-md-table-cell">Note</th>
                                <th class="d-none d-lg-table-cell">Opérateur</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach(array_slice($recentMovements,0,10) as $m):
                            $typeMap = ['entry'=>['ts-mvt-entry','↑ Entrée'],'exit'=>['ts-mvt-exit','↓ Sortie'],'adjustment'=>['ts-mvt-adjust','⇄ Ajustement'],'return'=>['ts-mvt-return','↺ Retour']];
                            $tm = $typeMap[$m['type']]??['ts-badge-neutral',$m['type']];
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($m['product_name']??'—') ?></strong></td>
                            <td><span class="ts-mvt-badge <?= $tm[0] ?>"><?= $tm[1] ?></span></td>
                            <td>
                                <span style="font-weight:700;font-size:15px;color:<?= $m['type']==='exit'?'var(--danger)':'var(--success)' ?>">
                                    <?= $m['type']==='exit'?'-':'+' ?><?= $m['quantity'] ?>
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell" style="color:var(--text-muted);font-style:italic"><?= htmlspecialchars($m['reason']??'—') ?></td>
                            <td class="d-none d-lg-table-cell" style="color:var(--text-muted)"><?= htmlspecialchars($m['admin_name']??'Admin') ?></td>
                            <td style="color:var(--text-muted);font-size:12px;font-style:italic"><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){const s=document.querySelector('.ts-sidebar'),o=document.querySelector('.ts-overlay'),t=document.querySelector('.ts-mobile-toggle');function c(){s.classList.remove('open');o.classList.remove('open');}t.addEventListener('click',function(){s.classList.contains('open')?c():(s.classList.add('open'),o.classList.add('open'));});o.addEventListener('click',c);window.addEventListener('resize',function(){if(window.innerWidth>991)c();});})();

function filterStock(val) {
    document.querySelectorAll('#stockTable tbody tr').forEach(function(tr) {
        if (val === 'all' || tr.dataset.status === val) {
            tr.style.display = '';
        } else {
            tr.style.display = 'none';
        }
    });
}
</script>
</body>
</html>
