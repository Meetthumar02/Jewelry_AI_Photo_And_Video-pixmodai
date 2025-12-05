<?php
/**
 * Database Setup Script
 * This script creates the ai-photoshoot and creative-ai databases
 * 
 * Usage: php database/setup_databases.php
 * Or run from command line: php artisan db:create
 */

// Database configuration (update these values)
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

$databases = [
    'ai_photoshoot',
    'creative_ai'
];

try {
    // Connect to MySQL server (without selecting a database)
    $pdo = new PDO(
        "mysql:host={$host};port={$port}",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Connected to MySQL server successfully.\n\n";

    foreach ($databases as $dbName) {
        try {
            // Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "✓ Database '{$dbName}' created successfully.\n";
        } catch (PDOException $e) {
            echo "✗ Error creating database '{$dbName}': " . $e->getMessage() . "\n";
        }
    }

    echo "\nDatabase setup completed!\n";
    echo "\nNext steps:\n";
    echo "1. Update your .env file with the database names:\n";
    echo "   AI_PHOTOSHOOT_DB_DATABASE=ai_photoshoot\n";
    echo "   CREATIVE_AI_DB_DATABASE=creative_ai\n";
    echo "\n2. Run migrations:\n";
    echo "   php artisan migrate\n";

} catch (PDOException $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    echo "\nPlease check your database credentials in the .env file.\n";
    exit(1);
}

