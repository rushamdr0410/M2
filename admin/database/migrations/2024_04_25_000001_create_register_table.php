<?php

class CreateRegisterTable extends Migration {
    public function up() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS register (
                id int(11) NOT NULL AUTO_INCREMENT,
                username varchar(50) DEFAULT NULL,
                email varchar(150) DEFAULT NULL,
                password varchar(60) DEFAULT NULL,
                usertype varchar(20) NOT NULL,
                reset_token varchar(100) DEFAULT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down() {
        $this->pdo->exec("DROP TABLE IF EXISTS register");
    }
} 