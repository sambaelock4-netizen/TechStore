<?php
/**
 * TECHSTORE - Formulaire Utilisateur
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($user) && $user ? 'Modifier' : 'Ajouter' ?> un utilisateur - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
    <style>
        .form-check-input:checked { background-color:var(--primary); border-color:var(--primary); }
        .form-check-input { width:2.4em; height:1.2em; cursor:pointer; }
    </style>
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
                <div class="ts-page-header-left">
                    <a href="<?= BASE_URL ?>/admin/users" class="ts-back-btn"><i class="fas fa-arrow-left"></i></a>
                    <div>
                        <h1 class="ts-page-title"><?= isset($user) && $user ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' ?></h1>
                        <p class="ts-page-subtitle"><?= isset($user) && $user ? 'Modifier les informations du compte' : 'Créer un nouveau compte' ?></p>
                    </div>
                </div>
            </div>

            <div class="ts-page-body">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="ts-card">
                            <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-user-edit"></i> Informations du compte</div></div>
                            <div class="ts-card-body">
                                <form method="POST">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="ts-label">Prénom <span style="color:var(--danger)">*</span></label>
                                            <div class="ts-input-icon"><i class="fas fa-user"></i>
                                                <input type="text" name="firstname" class="ts-input" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Nom <span style="color:var(--danger)">*</span></label>
                                            <div class="ts-input-icon"><i class="fas fa-user"></i>
                                                <input type="text" name="lastname" class="ts-input" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">E-mail <span style="color:var(--danger)">*</span></label>
                                            <div class="ts-input-icon"><i class="fas fa-envelope"></i>
                                                <input type="email" name="email" class="ts-input" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Téléphone</label>
                                            <div class="ts-input-icon"><i class="fas fa-phone"></i>
                                                <input type="tel" name="phone" class="ts-input" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label"><?= isset($user) && $user ? 'Nouveau mot de passe' : 'Mot de passe' ?> <?= !isset($user) ? '<span style="color:var(--danger)">*</span>' : '' ?></label>
                                            <div class="ts-input-icon"><i class="fas fa-lock"></i>
                                                <input type="password" name="password" class="ts-input" <?= !isset($user) ? 'required' : '' ?> minlength="8"
                                                       placeholder="<?= isset($user) ? 'Laisser vide pour conserver' : 'Min. 8 caractères' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Rôle</label>
                                            <div class="ts-input-icon"><i class="fas fa-shield-alt"></i>
                                                <select name="role" class="ts-input ts-select">
                                                    <option value="client" <?= ($user['role'] ?? 'client') === 'client' ? 'selected' : '' ?>>Client</option>
                                                    <option value="admin"  <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="ts-label">Adresse</label>
                                            <div class="ts-input-icon"><i class="fas fa-map-marker-alt"></i>
                                                <input type="text" name="address" class="ts-input" value="<?= htmlspecialchars($user['address'] ?? '') ?>" placeholder="Rue, numéro...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="ts-label">Code postal</label>
                                            <input type="text" name="postal_code" class="ts-input" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="ts-label">Ville</label>
                                            <input type="text" name="city" class="ts-input" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                                        </div>
                                        <div class="col-12" style="display:flex; align-items:center; gap:10px; padding:6px 0">
                                            <input class="form-check-input form-switch" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                                   <?= (!isset($user['is_active']) || $user['is_active']) ? 'checked' : '' ?>>
                                            <label for="is_active" style="font-size:13.5px; font-weight:500; cursor:pointer; margin:0">
                                                <i class="fas fa-check-circle" style="color:var(--success);margin-right:6px"></i>Compte actif
                                            </label>
                                        </div>
                                        <div class="col-12" style="margin-top:4px; display:flex; gap:10px">
                                            <button type="submit" class="ts-btn ts-btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                                            <a href="<?= BASE_URL ?>/admin/users" class="ts-btn ts-btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="ts-card">
                            <div class="ts-card-body" style="text-align:center; padding:28px 20px">
                                <div class="ts-profile-avatar">
                                    <?= strtoupper(substr($user['firstname'] ?? 'U', 0, 1)) ?>
                                </div>
                                <?php if (isset($user) && $user): ?>
                                <h6 style="font-weight:700; margin-bottom:4px"><?= htmlspecialchars(($user['firstname'] ?? '').' '.($user['lastname'] ?? '')) ?></h6>
                                <p style="color:var(--text-muted); font-size:12px; margin-bottom:12px"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                                <span class="ts-badge <?= ($user['role'] ?? '') === 'admin' ? 'ts-badge-purple' : 'ts-badge-info' ?>">
                                    <?= ($user['role'] ?? '') === 'admin' ? '<i class="fas fa-shield-alt"></i> Admin' : '<i class="fas fa-user"></i> Client' ?>
                                </span>
                                <?php else: ?>
                                <p style="color:var(--text-muted); font-size:13px">Nouveau compte utilisateur</p>
                                <?php endif; ?>
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
