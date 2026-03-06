<?php
/**
 * TECHSTORE - Admin Profile Responsive
 */
?>

<div class="admin-wrapper">
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle d-lg-none" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay d-lg-none" onclick="toggleSidebar()"></div>

    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon"><i class="fas fa-store"></i></span>
            <span class="brand-text">TECHSTORE</span>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/admin" class="nav-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
            <a href="<?= BASE_URL ?>/admin/products" class="nav-item"><i class="fas fa-box"></i><span>Produits</span></a>
            <a href="<?= BASE_URL ?>/admin/orders" class="nav-item"><i class="fas fa-shopping-cart"></i><span>Commandes</span></a>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-item"><i class="fas fa-users"></i><span>Utilisateurs</span></a>
            <a href="<?= BASE_URL ?>/admin/categories" class="nav-item"><i class="fas fa-tags"></i><span>Catégories</span></a>
            <a href="<?= BASE_URL ?>/admin/stock" class="nav-item"><i class="fas fa-warehouse"></i><span>Stock</span></a>
            <a href="<?= BASE_URL ?>/admin/promotions" class="nav-item"><i class="fas fa-percent"></i><span>Promotions</span></a>
            <a href="<?= BASE_URL ?>/admin/statistics" class="nav-item"><i class="fas fa-chart-bar"></i><span>Statistiques</span></a>
            <a href="<?= BASE_URL ?>/admin/profile" class="nav-item active"><i class="fas fa-user-cog"></i><span>Profil</span></a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>/home" class="nav-item"><i class="fas fa-arrow-left"></i><span>Retour au site</span></a>
            <a href="<?= BASE_URL ?>/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <div class="header-title">
                <h1>Mon Profil</h1>
                <p class="text-muted">Gérez vos informations personnelles</p>
            </div>
        </header>

        <div class="row">
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h3><i class="fas fa-user"></i> Informations du profil</h3>
                    </div>
                    <div class="card-body-custom">
                        <form method="POST" class="profile-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstname" class="form-label">Prénom</label>
                                    <input type="text" id="firstname" name="firstname" 
                                           value="<?= htmlspecialchars($user['firstname'] ?? ''); ?>" 
                                           class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastname" class="form-label">Nom</label>
                                    <input type="text" id="lastname" name="lastname" 
                                           value="<?= htmlspecialchars($user['lastname'] ?? ''); ?>" 
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? ''); ?>" 
                                       class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" 
                                       class="form-control">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="content-card mt-4">
                    <div class="card-header-custom">
                        <h3><i class="fas fa-lock"></i> Changer le mot de passe</h3>
                    </div>
                    <div class="card-body-custom">
                        <form method="POST" action="<?= BASE_URL ?>/admin/profile/password" class="password-form">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" 
                                       class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" id="new_password" name="new_password" 
                                           class="form-control" required minlength="8">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" id="confirm_password" name="confirm_password" 
                                           class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Changer le mot de passe
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-card">
                    <div class="card-body-custom text-center">
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h4 class="mt-3"><?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?></h4>
                        <p class="text-muted"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                        <span class="badge bg-primary">Administrateur</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.querySelector('.sidebar-overlay').classList.toggle('show');
}

document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    if (window.innerWidth <= 991) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('show');
            document.querySelector('.sidebar-overlay').classList.remove('show');
        }
    }
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 991) {
        document.getElementById('sidebar').classList.remove('show');
        document.querySelector('.sidebar-overlay').classList.remove('show');
    }
});
</script>

<style>
.admin-wrapper { display: flex; min-height: calc(100vh - 76px); background: #f5f7fa; }

.admin-sidebar { 
    width: 260px; 
    background: linear-gradient(180deg, #1a1d20 0%, #2d3238 100%); 
    position: fixed; 
    height: calc(100vh - 76px); 
    transition: transform 0.3s ease;
    z-index: 1000;
}

.sidebar-brand { padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 12px; }
.brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #0d6efd, #0a58ca); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
.brand-text { color: white; font-size: 20px; font-weight: 700; }
.sidebar-nav { flex: 1; padding: 20px 0; }
.nav-item { display: flex; align-items: center; gap: 12px; padding: 14px 20px; color: rgba(255,255,255,0.7); text-decoration: none; border-left: 3px solid transparent; }
.nav-item:hover, .nav-item.active { background: rgba(13, 110, 253, 0.15); color: #0d6efd; border-left-color: #0d6efd; }
.sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
.logout-btn { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: rgba(255,255,255,0.7); text-decoration: none; }
.logout-btn:hover { color: #dc3545; }

.admin-main { flex: 1; margin-left: 260px; padding: 30px; transition: margin-left 0.3s ease; }
.admin-header { margin-bottom: 30px; }
.header-title h1 { font-size: 28px; font-weight: 700; color: #1a1d20; margin: 0; }
.header-title .text-muted { color: #6c757d; margin: 5px 0 0; }

.content-card { background: white; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
.card-header-custom { padding: 20px; border-bottom: 1px solid #f0f0f0; }
.card-header-custom h3 { font-size: 16px; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 10px; }
.card-header-custom h3 i { color: #0d6efd; }
.card-body-custom { padding: 20px; }

.form-label { font-weight: 600; margin-bottom: 8px; color: #1a1d20; font-size: 14px; }
.form-control { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
.form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1); }

.btn { padding: 12px 24px; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; }
.btn-primary { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: white; }
.btn-warning { background: linear-gradient(135deg, #ffc107, #d69b00); color: #1a1d20; }

.profile-avatar i { font-size: 80px; color: #dee2e6; }
.mt-3 { margin-top: 15px; }
.mb-3 { margin-bottom: 15px; }
.mt-4 { margin-top: 20px; }

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1100;
    background: #0d6efd;
    color: white;
    border: none;
    border-radius: 8px;
    width: 45px;
    height: 45px;
    font-size: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

/* Responsive */
@media (max-width: 991px) {
    .mobile-menu-toggle { display: flex; align-items: center; justify-content: center; }
    .admin-sidebar { transform: translateX(-100%); }
    .admin-sidebar.show { transform: translateX(0); }
    .sidebar-overlay.show { display: block; }
    .admin-main { margin-left: 0; padding: 70px 20px 20px; }
}

@media (max-width: 767px) {
    .admin-main { padding: 70px 10px 15px; }
    .header-title h1 { font-size: 20px; }
    .content-card { border-radius: 12px; margin-bottom: 15px; }
    .card-header-custom { padding: 15px; }
    .card-header-custom h3 { font-size: 14px; }
    .card-body-custom { padding: 15px; }
    .form-label { font-size: 13px; }
    .form-control { padding: 10px 12px; font-size: 13px; }
    .btn { padding: 10px 16px; font-size: 13px; }
    .profile-avatar i { font-size: 60px; }
    .col-lg-8, .col-lg-4 { width: 100%; }
}

@media (max-width: 480px) {
    .header-title h1 { font-size: 18px; }
    .d-flex.gap-2 { flex-direction: column; }
    .btn { width: 100%; justify-content: center; }
}
</style>
