<?php
// Récupérer les paramètres de l'URL
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!-- Messages d'alerte -->
<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php
    switch($success) {
        case 'added': echo 'Élément ajouté avec succès!'; break;
        case 'updated': echo 'Élément mis à jour avec succès!'; break;
        case 'deleted': echo 'Élément supprimé avec succès!'; break;
        case 'password_reset': echo 'Mot de passe réinitialisé!'; break;
        default: echo 'Opération réussie!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php
    switch($error) {
        case 'not_found': echo 'Élément non trouvé!'; break;
        case 'cannot_delete_self': echo 'Vous ne pouvez pas supprimer votre propre compte!'; break;
        default: echo 'Une erreur est survenue!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Dashboard</h2>
    <span class="text-muted">Bienvenue, <?= htmlspecialchars($currentUser['firstname']) ?>!</span>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Produits</h6>
                        <h2 class="mb-0"><?= number_format($stats['products']) ?></h2>
                    </div>
                    <div class="icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/admin/products" class="small text-primary mt-2 d-inline-block">
                    Voir tous les produits <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="stat-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Commandes</h6>
                        <h2 class="mb-0"><?= number_format($stats['orders']) ?></h2>
                    </div>
                    <div class="icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/admin/orders" class="small text-success mt-2 d-inline-block">
                    Voir toutes les commandes <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="stat-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Clients</h6>
                        <h2 class="mb-0"><?= number_format($stats['clients']) ?></h2>
                    </div>
                    <div class="icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/admin/users" class="small text-info mt-2 d-inline-block">
                    Voir tous les clients <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="stat-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Commandes en attente</h6>
                        <h2 class="mb-0"><?= number_format($stats['pending_orders']) ?></h2>
                    </div>
                    <div class="icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/admin/orders?status=en_attente" class="small text-warning mt-2 d-inline-block">
                    Traiter les commandes <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Revenue and Recent Orders -->
<div class="row g-4">
    <!-- Revenue Card -->
    <div class="col-md-4">
        <div class="stat-card card h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Chiffre d'affaires Total</h6>
                <h1 class="text-success mb-3"><?= number_format($stats['revenue'], 0, ',', ' ') ?> €</h1>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Commandes livrées et payées
                </p>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="col-md-8">
        <div class="table-card card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dernières Commandes</h5>
                    <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm btn-primary">
                        Voir tout <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stats['recent_orders'])): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>
                                    Aucune commande
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($stats['recent_orders'] as $order): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td>
                                    <?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?>
                                    <div class="small text-muted"><?= htmlspecialchars($order['email']) ?></div>
                                </td>
                                <td><strong><?= number_format($order['total_amount'], 2, ',', ' ') ?> €</strong></td>
                                <td>
                                    <span class="badge badge-status badge-<?= $order['status'] ?>">
                                        <?= str_replace('_', ' ', ucfirst($order['status'])) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <h5 class="mb-3">Actions Rapides</h5>
    </div>
    
    <?php if ($currentUser['role'] === 'super_admin' || ($currentUser['permissions'] && json_decode($currentUser['permissions'], true)['products'])): ?>
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/product/add" class="text-decoration-none">
            <div class="stat-card card h-100 text-center">
                <div class="card-body">
                    <div class="icon bg-primary bg-opacity-10 text-primary mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-plus fa-lg"></i>
                    </div>
                    <h6>Ajouter un Produit</h6>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
    
    <?php if ($currentUser['role'] === 'super_admin' || ($currentUser['permissions'] && json_decode($currentUser['permissions'], true)['orders'])): ?>
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/orders?status=en_attente" class="text-decoration-none">
            <div class="stat-card card h-100 text-center">
                <div class="card-body">
                    <div class="icon bg-warning bg-opacity-10 text-warning mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <h6>Commandes en Attente</h6>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
    
    <?php if ($currentUser['role'] === 'super_admin' || ($currentUser['permissions'] && json_decode($currentUser['permissions'], true)['users'])): ?>
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/users/add" class="text-decoration-none">
            <div class="stat-card card h-100 text-center">
                <div class="card-body">
                    <div class="icon bg-success bg-opacity-10 text-success mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-plus fa-lg"></i>
                    </div>
                    <h6>Ajouter un Utilisateur</h6>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
    
    <?php if ($currentUser['role'] === 'super_admin'): ?>
    <div class="col-md-3">
        <a href="<?= BASE_URL ?>/admin/categories/add" class="text-decoration-none">
            <div class="stat-card card h-100 text-center">
                <div class="card-body">
                    <div class="icon bg-info bg-opacity-10 text-info mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-tag fa-lg"></i>
                    </div>
                    <h6>Ajouter une Catégorie</h6>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
</div>
