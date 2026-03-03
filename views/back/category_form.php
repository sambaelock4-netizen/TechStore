<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $error ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-tag me-2"></i><?= isset($category) ? 'Modifier' : 'Ajouter' ?> une Catégorie</h2>
    <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<!-- Category Form -->
<div class="table-card card">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nom de la catégorie *</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category['name'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="slug" class="form-label">Slug (URL)</label>
                <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($category['slug'] ?? '') ?>" placeholder="categorie-slug">
                <small class="text-muted">Laissez vide pour générer automatiquement</small>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?= (!isset($category['is_active']) || $category['is_active']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Catégorie active</label>
            </div>
            
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
