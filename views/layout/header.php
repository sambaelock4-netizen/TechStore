<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TechStore' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/style.css">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root { --primary-tech: #0d6efd; --dark-tech: #1a1d20; }
        body { font-family: 'Inter', sans-serif; }
        
        /* Navbar Styling */
        .navbar { background-color: var(--dark-tech); border-bottom: 2px solid var(--primary-tech); }
        .nav-link { font-weight: 500; transition: color 0.3s ease; }
        .nav-link:hover { color: var(--primary-tech) !important; }

        /* Barre de recherche elegante */
        .search-group { width: 350px; }
        .search-group .form-control { border-radius: 20px 0 0 20px; border: none; }
        .search-group .btn { border-radius: 0 20px 20px 0; }
        
        /* Mobile navbar adjustments */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: var(--dark-tech);
                padding: 1rem;
                border-radius: 0 0 8px 8px;
                margin-top: 0.5rem;
            }
            
            .search-group {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .search-group .form-control {
                border-radius: 20px 0 0 20px;
            }
            
            .search-group .btn {
                border-radius: 0 20px 20px 0;
            }
            
            .nav-link {
                padding: 0.75rem 0 !important;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .nav-item.me-3 {
                margin: 1rem 0 !important;
            }
        }

        /* Effets Boutons */
        .btn-tech { 
            border-radius: 8px; 
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-tech::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-tech:hover::before {
            left: 100%;
        }
        
        .btn-tech:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }
        
        .btn-tech:active {
            transform: translateY(-1px);
        }
        
        /* Style specifique pour inscription */
        .btn-register {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #0953a3 100%);
        }
        
        /* Style specifique pour connexion */
        .btn-login {
            border: 2px solid rgba(255,255,255,0.3);
            background: transparent;
        }
        
        .btn-login:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
        }
        
        .cart-badge { font-size: 0.7rem; top: -5px; right: -10px; }
    </style>
</head>
<body>

<header class="sticky-top shadow">
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="<?= BASE_URL ?>/home">
                <span class="text-primary">TECH</span>STORE
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <form class="d-flex mx-auto mt-3 mt-lg-0 search-group" action="<?= BASE_URL ?>/catalogue" method="GET">
                    <input class="form-control search-input" type="search" name="q" placeholder="Chercher un produit..." aria-label="Search">
                    <button class="btn btn-primary search-btn text-white" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link px-3" href="<?= BASE_URL ?>/home">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="<?= BASE_URL ?>/catalogue">Boutique</a></li>

                    <li class="nav-item me-3">
                        <a href="<?= BASE_URL ?>/cart" class="nav-link position-relative">
                            <i class="bi bi-cart3 fs-4"></i>
                            <span class="position-absolute badge rounded-pill bg-danger cart-badge" id="headerCartCount">0</span>
                        </a>
                    </li>

                    <?php if(isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn btn-outline-primary btn-tech text-white px-4" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> Mon Profil
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/account">Tableau de bord</a></li>
                                <?php if($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'super_admin'): ?>
                                    <li><a class="dropdown-item text-primary fw-bold" href="<?= BASE_URL ?>/admin">Back Office</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout">Deconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Bouton Connexion -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/login" class="btn btn-outline-light btn-tech btn-login px-4 fw-bold me-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
                            </a>
                        </li>
                        <!-- Bouton Inscription -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-tech btn-register px-4 fw-bold">
                                <i class="bi bi-person-plus me-1"></i> S'inscrire
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });

    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('techstore_cart')) || [];
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        
        // Update header cart badge
        const headerCartBadge = document.getElementById('headerCartCount');
        if (headerCartBadge) {
            headerCartBadge.textContent = totalItems;
        }
        
        // Update promo cart badge in home page carousel
        const promoCartBadge = document.getElementById('promoCartBadge');
        if (promoCartBadge) {
            promoCartBadge.textContent = '🛒 Panier (' + totalItems + ')';
        }
    }
</script>

<main>
