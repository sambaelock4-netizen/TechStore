<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Commande mise à jour avec succès!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Commande #<?= $order['id'] ?></h2>
    <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row">
    <!-- Order Info -->
    <div class="col-md-4">
        <div class="table-card card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations Commande</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Statut</label>
                    <form method="POST" action="<?= BASE_URL ?>/admin/orders/update/<?= $order['id'] ?>" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="en_attente" <?= ($order['status'] === 'en_attente') ? 'selected' : '' ?>>En attente</option>
                            <option value="confirme" <?= ($order['status'] === 'confirme') ? 'selected' : '' ?>>Confirmé</option>
                            <option value="en_preparation" <?= ($order['status'] === 'en_preparation') ? 'selected' : '' ?>>En préparation</option>
                            <option value="expedie" <?= ($order['status'] === 'expedie') ? 'selected' : '' ?>>Expédié</option>
                            <option value="livre" <?= ($order['status'] === 'livre') ? 'selected' : '' ?>>Livré</option>
                            <option value="annule" <?= ($order['status'] === 'annule') ? 'selected' : '' ?>>Annulé</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Date de création</label>
                    <p class="mb-0"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Dernière mise à jour</label>
                    <p class="mb-0"><?= date('d/m/Y H:i', strtotime($order['updated_at'])) ?></p>
                </div>
                <div>
                    <label class="text-muted small">Montant total</label>
                    <h4 class="mb-0 text-success"><?= number_format($order['total_amount'], 2, ',', ' ') ?> €</h4>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Info -->
    <div class="col-md-4">
        <div class="table-card card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations Client</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Nom</label>
                    <p class="mb-0"><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <p class="mb-0"><?= htmlspecialchars($order['email']) ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Téléphone</label>
                    <p class="mb-0"><?= htmlspecialchars($order['phone'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="text-muted small">Adresse</label>
                    <p class="mb-0">
                        <?= htmlspecialchars($order['address'] ?? '-') ?><br>
                        <?= htmlspecialchars($order['postal_code'] ?? '') ?> <?= htmlspecialchars($order['city'] ?? '') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Shipping Info -->
    <div class="col-md-4">
        <div class="table-card card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Livraison</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Adresse de livraison</label>
                    <p class="mb-0"><?= htmlspecialchars($order['shipping_address'] ?? '-') ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Ville</label>
                    <p class="mb-0"><?= htmlspecialchars($order['shipping_city'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="text-muted small">Code postal</label>
                    <p class="mb-0"><?= htmlspecialchars($order['shipping_postal_code'] ?? '-') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="table-card card">
    <div class="card-header">
        <h5 class="mb-0">Articles commandés</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="text-center">Quantité</th>
                        <th class="text-end">Prix unitaire</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if ($item['image']): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;" class="me-3 rounded">
                                <?php endif; ?>
                                <div>
                                    <strong><?= htmlspecialchars($item['name']) ?></strong>
                                </div>
                            </div>
                        </td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end"><?= number_format($item['unit_price'], 2, ',', ' ') ?> €</td>
                        <td class="text-end"><strong><?= number_format($item['unit_price'] * $item['quantity'], 2, ',', ' ') ?> €</strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td class="text-end"><strong><?= number_format($order['total_amount'], 2, ',', ' ') ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
