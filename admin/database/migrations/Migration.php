<?php
abstract class Migration {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    abstract public function up();
    abstract public function down();
} 