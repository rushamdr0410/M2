<?php

require_once __DIR__ . '/dbconfig.php';

class Migration {
    protected $pdo;

    public function __construct() {
        global $server_name, $db_username, $db_password, $db_name;

        try {
            $this->pdo = new PDO(
                "mysql:host=$server_name;dbname=$db_name",
                $db_username,
                $db_password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
} 