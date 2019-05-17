<?php
//this site is available for not logged
echo 'log in';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once __DIR__ . '/../src/User.php';
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    //search for user by email in db
    $user = User::loadUserByEmail($conn,$email); //returns object or null
    //if exist we check password
    if (!is_null($user)) {
        if (password_verify($password, $user->getHashPass())) {
            $_SESSION['isLogged'] = true;
            $_SESSION['user'] = ['id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail()];
            ob_end_clean();
            header('Location: index.php');
        }
    }


}
?>

<form action="" method="post">
    <input type="email" name="email" placeholder="User Email">
    <br>
    <input type="password" name="password" placeholder="Password">
    <br>
    <br>
    <button>Log In</button>
</form>


