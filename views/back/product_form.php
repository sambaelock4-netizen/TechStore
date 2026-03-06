<?php
/**
 * TECHSTORE - Admin Product Form Responsive
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($product) && $product ? 'Modifier' : 'Ajouter'; ?> un produit - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #0dcaf0;
            --dark: #1a1d20;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--dark) 0%, #2d3238 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), #0a58ca);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .sidebar-brand .brand-text {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .sidebar-nav {
            padding: 15px 0;
        }
        
        .nav-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin: 2px 10px;
            border-radius: 8px;
        }
        
        .nav-item-custom:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }
        
        .nav-item-custom.active {
            background: rgba(13, 110, 253, 0.15);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        
        .nav-item-custom i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }
        
        .nav-item-custom span {
            font-size: 14px;
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 5px 10px;
            border-radius: 8px;
        }
        
        .logout-btn:hover {
            background: rgba(220, 53, 69, 0.2);
            color: var(--danger);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }
        
        .content-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
        }
        
        .content-card .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }
        
        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafbfc;
        }
        
        .image-upload-area:hover {
            border-color: var(--primary);
            background: #f0f7ff;
        }
        
        .image-upload-area i {
            font-size: 48px;
            color: #adb5bd;
            margin-bottom: 15px;
        }
        
        .current-image-preview {
            position: relative;
            display: inline-block;
            margin-top: 15px;
        }
        
        .current-image-preview img {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 200px;
        }
        
        .current-image-preview .remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #0a58ca);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }
        
        .btn-secondary {
            background: var(--secondary);
            border: none;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--secondary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .promotion-section {
            background: linear-gradient(135deg, #fff3cd 0%, #fff8e1 100%);
            border: 1px solid #ffc107;
            border-radius: 12px;
            padding: 20px;
            margin-top: 15px;
        }
        
        .promotion-section .section-title {
            color: #d69b00;
            border-bottom-color: #ffc107;
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            width: 45px;
            height: 45px;
            font-size: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        /* Responsive Styles */
        @media (max-width: 991px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .main-content {
                margin-left: 0;
                padding: 70px 20px 20px;
            }
            
            .sidebar-brand {
                padding: 15px;
            }
            
            .sidebar-brand .brand-icon {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
            
            .sidebar-brand .brand-text {
                font-size: 18px;
            }
            
            .nav-item-custom {
                padding: 12px 15px;
                gap: 10px;
            }
            
            .nav-item-custom i {
                font-size: 16px;
            }
            
            .nav-item-custom span {
                font-size: 13px;
            }
        }
        
        @media (max-width: 767px) {
            .main-content {
                padding: 70px 10px 15px;
            }
            
            .content-card {
                border-radius: 12px;
                margin-bottom: 15px;
            }
            
            .content-card .card-body {
                padding: 15px !important;
            }
            
            .section-title {
                font-size: 12px;
                margin-bottom: 15px;
                padding-bottom: 8px;
            }
            
            .form-label {
                font-size: 13px;
            }
            
            .form-control, .form-select {
                padding: 10px 12px;
                font-size: 13px;
            }
            
            .image-upload-area {
                padding: 20px;
            }
            
            .image-upload-area i {
                font-size: 36px;
            }
            
            .current-image-preview img {
                max-width: 150px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 13px;
            }
            
            .promotion-section {
                padding: 15px;
            }
            
            .promotion-section .row {
                gap: 10px !important;
            }
            
            h2 {
                font-size: 1.2rem !important;
            }
            
            .d-flex.gap-2 {
                gap: 10px !important;
            }
            
            .d-flex.gap-2 .btn {
                flex: 1;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 65px 8px 10px;
            }
            
            .content-card .card-body {
                padding: 12px !important;
            }
            
            .form-control, .form-select {
                padding: 8px 10px;
                font-size: 12px;
            }
            
            .image-upload-area {
                padding: 15px;
            }
            
            .image-upload-area p {
                font-size: 13px;
            }
            
            .image-upload-area small {
                font-size: 11px;
            }
            
            .current-image-preview img {
                max-width: 120px;
            }
            
            .current-image-preview .remove-image {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand d-flex align-items-center gap-3">
                <div class="brand-icon text-white">
                    <i class="bi bi-motherboard"></i>
                </div>
                <span class="brand-text text-white">TECHSTORE</span>
            </div>
            
            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>/admin" class="nav-item-custom">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/products" class="nav-item-custom active">
                    <i class="fas fa-box"></i>
                    <span>Produits</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/orders" class="nav-item-custom">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commandes</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/users" class="nav-item-custom">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/categories" class="nav-item-custom">
                    <i class="fas fa-tags"></i>
                    <span>Catégories</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/stock" class="nav-item-custom">
                    <i class="fas fa-warehouse"></i>
                    <span>Stock</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item-custom">
                    <i class="fas fa-percent"></i>
                    <span>Promotions</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item-custom">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/profile" class="nav-item-custom">
                    <i class="fas fa-user-cog"></i>
                    <span>Profil</span>
                </a>
            </nav>
            
            <div class="mt-auto p-3">
                <a href="<?= BASE_URL ?>/home" class="logout-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour au site</span>
                </a>
                <a href="<?= BASE_URL ?>/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-grow-1">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-<?= isset($product) && $product ? 'pencil-square' : 'plus-circle' ?> me-2"></i>
                        <?= isset($product) && $product ? 'Modifier' : 'Ajouter'; ?> un produit
                    </h2>
                    <p class="text-muted mb-0"><?= isset($product) && $product ? 'Modifiez les informations du produit' : 'Créez un nouveau produit dans votre catalogue'; ?></p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Left Column - Product Info -->
                    <div class="col-lg-8">
                        <div class="content-card">
                            <div class="card-body p-4">
                                <div class="section-title">Informations du produit</div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= htmlspecialchars($product['name'] ?? ''); ?>" 
                                           placeholder="Ex: MacBook Pro 14 pouces M3" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Description courte</label>
                                    <input type="text" class="form-control" id="short_description" name="short_description" 
                                           value="<?= htmlspecialchars($product['short_description'] ?? ''); ?>"
                                           placeholder="Ex: MacBook Pro 14\" M3 Pro - 18GB/512GB">
                                    <small class="text-muted">Cette description apparaîtra dans les listes de produits</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description complète</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                              placeholder="Décrivez votre produit en détail..."><?= htmlspecialchars($product['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="content-card">
                            <div class="card-body p-4">
                                <div class="section-title">Prix et Stock</div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Prix normal (FC) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   step="0.01" min="0" 
                                                   value="<?= $product['price'] ?? ''; ?>" 
                                                   placeholder="0.00" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                                            <input type="number" class="form-control" id="stock" name="stock" 
                                                   min="0" value="<?= $product['stock'] ?? 0; ?>" 
                                                   placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Catégorie</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Sélectionner une catégorie</option>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id']; ?>" 
                                                    <?= (isset($product['category_id']) && $product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($category['name']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <!-- Section Promotion -->
                                <div class="promotion-section">
                                    <div class="section-title">Promotion</div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_promotion" name="is_promotion" 
                                               value="1" <?= (isset($product['is_promotion']) && $product['is_promotion']) ? 'checked' : ''; ?>
                                               onchange="togglePromotionFields()">
                                        <label class="form-check-label fw-bold" for="is_promotion">
                                            <i class="fas fa-percentage text-danger me-1"></i> Produit en promotion
                                        </label>
                                    </div>
                                    
                                    <div id="promotionFields" style="<?= (isset($product['is_promotion']) && $product['is_promotion']) ? '' : 'display: none;' ?>">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="promotion_price" class="form-label">Prix promotionnel (FC)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tag text-danger"></i></span>
                                                    <input type="number" class="form-control" id="promotion_price" name="promotion_price" 
                                                           step="0.01" min="0" 
                                                           value="<?= $product['promotion_price'] ?? ''; ?>" 
                                                           placeholder="0.00">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="discount" class="form-label">Remise (%)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-percent text-danger"></i></span>
                                                    <input type="number" class="form-control" id="discount" name="discount" 
                                                           min="0" max="100" 
                                                           value="<?= $product['discount'] ?? 0; ?>" 
                                                           placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="promotion_start_date" class="form-label">Date de début</label>
                                                <input type="date" class="form-control" id="promotion_start_date" name="promotion_start_date" 
                                                       value="<?= $product['promotion_start_date'] ?? ''; ?>">
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="promotion_end_date" class="form-label">Date de fin</label>
                                                <input type="date" class="form-control" id="promotion_end_date" name="promotion_end_date" 
                                                       value="<?= $product['promotion_end_date'] ?? ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Image & Status -->
                    <div class="col-lg-4">
                        <div class="content-card">
                            <div class="card-body p-4">
                                <div class="section-title">Image du produit</div>
                                
                                <div class="image-upload-area" onclick="document.getElementById('image').click()">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <p class="mb-1 fw-semibold">Cliquez pour uploader</p>
                                    <small class="text-muted">JPG, PNG, GIF, WEBP (Max 2MB)</small>
                                </div>
                                <input type="file" id="image" name="image" accept="image/*" style="display: none;" 
                                       onchange="this.previousElementSibling.innerHTML = '<i class=\'bi bi-check-circle\'></i><p class=\'mb-1 fw-semibold\'>Image sélectionnée</p><small class=\'text-muted\'>' + this.files[0].name + '</small>'">
                                
                                <?php if (!empty($product['image'])): ?>
                                <div class="current-image-preview">
                                    <img src="<?= UPLOAD_URL; ?>/<?= htmlspecialchars($product['image']); ?>" 
                                         alt="<?= htmlspecialchars($product['name']); ?>">
                                    <button type="button" class="remove-image" 
                                            onclick="document.getElementById('delete_image').checked = true; 
                                                     this.parentElement.style.display = 'none';">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <input type="checkbox" id="delete_image" name="delete_image" value="1" style="display: none;">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="content-card">
                            <div class="card-body p-4">
                                <div class="section-title">Statut</div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                           value="1" <?= (isset($product['is_featured']) && $product['is_featured']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-1"></i> Produit en vedette
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" <?= (isset($product['is_active']) && $product['is_active']) || !isset($product['is_active']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle text-success me-1"></i> Produit actif
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_production" name="is_production" 
                                           value="1" <?= (isset($product['is_production']) && $product['is_production']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_production">
                                        <i class="fas fa-industry text-primary me-1"></i> Produit en production
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer
                            </button>
                            <a href="<?= BASE_URL; ?>/admin/products" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePromotionFields() {
            const checkbox = document.getElementById('is_promotion');
            const fields = document.getElementById('promotionFields');
            if (checkbox.checked) {
                fields.style.display = 'block';
            } else {
                fields.style.display = 'none';
            }
        }
        
        // Calculate discount automatically when prices change
        document.getElementById('price')?.addEventListener('input', calculateDiscount);
        document.getElementById('promotion_price')?.addEventListener('input', calculateDiscount);
        
        function calculateDiscount() {
            const price = parseFloat(document.getElementById('price')?.value) || 0;
            const promoPrice = parseFloat(document.getElementById('promotion_price')?.value) || 0;
            
            if (price > 0 && promoPrice > 0 && promoPrice < price) {
                const discount = Math.round((1 - promoPrice / price) * 100);
                document.getElementById('discount').value = discount;
            }
        }
        
        // Calculate promotion price when discount changes
        document.getElementById('discount')?.addEventListener('input', function() {
            const price = parseFloat(document.getElementById('price')?.value) || 0;
            const discount = parseInt(this.value) || 0;
            
            if (price > 0 && discount > 0 && discount <= 100) {
                const promoPrice = price - (price * discount / 100);
                document.getElementById('promotion_price').value = promoPrice.toFixed(2);
            }
        });
        
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            if (window.innerWidth <= 991) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                    document.querySelector('.sidebar-overlay').classList.remove('show');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                document.getElementById('sidebar').classList.remove('show');
                document.querySelector('.sidebar-overlay').classList.remove('show');
            }
        });
    </script>
</body>
</html>
