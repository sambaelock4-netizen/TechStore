<?php
/**
 * TECHSTORE - Splash Screen
 * Écran de démarrage avec redirection automatique
 * Note: Ce fichier est autonome et n'inclut pas le header/footer
 */

// Configurer le délai de redirection (en secondes)
$redirectDelay = 3;
$redirectUrl = BASE_URL . '/home';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Chargement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 50%, #084298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .splash-container {
            text-align: center;
            color: white;
            animation: fadeIn 0.5s ease-in;
        }
        
        .logo-container {
            width: 150px;
            height: 150px;
            background: white;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: pulse 2s infinite;
        }
        
        .logo-container i {
            font-size: 80px;
            color: #0d6efd;
        }
        
        .brand-text {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: 3px;
            margin-bottom: 10px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        
        .tagline {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 40px;
        }
        
        .loading-bar {
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .loading-bar::after {
            content: '';
            display: block;
            width: 50%;
            height: 100%;
            background: white;
            border-radius: 2px;
            animation: loading 1.5s infinite ease-in-out;
        }
        
        .skip-text {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .skip-text:hover {
            color: white;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        @keyframes loading {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(300%);
            }
        }
    </style>
</head>
<body>
    <div class="splash-container">
        <div class="logo-container">
            <i class="fas fa-microchip"></i>
        </div>
        <h1 class="brand-text">TECHSTORE</h1>
        <p class="tagline">Votre boutique d'équipements informatiques</p>
        <div class="loading-bar"></div>
    </div>
    
    <a href="<?php echo $redirectUrl; ?>" class="skip-text">Passer ></a>
    
    <script>
        // Redirection automatique après <?php echo $redirectDelay; ?> secondes
        setTimeout(function() {
            window.location.href = '<?php echo $redirectUrl; ?>';
        }, <?php echo $redirectDelay * 1000; ?>);
    </script>
</body>
</html>

