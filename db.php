<?php
define('DB_HOST', 'localhost');//host
define('DB_USER', 'root');//uzytkownik
define('DB_PASSWORD', 'coderslab');//haslo
define('DB_DB', 'twitter');//nazwa bazy


try {
    $conn = new PDO("mysql:host=" . DB_HOST. ";dbname=" . DB_DB . ";charset=utf8", DB_USER, DB_PASSWORD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e){//lapie wyjatek i go odpowiednio wyswietla
    echo $e->getMessage();
}