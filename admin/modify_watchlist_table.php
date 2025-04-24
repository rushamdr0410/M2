<?php
include('user_auth.php');

// Drop the existing foreign key constraint
$drop_fk_sql = "ALTER TABLE watchlist DROP FOREIGN KEY watchlist_ibfk_2";
if (mysqli_query($connection, $drop_fk_sql)) {
    echo "Foreign key constraint removed successfully<br>";
} else {
    echo "Error removing foreign key constraint: " . mysqli_error($connection) . "<br>";
}

// Modify the movie_id column to accept string values
$modify_column_sql = "ALTER TABLE watchlist MODIFY COLUMN movie_id VARCHAR(255)";
if (mysqli_query($connection, $modify_column_sql)) {
    echo "Column modified successfully<br>";
} else {
    echo "Error modifying column: " . mysqli_error($connection) . "<br>";
}

echo "Database structure update completed.";
?> 