<?php
define('DB_HOST', 'localhost');//host
define('DB_USER', 'root');//user
define('DB_PASSWORD', 'coderslab');//password
define('DB_DB', 'twitter');//database name


try {
    $conn = new PDO("mysql:host=" . DB_HOST. ";dbname=" . DB_DB . ";charset=utf8", DB_USER, DB_PASSWORD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e){//catches exceptions and displays it
    echo $e->getMessage();
}