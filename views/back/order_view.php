<?php /** TECHSTORE - Détail d'une commande */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
    <style>
        .ts-order-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        .ts-order-grid .full { grid-column:span 2; }
        @media(max-width:767px){ .ts-order-grid{grid-template-columns:1fr;} .ts-order-grid .full{grid-column:span 1;} }
        .ts-info-row { display:flex; justify-content:space-between; padding:11px 0; border-bottom:1px solid var(--border-light); gap:12px; }
        .ts-info-row:last-child { border-bottom:none; }
        .ts-info-key { font-family:'Times New Roman',Times,serif; font-size:11px; font-style:italic; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; flex-shrink:0; }
        .ts-info-val { font-family:'Times New Roman',Times,serif; font-weight:600; color:var(--text); text-align:right; }

        /* Timeline statut vertical */
        .ts-status-timeline { display:flex; flex-direction:column; gap:0; }
        .ts-stl-step { display:flex; align-items:flex-start; gap:14px; }
        .ts-stl-col  { display:flex; flex-direction:column; align-items:center; }
        .ts-stl-dot  { width:14px; height:14px; border-radius:50%; border:2px solid; flex-shrink:0; margin-top:2px; }
        .ts-stl-line { width:2px; height:28px; background:var(--border); margin:3px 0; }
        .ts-stl-dot.done   { border-color:var(--success); background:var(--success); }
        .ts-stl-dot.curr   { border-color:var(--red-light); background:var(--red-light); box-shadow:0 0 10px rgba(232,68,90,0.5); }
        .ts-stl-dot.idle   { border-color:var(--border); background:transparent; }
        .ts-stl-line.done  { background:var(--success); opacity:0.4; }
        .ts-stl-info { padding-bottom: 20px; }
        .ts-stl-label { font-family:'Times New Roman',Times,serif; font-size:13px; font-weight:600; }
        .ts-stl-label.done { color:var(--success); }
        .ts-stl-label.curr { color:var(--red-light); font-style:italic; }
        .ts-stl-label.idle { color:var(--text-muted); }
        .ts-stl-date { font-family:'Times New Roman',Times,serif; font-size:11px; font-style:italic; color:var(--text-muted); margin-top:2px; }
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
        <div class="ts-page-header-left">
            <a href="<?= BASE_URL ?>/admin/orders" class="ts-back-btn"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="ts-page-title">Commande #<?= $order['order_number']??$order['id'] ?></h1>
                <p class="ts-page-subtitle">Passée le <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></p>
            </div>
        </div>
        <div class="ts-page-actions">
            <a href="<?= BASE_URL ?>/admin/orders/generateInvoice/<?= $order['id'] ?>" class="ts-btn ts-btn-primary" target="_blank">
                <i class="fas fa-file-invoice"></i><span class="d-none d-sm-inline">Facture PDF</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/orders/generateDeliveryNote/<?= $order['id'] ?>" class="ts-btn ts-btn-secondary" target="_blank">
                <i class="fas fa-truck"></i><span class="d-none d-sm-inline">Bon de livraison</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/orders" class="ts-btn ts-btn-secondary">
                <i class="fas fa-arrow-left"></i><span class="d-none d-sm-inline">Retour</span>
            </a>
        </div>
    </div>

    <div class="ts-page-body">
        <div class="ts-order-grid">

            <!-- Statut + Timeline -->
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header">
                    <div class="ts-card-title"><i class="fas fa-tasks"></i> Suivi &amp; Mise à jour</div>
                    <?php
                    $sm=['en_attente'=>['warning','En attente'],'confirme'=>['info','Confirmé'],'en_preparation'=>['primary','Préparation'],'expedie'=>['purple','Expédié'],'livre'=>['success','Livré'],'annule'=>['danger','Annulé']];
                    $cs=$sm[$order['status']]??['neutral',$order['status']];
                    ?><span class="ts-badge ts-badge-<?= $cs[0] ?>"><?= $cs[1] ?></span>
                </div>
                <div class="ts-card-body">
                    <!-- Timeline verticale -->
                    <?php if($order['status']!=='annule'):
                        $steps=[['en_attente','En attente','fas fa-clock'],['confirme','Confirmé','fas fa-check'],['en_preparation','En préparation','fas fa-cog'],['expedie','Expédié','fas fa-truck'],['livre','Livré','fas fa-check-double']];
                        $curIdx=array_search($order['status'],array_column($steps,0));
                    ?>
                    <div class="ts-status-timeline" style="margin-bottom:22px">
                        <?php foreach($steps as $si=>[$sv,$sl,$si_ico]):
                            $state=$curIdx===false?'idle':($si<$curIdx?'done':($si===$curIdx?'curr':'idle'));
                            $isLast=$si===count($steps)-1;
                        ?>
                        <div class="ts-stl-step">
                            <div class="ts-stl-col">
                                <div class="ts-stl-dot <?= $state ?>"></div>
                                <?php if(!$isLast): ?><div class="ts-stl-line <?= $state==='done'?'done':'' ?>"></div><?php endif; ?>
                            </div>
                            <div class="ts-stl-info">
                                <div class="ts-stl-label <?= $state ?>">
                                    <i class="<?= $si_ico ?>" style="margin-right:6px;font-size:11px"></i><?= $sl ?>
                                </div>
                                <?php if($state==='curr'): ?><div class="ts-stl-date">Statut actuel</div><?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <!-- Formulaire mise à jour -->
                    <form method="POST" action="<?= BASE_URL ?>/admin/orders/update/<?= $order['id'] ?>">
                        <div class="ts-form-group">
                            <label class="ts-label">Changer le statut</label>
                            <div class="ts-input-icon">
                                <i class="fas fa-tag"></i>
                                <select name="status" class="ts-input ts-select">
                                    <?php foreach(['en_attente'=>'En attente','confirme'=>'Confirmé','en_preparation'=>'En préparation','expedie'=>'Expédié','livre'=>'Livré','annule'=>'Annulé'] as $v=>$l): ?>
                                    <option value="<?= $v ?>" <?= $order['status']===$v?'selected':'' ?>><?= $l ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="ts-form-group">
                            <label class="ts-label">Notes internes</label>
                            <textarea name="notes" class="ts-input ts-textarea" rows="3" placeholder="Note visible uniquement par les admins..."><?= htmlspecialchars($order['notes']??'') ?></textarea>
                        </div>
                        <button type="submit" class="ts-btn ts-btn-primary" style="width:100%">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </form>
                    <?php if(in_array($order['status'],['en_attente','confirme'])): ?>
                    <div style="margin-top:12px">
                        <a href="<?= BASE_URL ?>/admin/orders/cancel/<?= $order['id'] ?>"
                           class="ts-btn ts-btn-danger" style="width:100%"
                           onclick="return confirm('Annuler définitivement cette commande ?')">
                            <i class="fas fa-ban"></i> Annuler la commande
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Infos client -->
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-user"></i> Informations client</div></div>
                <div class="ts-card-body">
                    <div class="ts-info-row"><span class="ts-info-key">Nom</span><span class="ts-info-val"><?= htmlspecialchars($order['firstname'].' '.$order['lastname']) ?></span></div>
                    <div class="ts-info-row"><span class="ts-info-key">Email</span><span class="ts-info-val"><?= htmlspecialchars($order['email']) ?></span></div>
                    <div class="ts-info-row"><span class="ts-info-key">Téléphone</span><span class="ts-info-val"><?= htmlspecialchars($order['phone']??'—') ?></span></div>
                    <div class="ts-info-row"><span class="ts-info-key">Date commande</span><span class="ts-info-val"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span></div>
                    <?php if(!empty($order['user_id'])): ?>
                    <div style="margin-top:14px">
                        <a href="<?= BASE_URL ?>/admin/users/view/<?= $order['user_id'] ?>" class="ts-btn ts-btn-secondary ts-btn-sm" style="width:100%">
                            <i class="fas fa-external-link-alt"></i> Voir le profil client
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Livraison -->
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-map-marker-alt"></i> Adresse de livraison</div></div>
                <div class="ts-card-body">
                    <address style="font-style:normal;line-height:2;color:var(--text)">
                        <strong><?= htmlspecialchars($order['shipping_name']??($order['firstname'].' '.$order['lastname'])) ?></strong><br>
                        <?= htmlspecialchars($order['shipping_address']??$order['address']??'—') ?><br>
                        <?= htmlspecialchars(($order['shipping_postal_code']??$order['postal_code']??'').' '.($order['shipping_city']??$order['city']??'')) ?>
                    </address>
                </div>
            </div>

            <!-- Paiement -->
            <div class="ts-card" style="margin-bottom:0">
                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-credit-card"></i> Paiement</div></div>
                <div class="ts-card-body">
                    <?php
                    $pm=['pending'=>['warning','En attente'],'paid'=>['success','Payé'],'failed'=>['danger','Échoué'],'refunded'=>['info','Remboursé']];
                    $pp=$pm[$order['payment_status']]??['neutral',$order['payment_status']];
                    ?>
                    <div class="ts-info-row"><span class="ts-info-key">Statut</span><span class="ts-info-val"><span class="ts-badge ts-badge-<?= $pp[0] ?>"><?= $pp[1] ?></span></span></div>
                    <div class="ts-info-row"><span class="ts-info-key">Méthode</span><span class="ts-info-val"><?= htmlspecialchars($order['payment_method']??'—') ?></span></div>
                    <?php if(!empty($order['discount_amount'])&&$order['discount_amount']>0): ?>
                    <div class="ts-info-row"><span class="ts-info-key">Remise</span><span class="ts-info-val" style="color:var(--success)">−<?= displayPrice($order['discount_amount']) ?></span></div>
                    <?php endif; ?>
                    <div class="ts-info-row"><span class="ts-info-key">Livraison</span><span class="ts-info-val"><?= displayPrice($order['shipping_cost']??0) ?></span></div>
                    <div class="ts-info-row" style="border-top:1px solid var(--border-accent);padding-top:14px;margin-top:4px">
                        <span class="ts-info-key" style="font-size:13px">Total TTC</span>
                        <span class="ts-info-val" style="color:var(--success);font-size:20px"><?= displayPrice($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Articles commandés (full width) -->
            <div class="ts-card full" style="margin-bottom:0">
                <div class="ts-card-header">
                    <div class="ts-card-title"><i class="fas fa-boxes"></i> Articles commandés</div>
                    <?php if(!empty($orderItems)): ?><span class="ts-badge ts-badge-neutral"><?= count($orderItems) ?> article(s)</span><?php endif; ?>
                </div>
                <div class="ts-card-body-flush">
                    <div class="ts-table-wrapper">
                        <table class="ts-table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="d-none d-md-table-cell">Variante</th>
                                    <th>Prix unit.</th>
                                    <th>Qté</th>
                                    <th style="text-align:right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($orderItems)): foreach($orderItems as $item): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:700"><?= htmlspecialchars($item['product_name']) ?></div>
                                    <?php if(!empty($item['sku'])): ?><code class="ts-code" style="font-size:10px"><?= htmlspecialchars($item['sku']) ?></code><?php endif; ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php if(!empty($item['variant_name'])): ?>
                                    <span class="ts-badge ts-badge-neutral"><?= htmlspecialchars($item['variant_name']) ?></span>
                                    <?php else: ?><span style="color:var(--text-muted)">—</span><?php endif; ?>
                                </td>
                                <td style="color:var(--text-muted)"><?= displayPrice($item['price']) ?></td>
                                <td><span class="ts-badge ts-badge-info"><?= $item['quantity'] ?>×</span></td>
                                <td style="text-align:right;font-weight:700"><?= displayPrice($item['total']) ?></td>
                            </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                            <tfoot>
                                <tr><td colspan="4" style="text-align:right;color:var(--text-muted);font-style:italic">Sous-total</td><td style="text-align:right;font-weight:600"><?= displayPrice($order['subtotal']??$order['total_amount']) ?></td></tr>
                                <tr><td colspan="4" style="text-align:right;color:var(--text-muted);font-style:italic">Frais de livraison</td><td style="text-align:right;font-weight:600"><?= displayPrice($order['shipping_cost']??0) ?></td></tr>
                                <?php if(($order['discount_amount']??0)>0): ?>
                                <tr><td colspan="4" style="text-align:right;color:var(--text-muted);font-style:italic">Remise</td><td style="text-align:right;color:var(--success);font-weight:700">−<?= displayPrice($order['discount_amount']) ?></td></tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="4" style="text-align:right;font-weight:700;font-size:15px;color:var(--text)">Total TTC</td>
                                    <td style="text-align:right;font-weight:800;font-size:18px;color:var(--success)"><?= displayPrice($order['total_amount']) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>(function(){const s=document.querySelector('.ts-sidebar'),o=document.querySelector('.ts-overlay'),t=document.querySelector('.ts-mobile-toggle');function c(){s.classList.remove('open');o.classList.remove('open');}t.addEventListener('click',function(){s.classList.contains('open')?c():(s.classList.add('open'),o.classList.add('open'));});o.addEventListener('click',c);window.addEventListener('resize',function(){if(window.innerWidth>991)c();});})();</script>
</body>
</html>
