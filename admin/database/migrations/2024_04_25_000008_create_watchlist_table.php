<?php

class CreateWatchlistTable extends Migration {
    public function up() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS watchlist (
                id int(11) NOT NULL AUTO_INCREMENT,
                user_id int(11) NOT NULL,
                tmdb_id varchar(255) NOT NULL,
                media_type enum('movie','tv') NOT NULL,
                date_added timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (id),
                UNIQUE KEY unique_user_movie (user_id,tmdb_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down() {
        $this->pdo->exec("DROP TABLE IF EXISTS watchlist");
    }
} 