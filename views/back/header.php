<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - TechStore' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --dark-color: #212529;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
        }
        
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand span {
            color: var(--warning-color);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0.5rem;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.5rem;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem;
        }
        
        /* Cards */
        .stat-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        /* Tables */
        .table-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--light-bg);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: var(--secondary-color);
        }
        
        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        /* Buttons */
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }
        
        /* Badges */
        .badge-status {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .badge-en_attente { background-color: #ffc107; color: #000; }
        .badge-confirme { background-color: #0dcaf0; color: #000; }
        .badge-en_preparation { background-color: #6c757d; color: #fff; }
        .badge-expedie { background-color: #0d6efd; color: #fff; }
        .badge-livre { background-color: #198754; color: #fff; }
        .badge-annule { background-color: #dc3545; color: #fff; }
        
        /* User Info */
        .user-info {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }
        
        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: var(--warning-color);
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse show">
                <div class="position-sticky pt-3">
                    <div class="sidebar-brand">
                        <i class="fas fa-store me-2"></i>
                        Tech<span>Store</span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin') !== false && strpos($_SERVER['REQUEST_URI'], '/admin/') === false ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <?php if (isset($currentUser) && ($currentUser['role'] === 'super_admin' || ($currentUser['permissions'] && json_decode($currentUser['permissions'], true)['products']))): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/products') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/products">
                                <i class="fas fa-box"></i>
                                Produits
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isset($currentUser) && ($currentUser['role'] === 'super_admin' || ($currentUser['permissions'] && json_decode($currentUser['permissions'], true)['orders']))): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/orders">
                                <i class="fas fa-shopping-cart"></i>
                                Commandes
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isset($currentUser) && ($currentUser['role'] === 'super_admin' || ($currentUser['permissions'] && json_decode($currentUser['permissions'], true)['users']))): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/users">
                                <i class="fas fa-users"></i>
                                Utilisateurs
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isset($currentUser) && ($currentUser['role'] === 'super_admin' || ($currentUser['permissions'] && json_decode($currentUser['permissions'], true)['categories']))): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/categories">
                                <i class="fas fa-tags"></i>
                                Catégories
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isset($currentUser) && $currentUser['role'] === 'super_admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/logs') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/logs">
                                <i class="fas fa-history"></i>
                                Journaux
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/profile') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/profile">
                                <i class="fas fa-user-cog"></i>
                                Mon Profil
                            </a>
                        </li>
                    </ul>
                    
                    <!-- User Info -->
                    <?php if (isset($currentUser)): ?>
                    <div class="user-info">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-2">
                                <?= strtoupper(substr($currentUser['firstname'], 0, 1) . substr($currentUser['lastname'], 0, 1)) ?>
                            </div>
                            <div class="text-white">
                                <div class="small fw-bold"><?= htmlspecialchars($currentUser['firstname'] . ' ' . $currentUser['lastname']) ?></div>
                                <div class="small text-white-50"><?= ucfirst($currentUser['role']) ?></div>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>/logout" class="btn btn-outline-light btn-sm w-100 mt-2">
                            <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
