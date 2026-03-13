<?php /** TECHSTORE - Gestion des Commandes */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
    <style>
        /* Status tabs */
        .ts-status-tabs { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:20px; }
        .ts-status-tab {
            padding: 7px 16px; border-radius: 20px;
            font-family:'Times New Roman',Times,serif; font-size:12.5px; font-style:italic;
            border: 1px solid var(--border); background: rgba(255,255,255,0.03);
            color: var(--text-muted); cursor: pointer; text-decoration: none;
            transition: var(--transition); white-space: nowrap;
        }
        .ts-status-tab:hover { border-color: var(--border-blue); color: var(--blue-light); }
        .ts-status-tab.active { font-style: normal; font-weight:700; }
        .ts-status-tab.all.active   { background:rgba(74,108,247,0.14); border-color:rgba(74,108,247,0.35); color:var(--blue-light); }
        .ts-status-tab.pending.active   { background:rgba(251,191,36,0.12); border-color:rgba(251,191,36,0.3); color:#fbbf24; }
        .ts-status-tab.confirm.active   { background:rgba(103,232,249,0.10); border-color:rgba(103,232,249,0.25); color:#67e8f9; }
        .ts-status-tab.prep.active      { background:rgba(232,68,90,0.12); border-color:rgba(232,68,90,0.3); color:var(--red-light); }
        .ts-status-tab.shipped.active   { background:rgba(124,77,255,0.12); border-color:rgba(124,77,255,0.3); color:#c084fc; }
        .ts-status-tab.delivered.active { background:rgba(74,222,128,0.12); border-color:rgba(74,222,128,0.25); color:#4ade80; }
        .ts-status-tab.cancelled.active { background:rgba(248,113,113,0.10); border-color:rgba(248,113,113,0.25); color:#f87171; }

        /* Timeline statut inline */
        .ts-order-timeline { display:flex; align-items:center; gap:0; }
        .ts-tl-step { display:flex; flex-direction:column; align-items:center; gap:3px; }
        .ts-tl-dot  { width:10px; height:10px; border-radius:50%; border:2px solid; flex-shrink:0; }
        .ts-tl-line { width:24px; height:2px; background: var(--border); }
        .ts-tl-label { font-size:8px; font-style:italic; color:var(--text-muted); white-space:nowrap; }
        .ts-tl-dot.done  { border-color:var(--success); background:var(--success); }
        .ts-tl-dot.curr  { border-color:var(--red-light); background:var(--red-light); box-shadow:0 0 8px rgba(232,68,90,0.6); }
        .ts-tl-dot.idle  { border-color:var(--border); background:transparent; }
        .ts-tl-line.done { background: var(--success); }

        .ts-section-label { display:flex;align-items:center;gap:14px;margin:20px 0 16px; }
        .ts-section-label span { font-family:'Times New Roman',Times,serif;font-size:11px;font-style:italic;color:var(--text-muted);text-transform:uppercase;letter-spacing:1.5px;white-space:nowrap; }
        .ts-section-label::before,.ts-section-label::after { content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent); }
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
        <a href="<?= BASE_URL ?>/admin/orders" class="ts-nav-item active"><i class="fas fa-shopping-cart"></i><span>Commandes</span></a>
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
            <h1 class="ts-page-title"><i class="fas fa-shopping-cart" style="margin-right:10px;font-size:20px"></i>Gestion des Commandes</h1>
            <p class="ts-page-subtitle">Suivez, traitez et gérez toutes les commandes</p>
        </div>
        <div class="ts-page-actions">
            <a href="<?= BASE_URL ?>/admin/export?type=orders&format=csv" class="ts-btn ts-btn-secondary">
                <i class="fas fa-file-csv"></i><span class="d-none d-sm-inline">Exporter CSV</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/export?type=orders&format=excel" class="ts-btn ts-btn-secondary">
                <i class="fas fa-file-excel"></i><span class="d-none d-sm-inline">Excel</span>
            </a>
        </div>
    </div>

    <div class="ts-page-body">

        <!-- KPI commandes -->
        <?php
            $statusMap = ['en_attente'=>['warning','En attente'],'confirme'=>['info','Confirmé'],'en_preparation'=>['primary','Préparation'],'expedie'=>['purple','Expédié'],'livre'=>['success','Livré'],'annule'=>['danger','Annulé']];
            $paymentMap = ['pending'=>['warning','En attente'],'paid'=>['success','Payé'],'failed'=>['danger','Échoué'],'refunded'=>['info','Remboursé']];
        ?>
        <div class="ts-stats-grid" style="margin-bottom:20px">
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(74,108,247,0.12);color:#6b8cff"><i class="fas fa-shopping-bag"></i></div>
                <div><div class="ts-stat-label">Total commandes</div><div class="ts-stat-value"><?= count($orders??[]) ?></div></div>
            </div>
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(251,191,36,0.12);color:#fbbf24"><i class="fas fa-clock"></i></div>
                <div><div class="ts-stat-label">En attente</div><div class="ts-stat-value" style="color:#fbbf24"><?= count(array_filter($orders??[],fn($o)=>$o['status']==='en_attente')) ?></div></div>
            </div>
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(124,77,255,0.12);color:#c084fc"><i class="fas fa-shipping-fast"></i></div>
                <div><div class="ts-stat-label">Expédiées</div><div class="ts-stat-value" style="color:#c084fc"><?= count(array_filter($orders??[],fn($o)=>$o['status']==='expedie')) ?></div></div>
            </div>
            <div class="ts-stat-card">
                <div class="ts-stat-icon" style="background:rgba(74,222,128,0.12);color:#4ade80"><i class="fas fa-check-double"></i></div>
                <div><div class="ts-stat-label">Livrées</div><div class="ts-stat-value" style="color:#4ade80"><?= count(array_filter($orders??[],fn($o)=>$o['status']==='livre')) ?></div></div>
            </div>
        </div>

        <!-- Tabs statut rapide -->
        <div class="ts-status-tabs">
            <?php
            $curSt = $selectedStatus??'';
            $tabDefs = [
                ['','all','Toutes','fas fa-list'],
                ['en_attente','pending','En attente','fas fa-clock'],
                ['confirme','confirm','Confirmées','fas fa-check'],
                ['en_preparation','prep','En préparation','fas fa-cog'],
                ['expedie','shipped','Expédiées','fas fa-truck'],
                ['livre','delivered','Livrées','fas fa-check-double'],
                ['annule','cancelled','Annulées','fas fa-ban'],
            ];
            foreach($tabDefs as [$val,$cls,$lbl,$ico]):
                $active = ($curSt===$val)?'active':'';
            ?>
            <a href="<?= BASE_URL ?>/admin/orders<?= $val?'?status='.$val:'' ?>" class="ts-status-tab <?= $cls ?> <?= $active ?>">
                <i class="<?= $ico ?>" style="margin-right:5px"></i><?= $lbl ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Filtres avancés -->
        <div class="ts-filter-bar">
            <form method="GET" action="<?= BASE_URL ?>/admin/orders" style="display:flex;gap:12px;flex-wrap:wrap;width:100%;align-items:flex-end;">
                <?php if($curSt): ?><input type="hidden" name="status" value="<?= $curSt ?>"> <?php endif; ?>
                <div class="ts-filter-group" style="max-width:260px">
                    <label class="ts-filter-label">Recherche</label>
                    <div class="ts-input-icon">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="ts-input" placeholder="Client, N° commande, email..." value="<?= htmlspecialchars($search??'') ?>">
                    </div>
                </div>
                <div class="ts-filter-group" style="max-width:155px">
                    <label class="ts-filter-label">Du</label>
                    <input type="date" name="date_from" value="<?= $dateFrom??'' ?>" class="ts-input">
                </div>
                <div class="ts-filter-group" style="max-width:155px">
                    <label class="ts-filter-label">Au</label>
                    <input type="date" name="date_to" value="<?= $dateTo??'' ?>" class="ts-input">
                </div>
                <div style="display:flex;gap:8px;align-items:flex-end">
                    <button type="submit" class="ts-btn ts-btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
                    <a href="<?= BASE_URL ?>/admin/orders" class="ts-btn ts-btn-secondary"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>

        <!-- Table commandes -->
        <div class="ts-card">
            <div class="ts-card-header">
                <div class="ts-card-title"><i class="fas fa-list-ul"></i> Liste des commandes</div>
                <?php if(!empty($orders)): ?><span class="ts-badge ts-badge-info"><?= count($orders) ?> résultat(s)</span><?php endif; ?>
            </div>
            <div class="ts-card-body-flush">
                <?php if(!empty($orders)): ?>
                <div class="ts-table-wrapper">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th class="d-none d-md-table-cell">Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th class="d-none d-lg-table-cell">Progression</th>
                                <th class="d-none d-lg-table-cell">Paiement</th>
                                <th class="d-none d-sm-table-cell">Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($orders as $order):
                            $s=$statusMap[$order['status']]??['neutral',$order['status']];
                            $p=$paymentMap[$order['payment_status']]??['neutral',$order['payment_status']];
                            // Calcul progression timeline
                            $steps=['en_attente','confirme','en_preparation','expedie','livre'];
                            $curIdx=array_search($order['status'],$steps);
                        ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" style="font-weight:700;color:var(--blue-light);text-decoration:none">
                                    #<?= $order['order_number']??$order['id'] ?>
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div style="font-weight:600"><?= htmlspecialchars($order['firstname'].' '.$order['lastname']) ?></div>
                                <small style="color:var(--text-muted);font-style:italic"><?= htmlspecialchars($order['email']) ?></small>
                            </td>
                            <td><strong style="color:var(--success);font-size:14px"><?= displayPrice($order['total_amount']) ?></strong></td>
                            <td><span class="ts-badge ts-badge-<?= $s[0] ?>"><?= $s[1] ?></span></td>
                            <td class="d-none d-lg-table-cell">
                                <?php if($order['status']!=='annule'): ?>
                                <div class="ts-order-timeline">
                                <?php foreach($steps as $si=>$step):
                                    $stepState = $curIdx===false?'idle':($si<$curIdx?'done':($si===$curIdx?'curr':'idle'));
                                ?>
                                    <div class="ts-tl-step">
                                        <div class="ts-tl-dot <?= $stepState ?>"></div>
                                    </div>
                                    <?php if($si<count($steps)-1): ?><div class="ts-tl-line <?= $curIdx!==false&&$si<$curIdx?'done':'' ?>"></div><?php endif; ?>
                                <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                <span style="color:var(--danger);font-size:11px;font-style:italic">Annulée</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-lg-table-cell"><span class="ts-badge ts-badge-<?= $p[0] ?>"><?= $p[1] ?></span></td>
                            <td class="d-none d-sm-table-cell" style="color:var(--text-muted);font-size:12px;font-style:italic"><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <div style="display:flex;gap:6px">
                                    <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" class="ts-action-btn ts-action-btn-view" title="Voir le détail"><i class="bi bi-eye"></i></a>
                                    <a href="<?= BASE_URL ?>/admin/orders/generateInvoice/<?= $order['id'] ?>" class="ts-action-btn ts-action-btn-edit" title="Facture PDF" target="_blank"><i class="bi bi-file-earmark-text"></i></a>
                                    <?php if(in_array($order['status'],['en_attente','confirme'])): ?>
                                    <a href="<?= BASE_URL ?>/admin/orders/cancel/<?= $order['id'] ?>" class="ts-action-btn ts-action-btn-delete" title="Annuler" onclick="return confirm('Annuler cette commande ?')"><i class="bi bi-x-circle"></i></a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <?php
                            $total = array_sum(array_column($orders,'total_amount'));
                        ?>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="color:var(--text-muted);font-style:italic">Total affiché</td>
                                <td colspan="6"><strong style="color:var(--success);font-size:15px"><?= displayPrice($total) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div class="ts-table-empty"><i class="bi bi-bag"></i>Aucune commande trouvée</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>(function(){const s=document.querySelector('.ts-sidebar'),o=document.querySelector('.ts-overlay'),t=document.querySelector('.ts-mobile-toggle');function c(){s.classList.remove('open');o.classList.remove('open');}t.addEventListener('click',function(){s.classList.contains('open')?c():(s.classList.add('open'),o.classList.add('open'));});o.addEventListener('click',c);window.addEventListener('resize',function(){if(window.innerWidth>991)c();});})();</script>
</body>
</html>
