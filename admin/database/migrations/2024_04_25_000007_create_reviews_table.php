<?php

class CreateReviewsTable extends Migration {
    public function up() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS reviews (
                id int(11) NOT NULL AUTO_INCREMENT,
                movie_id int(11) NOT NULL,
                user_id int(11) NOT NULL,
                review_text text NOT NULL,
                likes int(11) DEFAULT 0,
                created_at timestamp NOT NULL DEFAULT current_timestamp(),
                rating tinyint(4) NOT NULL COMMENT 'Rating between 1-5',
                review_date timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (id),
                KEY video_id (movie_id),
                KEY user_id (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down() {
        $this->pdo->exec("DROP TABLE IF EXISTS reviews");
    }
} 