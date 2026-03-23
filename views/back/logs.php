<?php
/**
 * TECHSTORE - Logs d'activité
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
</head>
<body>
    <button class="ts-mobile-toggle"><i class="bi bi-list"></i></button>
    <div class="ts-overlay"></div>
    <div class="ts-layout">
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

        <main class="ts-main">
            <div class="ts-page-header">
                <div>
                    <h1 class="ts-page-title">Logs d'activité</h1>
                    <p class="ts-page-subtitle">Historique des actions effectuées dans le panneau admin</p>
                </div>
            </div>

            <div class="ts-page-body">
                <div class="ts-card">
                    <div class="ts-card-header">
                        <div class="ts-card-title"><i class="fas fa-history"></i> Journal d'activité</div>
                    </div>
                    <div class="ts-card-body-flush">
                        <?php if (!empty($logs)): ?>
                        <div class="ts-table-wrapper">
                            <table class="ts-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Utilisateur</th>
                                        <th>Action</th>
                                        <th class="d-none d-md-table-cell">Détails</th>
                                        <th class="d-none d-lg-table-cell">IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td style="color:var(--text-muted);white-space:nowrap;font-size:12.5px">
                                        <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                    </td>
                                    <td>
                                        <span style="font-weight:600"><?= htmlspecialchars($log['user_name'] ?? '—') ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $actionColors = [
                                            'create' => 'success', 'add' => 'success',
                                            'update' => 'warning', 'edit' => 'warning',
                                            'delete' => 'danger',  'remove' => 'danger',
                                            'login'  => 'primary', 'logout' => 'neutral',
                                        ];
                                        $action = strtolower($log['action'] ?? '');
                                        $color = 'neutral';
                                        foreach ($actionColors as $k => $c) {
                                            if (str_contains($action, $k)) { $color = $c; break; }
                                        }
                                        ?>
                                        <span class="ts-badge ts-badge-<?= $color ?>">
                                            <?= htmlspecialchars($log['action'] ?? '—') ?>
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell" style="color:var(--text-muted);max-width:280px;font-size:12.5px">
                                        <?= htmlspecialchars(mb_strimwidth($log['details'] ?? '—', 0, 100, '...')) ?>
                                    </td>
                                    <td class="d-none d-lg-table-cell" style="color:var(--text-muted);font-size:12px">
                                        <?= htmlspecialchars($log['ip_address'] ?? '—') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="ts-table-empty">
                            <i class="fas fa-history"></i>
                            Aucun log disponible pour le moment
                        </div>
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
