<?php
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Traitement de la suppression d'adresse
if (isset($_GET['delete_address'])) {
    $address_id = (int)$_GET['delete_address'];
    try {
        $stmt = $pdo->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
        $stmt->execute([$address_id, $user_id]);
        header('Location: ' . BASE_URL . '/account');
        exit;
    } catch (PDOException $e) {
        $error = 'Erreur lors de la suppression';
        error_log($e->getMessage());
    }
}

// Traitement de l'ajout d'adresse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $address_name = trim($_POST['address_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    
    if (empty($address_name) || empty($address) || empty($postal_code) || empty($city)) {
        $error = 'Veuillez remplir tous les champs obligatoires';
    } else {
        try {
            if ($is_default) {
                $stmt = $pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?");
                $stmt->execute([$user_id]);
            }
            $stmt = $pdo->prepare("INSERT INTO addresses (user_id, name, address, postal_code, city, phone, is_default) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $address_name, $address, $postal_code, $city, $phone, $is_default]);
            $success = 'Adresse ajoutée avec succès!';
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'ajout de l\'adresse';
            error_log($e->getMessage());
        }
    }
}

// Traitement de la modification d'adresse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_address'])) {
    $address_id = (int)$_POST['address_id'];
    $address_name = trim($_POST['address_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    
    if (empty($address_name) || empty($address) || empty($postal_code) || empty($city)) {
        $error = 'Veuillez remplir tous les champs obligatoires';
    } else {
        try {
            if ($is_default) {
                $stmt = $pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ? AND id != ?");
                $stmt->execute([$user_id, $address_id]);
            }
            $stmt = $pdo->prepare("UPDATE addresses SET name = ?, address = ?, postal_code = ?, city = ?, phone = ?, is_default = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$address_name, $address, $postal_code, $city, $phone, $is_default, $address_id, $user_id]);
            $success = 'Adresse mise à jour avec succès!';
        } catch (PDOException $e) {
            $error = 'Erreur lors de la mise à jour de l\'adresse';
            error_log($e->getMessage());
        }
    }
}

// Traitement du formulaire de profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if (empty($firstname) || empty($lastname) || empty($email)) {
        $error = 'Veuillez remplir tous les champs obligatoires';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
            $stmt->execute([$email, $user_id]);
            
            if ($stmt->fetch()) {
                $error = 'Cette adresse email est déjà utilisée';
            } else {
                $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE id = ?");
                $stmt->execute([$firstname, $lastname, $email, $phone, $user_id]);
                
                $_SESSION['user_name'] = $firstname . ' ' . $lastname;
                $_SESSION['user_email'] = $email;
                
                $success = 'Profil mis à jour avec succès!';
            }
        } catch (PDOException $e) {
            $error = 'Une erreur est survenue. Veuillez réessayer.';
            error_log($e->getMessage());
        }
    }
}

// Récupérer les informations de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $user = [];
}

// Récupérer les adresses de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
    $stmt->execute([$user_id]);
    $addresses = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $addresses = [];
}

// Récupérer les commandes de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $orders = [];
}
?>

