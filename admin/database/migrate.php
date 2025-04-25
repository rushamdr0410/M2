<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Migration.php';

try {
    $migration = new Migration();
    
    // Create migrations table if it doesn't exist
    $migration->pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL
        )
    ");

    // Get list of migrations that have already run
    $stmt = $migration->pdo->query("SELECT migration FROM migrations");
    $completed = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Get all migration files
    $migrationFiles = glob(__DIR__ . '/migrations/2024_*.php');
    sort($migrationFiles); // Sort by timestamp

    $batch = time();
    foreach ($migrationFiles as $file) {
        $fileName = basename($file, '.php');
        // Convert filename to class name (e.g., 2024_04_25_000001_create_register_table -> CreateRegisterTable)
        $parts = explode('_', $fileName);
        array_splice($parts, 0, 4); // Remove the timestamp parts
        $className = str_replace(' ', '', ucwords(implode(' ', $parts)));
        
        if (!in_array($fileName, $completed)) {
            require_once $file;
            $migrationClass = new $className();
            $migrationClass->pdo = $migration->pdo;
            
            echo "Running migration: $className\n";
            $migrationClass->up();
            
            // Record the migration
            $stmt = $migration->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            $stmt->execute([$fileName, $batch]);
            echo "Migration completed: $className\n";
        }
    }
    
    echo "All migrations completed successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 