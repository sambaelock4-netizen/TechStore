<?php
/**
 * TECHSTORE - Admin Orders List Responsive
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/admin-responsive.css">
    <style>
        /* Additional custom styles */
        .status-badge {
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
                <a href="<?= BASE_URL ?>/admin/orders" class="nav-item-custom active">
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
                    <h2 class="fw-bold text-dark mb-1">Gestion des Commandes</h2>
                    <p class="text-muted mb-0">Suivez et gérez toutes les commandes</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL ?>/admin/orders" class="row g-2 g-md-3">
                        <div class="col-12 col-md-3">
                            <input type="text" name="search" placeholder="Rechercher..." 
                                   value="<?= htmlspecialchars($search ?? '') ?>" class="form-control">
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Statut</option>
                                <option value="en_attente" <?= ($selectedStatus ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="confirme" <?= ($selectedStatus ?? '') === 'confirme' ? 'selected' : '' ?>>Confirmé</option>
                                <option value="en_preparation" <?= ($selectedStatus ?? '') === 'en_preparation' ? 'selected' : '' ?>>Préparation</option>
                                <option value="expedie" <?= ($selectedStatus ?? '') === 'expedie' ? 'selected' : '' ?>>Expédié</option>
                                <option value="livre" <?= ($selectedStatus ?? '') === 'livre' ? 'selected' : '' ?>>Livré</option>
                                <option value="annule" <?= ($selectedStatus ?? '') === 'annule' ? 'selected' : '' ?>>Annulé</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="date" name="date_from" value="<?= $dateFrom ?? '' ?>" class="form-control" placeholder="Du">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="date" name="date_to" value="<?= $dateTo ?? '' ?>" class="form-control" placeholder="Au">
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i> <span class="hide-mobile">Filtrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="content-card">
                <div class="card-body p-0">
                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th class="d-none d-md-table-cell">Client</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th class="d-none d-lg-table-cell">Paiement</th>
                                        <th class="d-none d-sm-table-cell">Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $order['order_number'] ?? $order['id'] ?></td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="fw-semibold"><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                        </td>
                                        <td class="fw-bold text-success"><?= displayPrice($order['total_amount']) ?></td>
                                        <td>
                                            <?php
                                            $statusConfig = [
                                                'en_attente' => ['class' => 'bg-warning text-dark', 'label' => 'En attente'],
                                                'confirme' => ['class' => 'bg-info', 'label' => 'Confirmé'],
                                                'en_preparation' => ['class' => 'bg-primary', 'label' => 'Préparation'],
                                                'expedie' => ['class' => 'bg-secondary', 'label' => 'Expédié'],
                                                'livre' => ['class' => 'bg-success', 'label' => 'Livré'],
                                                'annule' => ['class' => 'bg-danger', 'label' => 'Annulé']
                                            ];
                                            $status = $statusConfig[$order['status']] ?? ['class' => 'bg-warning text-dark', 'label' => $order['status']];
                                            ?>
                                            <span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <?php
                                            $paymentConfig = [
                                                'pending' => ['class' => 'bg-warning text-dark', 'label' => 'En attente'],
                                                'paid' => ['class' => 'bg-success', 'label' => 'Payé'],
                                                'failed' => ['class' => 'bg-danger', 'label' => 'Échoué'],
                                                'refunded' => ['class' => 'bg-info', 'label' => 'Remboursé']
                                            ];
                                            $payment = $paymentConfig[$order['payment_status']] ?? ['class' => 'bg-secondary', 'label' => $order['payment_status']];
                                            ?>
                                            <span class="badge <?= $payment['class'] ?>"><?= $payment['label'] ?></span>
                                        </td>
                                        <td class="d-none d-sm-table-cell"><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary btn-action" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/admin/orders/generateInvoice/<?= $order['id'] ?>" 
                                                   class="btn btn-sm btn-outline-warning btn-action d-none d-sm-inline-flex" title="Facture" target="_blank">
                                                    <i class="bi bi-receipt"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/admin/orders/generateDeliveryNote/<?= $order['id'] ?>" 
                                                   class="btn btn-sm btn-outline-success btn-action d-none d-sm-inline-flex" title="Bon de livraison" target="_blank">
                                                    <i class="bi bi-truck"></i>
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
                            <i class="bi bi-bag text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">Aucune commande trouvée</p>
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
