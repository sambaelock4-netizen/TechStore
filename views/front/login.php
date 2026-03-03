<?php
// Traitement du formulaire de connexion
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        // Vérifier les identifiants dans la base de données
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'] ?? 'client';
                
                // Redirection vers la page précédente ou selon le rôle
                $redirect = $_GET['redirect'] ?? '';
                
                if ($redirect === 'admin') {
                    // Redirection vers le back office
                    header('Location: ' . BASE_URL . '/admin');
                } elseif (in_array($user['role'], ['admin', 'super_admin', 'product_manager', 'order_manager'])) {
                    // Redirection vers le back office pour les admins
                    header('Location: ' . BASE_URL . '/admin');
                } else {
                    // Redirection vers le compte pour les clients
                    header('Location: ' . BASE_URL . '/account');
                }
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        } catch (PDOException $e) {
            $error = 'Une erreur est survenue. Veuillez réessayer.';
            error_log($e->getMessage());
        }
    }
}
?>

<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="auth-card animate__animated animate__fadeInUp">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <a href="<?= BASE_URL ?>/home" class="navbar-brand fw-bold fs-3">
                            <span class="text-primary">TECH</span>STORE
                        </a>
                    </div>
                    
                    <!-- Titre -->
                    <h2 class="text-center mb-2 fw-bold">Bienvenue</h2>
                    <p class="text-center text-muted mb-4">Connectez-vous à votre compte</p>
                    
                    <!-- Message d'erreur -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger animate__animated animate__shake">
                            <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulaire -->
                    <form method="POST" action="" class="auth-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" 
                                       placeholder="vous@exemple.com" required 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" id="password" name="password" 
                                       placeholder="••••••••" required>
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                            <a href="<?= BASE_URL ?>/forgot-password" class="text-primary text-decoration-none small">
                                Mot de passe oublié ?
                            </a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold btn-auth">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
                        </button>
                    </form>
                    
                    <!-- Séparateur -->
                    <div class="text-center my-4">
                        <span class="bg-light px-3 position-relative text-muted small">ou</span>
                    </div>
                    
                    <!-- Lien inscription -->
                    <p class="text-center mb-0">
                        Pas encore de compte ? 
                        <a href="<?= BASE_URL ?>/register" class="text-primary fw-bold text-decoration-none">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-page {
    background: linear-gradient(135deg, #1a1d20 0%, #2d3238 100%);
    min-height: calc(100vh - 76px);
}

.auth-card {
    background: white;
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.auth-card .form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.auth-card .input-group-text {
    border-radius: 8px 0 0 8px;
}

.auth-card .form-control {
    border-radius: 0 8px 8px 0;
    padding: 0.75rem 1rem;
}

.auth-card .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

#togglePassword {
    border-radius: 0 8px 8px 0;
    border-left: none;
}

#togglePassword:hover {
    background-color: #e9ecef;
}

.btn-auth {
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
}

.alert-danger {
    border-radius: 8px;
    border: none;
    background-color: #f8d7da;
    color: #842029;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    }
});
</script>
