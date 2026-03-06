<?php
/**
 * TECHSTORE - Admin Dashboard Responsive
 */

$adminName = isset($_SESSION['user']['firstname']) ? $_SESSION['user']['firstname'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TechStore Admin</title>
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
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
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
        
        .content-card .card-body {
            padding: 0;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #f8f9fa;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--secondary);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            transform: scale(1.1);
        }
        
        .quick-action-btn {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            text-decoration: none;
            color: var(--dark);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            color: var(--primary);
        }
        
        .quick-action-btn i {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .quick-action-btn span {
            display: block;
            font-weight: 500;
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
            .stat-number {
                font-size: 28px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
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
        }
        
        @media (max-width: 767px) {
            .main-content {
                padding: 70px 10px 15px;
            }
            
            .stat-card {
                padding: 15px;
                border-radius: 12px;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            
            .stat-number {
                font-size: 24px;
            }
            
            .stat-card p {
                font-size: 13px;
                margin-bottom: 5px !important;
            }
            
            .content-card {
                border-radius: 12px;
                margin-bottom: 15px;
            }
            
            .content-card .card-header {
                padding: 15px;
                flex-direction: column;
                gap: 10px;
                align-items: stretch !important;
            }
            
            .table {
                font-size: 12px;
            }
            
            .table thead th,
            .table tbody td {
                padding: 10px 8px;
            }
            
            .quick-action-btn {
                padding: 15px;
                border-radius: 12px;
            }
            
            .quick-action-btn i {
                font-size: 24px;
                margin-bottom: 8px;
            }
            
            .quick-action-btn span {
                font-size: 12px;
            }
            
            .btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            .btn-sm {
                padding: 5px 10px;
                font-size: 12px;
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
            
            .d-flex.gap-2 {
                gap: 8px !important;
            }
            
            .btn-action {
                width: 30px;
                height: 30px;
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
                <a href="<?= BASE_URL ?>/admin" class="nav-item-custom active">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/products" class="nav-item-custom">
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
                    <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
                    <p class="text-muted mb-0">Bienvenue, <?= htmlspecialchars($adminName) ?>!</p>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="text-muted d-none d-md-block">
                        <i class="bi bi-calendar3 me-2"></i>
                        <?= date('d/m/Y') ?>
                    </span>
                    <a href="<?= BASE_URL ?>/home" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-up-right me-2"></i>
                        <span class="d-none d-lg-inline">Voir le site</span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 g-md-4 mb-4">
                <div class="col-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Produits</p>
                                <div class="stat-number"><?= $stats['total_products'] ?? 0 ?></div>
                            </div>
                            <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: var(--primary);">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Commandes</p>
                                <div class="stat-number"><?= $stats['total_orders'] ?? 0 ?></div>
                            </div>
                            <div class="stat-icon" style="background: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                                <i class="bi bi-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Clients</p>
                                <div class="stat-number"><?= $stats['total_users'] ?? 0 ?></div>
                            </div>
                            <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: var(--success);">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Revenus</p>
                                <div class="stat-number"><?= displayPrice($stats['total_revenue'] ?? 0) ?></div>
                            </div>
                            <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #d69b00;">
                                <i class="bi bi-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="content-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2 text-muted"></i>
                        Commandes récentes
                    </h5>
                    <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm btn-outline-primary">
                        Voir tout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="d-none d-md-table-cell">Client</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th class="d-none d-lg-table-cell">Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $order['id'] ?></td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="fw-semibold"><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></div>
                                        </td>
                                        <td class="fw-bold text-success"><?= displayPrice($order['total_amount']) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = 'bg-warning text-dark';
                                            $statusText = $order['status'] ?? 'en_attente';
                                            if ($statusText == 'livre') { $statusClass = 'bg-success'; }
                                            elseif ($statusText == 'annule') { $statusClass = 'bg-danger'; }
                                            elseif ($statusText == 'confirme') { $statusClass = 'bg-info'; }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td class="d-none d-lg-table-cell"><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary btn-action" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-bag text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">Aucune commande pour le moment</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-charge me-2 text-muted"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-6 col-lg-3">
                            <a href="<?= BASE_URL ?>/admin/product/add" class="quick-action-btn">
                                <i class="bi bi-plus-circle text-primary"></i>
                                <span>Nouveau produit</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-6 col-lg-3">
                            <a href="<?= BASE_URL ?>/admin/users/add" class="quick-action-btn">
                                <i class="bi bi-person-plus text-success"></i>
                                <span>Nouveau client</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-6 col-lg-3">
                            <a href="<?= BASE_URL ?>/admin/categories/add" class="quick-action-btn">
                                <i class="bi bi-tag text-warning"></i>
                                <span>Nouvelle catégorie</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-6 col-lg-3">
                            <a href="<?= BASE_URL ?>/admin/statistics" class="quick-action-btn">
                                <i class="bi bi-graph-up-arrow text-info"></i>
                                <span>Statistiques</span>
                            </a>
                        </div>
                    </div>
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
