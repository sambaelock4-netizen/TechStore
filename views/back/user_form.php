<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $error === 'exists' ? 'L\'email existe déjà!' : 'Une erreur est survenue!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user me-2"></i><?= isset($user) ? 'Modifier' : 'Ajouter' ?> un Utilisateur</h2>
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<!-- User Form -->
<div class="table-card card">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Prénom *</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label"><?= isset($user) ? 'Nouveau mot de passe' : 'Mot de passe *' ?></label>
                <input type="password" class="form-control" id="password" name="password" <?= !isset($user) ? 'required' : '' ?>>
                <?php if (isset($user)): ?>
                <small class="text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">Rôle *</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="client" <?= (isset($user['role']) && $user['role'] === 'client') ? 'selected' : '' ?>>Client</option>
                    <option value="admin" <?= (isset($user['role']) && $user['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
                    <option value="super_admin" <?= (isset($user['role']) && $user['role'] === 'super_admin') ? 'selected' : '' ?>>Super Administrateur</option>
                    <option value="product_manager" <?= (isset($user['role']) && $user['role'] === 'product_manager') ? 'selected' : '' ?>>Gestionnaire Produits</option>
                    <option value="order_manager" <?= (isset($user['role']) && $user['role'] === 'order_manager') ? 'selected' : '' ?>>Gestionnaire Commandes</option>
                </select>
            </div>
            
            <hr>
            
            <h5 class="mb-3">Informations complémentaires</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Adresse</label>
                <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="postal_code" class="form-label">Code postal</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>">
            </div>
            
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
