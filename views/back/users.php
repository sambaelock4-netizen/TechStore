<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
$tempPassword = $_GET['temp_password'] ?? '';
?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php
    switch($success) {
        case 'added': echo 'Utilisateur ajouté avec succès!'; break;
        case 'updated': echo 'Utilisateur mis à jour avec succès!'; break;
        case 'deleted': echo 'Utilisateur supprimé avec succès!'; break;
        case 'password_reset': echo 'Mot de passe réinitialisé!'; break;
        default: echo 'Opération réussie!';
    }
    ?>
    <?php if ($tempPassword): ?>
    <br><strong>Mot de passe temporaire: <?= htmlspecialchars($tempPassword) ?></strong>
    <?php endif; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $error === 'not_found' ? 'Utilisateur non trouvé!' : ($error === 'cannot_delete_self' ? 'Vous ne pouvez pas supprimer votre propre compte!' : 'Une erreur est survenue!') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-users me-2"></i>Gestion des Utilisateurs</h2>
    <a href="<?= BASE_URL ?>/admin/users/add" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Ajouter un Utilisateur
    </a>
</div>

<!-- Users Table -->
<div class="table-card card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Ville</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-3x mb-3 d-block"></i>
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?= $user['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></strong>
                            <div class="small text-muted"><?= htmlspecialchars($user['phone'] ?? '') ?></div>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php
                            $roleBadges = [
                                'super_admin' => 'bg-danger',
                                'admin' => 'bg-primary',
                                'product_manager' => 'bg-warning text-dark',
                                'order_manager' => 'bg-info text-dark',
                                'client' => 'bg-secondary'
                            ];
                            $badgeClass = $roleBadges[$user['role']] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($user['role']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($user['city'] ?? '-') ?></td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/users/reset/<?= $user['id'] ?>" class="btn btn-sm btn-outline-warning" title="Réinitialiser mot de passe" onclick="return confirm('Réinitialiser le mot de passe de cet utilisateur?');">
                                    <i class="fas fa-key"></i>
                                </a>
                                <?php if ($user['id'] != $currentUser['id']): ?>
                                <a href="<?= BASE_URL ?>/admin/users/delete/<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirmDelete('Êtes-vous sûr de vouloir supprimer cet utilisateur?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
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
