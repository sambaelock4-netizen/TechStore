<?php
// Test database connection
echo "<h1>Test de connexion</h1>";

try {
    require_once dirname(__DIR__) . '/config/constants.php';
    echo "Constants loaded<br>";
    
    require_once dirname(__DIR__) . '/config/db.php';
    echo "Database connected successfully!<br>";
    echo "Base de données: " . $dbname . "<br>";
    
    // Test a simple query
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    echo "Tables trouvées: " . count($tables) . "<br>";
    
} catch (PDOException $e) {
    echo "ERREUR: " . $e->getMessage() . "<br>";
}
