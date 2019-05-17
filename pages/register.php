<?php
//this part is available only for not logged
echo 'register';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../src/User.php';

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    if (strlen($username) > 0) {
        if (strlen($email) > 0) {
            if ($password == $password2 && strlen($password) > 0) {
                //her you register a new user
                $user = new User();
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setHashPass($password);
                $user->saveToDB($conn);


            } else {
                echo 'Password is empty or one passwords are not the same';
            }

        } else {
            echo 'Your email is empty';
        }

    } else {
        echo 'Your username is empty';
    }
}
?>

<form action="" method="post">
    <input type="text" name="username" placeholder="Nazwa uzytkownika">
    <br>
    <input type="email" name="email" placeholder="Email użytkownika">
    <br>
    <input type="password" name="password" placeholder="Hasło">
    <br>
    <input type="password" name="password2" placeholder="Powtórz Hasło">
    <br>
    <br>
    <button>Rejestruj</button>
</form>
