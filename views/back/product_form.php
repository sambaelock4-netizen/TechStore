<?php
/**
 * TECHSTORE - Formulaire Produit (ajout/modification)
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($product) && $product ? 'Modifier' : 'Ajouter' ?> un produit - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin.css">
    <style>
        .ts-upload-zone {
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: var(--bg);
        }
        .ts-upload-zone:hover { border-color: var(--primary); background: var(--primary-bg); }
        .ts-upload-zone i { font-size: 40px; color: var(--text-muted); display:block; margin-bottom:10px; }
        .ts-promo-box {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: var(--radius);
            padding: 20px;
        }
        .ts-section-label {
            font-size: 11px; font-weight: 700; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 1px;
            padding-bottom: 12px; margin-bottom: 16px;
            border-bottom: 1px solid var(--border-light);
        }
        .ts-switch { display:flex; align-items:center; gap:10px; padding:10px 0; }
        .ts-switch label { font-size:13.5px; font-weight:500; cursor:pointer; }
        .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
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
                <div class="ts-page-header-left">
                    <a href="<?= BASE_URL ?>/admin/products" class="ts-back-btn"><i class="fas fa-arrow-left"></i></a>
                    <div>
                        <h1 class="ts-page-title">
                            <?= isset($product) && $product ? 'Modifier le produit' : 'Nouveau produit' ?>
                        </h1>
                        <p class="ts-page-subtitle">
                            <?= isset($product) && $product ? 'Modifier les informations du produit' : 'Ajouter un produit à votre catalogue' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="ts-page-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-4">

                        <!-- Colonne principale -->
                        <div class="col-lg-8">

                            <!-- Informations de base -->
                            <div class="ts-card">
                                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-info-circle"></i> Informations du produit</div></div>
                                <div class="ts-card-body">
                                    <div class="ts-form-group">
                                        <label class="ts-label">Nom du produit <span style="color:var(--danger)">*</span></label>
                                        <input type="text" name="name" class="ts-input"
                                               value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                                               placeholder="Ex: MacBook Pro 14 pouces M3" required>
                                    </div>
                                    <div class="ts-form-group">
                                        <label class="ts-label">Description courte</label>
                                        <input type="text" name="short_description" class="ts-input"
                                               value="<?= htmlspecialchars($product['short_description'] ?? '') ?>"
                                               placeholder="Résumé en une ligne pour les listes">
                                    </div>
                                    <div class="ts-form-group" style="margin-bottom:0">
                                        <label class="ts-label">Description complète</label>
                                        <textarea name="description" class="ts-input ts-textarea" rows="5"
                                                  placeholder="Décrivez le produit en détail..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Prix & Stock -->
                            <div class="ts-card">
                                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-coins"></i> Prix & Stock</div></div>
                                <div class="ts-card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="ts-label">Prix normal (FC) <span style="color:var(--danger)">*</span></label>
                                            <div class="ts-input-icon">
                                                <i class="fas fa-coins"></i>
                                                <input type="number" name="price" class="ts-input"
                                                       step="0.01" min="0" value="<?= $product['price'] ?? '' ?>"
                                                       placeholder="0.00" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ts-label">Stock initial <span style="color:var(--danger)">*</span></label>
                                            <div class="ts-input-icon">
                                                <i class="fas fa-boxes"></i>
                                                <input type="number" name="stock" class="ts-input"
                                                       min="0" value="<?= $product['stock'] ?? 0 ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="ts-label">Catégorie</label>
                                            <div class="ts-input-icon">
                                                <i class="fas fa-tags"></i>
                                                <select name="category_id" class="ts-input ts-select">
                                                    <option value="">Sélectionner une catégorie</option>
                                                    <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                                                    <option value="<?= $cat['id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($cat['name']) ?>
                                                    </option>
                                                    <?php endforeach; endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Promotion -->
                                    <div class="ts-promo-box" style="margin-top:20px">
                                        <div class="ts-section-label"><i class="fas fa-percentage" style="color:var(--warning);margin-right:6px"></i>Section Promotion</div>
                                        <div class="ts-switch">
                                            <input class="form-check-input form-switch" type="checkbox" role="switch"
                                                   id="is_promotion" name="is_promotion" value="1"
                                                   <?= (isset($product['is_promotion']) && $product['is_promotion']) ? 'checked' : '' ?>
                                                   onchange="togglePromo()">
                                            <label for="is_promotion" class="ts-label" style="margin:0">Activer la promotion</label>
                                        </div>
                                        <div id="promoFields" style="<?= (isset($product['is_promotion']) && $product['is_promotion']) ? '' : 'display:none' ?>; margin-top:14px">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="ts-label">Prix promotionnel (FC)</label>
                                                    <div class="ts-input-icon">
                                                        <i class="fas fa-tag"></i>
                                                        <input type="number" name="promotion_price" class="ts-input"
                                                               step="0.01" min="0" value="<?= $product['promotion_price'] ?? '' ?>" placeholder="0.00">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="ts-label">Remise (%)</label>
                                                    <div class="ts-input-icon">
                                                        <i class="fas fa-percent"></i>
                                                        <input type="number" name="discount" class="ts-input"
                                                               min="0" max="100" value="<?= $product['discount'] ?? 0 ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="ts-label">Date de début</label>
                                                    <input type="date" name="promotion_start_date" class="ts-input"
                                                           value="<?= $product['promotion_start_date'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="ts-label">Date de fin</label>
                                                    <input type="date" name="promotion_end_date" class="ts-input"
                                                           value="<?= $product['promotion_end_date'] ?? '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Colonne latérale -->
                        <div class="col-lg-4">

                            <!-- Image -->
                            <div class="ts-card">
                                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-image"></i> Image du produit</div></div>
                                <div class="ts-card-body">
                                    <div class="ts-upload-zone" id="uploadZone" onclick="document.getElementById('image').click()">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <p style="font-weight:600; margin:0 0 4px">Cliquez pour uploader</p>
                                        <small style="color:var(--text-muted)">JPG, PNG, WEBP — max 2 MB</small>
                                    </div>
                                    <input type="file" id="image" name="image" accept="image/*" style="display:none"
                                           onchange="previewImage(this)">
                                    <div id="imagePreview" style="margin-top:14px; text-align:center; display:none">
                                        <img id="previewImg" src="" alt="Aperçu" style="max-width:100%; border-radius:var(--radius); border:1px solid var(--border)">
                                    </div>
                                    <?php if (!empty($product['image'])): ?>
                                    <div id="currentImage" style="margin-top:14px; text-align:center; position:relative">
                                        <img src="<?= UPLOAD_URL ?>/<?= htmlspecialchars($product['image']) ?>"
                                             alt="Image actuelle" style="max-width:100%; border-radius:var(--radius); border:1px solid var(--border)">
                                        <button type="button" onclick="removeCurrentImage()"
                                                style="position:absolute;top:-8px;right:-8px;background:var(--danger);color:white;border:none;border-radius:50%;width:26px;height:26px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:11px">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="checkbox" id="delete_image" name="delete_image" value="1" style="display:none">
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="ts-card">
                                <div class="ts-card-header"><div class="ts-card-title"><i class="fas fa-sliders-h"></i> Options</div></div>
                                <div class="ts-card-body" style="padding-top:8px; padding-bottom:8px">
                                    <div class="ts-switch">
                                        <input class="form-check-input form-switch" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                               <?= (!isset($product['is_active']) || $product['is_active']) ? 'checked' : '' ?>>
                                        <label for="is_active" style="margin:0; font-size:13.5px; font-weight:500; cursor:pointer">
                                            <i class="fas fa-check-circle" style="color:var(--success);margin-right:6px"></i>Produit actif
                                        </label>
                                    </div>
                                    <div class="ts-switch">
                                        <input class="form-check-input form-switch" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1"
                                               <?= (isset($product['is_featured']) && $product['is_featured']) ? 'checked' : '' ?>>
                                        <label for="is_featured" style="margin:0; font-size:13.5px; font-weight:500; cursor:pointer">
                                            <i class="fas fa-star" style="color:var(--warning);margin-right:6px"></i>Produit en vedette
                                        </label>
                                    </div>
                                    <div class="ts-switch" style="border-top:1px solid var(--border-light); padding-top:10px">
                                        <input class="form-check-input form-switch" type="checkbox" role="switch" id="is_production" name="is_production" value="1"
                                               <?= (isset($product['is_production']) && $product['is_production']) ? 'checked' : '' ?>>
                                        <label for="is_production" style="margin:0; font-size:13.5px; font-weight:500; cursor:pointer">
                                            <i class="fas fa-industry" style="color:var(--primary);margin-right:6px"></i>En production
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div style="display:flex; gap:10px; margin-top:4px">
                                <button type="submit" class="ts-btn ts-btn-primary" style="flex:1">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                                <a href="<?= BASE_URL ?>/admin/products" class="ts-btn ts-btn-secondary" style="padding:10px 16px">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function(){const s=document.querySelector('.ts-sidebar'),o=document.querySelector('.ts-overlay'),t=document.querySelector('.ts-mobile-toggle');function c(){s.classList.remove('open');o.classList.remove('open');}t.addEventListener('click',function(){s.classList.contains('open')?c():(s.classList.add('open'),o.classList.add('open'));});o.addEventListener('click',c);window.addEventListener('resize',function(){if(window.innerWidth>991)c();});})();

    function togglePromo() {
        const f = document.getElementById('promoFields');
        f.style.display = document.getElementById('is_promotion').checked ? '' : 'none';
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = '';
                document.getElementById('uploadZone').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeCurrentImage() {
        document.getElementById('delete_image').checked = true;
        document.getElementById('currentImage').style.display = 'none';
    }
    </script>
</body>
</html>
