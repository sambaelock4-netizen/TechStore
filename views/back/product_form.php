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
    <h2 class="mb-0"><i class="fas fa-box me-2"></i><?= isset($product) ? 'Modifier' : 'Ajouter' ?> un Produit</h2>
    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<!-- Product Form -->
<div class="table-card card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du produit *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Description courte</label>
                        <input type="text" class="form-control" id="short_description" name="short_description" value="<?= htmlspecialchars($product['short_description'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description complète</label>
                        <textarea class="form-control" id="description" name="description" rows="6"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Prix (€) *</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?= $product['price'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stock *</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" value="<?= $product['stock'] ?? 0 ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Catégorie</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="url" class="form-control" id="image" name="image" value="<?= htmlspecialchars($product['image'] ?? '') ?>" placeholder="https://...">
                        <?php if (isset($product['image']) && $product['image']): ?>
                        <div class="mt-2">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" <?= (isset($product['is_featured']) && $product['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_featured">Produit en vedette</label>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" <?= (!isset($product['is_active']) || $product['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Produit actif</label>
                    </div>
                </div>
            </div>
            
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
