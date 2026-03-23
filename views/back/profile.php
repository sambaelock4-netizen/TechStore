<?php
/**
 * TECHSTORE - Mon Profil
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - TechStore Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/profile" class="ts-nav-item active"><i class="fas fa-user-cog"></i><span>Mon Profil</span></a>
            </nav>
            <div class="ts-sidebar-footer">
                <a href="<?= BASE_URL ?>/home" class="ts-logout-btn ts-back-link"><i class="fas fa-arrow-left"></i><span>Retour au site</span></a>
                <a href="<?= BASE_URL ?>/logout" class="ts-logout-btn"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
            </div>
        </aside>

        <main class="ts-main">
            <div class="ts-page-header">
                <div>
                    <h1 class="ts-page-title">Mon Profil</h1>
                    <p class="ts-page-subtitle">Gérez vos informations personnelles et accès</p>
                </div>
            </div>

            <div class="ts-page-body">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="ts-card">
                            <div class="ts-card-header">
                                <div class="ts-card-title"><i class="fas fa-user-edit"></i> Informations personnelles</div>
                            </div>
                            <div class="ts-card-body">
                                <form method="POST" action="<?= BASE_URL ?>/admin/profile/update">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="ts-label">Prénom</label>
                                            <div class="ts-input-icon">
                                                <i class="fas fa-user"></i>
                                                <input type="text" name="firstname" class="ts-input" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Nom</label>
                                            <div class="ts-input-icon">
                                                <i class="fas fa-user"></i>
                                                <input type="text" name="lastname" class="ts-input" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">E-mail</label>
                                            <div class="ts-input-icon">
                                                <i class="fas fa-envelope"></i>
                                                <input type="email" name="email" class="ts-input" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Téléphone</label>
                                            <div class="ts-input-icon">
                                                <i class="fas fa-phone"></i>
                                                <input type="tel" name="phone" class="ts-input" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-12" style="margin-top:8px">
                                            <button type="submit" class="ts-btn ts-btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="ts-card">
                            <div class="ts-card-header">
                                <div class="ts-card-title"><i class="fas fa-lock"></i> Changer le mot de passe</div>
                            </div>
                            <div class="ts-card-body">
                                <form method="POST" action="<?= BASE_URL ?>/admin/profile/password">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="ts-label">Mot de passe actuel</label>
                                            <div class="ts-input-icon"><i class="fas fa-key"></i><input type="password" name="current_password" class="ts-input" required></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Nouveau mot de passe</label>
                                            <div class="ts-input-icon"><i class="fas fa-lock"></i><input type="password" name="new_password" class="ts-input" required minlength="8"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Confirmer</label>
                                            <div class="ts-input-icon"><i class="fas fa-lock"></i><input type="password" name="confirm_password" class="ts-input" required></div>
                                        </div>
                                        <div class="col-12" style="margin-top:8px">
                                            <button type="submit" class="ts-btn ts-btn-warning"><i class="fas fa-key"></i> Mettre à jour</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="ts-card">
                            <div class="ts-card-body" style="text-align:center; padding:32px 24px">
                                <div class="ts-profile-avatar">
                                    <?= strtoupper(substr($user['firstname'] ?? 'A', 0, 1)) ?>
                                </div>
                                <h5 style="font-weight:700; margin-bottom:4px; color:var(--dark)">
                                    <?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?>
                                </h5>
                                <p style="color:var(--text-muted); font-size:13px; margin-bottom:16px"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                                <span class="ts-badge ts-badge-primary" style="padding:6px 16px">
                                    <i class="fas fa-shield-alt"></i> Administrateur
                                </span>
                                <hr class="ts-divider" style="margin:24px 0">
                                <div style="display:flex; flex-direction:column; gap:10px; text-align:left">
                                    <div style="display:flex; align-items:center; gap:12px; padding:12px; background:var(--bg); border-radius:10px">
                                        <i class="fas fa-calendar-alt" style="color:var(--primary); width:16px"></i>
                                        <div>
                                            <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase">Membre depuis</div>
                                            <div style="font-weight:600; font-size:13px"><?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?></div>
                                        </div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:12px; padding:12px; background:var(--bg); border-radius:10px">
                                        <i class="fas fa-circle" style="color:var(--success); width:16px; font-size:9px"></i>
                                        <div>
                                            <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase">Statut</div>
                                            <div style="font-weight:600; font-size:13px; color:var(--success)">Compte actif</div>
                                        </div>
                                    </div>
                                </div>
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
