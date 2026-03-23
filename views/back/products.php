<?php
/**
 * TECHSTORE - Gestion des Produits
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits - TechStore Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/products" class="ts-nav-item active"><i class="fas fa-box"></i><span>Produits</span></a>
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
                    <h1 class="ts-page-title">Gestion des Produits</h1>
                    <p class="ts-page-subtitle">Gérez votre catalogue complet</p>
                </div>
                <div class="ts-page-actions">
                    <a href="<?= BASE_URL ?>/admin/product/add" class="ts-btn ts-btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Nouveau produit</span>
                    </a>
                </div>
            </div>

            <div class="ts-page-body">

                <!-- Filtres -->
                <div class="ts-filter-bar">
                    <form method="GET" action="<?= BASE_URL ?>/admin/products" style="display:flex; gap:12px; flex-wrap:wrap; width:100%; align-items:flex-end;">
                        <div class="ts-filter-group" style="max-width:280px">
                            <label class="ts-filter-label">Recherche</label>
                            <div class="ts-input-icon">
                                <i class="fas fa-search"></i>
                                <input type="text" name="search" class="ts-input" placeholder="Nom, SKU..."
                                       value="<?= htmlspecialchars($search ?? '') ?>">
                            </div>
                        </div>
                        <div class="ts-filter-group" style="max-width:200px">
                            <label class="ts-filter-label">Catégorie</label>
                            <select name="category" class="ts-input ts-select">
                                <option value="">Toutes les catégories</option>
                                <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="ts-filter-group" style="max-width:160px">
                            <label class="ts-filter-label">Statut</label>
                            <select name="status" class="ts-input ts-select">
                                <option value="">Tous</option>
                                <option value="1" <?= ($selectedStatus ?? '') === '1' ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= ($selectedStatus ?? '') === '0' ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>
                        <button type="submit" class="ts-btn ts-btn-primary">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <?php if (!empty($search) || !empty($selectedCategory) || $selectedStatus !== ''): ?>
                        <a href="<?= BASE_URL ?>/admin/products" class="ts-btn ts-btn-secondary">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Table produits -->
                <div class="ts-card">
                    <div class="ts-card-header">
                        <div class="ts-card-title">
                            <i class="fas fa-list"></i>
                            Liste des produits
                        </div>
                        <?php if (!empty($products)): ?>
                        <span class="ts-badge ts-badge-primary"><?= count($products) ?> produit(s)</span>
                        <?php endif; ?>
                    </div>
                    <div class="ts-card-body-flush">
                        <?php if (!empty($products)): ?>
                        <div class="ts-table-wrapper">
                            <table class="ts-table">
                                <thead>
                                    <tr>
                                        <th class="d-none d-lg-table-cell">ID</th>
                                        <th>Image</th>
                                        <th>Produit</th>
                                        <th class="d-none d-md-table-cell">Catégorie</th>
                                        <th>Prix</th>
                                        <th class="d-none d-sm-table-cell">Stock</th>
                                        <th class="d-none d-lg-table-cell">Promo</th>
                                        <th class="d-none d-md-table-cell">Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($products as $product): ?>
                                    <?php $stock = $product['stock'] ?? 0; ?>
                                    <tr>
                                        <td class="d-none d-lg-table-cell" style="color:var(--text-muted)">#<?= $product['id'] ?></td>
                                        <td>
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?= UPLOAD_URL ?>/<?= htmlspecialchars($product['image']) ?>"
                                                     alt="<?= htmlspecialchars($product['name']) ?>" class="ts-thumb">
                                            <?php else: ?>
                                                <div class="ts-thumb-placeholder"><i class="bi bi-image"></i></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="font-weight:600; color:var(--dark)"><?= htmlspecialchars($product['name']) ?></div>
                                            <?php if (!empty($product['sku'])): ?>
                                                <small style="color:var(--text-muted)"><?= htmlspecialchars($product['sku']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-md-table-cell" style="color:var(--text-muted)">
                                            <?= htmlspecialchars($product['category_name'] ?? '—') ?>
                                        </td>
                                        <td>
                                            <?php if (($product['is_promotion'] ?? 0) == 1 && !empty($product['promotion_price'])): ?>
                                                <div style="text-decoration:line-through; color:var(--text-muted); font-size:12px"><?= displayPrice($product['price'] ?? 0) ?></div>
                                                <div style="font-weight:700; color:var(--danger)"><?= displayPrice($product['promotion_price']) ?></div>
                                            <?php else: ?>
                                                <div style="font-weight:700; color:var(--success)"><?= displayPrice($product['price'] ?? 0) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="ts-badge <?= $stock <= 5 ? 'ts-badge-danger' : 'ts-badge-success' ?>">
                                                <?= $stock ?>
                                            </span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <?php if (($product['is_promotion'] ?? 0) == 1): ?>
                                                <span class="ts-badge ts-badge-danger">-<?= $product['discount'] ?? 0 ?>%</span>
                                            <?php else: ?>
                                                <span class="ts-badge ts-badge-neutral">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <?php if (($product['is_active'] ?? 1) == 1): ?>
                                                <span class="ts-badge ts-badge-success">Actif</span>
                                            <?php else: ?>
                                                <span class="ts-badge ts-badge-neutral">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="display:flex; gap:6px">
                                                <a href="<?= BASE_URL ?>/admin/product/edit/<?= $product['id'] ?>" class="ts-action-btn ts-action-btn-edit" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/admin/product/delete/<?= $product['id'] ?>" class="ts-action-btn ts-action-btn-delete" title="Supprimer"
                                                   onclick="return confirm('Supprimer ce produit ?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="ts-table-empty">
                            <i class="bi bi-box-seam"></i>
                            Aucun produit trouvé
                            <a href="<?= BASE_URL ?>/admin/product/add" class="ts-btn ts-btn-primary" style="margin-top:14px">
                                <i class="fas fa-plus"></i> Ajouter un produit
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function(){
        const sidebar=document.querySelector('.ts-sidebar'),overlay=document.querySelector('.ts-overlay'),toggle=document.querySelector('.ts-mobile-toggle');
        function close(){sidebar.classList.remove('open');overlay.classList.remove('open');}
        toggle.addEventListener('click',function(){sidebar.classList.contains('open')?close():(sidebar.classList.add('open'),overlay.classList.add('open'));});
        overlay.addEventListener('click',close);
        window.addEventListener('resize',function(){if(window.innerWidth>991)close();});
    })();
    </script>
</body>
</html>
