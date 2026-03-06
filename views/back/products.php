<?php
/**
 * TECHSTORE - Admin Products List avec Bootstrap - Responsive
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - TechStore Admin</title>
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
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
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
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: margin-left 0.3s ease;
        }
        
        .content-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            border: none;
        }
        
        .content-card .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 25px;
        }
        
        /* Table Styles */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #f8f9fa;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px;
            font-weight: 600;
            color: var(--secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .no-image {
            width: 50px;
            height: 50px;
            background: #f1f3f5;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
        }
        
        .stock-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .stock-badge.normal {
            background: rgba(25, 135, 84, 0.1);
            color: var(--success);
        }
        
        .stock-badge.low {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }
        
        .badge-production {
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary);
            font-size: 11px;
            padding: 3px 8px;
        }
        
        .badge-promotion {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
            font-size: 11px;
            padding: 3px 8px;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            padding: 0;
        }
        
        .btn-action:hover {
            transform: scale(1.1);
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
        @media (max-width: 1199px) {
            .table-responsive {
                font-size: 13px;
            }
            
            .table thead th, 
            .table tbody td {
                padding: 10px 12px;
            }
        }
        
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
            
            /* Hide some columns on tablet */
            .hide-tablet {
                display: none;
            }
        }
        
        @media (max-width: 767px) {
            .main-content {
                padding: 70px 10px 15px;
            }
            
            .content-card {
                border-radius: 12px;
            }
            
            .content-card .card-header {
                padding: 15px;
                flex-direction: column;
                gap: 10px;
                align-items: stretch !important;
            }
            
            .content-card .card-header h2,
            .content-card .card-header h5 {
                font-size: 1.1rem;
            }
            
            .btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            .btn-sm {
                padding: 5px 10px;
                font-size: 12px;
            }
            
            /* Hide more columns on mobile */
            .hide-mobile {
                display: none;
            }
            
            .table {
                font-size: 12px;
            }
            
            .table thead th,
            .table tbody td {
                padding: 8px 6px;
            }
            
            .product-thumb {
                width: 40px;
                height: 40px;
            }
            
            .no-image {
                width: 40px;
                height: 40px;
            }
            
            .stock-badge {
                font-size: 11px;
                padding: 2px 6px;
            }
            
            .badge-production,
            .badge-promotion {
                font-size: 10px;
                padding: 2px 5px;
            }
            
            /* Filter form */
            .card-body .row {
                gap: 10px !important;
            }
            
            .form-control, 
            .form-select {
                font-size: 13px;
                padding: 8px 12px;
            }
            
            .btn-action {
                width: 28px;
                height: 28px;
            }
            
            .btn-action i {
                font-size: 12px;
            }
            
            .d-flex.gap-2 {
                gap: 5px !important;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 65px 8px 10px;
            }
            
            h2 {
                font-size: 1.2rem !important;
            }
            
            p {
                font-size: 12px;
            }
            
            .table-responsive {
                margin: 0 -15px;
            }
            
            /* Card view for very small screens */
            .products-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .product-card-mobile {
                background: white;
                border-radius: 12px;
                padding: 15px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }
            
            .product-card-mobile .product-header {
                display: flex;
                gap: 12px;
                margin-bottom: 10px;
            }
            
            .product-card-mobile .product-info {
                flex: 1;
            }
            
            .product-card-mobile .product-actions {
                display: flex;
                gap: 8px;
                margin-top: 10px;
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
                    <h2 class="fw-bold text-dark mb-1">Gestion des Produits</h2>
                    <p class="text-muted mb-0">Gérez votre catalogue de produits</p>
                </div>
                <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span class="hide-mobile">Ajouter un produit</span>
                    <span class="hide-tablet hide-desktop">Ajouter</span>
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL ?>/admin/products" class="row g-2 g-md-3">
                        <div class="col-12 col-md-4">
                            <input type="text" name="search" placeholder="Rechercher..." 
                                   value="<?= htmlspecialchars($search ?? '') ?>" class="form-control">
                        </div>
                        <div class="col-6 col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Catégorie</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Statut</option>
                                <option value="1" <?= ($selectedStatus ?? '') === '1' ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= ($selectedStatus ?? '') === '0' ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i> <span class="hide-mobile">Filtrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="content-card">
                <div class="card-body p-0">
                    <?php if (!empty($products)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="hide-mobile">ID</th>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th class="hide-tablet">Catégorie</th>
                                        <th>Prix</th>
                                        <th class="hide-mobile">Stock</th>
                                        <th class="hide-mobile">Production</th>
                                        <th class="hide-mobile">Promo</th>
                                        <th class="hide-tablet hide-mobile">Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td class="hide-mobile"><?= $product['id'] ?></td>
                                        <td>
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?= UPLOAD_URL ?>/<?= htmlspecialchars($product['image']) ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumb">
                                            <?php else: ?>
                                                <div class="no-image"><i class="bi bi-image"></i></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($product['name']) ?></div>
                                            <?php if (!empty($product['sku'])): ?>
                                                <small class="text-muted"><?= htmlspecialchars($product['sku']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-tablet"><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                                        <td>
                                            <?php if (($product['is_promotion'] ?? 0) == 1 && !empty($product['promotion_price'])): ?>
                                                <div class="text-decoration-line-through text-muted small"><?= displayPrice($product['price'] ?? 0) ?></div>
                                                <div class="fw-bold text-danger"><?= displayPrice($product['promotion_price']) ?></div>
                                            <?php else: ?>
                                                <div class="fw-bold text-success"><?= displayPrice($product['price'] ?? 0) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-mobile">
                                            <?php $stock = $product['stock'] ?? 0; ?>
                                            <span class="stock-badge <?= $stock <= 5 ? 'low' : 'normal' ?>">
                                                <?= $stock ?>
                                            </span>
                                        </td>
                                        <td class="hide-mobile">
                                            <?php if (($product['is_production'] ?? 0) == 1): ?>
                                                <span class="badge badge-production"><i class="fas fa-industry"></i></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-mobile">
                                            <?php if (($product['is_promotion'] ?? 0) == 1): ?>
                                                <span class="badge badge-promotion">-<?= $product['discount'] ?? 0 ?>%</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="hide-tablet hide-mobile">
                                            <?php if (($product['is_active'] ?? 1) == 1): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= BASE_URL ?>/admin/product/edit/<?= $product['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary btn-action" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/admin/product/delete/<?= $product['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger btn-action" title="Supprimer"
                                                   onclick="return confirm('Êtes-vous sûr ?')">
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
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">Aucun produit trouvé</p>
                            <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Ajouter un produit
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
