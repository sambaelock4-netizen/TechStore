<?php
/**
 * TECHSTORE - Constants
 * Configuration des constantes de l'application
 */

// URLs du site
define('BASE_URL', 'http://localhost/TechStore');           // URL de base
define('PUBLIC_URL', BASE_URL . '/public');                  // Dossier public
define('UPLOAD_URL', BASE_URL . '/uploads');                 // Dossier uploads

// Chemins absolus
define('ROOT_PATH', dirname(__DIR__));                         // Racine du projet
define('APP_PATH', ROOT_PATH . '/app');                      // Dossier app
define('PUBLIC_PATH', ROOT_PATH . '/public');                // Dossier public
define('VIEW_PATH', ROOT_PATH . '/views');                   // Dossier views
define('UPLOAD_PATH', ROOT_PATH . '/uploads');              // Dossier uploads
define('CONFIG_PATH', ROOT_PATH . '/config');                // Dossier config

// Informations du site
define('SITE_NAME', 'TechStore');
define('SITE_DESCRIPTION', 'Votre boutique d\'équipements informatiques');
define('SITE_EMAIL', 'contact@techstore.com');
define('SITE_PHONE', '+33 1 23 45 67 89');

// Paramètres de pagination
define('ITEMS_PER_PAGE', 12);                                // Produits par page
define('ITEMS_PER_PAGE_ADMIN', 10);                          // Pour l'admin

// Paramètres du panier
define('CART_COOKIE_NAME', 'techstore_cart');
define('CART_COOKIE_EXPIRY', 86400 * 30);                   // 30 jours

// Messages d'erreur
define('ERROR_404', 'Page introuvable');
define('ERROR_500', 'Erreur serveur');

// Formats de date
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y à H:i');

// Conversion EUR to CFA (XOF)
define('EUR_TO_CFA', 655.957);

/**
 * Fonction pour afficher le prix en CFA
 */
function displayPrice($price_eur) {
    $price_cfa = $price_eur * EUR_TO_CFA;
    return number_format($price_cfa, 0, ',', ' ') . ' CFA';
}
