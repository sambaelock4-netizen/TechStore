<?php
/**
 * TECHSTORE - Gestion des Catégories
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories - TechStore Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/categories" class="ts-nav-item active"><i class="fas fa-tags"></i><span>Catégories</span></a>
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
                    <h1 class="ts-page-title">Gestion des Catégories</h1>
                    <p class="ts-page-subtitle">Organisez votre catalogue par catégories</p>
                </div>
                <div class="ts-page-actions">
                    <a href="<?= BASE_URL ?>/admin/categories/add" class="ts-btn ts-btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle catégorie
                    </a>
                </div>
            </div>

            <div class="ts-page-body">
                <div class="ts-card">
                    <div class="ts-card-header">
                        <div class="ts-card-title"><i class="fas fa-tags"></i> Liste des catégories</div>
                        <?php if (!empty($categories)): ?>
                        <span class="ts-badge ts-badge-primary"><?= count($categories) ?> catégorie(s)</span>
                        <?php endif; ?>
                    </div>
                    <div class="ts-card-body-flush">
                        <?php if (!empty($categories)): ?>
                        <div class="ts-table-wrapper">
                            <table class="ts-table">
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th class="d-none d-md-table-cell">Description</th>
                                        <th>Produits</th>
                                        <th class="d-none d-md-table-cell">Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:12px">
                                            <?php if (!empty($cat['icon'])): ?>
                                            <div style="width:38px;height:38px;border-radius:10px;background:var(--primary-bg);display:flex;align-items:center;justify-content:center;font-size:16px">
                                                <i class="<?= htmlspecialchars($cat['icon']) ?>" style="color:var(--primary)"></i>
                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <div style="font-weight:600"><?= htmlspecialchars($cat['name']) ?></div>
                                                <?php if (!empty($cat['slug'])): ?>
                                                <small style="color:var(--text-muted)">/<?= htmlspecialchars($cat['slug']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell" style="color:var(--text-muted);max-width:280px">
                                        <?= htmlspecialchars(mb_strimwidth($cat['description'] ?? '—', 0, 80, '...')) ?>
                                    </td>
                                    <td>
                                        <span class="ts-badge ts-badge-primary">
                                            <?= $cat['product_count'] ?? 0 ?> produit(s)
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php if (($cat['is_active'] ?? 1) == 1): ?>
                                            <span class="ts-badge ts-badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="ts-badge ts-badge-neutral">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px">
                                            <a href="<?= BASE_URL ?>/admin/categories/edit/<?= $cat['id'] ?>" class="ts-action-btn ts-action-btn-edit" title="Modifier"><i class="bi bi-pencil"></i></a>
                                            <a href="<?= BASE_URL ?>/admin/categories/delete/<?= $cat['id'] ?>" class="ts-action-btn ts-action-btn-delete" title="Supprimer"
                                               onclick="return confirm('Supprimer cette catégorie ?')"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="ts-table-empty">
                            <i class="bi bi-tags"></i>
                            Aucune catégorie créée
                            <a href="<?= BASE_URL ?>/admin/categories/add" class="ts-btn ts-btn-primary" style="margin-top:14px">
                                <i class="fas fa-plus"></i> Créer une catégorie
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
