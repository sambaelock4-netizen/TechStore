<?php
/**
 * TECHSTORE - Admin Products ULTRA PREMIUM
 */

$pageTitle = 'Produits';
$currentPage = 'products';

ob_start();
?>

<!-- Filters -->
<div class="content-card mb-4">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-funnel"></i>
            Filtres
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>/admin/products" class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" name="search" placeholder="Rechercher un produit..." 
                       value="<?= htmlspecialchars($search ?? '') ?>" class="form-control">
            </div>
            <div class="col-6 col-md-3">
                <select name="category" class="form-select form-control">
                    <option value="">Toutes les catégories</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-control">
                    <option value="">Statut</option>
                    <option value="1" <?= ($selectedStatus ?? '') === '1' ? 'selected' : '' ?>>Actif</option>
                    <option value="0" <?= ($selectedStatus ?? '') === '0' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-box-seam"></i>
            Liste des Produits (<?= count($products ?? []) ?>)
        </h3>
        <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Ajouter
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($products)): ?>
        <div class="table-container">
            <table class="data-table">
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
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><span class="text-muted">#<?= $product['id'] ?></span></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= UPLOAD_URL ?>/<?= htmlspecialchars($product['image']) ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumb">
                            <?php else: ?>
                                <div class="product-thumb-placeholder"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($product['name']) ?></div>
                            <?php if (!empty($product['sku'])): ?>
                                <small class="text-muted"><?= htmlspecialchars($product['sku']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge badge-secondary"><?= htmlspecialchars($product['category_name'] ?? '-') ?></span></td>
                        <td>
                            <?php if (($product['is_promotion'] ?? 0) == 1 && !empty($product['promotion_price'])): ?>
                                <div class="text-decoration-line-through text-muted small"><?= displayPrice($product['price'] ?? 0) ?></div>
                                <div class="fw-bold text-danger"><?= displayPrice($product['promotion_price']) ?></div>
                            <?php else: ?>
                                <div class="fw-bold text-success"><?= displayPrice($product['price'] ?? 0) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $stock = $product['stock'] ?? 0; ?>
                            <span class="stock-indicator <?= $stock <= 5 ? 'low' : 'normal' ?>"><?= $stock ?></span>
                        </td>
                        <td>
                            <?php if (($product['is_active'] ?? 1) == 1): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= BASE_URL ?>/admin/product/edit/<?= $product['id'] ?>" class="btn btn-icon btn-secondary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/product/delete/<?= $product['id'] ?>" class="btn btn-icon btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <p>Aucun produit trouvé</p>
            <a href="<?= BASE_URL ?>/admin/product/add" class="btn btn-primary mt-3"><i class="bi bi-plus-lg"></i> Ajouter un produit</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.product-thumb { width: 48px; height: 48px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--glass-border); }
.product-thumb-placeholder { width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--glass-bg); border: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; color: var(--text-tertiary); }
.stock-indicator { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: var(--radius-full); font-size: 13px; font-weight: 600; }
.stock-indicator.normal { background: rgba(16, 185, 129, 0.15); color: var(--success); }
.stock-indicator.low { background: rgba(239, 68, 68, 0.15); color: var(--danger); }
.stock-indicator.low::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: none; cursor: pointer; transition: all var(--transition-base); text-decoration: none; }
.btn-icon.btn-secondary { background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-secondary); }
.btn-icon.btn-secondary:hover { background: var(--glass-bg-hover); color: var(--text-primary); }
.btn-icon.btn-danger { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); }
.btn-icon.btn-danger:hover { background: rgba(239, 68, 68, 0.25); transform: scale(1.05); }
.empty-state { text-align: center; padding: 64px 24px; color: var(--text-tertiary); }
.empty-state i { font-size: 64px; margin-bottom: 16px; opacity: 0.4; }
.empty-state p { font-size: 16px; margin-bottom: 8px; }
.mb-4 { margin-bottom: 24px; }
.mt-3 { margin-top: 16px; }
@media (max-width: 768px) { .product-thumb, .product-thumb-placeholder { width: 36px; height: 36px; } }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';

