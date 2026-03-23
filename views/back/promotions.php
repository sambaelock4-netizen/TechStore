<?php
/**
 * TECHSTORE - Gestion des Promotions
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotions - TechStore Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/promotions" class="ts-nav-item active"><i class="fas fa-percent"></i><span>Promotions</span></a>
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
                    <h1 class="ts-page-title">Gestion des Promotions</h1>
                    <p class="ts-page-subtitle">Créez et gérez vos promotions et codes promo</p>
                </div>
                <div class="ts-page-actions">
                    <a href="<?= BASE_URL ?>/admin/promotions/add" class="ts-btn ts-btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle promotion
                    </a>
                </div>
            </div>

            <div class="ts-page-body">
                <div class="ts-card">
                    <div class="ts-card-header">
                        <div class="ts-card-title"><i class="fas fa-percent"></i> Liste des promotions</div>
                        <?php if (!empty($promotions)): ?>
                        <span class="ts-badge ts-badge-primary"><?= count($promotions) ?> promotion(s)</span>
                        <?php endif; ?>
                    </div>
                    <div class="ts-card-body-flush">
                        <?php if (!empty($promotions)): ?>
                        <div class="ts-table-wrapper">
                            <table class="ts-table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th class="d-none d-md-table-cell">Code promo</th>
                                        <th>Valeur</th>
                                        <th class="d-none d-lg-table-cell">Période</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($promotions as $promo):
                                    $now   = new DateTime();
                                    $start = new DateTime($promo['start_date']);
                                    $end   = new DateTime($promo['end_date']);
                                    $isActive = $promo['is_active'] && $now >= $start && $now <= $end;
                                ?>
                                <tr>
                                    <td>
                                        <div style="font-weight:600"><?= htmlspecialchars($promo['name']) ?></div>
                                        <small class="d-md-none" style="color:var(--text-muted)">
                                            <?= htmlspecialchars($promo['code'] ?? '') ?>
                                        </small>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php if (!empty($promo['code'])): ?>
                                        <code class="ts-code"><?= htmlspecialchars($promo['code']) ?></code>
                                        <?php else: ?>
                                        <span style="color:var(--text-muted)">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span style="font-weight:700; color:var(--danger); font-size:15px">
                                            <?= $promo['type'] === 'percentage' ? $promo['value'] . '%' : displayPrice($promo['value']) ?>
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell" style="color:var(--text-muted); font-size:12.5px">
                                        <div><?= date('d/m/Y', strtotime($promo['start_date'])) ?></div>
                                        <div>→ <?= date('d/m/Y', strtotime($promo['end_date'])) ?></div>
                                    </td>
                                    <td>
                                        <?php if ($isActive): ?>
                                            <span class="ts-badge ts-badge-success"><i class="fas fa-check-circle"></i> Active</span>
                                        <?php else: ?>
                                            <span class="ts-badge ts-badge-neutral">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px">
                                            <a href="<?= BASE_URL ?>/admin/promotions/edit/<?= $promo['id'] ?>" class="ts-action-btn ts-action-btn-edit" title="Modifier"><i class="bi bi-pencil"></i></a>
                                            <a href="<?= BASE_URL ?>/admin/promotions/delete/<?= $promo['id'] ?>" class="ts-action-btn ts-action-btn-delete" title="Supprimer"
                                               onclick="return confirm('Supprimer cette promotion ?')"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="ts-table-empty">
                            <i class="fas fa-tags"></i>
                            Aucune promotion créée
                            <a href="<?= BASE_URL ?>/admin/promotions/add" class="ts-btn ts-btn-primary" style="margin-top:14px">
                                <i class="fas fa-plus"></i> Créer une promotion
                            </a>
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
