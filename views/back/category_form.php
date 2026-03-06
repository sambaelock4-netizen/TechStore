<?php
/**
 * TECHSTORE - Admin Category Form with Beautiful Design
 */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($category) && $category ? 'Modifier' : 'Ajouter'; ?> une catégorie - TechStore Admin</title>
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
        
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
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
        
        .preview-icon {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            background: linear-gradient(135deg, var(--primary), #0a58ca);
            color: white;
            margin-bottom: 15px;
        }
        
        .category-icon-select {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }
        
        .category-icon-option {
            width: 50px;
            height: 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
            color: var(--secondary);
        }
        
        .category-icon-option:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .category-icon-option.selected {
            border-color: var(--primary);
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary);
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
                        <i class="bi bi-<?= isset($category) && $category ? 'pencil-square' : 'plus-circle' ?> me-2"></i>
                        <?= isset($category) && $category ? 'Modifier' : 'Ajouter'; ?> une catégorie
                    </h2>
                    <p class="text-muted mb-0"><?= isset($category) && $category ? 'Modifiez les informations de la catégorie' : 'Créez une nouvelle catégorie de produits'; ?></p>
                </div>
            </div>

            <form method="POST">
                <div class="row">
                    <!-- Left Column - Category Info -->
                    <div class="col-lg-8">
                        <div class="content-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-title">Informations de la catégorie</div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?= htmlspecialchars($category['name'] ?? ''); ?>" 
                                               placeholder="Ex: Ordinateurs portables" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug (URL)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-link"></i></span>
                                        <input type="text" class="form-control" id="slug" name="slug" 
                                               value="<?= htmlspecialchars($category['slug'] ?? ''); ?>" 
                                               placeholder="Ex: ordinateurs-portables">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip" title="Laissez vide pour générer automatiquement"></i>
                                        </span>
                                    </div>
                                    <small class="text-muted">Ce champ est optional - il sera généré automatiquement si laissé vide</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                              placeholder="Décrivez cette catégorie de produits..."><?= htmlspecialchars($category['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Icon & Status -->
                    <div class="col-lg-4">
                        <div class="content-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-title">Icône de la catégorie</div>
                                
                                <div class="text-center mb-3">
                                    <div class="preview-icon mx-auto" id="iconPreview">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                </div>
                                
                                <label class="form-label">Sélectionner une icône</label>
                                <div class="category-icon-select">
                                    <div class="category-icon-option selected" data-icon="fa-folder" onclick="selectIcon(this)">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-laptop" onclick="selectIcon(this)">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-desktop" onclick="selectIcon(this)">
                                        <i class="fas fa-desktop"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-microchip" onclick="selectIcon(this)">
                                        <i class="fas fa-microchip"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-memory" onclick="selectIcon(this)">
                                        <i class="fas fa-memory"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-hdd" onclick="selectIcon(this)">
                                        <i class="fas fa-hdd"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-keyboard" onclick="selectIcon(this)">
                                        <i class="fas fa-keyboard"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-mouse" onclick="selectIcon(this)">
                                        <i class="fas fa-mouse"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-headphones" onclick="selectIcon(this)">
                                        <i class="fas fa-headphones"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-camera" onclick="selectIcon(this)">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-wifi" onclick="selectIcon(this)">
                                        <i class="fas fa-wifi"></i>
                                    </div>
                                    <div class="category-icon-option" data-icon="fa-network-wired" onclick="selectIcon(this)">
                                        <i class="fas fa-network-wired"></i>
                                    </div>
                                </div>
                                <input type="hidden" name="icon" id="selectedIcon" value="<?= htmlspecialchars($category['icon'] ?? 'fa-folder'); ?>">
                            </div>
                        </div>
                        
                        <div class="content-card mb-4">
                            <div class="card-body p-4">
                                <div class="section-title">Statut</div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" <?= (isset($category['is_active']) && $category['is_active']) || !isset($category['is_active']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle text-success me-1"></i> Catégorie active
                                    </label>
                                    <small class="d-block text-muted mt-1">Les produits de cette catégorie seront visibles</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer
                            </button>
                            <a href="<?= BASE_URL; ?>/admin/categories" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectIcon(element) {
            document.querySelectorAll('.category-icon-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('selectedIcon').value = element.dataset.icon;
            
            // Update preview
            document.getElementById('iconPreview').innerHTML = '<i class="fas ' + element.dataset.icon + '"></i>';
        }
        
        // Initialize with existing icon
        document.addEventListener('DOMContentLoaded', function() {
            const existingIcon = '<?= htmlspecialchars($category['icon'] ?? 'fa-folder'); ?>';
            const iconOption = document.querySelector('.category-icon-option[data-icon="' + existingIcon + '"]');
            if (iconOption) {
                selectIcon(iconOption);
            }
        });
    </script>
</body>
</html>
