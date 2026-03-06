<?php
// Vérifier si déjà connecté - rediriger automatiquement vers la page appropriée
if (isset($_SESSION['user'])) {
    $userRole = $_SESSION['user']['role'] ?? 'client';
    if ($userRole === 'admin' || $userRole === 'super_admin') {
        header('Location: ' . BASE_URL . '/admin');
    } else {
        header('Location: ' . BASE_URL . '/account');
    }
    exit;
}

// Traitement du formulaire d'inscription
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $acceptTerms = isset($_POST['accept_terms']);
    
    // Validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Veuillez remplir tous les champs';
    } elseif (strlen($firstname) < 2) {
        $error = 'Le prénom doit contenir au moins 2 caractères';
    } elseif (strlen($lastname) < 2) {
        $error = 'Le nom doit contenir au moins 2 caractères';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères';
    } elseif ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif (!$acceptTerms) {
        $error = 'Vous devez accepter les conditions générales';
    } else {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error = 'Cette adresse email est déjà utilisée';
            } else {
                // Créer l'utilisateur
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role, created_at) VALUES (?, ?, ?, ?, 'client', NOW())");
                $stmt->execute([$firstname, $lastname, $email, $hashedPassword]);
                
                $success = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
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
            <div class="col-md-6 col-lg-5">
                <div class="auth-card animate__animated animate__fadeInUp">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <a href="<?= BASE_URL ?>/home" class="navbar-brand fw-bold fs-3">
                            <span class="text-primary">TECH</span>STORE
                        </a>
                    </div>
                    
                    <!-- Titre -->
                    <h2 class="text-center mb-2 fw-bold">Créer un compte</h2>
                    <p class="text-center text-muted mb-4">Rejoignez TechStore et commencez vos achats</p>
                    
                    <!-- Message d'erreur -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger animate__animated animate__shake">
                            <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Message de succès avec redirection automatique -->
                    <?php if ($success): ?>
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                            <p class="mt-2 mb-0">Redirection automatique vers la page de connexion...</p>
                        </div>
                        <script>
                            setTimeout(function() {
                                window.location.href = '<?= BASE_URL ?>/login';
                            }, 3000);
                        </script>
                    <?php endif; ?>
                    
                    <!-- Formulaire -->
                    <?php if (!$success): ?>
                    <form method="POST" action="" class="auth-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label">Prénom</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="firstname" name="firstname" 
                                           placeholder="Thierry" required 
                                           value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastname" class="form-label">Nom</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="lastname" name="lastname" 
                                           placeholder="Armel" required 
                                           value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" 
                                       placeholder="Thierry@gmail.com" required 
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
                            <div class="form-text">Minimum 8 caractères</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" 
                                       placeholder="••••••••" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" 
                                       <?= isset($_POST['accept_terms']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="accept_terms">
                                    J'accepte les <a href="#" class="text-primary">conditions générales</a> et la <a href="#" class="text-primary">politique de confidentialité</a>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold btn-auth">
                            <i class="bi bi-person-plus me-2"></i> Créer mon compte
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <!-- Séparateur -->
                    <div class="text-center my-4">
                        <span class="bg-light px-3 position-relative text-muted small">ou</span>
                    </div>
                    
                    <!-- Lien connexion -->
                    <p class="text-center mb-0">
                        Déjà inscrit ? 
                        <a href="<?= BASE_URL ?>/login" class="text-primary fw-bold text-decoration-none">
                            Se connecter
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

.alert-success {
    border-radius: 8px;
    border: none;
    background-color: #d1e7dd;
    color: #0f5132;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    }
});
</script>
