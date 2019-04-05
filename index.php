<?php
session_start();
require_once __DIR__ . '/functions.php';
include __DIR__ . '/db.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter</title>
</head>
<body>
<div>
    <a href="index.php?page=login">Logowanie</a>
    <a href="index.php?page=register">Rejestracja</a>
    <a href="index.php">Strona Główna</a>
</div>
<?php
//zakladamy, ze strony sa wybierane przez parametr GET -> page
// np. index.php?page=login etc

if(isset($_GET['page']) && file_exists(__DIR__ . '/pages/' . $_GET['page'] . '.php') && strpos('..',$_GET['page']) === false){
    include __DIR__ . '/pages/' . $_GET['page'] . '.php';
} else {
    include __DIR__ . '/pages/main.php';
}
?>
</body>
</html>