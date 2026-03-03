<?php
/**
 * TECHSTORE - Database Connection
 * Connexion à la base de données MySQL
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'techstore';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // Lever les exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // Retourner un tableau associatif
    PDO::ATTR_EMULATE_PREPARES => false,                  // Utiliser les vraie requêtes préparées
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
