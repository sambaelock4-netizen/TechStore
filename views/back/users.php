<?php
/**
 * TECHSTORE - Admin Users ULTRA PREMIUM
 */

$pageTitle = 'Utilisateurs';
$currentPage = 'users';

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
        <form method="GET" action="<?= BASE_URL ?>/admin/users" class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" name="search" placeholder="Rechercher un utilisateur..." 
                       value="<?= htmlspecialchars($search ?? '') ?>" class="form-control">
            </div>
            <div class="col-6 col-md-3">
                <select name="role" class="form-select form-control">
                    <option value="">Tous les rôles</option>
                    <option value="admin" <?= ($selectedRole ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                    <option value="user" <?= ($selectedRole ?? '') === 'user' ? 'selected' : '' ?>>Utilisateur</option>
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

<!-- Users Table -->
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-people"></i>
            Liste des Utilisateurs (<?= count($users ?? []) ?>)
        </h3>
        <a href="<?= BASE_URL ?>/admin/users/add" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Ajouter
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($users)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Commandes</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><span class="text-muted">#<?= $user['id'] ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-mini">
                                    <?= strtoupper(substr($user['firstname'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></div>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($user['created_at'])) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-muted"><?= htmlspecialchars($user['email']) ?></span></td>
                        <td>
                            <?php if (($user['role'] ?? 'user') === 'admin'): ?>
                                <span class="badge badge-primary">Admin</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Client</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="fw-semibold"><?= $user['order_count'] ?? 0 ?></span>
                        </td>
                        <td>
                            <?php if (($user['is_active'] ?? 1) == 1): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>" class="btn btn-icon btn-secondary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/users/delete/<?= $user['id'] ?>" class="btn btn-icon btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr?')">
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
            <i class="bi bi-people"></i>
            <p>Aucun utilisateur trouvé</p>
            <a href="<?= BASE_URL ?>/admin/users/add" class="btn btn-primary mt-3"><i class="bi bi-plus-lg"></i> Ajouter un utilisateur</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.user-avatar-mini { width: 36px; height: 36px; border-radius: var(--radius-full); background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: white; }
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
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';

