<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Opération réussie!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-history me-2"></i>Journal d'Activité</h2>
</div>

<!-- Logs Table -->
<div class="table-card card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Entité</th>
                        <th>Détails</th>
                        <th>IP</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-history fa-3x mb-3 d-block"></i>
                            Aucune activité enregistrée
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td>#<?= $log['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($log['firstname'] . ' ' . $log['lastname']) ?></strong>
                            <div class="small text-muted"><?= htmlspecialchars($log['email']) ?></div>
                        </td>
                        <td>
                            <?php
                            $actionBadges = [
                                'add_product' => 'bg-success',
                                'edit_product' => 'bg-primary',
                                'delete_product' => 'bg-danger',
                                'add_user' => 'bg-success',
                                'edit_user' => 'bg-primary',
                                'delete_user' => 'bg-danger',
                                'reset_password' => 'bg-warning text-dark',
                                'add_category' => 'bg-success',
                                'edit_category' => 'bg-primary',
                                'delete_category' => 'bg-danger',
                                'update_order_status' => 'bg-info',
                                'update_profile' => 'bg-secondary'
                            ];
                            $badgeClass = $actionBadges[$log['action']] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($log['action']) ?></span>
                        </td>
                        <td>
                            <?php if ($log['entity_type']): ?>
                            <span class="badge bg-dark"><?= htmlspecialchars($log['entity_type']) ?> #<?= $log['entity_id'] ?></span>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($log['details']): ?>
                            <small><?= htmlspecialchars($log['details']) ?></small>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></small></td>
                        <td><small><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<nav aria-label="Pagination" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
        <li class="page-item">
            <a class="page-link" href="<?= BASE_URL ?>/admin/logs?page=<?= $page - 1 ?>">Précédent</a>
        </li>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>/admin/logs?page=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
        <li class="page-item">
            <a class="page-link" href="<?= BASE_URL ?>/admin/logs?page=<?= $page + 1 ?>">Suivant</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