<div class="account-page py-5">
    <div class="container">
        <h1 class="mb-4 fw-bold">Mon Compte</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger animate__animated animate__shake">
                <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success animate__animated animate__fadeIn">
                <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" id="accountTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                    <i class="bi bi-person me-2"></i>Mon Profil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="addresses-tab" data-bs-toggle="tab" data-bs-target="#addresses" type="button" role="tab">
                    <i class="bi bi-geo-alt me-2"></i>Mes Adresses
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                    <i class="bi bi-bag me-2"></i>Mes Commandes
                </button>
            </li>
        </ul>
        
        <!-- Contenu des onglets -->
        <div class="tab-content" id="accountTabsContent">
            <!-- Profil -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel" tabindex="0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class="bi bi-person-circle me-2"></i>Informations personnelles</h4>
                        <form method="POST" action="">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstname" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" 
                                           value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastname" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" 
                                           value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>Enregistrer les modifications
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Adresses -->
            <div class="tab-pane fade" id="addresses" role="tabpanel" tabindex="0">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title mb-0"><i class="bi bi-geo-alt me-2"></i>Mes adresses</h4>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-lg me-1"></i>Ajouter une adresse
                            </button>
                        </div>
                        
                        <?php if (empty($addresses)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-geo fs-1"></i>
                                <p class="mt-2">Vous n'avez pas encore d'adresse enregistrée.</p>
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($addresses as $address): ?>
                                    <div class="col-md-6">
                                        <div class="card border <?= $address['is_default'] ? 'border-primary' : '' ?>">
                                            <div class="card-body">
                                                <?php if ($address['is_default']): ?>
                                                    <span class="badge bg-primary mb-2">Adresse par défaut</span>
                                                <?php endif; ?>
                                                <h6 class="fw-bold"><?= htmlspecialchars($address['name'] ?? 'Adresse') ?></h6>
                                                <p class="mb-1"><?= htmlspecialchars($address['address']) ?></p>
                                                <p class="mb-1"><?= htmlspecialchars($address['postal_code']) ?> <?= htmlspecialchars($address['city']) ?></p>
                                                <p class="mb-2 text-muted"><?= htmlspecialchars($address['country'] ?? 'France') ?></p>
                                                <?php if ($address['phone']): ?>
                                                    <p class="mb-2"><i class="bi bi-phone me-1"></i><?= htmlspecialchars($address['phone']) ?></p>
                                                <?php endif; ?>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAddressModal<?= $address['id'] ?>">
                                                        <i class="bi bi-pencil"></i> Modifier
                                                    </button>
                                                    <a href="<?= BASE_URL ?>/account?delete_address=<?= $address['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse?')">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Commandes -->
            <div class="tab-pane fade" id="orders" role="tabpanel" tabindex="0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class="bi bi-bag me-2"></i>Historique des commandes</h4>
                        
                        <?php if (empty($orders)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-bag fs-1"></i>
                                <p class="mt-2">Vous n'avez pas encore passé de commande.</p>
                                <a href="<?= BASE_URL ?>/catalogue" class="btn btn-primary mt-2">Découvrir nos produits</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>N° Commande</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><strong>#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></strong></td>
                                                <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                                <td><?= number_format($order['total_amount'], 2, ',', ' ') ?> FC</td>
                                                <td>
                                                    <?php
                                                    $status_class = [
                                                        'en_attente' => 'warning',
                                                        'confirme' => 'info',
                                                        'en_preparation' => 'primary',
                                                        'expedie' => 'info',
                                                        'livre' => 'success',
                                                        'annule' => 'danger'
                                                    ];
                                                    $status_labels = [
                                                        'en_attente' => 'En attente',
                                                        'confirme' => 'Confirmé',
                                                        'en_preparation' => 'En préparation',
                                                        'expedie' => 'Expédié',
                                                        'livre' => 'Livré',
                                                        'annule' => 'Annulé'
                                                    ];
                                                    ?>
                                                    <span class="badge bg-<?= $status_class[$order['status']] ?? 'secondary' ?>">
                                                        <?= $status_labels[$order['status']] ?? $order['status'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal<?= $order['id'] ?>">
                                                        <i class="bi bi-eye"></i> Détails
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter une adresse -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Ajouter une adresse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/account">
                <div class="modal-body">
                    <input type="hidden" name="add_address" value="1">
                    <div class="mb-3">
                        <label for="address_name" class="form-label">Libellé (ex: Maison, Travail)</label>
                        <input type="text" class="form-control" id="address_name" name="address_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Code postal</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                        <label class="form-check-label" for="is_default">
                            Définir comme adresse par défaut
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.account-page {
    background-color: #f8f9fa;
    min-height: calc(100vh - 76px);
}

.account-page .card {
    border-radius: 12px;
    border: none;
}

.account-page .nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
}

.account-page .nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: transparent;
    border-bottom: 2px solid #0d6efd;
}

.account-page .nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #0d6efd;
}

.account-page .table th {
    font-weight: 600;
    color: #495057;
}

.account-page .badge {
    padding: 0.5em 0.75em;
}
</style>
