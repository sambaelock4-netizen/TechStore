<?php
/**
 * ==================================================================================
 * TechStore - Point d'Entrée (Legacy / Redirect)
 * ==================================================================================
 * Ce fichier sert de point d'entrée par défaut si l'utilisateur accède 
 * directement à la racine du projet. 
 * Il redirige immédiatement vers l'écran d'accueil (splash.php) ou le router.
 * ==================================================================================
 */

// Redirection vers la page de garde (splash screen)
header('Location: /TechStore/splash.php');
exit;

