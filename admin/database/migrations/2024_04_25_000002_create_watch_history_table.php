<?php

class CreateWatchHistoryTable extends Migration {
    public function up() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS watch_history (
                id int(11) NOT NULL AUTO_INCREMENT,
                user_id int(11) NOT NULL,
                movie_id int(11) NOT NULL,
                media_type varchar(10) NOT NULL,
                watch_date datetime NOT NULL,
                PRIMARY KEY (id),
                KEY user_id (user_id,movie_id),
                KEY watch_date (watch_date)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down() {
        $this->pdo->exec("DROP TABLE IF EXISTS watch_history");
    }
} 