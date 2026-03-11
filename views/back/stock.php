<?php
/**
 * TECHSTORE - Admin Stock ULTRA PREMIUM
 */

$pageTitle = 'Stock';
$currentPage = 'stock';

ob_start();
?>

<!-- Stock Alert -->
<?php
$lowStockProducts = array_filter($products ?? [], function($p) {
    return ($p['stock'] ?? 0) <= 5;
});
if (!empty($lowStockProducts)):
?>
<div class="alert-box warning" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 16px;">
    <div style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.15); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--warning); font-size: 20px;">
        <i class="bi bi-exclamation-triangle"></i>
    </div>
    <div>
        <div class="fw-semibold" style="color: var(--text-primary);">Alerte Stock Bas</div>
        <div style="color: var(--text-secondary); font-size: 14px;"><?= count($lowStockProducts) ?> produit(s) ont un stock faible ou épuisé</div>
    </div>
</div>
<?php endif; ?>

<!-- Stock Summary -->
<div class="kpi-grid" style="margin-bottom: 24px;">
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon success">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
        <div class="kpi-value"><?= array_sum(array_column($products ?? [], 'stock')) ?></div>
        <div class="kpi-label">Total Stock</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
        <div class="kpi-value"><?= count($lowStockProducts) ?></div>
        <div class="kpi-label">Stock Faible</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon danger">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="kpi-value"><?= count(array_filter($products ?? [], function($p) { return ($p['stock'] ?? 0) == 0; })) ?></div>
        <div class="kpi-label">Épuisé</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon primary">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="kpi-value"><?= count(array_filter($products ?? [], function($p) { return ($p['stock'] ?? 0) > 10; })) ?></div>
        <div class="kpi-label">Bien Stocké</div>
    </div>
</div>

<!-- Stock Table -->
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-warehouse"></i>
            Gestion du Stock (<?= count($products ?? []) ?>)
        </h3>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($products)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Stock Actuel</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): 
                        $stock = $product['stock'] ?? 0;
                        $status = 'normal';
                        $statusLabel = 'En stock';
                        if ($stock == 0) {
                            $status = 'out';
                            $statusLabel = 'Épuisé';
                        } elseif ($stock <= 5) {
                            $status = 'low';
                            $statusLabel = 'Faible';
                        }
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= UPLOAD_URL ?>/<?= htmlspecialchars($product['image']) ?>" alt="" style="width: 40px; height: 40px; border-radius: var(--radius-sm); object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 40px; height: 40px; background: var(--glass-bg); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--text-tertiary);">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($product['name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($product['sku'] ?? '-') ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-secondary"><?= htmlspecialchars($product['category_name'] ?? '-') ?></span>
                        </td>
                        <td>
                            <span class="stock-value <?= $status ?>"><?= $stock ?></span>
                        </td>
                        <td>
                            <?php
                            $statusClasses = [
                                'normal' => 'badge-success',
                                'low' => 'badge-warning',
                                'out' => 'badge-danger'
                            ];
                            ?>
                            <span class="badge <?= $statusClasses[$status] ?>"><?= $statusLabel ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/product/edit/<?= $product['id'] ?>" class="btn btn-icon btn-secondary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-warehouse"></i>
            <p>Aucun produit trouvé</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.alert-box { animation: fadeInUp 0.4s ease; }
.kpi-grid { margin-bottom: 32px; }
.stock-value { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 700; }
.stock-value.normal { color: var(--success); }
.stock-value.low { color: var(--warning); }
.stock-value.out { color: var(--danger); }
.btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: none; cursor: pointer; transition: all var(--transition-base); text-decoration: none; }
.btn-icon.btn-secondary { background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-secondary); }
.btn-icon.btn-secondary:hover { background: var(--glass-bg-hover); color: var(--text-primary); }
.empty-state { text-align: center; padding: 64px 24px; color: var(--text-tertiary); }
.empty-state i { font-size: 64px; margin-bottom: 16px; opacity: 0.4; }
.empty-state p { font-size: 16px; margin-bottom: 8px; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';

