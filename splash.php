<?php
/**
 * TECHSTORE - Splash Screen Premium
 * Écran de démarrage avec animation premium
 */

// Charger les constantes
require_once 'config/constants.php';

// Configurer le délai de redirection (en secondes)
$redirectDelay = 4;
$redirectUrl = '/TechStore/router.php?url=home';
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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        /* Animated background */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .bg-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(ellipse at 20% 80%, rgba(13, 110, 253, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(108, 117, 125, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 40% 40%, rgba(13, 110, 253, 0.08) 0%, transparent 40%);
            animation: bgMove 20s ease-in-out infinite;
        }
        
        @keyframes bgMove {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(2%, 2%) rotate(1deg);
            }
            50% {
                transform: translate(0, 4%) rotate(0deg);
            }
            75% {
                transform: translate(-2%, 2%) rotate(-1deg);
            }
        }
        
        /* Floating particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(13, 110, 253, 0.6);
            border-radius: 50%;
            animation: float 15s infinite;
        }
        
        .particle:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; animation-duration: 12s; }
        .particle:nth-child(2) { left: 20%; top: 80%; animation-delay: 2s; animation-duration: 18s; }
        .particle:nth-child(3) { left: 30%; top: 40%; animation-delay: 4s; animation-duration: 14s; }
        .particle:nth-child(4) { left: 40%; top: 60%; animation-delay: 1s; animation-duration: 16s; }
        .particle:nth-child(5) { left: 50%; top: 30%; animation-delay: 3s; animation-duration: 12s; }
        .particle:nth-child(6) { left: 60%; top: 70%; animation-delay: 5s; animation-duration: 20s; }
        .particle:nth-child(7) { left: 70%; top: 50%; animation-delay: 2.5s; animation-duration: 15s; }
        .particle:nth-child(8) { left: 80%; top: 20%; animation-delay: 1.5s; animation-duration: 17s; }
        .particle:nth-child(9) { left: 90%; top: 90%; animation-delay: 3.5s; animation-duration: 13s; }
        .particle:nth-child(10) { left: 15%; top: 55%; animation-delay: 4.5s; animation-duration: 19s; }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(50px) scale(0.5);
                opacity: 0;
            }
        }
        
        /* Main container */
        .splash-container {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
        }
        
        /* Logo container with 3D effect */
        .logo-wrapper {
            position: relative;
            margin-bottom: 40px;
        }
        
        .logo-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(13, 110, 253, 0.4) 0%, transparent 70%);
            filter: blur(30px);
            animation: glowPulse 2s ease-in-out infinite;
        }
        
        @keyframes glowPulse {
            0%, 100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.5;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.8;
            }
        }
        
        .logo-container {
            width: 160px;
            height: 160px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.5),
                inset 0 0 30px rgba(13, 110, 253, 0.1);
            position: relative;
            overflow: hidden;
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        .logo-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 40%,
                rgba(13, 110, 253, 0.1) 50%,
                transparent 60%
            );
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }
        
        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .logo-container i {
            font-size: 70px;
            background: linear-gradient(135deg, #0d6efd, #6c757d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: iconPulse 2s ease-in-out infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        /* Brand text */
        .brand-text {
            font-size: 56px;
            font-weight: 800;
            letter-spacing: 8px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #ffffff 0%, #0d6efd 50%, #6c757d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textGlow 3s ease-in-out infinite;
            text-transform: uppercase;
        }
        
        @keyframes textGlow {
            0%, 100% {
                filter: drop-shadow(0 0 20px rgba(13, 110, 253, 0.5));
            }
            50% {
                filter: drop-shadow(0 0 40px rgba(13, 110, 253, 0.8));
            }
        }
        
        .tagline {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 50px;
            letter-spacing: 3px;
            font-weight: 300;
        }
        
        /* Loading animation */
        .loading-wrapper {
            width: 300px;
            margin: 0 auto;
        }
        
        .loading-track {
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }
        
        .loading-bar {
            height: 100%;
            background: linear-gradient(90deg, #0d6efd, #6c757d, #0d6efd);
            background-size: 200% 100%;
            border-radius: 3px;
            animation: loadingSlide 2s linear infinite;
        }
        
        @keyframes loadingSlide {
            0% {
                background-position: 200% 0;
                width: 0%;
            }
            20% {
                width: 60%;
            }
            80% {
                width: 60%;
            }
            100% {
                background-position: -200% 0;
                width: 100%;
            }
        }
        
        .loading-text {
            margin-top: 20px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 4px;
            text-transform: uppercase;
        }
        
        /* Skip button */
        .skip-container {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
        }
        
        .skip-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .skip-btn:hover {
            background: rgba(13, 110, 253, 0.3);
            border-color: rgba(13, 110, 253, 0.5);
            color: white;
            transform: translateY(-2px);
        }
        
        .skip-btn i {
            transition: transform 0.3s ease;
        }
        
        .skip-btn:hover i {
            transform: translateX(5px);
        }
        
        /* Countdown */
        .countdown {
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 20;
        }
        
        .countdown-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 2px;
        }
        
        /* Entrance animations */
        .splash-container {
            animation: fadeInUp 1s ease-out;
        }
        
        .logo-wrapper {
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        .brand-text {
            animation: fadeInUp 1s ease-out 0.4s both;
        }
        
        .tagline {
            animation: fadeInUp 1s ease-out 0.6s both;
        }
        
        .loading-wrapper {
            animation: fadeInUp 1s ease-out 0.8s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Animated background -->
    <div class="bg-animation"></div>
    
    <!-- Floating particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <!-- Countdown -->
    <div class="countdown">
        <span class="countdown-text">REDIRECTION DANS <?php echo $redirectDelay; ?>S</span>
    </div>
    
    <!-- Main content -->
    <div class="splash-container">
        <div class="logo-wrapper">
            <div class="logo-glow"></div>
            <div class="logo-container">
                <i class="fas fa-microchip"></i>
            </div>
        </div>
        
        <h1 class="brand-text">TECHSTORE</h1>
        <p class="tagline">Votre Boutique d'Équipements Informatiques Premium</p>
        
        <div class="loading-wrapper">
            <div class="loading-track">
                <div class="loading-bar"></div>
            </div>
            <div class="loading-text">Chargement en cours</div>
        </div>
    </div>
    
    <!-- Skip button -->
    <div class="skip-container">
        <a href="<?php echo $redirectUrl; ?>" class="skip-btn">
            Passer <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    
    <script>
        // Countdown timer
        let countdown = <?php echo $redirectDelay; ?>;
        const countdownElement = document.querySelector('.countdown-text');
        
        const countdownInterval = setInterval(function() {
            countdown--;
            if (countdown > 0) {
               Content = 'RE countdownElement.textDIRECTION DANS ' + countdown + 'S';
            } else {
                clearInterval(countdownInterval);
            }
        }, 1000);
        
        // Auto redirect after <?php echo $redirectDelay; ?> seconds
        setTimeout(function() {
            window.location.href = '<?php echo $redirectUrl; ?>';
        }, <?php echo $redirectDelay * 1000; ?>);
    </script>
</body>
</html>

