<?php
/**
 * TECHSTORE - Admin User Form with Beautiful Design
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($user) && $user ? 'Modifier' : 'Ajouter'; ?> un utilisateur - TechStore Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #0dcaf0;
            --dark: #1a1d20;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }
        
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--dark) 0%, #2d3238 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), #0a58ca);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .sidebar-brand .brand-text {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .sidebar-nav {
            padding: 15px 0;
        }
        
        .nav-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin: 2px 10px;
            border-radius: 8px;
        }
        
        .nav-item-custom:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }
        
        .nav-item-custom.active {
            background: rgba(13, 110, 253, 0.15);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        
        .nav-item-custom i {
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        
        .content-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            border: none;
        }
        
        .content-card .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #0a58ca);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }
        
        .btn-secondary {
            background: var(--secondary);
            border: none;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--secondary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            color: var(--secondary);
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 5px 10px;
            border-radius: 8px;
        }
        
        .logout-btn:hover {
            background: rgba(220, 53, 69, 0.2);
            color: var(--danger);
        }
        
        .role-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .role-badge.admin {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .role-badge.client {
            background: linear-gradient(135deg, var(--primary), #0a58ca);
            color: white;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand d-flex align-items-center gap-3">
                <div class="brand-icon text-white">
                    <i class="bi bi-motherboard"></i>
                </div>
                <span class="brand-text text-white">TECHSTORE</span>
            </div>
            
            <nav class="sidebar-nav">
                <a href="<?= BASE_URL ?>/admin" class="nav-item-custom">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/products" class="nav-item-custom">
                    <i class="fas fa-box"></i>
                    <span>Produits</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/orders" class="nav-item-custom">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commandes</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/users" class="nav-item-custom">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/categories" class="nav-item-custom">
                    <i class="fas fa-tags"></i>
                    <span>Catégories</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/stock" class="nav-item-custom">
                    <i class="fas fa-warehouse"></i>
                    <span>Stock</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item-custom">
                    <i class="fas fa-percent"></i>
                    <span>Promotions</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item-custom">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
                <a href="<?= BASE_URL ?>/admin/profile" class="nav-item-custom">
                    <i class="fas fa-user-cog"></i>
                    <span>Profil</span>
                </a>
            </nav>
            
            <div class="mt-auto p-3">
                <a href="<?= BASE_URL ?>/home" class="logout-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour au site</span>
                </a>
                <a href="<?= BASE_URL ?>/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-grow-1">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-<?= isset($user) && $user ? 'person-dash' : 'person-plus' ?> me-2"></i>
                        <?= isset($user) && $user ? 'Modifier' : 'Ajouter'; ?> un utilisateur
                    </h2>
                    <p class="text-muted mb-0"><?= isset($user) && $user ? 'Modifiez les informations de l\'utilisateur' : 'Créez un nouvel utilisateur'; ?></p>
                </div>
            </div>

            <form method="POST">
                <div class="row">
                    <!-- Left Column - User Info -->
                    <div class="col-lg-8">
                        <div class="content-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-title">Informations personnelles</div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstname" class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="firstname" name="firstname" 
                                                   value="<?= htmlspecialchars($user['firstname'] ?? ''); ?>" 
                                                   placeholder="Ex: Jean" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="lastname" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="lastname" name="lastname" 
                                                   value="<?= htmlspecialchars($user['lastname'] ?? ''); ?>" 
                                                   placeholder="Ex: Dupont" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($user['email'] ?? ''); ?>" 
                                               placeholder="Ex: jean.dupont@email.com" required>
                                    </div>
                                </div>
                                
                                <?php if (!isset($user) || !$user): ?>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Mot de passe" required>
                                    </div>
                                    <small class="text-muted">Laissez ce champ vide pour conserver le mot de passe actuel</small>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" 
                                               placeholder="Ex: 0612345678">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea class="form-control" id="address" name="address" rows="2"
                                                  placeholder="Adresse complète"><?= htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">Ville</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                                            <input type="text" class="form-control" id="city" name="city" 
                                                   value="<?= htmlspecialchars($user['city'] ?? ''); ?>" 
                                                   placeholder="Ex: Paris">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="postal_code" class="form-label">Code postal</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                            <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                                   value="<?= htmlspecialchars($user['postal_code'] ?? ''); ?>" 
                                                   placeholder="Ex: 75001">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Role & Status -->
                    <div class="col-lg-4">
                        <div class="content-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-title">Rôle utilisateur</div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Sélectionner le rôle</label>
                                    
                                    <div class="form-check custom-radio-box mb-3">
                                        <input class="form-check-input" type="radio" name="role" id="role_client" 
                                               value="client" <?= (isset($user['role']) && $user['role'] === 'client') || !isset($user['role']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label w-100" for="role_client">
                                            <div class="d-flex align-items-center p-3 border rounded">
                                                <i class="fas fa-user text-primary me-3 fs-4"></i>
                                                <div>
                                                    <strong>Client</strong>
                                                    <small class="d-block text-muted">Accès limité au storefront</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check custom-radio-box">
                                        <input class="form-check-input" type="radio" name="role" id="role_admin" 
                                               value="admin" <?= isset($user['role']) && $user['role'] === 'admin' ? 'checked' : ''; ?>>
                                        <label class="form-check-label w-100" for="role_admin">
                                            <div class="d-flex align-items-center p-3 border rounded">
                                                <i class="fas fa-shield-alt text-danger me-3 fs-4"></i>
                                                <div>
                                                    <strong>Administrateur</strong>
                                                    <small class="d-block text-muted">Accès complet au panel admin</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer
                            </button>
                            <a href="<?= BASE_URL; ?>/admin/users" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
