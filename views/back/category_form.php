<?php
/**
 * TECHSTORE - Formulaire Catégorie
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Times New Roman system font — no import needed -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($category) && $category ? 'Modifier' : 'Ajouter' ?> une catégorie - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
    <style>
        .ts-icon-preview { width:54px; height:54px; border-radius:14px; background:var(--primary-bg); display:flex; align-items:center; justify-content:center; font-size:22px; color:var(--primary); }
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
                <div class="ts-page-header-left">
                    <a href="<?= BASE_URL ?>/admin/categories" class="ts-back-btn"><i class="fas fa-arrow-left"></i></a>
                    <div>
                        <h1 class="ts-page-title"><?= isset($category) && $category ? 'Modifier la catégorie' : 'Nouvelle catégorie' ?></h1>
                        <p class="ts-page-subtitle">Organiser votre catalogue de produits</p>
                    </div>
                </div>
            </div>

            <div class="ts-page-body">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="ts-card">
                            <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-tag"></i> Informations de la catégorie</div></div>
                            <div class="ts-card-body">
                                <form method="POST">
                                    <div class="ts-form-group">
                                        <label class="ts-label">Nom de la catégorie <span style="color:var(--danger)">*</span></label>
                                        <div class="ts-input-icon"><i class="fas fa-tag"></i>
                                            <input type="text" name="name" class="ts-input"
                                                   value="<?= htmlspecialchars($category['name'] ?? '') ?>"
                                                   placeholder="Ex: Smartphones, Ordinateurs..." required>
                                        </div>
                                    </div>
                                    <div class="ts-form-group">
                                        <label class="ts-label">Slug URL</label>
                                        <div class="ts-input-icon"><i class="fas fa-link"></i>
                                            <input type="text" name="slug" class="ts-input"
                                                   value="<?= htmlspecialchars($category['slug'] ?? '') ?>"
                                                   placeholder="generé-automatiquement">
                                        </div>
                                        <small style="color:var(--text-muted); font-size:12px">Laissez vide pour générer automatiquement</small>
                                    </div>
                                    <div class="ts-form-group">
                                        <label class="ts-label">Description</label>
                                        <textarea name="description" class="ts-input ts-textarea" rows="4"
                                                  placeholder="Description de la catégorie..."><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                                    </div>
                                    <div class="ts-form-group">
                                        <label class="ts-label">Icône FontAwesome</label>
                                        <div class="ts-input-icon"><i class="fas fa-icons"></i>
                                            <input type="text" name="icon" class="ts-input" id="iconInput"
                                                   value="<?= htmlspecialchars($category['icon'] ?? '') ?>"
                                                   placeholder="Ex: fas fa-laptop"
                                                   oninput="updateIconPreview(this.value)">
                                        </div>
                                        <small style="color:var(--text-muted); font-size:12px">Classe FontAwesome, ex: <code style="font-size:11px">fas fa-laptop</code></small>
                                    </div>
                                    <div class="ts-form-group">
                                        <label class="ts-label">Catégorie parente</label>
                                        <div class="ts-input-icon"><i class="fas fa-sitemap"></i>
                                            <select name="parent_id" class="ts-input ts-select">
                                                <option value="">Aucune (catégorie racine)</option>
                                                <?php if (!empty($categories)): foreach ($categories as $cat):
                                                    if (isset($category['id']) && $cat['id'] == $category['id']) continue;
                                                ?>
                                                <option value="<?= $cat['id'] ?>" <?= (isset($category['parent_id']) && $category['parent_id'] == $cat['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat['name']) ?>
                                                </option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:10px; padding:8px 0 18px">
                                        <input class="form-check-input form-switch" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                               <?= (!isset($category['is_active']) || $category['is_active']) ? 'checked' : '' ?>>
                                        <label for="is_active" style="font-size:13.5px; font-weight:500; cursor:pointer; margin:0">
                                            <i class="fas fa-check-circle" style="color:var(--success);margin-right:6px"></i>Catégorie active
                                        </label>
                                    </div>
                                    <div style="display:flex; gap:10px">
                                        <button type="submit" class="ts-btn ts-btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                                        <a href="<?= BASE_URL ?>/admin/categories" class="ts-btn ts-btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="ts-card">
                            <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-eye"></i> Aperçu</div></div>
                            <div class="ts-card-body" style="text-align:center; padding:32px 24px">
                                <div id="iconPreview" class="ts-icon-preview" style="margin:0 auto 16px">
                                    <?php if (!empty($category['icon'])): ?>
                                    <i class="<?= htmlspecialchars($category['icon']) ?>"></i>
                                    <?php else: ?>
                                    <i class="fas fa-tag"></i>
                                    <?php endif; ?>
                                </div>
                                <div style="font-weight:700; font-size:15px; color:var(--dark)" id="previewName">
                                    <?= htmlspecialchars($category['name'] ?? 'Nom de la catégorie') ?>
                                </div>
                                <div style="color:var(--text-muted); font-size:12px; margin-top:4px" id="previewSlug">
                                    /<?= htmlspecialchars($category['slug'] ?? 'slug-url') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function(){const s=document.querySelector('.ts-sidebar'),o=document.querySelector('.ts-overlay'),t=document.querySelector('.ts-mobile-toggle');function c(){s.classList.remove('open');o.classList.remove('open');}t.addEventListener('click',function(){s.classList.contains('open')?c():(s.classList.add('open'),o.classList.add('open'));});o.addEventListener('click',c);window.addEventListener('resize',function(){if(window.innerWidth>991)c();});})();

    function updateIconPreview(val) {
        document.getElementById('iconPreview').innerHTML = '<i class="' + val + '"></i>';
    }

    document.querySelector('[name="name"]')?.addEventListener('input', function(){
        document.getElementById('previewName').textContent = this.value || 'Nom de la catégorie';
    });
    document.querySelector('[name="slug"]')?.addEventListener('input', function(){
        document.getElementById('previewSlug').textContent = '/' + (this.value || 'slug-url');
    });
    </script>
</body>
</html>
