<?php
/**
 * ==================================================================================
 * TechStore - Connexion Base de Données
 * ==================================================================================
 * Ce fichier configure et initialise la connexion PDO globale.
 * Il définit les paramètres de connexion et les options PDO recommandées
 * pour la sécurité et la performance.
 * ==================================================================================
 */

// Paramètres de connexion
$host    = 'localhost';    // Hôte (souvent localhost)
$dbname  = 'techstore';    // Nom de la base de données
$user    = 'root';         // Utilisateur (root par défaut sous XAMPP)
$pass    = '';             // Mot de passe (vide par défaut sous XAMPP)
$charset = 'utf8mb4';      // Encodage recommandé pour le support complet de l'UTF-8

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Options PDO pour un comportement professionnel
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Active les exceptions pour les erreurs SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // Retourne les résultats sous forme de tableaux associatifs
    PDO::ATTR_EMULATE_PREPARES => false,                // Utilise les vraies requêtes préparées MySQL
];

try {
    /** @var PDO $pdo Instance globale utilisée dans toute l'application */
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // En cas d'échec, on arrête tout proprement avec un message d'erreur
    die("ERREUR CRITIQUE : Impossible de se connecter à la base de données MySQL. Vérifiez vos identifiants dans config/db.php. Détails : " . $e->getMessage());
}
