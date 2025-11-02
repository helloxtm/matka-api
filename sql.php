<?php
require('database/config.php');

try {
    // SQL statement to create 'market_list' table
    $sql1 = "CREATE TABLE IF NOT EXISTS market_list (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) DEFAULT NULL UNIQUE,
        open_time TIME,
        close_time TIME,
        status ENUM('0', '1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
    )";

    // SQL statement to create 'market_result' table
    $sql2 = "CREATE TABLE IF NOT EXISTS market_result (
        id INT AUTO_INCREMENT PRIMARY KEY,
        game_id INT,
        open_patti VARCHAR(3),
        open_sd VARCHAR(1),
        open_update_time TIME,
        close_patti VARCHAR(3),
        close_sd VARCHAR(1),
        close_update_time TIME,
        jodi VARCHAR(5),
        date DATE,
        UNIQUE (game_id, date),
        FOREIGN KEY (game_id) REFERENCES market_list(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";

    // SQL statement to create 'satta_list' table
    $sql3 = "CREATE TABLE IF NOT EXISTS satta_list (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(50) DEFAULT NULL UNIQUE, 
        result_time TIME DEFAULT NULL,
        status ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT '1=on, 0=off'
    )";

    // SQL statement to create 'satta_result' table
    $sql4 = "CREATE TABLE IF NOT EXISTS satta_result (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        game_id INT NOT NULL, 
        result VARCHAR(2) DEFAULT NULL,
        date DATE DEFAULT NULL,
        update_time TIME DEFAULT NULL,
        UNIQUE (game_id, date),
        FOREIGN KEY (game_id) REFERENCES satta_list(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";

    // Execute all SQL statement's
    $db->exec($sql1);
    $db->exec($sql2);
    $db->exec($sql3);
    $db->exec($sql4);

    echo "Tables created successfully.";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>