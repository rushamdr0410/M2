<?php
require_once 'config.php';
require_once 'migrations/MigrationManager.php';
require_once 'Migration.php';
require_once '2024_04_25_000001_create_register_table.php';
require_once '2024_04_25_000002_create_watch_history_table.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $migrationManager = new MigrationManager($pdo, __DIR__ . '/migrations');
    
    // Create migrations table if it doesn't exist
    $migration = new Migration();
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

    // Run pending migrations
    $migrations = [
        'CreateRegisterTable',
        'CreateWatchHistoryTable'
    ];

    $batch = time();
    if (isset($argv[1]) && $argv[1] === 'rollback') {
        $steps = isset($argv[2]) ? (int)$argv[2] : 1;
        $migrationManager->rollbackMigrations($steps);
    } else {
        $migrationManager->runMigrations();
    }
    
    echo "Migrations completed successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 