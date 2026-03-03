<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
$statusFilter = $_GET['status'] ?? '';
?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php
    switch($success) {
        case 'updated': echo 'Commande mise à jour avec succès!'; break;
        default: echo 'Opération réussie!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $error === 'not_found' ? 'Commande non trouvée!' : 'Une erreur est survenue!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Gestion des Commandes</h2>
</div>

<!-- Status Filter -->
<div class="mb-3">
    <div class="btn-group">
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm <?= !$statusFilter ? 'btn-primary' : 'btn-outline-primary' ?>">Tous</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=en_attente" class="btn btn-sm <?= $statusFilter === 'en_attente' ? 'btn-primary' : 'btn-outline-primary' ?>">En attente</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=confirme" class="btn btn-sm <?= $statusFilter === 'confirme' ? 'btn-primary' : 'btn-outline-primary' ?>">Confirmé</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=en_preparation" class="btn btn-sm <?= $statusFilter === 'en_preparation' ? 'btn-primary' : 'btn-outline-primary' ?>">En préparation</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=expedie" class="btn btn-sm <?= $statusFilter === 'expedie' ? 'btn-primary' : 'btn-outline-primary' ?>">Expédié</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=livre" class="btn btn-sm <?= $statusFilter === 'livre' ? 'btn-primary' : 'btn-outline-primary' ?>">Livré</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=annule" class="btn btn-sm <?= $statusFilter === 'annule' ? 'btn-primary' : 'btn-outline-primary' ?>">Annulé</a>
    </div>
</div>

<!-- Orders Table -->
<div class="table-card card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Ville</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
                            Aucune commande trouvée
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                    <?php if ($statusFilter && $order['status'] !== $statusFilter) continue; ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></strong>
                            <div class="small text-muted"><?= htmlspecialchars($order['email']) ?></div>
                        </td>
                        <td><strong><?= number_format($order['total_amount'], 2, ',', ' ') ?> €</strong></td>
                        <td>
                            <span class="badge badge-status badge-<?= $order['status'] ?>">
                                <?= str_replace('_', ' ', ucfirst($order['status'])) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($order['shipping_city'] ?? '-') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
