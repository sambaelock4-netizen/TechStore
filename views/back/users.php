<?php
/**
 * TECHSTORE - Gestion des Utilisateurs
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs - TechStore Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/users" class="ts-nav-item active"><i class="fas fa-users"></i><span>Utilisateurs</span></a>
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
                    <h1 class="ts-page-title">Gestion des Utilisateurs</h1>
                    <p class="ts-page-subtitle">Clients et administrateurs de la boutique</p>
                </div>
                <div class="ts-page-actions">
                    <a href="<?= BASE_URL ?>/admin/users/add" class="ts-btn ts-btn-primary">
                        <i class="fas fa-user-plus"></i> Ajouter un utilisateur
                    </a>
                </div>
            </div>

            <div class="ts-page-body">

                <!-- Filtres -->
                <div class="ts-filter-bar">
                    <form method="GET" action="<?= BASE_URL ?>/admin/users" style="display:flex;gap:12px;flex-wrap:wrap;width:100%;align-items:flex-end;">
                        <div class="ts-filter-group" style="max-width:280px">
                            <label class="ts-filter-label">Recherche</label>
                            <div class="ts-input-icon">
                                <i class="fas fa-search"></i>
                                <input type="text" name="search" class="ts-input" placeholder="Nom, email..."
                                       value="<?= htmlspecialchars($search ?? '') ?>">
                            </div>
                        </div>
                        <div class="ts-filter-group" style="max-width:180px">
                            <label class="ts-filter-label">Rôle</label>
                            <select name="role" class="ts-input ts-select">
                                <option value="">Tous les rôles</option>
                                <option value="client" <?= ($selectedRole ?? '') === 'client' ? 'selected' : '' ?>>Client</option>
                                <option value="admin"  <?= ($selectedRole ?? '') === 'admin'  ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="ts-btn ts-btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
                    </form>
                </div>

                <!-- Table -->
                <div class="ts-card">
                    <div class="ts-card-header">
                        <div class="ts-card-title"><i class="fas fa-users"></i> Liste des utilisateurs</div>
                        <?php if (!empty($users)): ?>
                        <span class="ts-badge ts-badge-primary"><?= count($users) ?> utilisateur(s)</span>
                        <?php endif; ?>
                    </div>
                    <div class="ts-card-body-flush">
                        <?php if (!empty($users)): ?>
                        <div class="ts-table-wrapper">
                            <table class="ts-table">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th class="d-none d-lg-table-cell">Email</th>
                                        <th>Rôle</th>
                                        <th class="d-none d-md-table-cell">Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:12px">
                                            <div style="width:38px;height:38px;border-radius:50%;background:var(--primary-bg);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--primary);font-weight:700;flex-shrink:0">
                                                <?= strtoupper(substr($user['firstname'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div style="font-weight:600"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></div>
                                                <small class="d-lg-none" style="color:var(--text-muted)"><?= htmlspecialchars($user['email']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell" style="color:var(--text-muted)"><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <span class="ts-badge ts-badge-purple"><i class="fas fa-shield-alt"></i> Admin</span>
                                        <?php else: ?>
                                            <span class="ts-badge ts-badge-info"><i class="fas fa-user"></i> Client</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php if (($user['is_active'] ?? 1) == 1): ?>
                                            <span class="ts-badge ts-badge-success">Actif</span>
                                        <?php else: ?>
                                            <span class="ts-badge ts-badge-neutral">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px">
                                            <a href="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>" class="ts-action-btn ts-action-btn-edit" title="Modifier"><i class="bi bi-pencil"></i></a>
                                            <?php if ($user['role'] !== 'admin'): ?>
                                            <a href="<?= BASE_URL ?>/admin/users/reset/<?= $user['id'] ?>" class="ts-action-btn ts-action-btn-reset d-none d-sm-inline-flex" title="Réinitialiser mot de passe"
                                               onclick="return confirm('Réinitialiser le mot de passe ?')"><i class="bi bi-key"></i></a>
                                            <a href="<?= BASE_URL ?>/admin/users/delete/<?= $user['id'] ?>" class="ts-action-btn ts-action-btn-delete d-none d-sm-inline-flex" title="Supprimer"
                                               onclick="return confirm('Supprimer cet utilisateur ?')"><i class="bi bi-trash"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="ts-table-empty">
                            <i class="bi bi-people"></i>
                            Aucun utilisateur trouvé
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
