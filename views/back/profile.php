<?php
// Messages d'alerte
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Profil mis à jour avec succès!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $error === 'password' ? 'Mot de passe actuel incorrect!' : 'Une erreur est survenue!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-cog me-2"></i>Mon Profil</h2>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Profile Form -->
        <div class="table-card card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations du profil</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Prénom *</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($currentUser['firstname']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($currentUser['lastname']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($currentUser['email']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Password Change -->
        <div class="table-card card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Changer le mot de passe</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-1"></i> Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Account Info -->
        <div class="table-card card">
            <div class="card-header">
                <h5 class="mb-0">Informations du compte</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Rôle</label>
                    <p class="mb-0">
                        <?php
                        $roles = [
                            'super_admin' => 'Super Administrateur',
                            'admin' => 'Administrateur',
                            'product_manager' => 'Gestionnaire Produits',
                            'order_manager' => 'Gestionnaire Commandes',
                            'client' => 'Client'
                        ];
                        echo $roles[$currentUser['role']] ?? $currentUser['role'];
                        ?>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Membre depuis</label>
                    <p class="mb-0"><?= date('d/m/Y', strtotime($currentUser['created_at'])) ?></p>
                </div>
                <div>
                    <label class="text-muted small">Dernière modification</label>
                    <p class="mb-0"><?= date('d/m/Y H:i', strtotime($currentUser['updated_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
