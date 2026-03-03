<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php
    switch($success) {
        case 'added': echo 'Produit ajouté avec succès!'; break;
        case 'updated': echo 'Produit mis à jour avec succès!'; break;
        case 'deleted': echo 'Produit supprimé avec succès!'; break;
        default: echo 'Opération réussie!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $error === 'not_found' ? 'Produit non trouvé!' : 'Une erreur est survenue!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-box me-2"></i>Gestion des Produits</h2>
    <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Ajouter un Produit
    </a>
</div>

<!-- Products Table -->
<div class="table-card card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                            Aucun produit trouvé
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>#<?= $product['id'] ?></td>
                        <td>
                            <?php if ($product['image']): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                            <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($product['name']) ?></strong>
                            <?php if ($product['is_featured']): ?>
                            <span class="badge bg-warning text-dark ms-1">Featured</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['category_name'] ?? 'Non catégorisé') ?></td>
                        <td><strong><?= number_format($product['price'], 2, ',', ' ') ?> €</strong></td>
                        <td>
                            <?php if ($product['stock'] > 10): ?>
                            <span class="text-success"><i class="fas fa-check-circle me-1"></i><?= $product['stock'] ?></span>
                            <?php elseif ($product['stock'] > 0): ?>
                            <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i><?= $product['stock'] ?></span>
                            <?php else: ?>
                            <span class="text-danger"><i class="fas fa-times-circle me-1"></i>Rupture</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($product['is_active']): ?>
                            <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>/admin/product/edit/<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/product/delete/<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirmDelete('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                    <i class="fas fa-trash"></i>
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
