<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';
include __DIR__ . '/db.php';
$userSession = $_SESSION['user'];
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
    <a href="index.php?page=login">Login</a>
    <a href="index.php?page=register">Register</a>
    <a href="index.php">Main Page</a>
    <?php if (isLogged()) {

        echo '
    <a href="index.php?page=allUserTweets&id=' . $userSession['id'] . '">My Tweets!</a>
    <a href="index.php?page=messageReceive&id=' . $userSession['id'] . '">Message Received</a>
    <a href="index.php?page=messageSend&id=' . $userSession['id'] . '">Message Send</a>';
    }
    ?>

</div>
<?php
//our pages are set by GET parameter
// for example: index.php?page=login etc

if (isset($_GET['page']) && file_exists(__DIR__ . '/pages/' . $_GET['page'] . '.php') && strpos('..', $_GET['page']) === false) {
    include __DIR__ . '/pages/' . $_GET['page'] . '.php';
} else {
    include __DIR__ . '/pages/main.php';
}
?>
</body>
</html>