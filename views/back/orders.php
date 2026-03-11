<?php
/**
 * TECHSTORE - Admin Orders ULTRA PREMIUM
 */

$pageTitle = 'Commandes';
$currentPage = 'orders';

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
        <form method="GET" action="<?= BASE_URL ?>/admin/orders" class="row g-3">
            <div class="col-12 col-md-3">
                <input type="text" name="search" placeholder="Rechercher..." 
                       value="<?= htmlspecialchars($search ?? '') ?>" class="form-control">
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-control">
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
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-bag"></i>
            Liste des Commandes (<?= count($orders ?? []) ?>)
        </h3>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($orders)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <span class="fw-bold">#<?= $order['order_number'] ?? $order['id'] ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-mini">
                                    <?= strtoupper(substr($order['firstname'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-success"><?= displayPrice($order['total_amount']) ?></span>
                        </td>
                        <td>
                            <?php
                            $statusConfig = [
                                'en_attente' => ['class' => 'warning', 'label' => 'En attente'],
                                'confirme' => ['class' => 'info', 'label' => 'Confirmé'],
                                'en_preparation' => ['class' => 'primary', 'label' => 'Préparation'],
                                'expedie' => ['class' => 'secondary', 'label' => 'Expédié'],
                                'livre' => ['class' => 'success', 'label' => 'Livré'],
                                'annule' => ['class' => 'danger', 'label' => 'Annulé']
                            ];
                            $status = $statusConfig[$order['status']] ?? ['class' => 'warning', 'label' => $order['status']];
                            ?>
                            <span class="badge badge-<?= $status['class'] ?>"><?= $status['label'] ?></span>
                        </td>
                        <td>
                            <?php
                            $paymentConfig = [
                                'pending' => ['class' => 'warning', 'label' => 'En attente'],
                                'paid' => ['class' => 'success', 'label' => 'Payé'],
                                'failed' => ['class' => 'danger', 'label' => 'Échoué'],
                                'refunded' => ['class' => 'info', 'label' => 'Remboursé']
                            ];
                            $payment = $paymentConfig[$order['payment_status']] ?? ['class' => 'secondary', 'label' => $order['payment_status']];
                            ?>
                            <span class="badge badge-<?= $payment['class'] ?>"><?= $payment['label'] ?></span>
                        </td>
                        <td>
                            <span class="text-muted"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/orders/view/<?= $order['id'] ?>" class="btn btn-icon btn-secondary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-bag"></i>
            <p>Aucune commande trouvée</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.user-avatar-mini { width: 32px; height: 32px; border-radius: var(--radius-full); background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: white; }
.btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: none; cursor: pointer; transition: all var(--transition-base); text-decoration: none; }
.btn-icon.btn-secondary { background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-secondary); }
.btn-icon.btn-secondary:hover { background: var(--glass-bg-hover); color: var(--text-primary); }
.empty-state { text-align: center; padding: 64px 24px; color: var(--text-tertiary); }
.empty-state i { font-size: 64px; margin-bottom: 16px; opacity: 0.4; }
.empty-state p { font-size: 16px; margin-bottom: 8px; }
.mb-4 { margin-bottom: 24px; }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';

