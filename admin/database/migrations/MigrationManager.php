<?php
class MigrationManager {
    private $pdo;
    private $migrationsDir;

    public function __construct(PDO $pdo, string $migrationsDir) {
        $this->pdo = $pdo;
        $this->migrationsDir = $migrationsDir;
        $this->createMigrationsTable();
    }

    private function createMigrationsTable() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function runMigrations() {
        $files = glob($this->migrationsDir . '/*.php');
        $batch = $this->getNextBatchNumber();
        $executedMigrations = $this->getExecutedMigrations();

        foreach ($files as $file) {
            $migrationName = basename($file, '.php');
            
            if (!in_array($migrationName, $executedMigrations)) {
                require_once $file;
                $className = $this->getClassNameFromFileName($migrationName);
                
                if (class_exists($className)) {
                    $migration = new $className($this->pdo);
                    $migration->up();
                    
                    $stmt = $this->pdo->prepare("
                        INSERT INTO migrations (migration, batch) 
                        VALUES (?, ?)
                    ");
                    $stmt->execute([$migrationName, $batch]);
                    
                    echo "Migrated: $migrationName\n";
                }
            }
        }
    }

    public function rollbackMigrations($steps = 1) {
        $batch = $this->getLastBatchNumber();
        $migrations = $this->getMigrationsByBatch($batch);
        
        foreach ($migrations as $migration) {
            $file = $this->migrationsDir . '/' . $migration . '.php';
            if (file_exists($file)) {
                require_once $file;
                $className = $this->getClassNameFromFileName($migration);
                
                if (class_exists($className)) {
                    $migration = new $className($this->pdo);
                    $migration->down();
                    
                    $stmt = $this->pdo->prepare("
                        DELETE FROM migrations 
                        WHERE migration = ?
                    ");
                    $stmt->execute([$migration]);
                    
                    echo "Rolled back: $migration\n";
                }
            }
        }
    }

    private function getNextBatchNumber() {
        $stmt = $this->pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['max_batch'] ?? 0) + 1;
    }

    private function getLastBatchNumber() {
        $stmt = $this->pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_batch'] ?? 0;
    }

    private function getExecutedMigrations() {
        $stmt = $this->pdo->query("SELECT migration FROM migrations");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getMigrationsByBatch($batch) {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations WHERE batch = ?");
        $stmt->execute([$batch]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getClassNameFromFileName($fileName) {
        return str_replace('_', '', ucwords($fileName, '_'));
    }
} 