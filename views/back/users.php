<?php
/**
 * TECHSTORE - Admin Users List Responsive
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin-responsive.css">
    <style>
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
                <a href="<?= BASE_URL ?>/admin/products" class="nav-item-custom">
                    <i class="fas fa-box"></i>
                    <span>Produits</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/orders" class="nav-item-custom">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commandes</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/users" class="nav-item-custom active">
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
                    <h2 class="fw-bold text-dark mb-1">Gestion des Utilisateurs</h2>
                    <p class="text-muted mb-0">Gérez les clients et administrateurs</p>
                </div>
                <a href="<?= BASE_URL ?>/admin/users/add" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span class="hide-mobile">Ajouter un utilisateur</span>
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL ?>/admin/users" class="row g-2 g-md-3">
                        <div class="col-12 col-md-6">
                            <input type="text" name="search" placeholder="Rechercher..." 
                                   value="<?= htmlspecialchars($search ?? '') ?>" class="form-control">
                        </div>
                        <div class="col-6 col-md-3">
                            <select name="role" class="form-select">
                                <option value="">Tous les rôles</option>
                                <option value="client" <?= ($selectedRole ?? '') === 'client' ? 'selected' : '' ?>>Client</option>
                                <option value="admin" <?= ($selectedRole ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i> <span class="hide-mobile">Filtrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="content-card">
                <div class="card-body p-0">
                    <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th class="d-none d-lg-table-cell">Email</th>
                                        <th>Rôle</th>
                                        <th class="d-none d-md-table-cell">Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                                        <td class="d-none d-lg-table-cell"><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <span class="role-badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                                                    <i class="bi bi-shield-check me-1"></i> <span class="hide-mobile">Admin</span>
                                                </span>
                                            <?php else: ?>
                                                <span class="role-badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                                    <i class="bi bi-person me-1"></i> Client
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <?php if (($user['is_active'] ?? 1) == 1): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary btn-action" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if ($user['role'] !== 'admin'): ?>
                                                <a href="<?= BASE_URL ?>/admin/users/reset/<?= $user['id'] ?>" 
                                                   class="btn btn-sm btn-outline-warning btn-action d-none d-sm-inline-flex" title="Réinitialiser"
                                                   onclick="return confirm('Réinitialiser le mot de passe ?')">
                                                    <i class="bi bi-key"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/admin/users/delete/<?= $user['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger btn-action d-none d-sm-inline-flex" title="Supprimer"
                                                   onclick="return confirm('Êtes-vous sûr ?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">Aucun utilisateur trouvé</p>
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
