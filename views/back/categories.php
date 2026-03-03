<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php
    switch($success) {
        case 'added': echo 'Catégorie ajoutée avec succès!'; break;
        case 'updated': echo 'Catégorie mise à jour avec succès!'; break;
        case 'deleted': echo 'Catégorie supprimée avec succès!'; break;
        default: echo 'Opération réussie!';
    }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $error === 'not_found' ? 'Catégorie non trouvée!' : 'Une erreur est survenue!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-tags me-2"></i>Gestion des Catégories</h2>
    <a href="<?= BASE_URL ?>/admin/categories/add" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Ajouter une Catégorie
    </a>
</div>

<!-- Categories Table -->
<div class="table-card card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-tags fa-3x mb-3 d-block"></i>
                            Aucune catégorie trouvée
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td>#<?= $category['id'] ?></td>
                        <td><strong><?= htmlspecialchars($category['name']) ?></strong></td>
                        <td><code><?= htmlspecialchars($category['slug']) ?></code></td>
                        <td><?= htmlspecialchars(mb_substr($category['description'] ?? '', 0, 50)) ?>...</td>
                        <td>
                            <?php if ($category['is_active']): ?>
                            <span class="badge bg-success">Active</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>/admin/categories/edit/<?= $category['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/categories/delete/<?= $category['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirmDelete('Êtes-vous sûr de vouloir supprimer cette catégorie?');">
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
